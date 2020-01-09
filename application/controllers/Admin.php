<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 관리 페이지 클래스
class Admin extends Admin_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('cookie', 'security', 'url', 'my_array_helper'));
    $this->load->library(array('image_lib'));
    $this->load->model(array('admin_model', 'area_model', 'club_model', 'file_model', 'member_model', 'shop_model'));
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
      if (empty($result['reserve']['userid'])) $result['reserve']['userid'] = '';
      if (empty($result['reserve']['depositname'])) $result['reserve']['depositname'] = '';
      if (empty($result['reserve']['memo'])) $result['reserve']['memo'] = '';
    } else {
      $result['reserve']['userid'] = '';
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
    foreach ($result['busType'] as $key => $busType) {
      foreach (range(1, $busType['seat']) as $seat) {
        $bus = $key + 1;
        $seat = checkDirection($seat, ($bus), $viewData['view']['bustype'], $viewData['view']['bus']);
        $result['seat'][$bus][] = $seat;
      }
    }

    $result['location'] = arrLocation(); // 승차위치
    $result['breakfast'] = arrBreakfast(); // 아침식사

    $this->output->set_output(json_encode($result));
  }

  /**
   * 예약 정보 - 버스
   *
   * @return json
   * @author bjchoi
   **/
  public function reserve_information_bus()
  {
    $idx = html_escape($this->input->post('idx'));
    $viewData['view'] = $this->admin_model->viewEntry($idx);

    $result['busType'] = getBusType($viewData['view']['bustype'], $viewData['view']['bus']); // 버스 형태별 좌석 배치

    // 해당 버스의 좌석
    foreach ($result['busType'] as $key => $busType) {
      foreach (range(1, $busType['seat']) as $seat) {
        $bus = $key + 1;
        $seat = checkDirection($seat, ($bus), $viewData['view']['bustype'], $viewData['view']['bus']);
        $result['seat'][$bus][] = $seat;
      }
    }

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
    $now = time();
    $idx = html_escape($this->input->post('idx'));
    $arrResIdx = $this->input->post('resIdx');
    $arrSeat = $this->input->post('seat');
    $arrUserid = $this->input->post('userid');
    $arrNickname = $this->input->post('nickname');
    $arrGender = $this->input->post('gender');
    $arrBus = $this->input->post('bus');
    $arrLocation = $this->input->post('location');
    $arrMemo = $this->input->post('memo');
    $arrDepositName = $this->input->post('depositname');
    $arrVip = $this->input->post('vip');
    $arrManager = $this->input->post('manager');
    $arrPriority = $this->input->post('priority');

    // 산행 정보
    $viewEntry = $this->admin_model->viewEntry($idx);

    foreach ($arrSeat as $key => $seat) {
      $nowUserid = html_escape($arrUserid[$key]);
      $nowNick = html_escape($arrNickname[$key]);
      $nowBus = html_escape($arrBus[$key]);
      $nowSeat = html_escape($seat);
      $nowManager = html_escape($arrManager[$key]) == 'true' ? 1 : 0;
      $nowPriority = html_escape($arrPriority[$key]) == 'true' ? 1 : 0;

      $postData = array(
        'rescode' => $idx,
        'userid' => $nowUserid,
        'nickname' => $nowNick,
        'gender' => html_escape($arrGender[$key]),
        'bus' => $nowBus,
        'seat' => $nowSeat,
        'loc' => html_escape($arrLocation[$key]),
        'memo' => html_escape($arrMemo[$key]),
        'depositname' => html_escape($arrDepositName[$key]),
        'vip' => html_escape($arrVip[$key]) == 'true' ? 1 : 0,
        'manager' => $nowManager,
        'regdate' => $now
      );

      if ($nowManager == 1) { // 운영진우선석
        $postData['status'] = RESERVE_PAY;
      }

      // 예약 번호 (수정시)
      $resIdx = html_escape($arrResIdx[$key]);

      // 선택한 좌석 예약 여부 확인
      $checkReserve = $this->admin_model->checkReserve($idx, $nowBus, $nowSeat);

      if (empty($resIdx)) {
        if (empty($checkReserve['idx'])) {
          $result = $this->admin_model->insertReserve($postData);

          if (!empty($nowPriority)) {
            $priorityIdx[] = $result; // 2인우선석일 경우, 각각의 고유번호를 저장
          }

          if (!empty($result) && empty($nowPriority) && empty($nowManager)) {
            // 관리자 예약 기록
            setHistory(LOG_ADMIN_RESERVE, $idx, $nowUserid, $nowNick, $viewEntry['subject'], $now);
          }
        }
      } else {
        // 선택한 좌석과 기존 예약좌석이 다를경우
        if (!empty($checkReserve['idx']) && $checkReserve['idx'] != $resIdx) {
          // 기존 예약정보 불러오기
          $search['idx'] = $resIdx;
          $viewOldReserve = $this->admin_model->viewReserve($search);

          // 이동하려는 좌석이 대기자우선석인 경우, 기존 좌석을 대기자우선석으로 변경
          if ($checkReserve['status'] == RESERVE_WAIT) {
            $updateWait = array(
              'userid' => NULL,
              'nickname' => '대기자우선',
              'bus' => $viewOldReserve['bus'],
              'seat' => $viewOldReserve['seat'],
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
            $this->admin_model->updateReserve($updateWait, $checkReserve['idx']);

            // 대기자 입력이기 때문에 미입금으로 변경
            $postData['status'] = RESERVE_ON;
          } elseif (!empty($checkReserve['priority']) && $checkReserve['nickname'] == '2인우선') {
            // 2인우선석의 경우
            $postData['status'] = RESERVE_ON;
            $postData['priority'] = 0;
          } else {
            // 일반 좌석일 경우 좌석을 교환
            $changeData = array(
              'bus' => $viewOldReserve['bus'],
              'seat' => $viewOldReserve['seat']
            );
            $this->admin_model->updateReserve($changeData, $checkReserve['idx']);
          }
        } else {
          if ($checkReserve['status'] == RESERVE_WAIT) {
            // 대기자 입력이기 때문에 미입금으로 변경
            $postData['status'] = RESERVE_ON;
          }
        }
        // 예약정보 수정
        $result = $this->admin_model->updateReserve($postData, $resIdx);
      }
    }

    if (empty($result)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_seat_duplicate'));
    } else {
      // 2인우선석 처리 (각각의 예약 고유번호를 입력)
      if (!empty($priorityIdx)) {
        $updateValues['priority'] = $priorityIdx[0];
        $this->admin_model->updateReserve($updateValues, $priorityIdx[1]);
        $updateValues['priority'] = $priorityIdx[1];
        $this->admin_model->updateReserve($updateValues, $priorityIdx[0]);
      }
      if ($viewEntry['status'] == STATUS_ABLE) {
        $cntReservation = $this->admin_model->cntReservation($idx);
        if ($cntReservation['CNT'] >= 15) {
          // 예약자가 15명 이상일 경우 확정으로 변경
          $updateValues = array('status' => STATUS_CONFIRM);
          $this->admin_model->updateEntry($updateValues, $idx);
        }
      }
      $result = array('error' => 0, 'message' => '');
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
    $now = time();
    $clubIdx = 1; // 경인웰빙
    $inputData['idx'] = html_escape($this->input->post('idx'));
    $subject = !empty($this->input->post('subject')) ? '<br>' . html_escape($this->input->post('subject')) : '';

    // 예약 정보
    $viewReserve = $this->admin_model->viewReserve($inputData);

    // 회원 정보
    $search['userid'] = $viewReserve['userid'];
    $userData = $this->admin_model->viewMember($search);

    // 산행 정보
    $viewEntry = $this->admin_model->viewEntry($viewReserve['rescode']);

    // 해당 산행과 버스의 예약자 수
    $cntReservation = $this->admin_model->cntReservation($viewReserve['rescode'], $viewReserve['bus'], 1);

    // 대기자 수
    $cntWait = $this->admin_model->cntWait($clubIdx, $viewReserve['rescode']);

    $busType = getBusType($viewEntry['bustype'], $viewEntry['bus']);
    $maxSeat = array();
    foreach ($busType as $key => $value) {
      $cnt = $key + 1;
      $maxSeat[$cnt] = $value['seat'];
    }

    // 예약자가 초과됐을 경우, 대기자수가 1명 이상일 경우
    if ($cntReservation['CNT'] >= $maxSeat[$viewReserve['bus']] && $cntWait['cnt'] >= 1) {
      // 예약 삭제 처리
      $updateValues = array(
        'userid' => NULL,
        'nickname' => '대기자우선',
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
      $rtn = $this->admin_model->deleteReserve($inputData['idx']);

      if ($viewEntry['status'] == STATUS_CONFIRM) {
        $cntReservation = $this->admin_model->cntReservation($viewReserve['rescode']);
        if ($cntReservation['CNT'] < 15) {
          // 예약자가 15명 이하일 경우 예정으로 변경
          $updateValues = array('status' => STATUS_ABLE);
          $this->admin_model->updateEntry($updateValues, $viewReserve['rescode']);
        }
      }
    }

    if (!empty($rtn) && empty($viewReserve['priority']) && empty($viewReserve['manager'])) {
      $startTime = explode(':', $viewEntry['starttime']);
      $startDate = explode('-', $viewEntry['startdate']);
      $limitDate = mktime($startTime[0], $startTime[1], 0, $startDate[1], $startDate[2], $startDate[0]);

      // 예약 페널티
      $penalty = 0;
      if ( $limitDate < ($now + 86400) ) {
        // 1일전 취소시 3점 페널티
        $penalty = 3;
      } elseif ( $limitDate < ($now + 172800) ) {
        // 2일전 취소시 1점 페널티
        $penalty = 1;
      }
      $this->member_model->updatePenalty($clubIdx, $viewReserve['userid'], ($userData['penalty'] + $penalty));

      // 예약 페널티 로그 기록
      if ($penalty > 0) {
        setHistory(LOG_PENALTYUP, $viewReserve['rescode'], $viewReserve['userid'], $viewReserve['nickname'], $viewEntry['subject'] . ' 관리자 예약 취소', $now, $penalty);
      }

      if ($viewReserve['status'] == RESERVE_PAY) {
        // 분담금 합계 (기존 버젼 호환용)
        $viewEntry['cost'] = $viewEntry['cost_total'] == 0 ? $viewEntry['cost'] : $viewEntry['cost_total'];

        // 비회원 입금취소의 경우, 환불내역 기록
        if (empty($viewReserve['userid'])) {
          setHistory(LOG_ADMIN_REFUND, $viewReserve['rescode'], '', $viewReserve['nickname'], $viewEntry['subject'], $now);
        }

        // 이미 입금을 마친 상태라면, 전액 포인트로 환불 (무료회원은 환불 안함)
        if (empty($userData['level']) || $userData['level'] != 2) {
          if ($userData['level'] == 1) {
            // 평생회원은 할인 적용된 가격을 환불
            $viewEntry['cost'] = $viewEntry['cost'] - 5000;
            $this->member_model->updatePoint($clubIdx, $viewReserve['userid'], ($userData['point'] + $viewEntry['cost']));
          } else {
            $this->member_model->updatePoint($clubIdx, $viewReserve['userid'], ($userData['point'] + $viewEntry['cost']));
          }
          // 포인트 반환 로그 기록
          setHistory(LOG_POINTUP, $viewReserve['rescode'], $viewReserve['userid'], $viewReserve['nickname'], $viewEntry['subject'] . ' 관리자 예약 취소', $now, $viewEntry['cost']);
        }
      } elseif ($viewReserve['status'] == RESERVE_ON && $viewReserve['point'] > 0) {
        // 예약정보에 포인트가 있을때 반환
        $this->member_model->updatePoint($clubIdx, $viewReserve['userid'], ($userData['point'] + $viewReserve['point']));

        // 포인트 반환 로그 기록
        setHistory(LOG_POINTUP, $viewReserve['rescode'], $viewReserve['userid'], $viewReserve['nickname'], $viewEntry['subject'] . ' 관리자 예약 취소', $now, $viewReserve['point']);
      }

      // 관리자 예약취소 기록
      setHistory(LOG_ADMIN_CANCEL, $viewReserve['rescode'], $viewReserve['userid'], $viewReserve['nickname'], $viewEntry['subject'] . $subject, $now);

      // 예약 취소 로그 기록
      setHistory(LOG_CANCEL, $viewReserve['rescode'], $viewReserve['userid'], $viewReserve['nickname'], $viewEntry['subject'] . $subject, $now);
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
    $now = time();
    $viewData['idx'] = html_escape($this->input->post('idx'));
    $viewReserve = $this->admin_model->viewReserve($viewData);
    $viewEntry = $this->admin_model->viewEntry($viewReserve['rescode']);

    if ($viewReserve['status'] == 1) {
      // 입금취소
      $updateValues['status'] = RESERVE_ON;
      $this->admin_model->updateReserve($updateValues, $viewData['idx']);

      // 관리자 입금취소 기록
      setHistory(LOG_ADMIN_DEPOSIT_CANCEL, $viewEntry['idx'], $viewReserve['userid'], $viewReserve['nickname'], $viewEntry['subject'], $now);

      // 비회원 입금취소의 경우, 환불내역 기록
      if (empty($viewReserve['userid'])) {
        setHistory(LOG_ADMIN_REFUND, $viewEntry['idx'], '', $viewReserve['nickname'], $viewEntry['subject'], $now);
      }
    } else {
      // 입금확인 날짜 체크
      if (strtotime($viewEntry['startdate']) <= strtotime(date('Y-m-d', $now))) {
        // 출발일보다 입금확인이 같거나 늦으면 예약 페널티 기록 (추후 포인트 지급 안됨)
        $updateValues['penalty'] = 1;
      }

      // 입금확인
      $updateValues['status'] = RESERVE_PAY;
      $this->admin_model->updateReserve($updateValues, $viewData['idx']);

      // 관리자 입금확인 기록
      setHistory(LOG_ADMIN_DEPOSIT_CONFIRM, $viewEntry['idx'], $viewReserve['userid'], $viewReserve['nickname'], $viewEntry['subject'], $now);
    }

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
    $search['status'] = array(STATUS_ABLE, STATUS_CONFIRM);
    $viewData['list'] = $this->admin_model->listNotice($search);

    foreach ($viewData['list'] as $key => $value) {
      // 지역
      if (!empty($value['area_sido'])) {
        $areaSido = unserialize($value['area_sido']);
        $areaGugun = unserialize($value['area_gugun']);

        foreach ($areaSido as $cnt => $sido) {
          $getSido = $this->area_model->getName($sido);
          $getGugun = $this->area_model->getName($areaGugun[$cnt]);
          $viewData['list'][$key]['sido'][$cnt] = $getSido['name'];
          $viewData['list'][$key]['gugun'][$cnt] = $getGugun['name'];
        }
      }
    }

    $this->_viewPage('admin/main_list_progress', $viewData);
  }

  /**
   * 계획중 산행 목록
   *
   * @return view
   * @author bjchoi
   **/
  public function main_list_planned()
  {
    $search['status'] = array(STATUS_PLAN);
    $viewData['list'] = $this->admin_model->listNotice($search);

    $this->_viewPage('admin/main_list_planned', $viewData);
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

    // 진행중 산행 목록
    $search['status'] = array(STATUS_ABLE, STATUS_CONFIRM);
    $viewData['list'] = $this->admin_model->listNotice($search);

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
    $busType = getBusType($viewData['view']['bustype'], $viewData['view']['bus']);

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
      if (!empty($value['loc'])) {
        $viewData['list'][$key]['time'] = $location[$value['loc']]['time'];
        $viewData['list'][$key]['title'] = $location[$value['loc']]['title'];
      } else {
        $viewData['list'][$key]['time'] = '';
        $viewData['list'][$key]['title'] = '';
      }

      foreach ($busType as $key => $bus) {
        $busNo = $key + 1;
        if ($busNo == $value['nowbus']) {
          $viewData['list'][$key]['bus_name'] = $bus['bus_name'];
          if (!empty($bus['bus_color'])) $viewData['list'][$key]['bus_name'] .= '(' . $bus['bus_color'] . ')';
        }
      }
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
    $now = time();
    $clubIdx = 1;
    $search['rescode'] = html_escape($this->input->post('idx'));
    $updateValues['status'] = html_escape($this->input->post('status'));

    if ($updateValues['status'] == STATUS_CLOSED) { // 종료 처리
      $viewEntry = $this->admin_model->viewEntry($search['rescode']);
      $viewReserve = $this->admin_model->viewReserveClosed($search['rescode']);

      foreach ($viewReserve as $value) {
        $search['userid'] = $value['userid'];
        $userData = $this->admin_model->viewMember($search);

        if ($userData['level'] != 2 && $userData['admin'] != 1) { // 무료회원과 관리자는 적립금 없음
          // 최초 1회는 자신의 레벨에 맞게 포인트 지급
          $memberLevel = memberLevel($userData['rescount'], $userData['penalty'], $userData['level'], $userData['admin']);
          $this->member_model->updatePoint($clubIdx, $userData['userid'], ($userData['point'] + $memberLevel['point']));
          setHistory(LOG_POINTUP, $search['rescode'], $userData['userid'], $userData['nickname'], $viewEntry['subject'] . ' 본인 예약 포인트', $now, $memberLevel['point']);

          // 같은 아이디로 추가 예약을 했을 경우 포인트 1000씩 지급
          $addedReserve = $this->admin_model->viewReserveClosedAdded($search['rescode'], $userData['userid']);
          if ($addedReserve['cnt'] > 1) {
            $addedPoint = ($addedReserve['cnt'] - 1) * 1000;
            $this->member_model->updatePoint($clubIdx, $userData['userid'], ($userData['point'] + $addedPoint));
            setHistory(LOG_POINTUP, $search['rescode'], $userData['userid'], $userData['nickname'], $viewEntry['subject'] . ' 일행 예약 포인트', $now, $addedPoint);
          }
        }

      }
    } elseif ($updateValues['status'] == STATUS_CANCEL) { // 취소 처리
      $viewEntry = $this->admin_model->viewEntry($search['rescode']);
      $viewReserve = $this->admin_model->viewReserve($search);

      foreach ($viewReserve as $value) {
        $search['userid'] = $value['userid'];
        $userData = $this->admin_model->viewMember($search);

        if ($value['status'] == RESERVE_PAY) {
          // 분담금 합계 (기존 버젼 호환용)
          $viewEntry['cost'] = $viewEntry['cost_total'] == 0 ? $viewEntry['cost'] : $viewEntry['cost_total'];

          // 이미 입금을 마친 상태라면, 전액 포인트로 환불 (무료회원은 환불 안함)
          if (empty($userData['level']) || $userData['level'] != 2) {
            if ($userData['level'] == 1) {
              // 평생회원은 할인 적용된 가격을 환불
              $viewEntry['cost'] = $viewEntry['cost'] - 5000;
              $this->member_model->updatePoint($clubIdx, $value['userid'], ($userData['point'] + $viewEntry['cost']));
            } else {
              $this->member_model->updatePoint($clubIdx, $value['userid'], ($userData['point'] + $viewEntry['cost']));
            }
            // 포인트 반환 로그 기록
            setHistory(LOG_POINTUP, $value['rescode'], $value['userid'], $value['nickname'], $viewEntry['subject'] . ' 산행 취소', $now, $viewEntry['cost']);
          }
        } elseif ($value['status'] == RESERVE_ON && $value['point'] > 0) {
          // 예약정보에 포인트가 있을때 반환
          $this->member_model->updatePoint($clubIdx, $value['userid'], ($userData['point'] + $value['point']));

          // 포인트 반환 로그 기록
          setHistory(LOG_POINTUP, $value['rescode'], $value['userid'], $value['nickname'], $viewEntry['subject'] . ' 산행 취소', $now, $value['point']);
        }

        // 관리자 예약취소 기록
        setHistory(LOG_ADMIN_CANCEL, $value['rescode'], $value['userid'], $value['nickname'], $viewEntry['subject'] . ' 산행 취소', $now);

        // 예약 취소 로그 기록
        setHistory(LOG_CANCEL, $value['rescode'], $value['userid'], $value['nickname'], $viewEntry['subject'] . ' 산행 취소', $now);
      }
    }

    $rtn = $this->admin_model->updateEntry($updateValues, $search['rescode']);

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

      // 버스 종류 확인
      $bus_type = getBusType($viewData['view']['bustype'], $viewData['view']['bus']);

      $viewData['view']['bustype'] = @unserialize($viewData['view']['bustype']);
      $viewData['view']['road_course'] = @unserialize($viewData['view']['road_course']);
      $viewData['view']['road_address'] = @unserialize($viewData['view']['road_address']);
      $viewData['view']['road_distance'] = @unserialize($viewData['view']['road_distance']);
      $viewData['view']['road_runtime'] = @unserialize($viewData['view']['road_runtime']);
      $viewData['view']['road_cost'] = @unserialize($viewData['view']['road_cost']);
      $viewData['view']['driving_fuel'] = @unserialize($viewData['view']['driving_fuel']);
      $viewData['view']['driving_cost'] = @unserialize($viewData['view']['driving_cost']);
      $viewData['view']['driving_add'] = @unserialize($viewData['view']['driving_add']);

      if (empty($viewData['view']['road_course'][0])) $viewData['view']['road_course'][0] = '기본운행구간';
      if (empty($viewData['view']['road_address'][0])) $viewData['view']['road_address'][0] = '경기도 부천시 수도로 344';
      if (empty($viewData['view']['road_distance'][0])) $viewData['view']['road_distance'][0] = '43.44';
      if (empty($viewData['view']['road_runtime'][0])) $viewData['view']['road_runtime'][0] = '0:50';
      if (empty($viewData['view']['road_cost'][0])) $viewData['view']['road_cost'][0] = '0';

      $viewData['maxSeat'] = 0; // 최대 좌석 계산
      foreach ($bus_type as $bus) {
        $viewData['maxSeat'] += $bus['seat'];
      }

      // 승객수에 따라 승객수당 지정
      $cntRes = cntRes($viewData['view']['idx']);

      // 승객수당
      if ($cntRes < 30) {
        $viewData['view']['cost_driver'] = 0;
      } elseif ($cntRes >= 30 && $cntRes < 40) {
        $viewData['view']['cost_driver'] = 40000;
      } elseif ($cntRes >= 30 && $cntRes < $viewData['maxSeat']) {
        $viewData['view']['cost_driver'] = 80000;
      } elseif ($cntRes == $viewData['maxSeat']) {
        $viewData['view']['cost_driver'] = 120000;
      }
    } else {
      $viewData['btn'] = '등록';
      $viewData['view']['idx'] = '';
      $viewData['view']['startdate'] = '';
      $viewData['view']['starttime'] = '';
      $viewData['view']['enddate'] = '';
      $viewData['view']['mname'] = '';
      $viewData['view']['area_sido'] = '';
      $viewData['view']['area_gugun'] = '';
      $viewData['view']['subject'] = '';
      $viewData['view']['content'] = '';
      $viewData['view']['bustype'] = '';
      $viewData['view']['article'] = '';
      $viewData['view']['peak'] = '';
      $viewData['view']['winter'] = '';
      $viewData['view']['distance'] = '';
      $viewData['view']['road_course'][0] = '기본운행구간';
      $viewData['view']['road_address'][0] = '경기도 부천시 수도로 344';
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
      $viewData['costGas'] = 0;
    }

    // 산행 목록
    $viewData['listNotice'] = $this->admin_model->listNotice(NULL, 'desc');

    // 버스 형태
    $viewData['listBustype'] = $this->admin_model->listBustype();

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

    if (empty($viewData['view']['driving_fuel'][2])) {
      // 전국 유가 정보 (오피넷 Key : F657191209)
      $url = 'http://www.opinet.co.kr/api/avgSidoPrice.do?out=xml&code=F657191209';
      $ch = cURL_init();

      cURL_setopt($ch, CURLOPT_URL, $url);
      cURL_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

      $response = cURL_exec($ch);
      cURL_close($ch); 

      $object = simplexml_load_string($response);
      foreach ($object as $value) {
        // 인천, 경유 정보 추출
        if ($value->SIDOCD == '15' && $value->PRODCD == 'D047') {
          $viewData['costGas'] = $value->PRICE;
        }
      }
    }

    $this->_viewPage('admin/main_entry', $viewData);
  }

  /**
   * 산행 등록시 다른 산행 정보 적용
   *
   * @return json
   * @author bjchoi
   **/
  public function main_entry_notice()
  {
    $result = '';
    $idx = html_escape($this->input->post('idx'));

    if (!empty($idx)) {
      $result = $this->admin_model->viewEntry($idx);

      $result['plan'] = reset_html_escape($result['plan']);
      $result['point'] = reset_html_escape($result['point']);
      $result['intro'] = reset_html_escape($result['intro']);
      $result['timetable'] = reset_html_escape($result['timetable']);
      $result['information'] = reset_html_escape($result['information']);
      $result['course'] = reset_html_escape($result['course']);

      // 지역
      if (!empty($result['area_sido'])) {
        $areaSido = unserialize($result['area_sido']);
        $areaGugun = unserialize($result['area_gugun']);

        foreach ($areaSido as $key => $sido) {
          $result['sido'][$key] = $sido;
          $result['gugun'][$key] = $areaGugun[$key];
        }
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 구/군 정보 불러오기
   *
   * @return json
   * @author bjchoi
   **/
  public function main_entry_notice_area()
  {
    $sido = html_escape($this->input->post('sido'));

    $result['area_sido'] = $this->area_model->listSido();
    $result['area_gugun'] = $this->area_model->listGugun($sido);

    $this->output->set_output(json_encode($result));
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
        'area_sido'       => make_serialize($this->input->post('area_sido')),     // 지역 시/도
        'area_gugun'      => make_serialize($this->input->post('area_gugun')),    // 지역 구/군
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
        'road_address'    => make_serialize($this->input->post('road_address')),  // 도착지주소
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
        $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
      } else {
        $result = array('error' => 0, 'message' => $this->lang->line('msg_complete'));
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

    // 산행 정보
    $viewData['view'] = $this->admin_model->viewEntry(html_escape($idx));

    // 산행 목록
    $viewData['listNotice'] = $this->admin_model->listNotice(NULL, 'desc');

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
    $search['userid'] = html_escape($postData['userid']);

    $insertValues  = array(
      'club_idx'    => $clubIdx,
      'notice_idx'  => html_escape($postData['idx']),
      'nickname'    => html_escape($postData['nickname']),
      'location'    => html_escape($postData['location']),
      'gender'      => html_escape($postData['gender']),
      'memo'        => html_escape($postData['memo']),
      'created_at'  => $now
    );

    if (!empty($search['userid'])) {
      $viewMember = $this->admin_model->viewMember($search);
      $insertValues['created_by'] = $viewMember['idx'];
    }

    $rtn = $this->admin_model->insertWait($insertValues);

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

  /**
   * 산행관리 - 산행예정 만들기
   *
   * @return view
   * @author bjchoi
   **/
  public function main_schedule()
  {
    $viewData['search']['syear'] = NULL;
    $viewData['search']['smonth'] = NULL;
    $viewData['search']['status'] = array(STATUS_PLAN);

    $sdate = html_escape($this->input->get('d'));
    if (!empty($sdate)) $viewData['sdate'] = html_escape($sdate); else $viewData['sdate'] = NULL;

    $viewData['listSchedule'] = $this->admin_model->listNotice($viewData['search']);

    // 캘린더 설정
    $listCalendar = $this->admin_model->listCalendar();

    foreach ($listCalendar as $key => $value) {
      if ($value['holiday'] == 1) {
        $class = 'holiday';
      } else {
        $class = 'dayname';
      }
      $viewData['listSchedule'][] = array(
        'idx' => 0,
        'startdate' => $value['nowdate'],
        'enddate' => $value['nowdate'],
        'schedule' => 0,
        'status' => 'schedule',
        'subject' => $value['dayname'],
        'class' => $class,
      );
    }

    $this->_viewPage('admin/main_schedule', $viewData);
  }

  /**
   * 설정 - 지난 산행 보기
   *
   * @return view
   * @author bjchoi
   **/
  public function main_schedule_past()
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

        $search['past_sdate'] = date('m-d', strtotime($sdate) - (86400 * 5));
        $search['past_edate'] = date('m-d', strtotime($edate) + (86400 * 5));

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
      $search['past_sdate'] = date('m-d', strtotime($sdate) - (86400 * 5));
      $search['past_edate'] = date('m-d', strtotime($edate) + (86400 * 5));

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
  public function main_schedule_update()
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
  public function main_schedule_delete()
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
   * 산행관리 - 산행일정 복사하기
   *
   * @return view
   * @author bjchoi
   **/
  public function main_list_copy()
  {
    // 등록된 산행 목록
    $search['sdate'] = date('Y-m-01');
    $search['edate'] = date('Y-m-t', time() + (60 * 60 * 24 * 30 * 12));
    $search['status'] = array(STATUS_PLAN, STATUS_ABLE, STATUS_CONFIRM, STATUS_CANCEL, STATUS_CLOSED);
    $viewData['listNotice'] = $this->admin_model->listNotice($search);

    foreach ($viewData['listNotice'] as $key => $value) {
      $viewData['listCalendar'][] = array(
        'idx' => $value['idx'],
        'startdate' => $value['startdate'],
        'starttime' => $value['starttime'],
        'enddate' => $value['enddate'],
        'schedule' => $value['schedule'],
        'status' => $value['status'],
        'mname' => $value['mname'],
        'class' => '',
      );
    }

    // 캘린더 설정
    $listCalendar = $this->admin_model->listCalendar();

    foreach ($listCalendar as $key => $value) {
      if ($value['holiday'] == 1) {
        $class = 'holiday';
      } else {
        $class = 'dayname';
      }
      $viewData['listCalendar'][] = array(
        'idx' => 0,
        'startdate' => $value['nowdate'],
        'enddate' => $value['nowdate'],
        'schedule' => 0,
        'status' => 'schedule',
        'mname' => $value['dayname'],
        'class' => $class,
      );
    }

    $this->load->view('admin/main_list_copy', $viewData);
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
  public function member_view($keyword)
  {
    $viewData['clubIdx'] = 1;
    $keyword = html_escape($keyword);

    $search = array('idx' => $keyword);
    $viewData['view'] = $this->admin_model->viewMember($search);

    if (empty($viewData['view'])) {
      $search = array('userid' => $keyword);
      $viewData['view'] = $this->admin_model->viewMember($search);
    }

    $viewData['view']['birthday'] = explode('/', $viewData['view']['birthday']);
    $viewData['view']['phone'] = explode('-', $viewData['view']['phone']);
    $viewData['view']['memberLevel'] = memberLevel($viewData['view']['rescount'], $viewData['view']['penalty'], $viewData['view']['level'], $viewData['view']['admin']);

    // 회원 정보
    $viewData['viewMember'] = $this->member_model->viewMember($viewData['clubIdx'], $viewData['view']['idx']);

    // 예약 내역
    $paging['perPage'] = 5; $paging['nowPage'] = 0;
    $viewData['userReserve'] = $this->reserve_model->userReserve($viewData['clubIdx'], $viewData['view']['userid'], NULL, $paging);

    // 예약 취소 내역 (로그)
    $viewData['userReserveCancel'] = $this->reserve_model->userReserveCancel($viewData['clubIdx'], $viewData['view']['userid']);

    // 산행 내역
    $viewData['userVisit'] = $this->reserve_model->userVisit($viewData['clubIdx'], $viewData['view']['userid']);

    // 산행 횟수
    $viewData['userVisitCount'] = $this->reserve_model->userVisitCount($viewData['clubIdx'], $viewData['view']['userid']);

    // 포인트 내역
    $viewData['userPoint'] = $this->member_model->userPointLog($viewData['clubIdx'], $viewData['view']['userid']);

    // 페널티 내역
    $viewData['userPenalty'] = $this->member_model->userPenaltyLog($viewData['clubIdx'], $viewData['view']['userid']);

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
    $inputData = $this->input->post();
    $idx = html_escape($this->input->post('idx'));

    $updateValues = array(
      'nickname'      => html_escape($inputData['nickname']),
      'realname'      => html_escape($inputData['realname']),
      'gender'        => html_escape($inputData['gender']),
      'birthday'      => html_escape($inputData['birthday_year']) . '/' . html_escape($inputData['birthday_month']) . '/' . html_escape($inputData['birthday_day']),
      'birthday_type' => html_escape($inputData['birthday_type']),
      'phone'         => html_escape($inputData['phone1']) . '-' . html_escape($inputData['phone2']) . '-' . html_escape($inputData['phone3']),
      'location'      => html_escape($inputData['location']),
    );

    // 평생회원, 무료회원, 드라이버
    if (!empty($inputData['level'])) {
      $updateValues['level'] = html_escape($inputData['level']);
    } else {
      $updateValues['level'] = 0;
    }

    // 관리자
    if (!empty($inputData['admin'])) {
      $updateValues['admin'] = $inputData['admin'];
    } else {
      $updateValues['admin'] = 0;
    }

    $result = $this->admin_model->updateMember($updateValues, $idx);

    $this->output->set_output(json_encode($result));
  }

  /**
   * 회원 포인트/페널티 수정
   *
   * @return view
   * @author bjchoi
   **/
  public function member_update_point($idx)
  {
    $now = time();
    $search['idx'] = html_escape($idx);
    $type = html_escape($this->input->post('type'));
    $point = html_escape($this->input->post('point'));
    $penalty = html_escape($this->input->post('penalty'));
    $subject = !empty($this->input->post('subject')) ? html_escape($this->input->post('subject')) : '';

    // 회원 정보
    $viewMember = $this->admin_model->viewMember($search);

    switch ($type) {
      case 1: // 포인트 추가
        $updateValues['point'] = $viewMember['point'] + $point;
        setHistory(LOG_POINTUP, $search['idx'], $viewMember['userid'], $viewMember['nickname'], $subject, $now, $point);
        break;
      case 2: // 포인트 감소
        $updateValues['point'] = $viewMember['point'] - $point;
        setHistory(LOG_POINTDN, $search['idx'], $viewMember['userid'], $viewMember['nickname'], $subject, $now, $point);
        break;
      case 3: // 페널티 추가
        $updateValues['penalty'] = $viewMember['penalty'] + $penalty;
        setHistory(LOG_PENALTYUP, $search['idx'], $viewMember['userid'], $viewMember['nickname'], $subject, $now, $penalty);
        break;
      case 4: // 페널티 감소
        $updateValues['penalty'] = $viewMember['penalty'] - $penalty;
        setHistory(LOG_PENALTYDN, $search['idx'], $viewMember['userid'], $viewMember['nickname'], $subject, $now, $penalty);
        break;
    }

    $result = $this->admin_model->updateMember($updateValues, $search['idx']);

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
    // 랭킹별 닉네임 추출
    $viewData['listNickname'] = $this->admin_model->listAttendanceNickname();

    foreach ($viewData['listNickname'] as $key => $value) {
      $listAttendanceResCode = $this->admin_model->listAttendanceResCode($value['nickname']);
      $viewData['listNickname'][$key]['mname'] = '';

      foreach ($listAttendanceResCode as $cnt => $data) {
        $getAttendanceMountainName = $this->admin_model->getAttendanceMountainName($data['rescode']);
        if ($cnt != 0) $viewData['listNickname'][$key]['mname'] .= ' / ';
        $viewData['listNickname'][$key]['mname'] .= $getAttendanceMountainName['mname'];
      }
    }

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
        if (!empty($arrDummy[$value['idx']])) {
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

    if (empty($rtn)) {
      $result['message'] = '에러가 발생했습니다.';
    } else {
      $result['message'] = '최신 데이터로 갱신했습니다.';
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 인증현황 등록
   *
   * @return view
   * @author bjchoi
   **/
  public function attendance_auth()
  {
    $search['status'] = array(STATUS_CLOSED);
    $search['sdate'] = '2019-04-06';
    $search['edate'] = date('Y-m-d');
    $viewData['listNotice'] = $this->admin_model->listNotice($search, 'desc');

    $this->_viewPage('admin/attendance_auth', $viewData);
  }

  /**
   * 인증현황 등록 처리
   *
   * @return view
   * @author bjchoi
   **/
  public function attendance_auth_insert()
  {
    $inputData = $this->input->post();

    $insertValues = array(
      'rescode' => html_escape($inputData['rescode']),
      'userid' => html_escape($inputData['userid']),
      'nickname' => html_escape($inputData['nickname']),
      'photo' => html_escape($inputData['photo']),
      'title' => html_escape($inputData['title']),
      'regdate' => time()
    );

    $rtn = $this->admin_model->insertAttendanceAuth($insertValues);

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_insert'));
    } else {
      $result = array('error' => 0, 'message' => $this->lang->line('msg_insert_complete'));
    }

    $this->output->set_output(json_encode($result));
  }


  /** ---------------------------------------------------------------------------------------
   * 활동관리
  --------------------------------------------------------------------------------------- **/

  /**
   * 활동관리 - 예약기록
   *
   * @return view
   * @return json
   * @author bjchoi
   **/
  public function log_reserve()
  {
    $viewData['keyword'] = !empty($this->input->post('k')) ? html_escape($this->input->post('k')) : NULL;
    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;
    $paging['perPage'] = 20;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];

    // 예약기록 불러오기
    $viewData['listReserve'] = $this->admin_model->listReserve($paging, $viewData['keyword']);

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('admin/log_reserve_append', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      // 1페이지에는 View 페이지로 전송
      $this->_viewPage('admin/log_reserve', $viewData);
    }
  }

  /**
   * 활동관리 - 회원 활동기록
   *
   * @return view
   * @return json
   * @author bjchoi
   **/
  public function log_user()
  {
    $action = html_escape($this->input->post('action'));
    $viewData['subject'] = html_escape($this->input->post('subject'));
    $viewData['nickname'] = html_escape($this->input->post('nickname'));
    $viewData['status'] = !empty($this->input->post('status')) ? html_escape($this->input->post('status')) : 0;
    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;

    $paging['perPage'] = $viewData['perPage'] = 30;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];

    if (!empty($action)) {
      $viewData['action'] = array($action);
      $viewData['listHistory'] = $this->admin_model->listHistory($paging, $viewData);
    } else {
      $viewData['action'] = array(LOG_ENTRY, LOG_RESERVE, LOG_CANCEL, LOG_POINTUP, LOG_POINTDN, LOG_PENALTYUP, LOG_PENALTYDN);
      $viewData['listHistory'] = $this->admin_model->listHistory($paging, $viewData);
      $viewData['action'][0] = ''; // 액션 초기화
    }

    $viewData['maxLog'] = $this->admin_model->cntHistory($viewData);
    $viewData['pageType'] = 'member';
    $viewData['pageUrl'] = base_url() . 'admin/log_user';
    $viewData['pageTitle'] = '회원 활동기록';

    foreach ($viewData['listHistory'] as $key => $value) {
      if (!empty($value['userid'])) {
        $search_member['userid'] = $value['userid'];
        $viewData['listHistory'][$key]['userData'] = $this->admin_model->viewMember($search_member);
      } else {
        $viewData['listHistory'][$key]['userData']['nickname'] = $value['nickname'];
      }

      switch ($value['action']) {
        case '1': // 회원등록
          $viewData['listHistory'][$key]['header'] = '[회원등록]';
          $viewData['listHistory'][$key]['subject'] = $value['userid'];
          break;
        case '2': // 예약
          $viewData['listHistory'][$key]['header'] = '<span class="text-primary">[예약완료]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'];
          break;
        case '3': // 예약취소
          $viewData['listHistory'][$key]['header'] = '<span class="text-danger">[예약취소]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'];
          break;
        case '4': // 포인트 적립
          $viewData['listHistory'][$key]['header'] = '<span class="text-info">[포인트적립]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'] . ' - ' . number_format($value['point']) . ' 포인트적립';
          break;
        case '5': // 포인트 감소
          $viewData['listHistory'][$key]['header'] = '<span class="text-warning">[포인트감소]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'] . ' - ' . number_format($value['point']) . ' 포인트감소';
          break;
        case '6': // 페널티 추가
          $viewData['listHistory'][$key]['header'] = '<span class="text-warning">[페널티추가]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'] . ' - ' . number_format($value['point']) . ' 페널티추가';
          break;
        case '7': // 페널티 감소
          $viewData['listHistory'][$key]['header'] = '<span class="text-info">[페널티감소]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'] . ' - ' . number_format($value['point']) . ' 페널티감소';
          break;
      }
    }

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('admin/log_user_append', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      // 1페이지에는 View 페이지로 전송
      $viewData['listHistory'] = $this->load->view('admin/log_user_append', $viewData, true);
      $this->_viewPage('admin/log_user', $viewData);
    }
  }

  /**
   * 활동관리 - 관리자 활동기록
   *
   * @return view
   * @return json
   * @author bjchoi
   **/
  public function log_admin()
  {
    $action = html_escape($this->input->post('action'));
    $viewData['subject'] = html_escape($this->input->post('subject'));
    $viewData['nickname'] = html_escape($this->input->post('nickname'));
    $viewData['status'] = !empty($this->input->post('status')) ? html_escape($this->input->post('status')) : 0;
    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;

    $paging['perPage'] = $viewData['perPage'] = 30;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];

    if (!empty($action)) {
      $viewData['action'] = array($action);
      $viewData['listHistory'] = $this->admin_model->listHistory($paging, $viewData);
    } else {
      $viewData['action'] = array(LOG_ADMIN_RESERVE, LOG_ADMIN_CANCEL, LOG_ADMIN_DEPOSIT_CONFIRM, LOG_ADMIN_DEPOSIT_CANCEL);
      $viewData['listHistory'] = $this->admin_model->listHistory($paging, $viewData);
      $viewData['action'][0] = ''; // 액션 초기화
    }

    $viewData['maxLog'] = $this->admin_model->cntHistory($viewData);
    $viewData['pageType'] = 'admin';
    $viewData['pageUrl'] = base_url() . 'admin/log_admin';
    $viewData['pageTitle'] = '관리자 활동기록';

    foreach ($viewData['listHistory'] as $key => $value) {
      if (!empty($value['userid'])) {
        $search_member['userid'] = $value['userid'];
        $viewData['listHistory'][$key]['userData'] = $this->admin_model->viewMember($search_member);
      } else {
        $viewData['listHistory'][$key]['userData']['nickname'] = $value['nickname'];
      }

      switch ($value['action']) {
        case LOG_ADMIN_RESERVE: // 관리자 예약
          $viewData['listHistory'][$key]['header'] = '<span class="text-primary">[관리자예약완료]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'];
          break;
        case LOG_ADMIN_CANCEL: // 관리자 예약취소
          $viewData['listHistory'][$key]['header'] = '<span class="text-danger">[관리자예약취소]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'];
          break;
        case LOG_ADMIN_DEPOSIT_CONFIRM: // 관리자 입금확인
          $search_reserve['idx'] = $value['fkey'];
          $viewReserve = $this->admin_model->viewReserve($search_reserve);
          $viewData['listHistory'][$key]['header'] = '<span class="text-info">[관리자입금확인]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'];
          break;
        case LOG_ADMIN_DEPOSIT_CANCEL: // 관리자 입금취소
          $search_reserve['idx'] = $value['fkey'];
          $viewReserve = $this->admin_model->viewReserve($search_reserve);
          $viewData['listHistory'][$key]['header'] = '<span class="text-warning">[관리자입금취소]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'];
          break;
      }
    }

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('admin/log_user_append', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      // 1페이지에는 View 페이지로 전송
      $viewData['listHistory'] = $this->load->view('admin/log_user_append', $viewData, true);
      $this->_viewPage('admin/log_user', $viewData);
    }
  }

  /**
   * 활동관리 - 비회원 환불기록
   *
   * @return view
   * @return json
   * @author bjchoi
   **/
  public function log_refund()
  {
    $action = html_escape($this->input->post('action'));
    $viewData['subject'] = html_escape($this->input->post('subject'));
    $viewData['nickname'] = html_escape($this->input->post('nickname'));
    $viewData['status'] = !empty($this->input->post('status')) ? html_escape($this->input->post('status')) : 0;
    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;

    $paging['perPage'] = $viewData['perPage'] = 30;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];

    if (!empty($action)) {
      $viewData['action'] = array($action);
      $viewData['listHistory'] = $this->admin_model->listHistory($paging, $viewData);
    } else {
      $viewData['action'] = array(LOG_ADMIN_REFUND);
      $viewData['refund'] = 'refund';
      $viewData['listHistory'] = $this->admin_model->listHistory($paging, $viewData);
      $viewData['action'][0] = ''; // 액션 초기화
    }

    $viewData['maxLog'] = $this->admin_model->cntHistory($viewData);
    $viewData['pageType'] = 'refund';
    $viewData['pageUrl'] = base_url() . 'admin/log_refund';
    $viewData['pageTitle'] = '비회원 환불기록';

    foreach ($viewData['listHistory'] as $key => $value) {
      $viewData['listHistory'][$key]['userData']['nickname'] = $value['nickname'];

      $viewEntry = $this->admin_model->viewEntry($value['fkey']);
      $viewData['listHistory'][$key]['header'] = '<span class="text-danger">[비회원환불기록]</span>';
      $viewData['listHistory'][$key]['subject'] = number_format($viewEntry['cost_total']) . '원<br>' . $value['subject'];
    }

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('admin/log_user_append', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      // 1페이지에는 View 페이지로 전송
      $viewData['listHistory'] = $this->load->view('admin/log_user_append', $viewData, true);
      $this->_viewPage('admin/log_user', $viewData);
    }
  }

  /**
   * 활동관리 - 버스 변경기록
   *
   * @return view
   * @return json
   * @author bjchoi
   **/
  public function log_bus()
  {
    $action = html_escape($this->input->post('action'));
    $viewData['subject'] = html_escape($this->input->post('subject'));
    $viewData['nickname'] = html_escape($this->input->post('nickname'));
    $viewData['status'] = !empty($this->input->post('status')) ? html_escape($this->input->post('status')) : 0;
    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;

    $paging['perPage'] = $viewData['perPage'] = 30;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];

    if (!empty($action)) {
      $viewData['action'] = array($action);
      $viewData['listHistory'] = $this->admin_model->listHistory($paging, $viewData);
    } else {
      $viewData['action'] = array(LOG_DRIVER_CHANGE);
      $viewData['listHistory'] = $this->admin_model->listHistory($paging, $viewData);
      $viewData['action'][0] = ''; // 액션 초기화
    }

    $viewData['maxLog'] = $this->admin_model->cntHistory($viewData);
    $viewData['pageType'] = 'bus';
    $viewData['pageUrl'] = base_url() . 'admin/log_bus';
    $viewData['pageTitle'] = '버스 변경기록';

    foreach ($viewData['listHistory'] as $key => $value) {
      $viewEntry = $this->admin_model->viewEntry($value['fkey']);

      switch ($value['action']) {
        case LOG_DRIVER_CHANGE: // 차량 변경
          $viewData['listHistory'][$key]['header'] = '<span class="text-danger">[차량변경]</span>';
          $viewData['listHistory'][$key]['subject'] = '<a href="' . base_url() . 'admin/main_view_progress/' . $value['fkey'] . '">' . $viewEntry['subject'] . '</a> - ' . $value['subject'];
          break;
      }
    }

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('admin/log_user_append', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      // 1페이지에는 View 페이지로 전송
      $viewData['listHistory'] = $this->load->view('admin/log_user_append', $viewData, true);
      $this->_viewPage('admin/log_user', $viewData);
    }
  }

  /**
   * 활동관리 - 회원 구매기록
   *
   * @return view
   * @return json
   * @author bjchoi
   **/
  public function log_buy()
  {
    $action = html_escape($this->input->post('action'));
    $viewData['subject'] = html_escape($this->input->post('subject'));
    $viewData['nickname'] = html_escape($this->input->post('nickname'));
    $viewData['status'] = !empty($this->input->post('status')) ? html_escape($this->input->post('status')) : 0;
    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;

    $paging['perPage'] = $viewData['perPage'] = 30;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];

    if (!empty($action)) {
      $viewData['action'] = array($action);
      $viewData['listHistory'] = $this->admin_model->listHistory($paging, $viewData);
    } else {
      $viewData['action'] = array(LOG_SHOP_BUY, LOG_SHOP_CHECKOUT, LOG_SHOP_CANCEL);
      $viewData['listHistory'] = $this->admin_model->listHistory($paging, $viewData);
      $viewData['action'][0] = ''; // 액션 초기화
    }

    $viewData['maxLog'] = $this->admin_model->cntHistory($viewData);
    $viewData['pageType'] = 'buy';
    $viewData['pageUrl'] = base_url() . 'admin/log_buy';
    $viewData['pageTitle'] = '회원 구매기록';

    foreach ($viewData['listHistory'] as $key => $value) {
      $viewData['listHistory'][$key]['userData']['nickname'] = $value['nickname'];
      $viewOrder = $this->shop_model->viewPurchase($value['fkey']);

      switch ($value['action']) {
        case LOG_SHOP_BUY: // 용품판매 - 구매
          $viewData['listHistory'][$key]['header'] = '<span class="text-primary">[구매완료]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'];
          break;
        case LOG_SHOP_CHECKOUT: // 용품판매 - 결제
          $viewData['listHistory'][$key]['header'] = '<span class="text-info">[결제완료]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'];
          break;
        case LOG_SHOP_CANCEL: // 용품판매 - 취소
          $viewData['listHistory'][$key]['header'] = '<span class="text-danger">[구매취소]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'];
          break;
      }
    }

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('admin/log_user_append', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      // 1페이지에는 View 페이지로 전송
      $viewData['listHistory'] = $this->load->view('admin/log_user_append', $viewData, true);
      $this->_viewPage('admin/log_user', $viewData);
    }
  }

  /**
   * 활동관리 - 관리자 구매기록
   *
   * @return view
   * @return json
   * @author bjchoi
   **/
  public function log_buy_admin()
  {
    $action = html_escape($this->input->post('action'));
    $viewData['subject'] = html_escape($this->input->post('subject'));
    $viewData['nickname'] = html_escape($this->input->post('nickname'));
    $viewData['status'] = !empty($this->input->post('status')) ? html_escape($this->input->post('status')) : 0;
    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;

    $paging['perPage'] = $viewData['perPage'] = 30;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];

    if (!empty($action)) {
      $viewData['action'] = array($action);
      $viewData['listHistory'] = $this->admin_model->listHistory($paging, $viewData);
    } else {
      $viewData['action'] = array(LOG_ADMIN_SHOP_BUY, LOG_ADMIN_SHOP_CANCEL, LOG_ADMIN_SHOP_DEPOSIT_CONFIRM, LOG_ADMIN_SHOP_DEPOSIT_CANCEL, LOG_ADMIN_SHOP_COMPLETE);
      $viewData['listHistory'] = $this->admin_model->listHistory($paging, $viewData);
      $viewData['action'][0] = ''; // 액션 초기화
    }

    $viewData['maxLog'] = $this->admin_model->cntHistory($viewData);
    $viewData['pageType'] = 'buy_admin';
    $viewData['pageUrl'] = base_url() . 'admin/log_buy_admin';
    $viewData['pageTitle'] = '관리자 구매기록';

    foreach ($viewData['listHistory'] as $key => $value) {
      $viewData['listHistory'][$key]['userData']['nickname'] = $value['nickname'];
      $viewOrder = $this->shop_model->viewPurchase($value['fkey']);

      switch ($value['action']) {
        case LOG_ADMIN_SHOP_BUY: // 용품판매 관리자 - 구매
          $viewData['listHistory'][$key]['header'] = '<span class="text-primary">[구매완료]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'];
          break;
        case LOG_ADMIN_SHOP_CANCEL: // 용품판매 관리자 - 취소
          $viewData['listHistory'][$key]['header'] = '<span class="text-info">[구매취소]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'];
          break;
        case LOG_ADMIN_SHOP_DEPOSIT_CONFIRM: // 용품판매 관리자 - 입금확인
          $viewData['listHistory'][$key]['header'] = '<span class="text-danger">[입금확인]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'];
          break;
        case LOG_ADMIN_SHOP_DEPOSIT_CANCEL: // 용품판매 관리자 - 입금취소
          $viewData['listHistory'][$key]['header'] = '<span class="text-danger">[입금취소]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'];
          break;
        case LOG_ADMIN_SHOP_COMPLETE: // 용품판매 관리자 - 판매완료
          $viewData['listHistory'][$key]['header'] = '<span class="text-primary">[판매완료]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'];
          break;
      }
    }

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('admin/log_user_append', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      // 1페이지에는 View 페이지로 전송
      $viewData['listHistory'] = $this->load->view('admin/log_user_append', $viewData, true);
      $this->_viewPage('admin/log_user', $viewData);
    }
  }

  /**
   * 활동관리 - 확인 체크
   *
   * @return json
   * @author bjchoi
   **/
  public function log_check()
  {
    $idx = html_escape($this->input->post('idx'));
    $status = html_escape($this->input->post('status'));

    $viewHistory = $this->admin_model->viewHistory($idx);

    if ($viewHistory['status'] == 1) $status = 0; else $status = 1;

    $updateValues = array('status' => $status);
    $rtn = $this->admin_model->updateHistory($updateValues, $idx);

    if (empty($rtn)) {
      $result = array('error' => 1, 'status' => $status, 'message' => $this->lang->line('error_all'));
    } else {
      $result = array('error' => 0, 'status' => $status, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 활동관리 - 클럽 댓글
   *
   * @return view
   * @author bjchoi
   **/
  public function log_reply()
  {
    $viewData['listReply'] = $this->admin_model->listReply();

    $this->_viewPage('admin/log_reply', $viewData);
  }

  /**
   * 활동관리 - 클럽 댓글 삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function log_reply_delete()
  {
    $userIdx = html_escape($this->session->userData['idx']);
    $idx = html_escape($this->input->post('idx'));

    $updateValues = array(
      'deleted_by' => $userIdx,
      'deleted_at' => time()
    );
    $this->admin_model->deleteReply($updateValues, $idx);

    $result['reload'] = true;
    $this->output->set_output(json_encode($result));
  }

  /**
   * 활동관리 - 좋아요/공유
   *
   * @return view
   * @author bjchoi
   **/
  public function log_reaction()
  {
    $viewData['listReaction'] = $this->admin_model->listReaction();

    $this->_viewPage('admin/log_reaction', $viewData);
  }

  /**
   * 활동관리 - 좋아요/공유 삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function log_reaction_delete()
  {
    $created_by = html_escape($this->input->post('created_by'));
    $created_at = html_escape($this->input->post('created_at'));

    $result = $this->admin_model->deleteReaction($created_by, $created_at);

    $this->output->set_output(json_encode($result));
  }

  /**
   * 활동관리 - 방문자 기록
   *
   * @return view
   * @author bjchoi
   **/
  public function log_visitor()
  {
    $viewData['keyword']      = $this->input->post('keyword') ? html_escape($this->input->post('keyword')) : '';
    $viewData['nowdate']      = $this->input->post('nowdate') ? html_escape($this->input->post('nowdate')) : date('Ymd');
    $viewData['searchYear']   = date('Y', strtotime($viewData['nowdate']));
    $viewData['searchMonth']  = date('m', strtotime($viewData['nowdate']));
    $viewData['searchDay']    = date('d', strtotime($viewData['nowdate']));
    $viewData['searchPrev']   = date('Ymd', strtotime('-1 day', strtotime($viewData['nowdate'])));
    $viewData['searchNext']   = date('Ymd', strtotime('+1 day', strtotime($viewData['nowdate'])));

    $viewData['listVisitor'] = $this->admin_model->listVisitor($viewData);

    $this->_viewPage('admin/log_visitor', $viewData);
  }

  /** ---------------------------------------------------------------------------------------
   * 설정
  --------------------------------------------------------------------------------------- **/

  /**
   * 설정 - 클럽 정보
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_information()
  {
    // 클럽 정보
    $clubIdx = 1; // 경인웰빙
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

    $this->_viewPage('admin/setup_information', $viewData);
  }

  /**
   * 설정 - 소개 페이지 저장
   *
   * @return json
   * @author bjchoi
   **/
  public function setup_information_update()
  {
    $now = time();
    $clubIdx = 1; // 경인웰빙
    $page = 'club';
    $userIdx = $this->session->userData['idx'];
    $input_data = $this->input->post();
    $file = html_escape($input_data['file']);

    $updateValues = array(
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
      'updated_by'        => $userIdx,
      'updated_at'        => $now
    );

    $rtn = $this->club_model->updateClub($updateValues, $clubIdx);

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

    redirect(base_url() . 'admin/setup_information');
  }

  /**
   * 설정 - 소개 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_pages()
  {
    // 클럽 정보
    $clubIdx = 1; // 경인웰빙
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    $this->_viewPage('admin/setup_pages', $viewData);
  }

  /**
   * 설정 - 소개 페이지 저장
   *
   * @return json
   * @author bjchoi
   **/
  public function setup_pages_update()
  {
    $now = time();
    $clubIdx = 1; // 경인웰빙
    $userIdx = $this->session->userData['idx'];
    $input_data = $this->input->post();

    $updateValues = array(
      'about'       => html_escape($input_data['about']),
      'guide'       => html_escape($input_data['guide']),
      'howto'       => html_escape($input_data['howto']),
      'auth'        => html_escape($input_data['auth']),
      'updated_by'  => $userIdx,
      'updated_at'  => $now
    );

    $this->club_model->updateClub($updateValues, $clubIdx);

    redirect(base_url() . 'admin/setup_pages');
  }

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
      $viewData['viewBustype']['memo'] = '';
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
    $bus_name     = $this->input->post('bus_name') != '' ? html_escape($this->input->post('bus_name')) : NULL;
    $bus_owner    = $this->input->post('bus_owner') != '' ? html_escape($this->input->post('bus_owner')) : NULL;
    $bus_license  = $this->input->post('bus_license') != '' ? html_escape($this->input->post('bus_license')) : NULL;
    $bus_color    = $this->input->post('bus_color') != '' ? html_escape($this->input->post('bus_color')) : NULL;
    $bus_seat     = $this->input->post('bus_seat') != '' ? html_escape($this->input->post('bus_seat')) : NULL;
    $memo         = $this->input->post('memo') != '' ? html_escape($this->input->post('memo')) : NULL;

    $insertData = array(
      'bus_name'    => $bus_name,
      'bus_owner'   => $bus_owner,
      'bus_license' => $bus_license,
      'bus_color'   => $bus_color,
      'bus_seat'    => $bus_seat,
      'memo'        => $memo,
      'created_at'  => $now,
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
    $bus_license  = $this->input->post('bus_license') != '' ? html_escape($this->input->post('bus_license')) : NULL;
    $bus_color  = $this->input->post('bus_color') != '' ? html_escape($this->input->post('bus_color')) : NULL;
    $bus_seat   = $this->input->post('bus_seat') != '' ? html_escape($this->input->post('bus_seat')) : NULL;
    $memo       = $this->input->post('memo') != '' ? html_escape($this->input->post('memo')) : NULL;
    $result     = 0;

    if (!is_null($idx)) {
      $updateData = array(
        'bus_name' => $bus_name,
        'bus_owner' => $bus_owner,
        'bus_license' => $bus_license,
        'bus_color' => $bus_color,
        'bus_seat' => $bus_seat,
        'memo' => $memo,
      );

      $result = $this->admin_model->updateBustype($updateData, $idx);
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 설정 - 차종등록 순서 변경
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_bustype_sort()
  {
    $sort = html_escape($this->input->post('sort'));
    $arrSort = explode(',', $sort);

    foreach ($arrSort as $key => $value) {
      if (!empty($value)) {
        $updateValues['sort'] = $key + 1;
        $this->admin_model->updateBustype($updateValues, $value);
      }
    }
  }

  /**
   * 설정 - 차종 숨기기
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_bustype_hide()
  {
    $idx = $this->input->post('idx') != '' ? html_escape($this->input->post('idx')) : NULL;
    $result = array('error' => 1, 'message' => $this->lang->line('error_all'));

    if (!is_null($idx)) {
      $getBusType = $this->admin_model->getBustype($idx);
      if ($getBusType['visible'] == 'Y') $visible = 'N'; else $visible = 'Y';

      $updateData = array('visible' => $visible);
      $this->admin_model->updateBustype($updateData, $idx);

      $result = array('error' => 0, 'message' => $visible);
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
   * 설정 - 달력 일정관리
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_calendar()
  {
    $sdate = html_escape($this->input->get('d'));
    if (!empty($sdate)) $viewData['sdate'] = html_escape($sdate); else $viewData['sdate'] = NULL;

    // 캘린더 설정
    $viewData['listCalendar'] = $this->admin_model->listCalendar();

    $this->_viewPage('admin/setup_calendar', $viewData);
  }

  /**
   * 설정 - 달력 일정 입력/수정
   *
   * @return json
   * @author bjchoi
   **/
  public function setup_calendar_update()
  {
    $now = time();
    $idx = html_escape($this->input->post('idx'));
    $postData['nowdate'] = html_escape($this->input->post('nowdate'));
    $postData['dayname'] = html_escape($this->input->post('dayname'));
    $postData['holiday'] = html_escape($this->input->post('holiday'));

    if (!empty($idx)) {
      $rtn = $this->admin_model->updateCalendar($postData, $idx);
    } else {
      $rtn = $this->admin_model->insertCalendar($postData);
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_update'));
    } else {
      $result = array('error' => 0, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 설정 - 달력 일정 삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function setup_calendar_delete()
  {
    $idx = html_escape($this->input->post('idx'));
    $rtn = $this->admin_model->deleteCalendar($idx);

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_delete'));
    } else {
      $result = array('error' => 0, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 사용자 로그인
   *
   * @return json
   * @author bjchoi
   **/
  public function user_login()
  {
    $search['idx'] = html_escape($this->input->post('idx'));

    if (!empty($search['idx'])) {
      $userData = $this->admin_model->viewMember($search);
      $this->session->unset_userdata('userData');
      $this->session->set_userdata('userData', $userData);
      $result = array('error' => 0);
    } else {
      $result = array('error' => 1);
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
    $search['nickname'] = html_escape($this->input->post('nickname'));
    $rtn = $this->admin_model->viewMember($search);

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => '');
    } else {
      $result = array('error' => 0, 'message' => $rtn);
    }

    $this->output->set_output(json_encode($result));
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
    $headerData['keyword'] = html_escape($this->input->post('k'));
    $this->load->view('admin/header', $headerData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('admin/footer');
  }
}
?>
