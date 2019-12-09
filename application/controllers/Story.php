<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 첫 페이지 클래스
class Story extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('security', 'url', 'my_array_helper'));
    $this->load->library(array('image_lib', 'session'));
    $this->load->model(array('club_model', 'file_model', 'reserve_model', 'story_model'));
  }

  /**
   * 스토리 메인 페이지
   *
   * @return json
   * @author bjchoi
   **/
  public function index($clubIdx)
  {
    $clubIdx = html_escape($clubIdx);
    $storyIdx = html_escape($this->input->post('n'));
    $page = html_escape($this->input->post('p'));
    $viewData['userData'] = $this->session->userData;
    $result = '';

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    if (!empty($storyIdx)) {
      $viewData['listStory'] = $this->story_model->viewStory($clubIdx, $storyIdx);
    } else {
      $paging['perPage'] = 5;
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
  public function view($clubIdx)
  {
    $clubIdx = html_escape($clubIdx);
    $storyIdx = html_escape($this->input->get('n'));

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    $viewData['listStory'] = $this->story_model->viewStory($clubIdx, $storyIdx);
    $viewData['viewStory'] = $this->load->view('story/index', $viewData, true);
    /*
    $clubIdx = html_escape($clubIdx);
    $userIdx = !empty($this->session->userData['idx']) ? html_escape($this->session->userData['idx']) : NULL;
    $storyIdx = html_escape($this->input->get('n'));

    // 스토리 보기
    $viewData['viewStory'] = $this->story_model->viewStory($clubIdx, $storyIdx);

    // 스토리 좋아요 확인 (로그인시에만)
    if (!empty($userIdx)) {
      $viewStoryReaction = $this->story_model->viewStoryReaction($clubIdx, $storyIdx, $userIdx);
      if ($viewData['viewStory']['idx'] == $viewStoryReaction['story_idx']) {
        $viewData['viewStory']['like'] = $viewStoryReaction['created_by'];
      }
    }
*/
    $this->_viewPage('story/view', $viewData);
  }

  /**
   * 스토리 등록
   *
   * @return json
   * @author bjchoi
   **/
  public function insert($clubIdx)
  {
    $now = time();
    $userIdx = $this->session->userData['idx'];
    $inputData = $this->input->post();
    $inputData['photo'] = html_escape($inputData['photo']);
    $inputData['page'] = html_escape($inputData['page']);

    if (empty($userIdx)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_login'));
    } else {
      $insertValues = array(
        'club_idx'    => html_escape($clubIdx),
        'content'     => html_escape($inputData['content']),
        'created_by'  => html_escape($userIdx),
        'created_at'  => $now
      );

      $idx = $this->story_model->insertStory($insertValues);

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
            $config['width'] = 650;
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
  public function reply($clubIdx)
  {
    $clubIdx = html_escape($clubIdx);
    $storyIdx = html_escape($this->input->post('storyIdx'));
    $replyType = html_escape($this->input->post('replyType'));
    $userData = $this->session->userData;
    $message = '';

    // 댓글 목록
    $reply = $this->story_model->listStoryReply($clubIdx, $storyIdx, $replyType);

    foreach ($reply as $value) {
      if ($userData['idx'] == $value['created_by'] || $userData['admin'] == 1) {
        $delete = '| <a href="javascript:;" class="btn-post-delete-modal" data-idx="' . $value['idx'] . '" data-action="delete_reply">삭제</a>';
      } else {
        $delete = '';
      }
      $message .= '<dl><dt><img class="img-profile" src="/public/photos/' . $value['created_by'] . '"> ' . $value['nickname'] . '</dt><dd>' . $value['content'] . '<span class="reply-date">' . calcStoryTime($value['created_at']) . '</span> ' . $delete . '</dd></dl>';
    }

    $result = array('error' => 0, 'message' => $message);

    $this->output->set_output(json_encode($result));
  }

  /**
   * 스토리 댓글 등록
   *
   * @return json
   * @author bjchoi
   **/
  public function insert_reply($clubIdx)
  {
    $now = time();
    $result = array('error' => 0);
    $userData = $this->session->userData;
    $clubIdx = html_escape($clubIdx);
    $storyIdx = html_escape($this->input->post('storyIdx'));
    $replyType = html_escape($this->input->post('replyType'));
    $content = html_escape($this->input->post('content'));

    if (empty($userData['idx'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_login'));
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

      if (empty($rtn)) {
        $result = array(
          'error' => 0,
          'content' => $this->lang->line('error_all')
        );
      } else {
        // 스토리 댓글 개수 올리기
        $cntStoryReply = $this->story_model->cntStoryReply($clubIdx, $storyIdx, $replyType);

        if ($replyType == REPLY_TYPE_STORY) {
          $updateData['reply_cnt'] = $cntStoryReply['cnt'];
          $this->story_model->updateStory($updateData, $clubIdx, $storyIdx);
        }

        $html = '<dl><dt><img class="img-profile" src="' . base_url() . '/public/photos/' . $userData['idx'] . '"> ' . $userData['nickname'] . '</dt><dd>' . $content . '<span class="reply-date">(' . calcStoryTime($now) . ')</span> | <a href="javascript:;" class="btn-post-delete-modal" data-idx="' . $rtn . '" data-action="delete_reply">삭제</a></dd></dl>';

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
  public function like($clubIdx)
  {
    $now = time();
    $userIdx = $this->session->userData['idx'];
    $clubIdx = html_escape($clubIdx);
    $storyIdx = html_escape($this->input->post('storyIdx'));
    $reactionType = html_escape($this->input->post('reactionType'));

    if (empty($userIdx)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_login'));
    } else {
      $cntStoryReaction = $this->story_model->cntStoryReaction($clubIdx, $storyIdx, $reactionType, REACTION_KIND_LIKE);
      $viewStoryReaction = $this->story_model->viewStoryReaction($clubIdx, $storyIdx, $reactionType, $userIdx);
      $result = array();

      if (!empty($viewStoryReaction['reaction_kind']) && $viewStoryReaction['reaction_kind'] == REACTION_KIND_LIKE) {
        // 데이터가 있으면 삭제
        $rtn = $this->story_model->deleteStoryReaction($clubIdx, $storyIdx, $reactionType, $userIdx);

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
        $this->story_model->updateStory($updateData, $clubIdx, $storyIdx);
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
  public function share($clubIdx)
  {
    $now = time();
    $userIdx = $this->session->userData['idx'];
    $clubIdx = html_escape($clubIdx);
    $storyIdx = html_escape($this->input->post('storyIdx'));
    $reactionType = html_escape($this->input->post('reactionType'));
    $shareType = html_escape($this->input->post('shareType'));
    $result = array('error' => 1, 'message' => $this->lang->line('error_login'));

    if (!empty($userIdx)) {
      $viewStoryReaction = $this->story_model->viewStoryReaction($clubIdx, $storyIdx, $reactionType, $userIdx, $shareType);

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
          $cntStoryReaction = $this->story_model->cntStoryReaction($clubIdx, $storyIdx, $reactionType, REACTION_KIND_SHARE);

          if ($reactionType == REACTION_TYPE_STORY) {
            $updateData['share_cnt'] = $cntStoryReaction['cnt'];
            $this->story_model->updateStory($updateData, $clubIdx, $storyIdx);
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
  public function delete($clubIdx)
  {
    $now = time();
    $userIdx = $this->session->userData['idx'];
    $clubIdx = html_escape($clubIdx);
    $storyIdx = html_escape($this->input->post('idx'));
    $result = array('error' => 1, 'message' => $this->lang->line('error_delete'));

    if (empty($userIdx)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_login'));
    } else {
      // 해당 글을 작성한 사람이 맞는치 확인 (관리자는 모두 삭제 가능)
      $viewStory = $this->story_model->viewStory($clubIdx, $storyIdx);
      $viewMember = $this->member_model->viewMember($clubIdx, $userIdx);

      if (!empty($viewStory[0]['user_idx']) && ($viewStory[0]['user_idx'] == $userIdx || $viewMember['admin'] == 1)) {
        // DB는 삭제 플래그만 세워줌
        $updateData = array(
          'deleted_by' => $userIdx,
          'deleted_at' => $now
        );

        $rtn = $this->story_model->updateStory($updateData, $clubIdx, $storyIdx);

        if (!empty($rtn)) {
          // 댓글 삭제 플래그 세우기
          $this->story_model->updateStoryReply($updateData, $clubIdx, $storyIdx);

          // 화상 데이터는 삭제
          $files = $this->file_model->getFile('story', $storyIdx);

          foreach ($files as $value) {
            if (file_exists(PHOTO_PATH . $value['filename'])) unlink(PHOTO_PATH . $value['filename']);
            $this->file_model->deleteFile($value['filename']);
          }

          $result = array('error' => 0);
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
  public function delete_reply($clubIdx)
  {
    $now = time();
    $userIdx = $this->session->userData['idx'];
    $clubIdx = html_escape($clubIdx);
    $storyReplyIdx = html_escape($this->input->post('idx'));
    $result = array('error' => 1, 'message' => $this->lang->line('error_delete'));

    if (empty($userIdx))  {
      $result = array('error' => 1, 'message' => $this->lang->line('error_login'));
    } else {
      // 해당 글을 작성한 사람이 맞는치 확인 (관리자는 모두 삭제 가능)
      $viewStoryReply = $this->story_model->viewStoryReply($clubIdx, $storyReplyIdx);
      $viewMember = $this->member_model->viewMember($clubIdx, $userIdx);

      if (!empty($viewStoryReply['created_by']) && ($viewStoryReply['created_by'] == $userIdx || $viewMember['admin'] == 1)) {
        // 삭제는 삭제 플래그만 세워줌
        $updateData = array(
          'deleted_by' => $userIdx,
          'deleted_at' => $now
        );

        $rtn = $this->story_model->updateStoryReply($updateData, $clubIdx, $viewStoryReply['story_idx'], $viewStoryReply['reply_type'], $storyReplyIdx);

        if (!empty($rtn)) {
          $cntStoryReply = $this->story_model->cntStoryReply($clubIdx, $viewStoryReply['story_idx'], $viewStoryReply['reply_type']);
          $updateData = array(
            'reply_cnt' => $cntStoryReply['cnt']
          );
          $this->story_model->updateStory($updateData, $clubIdx, $viewStoryReply['story_idx']);
          $result = array('error' => 0, 'message' => $updateData['reply_cnt']);
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
  public function delete_photo($clubIdx)
  {
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
        // 사진 사이즈 줄이기 (가로가 사이즈가 1024보다 클 경우)
        $size = getImageSize(UPLOAD_PATH . $filename);
        if ($size[0] >= 1024) {
          $this->image_lib->clear();
          $config['image_library'] = 'gd2';
          $config['source_image'] = UPLOAD_PATH . $filename;
          //$config['new_image'] = UPLOAD_PATH . 'thumb_' . $filename;
          $config['maintain_ratio'] = TRUE;
          $config['width'] = 1024;
          $this->image_lib->initialize($config);
          $this->image_lib->resize();
        }

        $result = array(
          'error' => 0,
          'message' => base_url() . UPLOAD_URL . $filename,
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
    $viewData['listNotice'] = $this->reserve_model->listNotice($viewData['view']['idx'], array(STATUS_PLAN, STATUS_ABLE, STATUS_CONFIRM));

    // 회원수
    $viewData['view']['cntMember'] = $this->member_model->cntMember($viewData['view']['idx']);
    $viewData['view']['cntMemberToday'] = $this->member_model->cntMemberToday($viewData['view']['idx']);

    // 방문자수
    $viewData['view']['cntVisitor'] = $this->member_model->cntVisitor($viewData['view']['idx']);
    $viewData['view']['cntVisitorToday'] = $this->member_model->cntVisitorToday($viewData['view']['idx']);

    // 클럽 대표이미지
    $files = $this->file_model->getFile('club', $viewData['view']['idx']);

    if (empty($files)) {
      $viewData['view']['photo'][0] = 'noimage.png';
    } else {
      foreach ($files as $key => $value) {
        if (!empty($value['filename'])) {
          $viewData['view']['photo'][$key] = $value['filename'];
        } else {
          $viewData['view']['photo'][$key] = 'noimage.png';
        }
      }
    }

    // 방문자 기록
    setVisitor();

    $this->load->view('header', $viewData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('footer', $viewData);
  }
}
?>
