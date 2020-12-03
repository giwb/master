<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 첫 페이지 클래스
class Place extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('url', 'my_array_helper'));
    $this->load->library(array('image_lib'));
    $this->load->model(array('area_model', 'file_model', 'place_model'));
  }

  public function index()
  {
    $viewData['search'] = $this->input->get('search');
    $viewData['keyword'] = $this->input->get('keyword');
    $viewData['list'] = $this->place_model->listPlace($viewData['search'], $viewData['keyword']);

    foreach ($viewData['list'] as $key => $value) {
      $file = $this->file_model->getFile('place', $value['idx'], TYPE_MAIN, 1);
      if (!empty($file[0]['filename'])) {
        $viewData['list'][$key]['photo'] = PHOTO_URL . 'thumb_' . $file[0]['filename'];
      } else {
        $viewData['list'][$key]['photo'] = '/public/images/noimage.png';
      }
    }

    switch ($viewData['keyword']) {
      case 'type2': $viewData['pageTitle'] = '산림청 선정 100대 명산'; break;
      case 'type3': $viewData['pageTitle'] = '블랙야크 명산100'; break;
      case 'type4': $viewData['pageTitle'] = '죽기전에 꼭 가봐야 할 국내여행 1001'; break;
      case 'type5': $viewData['pageTitle'] = '백두대간'; break;
      case 'type6': $viewData['pageTitle'] = '도보트레킹'; break;
      case 'type7': $viewData['pageTitle'] = '투어'; break;
      case 'type8': $viewData['pageTitle'] = '섬'; break;
      default: $viewData['pageTitle'] = '전체보기';
    }

    $this->_viewPage('place/index', $viewData);
  }

  public function view($idx)
  {
    $viewData['view'] = $this->place_model->viewPlace($idx);
    $viewData['view']['photo'] = array();
    $viewData['view']['reason'] = reset_html_escape($viewData['view']['reason']);
    $viewData['view']['content'] = reset_html_escape($viewData['view']['content']);
    $viewData['view']['around'] = reset_html_escape($viewData['view']['around']);
    $viewData['view']['course'] = reset_html_escape($viewData['view']['course']);

    $files = $this->file_model->getFile('place', $idx);

    foreach ($files as $key => $value) {
      if ($value['filename'] != '') {
        $viewData['view']['photo_' . $value['type']][$key] = $value['filename'];
      } else {
        $viewData['view']['photo_' . $value['type']][$key] = '';
      }
    }

    // 공통 메뉴
    $viewData['commonMenu'] = $this->load->view('place/menu', $viewData, true);

    $this->_viewPage('place/view', $viewData);
  }

  /**
   * 등록폼
   *
   * @return view
   * @author bjchoi
   **/
  public function entry($idx=NULL)
  {
    if (!is_null($idx)) {
      $viewData['view'] = $this->place_model->viewPlace($idx);
      $viewData['view']['point'] = is_null($viewData['view']['point']) || strlen($viewData['view']['point']) <= 3 ? array() : unserialize($viewData['view']['point']);
      $viewData['view']['type'] = is_null($viewData['view']['type']) || strlen($viewData['view']['type']) <= 3 ? array() : unserialize($viewData['view']['type']);

      $files = $this->file_model->getFile('place', $idx);

      if (empty($files)) {
        $viewData['view']['photo'][0] = '';
      } else {
        foreach ($files as $key => $value) {
          if (!$value['filename'] == '') {
            $viewData['view']['photo'][$key] = $value;
          } else {
            $viewData['view']['photo'][$key] = '';
          }
        }
      }
    } else {
      $viewData['view']['idx'] = '';
      $viewData['view']['title'] = '';
      $viewData['view']['area_sido'] = array();
      $viewData['view']['area_gugun'] = array();
      $viewData['view']['point'] = array();
      $viewData['view']['type'] = array();
      $viewData['view']['reason'] = '';
      $viewData['view']['altitude'] = '';
      $viewData['view']['content'] = '';
      $viewData['view']['around'] = '';
      $viewData['view']['course'] = '';
      $viewData['view']['photo'][0] = '';
    }

    $viewData['area_sido'] = $this->area_model->listSido();
    if (!empty($viewData['view']['area_sido'])) {
      $area_sido = unserialize($viewData['view']['area_sido']);
      $area_gugun = unserialize($viewData['view']['area_gugun']);

      foreach ($area_sido as $key => $value) {
        $sido = $this->area_model->getName($value);
        $gugun = $this->area_model->getName($area_gugun[$key]);
        $viewData['list_sido'] = $this->area_model->listSido();
        $viewData['list_gugun'][$key] = $this->area_model->listGugun($value);
        $viewData['view']['sido'][$key] = $sido['name'];
        $viewData['view']['gugun'][$key] = $gugun['name'];
      }

      $viewData['area_gugun'] = $this->area_model->listGugun($viewData['view']['area_sido']);
    } else {
      $viewData['area_gugun'] = array();
    }

    // 공통 메뉴
    $viewData['commonMenu'] = $this->load->view('place/menu', $viewData, true);

    $this->_viewPage('place/entry', $viewData);
  }

  /**
   * 등록
   *
   * @return json
   * @author bjchoi
   **/
  public function insert()
  {
    $now = time();
    $files = array();
    $input_data = $this->input->post();

    if (empty($input_data['title']) || empty($input_data['content']) || empty($input_data['file_' . TYPE_MAIN])) {
      $return = array(
        'error' => 1,
        'message' => '필수 항목은 꼭 입력해주세요.'
      );
    } else {
      if (!empty($input_data['file_' . TYPE_MAIN])) $files[TYPE_MAIN] = explode(',', html_escape($input_data['file_' . TYPE_MAIN]));
      if (!empty($input_data['file_' . TYPE_ADDED])) $files[TYPE_ADDED] = explode(',', html_escape($input_data['file_' . TYPE_ADDED]));
      if (!empty($input_data['file_' . TYPE_ADDED])) $files[TYPE_ADDED] = explode(',', html_escape($input_data['file_' . TYPE_MAP]));

      $insert_values  = array(
        'title'       => html_escape($input_data['title']),
        'area_sido'   => make_serialize($input_data['area_sido']),
        'area_gugun'  => make_serialize($input_data['area_gugun']),
        'point'       => !empty($input_data['point']) ? make_serialize($input_data['point']) : NULL,
        'type'        => !empty($input_data['type']) ? make_serialize($input_data['type']) : NULL,
        'altitude'    => !empty($input_data['altitude']) ? html_escape($input_data['altitude']) : NULL,
        'reason'      => html_escape($input_data['reason']),
        'content'     => html_escape($input_data['content']),
        'around'      => html_escape($input_data['around']),
        'course'      => html_escape($input_data['course']),
        'created_by'  => 1,
        'created_at'  => $now
      );

      $idx = $this->place_model->insertPlace($insert_values);

      if (!$idx) {
        $return = array(
          'error' => 1,
          'message' => '등록에 실패했습니다.'
        );
      } else {
        // 파일 등록
        foreach ($files as $key => $file) {
          if ($file[0] != '') {
            foreach ($file as $value) {
              // 업로드 된 파일이 있을 경우에만 등록 후 이동
              if (file_exists(UPLOAD_PATH . $value)) {
                $file_values = array(
                  'page' => html_escape($input_data['page']),
                  'page_idx' => $idx,
                  'type' => $key,
                  'filename' => $value,
                  'created_at' => $now
                );
                $this->file_model->insertFile($file_values);

                // 파일 이동
                rename(UPLOAD_PATH . $value, PHOTO_PATH . $value);

                // 썸네일 만들기
                $this->image_lib->clear();
                $config['image_library'] = 'gd2';
                $config['source_image'] = PHOTO_PATH . $value;
                $config['new_image'] = PHOTO_PATH . 'thumb_' . $value;
                $config['create_thumb'] = TRUE;
                $config['maintain_ratio'] = FALSE;
                $config['thumb_marker'] = '';
                $config['width'] = 250;
                $config['height'] = 160;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
              }
            }
          }
        }

        $return = array(
          'error' => 0,
          'message' => '등록이 완료되었습니다.'
        );
      }
    }

    $this->output->set_output(json_encode($return));
  }

  /**
   * 수정
   *
   * @return json
   * @author bjchoi
   **/
  public function update()
  {
    $now = time();
    $input_data = $this->input->post();
    $idx = html_escape($input_data['idx']);
    $files[TYPE_MAIN] = explode(',', html_escape($input_data['file_' . TYPE_MAIN]));
    $files[TYPE_ADDED] = explode(',', html_escape($input_data['file_' . TYPE_ADDED]));
    $files[TYPE_MAP] = explode(',', html_escape($input_data['file_' . TYPE_MAP]));

    $update_values  = array(
      'title'       => html_escape($input_data['title']),
      'area_sido'   => make_serialize($input_data['area_sido']),
      'area_gugun'  => make_serialize($input_data['area_gugun']),
      'type'        => make_serialize($input_data['type']),
      'altitude'    => html_escape($input_data['altitude']),
      'reason'      => html_escape($input_data['reason']),
      'content'     => html_escape($input_data['content']),
      'around'      => html_escape($input_data['around']),
      'course'      => html_escape($input_data['course']),
      'updated_by'  => 1,
      'updated_at'  => $now
    );

    if (!empty($input_data['point'])) {
      $update_values['point'] = make_serialize($input_data['point']);
    }

    $this->place_model->updatePlace($update_values, $idx);

    // 파일 등록
    foreach ($files as $key => $file) {
      if ($file[0] != '') {
        foreach ($file as $value) {
          // 업로드 된 파일이 있을 경우에만 등록 후 이동
          if (file_exists(UPLOAD_PATH . $value)) {
            $file_values = array(
              'page' => html_escape($input_data['page']),
              'page_idx' => $idx,
              'type' => $key,
              'filename' => $value,
              'created_at' => $now
            );
            $this->file_model->insertFile($file_values);

            // 파일 이동
            rename(UPLOAD_PATH . $value, PHOTO_PATH . $value);

            // 썸네일 만들기
            $this->image_lib->clear();
            $config['image_library'] = 'gd2';
            $config['source_image'] = PHOTO_PATH . $value;
            $config['new_image'] = PHOTO_PATH . 'thumb_' . $value;
            $config['create_thumb'] = TRUE;
            $config['maintain_ratio'] = FALSE;
            $config['thumb_marker'] = '';
            $config['width'] = 250;
            $config['height'] = 160;
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
          }
        }
      }
    }

    redirect('/place');
  }

  /**
   * 삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function delete()
  {
    $input_data = $this->input->post();
    $idx = html_escape($input_data['idx']);

    $update_values = array(
      'deleted_by' => 1,
      'deleted_at' => time()
    );

    $result = $this->place_model->updatePlace($update_values, $idx);

    if ($result != '') {
      $result = array(
        'error' => 0,
        'message' => '삭제가 완료되었습니다.'
      );
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
    $filename = html_escape($this->input->post('photo'));
    $result = $this->file_model->deleteFile($filename);

    if (file_exists(UPLOAD_PATH . $filename)) {
      unlink(UPLOAD_PATH . $filename);
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

  public function list_gugun()
  {
    $parent = html_escape($this->input->post('parent'));
    $result = $this->area_model->listGugun($parent);
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
    // 리다이렉트 URL 추출
    if ($_SERVER['SERVER_PORT'] == '80') $HTTP_HEADER = 'http://'; else $HTTP_HEADER = 'https://';
    $viewData['redirectUrl'] = $HTTP_HEADER . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    $this->load->view('header', $viewData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('footer');
  }
}
?>