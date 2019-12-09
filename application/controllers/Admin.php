<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 관리 페이지 클래스
class Admin extends Admin_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('url', 'my_array_helper'));
    $this->load->library('session');
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
    $clubIdx = 1; // 최초는 경인웰빙

    // 등록된 산행 목록
    $viewData['listNotice'] = $this->admin_model->listNotice();

    // 캘린더 설정
    $listCalendar = $this->admin_model->listCalendar();

    foreach ($listCalendar as $key => $value) {
      if ($value['holiday'] == 1) {
        $class = 'holiday';
      } else {
        $class = 'dayname';
      }
      $viewData['listNotice'][] = array(
        'idx' => 0,
        'startdate' => $value['nowdate'],
        'enddate' => $value['nowdate'],
        'schedule' => 0,
        'status' => 'schedule',
        'mname' => $value['dayname'],
        'class' => $class,
      );
    }

    // 현재 회원수
    $viewData['cntTotalMember'] = $this->admin_model->cntTotalMember();
    // 다녀온 산행횟수
    $viewData['cntTotalTour'] = $this->admin_model->cntTotalTour();
    // 다녀온 산행 인원수
    $viewData['cntTotalCustomer'] = $this->admin_model->cntTotalCustomer();
    // 오늘 방문자수
    $viewData['cntTodayVisitor'] = $this->admin_model->cntTodayVisitor($clubIdx);

    $this->_viewPage('admin/index', $viewData);
  }

  /** ---------------------------------------------------------------------------------------
   * 예약
  --------------------------------------------------------------------------------------- **/

  /**
   * 예약 정보
   *
   * @return json
   * @author bjchoi
   **/
  public function reserve_information()
  {
    $idx = html_escape($this->input->post('idx'));
    $viewData['idx'] = html_escape($this->input->post('resIdx'));
    $viewData['view'] = $this->admin_model->viewEntry($idx);

    if (!empty($viewData['idx'])) {
      $result['reserve'] = $this->admin_model->viewReserve($viewData);
      if (empty($result['reserve']['depositname'])) $result['reserve']['depositname'] = '';
      if (empty($result['reserve']['memo'])) $result['reserve']['memo'] = '';
    } else {
      $result['reserve']['nickname'] = '';
      $result['reserve']['gender'] = 'M';
      $result['reserve']['loc'] = '';
      $result['reserve']['bref'] = '';
      $result['reserve']['depositname'] = '';
      $result['reserve']['memo'] = '';
      $result['reserve']['vip'] = '';
      $result['reserve']['manager'] = '';
      $result['reserve']['priority'] = '';
    }

    $result['busType'] = getBusType($viewData['view']['bustype'], $viewData['view']['bus']); // 버스 형태별 좌석 배치

    // 해당 버스의 좌석
    foreach ($result['busType'] as $busType) {
      foreach (range(1, $busType['seat']) as $seat) {
        $result['seat'][] = $seat;
      }
    }

    $result['location'] = arrLocation(); // 승차위치
    $result['breakfast'] = arrBreakfast(); // 아침식사

    $this->output->set_output(json_encode($result));
  }

  /**
   * 예약 처리
   *
   * @return json
   * @author bjchoi
   **/
  public function reserve_complete()
  {
    $idx = html_escape($this->input->post('idx'));
    $arrResIdx = $this->input->post('resIdx');
    $arrSeat = $this->input->post('seat');
    $arrNickname = $this->input->post('nickname');
    $arrGender = $this->input->post('gender');
    $arrBus = $this->input->post('bus');
    $arrLocation = $this->input->post('location');
    $arrMemo = $this->input->post('memo');
    $arrDepositName = $this->input->post('depositname');
    $arrVip = $this->input->post('vip');
    $arrManager = $this->input->post('manager');
    $arrPriority = $this->input->post('priority');

    foreach ($arrSeat as $key => $seat) {
      $postData = array(
        'rescode' => $idx,
        'nickname' => html_escape($arrNickname[$key]),
        'gender' => html_escape($arrGender[$key]),
        'bus' => html_escape($arrBus[$key]),
        'seat' => html_escape($seat),
        'loc' => html_escape($arrLocation[$key]),
        'memo' => html_escape($arrMemo[$key]),
        'depositname' => html_escape($arrDepositName[$key]),
        'vip' => html_escape($arrVip[$key]) == 'true' ? 1 : 0,
        'manager' => html_escape($arrManager[$key]) == 'true' ? 1 : 0,
        'priority' => html_escape($arrPriority[$key]) == 'true' ? 1 : 0,
        'regdate' => time(),
      );

      $resIdx = html_escape($arrResIdx[$key]);

      if (empty($resIdx)) {
        $result = $this->admin_model->insertReserve($postData);
      } else {
        $result = $this->admin_model->updateReserve($postData, $resIdx);
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 예약 취소
   *
   * @return json
   * @author bjchoi
   **/
  public function reserve_cancel()
  {
    $inputData['idx'] = html_escape($this->input->post('idx'));

    // 유저 예약 정보
    $viewReserve = $this->admin_model->viewReserve($inputData);

    // 산행 정보
    $viewEntry = $this->admin_model->viewEntry($viewReserve['rescode']);

    // 해당 산행과 버스의 예약자 수
    $cntReservation = $this->admin_model->cntReservation($viewReserve['rescode'], $viewReserve['bus']);

    $busType = getBusType($viewEntry['bustype'], $viewEntry['bus']);
    $maxSeat = array();
    foreach ($busType as $key => $value) {
      $cnt = $key + 1;
      $maxSeat[$cnt] = $value['seat'];
    }

    // 예약자가 초과됐을 경우
    if ($cntReservation['CNT'] >= $maxSeat[$viewReserve['bus']]) {
      // 예약 삭제 처리
      $updateValues = array(
        'userid' => NULL,
        'nickname' => '대기자 우선',
        'gender' => '',
        'loc' => NULL,
        'bref' => NULL,
        'memo' => NULL,
        'depositname' => NULL,
        'point' => 0,
        'priority' => 0,
        'vip' => 0,
        'manager' => 0,
        'penalty' => 0,
        'status' => RESERVE_WAIT
      );
      // 만석일 경우에는 대기자 우선석으로 변경
      $rtn = $this->admin_model->updateReserve($updateValues, $inputData['idx']);
    } else {
      // 좌석이 남아있을 경우에는 그냥 삭제
      $this->admin_model->deleteReserve($inputData['idx']);
    }

    $result['reload'] = true;
    $this->output->set_output(json_encode($result));
  }

  /**
   * 입금 확인
   *
   * @return json
   * @author bjchoi
   **/
  public function reserve_deposit()
  {
    // 입금 확인 완료
    $idx = html_escape($this->input->post('idx'));
    $updateData['status'] = RESERVE_PAY;

    $this->admin_model->updateReserve($updateData, $idx);

    $result['reload'] = true;
    $this->output->set_output(json_encode($result));
  }

  /**
   * 좌석 변경
   *
   * @return json
   * @author bjchoi
   **/
  public function reserve_change_seat()
  {
    $idx = html_escape($this->input->post('idx'));
    $reserve_seat = html_escape($this->input->post('reserve'));
    $array_seat = array();

    // 좌석 중복 확인
    foreach ($reserve_seat as $value) {
      $array_seat[] = $value['seat'];
    }

    if ((count(array_keys(array_flip($array_seat))) !== count($array_seat)) > 0) {
      $result = array('error' => 1, 'message' => '중복되는 좌석이 있습니다.');
    } else {
      foreach ($reserve_seat as $value) {
        if ($value['seat'] > 0 && $value['seat'] != $value['origin_seat']) {
          $updateData['seat'] = $value['seat'];
          $this->admin_model->updateReserve($updateData, $value['idx']);
        }
      }

      $result = array('error' => 0, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
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
    $search['status'] = array(STATUS_PLAN, STATUS_ABLE, STATUS_CONFIRM);
    $viewData['list'] = $this->admin_model->listNotice($search);

    $this->_viewPage('admin/main_list_progress', $viewData);
  }

  /**
   * 진행중 산행 예약 보기
   *
   * @return view
   * @author bjchoi
   **/
  public function main_view_progress($idx)
  {
    $viewData['rescode'] = html_escape($idx);
    $viewData['view'] = $this->admin_model->viewEntry($viewData['rescode']);

    // 버스 형태별 좌석 배치
    $viewData['busType'] = getBusType($viewData['view']['bustype'], $viewData['view']['bus']);

    // 예약 정보
    $viewData['reserve'] = $this->admin_model->viewReserve($viewData);

    // 대기자 정보
    $viewData['wait'] = $this->admin_model->listWait($viewData['rescode']);

    // 승차위치
    $viewData['arrLocation'] = arrLocation();

    $this->_viewPage('admin/main_view_progress', $viewData);
  }

  /**
   * 진행중 산행 : 승차관리
   *
   * @return view
   * @author bjchoi
   **/
  public function main_view_boarding($idx)
  {
    $viewData['rescode'] = html_escape($idx);
    $viewData['view'] = $this->admin_model->viewEntry($viewData['rescode']);

    // 버스 형태별 좌석 배치
    $viewData['busType'] = getBusType($viewData['view']['bustype'], $viewData['view']['bus']);

    // 예약 정보
    $viewData['reserve'] = $this->admin_model->viewReserve($viewData);

    // 시간별 승차위치
    $listLocation = arrLocation($viewData['view']['starttime']);

    foreach ($viewData['busType'] as $key1 => $bus) {
      $busNumber = $key1 + 1;
      foreach ($listLocation as $key2 => $value) {
        $viewData['busType'][$key1]['listLocation'][] = $value;
        $resData = $this->admin_model->listReserveLocation($viewData['rescode'], $busNumber, $value['no']);
        foreach ($resData as $people) {
          $viewData['busType'][$key1]['listLocation'][$key2]['nickname'][] = $people['nickname'];
        }
      }

      // 포인트 결제
      $viewData['busType'][$key1]['maxPoint'] = 0;
      $viewData['busType'][$key1]['listPoint'] = array();
      foreach ($viewData['reserve'] as $value) {
        if ($value['bus'] == $busNumber && $value['point'] > 0) {
          $viewData['busType'][$key1]['maxPoint']++;
          $viewData['busType'][$key1]['listPoint'][] = array(
            'seat' => $value['seat'],
            'nickname' => $value['nickname'],
            'point' => $value['point']
          );
        }
      }
      sort($viewData['busType'][$key1]['listPoint']);

      // 요청사항
      $viewData['busType'][$key1]['maxMemo'] = 0;
      $viewData['busType'][$key1]['listMemo'] = array();
      foreach ($viewData['reserve'] as $value) {
        if ($value['bus'] == $busNumber && !empty($value['memo'])) {
          $viewData['busType'][$key1]['maxMemo']++;
          $viewData['busType'][$key1]['listMemo'][] = array(
            'seat' => $value['seat'],
            'nickname' => $value['nickname'],
            'memo' => $value['memo']
          );
        }
      }
      sort($viewData['busType'][$key1]['listMemo']);
    }

    $this->_viewPage('admin/main_view_boarding', $viewData);
  }

  /**
   * 진행중 산행 : 정산관리
   *
   * @return view
   * @author bjchoi
   **/
  public function main_view_adjust($idx)
  {
    $idx = html_escape($idx);
    $viewData['view'] = $this->admin_model->viewEntry($idx);

    $this->_viewPage('admin/main_view_adjust', $viewData);
  }

  /**
   * 진행중 산행 : 문자양식
   *
   * @return view
   * @author bjchoi
   **/
  public function main_view_sms($idx)
  {
    $idx = html_escape($idx);
    $viewData['view'] = $this->admin_model->viewEntry($idx);

    // 총 버스 갯수
    if (!empty($viewData['view']['bustype'])) {
      $viewData['view']['busTotal'] = count(unserialize($viewData['view']['bustype']));
    } else {
      $viewData['view']['busTotal'] = 0;
    }

    // 문자양식 만들기
    $list = $this->admin_model->listSMS($idx);

    foreach ($list as $key => $value) {
      $location = arrLocation($value['startdate']);
      $viewData['list'][$key]['date'] = date('m/d', strtotime($value['startdate']));
      $viewData['list'][$key]['week'] = calcWeek($value['startdate']);
      $viewData['list'][$key]['dist'] = calcSchedule($value['schedule']);
      $viewData['list'][$key]['subject'] = $value['subject'];
      $viewData['list'][$key]['nickname'] = $value['nickname'];
      $viewData['list'][$key]['bus'] = $value['nowbus'];
      $viewData['list'][$key]['seat'] = $value['seat'];
      $viewData['list'][$key]['time'] = $location[$value['loc']]['time'];
      $viewData['list'][$key]['title'] = $location[$value['loc']]['title'];
    }

    $this->_viewPage('admin/main_view_sms', $viewData);
  }

  /**
   * 진행중 산행 : 좌석변경
   *
   * @return json
   * @author bjchoi
   **/
  /*
  public function main_view_seat($idx)
  {
    $idx = html_escape($idx);
    $viewData['view'] = $this->admin_model->viewEntry($idx);

    // 버스 형태별 좌석 배치
    $viewData['busType'] = getBusType($viewData['view']['bustype'], $viewData['view']['bus']);

    // 예약 정보
    $viewData['reserve'] = $this->admin_model->viewProgress($idx);

    $this->_viewPage('admin/main_view_seat', $viewData);
  }
  */

  /**
   * 다녀온 산행 목록
   *
   * @return view
   * @author bjchoi
   **/
  public function main_list_closed()
  {
    $viewData['search']['subject'] = $this->input->get('subject') ? html_escape($this->input->get('subject')) : '';
    $viewData['search']['sdate'] = $this->input->get('sdate') ? html_escape($this->input->get('sdate')) : date('Y-m-01');
    $viewData['search']['edate'] = $this->input->get('edate') ? html_escape($this->input->get('edate')) : date('Y-m-t');
    $viewData['search']['syear'] = !empty($viewData['search']['sdate']) ? date('Y', strtotime($viewData['search']['sdate'])) : date('Y');
    $viewData['search']['smonth'] = !empty($viewData['search']['sdate']) ? date('m', strtotime($viewData['search']['sdate'])) : date('m');
    $viewData['search']['prev'] = 'sdate=' . date('Y-m-01', strtotime('-1 months', strtotime($viewData['search']['sdate']))) . '&edate=' . date('Y-m-t', strtotime('-1 months', strtotime($viewData['search']['sdate'])));
    $viewData['search']['next'] = 'sdate=' . date('Y-m-01', strtotime('+1 months', strtotime($viewData['search']['sdate']))) . '&edate=' . date('Y-m-t', strtotime('+1 months', strtotime($viewData['search']['sdate'])));
    $viewData['search']['status'] = array(STATUS_CLOSED);

    $viewData['listClosed'] = $this->admin_model->listNotice($viewData['search']);

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
    $viewData['search']['subject'] = $this->input->get('subject') ? html_escape($this->input->get('subject')) : '';
    $viewData['search']['sdate'] = $this->input->get('sdate') ? html_escape($this->input->get('sdate')) : date('Y-m-01');
    $viewData['search']['edate'] = $this->input->get('edate') ? html_escape($this->input->get('edate')) : date('Y-m-t');
    $viewData['search']['syear'] = !empty($viewData['search']['sdate']) ? date('Y', strtotime($viewData['search']['sdate'])) : date('Y');
    $viewData['search']['smonth'] = !empty($viewData['search']['sdate']) ? date('m', strtotime($viewData['search']['sdate'])) : date('m');
    $viewData['search']['prev'] = 'sdate=' . date('Y-m-01', strtotime('-1 months', strtotime($viewData['search']['sdate']))) . '&edate=' . date('Y-m-t', strtotime('-1 months', strtotime($viewData['search']['sdate'])));
    $viewData['search']['next'] = 'sdate=' . date('Y-m-01', strtotime('+1 months', strtotime($viewData['search']['sdate']))) . '&edate=' . date('Y-m-t', strtotime('+1 months', strtotime($viewData['search']['sdate'])));
    $viewData['search']['status'] = array(STATUS_CANCEL);

    $viewData['listCancel'] = $this->admin_model->listNotice($viewData['search']);

    $this->_viewPage('admin/main_list_canceled', $viewData);
  }

  /**
   * 산행 상태 변경
   *
   * @return json
   * @author bjchoi
   **/
  public function change_status()
  {
    $idx = html_escape($this->input->post('idx'));
    $updateData['status'] = html_escape($this->input->post('status'));

    $rtn = $this->admin_model->updateEntry($updateData, $idx);

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
    } else {
      $result = array('error' => 0, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 산행 숨김/공개
   *
   * @return json
   * @author bjchoi
   **/
  public function change_visible()
  {
    $idx = html_escape($this->input->post('idx'));
    $updateData['visible'] = html_escape($this->input->post('visible'));

    $rtn = $this->admin_model->updateEntry($updateData, $idx);

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
    } else {
      $result = array('error' => 0, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
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
      $viewData['view']['bustype'] = @unserialize($viewData['view']['bustype']);
      $viewData['view']['road_course'] = @unserialize($viewData['view']['road_course']);
      $viewData['view']['road_distance'] = @unserialize($viewData['view']['road_distance']);
      $viewData['view']['road_runtime'] = @unserialize($viewData['view']['road_runtime']);
      $viewData['view']['road_cost'] = @unserialize($viewData['view']['road_cost']);
      $viewData['view']['driving_fuel'] = @unserialize($viewData['view']['driving_fuel']);
      $viewData['view']['driving_cost'] = @unserialize($viewData['view']['driving_cost']);
      $viewData['view']['driving_add'] = @unserialize($viewData['view']['driving_add']);
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

  /**
   * 공지사항 폼
   *
   * @return view
   * @author bjchoi
   **/
  public function main_notice($idx=NULL)
  {
    if (is_null($idx)) exit;

    $viewData['view'] = $this->admin_model->viewEntry(html_escape($idx));

    $this->_viewPage('admin/main_notice', $viewData);
  }

  /**
   * 공지사항 수정 처리
   *
   * @return view
   * @author bjchoi
   **/
  public function main_notice_update()
  {
    $now = time();
    $postData = array();
    $idx = html_escape($this->input->post('idx'));

    if (!empty($idx)) {
/*
      if (!empty($_FILES['photo']['tmp_name']) && $_FILES['photo']['type'] == 'image/jpeg') {
        $postData['photo'] = $now . mt_rand(10000, 99999) . ".jpg";
        move_uploaded_file($_FILES['photo']['tmp_name'], PHOTO_PATH . $postData['photo']);
      }

      if (!empty($_FILES['map']['tmp_name']) && $_FILES['map']['type'] == 'image/jpeg') {
        $postData['map'] = $now . mt_rand(10000, 99999) . ".jpg";
        move_uploaded_file($_FILES['map']['tmp_name'], PHOTO_PATH . $postData['map']);
      }
*/
      $postData = array(
        'plan'        => html_escape($this->input->post('plan')),         // 기획의도
        'point'       => html_escape($this->input->post('point')),        // 핵심안내
        'timetable'   => html_escape($this->input->post('timetable')),    // 타임테이블
        'information' => html_escape($this->input->post('information')),  // 산행안내
        'course'      => html_escape($this->input->post('course')),       // 산행코스안내
        'intro'       => html_escape($this->input->post('intro')),        // 산행지 소개
      );

      $rtn = $this->admin_model->updateEntry($postData, $idx);
    }
/*
    if (!$rtn) {
      $result = array('error' => 1, 'message' => '에러가 발생했습니다.');
    } else {
      $result = array('error' => 0, 'message' => '');
    }
*/
    redirect('/admin/main_notice/' . $idx);
  }

  /**
   * 산행 삭제
   *
   * @return view
   * @author bjchoi
   **/
  public function main_notice_delete()
  {
    $idx = html_escape($this->input->post('idx'));
    $rtn = $this->admin_model->deleteEntry($idx);

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
    } else {
      $result = array('error' => 0, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 대기자 등록
   *
   * @return view
   * @author bjchoi
   **/
  public function main_wait_insert()
  {
    $now = time();
    $clubIdx = 1;
    $postData = $this->input->post();
    $userIdx = html_escape($postData['created_by']);

    if (empty($userIdx)) {
      $userIdx = $this->session->userData['idx'];
    }

    $processData  = array(
      'club_idx'    => $clubIdx,
      'notice_idx'  => html_escape($postData['idx']),
      'nickname'    => html_escape($postData['nickname']),
      'location'    => html_escape($postData['location']),
      'gender'      => html_escape($postData['gender']),
      'memo'        => html_escape($postData['memo']),
      'created_by'  => $userIdx,
      'created_at'  => $now,
    );
    $rtn = $this->admin_model->insertWait($processData);

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
    } else {
      $result = array('error' => 0, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 대기자 삭제
   *
   * @return view
   * @author bjchoi
   **/
  public function main_wait_delete()
  {
    $idx = html_escape($this->input->post('idx'));
    $rtn = $this->admin_model->deleteWait($idx);

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_delete'));
    } else {
      $result = array('error' => 0, 'message' => '');
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
   * @return json
   * @author bjchoi
   **/
  public function member_list()
  {
    $viewData['search']['realname'] = html_escape($this->input->post('realname'));
    $viewData['search']['nickname'] = html_escape($this->input->post('nickname'));
    $viewData['search']['levelType'] = html_escape($this->input->post('levelType'));

    if (!empty($viewData['search']['levelType'])) {
      switch($viewData['search']['levelType']) {
        case '1': // 한그루 회원
          $viewData['search']['resMin'] = 0;
          $viewData['search']['resMax'] = 9;
          break;
        case '2': // 두그루 회원
          $viewData['search']['resMin'] = 10;
          $viewData['search']['resMax'] = 29;
          break;
        case '3': // 세그루 회원
          $viewData['search']['resMin'] = 30;
          $viewData['search']['resMax'] = 49;
          break;
        case '4': // 네그루 회원
          $viewData['search']['resMin'] = 50;
          $viewData['search']['resMax'] = 99;
          break;
        case '5': // 다섯그루 회원
          $viewData['search']['resMin'] = 100;
          break;
        case '6': // 평생회원
          $viewData['search']['level'] = 1;
          break;
        case '7': // 무료회원
          $viewData['search']['level'] = 2;
          break;
        case '8': // 관리자
          $viewData['search']['admin'] = 1;
          break;
      }
    } else {
      $viewData['search']['level'] = 0;
    }

    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;
    $paging['perPage'] = 20;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];
    $viewData['listMembers'] = $this->admin_model->listMembers($paging, $viewData['search']);

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('admin/member_list_append', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      // 1페이지에는 View 페이지로 전송
      $this->_viewPage('admin/member_list', $viewData);
    }
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
    $idx = html_escape($this->input->post('idx'));
    $result = array('error' => 1, 'message' => $this->lang->line('error_all'));

    if (!empty($idx)) {
      $updateValues['quitdate'] = time();
      $rtn = $this->admin_model->updateMember($updateValues, $idx);

      if (!empty($rtn)) {
        $result = array('error' => 0, 'message' => '');
      }
    }

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
   * 설정 - 문자양식보기
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_sms()
  {
    $search['status'] = array(STATUS_PLAN, STATUS_ABLE, STATUS_CONFIRM);
    $viewData['list'] = $this->admin_model->listNotice($search);

    $this->_viewPage('admin/setup_sms', $viewData);
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
   * 설정 - 달력관리
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_calendar()
  {
    $viewData['search']['syear'] = NULL;
    $viewData['search']['smonth'] = NULL;
    $viewData['search']['status'] = array(STATUS_PLAN);

    $viewData['listSchedule'] = $this->admin_model->listNotice($viewData['search']);
    $sdate = $this->input->get('d');
    if (!empty($sdate)) $viewData['sdate'] = html_escape($sdate); else $viewData['sdate'] = NULL;

    $this->_viewPage('admin/setup_calendar', $viewData);
  }

  /**
   * 설정 - 산행예정 만들기
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_schedule()
  {
    $viewData['search']['syear'] = NULL;
    $viewData['search']['smonth'] = NULL;
    $viewData['search']['status'] = array(STATUS_PLAN);

    $viewData['listSchedule'] = $this->admin_model->listNotice($viewData['search']);
    $sdate = $this->input->get('d');
    if (!empty($sdate)) $viewData['sdate'] = html_escape($sdate); else $viewData['sdate'] = NULL;

    $this->_viewPage('admin/setup_schedule', $viewData);
  }

  /**
   * 설정 - 지난 산행 보기
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_schedule_past()
  {
    $html = '';
    $idx = html_escape($this->input->post('idx'));
    $sdate = html_escape($this->input->post('sdate'));
    $edate = html_escape($this->input->post('edate'));
    $search['status'] = array(STATUS_CLOSED);

    $result = array(
      'error' => 1,
      'message' => '해당하는 일자의 과거 산행 정보가 없습니다.'
    );

    if (!empty($idx)) {
      $viewNotice = $this->admin_model->viewEntry($idx);

      if (!empty($viewNotice['idx'])) {
        $sdate = $viewNotice['startdate'];
        $edate = $viewNotice['enddate'];

        $search['syear'] = date('md', strtotime($sdate) - (86400 * 5));
        $search['smonth'] = date('md', strtotime($edate) + (86400 * 5));

        $listPastNotice = $this->admin_model->listNotice($search);

        if (!empty($listPastNotice)) {
          foreach ($listPastNotice as $value) {
            $html .= '<a href="javascript:;" data-subject="' . $value['subject'] . '">' . $value['startdate'] . ' (' . calcWeek($value['startdate']) . ') ' . $value['subject'] . '</a><br>';
          }

          $result = array(
            'error' => 0,
            'idx' => $idx,
            'sdate' => $sdate,
            'edate' => $edate,
            'subject' => $viewNotice['subject'],
            'message' => $html
          );
        }
      }
    } elseif (!empty($sdate) && !empty($edate)) {
      $search['syear'] = date('md', strtotime($sdate) - (86400 * 5));
      $search['smonth'] = date('md', strtotime($edate) + (86400 * 5));

      $listPastNotice = $this->admin_model->listNotice($search);

      if (!empty($listPastNotice)) {
        foreach ($listPastNotice as $value) {
          $html .= '<a href="javascript:;" data-subject="' . $value['subject'] . '">' . $value['startdate'] . ' (' . calcWeek($value['startdate']) . ') ' . $value['subject'] . '</a><br>';
        }

        $result = array(
          'error' => 0,
          'message' => $html
        );
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 설정 - 산행예정 갱신
   *
   * @return json
   * @author bjchoi
   **/
  public function setup_schedule_update()
  {
    $now = time();
    $postData['startdate'] = html_escape($this->input->post('sdate'));
    $postData['starttime'] = '06:00';
    $postData['enddate'] = html_escape($this->input->post('edate'));
    $postData['subject'] = $postData['mname'] = html_escape($this->input->post('subject'));
    $postData['visible'] = VISIBLE_NONE;
    $idx = html_escape($this->input->post('idx'));

    if (!empty($idx)) {
      $rtn = $this->admin_model->updateEntry($postData, $idx);
    } else {
      $postData['regdate'] = $now;
      $rtn = $this->admin_model->insertEntry($postData);
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_update'));
    } else {
      $result = array('error' => 0, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 설정 - 산행예정 삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function setup_schedule_delete()
  {
    $idx = html_escape($this->input->post('idx'));
    $rtn = $this->admin_model->deleteEntry($idx);

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_delete'));
    } else {
      $result = array('error' => 0, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 닉네임으로 아이디 찾기
   *
   * @return json
   * @author bjchoi
   **/
  public function search_by_nickname()
  {
    $nickname = html_escape($this->input->post('nickname'));
    $rtn = $this->admin_model->viewMember(NULL, $nickname);

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => '');
    } else {
      $result = array('error' => 0, 'message' => '', 'idx' => $rtn['idx'], 'userid' => $rtn['userid']);
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
    $headerData['uri'] = $_SERVER['REQUEST_URI'];
    $this->load->view('admin/header', $headerData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('admin/footer');
  }
}
?>
