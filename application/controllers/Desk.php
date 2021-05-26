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
    $this->load->model(array('area_model', 'club_model', 'desk_model', 'file_model'));
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
   * @return json
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
        if ($_FILES['main_image']['type'] == 'image/gif') {
          $ext = ".gif";
        } elseif ($_FILES['main_image']['type'] == 'image/png') {
          $ext = ".png";
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
   * @return redirect
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
   * @return redirect
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
   * 기사 분류 편집
   *
   * @return json
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
    $viewData['keyword'] = $this->input->get('keyword');
    $viewData['sort'] = $this->input->get('sort');
    $viewData['cate'] = $this->input->get('cate');

    if (!empty($viewData['sort'])) {
      set_cookie('COOKIE_SORT', $viewData['sort'], COOKIE_STRAGE_PERIOD);
    } else {
      $viewData['sort'] = get_cookie('COOKIE_SORT');
    }

    $viewData['category'] = $this->desk_model->listPlaceCategory();
    $viewData['list'] = $this->desk_model->listPlace($viewData);

    // 최대 정보
    $max = $this->desk_model->listPlace();
    $viewData['max'] = count($max);

    foreach ($viewData['category'] as $key => $value) {
      $search['cate'] = $value['code'];
      $category = $this->desk_model->listPlace($search);
      $viewData['category'][$key]['count'] = count($category);
    }

    $this->_viewPage('desk/place', $viewData);
  }

  /**
   * 여행정보 등록
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
   * 여행정보 등록/수정
   *
   * @return json
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
        if ($_FILES['thumbnail']['type'] == 'image/gif') {
          $ext = ".gif";
        } elseif ($_FILES['thumbnail']['type'] == 'image/png') {
          $ext = ".png";
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
          'area_sido'   => make_serialize($inputData['area_sido']),
          'area_gugun'  => make_serialize($inputData['area_gugun']),
          'category'    => make_serialize($inputData['category']),
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
          'area_sido'   => make_serialize($inputData['area_sido']),
          'area_gugun'  => make_serialize($inputData['area_gugun']),
          'category'    => make_serialize($inputData['category']),
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
   * 여행정보 삭제
   *
   * @return redirect
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
   * 여행정보 분류 편집
   *
   * @return json
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
      여행일정 관리 섹션
    ====================================================================================================================
  **/

  /**
   * 여행일정 관리
   *
   * @return view
   * @author bjchoi
   **/
  public function schedule()
  {
    $viewData['list'] = $this->desk_model->listSchedule();
    $viewData['max'] = count($viewData['list']);
    $cnt = 0;

    foreach ($viewData['list'] as $key1 => $value) {
      // 지역
      $viewData['area_sido'] = $this->area_model->listSido();
      if (!empty($value['area_sido'])) {
        $area_sido = unserialize($value['area_sido']);
        $area_gugun = unserialize($value['area_gugun']);

        foreach ($area_sido as $key2 => $value2) {
          $sido = $this->area_model->getName($value2);
          $gugun = $this->area_model->getName($area_gugun[$key2]);
          $viewData['list'][$key1]['sido'][$key2] = $sido['name'];
          $viewData['list'][$key1]['gugun'][$key2] = $gugun['name'];
        }
      }
    }

    $this->_viewPage('desk/schedule', $viewData);
  }

  /**
   * 여행일정 등록
   *
   * @return view
   * @author bjchoi
   **/
  public function schedule_post($idx=NULL)
  {
    $viewData['userData'] = $this->load->get_var('userData');

    if (!is_null($idx)) {
      $viewData['view'] = $this->desk_model->viewSchedule(html_escape($idx));
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

    $this->_viewPage('desk/schedule_post', $viewData);
  }

  /**
   * 여행일정 등록/수정
   *
   * @return json
   * @author bjchoi
   **/
  public function schedule_update()
  {
    $now = time();
    $inputData = $this->input->post();

    if (empty($inputData['title'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_no_title'));
    }
    if (empty($inputData['agency_name'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_no_agency'));
    }

    if (empty($result)) {
      if (!empty($inputData['idx'])) {
        $idx = html_escape($inputData['idx']);
        $updateValues = array(
          'agency_name' => html_escape($inputData['agency_name']),
          'title'       => html_escape($inputData['title']),
          'link'        => html_escape($inputData['link']),
          'area_sido'   => make_serialize(html_escape($inputData['area_sido'])),
          'area_gugun'  => make_serialize(html_escape($inputData['area_gugun'])),
          'startdate'   => html_escape($inputData['startdate']),
          'starttime'   => html_escape($inputData['starttime']),
          'cost'        => html_escape($inputData['cost']),
          'distance'    => html_escape($inputData['distance']),
          'updated_by'  => html_escape($inputData['useridx']),
          'updated_at'  => $now,
        );
        $this->desk_model->update(DB_SCHEDULES, $updateValues, $idx);
      } else {
        $updateValues = array(
          'agency_name' => html_escape($inputData['agency_name']),
          'title'       => html_escape($inputData['title']),
          'link'        => html_escape($inputData['link']),
          'area_sido'   => make_serialize(html_escape($inputData['area_sido'])),
          'area_gugun'  => make_serialize(html_escape($inputData['area_gugun'])),
          'startdate'   => html_escape($inputData['startdate']),
          'starttime'   => html_escape($inputData['starttime']),
          'cost'        => html_escape($inputData['cost']),
          'distance'    => html_escape($inputData['distance']),
          'created_by'  => html_escape($inputData['useridx']),
          'created_at'  => $now,
        );
        $this->desk_model->insert(DB_SCHEDULES, $updateValues);
      }
      $result = array('error' => 0, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 여행일정 삭제
   *
   * @return redirect
   * @author bjchoi
   **/
  public function schedule_delete()
  {
    $now = time();
    $idx = html_escape($this->input->post('idx'));
    $userData = $this->load->get_var('userData');

    $updateValues = array(
      'deleted_by' => $userData['idx'],
      'deleted_at' => $now
    );
    $this->desk_model->update(DB_SCHEDULES, $updateValues, $idx);

    redirect('/desk/schedule');
  }


  /**
    ====================================================================================================================
      산악회 관리 섹션
    ====================================================================================================================
  **/

  /**
   * 산악회 관리
   *
   * @return view
   * @author bjchoi
   **/
  public function club()
  {
    $viewData['list'] = $this->club_model->listClub(NULL, NULL, true);
    $viewData['max'] = count($viewData['list']);

    foreach ($viewData['list'] as $key => $value) {
      $file = $this->file_model->getFile('club', $value['idx'], NULL, 1);
      if (!empty($file[0]['filename'])) {
        $viewData['list'][$key]['thumbnail'] = UPLOAD_CLUB_URL . $value['idx'] . '/thumb_' . $file[0]['filename'];
      } else {
        $viewData['list'][$key]['thumbnail'] = '/public/images/noimage.png';
      }
    }

    $this->_viewPage('desk/club', $viewData);
  }

  /**
   * 산악회 등록
   *
   * @return view
   * @author bjchoi
   **/
  public function club_post($idx=NULL)
  {
    $viewData['userData'] = $this->load->get_var('userData');

    if (!is_null($idx)) {
      $viewData['view'] = $this->club_model->viewClub(html_escape($idx));
    }

    // 지역
    $viewData['area_sido'] = $this->area_model->listSido();
    if (!empty($viewData['view']['area_sido'])) {
      $area_sido = unserialize($viewData['view']['area_sido']);
      $area_gugun = unserialize($viewData['view']['area_gugun']);

      foreach ($area_sido as $key => $value) {
        $sido = $this->area_model->getName($value);
        if (!empty($area_gugun[$key])) $gugun = $this->area_model->getName($area_gugun[$key]); else $gugun['name'] = '';
        $viewData['list_sido'] = $this->area_model->listSido();
        $viewData['list_gugun'][$key] = $this->area_model->listGugun($value);
        $viewData['view']['sido'][$key] = $sido['name'];
        if (!empty($area_gugun[$key])) $viewData['view']['gugun'][$key] = $gugun['name'];
      }

      $viewData['area_gugun'] = $this->area_model->listGugun($viewData['view']['area_sido']);
    } else {
      $viewData['area_gugun'] = array();
    }

    $this->_viewPage('desk/club_post', $viewData);
  }

  /**
   * 산악회 등록/수정
   *
   * @return json
   * @author bjchoi
   **/
  public function club_update()
  {
    $now = time();
    $inputData = $this->input->post();

    if (empty($inputData['title'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_no_title'));
    }
    if (empty($inputData['phone'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_no_phone'));
    }
    if (empty($inputData['establish'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_no_establish'));
    }
    if (empty($inputData['about'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_no_content'));
    }

    if (empty($result)) {
      if (!empty($inputData['idx'])) {
        $idx = html_escape($inputData['idx']);
        $file = $this->file_model->getFile('club', $idx);
        $updateValues = array(
          'url'         => html_escape($inputData['url']),
          'homepage'    => html_escape($inputData['homepage']),
          'title'       => html_escape($inputData['title']),
          'phone'       => html_escape($inputData['phone']),
          'area_sido'   => make_serialize(html_escape($inputData['area_sido'])),
          'area_gugun'  => make_serialize(html_escape($inputData['area_gugun'])),
          'main_design' => html_escape($inputData['main_design']),
          'main_color'  => html_escape($inputData['main_color']),
          'establish'   => html_escape($inputData['establish']),
          'about'       => html_escape($inputData['about']),
          'updated_by'  => html_escape($inputData['useridx']),
          'updated_at'  => $now,
        );
        $this->desk_model->update(DB_CLUBS, $updateValues, $idx);
      } else {
        $updateValues = array(
          'url'         => html_escape($inputData['url']),
          'homepage'    => html_escape($inputData['homepage']),
          'title'       => html_escape($inputData['title']),
          'phone'       => html_escape($inputData['phone']),
          'area_sido'   => make_serialize(html_escape($inputData['area_sido'])),
          'area_gugun'  => make_serialize(html_escape($inputData['area_gugun'])),
          'main_design' => html_escape($inputData['main_design']),
          'main_color'  => html_escape($inputData['main_color']),
          'establish'   => html_escape($inputData['establish']),
          'about'       => html_escape($inputData['about']),
          'created_by'  => html_escape($inputData['useridx']),
          'created_at'  => $now,
        );
        $idx = $this->desk_model->insert(DB_CLUBS, $updateValues);
      }

      $clubPath = UPLOAD_CLUB_PATH . $idx;
      if (!empty($idx) && !file_exists($clubPath)) {
        mkdir($clubPath);
      }

      if (!empty($_FILES['thumbnail']['tmp_name'])) {
        // 썸네일 이미지 처리
        if ($_FILES['thumbnail']['type'] == 'image/gif') {
          $ext = ".gif";
        } elseif ($_FILES['thumbnail']['type'] == 'image/png') {
          $ext = ".png";
        } else {
          $ext = ".jpg";
        }
        $inputData['thumbnail_uploaded'] = time() . mt_rand(10000, 99999) . $ext;
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], $clubPath  . '/' . $inputData['thumbnail_uploaded']);

        // 썸네일 만들기
        $this->image_lib->clear();
        $config['image_library'] = 'gd2';
        $config['source_image'] = $clubPath  . '/' . $inputData['thumbnail_uploaded'];
        $config['new_image'] = $clubPath  . '/thumb_' . $inputData['thumbnail_uploaded'];
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['thumb_marker'] = '';
        $config['width'] = 500;
        $this->image_lib->initialize($config);
        $this->image_lib->resize();

        // 기존 파일 데이터가 있었다면 삭제
        if (!empty($file[0]['filename'])) {
          $this->file_model->deleteFile($file[0]['filename']);
        }

        // 파일 데이터 저장
        $fileValues = array(
          'page' => 'club',
          'page_idx' => $idx,
          'filename' => $inputData['thumbnail_uploaded'],
          'created_at' => $now
        );
        $this->file_model->insertFile($fileValues);
      }

      $result = array('error' => 0, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 산악회 삭제
   *
   * @return redirect
   * @author bjchoi
   **/
  public function club_delete()
  {
    $now = time();
    $idx = html_escape($this->input->post('idx'));
    $userData = $this->load->get_var('userData');

    $updateValues = array(
      'deleted_by' => $userData['idx'],
      'deleted_at' => $now
    );
    $this->desk_model->update(DB_CLUBS, $updateValues, $idx);

    redirect('/desk/club');
  }


  /**
    ====================================================================================================================
      현지영상 관리 섹션
    ====================================================================================================================
  **/

  /**
   * 현지영상 관리
   *
   * @return view
   * @author bjchoi
   **/
  public function cctv()
  {
    $viewData['list'] = $this->desk_model->listCctv();
    $viewData['max'] = count($viewData['list']);
    $this->_viewPage('desk/cctv', $viewData);
  }

  /**
   * 현지영상 등록
   *
   * @return view
   * @author bjchoi
   **/
  public function cctv_post($idx=NULL)
  {
    $viewData['userData'] = $this->load->get_var('userData');
    $viewData['category'] = $this->desk_model->listCctvCategory();

    if (!is_null($idx)) {
      $viewData['view'] = $this->desk_model->viewCctv(html_escape($idx));
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

    $this->_viewPage('desk/cctv_post', $viewData);
  }

  /**
   * 현지영상 등록/수정
   *
   * @return json
   * @author bjchoi
   **/
  public function cctv_update()
  {
    $now = time();
    $inputData = $this->input->post();

    if (empty($inputData['title'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_no_title'));
    }
    if (empty($inputData['category'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_no_category'));
    }
    if (empty($inputData['link'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_no_url'));
    }

    if (empty($result)) {
      if (!empty($_FILES['thumbnail']['tmp_name'])) {
        // 메인 이미지 처리
        if ($_FILES['thumbnail']['type'] == 'image/gif') {
          $ext = ".gif";
        } elseif ($_FILES['thumbnail']['type'] == 'image/png') {
          $ext = ".png";
        } else {
          $ext = ".jpg";
        }
        $inputData['thumbnail_uploaded'] = time() . mt_rand(10000, 99999) . $ext;
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], CCTV_THUMBNAIL_PATH . $inputData['thumbnail_uploaded']);

        // 썸네일 만들기
        $this->image_lib->clear();
        $config['image_library'] = 'gd2';
        $config['source_image'] = CCTV_THUMBNAIL_PATH . $inputData['thumbnail_uploaded'];
        $config['new_image'] = CCTV_THUMBNAIL_PATH . $inputData['thumbnail_uploaded'];
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['thumb_marker'] = '';
        $config['width'] = 437;
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
      }

      if (!empty($inputData['idx'])) {
        $idx = html_escape($inputData['idx']);

        // 기존 썸네일 삭제하기
        $viewCctv = $this->desk_model->viewCctv($idx);
        if (!empty($viewCctv['thumbnail']) && file_exists(CCTV_THUMBNAIL_PATH . $viewCctv['thumbnail'])) {
          unlink(CCTV_THUMBNAIL_PATH . $viewCctv['thumbnail']);
        }

        $updateValues = array(
          'category'    => html_escape($inputData['category']),
          'title'       => html_escape($inputData['title']),
          'link'        => html_escape($inputData['link']),
          'thumbnail'   => !empty($inputData['thumbnail_uploaded']) ? html_escape($inputData['thumbnail_uploaded']) : NULL,
          'updated_by'  => html_escape($inputData['useridx']),
          'updated_at'  => $now,
        );
        $this->desk_model->update(DB_CCTVS, $updateValues, $idx);
      } else {
        $updateValues = array(
          'category'    => html_escape($inputData['category']),
          'title'       => html_escape($inputData['title']),
          'link'        => html_escape($inputData['link']),
          'thumbnail'   => !empty($inputData['thumbnail_uploaded']) ? html_escape($inputData['thumbnail_uploaded']) : NULL,
          'created_by'  => html_escape($inputData['useridx']),
          'created_at'  => $now,
        );
        $this->desk_model->insert(DB_CCTVS, $updateValues);
      }
      $result = array('error' => 0, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 현지영상 삭제
   *
   * @return redirect
   * @author bjchoi
   **/
  public function cctv_delete()
  {
    $now = time();
    $idx = html_escape($this->input->post('idx'));
    $userData = $this->load->get_var('userData');

    $updateValues = array(
      'deleted_by' => $userData['idx'],
      'deleted_at' => $now
    );
    $this->desk_model->update(DB_CCTVS, $updateValues, $idx);

    redirect('/desk/cctv');
  }

  /**
   * 현지영상 분류 편집
   *
   * @return json
   * @author bjchoi
   **/
  public function cctv_category_update()
  {
    $now = time();
    $inputData = $this->input->post();
    $message = '<option value="">분류를 선택해주세요</option>';

    if (!empty($inputData['category_code'][0]) && !empty($inputData['category_name'][0])) {
      $this->desk_model->delete(DB_CCTVS_CATEGORY);

      foreach ($inputData['category_code'] as $key => $value) {
        if (!empty($value) && !empty($inputData['category_name'][$key])) {
          $code = html_escape($value);
          $name = html_escape($inputData['category_name'][$key]);
          $updateValues = array(
            'code' => $code,
            'name' => $name,
          );
          $message .= "<option value='" . $code . "'>" . $name . "</option>";
          $rtn = $this->desk_model->insert(DB_CCTVS_CATEGORY, $updateValues);
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
   * @return json
   * @author bjchoi
   **/
  public function upload_process()
  {
    $result = array();
    $files = $_FILES['files'];
    foreach ($files['tmp_name'] as $key => $value) {
      if (!empty($value)) {
        if ($files['type'][$key] == 'image/gif') {
          $ext = ".gif";
        } elseif ($files['type'][$key] == 'image/png') {
          $ext = ".png";
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
