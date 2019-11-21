<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 첫 페이지 클래스
class Story extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('security', 'url', 'my_array_helper'));
    $this->load->library(array('image_lib'));
    $this->load->model(array('story_model', 'file_model'));
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
    $inputData = $this->input->post();
    $inputData['photo'] = html_escape($inputData['photo']);
    $inputData['page'] = html_escape($inputData['page']);
    $inputData['userIdx'] = html_escape($inputData['user_idx']);
    $result = array('error' => 1, 'message' => $this->lang->line('error_insert'));

    if (!empty($inputData['userIdx'])) {
      $insertValues = array(
        'club_idx'    => html_escape($clubIdx),
        'content'     => html_escape($inputData['content']),
        'created_by'  => html_escape($inputData['userIdx']),
        'created_at'  => $now
      );

      $idx = $this->story_model->insertStory($insertValues);

      if (!empty($idx)) {
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

        $result = array('error' => 0);
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
    $result = array();

    // 댓글 목록
    $result = $this->story_model->listStoryReply($clubIdx, $storyIdx);

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
    $clubIdx = html_escape($clubIdx);
    $storyIdx = html_escape($this->input->post('storyIdx'));
    $content = html_escape($this->input->post('content'));

    $insertValues = array(
      'club_idx' => $clubIdx,
      'story_idx' => $storyIdx,
      'content' => $content,
      'created_by' => html_escape($this->input->post('userIdx')),
      'created_at' => $now
    );

    $rtn = $this->story_model->insertStoryReply($insertValues);

    if (empty($rtn)) {
      $result = array('error' => 1);
    } else {
      // 스토리 댓글 개수 올리기
      $cntStoryReply = $this->story_model->cntStoryReply($clubIdx, $storyIdx);
      $updateData['reply_cnt'] = $cntStoryReply['cnt'];
      $this->story_model->updateStory($updateData, $clubIdx, $storyIdx);

      $result = array(
        'error' => 0,
        'content' => $content,
        'created_at' => date('Y/m/d H:i:s', $now),
        'reply_cnt' => $updateData['reply_cnt']
      );
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
    $clubIdx = html_escape($clubIdx);
    $storyIdx = html_escape($this->input->post('storyIdx'));
    $userIdx = html_escape($this->input->post('userIdx'));
    $cntStoryReaction = $this->story_model->cntStoryReaction($clubIdx, $storyIdx, TYPE_REACTION_LIKE);
    $viewStoryReaction = $this->story_model->viewStoryReaction($clubIdx, $storyIdx, $userIdx);
    $result = array();

    if (!empty($viewStoryReaction['type_reaction']) && $viewStoryReaction['type_reaction'] == TYPE_REACTION_LIKE) {
      // 데이터가 있으면 삭제
      $rtn = $this->story_model->deleteStoryReaction($clubIdx, $storyIdx, $userIdx);

      if (!empty($rtn)) {
        $updateData['like_cnt'] = $cntStoryReaction['cnt'] - 1;
        $result = array('type' => 0, 'count' => $updateData['like_cnt']);
      }
    } else {
      // 데이터가 없으면 추가
      $insertData = array(
        'club_idx' => $clubIdx,
        'story_idx' => $storyIdx,
        'type_reaction' => TYPE_REACTION_LIKE,
        'created_by' => $userIdx,
        'created_at' => $now
      );
      $rtn = $this->story_model->insertStoryReaction($insertData);

      if (!empty($rtn)) {
        $updateData['like_cnt'] = $cntStoryReaction['cnt'] + 1;
        $result = array('type' => 1, 'count' => $updateData['like_cnt']);
      }
    }

    if (!empty($rtn)) {
      // 스토리 좋아요 카운트 수정
      $this->story_model->updateStory($updateData, $clubIdx, $storyIdx);
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
    $clubIdx = html_escape($clubIdx);
    $storyIdx = html_escape($this->input->post('idx'));
    $userIdx = html_escape($this->input->post('user_idx'));
    $result = array('error' => 1, 'message' => $this->lang->line('error_delete'));

    // 해당 글을 작성한 사람이 맞는치 확인
    $viewStory = $this->story_model->viewStory($clubIdx, $storyIdx);

    if (!empty($viewStory['created_by']) && $viewStory['created_by'] == $userIdx) {
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
    $userData = $this->load->get_var('userData');
    $clubIdx = html_escape($clubIdx);
    $storyIdx = html_escape($this->input->post('story_idx'));
    $storyReplyIdx = html_escape($this->input->post('idx'));
    $result = array('error' => 1, 'message' => $this->lang->line('error_delete'));

    // 해당 글을 작성한 사람이 맞는치 확인
    $viewStoryReply = $this->story_model->viewStoryReply($clubIdx, $storyIdx);

    if (!empty($viewStoryReply['created_by']) && $viewStoryReply['created_by'] == $userData['idx']) {
      // 삭제는 삭제 플래그만 세워줌
      $updateData = array(
        'deleted_by' => $userData['idx'],
        'deleted_at' => $now
      );

      $rtn = $this->story_model->updateStoryReply($updateData, $clubIdx, $storyIdx, $storyReplyIdx);

      if (!empty($rtn)) {
        $result = array('error' => 0);
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
}
?>