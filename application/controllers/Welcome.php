<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 첫 페이지 클래스
class Welcome extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('url', 'my_array_helper'));
    $this->load->model(array('admin_model', 'area_model', 'club_model', 'file_model'));
  }

  public function index()
  {
    $viewData['userData'] = $this->load->get_var('userData');
    $this->_viewPage('index', $viewData);
  }

  /**
   * 클럽 리스트 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function listing()
  {
    $viewData['search'] = html_escape($this->input->get('s'));
    $viewData['keyword'] = html_escape($this->input->get('k'));
    $viewData['list'] = $this->club_model->listClub($viewData['search'], $viewData['keyword']);

    if ($viewData['search'] == 'area_sido') {
      $viewData['searchTitle'] = $this->area_model->getName($viewData['keyword']);
    } else {
      $viewData['searchTitle']['name'] = '전체';
    }

    foreach ($viewData['list'] as $key => $value) {
      // 도메인
      if (strstr($value['domain'], '.')) {
        $viewData['list'][$key]['domain'] = 'http://' . $value['domain'];
      } else {
        $viewData['list'][$key]['domain'] = base_url() . $value['domain'];
      }

      // 사진
      $file = $this->file_model->getFile('club', $value['idx'], NULL, 1);
      if (!empty($file[0]['filename'])) {
        $viewData['list'][$key]['photo'] = 'thumb_' . $file[0]['filename'];
      } else {
        $viewData['list'][$key]['photo'] = 'noimage.png';
      }
    }

    $this->_viewPage('club/listing', $viewData);
  }


  /**
   * 클럽 등록폼
   *
   * @return view
   * @author bjchoi
   **/
  public function entry($idx=NULL)
  {
    if (!is_null($idx)) {
      $viewData['view'] = $this->club_model->viewClub($idx);
      $viewData['view']['club_type'] = is_null($viewData['view']['club_type']) || strlen($viewData['view']['club_type']) <= 3 ? array() : unserialize($viewData['view']['club_type']);
      $viewData['view']['club_option'] = is_null($viewData['view']['club_option']) || strlen($viewData['view']['club_option']) <= 3 ? array() : unserialize($viewData['view']['club_option']);
      $viewData['view']['club_cycle'] = is_null($viewData['view']['club_cycle']) || strlen($viewData['view']['club_cycle']) <= 3 ? array() : unserialize($viewData['view']['club_cycle']);
      $viewData['view']['club_week'] = is_null($viewData['view']['club_week']) || strlen($viewData['view']['club_week']) <= 3 ? array() : unserialize($viewData['view']['club_week']);
      $viewData['view']['club_geton'] = is_null($viewData['view']['club_geton']) || strlen($viewData['view']['club_geton']) <= 3 ? array() : unserialize($viewData['view']['club_geton']);
      $viewData['view']['club_getoff'] = is_null($viewData['view']['club_getoff']) || strlen($viewData['view']['club_getoff']) <= 3 ? array() : unserialize($viewData['view']['club_getoff']);
      $files = $this->file_model->getFile('club', $idx);

      if (empty($files)) {
        $viewData['view']['photo'][0] = '';
      } else {
        foreach ($files as $key => $value) {
          if (!$value['filename'] == '') {
            $viewData['view']['photo'][$key] = $value['filename'];
          } else {
            $viewData['view']['photo'][$key] = '';
          }
        }
      }
    } else {
      $viewData['view']['idx'] = '';
      $viewData['view']['title'] = '';
      $viewData['view']['homepage'] = '';
      $viewData['view']['area_sido'] = '';
      $viewData['view']['area_gugun'] = '';
      $viewData['view']['phone'] = '';
      $viewData['view']['content'] = '';
      $viewData['view']['photo'][0] = '';
      $viewData['view']['establish'] = '';
      $viewData['view']['club_type'] = array();
      $viewData['view']['club_option'] = array();
      $viewData['view']['club_cycle'] = array();
      $viewData['view']['club_week'] = array();
      $viewData['view']['club_geton'] = array();
      $viewData['view']['club_getoff'] = array();
      $viewData['view']['club_option_text'] = '';
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

    $this->_viewPage('club/entry', $viewData);
  }

  /**
   * 클럽 등록
   *
   * @return json
   * @author bjchoi
   **/
  public function insert()
  {
    $now = time();
    $input_data = $this->input->post();
    $files = explode(',', html_escape($input_data['file_' . TYPE_MAIN]));

    $insert_values = array(
      'title'             => html_escape($input_data['title']),
      'homepage'          => html_escape($input_data['homepage']),
      'phone'             => html_escape($input_data['phone']),
      'area_sido'         => make_serialize($input_data['area_sido']),
      'area_gugun'        => make_serialize($input_data['area_gugun']),
      'about'             => html_escape($input_data['about']),
      'establish'         => html_escape($input_data['establish']),
      'club_type'         => make_serialize($input_data['club_type']),
      'club_option'       => make_serialize($input_data['club_option']),
      'club_option_text'  => html_escape($input_data['club_option_text']),
      'club_cycle'        => make_serialize($input_data['club_cycle']),
      'club_week'         => make_serialize($input_data['club_week']),
      'club_geton'        => make_serialize($input_data['club_geton']),
      'club_getoff'       => make_serialize($input_data['club_getoff']),
      'created_by'        => 1,
      'created_at'        => $now
    );

    $idx = $this->club_model->insertClub($insert_values);

    if (!empty($idx)) {
      // 파일 등록
      foreach ($files as $value) {
        // 업로드 된 파일이 있을 경우에만 등록 후 이동
        if (!empty($value) && file_exists(UPLOAD_PATH . $value)) {
          $file_values = array(
            'page' => html_escape($input_data['page']),
            'page_idx' => $idx,
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
          $config['width'] = 200;
          $config['height'] = 200;
          $this->image_lib->initialize($config);
          $this->image_lib->resize();
        }
      }
    }

    redirect('/club');
  }

  /**
   * 클럽 수정
   *
   * @return json
   * @author bjchoi
   **/
  public function update()
  {
    $now = time();
    $input_data = $this->input->post();
    $idx = html_escape($input_data['idx']);
    $files = explode(',', html_escape($input_data['file_' . TYPE_MAIN]));

    $update_values = array(
      'title'             => html_escape($input_data['title']),
      'homepage'          => html_escape($input_data['homepage']),
      'phone'             => html_escape($input_data['phone']),
      'area_sido'         => make_serialize($input_data['area_sido']),
      'area_gugun'        => make_serialize($input_data['area_gugun']),
      'content'           => html_escape($input_data['content']),
      'establish'         => html_escape($input_data['establish']),
      'club_type'         => make_serialize($input_data['club_type']),
      'club_option'       => make_serialize($input_data['club_option']),
      'club_option_text'  => html_escape($input_data['club_option_text']),
      'club_cycle'        => make_serialize($input_data['club_cycle']),
      'club_week'         => make_serialize($input_data['club_week']),
      'club_geton'        => make_serialize($input_data['club_geton']),
      'club_getoff'       => make_serialize($input_data['club_getoff']),
      'updated_by'  => 1,
      'updated_at'  => $now
    );
    $this->club_model->updateClub($update_values, $idx);

    // 파일 등록
    if ($files[0] != '') {
      foreach ($files as $value) {
        // 업로드 된 파일이 있을 경우에만 등록 후 이동
        if (file_exists(UPLOAD_PATH . $value)) {
          $file_values = array(
            'page' => html_escape($input_data['page']),
            'page_idx' => $idx,
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
          $config['width'] = 200;
          $config['height'] = 200;
          $this->image_lib->initialize($config);
          $this->image_lib->resize();
        }
      }
    }

    $result = array(
      'error' => 0,
      'message' => '수정이 완료되었습니다.'
    );

    $this->output->set_output(json_encode($result));
  }

  /**
   * 클럽 삭제
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

    $result = $this->club_model->updateClub($update_values, $idx);

    if ($result != '') {
      $result = array(
        'error' => 0,
        'message' => '삭제가 완료되었습니다.'
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
    // 리다이렉트 URL 추출
    if ($_SERVER['SERVER_PORT'] == '80') $HTTP_HEADER = 'http://'; else $HTTP_HEADER = 'https://';
    $viewData['redirectUrl'] = $HTTP_HEADER . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    $this->load->view('header', $viewData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('footer');
  }
}
?>