<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 첫 페이지 클래스
class Story extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('my_array_helper', 'url'));
    $this->load->library(array('image_lib'));
    $this->load->model(array('story_model', 'file_model'));
  }

  /**
   * 스토리 등록
   *
   * @return json
   * @author bjchoi
   **/
  public function story_insert()
  {
    $now = time();
    $inputData = $this->input->post();
    $inputData['member_idx'] = 1;
    $inputData['photo'] = html_escape($inputData['photo']);
    $inputData['page'] = html_escape($inputData['page']);

    $insertValues = array(
      'club_idx'    => html_escape($inputData['club_idx']),
      'member_idx'  => html_escape($inputData['member_idx']),
      'content'     => html_escape($inputData['content']),
      'created_by'  => html_escape($inputData['member_idx']),
      'created_at'  => $now
    );

    $idx = $this->story_model->insertStory($insertValues);

    if ($idx == '') {
      $return = array(
        'error' => 1,
        'message' => '등록에 실패했습니다.'
      );
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

      $return = array(
        'error' => 0,
        'message' => '등록이 완료되었습니다.'
      );
    }

    $this->output->set_output(json_encode($return));
  }

  /**
   * 사진 삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function delete_photo()
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