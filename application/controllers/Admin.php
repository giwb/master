<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 관리 페이지 클래스
class Admin extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('url', 'my_array_helper'));
    $this->load->model(array('admin_model'));
  }

  /**
   * 관리자 인덱스
   *
   * @return view
   * @author bjchoi
   **/
  public function index()
  {
    // 등록된 산행 목록
    $viewData['listNotice'] = $this->admin_model->listNotice();

    // 현재 회원수
    $viewData['cntTotalMember'] = $this->admin_model->cntTotalMember();
    // 다녀온 산행횟수
    $viewData['cntTotalTour'] = $this->admin_model->cntTotalTour();
    // 다녀온 산행 인원수
    $viewData['cntTotalCustomer'] = $this->admin_model->cntTotalCustomer();

    $this->_viewPage('admin/index', $viewData);
  }

  /** ---------------------------------------------------------------------------------------
   * 산행관리
  --------------------------------------------------------------------------------------- **/

  /**
   * 진행중 산행 목록
   *
   * @return view
   * @author bjchoi
   **/
  public function main_list_progress()
  {
    $viewData['list'] = $this->admin_model->listProgress();

    $this->_viewPage('admin/main_list_progress', $viewData);
  }

  /**
   * 진행중 산행 예약 보기
   *
   * @return view
   * @author bjchoi
   **/
  public function main_view_progress($rescode)
  {
    $viewData['view'] = $this->admin_model->viewProgress(html_escape($rescode));

    $this->_viewPage('admin/main_view_progress', $viewData);
  }

  /**
   * 다녀온 산행 목록
   *
   * @return view
   * @author bjchoi
   **/
  public function main_list_closed()
  {
    // PHP Ver 7.x
    //$syear = !empty($this->input->get('syear')) ? $this->input->get('syear') : date('Y');
    //$smonth = !empty($this->input->get('smonth')) ? $this->input->get('syear') : date('m');

    // PHP Ver 5.x
    $syear = $this->input->get('syear') ? $this->input->get('syear') : date('Y');
    $smonth = $this->input->get('smonth') ? $this->input->get('syear') : date('m');
    $viewData['list'] = $this->admin_model->listClosed($syear, $smonth, STATUS_CLOSED);

    $this->_viewPage('admin/main_list_closed', $viewData);
  }

  /**
   * 취소된 산행 목록
   *
   * @return view
   * @author bjchoi
   **/
  public function main_list_canceled()
  {
    // PHP Ver 7.x
    //$syear = !empty($this->input->get('syear')) ? $this->input->get('syear') : date('Y');
    //$smonth = !empty($this->input->get('smonth')) ? $this->input->get('syear') : date('m');

    // PHP Ver 5.x
    $syear = $this->input->get('syear') ? $this->input->get('syear') : date('Y');
    $smonth = $this->input->get('smonth') ? $this->input->get('syear') : date('m');
    $viewData['list'] = $this->admin_model->listClosed($syear, $smonth, STATUS_CANCLE);

    $this->_viewPage('admin/main_list_canceled', $viewData);
  }

  /**
   * 산행 등록
   *
   * @return view
   * @author bjchoi
   **/
  public function main_entry($idx=NULL)
  {
    if (!is_null($idx)) {
      $viewData['btn'] = '수정';
      $viewData['view'] = $this->admin_model->viewEntry(html_escape($idx));
      $viewData['view']['bustype'] = unserialize($viewData['view']['bustype']);
      $viewData['view']['road_course'] = unserialize($viewData['view']['road_course']);
      $viewData['view']['road_distance'] = unserialize($viewData['view']['road_distance']);
      $viewData['view']['road_runtime'] = unserialize($viewData['view']['road_runtime']);
      $viewData['view']['road_cost'] = unserialize($viewData['view']['road_cost']);
      $viewData['view']['driving_fuel'] = unserialize($viewData['view']['driving_fuel']);
      $viewData['view']['driving_cost'] = unserialize($viewData['view']['driving_cost']);
      $viewData['view']['driving_add'] = unserialize($viewData['view']['driving_add']);
    } else {
      $viewData['btn'] = '등록';
      $viewData['view']['idx'] = '';
      $viewData['view']['startdate'] = '';
      $viewData['view']['starttime'] = '';
      $viewData['view']['enddate'] = '';
      $viewData['view']['mname'] = '';
      $viewData['view']['subject'] = '';
      $viewData['view']['content'] = '';
      $viewData['view']['bustype'] = '';
      $viewData['view']['article'] = '';
      $viewData['view']['peak'] = '';
      $viewData['view']['winter'] = '';
      $viewData['view']['distance'] = '';
      $viewData['view']['road_course'][0] = '기본운행구간';
      $viewData['view']['road_distance'][0] = '43.44';
      $viewData['view']['road_runtime'][0] = '0:50';
      $viewData['view']['road_cost'][0] = '0';
      $viewData['view']['driving_fuel'] = '';
      $viewData['view']['driving_cost'] = '';
      $viewData['view']['driving_add'] = '';
      $viewData['view']['driving_total'] = '';
      $viewData['view']['cost'] = '';
      $viewData['view']['cost_added'] = '';
      $viewData['view']['cost_total'] = '';
      $viewData['view']['costmemo'] = '';
    }

    $viewData['listBustype'] = $this->admin_model->listBustype();

    $this->_viewPage('admin/main_entry', $viewData);
  }

  /**
   * 산행 등록 처리
   *
   * @return view
   * @author bjchoi
   **/
  public function main_entry_update()
  {
    $idx = html_escape($this->input->post('idx'));
    $result = array();

    if (!$this->input->post('subject') || !$this->input->post('startdate') || !$this->input->post('enddate')) {
      $result = array('error' => 1, 'message' => '에러가 발생했습니다.');
    } else {
      $postData = array(
        'startdate'       => html_escape($this->input->post('startdate')),        // 출발일시
        'starttime'       => html_escape($this->input->post('starttime')),        // 출발시간
        'enddate'         => html_escape($this->input->post('enddate')),          // 도착일자
        'mname'           => html_escape($this->input->post('mname')),            // 산 이름
        'subject'         => html_escape($this->input->post('subject')),          // 산행제목
        'content'         => html_escape($this->input->post('content')),          // 산행코스
        'bustype'         => make_serialize($this->input->post('bustype')),       // 차량
        'article'         => html_escape($this->input->post('article')),          // 메모
        'distance'        => html_escape($this->input->post('distance')),         // 운행거리
        'road_course'     => make_serialize($this->input->post('road_course')),   // 운행구간
        'road_distance'   => make_serialize($this->input->post('road_distance')), // 거리
        'road_runtime'    => make_serialize($this->input->post('road_runtime')),  // 소요시간
        'road_cost'       => make_serialize($this->input->post('road_cost')),     // 통행료
        'driving_default' => html_escape($this->input->post('driving_default')),  // 버스 기본요금
        'driving_fuel'    => make_serialize($this->input->post('driving_fuel')),  // 주유비
        'driving_cost'    => make_serialize($this->input->post('driving_cost')),  // 운행비
        'driving_add'     => make_serialize($this->input->post('driving_add')),   // 추가비용
        'driving_total'   => html_escape($this->input->post('driving_total')),    // 운행견적총액
        'cost'            => html_escape($this->input->post('cost')),             // 산행분담금 기본비용
        'cost_added'      => html_escape($this->input->post('cost_added')),       // 산행분담금 추가비용
        'cost_total'      => html_escape($this->input->post('cost_total')),       // 산행분담금 합계
        'costmemo'        => html_escape($this->input->post('cost_memo')),        // 포함사항
      );

      if (!$idx) {
        // 등록
        $rtn = $this->admin_model->insertEntry($postData);
      } else {
        // 수정
        $rtn = $this->admin_model->updateEntry($postData, $idx);
      }

      if (!$rtn) {
        $result = array('error' => 1, 'message' => '에러가 발생했습니다.');
      } else {
        $result = array('error' => 0, 'message' => '');
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /** ---------------------------------------------------------------------------------------
   * 회원관리
  --------------------------------------------------------------------------------------- **/

  /**
   * 전체 회원 목록
   *
   * @return view
   * @author bjchoi
   **/
  public function member_list()
  {
    $viewData['list'] = $this->admin_model->listMembers();

    $this->_viewPage('admin/member_list', $viewData);
  }

  /**
   * 회원 정보 보기
   *
   * @return view
   * @author bjchoi
   **/
  public function member_view($idx)
  {
    $viewData['view'] = $this->admin_model->viewMember(html_escape($idx));
    $viewData['view']['birthday'] = explode('/', $viewData['view']['birthday']);
    $viewData['view']['phone'] = explode('-', $viewData['view']['phone']);

    // 산행/예약횟수 구하기
    $max = 0; $res = 0;
    $viewData['view']['cntPersonalReservation'] = $this->admin_model->cntPersonalReservation($viewData['view']['userid']);
    foreach ($viewData['view']['cntPersonalReservation'] as $value) {
      // 예약횟수는 자신 이외의 사람을 포함하여 전체 예약한 숫자
      $max += $value['cnt'];

      // 산행횟수는 자신만 카운트
      if ($value['cnt'] != 0) {
        $res++;
      }
    }

    // 산행횟수
    $viewData['view']['cntPersonalReservation'] = $res;

    // 예약횟수
    $viewData['view']['cntTotalReservation'] = $max;

    // 레벨
    $viewData['view']['memberLevel'] = $viewData['view']['cntTotalReservation'] - $viewData['view']['penalty'];

    $this->_viewPage('admin/member_view', $viewData);
  }

  /**
   * 회원 정보 수정
   *
   * @return view
   * @author bjchoi
   **/
  public function member_update()
  {
    $result = 0;
    $this->output->set_output(json_encode($result));
  }

  /**
   * 회원 정보 삭제
   *
   * @return view
   * @author bjchoi
   **/
  public function member_delete()
  {
    $idx = html_escape($this->input->post());

    $result = 0;
    $this->output->set_output(json_encode($result));
  }

  /**
   * 회원 등록
   *
   * @return view
   * @author bjchoi
   **/
  public function member_entry()
  {
    $viewData = array();
    $this->_viewPage('admin/member_entry', $viewData);
  }

  /** ---------------------------------------------------------------------------------------
   * 출석현황
  --------------------------------------------------------------------------------------- **/

  /**
   * 출석체크 보기
   *
   * @return view
   * @author bjchoi
   **/
  public function attendance_list()
  {
    // PHP Ver 7.x
    //$viewData['viewType'] = !empty($this->input->get('action')) ? $this->input->get('action') : '';

    // PHP Ver 5.x
    $viewData['viewType'] = $this->input->get('action') ? $this->input->get('action') : '';
    $cnt = 0;
    $buf = 0;

    // 산행 추출
    $dateStart = '2019-04-06';
    $dateEnd = date('Y-m-d');
    $viewData['listAttendanceNotice'] = $this->admin_model->listAttendanceNotice($dateStart, $dateEnd);
    $maxNotice = count($viewData['listAttendanceNotice']);
    $viewData['width'] = 88 / ($maxNotice);

    // 랭킹별 닉네임 추출
    $viewData['listNickname'] = $this->admin_model->listAttendanceNickname();

    // PHP Ver 7.x
    //if (!empty($viewData['listNickname'])) {
    // PHP Ver 5.x
    if ($viewData['listNickname'] != '') {
      foreach ($viewData['listNickname'] as $key => $list) {
        if ($buf != $list['cnt']) {
          $viewData['listNickname'][$key]['rank'] = $key;
          $viewData['listNickname'][$key]['rank']++;
        }

        foreach ($viewData['listAttendanceNotice'] as $notice) {
          $viewData['listNickname'][$key]['listNotice'][] = $this->admin_model->checkAttendance($notice['idx'], $list['nickname']);
        }
      }
    }

    $this->_viewPage('admin/attendance_list', $viewData);
  }

  /**
   * 산행지로 보기
   *
   * @return view
   * @author bjchoi
   **/
  public function attendance_mountain()
  {
    $viewData = array();
    $this->_viewPage('admin/attendance_mountain', $viewData);
  }

  /**
   * 출석체크 추출
   *
   * @return redirect
   * @author bjchoi
   **/
  public function attendance_make()
  {
    // 산행 추출
    $dateStart = '2019-04-06';
    $dateEnd = date('Y-m-d');
    $dataNotice = $this->admin_model->listAttendanceNotice($dateStart, $dateEnd);
    $maxNotice = count($dataNotice);
    $listMember = array();

    foreach ($dataNotice as $value) {
      $date = date("ymd", strtotime($value['startdate']));
      $entryMember = $this->admin_model->getAttendanceNickname($value['idx']);

      $cnt = 0;
      foreach ($entryMember as $key => $entry) {
        // PHP Ver 7.x
        //if (!empty($arrDummy[$value['idx']])) {
        // PHP Ver 5.x
        if ($arrDummy[$value['idx']] != '') {
          $cnt = 1;
          foreach ($arrDummy[$value['idx']] as $list) {
            if ($list == $entry['nickname']) $cnt++;
          }
        }
        $arrDummy[$value['idx']][] = $entry['nickname'];
        if ($cnt > 1) $nickname = $entry['nickname'] . $cnt; else $nickname = $entry['nickname'];
        $listMember[$value['idx']][] = $nickname;
      }
    }

    // 이전 데이터 삭제
    $this->admin_model->deleteAttendance();

    // 새로운 데이터 갱신
    foreach ($listMember as $key => $list) {
      foreach ($list as $nickname) {
        $insertData = array(
          'rescode' => $key,
          'nickname' => $nickname,
        );
        $rtn = $this->admin_model->insertAttendance($insertData);
      }
    }

    // PHP Ver 7.x
    //if (empty($rtn)) {
    // PHP Ver 5.x
    if (!$rtn) {
      $result['message'] = '에러가 발생했습니다.';
    } else {
      $result['message'] = '최신 데이터로 갱신했습니다.';
    }

    $this->output->set_output(json_encode($result));
  }

  /** ---------------------------------------------------------------------------------------
   * 활동관리
  --------------------------------------------------------------------------------------- **/

  /**
   * 활동관리 - 회원 활동 목록
   *
   * @return view
   * @author bjchoi
   **/
  public function log_user()
  {
    $viewData = array();
    $this->_viewPage('admin/log_user', $viewData);
  }

  /**
   * 활동관리 - 관리자 활동 목록
   *
   * @return view
   * @author bjchoi
   **/
  public function log_admin()
  {
    $viewData = array();
    $this->_viewPage('admin/log_admin', $viewData);
  }

  /** ---------------------------------------------------------------------------------------
   * 설정
  --------------------------------------------------------------------------------------- **/

  /**
   * 설정 - 대문관리
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_front()
  {
    $viewData['listFront'] = $this->admin_model->listFront();

    $this->_viewPage('admin/setup_front', $viewData);
  }

  /**
   * 설정 - 대문등록
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_front_insert()
  {
    if ($_FILES['file_obj']['type'] == 'image/jpeg') {
      $filename = time() . mt_rand(10000, 99999) . ".jpg";

      if (move_uploaded_file($_FILES['file_obj']['tmp_name'], PATH_FRONT . $filename)) {
        $maxIdx = $this->admin_model->getFrontSortMaxIdx();
        $insertData = array(
          'sort_idx' => $maxIdx['sort_idx'] + 1,
          'filename' => $filename,
        );
        $this->admin_model->insertFront($insertData);

        $result = array(
          'error' => 0,
          'message' => base_url() . URL_FRONT . $filename,
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
   * 설정 - 정렬 순서 수정
   *
   * @return view
   * @author bjchoi
   **/

  public function setup_front_sort()
  {
    $sortIdx = html_escape($this->input->post('sort_idx'));
    $listFront = $this->admin_model->listFront();

    foreach ($listFront as $key => $value) {
      $result = $this->admin_model->updateFrontSortIdx($value['filename'], $sortIdx[$key]);
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 설정 - 대문삭제
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_front_delete()
  {
    $filename = html_escape($this->input->post('idx'));

    // 데이터 삭제
    $result = $this->admin_model->deleteFront($filename);

    // 파일 삭제
    if (file_exists(PATH_FRONT . $filename)) {
      unlink(PATH_FRONT . $filename);
    }

    // 정렬 순서 갱신
    $listFront = $this->admin_model->listFront();

    foreach ($listFront as $key => $value) {
      $this->admin_model->updateFrontSortIdx($value['filename'], $key + 1);
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 설정 - 달력관리
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_calendar()
  {
    $viewData = array();
    $this->_viewPage('admin/setup_calendar', $viewData);
  }

  /**
   * 설정 - 차종등록
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_bustype()
  {
    $viewData['listBustype'] = $this->admin_model->listBustype();

    $this->_viewPage('admin/setup_bustype', $viewData);
  }

  /**
   * 설정 - 차종등록 추가
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_bustype_add($idx=NULL)
  {
    if (!is_null($idx)) {
      $idx = html_escape($idx);
      $viewData['viewBustype'] = $this->admin_model->getBustype($idx);
      $viewData['action'] = "setup_bustype_update";
      $viewData['btnName'] = "수정합니다";
    } else {
      $viewData['viewBustype']['idx'] = '';
      $viewData['viewBustype']['bus_name'] = '';
      $viewData['viewBustype']['bus_owner'] = '';
      $viewData['viewBustype']['bus_seat'] = '';
      $viewData['action'] = "setup_bustype_insert";
      $viewData['btnName'] = "등록합니다";
    }

    $viewData['listBusdata'] = $this->admin_model->listBusdata();

    $this->_viewPage('admin/setup_bustype_add', $viewData);
  }

  /**
   * 설정 - 차종등록 추가완료
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_bustype_insert()
  {
    $now = time();
    $bus_name   = $this->input->post('bus_name') != '' ? html_escape($this->input->post('bus_name')) : NULL;
    $bus_owner  = $this->input->post('bus_owner') != '' ? html_escape($this->input->post('bus_owner')) : NULL;
    $bus_seat   = $this->input->post('bus_seat') != '' ? html_escape($this->input->post('bus_seat')) : NULL;

    $insertData = array(
      'bus_name' => $bus_name,
      'bus_owner' => $bus_owner,
      'bus_seat' => $bus_seat,
      'created_at' => $now,
    );
    $result = $this->admin_model->insertBustype($insertData);

    $this->output->set_output(json_encode($result));
  }

  /**
   * 설정 - 차종등록 수정
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_bustype_update()
  {
    $idx        = $this->input->post('idx') != '' ? html_escape($this->input->post('idx')) : NULL;
    $bus_name   = $this->input->post('bus_name') != '' ? html_escape($this->input->post('bus_name')) : NULL;
    $bus_owner  = $this->input->post('bus_owner') != '' ? html_escape($this->input->post('bus_owner')) : NULL;
    $bus_seat   = $this->input->post('bus_seat') != '' ? html_escape($this->input->post('bus_seat')) : NULL;
    $result     = 0;

    if (!is_null($idx)) {
      $updateData = array(
        'bus_name' => $bus_name,
        'bus_owner' => $bus_owner,
        'bus_seat' => $bus_seat,
      );

      $result = $this->admin_model->updateBustype($updateData, $idx);
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 설정 - 차종 삭제
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_bustype_delete()
  {
    $idx = html_escape($this->input->post('idx'));

    $result = $this->admin_model->deleteBustype($idx);

    $this->output->set_output(json_encode($result));
  }

  /**
   * 설정 - 문자양식보기
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_sms()
  {
    $viewData = array();
    $this->_viewPage('admin/setup_sms', $viewData);
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
    $headerData['uri'] = $_SERVER['REQUEST_URI'];
    $this->load->view('admin/header', $headerData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('admin/footer');
  }
}
?>
