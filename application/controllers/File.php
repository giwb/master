<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 파일 클래스
class File extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->library('image_lib');
  }

  /**
   * 파일 업로드
   *
   * @return json
   * @author bjchoi
   **/
  public function upload()
  {
    $file = $_FILES['file_obj'];
    $arr = explode('.', $file['name']);
    $ext = array_pop($arr);

    if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {
      $filename = time() . mt_rand(10000, 99999) . '.' . $ext;

      if (move_uploaded_file($_FILES['file_obj']['tmp_name'], UPLOAD_PATH . $filename)) {
        // 사진 사이즈 줄이기 (가로 사이즈가 800보다 클 경우)
        $size = getImageSize(UPLOAD_PATH . $filename);
        if ($size[0] >= 800) {
          $this->image_lib->clear();
          $config['image_library'] = 'gd2';
          $config['source_image'] = UPLOAD_PATH . $filename;
          $config['maintain_ratio'] = TRUE;
          $config['width'] = 800;
          $this->image_lib->initialize($config);
          $this->image_lib->resize();
        }

        $result = array('error' => 0, 'message' => UPLOAD_URL . $filename, 'filename' => $filename);
      } else {
        $result = array('error' => 1, 'message' => $this->lang->line('error_photo_delete'));
      }
    } else {
      $result = array('error' => 1, 'message' => $this->lang->line('error_photo_ext'));
    }

    $this->output->set_output(json_encode($result));
  }
}
?>