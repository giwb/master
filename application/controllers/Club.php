<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 첫 페이지 클래스
class Club extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('cookie', 'security', 'url', 'my_array_helper'));
    $this->load->library(array('image_lib', 'session'));
    $this->load->model(array('admin_mode', 'area_model', 'club_model', 'file_model', 'notice_model', 'member_model', 'reserve_model', 'story_model'));
  }

  /**
   * 클럽 메인 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function index()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    if (empty($userData['idx'])) $userData['idx'] = NULL;

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 등록된 산행 목록
    $viewData['listNoticeCalendar'] = $this->reserve_model->listNotice($clubIdx);

    // 캘린더 설정
    $listCalendar = $this->club_model->listCalendar();

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

    // 최초 스토리 로딩
    $paging['perPage'] = 5;
    $paging['nowPage'] = (1 * $paging['perPage']) - $paging['perPage'];
    $viewData['listStory'] = $this->story_model->listStory($clubIdx, $paging);
    $viewData['listStory'] = $this->load->view('story/index', $viewData, true);

    // 클럽 스토리
    //$viewData['listStory'] = $this->story_model->listStory($clubIdx, 5);

/*
    // 클럽 스토리 좋아요 확인 (로그인시에만)
    if (!empty($userData['idx'])) {
      $listStoryReaction = $this->story_model->listStoryReaction($clubIdx, $userData['idx']);

      foreach ($viewData['listStory'] as $key => $value) {
        foreach ($listStoryReaction as $reaction) {
          if ($value['idx'] == $reaction['story_idx']) {
            $viewData['listStory'][$key]['like'] = $reaction['created_by'];
          }
        }
      }
    }
*/
    $this->_viewPage('club/index', $viewData);
  }

  /**
   * 지난 산행보기
   *
   * @return view
   * @author bjchoi
   **/
  public function past()
  {
    $now = time();
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $sdate = html_escape($this->input->get('sdate'));
    $edate = html_escape($this->input->get('edate'));
    $keyword = html_escape($this->input->get('keyword'));

    $viewData['searchData']['keyword'] = !empty($keyword) ? $keyword : NULL;
    $viewData['searchData']['sdate'] = !empty($sdate) ? $sdate : date('Y-m-01', $now);
    $viewData['searchData']['edate'] = !empty($edate) ? $edate : date('Y-m-31', $now);
    $viewData['searchData']['syear'] = !empty($sdate) ? date('Y', strtotime($sdate)) : date('Y');
    $viewData['searchData']['smonth'] = !empty($sdate) ? date('m', strtotime($sdate)) : date('m');
    $viewData['searchData']['prev'] = 'sdate=' . date('Y-m-01', strtotime('-1 months', strtotime($viewData['searchData']['sdate']))) . '&edate=' . date('Y-m-31', strtotime('-1 months', strtotime($viewData['searchData']['sdate'])));
    $viewData['searchData']['next'] = 'sdate=' . date('Y-m-01', strtotime('+1 months', strtotime($viewData['searchData']['sdate']))) . '&edate=' . date('Y-m-31', strtotime('+1 months', strtotime($viewData['searchData']['sdate'])));

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 지난 산행
    $viewData['listPastNotice'] = $this->reserve_model->listNotice($clubIdx, array(STATUS_CLOSED), 'desc', $viewData['searchData']);

    $this->_viewPage('club/past', $viewData);
  }

  /**
   * 소개
   *
   * @return view
   * @author bjchoi
   **/
  public function about()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    $this->_viewPage('club/about', $viewData);
  }

  /**
   * 안내인 소개
   *
   * @return view
   * @author bjchoi
   **/
  public function guide()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    $this->_viewPage('club/guide', $viewData);
  }

  /**
   * 이용안내
   *
   * @return view
   * @author bjchoi
   **/
  public function howto()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    $this->_viewPage('club/howto', $viewData);
  }

  /**
   * 백산백소 소개
   *
   * @return view
   * @author bjchoi
   **/
  public function auth_about()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    $this->_viewPage('club/auth_about', $viewData);
  }

  /**
   * 백산백소 인증현황
   *
   * @return view
   * @author bjchoi
   **/
  public function auth()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $sDate = '2019-04-06';
    $nDate = date('Y-m-d');
    $rank = 0;
    $buf = 0;

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 백산백소 인증 데이터 불러오기
    $viewData['auth'] = $this->club_model->listAuth();

    foreach ($viewData['auth'] as $key => $value) {
      if ($buf != $value['cnt']) { $rank = $key; $rank++; }
      $viewData['auth'][$key]['rank'] = $rank;
      $viewData['auth'][$key]['title'] = '';

      $authList = $this->club_model->listAuthNotice($value['nickname']);
      foreach ($authList as $auth) {
        $viewData['auth'][$key]['title'] .= "<a target='_blank' href='" . $auth['photo'] . "'>" . $auth['title'] . "</a> / ";
      }
      $buf = $value['cnt'];
    }

    $this->_viewPage('club/auth', $viewData);
  }

  /**
   * 설정
   *
   * @return json
   * @author bjchoi
   **/
  public function setup()
  {
    checkAdminLogin();
    $clubIdx = $this->load->get_var('clubIdx');

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);
    $viewData['view']['club_type'] = is_null($viewData['view']['club_type']) || strlen($viewData['view']['club_type']) <= 3 ? array() : unserialize($viewData['view']['club_type']);
    $viewData['view']['club_option'] = is_null($viewData['view']['club_option']) || strlen($viewData['view']['club_option']) <= 3 ? array() : unserialize($viewData['view']['club_option']);
    $viewData['view']['club_cycle'] = is_null($viewData['view']['club_cycle']) || strlen($viewData['view']['club_cycle']) <= 3 ? array() : unserialize($viewData['view']['club_cycle']);
    $viewData['view']['club_week'] = is_null($viewData['view']['club_week']) || strlen($viewData['view']['club_week']) <= 3 ? array() : unserialize($viewData['view']['club_week']);
    $viewData['view']['club_geton'] = is_null($viewData['view']['club_geton']) || strlen($viewData['view']['club_geton']) <= 3 ? array() : unserialize($viewData['view']['club_geton']);
    $viewData['view']['club_getoff'] = is_null($viewData['view']['club_getoff']) || strlen($viewData['view']['club_getoff']) <= 3 ? array() : unserialize($viewData['view']['club_getoff']);
    $files = $this->file_model->getFile('club', $clubIdx);

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

    $this->_viewPage('club/setup', $viewData);
  }

  /**
   * 설정 수정
   *
   * @return json
   * @author bjchoi
   **/
  public function setup_update()
  {
    $now = time();
    $input_data = $this->input->post();
    $clubIdx = html_escape($input_data['clubIdx']);
    $file = html_escape($input_data['file']);
    $page = html_escape($input_data['page']);

    $update_values = array(
      'title'             => html_escape($input_data['title']),
      'homepage'          => html_escape($input_data['homepage']),
      'phone'             => html_escape($input_data['phone']),
      'area_sido'         => make_serialize($input_data['area_sido']),
      'area_gugun'        => make_serialize($input_data['area_gugun']),
      'establish'         => html_escape($input_data['establish']),
      'club_type'         => make_serialize($input_data['club_type']),
      'club_option'       => make_serialize($input_data['club_option']),
      'club_option_text'  => html_escape($input_data['club_option_text']),
      'club_cycle'        => make_serialize($input_data['club_cycle']),
      'club_week'         => make_serialize($input_data['club_week']),
      'club_geton'        => make_serialize($input_data['club_geton']),
      'club_getoff'       => make_serialize($input_data['club_getoff']),
      'about'             => html_escape($input_data['about']),
      'guide'             => html_escape($input_data['guide']),
      'howto'             => html_escape($input_data['howto']),
      'auth'              => html_escape($input_data['auth']),
      'updated_by'        => 1,
      'updated_at'        => $now
    );
    $result = $this->club_model->updateClub($update_values, $clubIdx);

    // 로고 파일 등록
    if (!empty($file)) {
      // 기존 로고 파일이 있는 경우 삭제
      $files = $this->file_model->getFile($page, $clubIdx);
      foreach ($files as $value) {
        $this->file_model->deleteFile($value['filename']);
        if (file_exists(PHOTO_PATH . $value['filename'])) unlink(PHOTO_PATH . $value['filename']);
      }

      // 업로드 된 로고 파일이 있을 경우에만 등록 후 이동
      if (file_exists(UPLOAD_PATH . $file)) {
        $file_values = array(
          'page' => $page,
          'page_idx' => $clubIdx,
          'filename' => $file,
          'created_at' => $now
        );
        $this->file_model->insertFile($file_values);

        // 파일 이동
        rename(UPLOAD_PATH . $file, PHOTO_PATH . $file);

        // 썸네일 만들기
        $this->image_lib->clear();
        $config['image_library'] = 'gd2';
        $config['source_image'] = PHOTO_PATH . $file;
        $config['new_image'] = PHOTO_PATH . 'thumb_' . $file;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = FALSE;
        $config['thumb_marker'] = '';
        $config['width'] = 200;
        $config['height'] = 200;
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
      }
    }
/*
    if (empty($result)) {
      $result = array(
        'error' => 1,
        'message' => '문제가 발생했습니다. 다시 시도해 주세요.'
      );
    } else {
      $result = array(
        'error' => 0,
        'message' => '수정이 완료되었습니다.'
      );
    }

    $this->output->set_output(json_encode($result));
*/
    redirect('/club/setup/' . $clubIdx);
  }

  /**
   * 구군 목록
   *
   * @return json
   * @author bjchoi
   **/
  public function list_gugun()
  {
    $parent = html_escape($this->input->post('parent'));
    $result = $this->area_model->listGugun($parent);
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
        $maxSize = 800;
        $size = getImageSize(UPLOAD_PATH . $filename);
        if ($size[0] >= $maxSize) {
          $this->image_lib->clear();
          $config['image_library'] = 'gd2';
          $config['source_image'] = UPLOAD_PATH . $filename;
          //$config['new_image'] = UPLOAD_PATH . 'thumb_' . $filename;
          $config['maintain_ratio'] = TRUE;
          $config['width'] = $maxSize;
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
    $viewData['uri'] = 'club';

    // 회원 정보
    $viewData['userData'] = $this->load->get_var('userData');
    $viewData['userLevel'] = $this->load->get_var('userLevel');

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
