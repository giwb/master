<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 첫 페이지 클래스
class Story extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('cookie', 'security', 'url', 'my_array_helper'));
    $this->load->library(array('image_lib', 'session'));
    $this->load->model(array('club_model', 'file_model', 'reserve_model', 'story_model'));
  }

  /**
   * 스토리 메인 페이지
   *
   * @return json
   * @author bjchoi
   **/
  public function index()
  {
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $storyIdx = html_escape($this->input->post('n'));
    $page = html_escape($this->input->post('p'));
    $viewData['userData'] = $this->session->userData;
    $result = '';

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    if (!empty($storyIdx)) {
      $viewData['listStory'] = $this->story_model->viewStory($storyIdx);
    } else {
      $paging['perPage'] = 10;
      $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];
      $viewData['listStory'] = $this->story_model->listStory($clubIdx, $paging);
    }

    if (!empty($viewData['listStory'])) {
      $result = $this->load->view('story/index', $viewData, true);
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 스토리 개별 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function view()
  {
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');
    $viewData['storyIdx'] = html_escape($this->input->get('n'));
    $viewData['userData'] = $this->session->userData;

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($viewData['clubIdx']);

    // 최초 스토리 출력
    $viewData['listStory'] = $this->story_model->viewStory($viewData['storyIdx']);
    $viewData['viewStory'] = $this->load->view('story/index', $viewData, true);

    $this->_viewPage('story/view', $viewData);
  }

  /**
   * 스토리 수정
   *
   * @return view
   * @author bjchoi
   **/
  public function edit()
  {
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');
    $viewData['storyIdx'] = html_escape($this->input->get('n'));
    $viewData['userData'] = $this->session->userData;

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($viewData['clubIdx']);

    // 최초 스토리 출력
    $viewData['listStory'] = $this->story_model->viewStory($viewData['storyIdx']);

    if ($viewData['userData']['idx'] != $viewData['listStory'][0]['created_by'] && $viewData['userData']['admin'] != 1) {
      redirect(BASE_URL . '/story/view/' . $viewData['clubIdx'] . '?n=' . $viewData['storyIdx']);
    } else {
      $viewData['viewStory'] = $this->load->view('story/index', $viewData, true);
      $this->_viewPage('story/edit', $viewData);
    }
  }

  /**
   * 스토리 등록
   *
   * @return json
   * @author bjchoi
   **/
  public function insert()
  {
    $now = time();
    $userIdx = $this->session->userData['idx'];
    $inputData = $this->input->post();
    $clubIdx = html_escape($inputData['clubIdx']);
    $inputData['photo'] = html_escape($inputData['photo']);
    $inputData['page'] = html_escape($inputData['page']);
    $idx = !empty($inputData['idx']) ? html_escape($inputData['idx']) : NULL;

    if (empty($userIdx)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_login'));
    } else {
      if (!empty($idx)) {
        $updateValues = array(
          'content'     => html_escape($inputData['content']),
          'updated_by'  => html_escape($userIdx),
          'updated_at'  => $now
        );
        $this->story_model->updateStory($updateValues, $idx);
      } else {
        $insertValues = array(
          'club_idx'    => html_escape($clubIdx),
          'content'     => html_escape($inputData['content']),
          'created_by'  => html_escape($userIdx),
          'created_at'  => $now
        );
        $idx = $this->story_model->insertStory($insertValues);
      }

      if (empty($idx)) {
        $result = array('error' => 1, 'message' => $this->lang->line('error_insert'));
      } else {
        // 파일 등록
        //foreach ($files as $value) {
          // 업로드 된 파일이 있을 경우에만 등록 후 이동
          if (!empty($inputData['photo']) && file_exists(UPLOAD_PATH . $inputData['photo'])) {
            $file_values = array(
              'page' => $inputData['page'],
              'page_idx' => $idx,
              'filename' => $inputData['photo'],
              'created_at' => $now
            );
            $this->file_model->insertFile($file_values);

            // 파일 이동
            rename(UPLOAD_PATH . $inputData['photo'], PHOTO_PATH . $inputData['photo']);

            // 썸네일 만들기
            $this->image_lib->clear();
            $config['image_library'] = 'gd2';
            $config['source_image'] = PHOTO_PATH . $inputData['photo'];
            $config['new_image'] = PHOTO_PATH . 'thumb_' . $inputData['photo'];
            $config['create_thumb'] = TRUE;
            $config['maintain_ratio'] = TRUE;
            $config['thumb_marker'] = '';
            $config['width'] = 592;
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
          }
        //}

        $result = array('error' => 0, 'message' => '');
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 스토리 댓글
   *
   * @return json
   * @author bjchoi
   **/
  public function reply()
  {
    $storyIdx = html_escape($this->input->post('storyIdx'));
    $replyType = html_escape($this->input->post('replyType'));
    $userData = $this->session->userData;
    $message = '';

    // 댓글 목록
    $reply = $this->story_model->listStoryReply($storyIdx, $replyType);

    foreach ($reply as $value) {
      if ($userData['idx'] == $value['created_by'] || $userData['admin'] == 1) {
        $delete = ' <a href="javascript:;" class="btn-reply-update" data-idx="' . $value['idx'] . '">[수정]</a> <a href="javascript:;" class="btn-post-delete-modal" data-idx="' . $value['idx'] . '" data-action="delete_reply">[삭제]</a>';
      } else {
        $delete = '';
      }
      if (!empty($value['updated_at'])) {
        $date = calcStoryTime($value['created_at']) . ' 작성, ' . calcStoryTime($value['updated_at']) . ' 수정';
      } else {
        $date = calcStoryTime($value['created_at']);
      }
      if (file_exists(PHOTO_PATH . $value['created_by'])) {
        $size = getImageSize(PHOTO_PATH . $value['created_by']);
        $value['photo'] = PHOTO_URL . $value['created_by'];
        $value['photo_width'] = $size[0];
        $value['photo_height'] = $size[1];
      } else {
        $value['photo'] = '/public/images/user.png';
        $value['photo_width'] = 64;
        $value['photo_height'] = 64;
      }
      $message .= '<dl class="story-reply-item" data-idx="' . $value['idx'] . '"><dt><img class="img-profile photo-zoom" src="' . $value['photo'] . '" data-filename="' . $value['photo'] . '" data-width="' . $value['photo_width'] . '" data-height="' . $value['photo_height'] . '"></dt><dd><strong>' . $value['nickname'] . '</strong> · <span class="reply-date">' . $date . $delete . '</span><div class="reply-content" data-idx="' . $value['idx'] . '">' . $value['content'] . '</div></dd></dl>';
    }

    $result = array('error' => 0, 'message' => $message);

    $this->output->set_output(json_encode($result));
  }

  /**
   * 스토리 댓글 등록/수정
   *
   * @return json
   * @author bjchoi
   **/
  public function insert_reply()
  {
    $now = time();
    $result = array('error' => 0);
    $userData = $this->session->userData;
    $clubIdx = html_escape($this->input->post('clubIdx'));
    $storyIdx = html_escape($this->input->post('storyIdx'));
    $replyType = html_escape($this->input->post('replyType'));
    $replyIdx = html_escape($this->input->post('replyIdx'));
    $content = html_escape($this->input->post('content'));

    if (empty($userData['idx'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_login'));
    } else {
      if (!empty($replyIdx)) {
        $updateValues = array(
          'content' => $content,
          'updated_by' => $userData['idx'],
          'updated_at' => $now
        );
        $this->story_model->updateStoryReply($updateValues, $storyIdx, $replyType, $replyIdx);

        // 기존 정보 불러오기
        $viewStoryReply = $this->story_model->viewStoryReply($replyIdx);
        $userData['idx'] = $viewStoryReply['created_by'];
        $userData['nickname'] = $viewStoryReply['nickname'];
        $rtn = $replyIdx;

        // 스토리 댓글 개수
        $cntStoryReply = $this->story_model->cntStoryReply($storyIdx, $replyType);
      } else {
        $insertValues = array(
          'club_idx' => $clubIdx,
          'story_idx' => $storyIdx,
          'reply_type' => $replyType,
          'content' => $content,
          'created_by' => $userData['idx'],
          'created_at' => $now
        );
        $rtn = $this->story_model->insertStoryReply($insertValues);

        // 스토리 댓글 개수 올리기
        $cntStoryReply = $this->story_model->cntStoryReply($storyIdx, $replyType);

        if ($replyType == REPLY_TYPE_STORY) {
          $updateData['reply_cnt'] = $cntStoryReply['cnt'];
          $this->story_model->updateStory($updateData, $storyIdx);
        }
      }

      if (empty($rtn)) {
        $result = array(
          'error' => 0,
          'content' => $this->lang->line('error_all')
        );
      } else {
        if (file_exists(PHOTO_PATH . $userData['idx'])) {
          $size = getImageSize(PHOTO_PATH . $userData['idx']);
          $photo = PHOTO_URL . $userData['idx'];
          $photo_width = $size[0];
          $photo_height = $size[1];
        } else {
          $photo = '/public/images/user.png';
          $photo_width = 64;
          $photo_height = 64;
        }

        $html = '<dl class="story-reply-item" data-idx="' . $rtn . '"><dt><img class="img-profile photo-zoom" src="' . $photo . '" data-filename="' . $photo . '" data-width="' . $photo_width . '" data-height="' . $photo_height . '"></dt><dd><strong>' . $userData['nickname'] . '</strong> · <span class="reply-date">(' . calcStoryTime($now) . ') <a href="javascript:;" class="btn-reply-update" data-idx="' . $rtn . '">[수정]</a> <a href="javascript:;" class="btn-post-delete-modal" data-idx="' . $rtn . '" data-action="delete_reply">[삭제]</a></span><div class="reply-content" data-idx="' . $rtn . '">' . $content . '</div></dd></dl>';

        $result = array(
          'error' => 0,
          'message' => $html,
          'reply_cnt' => $cntStoryReply['cnt']
        );
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 좋아요 추가/삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function like()
  {
    $now = time();
    $userIdx = $this->session->userData['idx'];
    $clubIdx = html_escape($this->input->post('clubIdx'));
    $storyIdx = html_escape($this->input->post('storyIdx'));
    $reactionType = html_escape($this->input->post('reactionType'));

    if (empty($userIdx)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_login'));
    } else {
      $cntStoryReaction = $this->story_model->cntStoryReaction($storyIdx, $reactionType, REACTION_KIND_LIKE);
      $viewStoryReaction = $this->story_model->viewStoryReaction($storyIdx, $reactionType, $userIdx);
      $result = array();

      if (!empty($viewStoryReaction['reaction_kind']) && $viewStoryReaction['reaction_kind'] == REACTION_KIND_LIKE) {
        // 데이터가 있으면 삭제
        $rtn = $this->story_model->deleteStoryReaction($storyIdx, $userIdx, $reactionType);

        if (!empty($rtn)) {
          $updateData['like_cnt'] = $cntStoryReaction['cnt'] - 1;
          $result = array('type' => 0, 'count' => $updateData['like_cnt']);
        }
      } else {
        // 데이터가 없으면 추가
        $insertData = array(
          'club_idx' => $clubIdx,
          'story_idx' => $storyIdx,
          'reaction_type' => $reactionType,
          'reaction_kind' => REACTION_KIND_LIKE,
          'created_by' => $userIdx,
          'created_at' => $now
        );
        $rtn = $this->story_model->insertStoryReaction($insertData);

        if (!empty($rtn)) {
          $updateData['like_cnt'] = $cntStoryReaction['cnt'] + 1;
          $result = array('type' => 1, 'count' => $updateData['like_cnt']);
        }
      }

      if (!empty($rtn) && $reactionType == REACTION_TYPE_STORY) {
        $this->story_model->updateStory($updateData, $storyIdx);
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 공유
   *
   * @return json
   * @author bjchoi
   **/
  public function share()
  {
    $now = time();
    $userIdx = $this->session->userData['idx'];
    $clubIdx = html_escape($this->input->post('clubIdx'));
    $storyIdx = html_escape($this->input->post('storyIdx'));
    $reactionType = html_escape($this->input->post('reactionType'));
    $shareType = html_escape($this->input->post('shareType'));
    $result = array('error' => 1, 'message' => $this->lang->line('error_login'));

    if (!empty($userIdx)) {
      $viewStoryReaction = $this->story_model->viewStoryReaction($storyIdx, $reactionType, $userIdx, $shareType);

      if (!empty($viewStoryReaction)) {
        $result = array('error' => 1, 'message' => '');
      } else {
        $insertData = array(
          'club_idx' => $clubIdx,
          'story_idx' => $storyIdx,
          'reaction_type' => $reactionType,
          'reaction_kind' => REACTION_KIND_SHARE,
          'share_type' => $shareType,
          'created_by' => $userIdx,
          'created_at' => $now
        );
        $rtn = $this->story_model->insertStoryReaction($insertData);

        if (!empty($rtn)) {
          $cntStoryReaction = $this->story_model->cntStoryReaction($storyIdx, $reactionType, REACTION_KIND_SHARE);

          if ($reactionType == REACTION_TYPE_STORY) {
            $updateData['share_cnt'] = $cntStoryReaction['cnt'];
            $this->story_model->updateStory($updateData, $storyIdx);
          }

          $result = array('type' => 1, 'count' => $cntStoryReaction['cnt']);
        }
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 스토리 삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function delete()
  {
    $now = time();
    $userIdx = $this->session->userData['idx'];
    $storyIdx = html_escape($this->input->post('idx'));
    $result = array('error' => 1, 'message' => $this->lang->line('error_delete'));

    if (empty($userIdx)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_login'));
    } else {
      // 해당 글을 작성한 사람이 맞는치 확인 (관리자는 모두 삭제 가능)
      $viewStory = $this->story_model->viewStory($storyIdx);
      $viewMember = $this->member_model->viewMember($userIdx);

      if (!empty($viewStory[0]['user_idx']) && ($viewStory[0]['user_idx'] == $userIdx || $viewMember['admin'] == 1)) {
        // DB는 삭제 플래그만 세워줌
        $updateData = array(
          'deleted_by' => $userIdx,
          'deleted_at' => $now
        );

        $rtn = $this->story_model->updateStory($updateData, $storyIdx);

        if (!empty($rtn)) {
          // 댓글 삭제 플래그 세우기
          $this->story_model->updateStoryReply($updateData, $storyIdx, REPLY_TYPE_STORY);

          // 리액션 삭제
          $this->story_model->deleteStoryReaction($storyIdx, $userIdx);

          // 화상 데이터는 삭제
          $files = $this->file_model->getFile('story', $storyIdx);

          foreach ($files as $value) {
            if (file_exists(PHOTO_PATH . $value['filename'])) unlink(PHOTO_PATH . $value['filename']);
            $this->file_model->deleteFile($value['filename']);
          }

          $result = array('error' => 0, 'message' => '');
        }
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 스토리 댓글 삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function delete_reply()
  {
    $now = time();
    $userIdx = $this->session->userData['idx'];
    $storyReplyIdx = html_escape($this->input->post('idx'));
    $result = array('error' => 1, 'message' => $this->lang->line('error_delete'));

    if (empty($userIdx))  {
      $result = array('error' => 1, 'message' => $this->lang->line('error_login'));
    } else {
      // 해당 글을 작성한 사람이 맞는치 확인 (관리자는 모두 삭제 가능)
      $viewStoryReply = $this->story_model->viewStoryReply($storyReplyIdx);
      $viewMember = $this->member_model->viewMember($userIdx);

      if (!empty($viewStoryReply['created_by']) && ($viewStoryReply['created_by'] == $userIdx || $viewMember['admin'] == 1)) {
        // 삭제는 삭제 플래그만 세워줌
        $updateData = array(
          'deleted_by' => $userIdx,
          'deleted_at' => $now
        );

        $rtn = $this->story_model->updateStoryReply($updateData, $viewStoryReply['story_idx'], $viewStoryReply['reply_type'], $storyReplyIdx);

        if (!empty($rtn)) {
          $cntStoryReply = $this->story_model->cntStoryReply($viewStoryReply['story_idx'], $viewStoryReply['reply_type']);
          $updateData = array('reply_cnt' => $cntStoryReply['cnt']);
          $this->story_model->updateStory($updateData, $viewStoryReply['story_idx']);
          $result = array('error' => 0, 'message' => 'delete_reply', 'story_idx' => $viewStoryReply['story_idx'], 'reply_cnt' => $cntStoryReply['cnt']);
        }
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 사진 삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function delete_photo()
  {
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $filename = html_escape($this->input->post('photo'));
    $result = $this->file_model->deleteFile($filename);

    if (file_exists(UPLOAD_PATH . $filename)) {
      unlink(UPLOAD_PATH . $filename);
    }
    if (file_exists(UPLOAD_PATH . 'thumb_' . $filename)) {
      unlink(UPLOAD_PATH . 'thumb_' . $filename);
    }
    if (file_exists(PHOTO_PATH . $filename)) {
      unlink(PHOTO_PATH . $filename);
    }
    if (file_exists(PHOTO_PATH . 'thumb_' . $filename)) {
      unlink(PHOTO_PATH . 'thumb_' . $filename);
    }

    if ($result != '') {
      $result = array(
        'error' => 0,
        'message' => '삭제가 완료되었습니다.'
      );
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 파일 업로드
   *
   * @return json
   * @author bjchoi
   **/
  public function upload()
  {
    if ($_FILES['file_obj']['type'] == 'image/jpeg') {
      $filename = time() . mt_rand(10000, 99999) . ".jpg";

      if (move_uploaded_file($_FILES['file_obj']['tmp_name'], UPLOAD_PATH . $filename)) {
        // 사진 사이즈 줄이기 (세로 사이즈가 1024보다 클 경우)
        $size = getImageSize(UPLOAD_PATH . $filename);
        if ($size[1] >= 1024) {
          $this->image_lib->clear();
          $config['image_library'] = 'gd2';
          $config['source_image'] = UPLOAD_PATH . $filename;
          $config['maintain_ratio'] = TRUE;
          $config['height'] = 1024;
          $this->image_lib->initialize($config);
          $this->image_lib->resize();
        }

        $result = array(
          'error' => 0,
          'message' => UPLOAD_URL . $filename,
          'filename' => $filename
        );
      } else {
        $result = array(
          'error' => 1,
          'message' => '사진 업로드에 실패했습니다.'
        );
      }
    } else {
      $result = array(
        'error' => 1,
        'message' => 'jpg 형식의 사진만 업로드 할 수 있습니다.'
      );
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 페이지 표시
   *
   * @param $viewPage
   * @param $viewData
   * @return view
   * @author bjchoi
   **/
  private function _viewPage($viewPage, $viewData=NULL)
  {
    $viewData['uri'] = 'story';

    // 회원 정보
    $viewData['userData'] = $this->session->userData;
    $viewData['userLevel'] = memberLevel($viewData['userData']['rescount'], $viewData['userData']['penalty'], $viewData['userData']['level'], $viewData['userData']['admin']);

    // 진행 중 산행
    $viewData['listFooterNotice'] = $this->reserve_model->listNotice($viewData['view']['idx'], array(STATUS_ABLE, STATUS_CONFIRM));

    // 최신 댓글
    $paging['perPage'] = 5; $paging['nowPage'] = 0;
    $viewData['listFooterReply'] = $this->admin_model->listReply($viewData['view']['idx'], $paging);

    foreach ($viewData['listFooterReply'] as $key => $value) {
      if ($value['reply_type'] == REPLY_TYPE_STORY):  $viewData['listFooterReply'][$key]['url'] = BASE_URL . '/story/view/?n=' . $value['story_idx']; endif;
      if ($value['reply_type'] == REPLY_TYPE_NOTICE): $viewData['listFooterReply'][$key]['url'] = BASE_URL . '/reserve/?n=' . $value['story_idx']; endif;
      if ($value['reply_type'] == REPLY_TYPE_SHOP):   $viewData['listFooterReply'][$key]['url'] = BASE_URL . '/shop/item/' . $value['story_idx']; endif;
    }

    // 최신 사진첩
    $paging['perPage'] = 2; $paging['nowPage'] = 0;
    $viewData['listFooterAlbum'] = $this->club_model->listAlbum($viewData['view']['idx'], $paging);

    foreach ($viewData['listFooterAlbum'] as $key => $value) {
      $photo = $this->file_model->getFile('album', $value['idx'], NULL, 1);
      if (!empty($photo[0]['filename'])) {
        //$viewData['listAlbum'][$key]['photo'] = PHOTO_URL . 'thumb_' . $photo[0]['filename'];
        $viewData['listFooterAlbum'][$key]['photo'] = PHOTO_URL . $photo[0]['filename'];
      } else {
        $viewData['listFooterAlbum'][$key]['photo'] = '/public/images/noimage.png';
      }
    }

    // 클럽 대표이미지
    $files = $this->file_model->getFile('club', $viewData['view']['idx']);
    if (!empty($files[0]['filename']) && file_exists(PHOTO_PATH . $files[0]['filename'])) {
      $size = getImageSize(PHOTO_PATH . $files[0]['filename']);
      $viewData['view']['main_photo'] = PHOTO_URL . $files[0]['filename'];
      $viewData['view']['main_photo_width'] = $size[0];
      $viewData['view']['main_photo_height'] = $size[1];
    }

    // 로그인 쿠키 처리
    if (!empty(get_cookie('cookie_userid'))) {
      $viewData['cookieUserid'] = get_cookie('cookie_userid');
    } else {
      $viewData['cookieUserid'] = '';
    }
    if (!empty(get_cookie('cookie_passwd'))) {
      $viewData['cookiePasswd'] = get_cookie('cookie_passwd');
    } else {
      $viewData['cookiePasswd'] = '';
    }

    // 리다이렉트 URL 추출
    if ($_SERVER['SERVER_PORT'] == '80') $HTTP_HEADER = 'http://'; else $HTTP_HEADER = 'https://';
    $viewData['redirectUrl'] = $HTTP_HEADER . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    // 방문자 기록
    setVisitor();

    $this->load->view('club/header', $viewData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('club/footer', $viewData);
  }
}
?>
