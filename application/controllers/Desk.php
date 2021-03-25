<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 데스크 페이지 클래스
class Desk extends Desk_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('cookie', 'security', 'url', 'my_array_helper'));
    $this->load->library(array('image_lib'));
    $this->load->model(array('area_model', 'desk_model'));
  }

  /**
   * 데스크 인덱스
   *
   * @return view
   * @author bjchoi
   **/
  public function index()
  {
    $this->_viewPage('desk/index');
  }


  /**
    ====================================================================================================================
      기사 관리 섹션
    ====================================================================================================================
  **/

  /**
   * 기사 관리
   *
   * @return view
   * @author bjchoi
   **/
  public function article()
  {
    $viewData['list'] = $this->desk_model->listArticle();
    $viewData['max'] = count($viewData['list']);
    $this->_viewPage('desk/article', $viewData);
  }

  /**
   * 기사 열람
   *
   * @return view
   * @author bjchoi
   **/
  public function article_view($idx)
  {
    $viewData['view'] = $this->desk_model->viewArticle(html_escape($idx));
    $this->_viewPage('desk/article_view', $viewData);
  }

  /**
   * 기사 등록
   *
   * @return view
   * @author bjchoi
   **/
  public function article_post($idx=NULL)
  {
    $viewData['userData'] = $this->load->get_var('userData');
    $viewData['category'] = $this->desk_model->listArticleCategory();

    if (!is_null($idx)) {
      $viewData['view'] = $this->desk_model->viewArticle(html_escape($idx));
    }

    $this->_viewPage('desk/article_post', $viewData);
  }

  /**
   * 기사 등록/수정
   *
   * @return view
   * @author bjchoi
   **/
  public function article_update()
  {
    $now = time();
    $inputData = $this->input->post();

    if (empty($inputData['title'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_no_title'));
    }
    if (empty($inputData['category'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_no_category'));
    }
    if (empty($inputData['content'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_no_content'));
    }
    if (empty($inputData['viewing_date']) || empty($inputData['viewing_time'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_no_viewing'));
    }

    if (empty($result)) {
      if (!empty($_FILES['main_image']['tmp_name'])) {
        // 메인 이미지 처리
        if ($_FILES['main_image']['type'] == 'image/jpeg') {
          $ext = ".jpg";
        } else {
          $ext = ".jpg";
        }
        $inputData['main_image_uploaded'] = time() . mt_rand(10000, 99999) . $ext;
        move_uploaded_file($_FILES['main_image']['tmp_name'], PHOTO_ARTICLE_PATH . $inputData['main_image_uploaded']);
      }

      if (!empty($inputData['idx'])) {
        $idx = html_escape($inputData['idx']);
        $updateValues = array(
          'category'    => html_escape($inputData['category']),
          'main_image'  => !empty($inputData['main_image_uploaded']) ? html_escape($inputData['main_image_uploaded']) : NULL,
          'title'       => html_escape($inputData['title']),
          'content'     => html_escape($inputData['content']),
          'viewing_at'  => strtotime(html_escape($inputData['viewing_date']) . ' ' . html_escape($inputData['viewing_time'])),
          'updated_by'  => html_escape($inputData['useridx']),
          'updated_at'  => $now,
        );
        $this->desk_model->update(DB_ARTICLE, $updateValues, $idx);
      } else {
        $updateValues = array(
          'category'    => html_escape($inputData['category']),
          'main_image'  => !empty($inputData['main_image_uploaded']) ? html_escape($inputData['main_image_uploaded']) : NULL,
          'title'       => html_escape($inputData['title']),
          'content'     => html_escape($inputData['content']),
          'viewing_at'  => strtotime(html_escape($inputData['viewing_date']) . ' ' . html_escape($inputData['viewing_time'])),
          'created_by'  => html_escape($inputData['useridx']),
          'created_at'  => $now,
        );
        $this->desk_model->insert(DB_ARTICLE, $updateValues);
      }
      $result = array('error' => 0, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 기사 메인 등록
   *
   * @return view
   * @author bjchoi
   **/
  public function article_main()
  {
    $updateValues['main_status'] = html_escape($this->input->post('value'));
    $this->desk_model->update(DB_ARTICLE, $updateValues, html_escape($this->input->post('idx')));
    redirect('/desk/article');
  }

  /**
   * 기사 삭제
   *
   * @return view
   * @author bjchoi
   **/
  public function article_delete()
  {
    $now = time();
    $idx = html_escape($this->input->post('idx'));
    $userData = $this->load->get_var('userData');

    $updateValues = array(
      'deleted_by' => $userData['idx'],
      'deleted_at' => $now
    );
    $this->desk_model->update(DB_ARTICLE, $updateValues, $idx);

    redirect('/desk/article');
  }

  /**
   * 분류 편집
   *
   * @return view
   * @author bjchoi
   **/
  public function article_category_update()
  {
    $now = time();
    $inputData = $this->input->post();
    $message = '<option value="">분류를 선택해주세요</option>';

    if (!empty($inputData['category_code'][0]) && !empty($inputData['category_name'][0])) {
      $this->desk_model->delete(DB_ARTICLE_CATEGORY);

      foreach ($inputData['category_code'] as $key => $value) {
        if (!empty($value) && !empty($inputData['category_name'][$key])) {
          $code = html_escape($value);
          $name = html_escape($inputData['category_name'][$key]);
          $updateValues = array(
            'code' => $code,
            'name' => $name,
          );
          $message .= "<option value='" . $code . "'>" . $name . "</option>";
          $rtn = $this->desk_model->insert(DB_ARTICLE_CATEGORY, $updateValues);
        }
      }
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
    } else {
      $result = array('error' => 0, 'message' => $message);
    }

    $this->output->set_output(json_encode($result));
  }


  /**
    ====================================================================================================================
      여행정보 관리 섹션
    ====================================================================================================================
  **/

  /**
   * 여행정보 관리
   *
   * @return view
   * @author bjchoi
   **/
  public function place()
  {
    $viewData['list'] = $this->desk_model->listPlace();
    $viewData['max'] = count($viewData['list']);
    $this->_viewPage('desk/place', $viewData);
  }

  /**
   * 기사 열람
   *
   * @return view
   * @author bjchoi
   **/
  public function place_view($idx)
  {
    $viewData['view'] = $this->desk_model->viewPlace(html_escape($idx));
    $this->_viewPage('desk/place_view', $viewData);
  }

  /**
   * 기사 등록
   *
   * @return view
   * @author bjchoi
   **/
  public function place_post($idx=NULL)
  {
    $viewData['userData'] = $this->load->get_var('userData');
    $viewData['category'] = $this->desk_model->listPlaceCategory();

    if (!is_null($idx)) {
      $viewData['view'] = $this->desk_model->viewPlace(html_escape($idx));
    }

    // 지역
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

    $this->_viewPage('desk/place_post', $viewData);
  }

  /**
   * 기사 등록/수정
   *
   * @return view
   * @author bjchoi
   **/
  public function place_update()
  {
    $now = time();
    $inputData = $this->input->post();

    if (empty($inputData['title'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_no_title'));
    }
    if (empty($inputData['category'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_no_category'));
    }
    if (empty($inputData['content'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_no_content'));
    }

    if (empty($result)) {
      if (!empty($_FILES['thumbnail']['tmp_name'])) {
        // 메인 이미지 처리
        if ($_FILES['thumbnail']['type'] == 'image/jpeg') {
          $ext = ".jpg";
        } else {
          $ext = ".jpg";
        }
        $inputData['thumbnail_uploaded'] = time() . mt_rand(10000, 99999) . $ext;
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], PHOTO_PLACE_PATH . $inputData['thumbnail_uploaded']);

        // 썸네일 만들기
        $this->image_lib->clear();
        $config['image_library'] = 'gd2';
        $config['source_image'] = PHOTO_PLACE_PATH . $inputData['thumbnail_uploaded'];
        $config['new_image'] = PHOTO_PLACE_PATH . 'thumb_' . $inputData['thumbnail_uploaded'];
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['thumb_marker'] = '';
        $config['width'] = 500;
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
      }

      if (!empty($inputData['idx'])) {
        $idx = html_escape($inputData['idx']);
        $updateValues = array(
          'area_sido'   => make_serialize(html_escape($inputData['area_sido'])),
          'area_gugun'  => make_serialize(html_escape($inputData['area_gugun'])),
          'category'    => html_escape($inputData['category']),
          'altitude'    => html_escape($inputData['altitude']),
          'thumbnail'   => !empty($inputData['thumbnail_uploaded']) ? html_escape($inputData['thumbnail_uploaded']) : NULL,
          'title'       => html_escape($inputData['title']),
          'reason'      => html_escape($inputData['reason']),
          'around'      => html_escape($inputData['around']),
          'course'      => html_escape($inputData['course']),
          'content'     => html_escape($inputData['content']),
          'updated_by'  => html_escape($inputData['useridx']),
          'updated_at'  => $now,
        );
        $this->desk_model->update(DB_PLACES, $updateValues, $idx);
      } else {
        $updateValues = array(
          'area_sido'   => make_serialize(html_escape($inputData['area_sido'])),
          'area_gugun'  => make_serialize(html_escape($inputData['area_gugun'])),
          'category'    => html_escape($inputData['category']),
          'altitude'    => html_escape($inputData['altitude']),
          'thumbnail'   => !empty($inputData['thumbnail_uploaded']) ? html_escape($inputData['thumbnail_uploaded']) : NULL,
          'title'       => html_escape($inputData['title']),
          'reason'      => html_escape($inputData['reason']),
          'around'      => html_escape($inputData['around']),
          'course'      => html_escape($inputData['course']),
          'content'     => html_escape($inputData['content']),
          'created_by'  => html_escape($inputData['useridx']),
          'created_at'  => $now,
        );
        $this->desk_model->insert(DB_PLACES, $updateValues);
      }
      $result = array('error' => 0, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 기사 메인 등록
   *
   * @return view
   * @author bjchoi
   **/
  public function place_main()
  {
    $updateValues['main_status'] = html_escape($this->input->post('value'));
    $this->desk_model->update(DB_PLACES, $updateValues, html_escape($this->input->post('idx')));
    redirect('/desk/place');
  }

  /**
   * 기사 삭제
   *
   * @return view
   * @author bjchoi
   **/
  public function place_delete()
  {
    $now = time();
    $idx = html_escape($this->input->post('idx'));
    $userData = $this->load->get_var('userData');

    $updateValues = array(
      'deleted_by' => $userData['idx'],
      'deleted_at' => $now
    );
    $this->desk_model->update(DB_PLACES, $updateValues, $idx);

    redirect('/desk/place');
  }

  /**
   * 분류 편집
   *
   * @return view
   * @author bjchoi
   **/
  public function place_category_update()
  {
    $now = time();
    $inputData = $this->input->post();
    $message = '<option value="">분류를 선택해주세요</option>';

    if (!empty($inputData['category_code'][0]) && !empty($inputData['category_name'][0])) {
      $this->desk_model->delete(DB_PLACES_CATEGORY);

      foreach ($inputData['category_code'] as $key => $value) {
        if (!empty($value) && !empty($inputData['category_name'][$key])) {
          $code = html_escape($value);
          $name = html_escape($inputData['category_name'][$key]);
          $updateValues = array(
            'code' => $code,
            'name' => $name,
          );
          $message .= "<option value='" . $code . "'>" . $name . "</option>";
          $rtn = $this->desk_model->insert(DB_PLACES_CATEGORY, $updateValues);
        }
      }
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
    } else {
      $result = array('error' => 0, 'message' => $message);
    }

    $this->output->set_output(json_encode($result));
  }


  /**
    ====================================================================================================================
      기타
    ====================================================================================================================
  **/

  /**
   * 멀티 사진 업로드
   *
   * @return view
   * @author bjchoi
   **/
  public function upload()
  {
    $this->load->view('desk/upload');
  }

  /**
   * 멀티 사진 업로드 처리
   *
   * @return view
   * @author bjchoi
   **/
  public function upload_process()
  {
    $result = array();
    $files = $_FILES['files'];
    foreach ($files['tmp_name'] as $key => $value) {
      if (!empty($value)) {
        if ($files['type'][$key] == 'image/jpeg') {
          $ext = ".jpg";
        } else {
          $ext = ".jpg";
        }
        $filename = time() . mt_rand(10000, 99999) . $ext;
        if (move_uploaded_file($value, PHOTO_ARTICLE_PATH . $filename)) {
          // 썸네일 만들기
          $this->image_lib->clear();
          $config['image_library'] = 'gd2';
          $config['source_image'] = PHOTO_ARTICLE_PATH . $filename;
          $config['new_image'] = PHOTO_ARTICLE_PATH . 'thumb_' . $filename;
          $config['create_thumb'] = TRUE;
          $config['maintain_ratio'] = TRUE;
          $config['thumb_marker'] = '';
          $config['width'] = 500;
          $this->image_lib->initialize($config);
          $this->image_lib->resize();
        }
        $buf = array(
          'sFileName' => $filename,
          'sFileURL' => PHOTO_ARTICLE_URL . $filename,
          'bNewLine' => 'true'
        );
        array_push($result, $buf);
      }
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
    $this->load->view('desk/header');
    $this->load->view($viewPage, $viewData);
    $this->load->view('desk/footer');
  }
}
?>
