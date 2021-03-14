<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 첫 페이지 클래스
class Travelog extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('cookie', 'security', 'url', 'my_array_helper'));
    $this->load->library(array('image_lib', 'session'));
    $this->load->model(array('club_model', 'file_model', 'story_model', 'travelog_model'));
  }

  /**
   * 여행기 메인 페이지
   *
   * @return json
   * @author bjchoi
   **/
  public function index()
  {
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $viewData['type'] = html_escape($this->input->get('type'));
    $viewData['userData'] = $this->session->userData;
    $result = '';
    $page = 1;

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    $paging['perPage'] = 10;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];
    $viewData['listTravelog'] = $this->travelog_model->listTravelog($clubIdx, $viewData['type'], $paging);

    $this->_viewPage('travelog/index', $viewData);
  }

  /**
   * 스토리 개별 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function view($idx=NULL)
  {
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $viewData['userData'] = $this->session->userData;
    $idx = html_escape($idx);

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 여행기 불러오기
    $viewData['viewTravelog'] = $this->travelog_model->viewTravelog($clubIdx, $idx);
    $viewData['type'] = $viewData['viewTravelog']['category'];

    $this->_viewPage('travelog/view', $viewData);
  }

  /**
   * 여행기 작성
   *
   * @return view
   * @author bjchoi
   **/
  public function post()
  {
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $viewData['userData'] = $this->session->userData;
    $viewData['type'] = html_escape($this->input->get('type'));

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    $this->_viewPage('travelog/post', $viewData);
  }

  /**
   * 스토리 등록
   *
   * @return json
   * @author bjchoi
   **/
  public function update()
  {
    $now = time();
    $userIdx = $this->session->userData['idx'];
    $inputData = $this->input->post();
    $clubIdx = html_escape($inputData['club_idx']);
    $idx = !empty($inputData['idx']) ? html_escape($inputData['idx']) : NULL;

    if (empty($userIdx)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_login'));
    } else {
      if (!empty($idx)) {
        $updateValues = array(
          'category'    => html_escape($inputData['category']),
          'title'       => html_escape($inputData['title']),
          'content'     => html_escape($inputData['content']),
          'updated_by'  => html_escape($userIdx),
          'updated_at'  => $now
        );
        $this->travelog_model->update($updateValues, $idx);
      } else {
        $insertValues = array(
          'club_idx'    => html_escape($clubIdx),
          'category'    => html_escape($inputData['category']),
          'title'       => html_escape($inputData['title']),
          'content'     => html_escape($inputData['content']),
          'created_by'  => html_escape($userIdx),
          'created_at'  => $now
        );
        $idx = $this->travelog_model->insert($insertValues);
      }

      if (empty($idx)) {
        $result = array('error' => 1, 'message' => $this->lang->line('error_insert'));
      } else {
        $result = array('error' => 0, 'message' => '');
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 코멘트 목록 (2021/03)
   *
   * @return json
   * @author bjchoi
   **/
  public function comment_list()
  {
    $clubIdx = html_escape($this->input->post('clubIdx'));
    $listTravelog = $this->travelog_model->listTravelog($clubIdx);
    $message = '';
    $page = 1;

    $paging['perPage'] = 10;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];
    $listTravelog = $this->travelog_model->listTravelog($clubIdx, $paging);

    foreach ($listTravelog as $value) {
      $message .= '<div class="row pl-3 pr-3 pt-3"><div class="col-2"><img src="/public/photos/' . $value['user_idx'] . '" class="story-photo"></div><div class="col-10 pl-0 text-justify"><b>' . $value['user_nickname'] . '</b> ' . $value['content'] . ' <span class="small grey-text">' . calcTravelogTime($value['created_at']) . '</span></div></div>';
    }

    $result = array('error' => 0, 'message' => $message);
    $this->output->set_output(json_encode($result));
  }

  /**
   * 코멘트 등록 (2021/03)
   *
   * @return json
   * @author bjchoi
   **/
  public function comment()
  {
    $now = time();
    $inputData = $this->input->post();
    $userIdx = $this->session->userData['idx'];
    $clubIdx = html_escape($inputData['clubIdx']);

    if (empty($userIdx)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_login'));
    } else {
      $insertValues = array(
        'club_idx'    => html_escape($clubIdx),
        'content'     => html_escape($inputData['content']),
        'created_by'  => html_escape($userIdx),
        'created_at'  => $now
      );
      $idx = $this->travelog_model->insertTravelog($insertValues);

      if (empty($idx)) {
        $result = array('error' => 1, 'message' => $this->lang->line('error_insert'));
      } else {
        $viewTravelog = $this->travelog_model->viewTravelog($idx);
        $message = '<div class="row pl-3 pr-3 pt-3"><div class="col-2"><img src="/public/photos/' . $userIdx . '" class="story-photo"></div><div class="col-10 pl-0 text-justify"><b>' . $viewTravelog[0]['user_nickname'] . '</b> ' . $viewTravelog[0]['content'] . ' <span class="small grey-text">방금</span></div></div>';
        $result = array('error' => 0, 'message' => $message);
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
    $reply = $this->travelog_model->listTravelogReply($storyIdx, $replyType);
    foreach ($reply as $value) {
      $message .= $this->_viewReplyContent($value, $userData, true);

      // 댓글에 대한 답글
      $replyResponse = $this->travelog_model->listTravelogReply($storyIdx, $replyType, $value['idx']);
      foreach ($replyResponse as $response) {
        $message .= $this->_viewReplyContent($response, $userData, true);
      }
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
    $replyParentIdx = html_escape($this->input->post('replyParentIdx'));
    $content = html_escape($this->input->post('content'));

    if (empty($userData['idx'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_login'));
    } else {
      if (!empty($replyIdx)) {
        // 수정
        $updateValues = array(
          'content' => $content,
          'updated_by' => $userData['idx'],
          'updated_at' => $now
        );
        $this->travelog_model->updateTravelogReply($updateValues, $storyIdx, $replyType, $replyIdx);

        // 기존 정보 불러오기
        $viewTravelogReply = $this->travelog_model->viewTravelogReply($replyIdx);
        $userData['idx'] = $viewTravelogReply['created_by'];
        $userData['nickname'] = $viewTravelogReply['nickname'];
        $rtn = $replyIdx;

        // 스토리 댓글 개수
        $cntTravelogReply = $this->travelog_model->cntTravelogReply($storyIdx, $replyType);
      } elseif (!empty($replyParentIdx)) {
        // 댓글에 대한 답글 등록
        $insertValues = array(
          'club_idx' => $clubIdx,
          'story_idx' => $storyIdx,
          'parent_idx' => $replyParentIdx,
          'reply_type' => $replyType,
          'content' => $content,
          'created_by' => $userData['idx'],
          'created_at' => $now
        );
        $rtn = $this->travelog_model->insertTravelogReply($insertValues);

        // 스토리 댓글 개수 올리기
        $cntTravelogReply = $this->travelog_model->cntTravelogReply($storyIdx, $replyType);

        if ($replyType == REPLY_TYPE_STORY) {
          $updateData['reply_cnt'] = $cntTravelogReply['cnt'];
          $this->travelog_model->updateTravelog($updateData, $storyIdx);
        }
      } else {
        // 등록
        $insertValues = array(
          'club_idx' => $clubIdx,
          'story_idx' => $storyIdx,
          'reply_type' => $replyType,
          'content' => $content,
          'created_by' => $userData['idx'],
          'created_at' => $now
        );
        $rtn = $this->travelog_model->insertTravelogReply($insertValues);

        // 스토리 댓글 개수 올리기
        $cntTravelogReply = $this->travelog_model->cntTravelogReply($storyIdx, $replyType);

        if ($replyType == REPLY_TYPE_STORY) {
          $updateData['reply_cnt'] = $cntTravelogReply['cnt'];
          $this->travelog_model->updateTravelog($updateData, $storyIdx);
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

        $replyData = array(
          'idx' => $rtn,
          'parent_idx' => $replyParentIdx,
          'photo' => $photo,
          'photo_width' => $photo_width,
          'photo_height' => $photo_height,
          'nickname' => $userData['nickname'],
          'content' => $content,
          'created_at' => $now,
          'created_by' => $userData['idx']
        );

        $html = $this->_viewReplyContent($replyData, $userData, true);

        $result = array(
          'error' => 0,
          'message' => $html,
          'reply_cnt' => $cntTravelogReply['cnt']
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
      $cntTravelogReaction = $this->travelog_model->cntTravelogReaction($storyIdx, $reactionType, REACTION_KIND_LIKE);
      $viewTravelogReaction = $this->travelog_model->viewTravelogReaction($storyIdx, $reactionType, $userIdx);
      $result = array();

      if (!empty($viewTravelogReaction['reaction_kind']) && $viewTravelogReaction['reaction_kind'] == REACTION_KIND_LIKE) {
        // 데이터가 있으면 삭제
        $rtn = $this->travelog_model->deleteTravelogReaction($storyIdx, $userIdx, $reactionType);

        if (!empty($rtn)) {
          $updateData['like_cnt'] = $cntTravelogReaction['cnt'] - 1;
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
        $rtn = $this->travelog_model->insertTravelogReaction($insertData);

        if (!empty($rtn)) {
          $updateData['like_cnt'] = $cntTravelogReaction['cnt'] + 1;
          $result = array('type' => 1, 'count' => $updateData['like_cnt']);
        }
      }

      if (!empty($rtn) && $reactionType == REACTION_TYPE_STORY) {
        $this->travelog_model->updateTravelog($updateData, $storyIdx);
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
      $viewTravelogReaction = $this->travelog_model->viewTravelogReaction($storyIdx, $reactionType, $userIdx, $shareType);

      if (!empty($viewTravelogReaction)) {
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
        $rtn = $this->travelog_model->insertTravelogReaction($insertData);

        if (!empty($rtn)) {
          $cntTravelogReaction = $this->travelog_model->cntTravelogReaction($storyIdx, $reactionType, REACTION_KIND_SHARE);

          if ($reactionType == REACTION_TYPE_STORY) {
            $updateData['share_cnt'] = $cntTravelogReaction['cnt'];
            $this->travelog_model->updateTravelog($updateData, $storyIdx);
          }

          $result = array('type' => 1, 'count' => $cntTravelogReaction['cnt']);
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
      $viewTravelog = $this->travelog_model->viewTravelog($storyIdx);
      $viewMember = $this->member_model->viewMember($userIdx);

      if (!empty($viewTravelog[0]['user_idx']) && ($viewTravelog[0]['user_idx'] == $userIdx || $viewMember['admin'] == 1)) {
        // DB는 삭제 플래그만 세워줌
        $updateData = array(
          'deleted_by' => $userIdx,
          'deleted_at' => $now
        );

        $rtn = $this->travelog_model->updateTravelog($updateData, $storyIdx);

        if (!empty($rtn)) {
          // 댓글 삭제 플래그 세우기
          $this->travelog_model->updateTravelogReply($updateData, $storyIdx, REPLY_TYPE_STORY);

          // 리액션 삭제
          $this->travelog_model->deleteTravelogReaction($storyIdx, $userIdx);

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
      $viewTravelogReply = $this->travelog_model->viewTravelogReply($storyReplyIdx);
      $viewMember = $this->member_model->viewMember($userIdx);

      if (!empty($viewTravelogReply['created_by']) && ($viewTravelogReply['created_by'] == $userIdx || $viewMember['admin'] == 1)) {
        // 삭제는 삭제 플래그만 세워줌
        $updateData = array(
          'deleted_by' => $userIdx,
          'deleted_at' => $now
        );

        $rtn = $this->travelog_model->updateTravelogReply($updateData, $viewTravelogReply['story_idx'], $viewTravelogReply['reply_type'], $storyReplyIdx);

        // 댓글에 대한 답글도 삭제
        $this->travelog_model->updateTravelogReply($updateData, $viewTravelogReply['story_idx'], $viewTravelogReply['reply_type'], NULL, $storyReplyIdx);

        if (!empty($rtn)) {
          $cntTravelogReply = $this->travelog_model->cntTravelogReply($viewTravelogReply['story_idx'], $viewTravelogReply['reply_type']);
          $updateData = array('reply_cnt' => $cntTravelogReply['cnt']);
          $this->travelog_model->updateTravelog($updateData, $viewTravelogReply['story_idx']);
          $result = array('error' => 0, 'message' => 'delete_reply', 'story_idx' => $viewTravelogReply['story_idx'], 'reply_cnt' => $cntTravelogReply['cnt']);
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
   * 댓글 표시
   *
   * @param $value
   * @param $userData
   * @return view
   * @author bjchoi
   **/
  private function _viewReplyContent($value, $userData)
  {
    if ($userData['idx'] == $value['created_by'] || $userData['admin'] == 1) {
      $delete = ' <a href="javascript:;" class="btn-reply-update" data-idx="' . $value['idx'] . '">[수정]</a> <a href="javascript:;" class="btn-post-delete-modal" data-idx="' . $value['idx'] . '" data-action="delete_reply">[삭제]</a>';
    } else {
      $delete = '';
    }
    if (!empty($value['updated_at'])) {
      $date = calcTravelogTime($value['created_at']) . ' 작성, ' . calcTravelogTime($value['updated_at']) . ' 수정';
    } else {
      $date = calcTravelogTime($value['created_at']);
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
    if (!empty($value['parent_idx'])) {
      $responseClass = ' response'; 
      $reply = '';
    } else {
      $responseClass = '';
      $value['parent_idx'] = $value['idx'];
      $reply = ' <a href="javascript:;" class="btn-reply-response" data-idx="' . $value['idx'] . '">[답글]</a>';
    } 
    $message = '<dl class="story-reply-item' . $responseClass . '" data-idx="' . $value['idx'] . '" data-parent="' . $value['parent_idx'] . '"><dt><img class="reply-response" src="/public/images/reply.png"><img class="img-profile photo-zoom" src="' . $value['photo'] . '" data-filename="' . $value['photo'] . '" data-width="' . $value['photo_width'] . '" data-height="' . $value['photo_height'] . '"></dt><dd><strong class="nickname">' . $value['nickname'] . '</strong> · <span class="reply-date">' . $date . $reply . $delete . '</span><div class="reply-content" data-idx="' . $value['idx'] . '">' . $value['content'] . '</div></dd></dl>';

    return $message;
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

    // 클럽 메뉴
    $viewData['listAbout'] = $this->club_model->listAbout($viewData['view']['idx']);

    // 등록된 산행 목록
    $viewData['listNoticeCalendar'] = $this->reserve_model->listNotice($viewData['view']['idx']);

    // 캘린더 설정
    $listCalendar = $this->admin_model->listCalendar();

    foreach ($listCalendar as $key => $value) {
      if ($value['holiday'] == 1) {
        $class = 'holiday';
      } else {
        $class = 'dayname';
      }
      $viewData['listNoticeCalendar'][] = array(
        'idx' => 0,
        'startdate' => $value['nowdate'],
        'enddate' => $value['nowdate'],
        'schedule' => 0,
        'status' => 'schedule',
        'mname' => $value['dayname'],
        'class' => $class,
      );
    }

    // 안부 인사
    $page = 1;
    $paging['perPage'] = 8;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];
    $viewData['listStory'] = $this->story_model->listStory($viewData['view']['idx'], $paging);

    foreach ($viewData['listStory'] as $key => $value) {
      if (file_exists(PHOTO_PATH . $value['user_idx'])) {
        $viewData['listStory'][$key]['avatar'] = PHOTO_URL . $value['user_idx'];
      } else {
        $viewData['listStory'][$key]['avatar'] = '/public/images/user.png';
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

    // 페이지 타이틀
    switch ($viewData['type']) {
      case 'news': $viewData['pageTitle'] = '여행 소식'; break;
      case 'logs': $viewData['pageTitle'] = '여행 후기'; break;
      default: $viewData['pageTitle'] = '여행기';
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

    $this->load->view('club/header_' . $viewData['view']['main_design'], $viewData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('club/footer_' . $viewData['view']['main_design'], $viewData);
  }
}
?>
