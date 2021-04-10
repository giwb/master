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
    $this->load->model(array('admin_model', 'area_model', 'club_model', 'desk_model', 'file_model', 'member_model', 'shop_model', 'story_model'));
  }

  /**
   * 관리자 인덱스
   *
   * @return view
   * @author bjchoi
   **/
  public function index()
  {
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    if (empty($viewData['clubIdx'])) {
      redirect(BASE_URL . '/admin');
      exit;
    }

    $listBookmark = $this->admin_model->listBookmark($viewData['clubIdx']);

    foreach ($listBookmark as $value) {
      if (empty($value['title']) && empty($value['link']) && !empty($value['memo'])) {
        $viewData['viewBookmark']['idx'] = $value['idx'];
        $viewData['viewBookmark']['memo'] = $value['memo'];
      } elseif (empty($value['parent_idx'])) {
        $viewData['listBookmark'][] = $value;
      }
    }

    if (!empty($viewData['listBookmark'])) {
      foreach ($viewData['listBookmark'] as $key => $value) {
        foreach ($listBookmark as $key2 => $bookmark) {
          if ($bookmark['parent_idx'] == $value['idx']) {
            $viewData['listBookmark'][$key]['bookmark'][$key2]['idx'] = $bookmark['idx'];
            $viewData['listBookmark'][$key]['bookmark'][$key2]['link'] = $bookmark['link'];
            $viewData['listBookmark'][$key]['bookmark'][$key2]['title'] = $bookmark['title'];
            if (!empty($bookmark['memo'])) $viewData['listBookmark'][$key]['bookmark'][$key2]['memo'] = $bookmark['memo']; else $viewData['listBookmark'][$key]['bookmark'][$key2]['memo'] = '';
          }
        }
      }
    }

    // 현재 회원수
    $viewData['cntTotalMember'] = $this->admin_model->cntTotalMember($viewData['clubIdx']);
    // 다녀온 산행횟수
    $viewData['cntTotalTour'] = $this->admin_model->cntTotalTour($viewData['clubIdx']);
    // 다녀온 산행 인원수
    $viewData['cntTotalCustomer'] = $this->admin_model->cntTotalCustomer($viewData['clubIdx']);
    // 오늘 방문자수
    $viewData['cntTodayVisitor'] = $this->admin_model->cntTodayVisitor($viewData['clubIdx']);

    $viewData['pageTitle'] = '관리페이지';

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
    $viewData['clubIdx'] = html_escape($this->input->post('clubIdx'));
    $viewData['idx'] = html_escape($this->input->post('resIdx'));
    $viewData['view'] = $this->admin_model->viewEntry($idx);

    // 클럽 정보
    $viewData['viewClub'] = $this->club_model->viewClub($viewData['clubIdx']);

    if (!empty($viewData['idx'])) {
      $result['reserve'] = $this->admin_model->viewReserve($viewData);
      if (empty($result['reserve']['user_idx'])) $result['reserve']['user_idx'] = '';
      if (empty($result['reserve']['depositname'])) $result['reserve']['depositname'] = '';
      if (empty($result['reserve']['memo'])) $result['reserve']['memo'] = '';
    } else {
      $result['reserve']['user_idx'] = '';
      $result['reserve']['nickname'] = '';
      $result['reserve']['gender'] = 'M';
      $result['reserve']['loc'] = '';
      $result['reserve']['bref'] = '';
      $result['reserve']['depositname'] = '';
      $result['reserve']['memo'] = '';
      $result['reserve']['vip'] = '';
      $result['reserve']['manager'] = '';
      $result['reserve']['priority'] = '';
      $result['reserve']['honor'] = '';
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

    $result['location'] = arrLocation($viewData['viewClub']['club_geton']); // 승차위치
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
    // 클럽ID
    $clubIdx = get_cookie('COOKIE_CLUBIDX');

    $now = time();
    $idx = html_escape($this->input->post('idx'));
    $arrResIdx = $this->input->post('resIdx');
    $arrSeat = $this->input->post('seat');
    $arrUseridx = $this->input->post('userIdx');
    $arrNickname = $this->input->post('nickname');
    $arrGender = $this->input->post('gender');
    $arrBus = $this->input->post('bus');
    $arrLocation = $this->input->post('location');
    $arrMemo = $this->input->post('memo');
    $arrDepositName = $this->input->post('depositname');
    $arrVip = $this->input->post('vip');
    $arrManager = $this->input->post('manager');
    $arrPriority = $this->input->post('priority');
    $arrHonor = $this->input->post('honor');

    // 산행 정보
    $viewEntry = $this->admin_model->viewEntry($idx);

    foreach ($arrSeat as $key => $seat) {
      $nowUseridx = html_escape($arrUseridx[$key]);
      $nowNick = html_escape($arrNickname[$key]);
      $nowBus = html_escape($arrBus[$key]);
      $nowSeat = html_escape($seat);
      $nowLocation = html_escape($arrLocation[$key]);
      $nowManager = html_escape($arrManager[$key]) == 'true' ? 1 : 0;
      $nowPriority = html_escape($arrPriority[$key]) == 'true' ? 1 : 0;
      $nowHonor = html_escape($arrHonor[$key]) == 'true' ? 1 : 0;

      if ($nowLocation == '' || $nowLocation == '승차위치') {
        $nowLocation = NULL;
      }

      $postData = array(
        'rescode' => $idx,
        'user_idx' => $nowUseridx,
        'nickname' => $nowNick,
        'gender' => html_escape($arrGender[$key]),
        'bus' => $nowBus,
        'seat' => $nowSeat,
        'loc' => $nowLocation,
        'memo' => html_escape($arrMemo[$key]),
        'depositname' => html_escape($arrDepositName[$key]),
        'vip' => html_escape($arrVip[$key]) == 'true' ? 1 : 0,
        'manager' => $nowManager,
        'regdate' => $now
      );

      if (!empty($nowUseridx)) {
        $search['idx'] = $nowUseridx;
        $nowUserInfo = $this->admin_model->viewMember($search);
      }

      if ($nowManager == 1 || (!empty($nowUserInfo['level']) && $nowUserInfo['level'] == LEVEL_FREE)) {
        // 운영진우선석, 무료회원의 경우에는 입금확인 상태로
        $postData['status'] = RESERVE_PAY;
      }

      // 예약 번호 (수정시)
      $resIdx = html_escape($arrResIdx[$key]);

      // 선택한 좌석 예약 여부 확인
      $checkReserve = $this->admin_model->checkReserve($idx, $nowBus, $nowSeat);

      if (empty($resIdx)) {
        if (empty($checkReserve['idx'])) {
          $result = $this->admin_model->insertReserve($postData);

          if (!empty($result) && empty($nowPriority) && empty($nowHonor) && empty($nowManager)) {
            // 관리자 예약 기록
            setHistory($clubIdx, LOG_ADMIN_RESERVE, $idx, $nowUseridx, $nowNick, $viewEntry['subject'], $now);
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
              'user_idx' => NULL,
              'nickname' => '대기자우선',
              'bus' => $viewOldReserve['bus'],
              'seat' => $viewOldReserve['seat'],
              'loc' => NULL,
              'bref' => NULL,
              'memo' => NULL,
              'depositname' => NULL,
              'point' => 0,
              'priority' => 0,
              'honor' => 0,
              'vip' => 0,
              'manager' => 0,
              'penalty' => 0,
              'status' => RESERVE_WAIT
            );
            $this->admin_model->updateReserve($updateWait, $checkReserve['idx']);

            // 대기자 입력이기 때문에 미입금으로 변경
            $postData['status'] = RESERVE_ON;
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
        if (!empty($result)) {
          $result = $resIdx;
        }
      }

      if (!empty($nowPriority)) {
        $priorityIdx[] = $result; // 2인우선석일 경우, 각각의 고유번호를 저장
      }
      if (!empty($nowHonor)) {
        $honorIdx[] = $result; // 1인우등석일 경우, 각각의 고유번호를 저장
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
      // 1인우등석 처리 (각각의 예약 고유번호를 입력)
      if (!empty($honorIdx)) {
        $updateValues['honor'] = $honorIdx[0];
        $this->admin_model->updateReserve($updateValues, $honorIdx[1]);
        $updateValues['honor'] = $honorIdx[1];
        $this->admin_model->updateReserve($updateValues, $honorIdx[0]);
      }
      if ($viewEntry['status'] == STATUS_ABLE) {
        $cntReservation = $this->admin_model->cntReservation($idx);
        $cntReservationHonor = $this->admin_model->cntReservationHonor($idx);
        if ($cntReservation['CNT'] - ($cntReservationHonor['CNT'] / 2) >= 15) {
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
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $inputData['idx'] = html_escape($this->input->post('idx'));
    $subject = !empty($this->input->post('subject')) ? '<br>' . html_escape($this->input->post('subject')) : '';

    // 예약 정보
    $viewReserve = $this->admin_model->viewReserve($inputData);

    // 회원 정보
    $search['idx'] = $viewReserve['user_idx'];
    $userData = $this->admin_model->viewMember($search);

    // 산행 정보
    $viewEntry = $this->admin_model->viewEntry($viewReserve['rescode']);

    // 해당 산행과 버스의 예약자 수
    $cntReservation = $this->admin_model->cntReservation($viewReserve['rescode'], $viewReserve['bus'], 1);

    // 대기자 수
    $cntWait = $this->admin_model->cntWait($viewReserve['rescode']);

    $busType = getBusType($viewEntry['bustype'], $viewEntry['bus']);
    $maxSeat = array();
    foreach ($busType as $key => $value) {
      $cnt = $key + 1;
      $maxSeat[$cnt] = $value['seat'];
    }

    // 예약자가 초과됐을 경우, 대기자수가 1명 이상일 경우
    if ($cntReservation['CNT'] > $maxSeat[$viewReserve['bus']] || $cntWait['cnt'] >= 1) {
      // 예약 삭제 처리
      $updateValues = array(
        'user_idx' => NULL,
        'nickname' => '대기자우선',
        'gender' => '',
        'loc' => NULL,
        'bref' => NULL,
        'memo' => NULL,
        'depositname' => NULL,
        'point' => 0,
        'priority' => 0,
        'honor' => 0,
        'vip' => 0,
        'manager' => 0,
        'penalty' => 0,
        'status' => RESERVE_WAIT
      );
      // 만석일 경우에는 대기자 우선석으로 변경
      $rtn = $this->admin_model->updateReserve($updateValues, $inputData['idx']);

      // 1인우등 좌석은 2개 모두 변경
      if (!empty($viewReserve['honor'])) {
        $this->admin_model->updateReserve($updateValues, $viewReserve['honor']);
      }
    } else {
      // 좌석이 남아있을 경우
      if (!empty($viewReserve['priority']) && $viewReserve['nickname'] != '2인우선') {
        // 2인우선석이었던 경우 변경
        $updateValues = array(
          'user_idx' => NULL,
          'nickname' => '2인우선',
          'gender' => 'M',
          'loc' => 0,
          'memo' => NULL,
          'depositname' => NULL,
          'point' => 0,
          'vip' => 0,
          'manager' => 0,
          'penalty' => 0,
          'status' => 0
        );
        //$this->admin_model->updateReserve($updateValues, $viewReserve['priority']);
        $rtn = $this->admin_model->updateReserve($updateValues, $inputData['idx']);
      } elseif (!empty($viewReserve['honor']) && $viewReserve['nickname'] != '1인우등') {
        // 1인우등석이었던 경우 변경
        $updateValues = array(
          'user_idx' => NULL,
          'nickname' => '1인우등',
          'gender' => 'M',
          'loc' => 0,
          'memo' => NULL,
          'depositname' => NULL,
          'point' => 0,
          'vip' => 0,
          'manager' => 0,
          'penalty' => 0,
          'status' => 0
        );
        $this->admin_model->updateReserve($updateValues, $viewReserve['honor']);
        $rtn = $this->admin_model->updateReserve($updateValues, $inputData['idx']);
      } else {
        // 일반 예약의 경우 삭제
        $rtn = $this->admin_model->deleteReserve($inputData['idx']);
      }

      if ($viewEntry['status'] == STATUS_CONFIRM) {
        $cntReservation = $this->admin_model->cntReservation($viewReserve['rescode']);
        $cntReservationHonor = $this->admin_model->cntReservationHonor($viewReserve['rescode']);
        if ($cntReservation['CNT'] - ($cntReservationHonor['CNT'] / 2) < 15) {
          // 예약자가 15명 이하일 경우 예정으로 변경
          $updateValues = array('status' => STATUS_ABLE);
          $this->admin_model->updateEntry($updateValues, $viewReserve['rescode']);
        }
      }
    }

    if (!empty($rtn) && empty($viewReserve['manager'])) {
      $startTime = explode(':', $viewEntry['starttime']);
      $startDate = explode('-', $viewEntry['startdate']);
      $limitDate = mktime($startTime[0], $startTime[1], 0, $startDate[1], $startDate[2], $startDate[0]);

      // 예약 페널티
      $penalty = 0;
      if ( !empty($viewReserve['regdate']) && ($viewReserve['regdate'] + 43200) < $now ) {
        if ( $limitDate < $now ) {
          // 출발 이후 취소했다면 5점 페널티
          $penalty = 5;
        } elseif ( $limitDate < ($now + 86400) ) {
          // 1일전 취소시 3점 페널티
          $penalty = 3;
        } elseif ( $limitDate < ($now + 172800) ) {
          // 2일전 취소시 1점 페널티
          $penalty = 1;
        }
      }
      $this->member_model->updatePenalty($clubIdx, $viewReserve['user_idx'], ($userData['penalty'] + $penalty));

      // 예약 페널티 로그 기록
      if ($penalty > 0) {
        setHistory($clubIdx, LOG_PENALTYUP, $viewReserve['rescode'], $viewReserve['user_idx'], $viewReserve['nickname'], $viewEntry['subject'] . ' 관리자 예약 취소', $now, $penalty);
      }

      if ($viewReserve['status'] == RESERVE_PAY) {
        // 요금 합계 (기존 버젼 호환용)
        $viewEntry['cost'] = $viewEntry['cost_total'] == 0 ? $viewEntry['cost'] : $viewEntry['cost_total'];

        // 우등버스 할증 (2020/12/08 추가)
        /*
        if (!empty($busType[$viewReserve['bus']-1]['bus_type']) && $busType[$viewReserve['bus']-1]['bus_type'] == 1) {
          $viewEntry['cost'] = $viewEntry['cost'] + 10000;
          $viewEntry['cost_total'] = $viewEntry['cost_total'] + 10000;
        }
        */

        // 비회원 입금취소의 경우, 환불내역 기록
        if (empty($viewReserve['user_idx'])) {
          setHistory($clubIdx, LOG_ADMIN_REFUND, $viewReserve['rescode'], '', $viewReserve['nickname'], $viewEntry['subject'], $now);
        }

        // 이미 입금을 마친 상태라면, 전액 포인트로 환불 (무료회원은 환불 안함)
        if (empty($userData['level']) || $userData['level'] != 2) {
          if ($userData['level'] == 1) {
            if ($viewReserve['honor'] > 0) {
              // 1인우등 좌석 취소
              $viewEntry['cost'] = $viewEntry['cost'] + 5000;
              $this->member_model->updatePoint($clubIdx, $viewReserve['user_idx'], ($userData['point'] + $viewEntry['cost']));
            } else {
              // 평생회원은 할인 적용된 가격을 환불
              $viewEntry['cost'] = $viewEntry['cost'] - 5000;
              $this->member_model->updatePoint($clubIdx, $viewReserve['user_idx'], ($userData['point'] + $viewEntry['cost']));
            }
          } else {
            if ($viewReserve['honor'] > 0) {
              // 1인우등 좌석의 취소는 1만원 추가 환불
              $viewEntry['cost'] = $viewEntry['cost'] + 10000;
              $this->member_model->updatePoint($clubIdx, $viewReserve['user_idx'], ($userData['point'] + $viewEntry['cost']));
            } else {
              $this->member_model->updatePoint($clubIdx, $viewReserve['user_idx'], ($userData['point'] + $viewEntry['cost']));
            }
          }
          // 포인트 반환 로그 기록
          setHistory($clubIdx, LOG_POINTUP, $viewReserve['rescode'], $viewReserve['user_idx'], $viewReserve['nickname'], $viewEntry['subject'] . ' 관리자 예약 취소', $now, $viewEntry['cost']);
        }
      } elseif ($viewReserve['status'] == RESERVE_ON && $viewReserve['point'] > 0) {
        // 예약정보에 포인트가 있을때 반환
        $this->member_model->updatePoint($clubIdx, $viewReserve['user_idx'], ($userData['point'] + $viewReserve['point']));

        // 포인트 반환 로그 기록
        setHistory($clubIdx, LOG_POINTUP, $viewReserve['rescode'], $viewReserve['user_idx'], $viewReserve['nickname'], $viewEntry['subject'] . ' 관리자 예약 취소', $now, $viewReserve['point']);
      }

      // 관리자 예약취소 기록
      setHistory($clubIdx, LOG_ADMIN_CANCEL, $viewReserve['rescode'], $viewReserve['user_idx'], $viewReserve['nickname'], $viewEntry['subject'] . $subject, $now);

      // 예약 취소 로그 기록
      setHistory($clubIdx, LOG_CANCEL, $viewReserve['rescode'], $viewReserve['user_idx'], $viewReserve['nickname'], $viewEntry['subject'] . $subject, $now);
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
    // 클럽ID
    $clubIdx = get_cookie('COOKIE_CLUBIDX');

    $now = time();
    $viewData['idx'] = html_escape($this->input->post('idx'));
    $viewReserve = $this->admin_model->viewReserve($viewData);
    $viewEntry = $this->admin_model->viewEntry($viewReserve['rescode']);

    if ($viewReserve['status'] == 1) {
      // 입금취소
      $updateValues['status'] = RESERVE_ON;
      $this->admin_model->updateReserve($updateValues, $viewData['idx']);

      // 1인우등의 경우, 함께 예약된 좌석도 입금취소
      if ($viewReserve['honor'] > 0) {
        $this->admin_model->updateReserve($updateValues, $viewReserve['honor']);
      }

      // 관리자 입금취소 기록
      setHistory($clubIdx, LOG_ADMIN_DEPOSIT_CANCEL, $viewEntry['idx'], $viewReserve['user_idx'], $viewReserve['nickname'], $viewEntry['subject'], $now);

      // 비회원 입금취소의 경우, 환불내역 기록
      if (empty($viewReserve['user_idx'])) {
        setHistory($clubIdx, LOG_ADMIN_REFUND, $viewEntry['idx'], '', $viewReserve['nickname'], $viewEntry['subject'], $now);
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

      // 1인우등의 경우, 함께 예약된 좌석도 입금확인
      if ($viewReserve['honor'] > 0) {
        $this->admin_model->updateReserve($updateValues, $viewReserve['honor']);
      }

      // 관리자 입금확인 기록
      setHistory($clubIdx, LOG_ADMIN_DEPOSIT_CONFIRM, $viewEntry['idx'], $viewReserve['user_idx'], $viewReserve['nickname'], $viewEntry['subject'], $now);
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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    $search['clubIdx'] = $viewData['clubIdx'];
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

    // 페이지 타이틀
    $viewData['pageTitle'] = '진행중 산행';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'main_header';

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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    // 클럽 정보
    $viewData['viewClub'] = $this->club_model->viewClub($viewData['clubIdx']);

    // 예약ID
    $viewData['rescode'] = html_escape($idx);

    // 공지 정보
    $viewData['view'] = $this->admin_model->viewEntry($viewData['rescode']);

    // 시간대별 버스 승차위치
    $viewData['location'] = arrLocation($viewData['viewClub']['club_geton'], $viewData['view']['starttime'], NULL, NULL, 1);

    // 버스 형태별 좌석 배치
    $viewData['busType'] = getBusType($viewData['view']['bustype'], $viewData['view']['bus']);

    // 예약 정보
    $viewData['reserve'] = $this->admin_model->viewReserve($viewData);

    // 대기자 정보
    $viewData['wait'] = $this->admin_model->listWait($viewData['rescode']);

    // 클럽 승차위치
    $viewData['arrLocation'] = arrLocation($viewData['viewClub']['club_geton']);

    // 진행중 산행 목록
    $search['status'] = array(STATUS_ABLE, STATUS_CONFIRM);
    $viewData['list'] = $this->admin_model->listNotice($search);

    // 댓글
    $viewData['listReply'] = $this->admin_model->listReply($viewData['view']['club_idx'], NULL, $viewData['rescode']);
    $viewData['listReply'] = $this->load->view('admin/log_reply_append', $viewData, true);

    // 페이지 타이틀
    $viewData['pageTitle'] = '예약 관리';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'main_header';
    $viewData['headerMenuView'] = $this->load->view('admin/main_view_header', $viewData, true);

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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    // 클럽 정보
    $clubData = $this->club_model->viewClub($viewData['clubIdx']);

    $viewData['rescode'] = html_escape($idx);
    $viewData['view'] = $this->admin_model->viewEntry($viewData['rescode']);

    // 버스 형태별 좌석 배치
    $viewData['busType'] = getBusType($viewData['view']['bustype'], $viewData['view']['bus']);

    // 예약 정보
    $viewData['reserve'] = $this->admin_model->viewReserve($viewData);

    // 시간별 승차위치
    $listLocation = arrLocation($clubData['club_geton'], $viewData['view']['starttime']);
    $cnt = 0;

    foreach ($viewData['busType'] as $key1 => $bus) {
      $busNumber = $key1 + 1;
      foreach ($listLocation as $key2 => $value) {
        $viewData['busType'][$key1]['listLocation'][] = $value;

        // 시간대별 탑승자 보기
        $resData = $this->admin_model->listReserveLocation($viewData['rescode'], $busNumber, $value['short']);
        foreach ($resData as $people) {
          if (!empty($people['honor'])) {
            $cnt++;
            if ($cnt > 1) {
              $viewData['busType'][$key1]['listLocation'][$key2]['nickname'][] = $people['nickname'];
              $cnt = 0;
            }
          } else {
            $viewData['busType'][$key1]['listLocation'][$key2]['nickname'][] = $people['nickname'];
          }
        }
      }

      // 탑승위치 지정 없음
      $viewData['busType'][$key1]['listNoLocation'][] = $value;
      $resData = $this->admin_model->listReserveNoLocation($viewData['rescode'], $busNumber);
      foreach ($resData as $people) {
        if (!empty($people['honor'])) {
          $cnt++;
          if ($cnt > 1) {
            $viewData['busType'][$key1]['listNoLocation'][0]['nickname'][] = $people['nickname'];
            $cnt = 0;
          }
        } else {
          $viewData['busType'][$key1]['listNoLocation'][0]['nickname'][] = $people['nickname'];
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

    // 구매대행
    $viewData['search'] = array('notice_idx' => $viewData['rescode']);
    $viewData['maxPurchase'] = $this->shop_model->cntPurchase($viewData['search']);
    $viewData['listPurchase'] = $this->shop_model->listPurchase(NULL, $viewData['search']);

    foreach ($viewData['listPurchase'] as $key => $value) {
      $items = unserialize($value['items']);
      $viewData['listPurchase'][$key]['listCart'] = $items;

      $viewData['listPurchase'][$key]['totalCost'] = 0;
      foreach ($items as $item) {
        $viewData['listPurchase'][$key]['totalCost'] += $item['cost'] * $item['amount'];
      }
    }

    // 페이지 타이틀
    $viewData['pageTitle'] = '승차 관리';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'main_header';
    $viewData['headerMenuView'] = $this->load->view('admin/main_view_header', $viewData, true);

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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    // 클럽 정보
    $viewData['viewClub'] = $this->club_model->viewClub($viewData['clubIdx']);

    $idx = html_escape($idx);
    $viewData['view'] = $this->admin_model->viewEntry($idx);
    $viewData['view']['cntRes'] = cntRes($idx);

    // 정산 내역
    $viewData['viewAdjust'] = $this->admin_model->viewAdjust($idx);

    // 시간대별 버스 승차위치
    $viewData['location'] = arrLocation($viewData['viewClub']['club_geton'], $viewData['view']['starttime'], NULL, NULL, 1);

    // 산행예약 금액
    if ($viewData['view']['cost_total'] != 0) $viewData['view']['cost'] = $viewData['view']['cost_total'];

/*
    // 1인우등 수량
    $search['honor'] = 1;
    $viewData['view']['honor'] = $this->admin_model->cntReserve($search);

    // 우등버스 수량
    $cntHonorBus = 0;
    $busType = getBusType($viewData['view']['bustype'], $viewData['view']['bus']); // 버스 형태별 좌석 배치
    $reserveData = $this->admin_model->viewReserve($search); // 예약 목록

    foreach ($reserveData as $value) {
      if (!empty($busType[$value['bus']-1]['bus_type']) && $busType[$value['bus']-1]['bus_type'] == 1) {
        $cntHonorBus++;
      }
    }

    // 우등수량
    $viewData['view']['cntHonor'] = ($viewData['view']['honor']['cnt'] / 2) + $cntHonorBus;
*/
    // 평생회원 수량
    $search['club_idx'] = $viewData['clubIdx'];
    $search['rescode'] = $idx;
    $search['honor'] = NULL;
    $search['vip'] = 1;
    $viewData['view']['vip'] = $this->admin_model->cntReserve($search);

    // 포인트 수량
    $search['vip'] = NULL;
    $search['point'] = 1;
    $viewData['view']['point'] = $this->admin_model->cntReserve($search);

    // 포인트 금액
    $viewData['view']['point_cost'] = $this->admin_model->viewReservePoint($search);

    // 초기 합계 금액
    //$viewData['view']['total'] = (($viewData['view']['cntRes'] * $viewData['view']['cost']) + ($viewData['view']['cntHonor'] * 10000)) - (($viewData['view']['vip']['cnt'] * 5000) + $viewData['view']['point_cost']['total']);
    $viewData['view']['total'] = ($viewData['view']['cntRes'] * $viewData['view']['cost']) - (($viewData['view']['vip']['cnt'] * 5000) + $viewData['view']['point_cost']['total']);

    // 페이지 타이틀
    $viewData['pageTitle'] = '정산 관리';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'main_header';
    $viewData['headerMenuView'] = $this->load->view('admin/main_view_header', $viewData, true);

    $this->_viewPage('admin/main_view_adjust', $viewData);
  }

  /**
   * 진행중 산행 : 정산관리
   *
   * @return view
   * @author bjchoi
   **/
  public function main_view_adjust_update()
  {
    $inputDatas = array('rescode', 'total', 'memo');
    foreach (range(1, 31) as $key) {
      array_push($inputDatas, 'title' . $key, 'amount' . $key, 'cost' . $key, 'total' . $key, 'memo' . $key);
    }

    $updateValues = array();
    if (!empty($this->input->post())) {
      foreach($inputDatas as $key) {
        $inputData = $this->input->post($key);
        $updateValues[$key] = htmlspecialchars($inputData, ENT_QUOTES, "UTF-8");
      }
    }

    $viewAdjust = $this->admin_model->viewAdjust($updateValues['rescode']);

    if (empty($viewAdjust['idx'])) {
      // 기존 데이터가 없으면 등록
      $updateValues['regdate'] = time();
      $rtn = $this->admin_model->insertAdjust($updateValues);
    } else {
      // 기존 데이터가 있으면 수정
      $rtn = $this->admin_model->updateAdjust($updateValues, $updateValues['rescode']);
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
    } else {
      $result = array('error' => 0, 'message' => $this->lang->line('msg_save_complete'));
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 진행중 산행 : 문자양식
   *
   * @return view
   * @author bjchoi
   **/
  public function main_view_sms($idx)
  {
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    // 클럽 정보
    $clubData = $this->club_model->viewClub($viewData['clubIdx']);

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
      $viewData['list'][$key]['date'] = date('m/d', strtotime($value['startdate']));
      $viewData['list'][$key]['week'] = calcWeek($value['startdate']);
      $viewData['list'][$key]['dist'] = calcSchedule($value['schedule']);
      $viewData['list'][$key]['subject'] = $value['subject'];
      $viewData['list'][$key]['nickname'] = $value['nickname'];
      $viewData['list'][$key]['bus'] = $value['nowbus'];
      $viewData['list'][$key]['seat'] = $value['seat'];
      if (!empty($value['loc'])) {
        $location = arrLocation($clubData['club_geton'], $value['starttime'], $value['loc']);
        foreach ($location as $locationValue) {
          if ($locationValue['short'] == $value['loc']) {
            $viewData['list'][$key]['time'] = $locationValue['time'];
            $viewData['list'][$key]['title'] = $locationValue['title'];
          }
        }
      } else {
        $viewData['list'][$key]['time'] = '';
        $viewData['list'][$key]['title'] = '';
      }

      foreach ($busType as $cnt => $bus) {
        $busNo = $cnt + 1;
        if ($busNo == $value['nowbus']) {
          $viewData['list'][$key]['bus_name'] = $bus['bus_name'];
          if (!empty($bus['bus_color'])) $viewData['list'][$key]['bus_name'] .= '(' . $bus['bus_color'] . ')';
        }
      }
    }

    // 페이지 타이틀
    $viewData['pageTitle'] = '문자 관리';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'main_header';
    $viewData['headerMenuView'] = $this->load->view('admin/main_view_header', $viewData, true);

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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    $viewData['search']['subject'] = $this->input->get('subject') ? html_escape($this->input->get('subject')) : '';
    $viewData['search']['sdate'] = $this->input->get('sdate') ? html_escape($this->input->get('sdate')) : date('Y-m-01');
    $viewData['search']['edate'] = $this->input->get('edate') ? html_escape($this->input->get('edate')) : date('Y-m-t');
    $viewData['search']['syear'] = !empty($viewData['search']['sdate']) ? date('Y', strtotime($viewData['search']['sdate'])) : date('Y');
    $viewData['search']['smonth'] = !empty($viewData['search']['sdate']) ? date('m', strtotime($viewData['search']['sdate'])) : date('m');
    $viewData['search']['prev'] = 'sdate=' . date('Y-m-01', strtotime('-1 months', strtotime($viewData['search']['sdate']))) . '&edate=' . date('Y-m-t', strtotime('-1 months', strtotime($viewData['search']['sdate'])));
    $viewData['search']['next'] = 'sdate=' . date('Y-m-01', strtotime('+1 months', strtotime($viewData['search']['sdate']))) . '&edate=' . date('Y-m-t', strtotime('+1 months', strtotime($viewData['search']['sdate'])));
    $viewData['search']['status'] = array(STATUS_CLOSED);
    $viewData['search']['clubIdx'] = $viewData['clubIdx'];

    // 다녀온 산행
    $viewData['listClosed'] = $this->admin_model->listNoticeClosed($viewData['search']);

    // 페이지 타이틀
    $viewData['pageTitle'] = '다녀온 산행';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'main_header';

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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    $viewData['search']['subject'] = $this->input->get('subject') ? html_escape($this->input->get('subject')) : '';
    $viewData['search']['sdate'] = $this->input->get('sdate') ? html_escape($this->input->get('sdate')) : date('Y-m-01');
    $viewData['search']['edate'] = $this->input->get('edate') ? html_escape($this->input->get('edate')) : date('Y-m-t');
    $viewData['search']['syear'] = !empty($viewData['search']['sdate']) ? date('Y', strtotime($viewData['search']['sdate'])) : date('Y');
    $viewData['search']['smonth'] = !empty($viewData['search']['sdate']) ? date('m', strtotime($viewData['search']['sdate'])) : date('m');
    $viewData['search']['prev'] = 'sdate=' . date('Y-m-01', strtotime('-1 months', strtotime($viewData['search']['sdate']))) . '&edate=' . date('Y-m-t', strtotime('-1 months', strtotime($viewData['search']['sdate'])));
    $viewData['search']['next'] = 'sdate=' . date('Y-m-01', strtotime('+1 months', strtotime($viewData['search']['sdate']))) . '&edate=' . date('Y-m-t', strtotime('+1 months', strtotime($viewData['search']['sdate'])));
    $viewData['search']['status'] = array(STATUS_CANCEL);
    $viewData['search']['clubIdx'] = $viewData['clubIdx'];

    $viewData['listCancel'] = $this->admin_model->listNotice($viewData['search']);

    // 페이지 타이틀
    $viewData['pageTitle'] = '취소된 산행';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'main_header';

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
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $search['rescode'] = html_escape($this->input->post('idx'));
    $updateValues['status'] = html_escape($this->input->post('status'));

    if ($updateValues['status'] == STATUS_CLOSED) { // 종료 처리
      $viewEntry = $this->admin_model->viewEntry($search['rescode']);
      $viewReserve = $this->admin_model->viewReserveClosed($search['rescode']);

      foreach ($viewReserve as $value) {
        $search['idx'] = $value['user_idx'];
        $userData = $this->admin_model->viewMember($search);

        if ($userData['level'] != 2 && $userData['admin'] != 1) { // 무료회원과 관리자는 적립금 없음
          // 최초 1회는 자신의 레벨에 맞게 포인트 지급
          $memberLevel = memberLevel($userData['rescount'], $userData['penalty'], $userData['level'], $userData['admin']);
          $this->member_model->updatePoint($clubIdx, $userData['idx'], ($userData['point'] + $memberLevel['point']));
          setHistory($clubIdx, LOG_POINTUP, $search['rescode'], $userData['idx'], $userData['nickname'], $viewEntry['subject'] . ' 본인 예약 포인트', $now, $memberLevel['point']);

          // 같은 아이디로 추가 예약을 했을 경우 포인트 1000씩 지급 (1인우등은 제외)
          if (empty($value['honor'])) {
            $addedReserve = $this->admin_model->viewReserveClosedAdded($search['rescode'], $userData['idx']);
            if ($addedReserve['cnt'] > 1) {
              $addedPoint = ($addedReserve['cnt'] - 1) * 1000;
              $userData = $this->admin_model->viewMember($search); // 갱신된 정보를 다시 불러옴
              $this->member_model->updatePoint($clubIdx, $userData['idx'], ($userData['point'] + $addedPoint));
              setHistory($clubIdx, LOG_POINTUP, $search['rescode'], $userData['idx'], $userData['nickname'], $viewEntry['subject'] . ' 일행 예약 포인트', $now, $addedPoint);
            }
          }
        }
      }
    } elseif ($updateValues['status'] == STATUS_CANCEL) { // 취소 처리
      $viewEntry = $this->admin_model->viewEntry($search['rescode']);
      $viewReserve = $this->admin_model->viewReserve($search);

      foreach ($viewReserve as $value) {
        $search['idx'] = $value['user_idx'];
        $userData = $this->admin_model->viewMember($search);

        if ($value['status'] == RESERVE_PAY) {
          // 요금 합계 (기존 버젼 호환용)
          $viewEntry['cost'] = $viewEntry['cost_total'] == 0 ? $viewEntry['cost'] : $viewEntry['cost_total'];

          // 우등버스 할증 (2020/12/08 추가)
          $busType = getBusType($viewEntry['bustype'], $viewEntry['bus']);
          if (!empty($busType[$viewReserve['bus']-1]['bus_type']) && $busType[$viewReserve['bus']-1]['bus_type'] == 1) {
            $viewEntry['cost'] = $viewEntry['cost'] + 10000;
            $viewEntry['cost_total'] = $viewEntry['cost_total'] + 10000;
          }

          // 이미 입금을 마친 상태라면, 전액 포인트로 환불 (무료회원은 환불 안함)
          if (empty($userData['level']) || $userData['level'] != 2) {
            if ($userData['level'] == 1) {
              if ($value['honor'] > 0) {
                // 1인우등 좌석의 취소는 2개 좌석의 합을 반으로 나눠서
                $viewEntry['cost'] = ($viewEntry['cost'] + 5000) / 2;
                $this->member_model->updatePoint($clubIdx, $value['user_idx'], ($userData['point'] + $viewEntry['cost']));
              } else {
                // 평생회원은 할인 적용된 가격을 환불
                $viewEntry['cost'] = $viewEntry['cost'] - 5000;
                $this->member_model->updatePoint($clubIdx, $value['user_idx'], ($userData['point'] + $viewEntry['cost']));
              }
            } else {
              if ($value['honor'] > 0) {
                // 1인우등 좌석의 취소는 2개 좌석의 합을 반으로 나눠서
                $viewEntry['cost'] = ($viewEntry['cost'] + 10000) / 2;
                $this->member_model->updatePoint($clubIdx, $value['user_idx'], ($userData['point'] + $viewEntry['cost']));
              } else {
                $this->member_model->updatePoint($clubIdx, $value['user_idx'], ($userData['point'] + $viewEntry['cost']));
              }
            }
            // 포인트 반환 로그 기록
            setHistory($clubIdx, LOG_POINTUP, $value['rescode'], $value['user_idx'], $value['nickname'], $viewEntry['subject'] . ' 산행 취소', $now, $viewEntry['cost']);
          }
        } elseif ($value['status'] == RESERVE_ON && $value['point'] > 0) {
          // 예약정보에 포인트가 있을때 반환
          $this->member_model->updatePoint($clubIdx, $value['user_idx'], ($userData['point'] + $value['point']));

          // 포인트 반환 로그 기록
          setHistory($clubIdx, LOG_POINTUP, $value['rescode'], $value['user_idx'], $value['nickname'], $viewEntry['subject'] . ' 산행 취소', $now, $value['point']);
        }

        // 관리자 예약취소 기록
        setHistory($clubIdx, LOG_ADMIN_CANCEL, $value['rescode'], $value['user_idx'], $value['nickname'], $viewEntry['subject'] . ' 산행 취소', $now);

        // 예약 취소 로그 기록
        setHistory($clubIdx, LOG_CANCEL, $value['rescode'], $value['user_idx'], $value['nickname'], $viewEntry['subject'] . ' 산행 취소', $now);
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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

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
      $viewData['view']['type'] = '';
      $viewData['view']['mname'] = '';
      $viewData['view']['area_sido'] = '';
      $viewData['view']['area_gugun'] = '';
      $viewData['view']['weather'] = '';
      $viewData['view']['cctv'] = '';
      $viewData['view']['subject'] = '';
      $viewData['view']['content'] = '';
      $viewData['view']['kilometer'] = '';
      $viewData['view']['bustype'] = '';
      $viewData['view']['options'] = '';
      $viewData['view']['options_etc'] = '';
      $viewData['view']['article'] = '';
      $viewData['view']['information'] = '';
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

    if (!empty($viewData['view']['status']) && ($viewData['view']['status'] <= 9 && empty($viewData['view']['driving_fuel'][2]) && ENVIRONMENT != 'development')) {
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
    } else {
      if (!empty($viewData['view']['driving_fuel'][2])) {
        $viewData['costGas'] = $viewData['view']['driving_fuel'][2];
      }
    }

    // 페이지 타이틀
    $viewData['pageTitle'] = '신규 산행 등록';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'main_header';
    $viewData['headerMenuView'] = $this->load->view('admin/main_view_header', $viewData, true);

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
        'club_idx'        => html_escape($this->input->post('club_idx')),     // 지역 시/도
        'area_sido'       => make_serialize($this->input->post('area_sido')),     // 지역 시/도
        'area_gugun'      => make_serialize($this->input->post('area_gugun')),    // 지역 구/군
        'startdate'       => html_escape($this->input->post('startdate')),        // 출발일시
        'starttime'       => html_escape($this->input->post('starttime')),        // 출발시간
        'enddate'         => html_escape($this->input->post('enddate')),          // 도착일자
        'weather'         => html_escape($this->input->post('weather')),          // 날씨URL
        'cctv'            => html_escape($this->input->post('cctv')),             // CCTV URL
        'type'            => html_escape($this->input->post('type')),             // 행사유형
        'mname'           => html_escape($this->input->post('mname')),            // 산 이름
        'subject'         => html_escape($this->input->post('subject')),          // 산행제목
        'content'         => html_escape($this->input->post('content')),          // 산행코스
        'kilometer'       => html_escape($this->input->post('kilometer')),        // 정상까지의 거리 (km)
        'bustype'         => make_serialize($this->input->post('bustype')),       // 차량
        'bus_assist'      => make_serialize($this->input->post('bus_assist')),    // 차량별 보조석 닉네임
        'options'         => !empty($this->input->post('options')) ? make_serialize($this->input->post('options')) : NULL, // 옵션
        'options_etc'     => html_escape($this->input->post('options_etc')),      // 옵션 기타
        'article'         => html_escape($this->input->post('article')),          // 메모
        'information'     => html_escape($this->input->post('information')),      // 안내문
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
        'cost'            => html_escape($this->input->post('cost')),             // 기본비용
        'cost_added'      => html_escape($this->input->post('cost_added')),       // 추가비용
        'cost_total'      => html_escape($this->input->post('cost_total')),       // 합계
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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    if (is_null($idx)) exit; else $idx = html_escape($idx);

    // 산행 목록
    $viewData['listNotice'] = $this->admin_model->listNotice(NULL, 'desc');

    // 산행 정보 상세
    $viewData['view'] = $this->admin_model->viewEntry($idx);

    // 산행 공지사항
    $viewData['listNoticeDetail'] = $this->admin_model->listNoticeDetail($idx);

    // 페이지 타이틀
    $viewData['pageTitle'] = '공지사항 등록';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'main_header';
    $viewData['headerMenuView'] = $this->load->view('admin/main_view_header', $viewData, true);

    $this->_viewPage('admin/main_notice', $viewData);
  }

  /**
   * 공지사항 수정 처리
   *
   * @return json
   * @author bjchoi
   **/
  public function main_notice_update()
  {
    $now = time();
    $userIdx = html_escape($this->session->userData['idx']);
    $inputData = $this->input->post();
    $noticeIdx = html_escape($inputData['noticeIdx']);

    // 사진 처리
    if (!empty($_FILES['photo']['tmp_name'])) {
      $filename = time() . mt_rand(10000, 99999) . ".jpg";
      if (move_uploaded_file($_FILES['photo']['tmp_name'], PHOTO_PATH . $filename)) {
        // 사진 정보 입력
        $updateValues = array('photo' => $filename);
        $this->admin_model->updateEntry($updateValues, $noticeIdx);

        // 썸네일 만들기
        $this->image_lib->clear();
        $config['image_library'] = 'gd2';
        $config['source_image'] = PHOTO_PATH . $filename;
        $config['new_image'] = PHOTO_PATH . 'thumb_' . $filename;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['thumb_marker'] = '';
        $config['width'] = 500;
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
      }
    }

    if (!empty($noticeIdx)) {
      foreach ($inputData['title'] as $key => $value) {
        $idx = !empty($inputData['idx'][$key]) ? html_escape($inputData['idx'][$key]) : NULL;
        if (!empty($idx)) {
          // 수정
          $updateValues = array(
            'sort_idx' => $key + 1,
            'title' => $value,
            'content' => html_escape($inputData['content'][$key]),
            'updated_by' => $userIdx,
            'updated_at' => $now
          );
          $rtn = $this->admin_model->updateNoticeDetail($updateValues, $idx);
        } else {
          // 등록
          $insertValues = array(
            'notice_idx' => $noticeIdx,
            'sort_idx' => $key + 1,
            'title' => html_escape($value),
            'content' => html_escape($inputData['content'][$key]),
            'created_by' => $userIdx,
            'created_at' => $now
          );
          $rtn = $this->admin_model->insertNoticeDetail($insertValues);
        }
      }
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
    } else {
      $result = array('error' => 0, 'message' => $this->lang->line('msg_save_complete'));
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 공지사항 항목 삭제 처리
   *
   * @return json
   * @author bjchoi
   **/
  public function main_notice_item_delete()
  {
    $now = time();
    $userIdx = html_escape($this->session->userData['idx']);
    $idx = html_escape($this->input->post('idx'));
    $noticeIdx = html_escape($this->input->post('noticeIdx'));

    if (!empty($noticeIdx) && !empty($idx)) {
      // 삭제
      $listNoticeDetail = $this->admin_model->listNoticeDetail($noticeIdx);
      foreach ($listNoticeDetail as $value) {
        if ($value['idx'] == $idx) {
          $updateValues = array(
            'deleted_by' => $userIdx,
            'deleted_at' => $now
          );
          $rtn = $this->admin_model->updateNoticeDetail($updateValues, $value['idx']);
        }
      }
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
    } else {
      $result = array('error' => 0, 'message' => $this->lang->line('msg_save_complete'));
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 공지사항 대표 사진 삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function main_notice_photo_delete()
  {
    $idx = html_escape($this->input->post('idx'));
    $filename = html_escape($this->input->post('filename'));

    // 사진 파일 삭제
    if (!empty($idx) && !empty($filename)) {
      if (file_exists(PHOTO_PATH . $filename)) unlink(PHOTO_PATH . $filename);
      if (file_exists(PHOTO_PATH . 'thumb_' . $filename)) unlink(PHOTO_PATH . 'thumb_' . $filename);

      // 사진 정보 삭제
      $updateValues = array('photo' => NULL);
      $rtn = $this->admin_model->updateEntry($updateValues, $idx);
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
    } else {
      $result = array('error' => 0, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 산행 삭제
   *
   * @return json
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
   * 공지사항 보기
   *
   * @return view
   * @author bjchoi
   **/
  public function main_notice_view($idx=NULL)
  {
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    if (is_null($idx)) exit; else $noticeIdx = html_escape($idx);

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($viewData['clubIdx']);

    // 산행 공지
    $viewData['notice'] = $this->reserve_model->viewNotice($noticeIdx);

    // 산행 공지 상세
    $viewData['listNoticeDetail'] = $this->reserve_model->listNoticeDetail($noticeIdx);

    foreach ($viewData['listNoticeDetail'] as $key => $notice) {
      $viewData['listNoticeDetail'][$key]['content'] = reset_html_escape($viewData['listNoticeDetail'][$key]['content']);
      preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $viewData['listNoticeDetail'][$key]['content'], $matches);
      foreach ($matches[1] as $value) {
        if (file_exists(BASE_PATH . $value)) {
          $size = getImageSize(BASE_PATH . $value);
          $viewData['listNoticeDetail'][$key]['content'] = str_replace('src="' . $value, 'data-width="' . $size[0] . '" data-height="' . $size[1] . '" src="'. $value, $viewData['listNoticeDetail'][$key]['content']);
        }
      }
    }

    $this->load->view('admin/main_notice_view', $viewData);
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
    $postData = $this->input->post();
    $insertValues  = array(
      'club_idx'    => html_escape($postData['clubIdx']),
      'notice_idx'  => html_escape($postData['idx']),
      'nickname'    => html_escape($postData['nickname']),
      'location'    => html_escape($postData['location']),
      'gender'      => html_escape($postData['gender']),
      'memo'        => html_escape($postData['memo']),
      'created_by'  => html_escape($postData['userIdx']),
      'created_at'  => $now
    );

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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    $viewData['search']['syear'] = NULL;
    $viewData['search']['smonth'] = NULL;
    $viewData['search']['status'] = array(STATUS_PLAN);
    $viewData['search']['clubIdx'] = $viewData['clubIdx'];

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

    // 계획중 산행
    $search['status'] = array(STATUS_PLAN);
    $search['clubIdx'] = $viewData['clubIdx'];
    $viewData['listPlanned'] = $this->admin_model->listNotice($search);

    // 페이지 타이틀
    $viewData['pageTitle'] = '산행 계획 관리';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'main_header';

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

    // 페이지 타이틀
    $viewData['pageTitle'] = '지난 산행 보기';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'main_header';

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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    // 등록된 산행 목록
    $search['sdate'] = date('Y-m-01');
    $search['edate'] = date('Y-m-t', time() + (60 * 60 * 24 * 30 * 12));
    $search['status'] = array(STATUS_PLAN, STATUS_ABLE, STATUS_CONFIRM, STATUS_CANCEL, STATUS_CLOSED);
    $search['clubIdx'] = $viewData['clubIdx'];
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

    // 페이지 타이틀
    $viewData['pageTitle'] = '산행 일정 복사';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'main_header';

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
    // 클럽ID
    $viewData['clubIdx'] = $viewData['search']['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    // 클럽 정보
    $viewData['viewClub'] = $this->club_model->viewClub($viewData['clubIdx']);

    $viewData['search']['keyword'] = html_escape($this->input->post('keyword'));
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
        case '8': // 드라이버
          $viewData['search']['level'] = 3;
          break;
        case '9': // 드라이버 관리자
          $viewData['search']['level'] = 4;
          break;
        case '10': // 관리자
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

    // 페이지 타이틀
    $viewData['pageTitle'] = '회원관리';

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('admin/member_list_append', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      $viewData['listMembers'] = $this->load->view('admin/member_list_append', $viewData, true);
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
    // 클럽ID
    $nowDate = time();
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    // 클럽 정보
    $viewData['viewClub'] = $this->club_model->viewClub($viewData['clubIdx']);

    $keyword = html_escape(urldecode($keyword));
    $search = array('idx' => $keyword);
    $viewData['viewMember'] = $this->admin_model->viewMember($search);

    if (empty($viewData['viewMember'])) {
      $search = array('userid' => $keyword);
      $viewData['viewMember'] = $this->admin_model->viewMember($search);
    }

    $viewData['viewMember']['birthday'] = explode('/', $viewData['viewMember']['birthday']);
    $viewData['viewMember']['phone'] = explode('-', $viewData['viewMember']['phone']);
    $viewData['viewMember']['memberLevel'] = memberLevel($viewData['viewMember']['rescount'], $viewData['viewMember']['penalty'], $viewData['viewMember']['level'], $viewData['viewMember']['admin']);

    // 거주지역 나누기
    $buf = unserialize($viewData['viewMember']['area']);
    $viewData['viewMember']['sido'] = $buf[0];
    $viewData['viewMember']['gugun'] = $buf[1];
    $viewData['viewMember']['dong'] = $buf[2];

    // 지역
    $viewData['area_sido'] = $this->area_model->listSido();
    $viewData['area_gugun'] = array();

    if (!empty($viewData['viewMember']['sido'])) {
      $viewData['area_gugun'] = $this->area_model->listGugun($viewData['viewMember']['sido']);
    }

    // 예약 내역
    $paging['perPage'] = 5; $paging['nowPage'] = 0;
    $viewData['userReserve'] = $this->reserve_model->userReserve($viewData['clubIdx'], $viewData['viewMember']['idx'], NULL, $paging);

    foreach ($viewData['userReserve'] as $key => $value) {
      if (empty($value['cost_total'])) {
        $value['cost_total'] = $value['cost'];
      }
      if ($viewData['viewMember']['level'] == LEVEL_LIFETIME) {
        // 평생회원 할인
        $viewData['userReserve'][$key]['view_cost'] = '<s class="text-secondary">' . number_format($value['cost_total']) . '원</s> → ' . number_format($value['cost_total'] - 5000) . '원';
        $viewData['userReserve'][$key]['real_cost'] = $value['cost_total'] - 5000;
      } elseif ($viewData['viewMember']['level'] == LEVEL_FREE) {
        // 무료회원 할인
        $viewData['userReserve'][$key]['view_cost'] = '<s class="text-secondary">' . number_format($value['cost_total']) . '원</s> → ' . '0원';
        $viewData['userReserve'][$key]['real_cost'] = 0;
      } elseif ($value['honor'] > 1) {
        // 1인우등 할인
        if ($key%2 == 0) {
          $honorCost = 10000;
          $viewData['userReserve'][$key]['view_cost'] = '<s class="text-secondary">' . number_format($value['cost_total']) . '원</s> → ' . number_format($honorCost) . '원';
          $viewData['userReserve'][$key]['real_cost'] = $honorCost;
        } else {
          $viewData['userReserve'][$key]['view_cost'] = number_format($value['cost_total']) . '원';
          $viewData['userReserve'][$key]['real_cost'] = $value['cost_total'];
        }
      } else {
        $viewData['userReserve'][$key]['view_cost'] = number_format($value['cost_total']) . '원';
        $viewData['userReserve'][$key]['real_cost'] = $value['cost_total'];
      }
      // 페널티 체크
      $startTime = explode(':', $value['starttime']);
      $startDate = explode('-', $value['startdate']);
      $limitDate = mktime($startTime[0], $startTime[1], 0, $startDate[1], $startDate[2], $startDate[0]);

      // 예약 페널티
      if ( $limitDate < ($nowDate + 86400) ) {
        // 1일전 취소시 3점 페널티
        $viewData['userReserve'][$key]['penalty'] = 3;
      } elseif ( $limitDate < ($nowDate + 172800) ) {
        // 2일전 취소시 1점 페널티
        $viewData['userReserve'][$key]['penalty'] = 1;
      }
    }

    // 예약 취소 내역 (로그)
    $viewData['userReserveCancel'] = $this->reserve_model->userReserveCancel($viewData['clubIdx'], $viewData['viewMember']['idx']);

    // 산행 내역
    $viewData['userVisit'] = $this->reserve_model->userVisit($viewData['clubIdx'], $viewData['viewMember']['idx']);

    // 산행 횟수
    $viewData['userVisitCount'] = $this->reserve_model->userVisitCount($viewData['clubIdx'], $viewData['viewMember']['idx']);

    // 포인트 내역
    $viewData['userPoint'] = $this->member_model->userPointLog($viewData['clubIdx'], $viewData['viewMember']['idx']);

    // 페널티 내역
    $viewData['userPenalty'] = $this->member_model->userPenaltyLog($viewData['clubIdx'], $viewData['viewMember']['idx']);

    // 페이지 타이틀
    $viewData['pageTitle'] = '회원관리';

    $this->_viewPage('admin/member_view', $viewData);
  }

  /**
   * 회원 정보 간략 보기
   *
   * @return view
   * @author bjchoi
   **/
  public function member_view_modal()
  {
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    $search = array('idx' => html_escape($this->input->post('userIdx')));
    $viewMember = $this->admin_model->viewMember($search);
    $viewMember['memberLevel'] = memberLevel($viewMember['rescount'], $viewMember['penalty'], $viewMember['level'], $viewMember['admin']);
    if ($viewMember['gender'] == 'M') $viewMember['gender'] = '남성'; else $viewMember['gender'] = '여성';
    $result = array('error' => 1, 'message' => $this->lang->line('error_all'));

    if (!empty($viewMember)) {
      $html = '<div class="mb-3 pl-4 pr-4">';
      $html .= '<div class="row text-left border-top border-left border-right"><div class="col-3 p-2 bg-secondary text-white">실명</div><div class="col-9 p-2">' . $viewMember['realname'] . '</div></div>';
      $html .= '<div class="row text-left border-top border-left border-right"><div class="col-3 p-2 bg-secondary text-white">별명</div><div class="col-9 p-2">' . $viewMember['nickname'] . '</div></div>';
      $html .= '<div class="row text-left border-top border-left border-right"><div class="col-3 p-2 bg-secondary text-white">성별</div><div class="col-9 p-2">' . $viewMember['gender'] . '</div></div>';
      $html .= '<div class="row text-left border-top border-left border-right"><div class="col-3 p-2 bg-secondary text-white">생년월일</div><div class="col-9 p-2">' . $viewMember['birthday'] . '</div></div>';
      $html .= '<div class="row text-left border-top border-left border-right"><div class="col-3 p-2 bg-secondary text-white">전화번호</div><div class="col-9 p-2">' . $viewMember['phone'] . '</div></div>';
      $html .= '<div class="row text-left border-top border-left border-right border-bottom"><div class="col-3 p-2 bg-secondary text-white">레벨</div><div class="col-9 p-2">' . $viewMember['memberLevel']['levelName'] . '</div></div>';
      $html .= '</div><div class="text-center"><a href="' . BASE_URL . '/admin/member_view/' . $viewMember['idx'] . '"><button class="btn btn-default">상세보기</button></a></div>';
      $result = array('error' => 0, 'message' => $html);
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 회원 정보 더보기
   *
   * @return view
   * @author bjchoi
   **/
  public function member_more()
  {
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    $userIdx = html_escape($this->input->post('userIdx')); // 회원 아이디
    $type = html_escape($this->input->post('type')); // 더보기 타입 (포인트 : point, 페널티 : penalty)

    // 페이징
    $result['page'] = html_escape($this->input->post('page'));
    if (empty($result['page'])) $result['page'] = 1; else $result['page']++;
    $paging['perPage'] = $viewData['perPage'] = 5;
    $paging['nowPage'] = ($result['page'] * $paging['perPage']) - $paging['perPage'];
    $result['html'] = '';

    switch ($type) {
      case 'point':
        // 포인트 내역
        $arr = $this->member_model->userPointLog($viewData['clubIdx'], $userIdx, $paging);
        foreach ($arr as $value) {
          $result['html'] .= '<div class="border-top pt-2 pb-2 row align-items-center"><div class="col-10 p-0">';
          if ($value['action'] == LOG_POINTUP) {
            $result['html'] .= '<strong class="text-primary">' . number_format($value['point']) . ' 포인트 추가</strong> - ' . $value['subject'];
          } elseif ($value['action'] == LOG_POINTDN) {
            $result['html'] .= '<strong class="text-danger">' . number_format($value['point']) . ' 포인트 감소</strong> - ' . $value['subject'];
          }
          $result['html'] .= '<br><small>' . date('Y-m-d, H:i:s', $value['regdate']) . '</small></div><div class="col-2 text-right p-0">';
          $result['html'] .= '<button type="button" data-idx="' . $value['idx'] . '" class="btn btn-sm btn-default btn-history-delete-modal">삭제</button></div></div>';
        }
        break;
      case 'penalty':
        // 페널티 내역
        $arr = $this->member_model->userPenaltyLog($viewData['clubIdx'], $userIdx, $paging);
        foreach ($arr as $value) {
          $result['html'] .= '<div class="border-top pt-2 pb-2 row align-items-center"><div class="col-10 p-0">';
          if ($value['action'] == LOG_PENALTYUP) {
            $result['html'] .= '<strong class="text-primary">' . number_format($value['point']) . ' 페널티 추가</strong> - ' . $value['subject'];
          } elseif ($value['action'] == LOG_PENALTYDN) {
            $result['html'] .= '<strong class="text-danger">' . number_format($value['point']) . ' 페널티 감소</strong> - ' . $value['subject'];
          }
          $result['html'] .= '<br><small>' . date('Y-m-d, H:i:s', $value['regdate']) . '</small></div><div class="col-2 text-right p-0">';
          $result['html'] .= '<button type="button" data-idx="' . $value['idx'] . '" class="btn btn-sm btn-default btn-history-delete-modal">삭제</button></div></div>';
        }
        break;
    }

    $this->output->set_output(json_encode($result));
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

    if (!empty($inputData['sido']) && !empty($inputData['gugun']) && !empty($inputData['dong'])) {
      $area['sido'] = $inputData['sido'];
      $area['gugun'] = $inputData['gugun'];
      $area['dong'] = $inputData['dong'];
      $inputData['area'] = make_serialize($area);
    } else {
      $inputData['area'] = NULL;
    }

    $updateValues = array(
      'nickname'      => html_escape($inputData['nickname']),
      'realname'      => html_escape($inputData['realname']),
      'personal_code' => html_escape($inputData['personal_code']),
      'gender'        => html_escape($inputData['gender']),
      'birthday'      => html_escape($inputData['birthday_year']) . '/' . html_escape($inputData['birthday_month']) . '/' . html_escape($inputData['birthday_day']),
      'birthday_type' => html_escape($inputData['birthday_type']),
      'phone'         => html_escape($inputData['phone1']) . '-' . html_escape($inputData['phone2']) . '-' . html_escape($inputData['phone3']),
      'location'      => html_escape($inputData['location']),
      'area'          => $inputData['area'],
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
    // 클럽ID
    $clubIdx = get_cookie('COOKIE_CLUBIDX');

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
        setHistory($clubIdx, LOG_POINTUP, $search['idx'], $viewMember['idx'], $viewMember['nickname'], $subject, $now, $point);
        break;
      case 2: // 포인트 감소
        $updateValues['point'] = $viewMember['point'] - $point;
        setHistory($clubIdx, LOG_POINTDN, $search['idx'], $viewMember['idx'], $viewMember['nickname'], $subject, $now, $point);
        break;
      case 3: // 페널티 추가
        $updateValues['penalty'] = $viewMember['penalty'] + $penalty;
        setHistory($clubIdx, LOG_PENALTYUP, $search['idx'], $viewMember['idx'], $viewMember['nickname'], $subject, $now, $penalty);
        break;
      case 4: // 페널티 감소
        $updateValues['penalty'] = $viewMember['penalty'] - $penalty;
        setHistory($clubIdx, LOG_PENALTYDN, $search['idx'], $viewMember['idx'], $viewMember['nickname'], $subject, $now, $penalty);
        break;
    }

    $result = $this->admin_model->updateMember($updateValues, $search['idx']);

    $this->output->set_output(json_encode($result));
  }

  /**
   * 기록 내역 삭제
   *
   * @return view
   * @author bjchoi
   **/
  public function member_delete_history()
  {
    $idx = html_escape($this->input->post('idx'));
    $result = array('error' => 1, 'message' => $this->lang->line('error_all'));

    if (!empty($idx)) {
      $rtn = $this->admin_model->deleteHistory($idx);

      if (!empty($rtn)) {
        $result = array('error' => 0, 'message' => '');
      }
    }

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
    // 클럽ID
    $viewData['clubIdx'] = $viewData['search']['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    // 클럽 정보
    $viewData['viewClub'] = $this->club_model->viewClub($viewData['clubIdx']);

    $this->_viewPage('admin/member_entry', $viewData);
  }

  /**
   * 회원 등록 처리
   *
   * @return json
   * @author bjchoi
   **/
  public function member_insert()
  {
    $now = time();
    $inputData = $this->input->post();
    $clubIdx = html_escape($inputData['clubIdx']);
    $nickname = html_escape($inputData['nickname']);

    $insertValues = array(
      'club_idx'      => $clubIdx,
      'nickname'      => $nickname,
      'realname'      => html_escape($inputData['realname']),
      'phone'         => html_escape($inputData['phone1']) . '-' . html_escape($inputData['phone2']) . '-' . html_escape($inputData['phone3']),
      'birthday'      => html_escape($inputData['birthday_year']) . '/' . html_escape($inputData['birthday_month']) . '/' . html_escape($inputData['birthday_day']),
      'birthday_type' => html_escape($inputData['birthday_type']),
      'gender'        => html_escape($inputData['gender']),
      'location'      => !empty($inputData['location']) ? html_escape($inputData['location']) : NULL,
      'connect'       => html_escape($inputData['connect']),
      'rescount'      => html_escape($inputData['rescount']),
      'point'         => html_escape($inputData['point']),
      'penalty'       => html_escape($inputData['penalty']),
      'level'         => !empty($inputData['level']) ? html_escape($inputData['level']) : 0,
      'admin'         => !empty($inputData['admin']) ? html_escape($inputData['admin']) : 0,
      'regdate'       => $now
    );

    $idx = $this->member_model->insertMember($insertValues);

    if (empty($idx)) {
      $result = array('error' => 1, 'message' => '등록에 실패했습니다.');
    } else {
      $result = array('error' => 0, 'message' => '');
/*
      // 사진 등록
      if (!empty($inputData['filename']) && file_exists(UPLOAD_PATH . $inputData['filename'])) {
        // 파일 이동
        rename(UPLOAD_PATH . html_escape($inputData['filename']), PHOTO_PATH . $idx);
      }
*/
      // 회원 가입 기록
      setHistory($clubIdx, LOG_ENTRY, $idx, $idx, $nickname, '', $now);
    }

    $this->output->set_output(json_encode($result));
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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');
    $idx = !empty($this->input->get('n')) ? html_escape($this->input->get('n')) : NULL;

    $search['clubIdx'] = $viewData['clubIdx'];
    $search['status'] = array(STATUS_CLOSED);
    $search['sdate'] = '2019-04-06';
    $search['edate'] = date('Y-m-d');
    $viewData['listAttendanceNotice'] = $this->admin_model->listNotice($search, 'desc');

    if (!empty($idx)) {
      $viewData['viewAuth'] = $this->admin_model->viewAuth($idx);
    }

    // 백산백소 목록
    $viewData['listAuth'] = $this->admin_model->listAuth();

    // 페이지 타이틀
    $viewData['pageTitle'] = '백산백소 인증';

    $this->_viewPage('admin/attendance_auth', $viewData);
  }

  /**
   * 인증현황 등록/수정
   *
   * @return view
   * @author bjchoi
   **/
  public function attendance_auth_update()
  {
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');
    $inputData = $this->input->post();
    $idx = !empty($this->input->post('idx')) ? html_escape($this->input->post('idx')) : NULL;

    if (!empty($idx)) {
      $updateValues = array(
        'rescode' => html_escape($inputData['rescode']),
        'userid' => html_escape($inputData['userid']),
        'nickname' => html_escape($inputData['nickname']),
        'photo' => html_escape($inputData['photo']),
        'title' => html_escape($inputData['title']),
      );
      $rtn = $this->admin_model->updateAttendanceAuth($updateValues, $idx);
      $message = $this->lang->line('msg_update_complete');
    } else {
      $insertValues = array(
        'rescode' => html_escape($inputData['rescode']),
        'userid' => html_escape($inputData['userid']),
        'nickname' => html_escape($inputData['nickname']),
        'photo' => html_escape($inputData['photo']),
        'title' => html_escape($inputData['title']),
        'regdate' => time()
      );
      $rtn = $this->admin_model->insertAttendanceAuth($insertValues);
      $message = $this->lang->line('msg_insert_complete');
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_insert'));
    } else {
      $result = array('error' => 0, 'message' => $message);
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 인증현황 삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function attendance_auth_delete()
  {
    $idx = !empty($this->input->post('idx')) ? html_escape($this->input->post('idx')) : NULL;

    if (empty($idx)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_delete'));
    } else {
      $this->admin_model->deleteAttendanceAuth($idx);
      $result = array('error' => 0, 'message' => '');
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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    $viewData['keyword'] = !empty($this->input->post('k')) ? html_escape($this->input->post('k')) : NULL;
    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;
    $paging['perPage'] = 20;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];

    // 페이지 타이틀
    $viewData['pageTitle'] = '회원 활동기록';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'log_header';

    // 예약기록 불러오기
    $viewData['listReserve'] = $this->admin_model->listReserve($paging, $viewData);

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('admin/log_reserve_append', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      // 1페이지에는 View 페이지로 전송
      $viewData['listReserve'] = $this->load->view('admin/log_reserve_append', $viewData, true);
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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    $action = !empty($this->input->get('action')) ? html_escape($this->input->get('action')) : html_escape($this->input->post('action'));
    $viewData['subject'] = !empty($this->input->get('subject')) ? html_escape($this->input->get('subject')) : html_escape($this->input->post('subject'));
    $viewData['nickname'] = !empty($this->input->get('nickname')) ? html_escape($this->input->get('nickname')) : html_escape($this->input->post('nickname'));
    $viewData['status'] = !empty($this->input->get('status')) ? html_escape($this->input->get('status')) : !empty($this->input->post('status')) ? html_escape($this->input->post('status')) : 0;
    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;

    $paging['perPage'] = $viewData['perPage'] = 30;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];

    if (!empty($action)) {
      $viewData['action'] = array($action);
      $viewData['listHistory'] = $this->admin_model->listHistory($paging, $viewData);
    } else {
      $viewData['action'] = array(LOG_ENTRY, LOG_RESERVE, LOG_CANCEL, LOG_POINTUP, LOG_POINTDN, LOG_PENALTYUP, LOG_PENALTYDN, LOG_ADMIN_RESERVE, LOG_ADMIN_CANCEL, LOG_ADMIN_DEPOSIT_CONFIRM, LOG_ADMIN_DEPOSIT_CANCEL, LOG_ADMIN_REFUND);
      $viewData['listHistory'] = $this->admin_model->listHistory($paging, $viewData);
      $viewData['action'][0] = ''; // 액션 초기화
    }

    $viewData['maxLog'] = $this->admin_model->cntHistory($viewData);
    $viewData['pageType'] = 'log';
    $viewData['pageUrl'] = BASE_URL . '/admin/log_user';

    // 페이지 타이틀
    $viewData['pageTitle'] = '활동기록';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'log_header';

    foreach ($viewData['listHistory'] as $key => $value) {
      if (!empty($value['user_idx'])) {
        $search_member['idx'] = $value['user_idx'];
        $viewData['listHistory'][$key]['userData'] = $this->admin_model->viewMember($search_member);
      } else {
        $viewData['listHistory'][$key]['userData']['nickname'] = $value['nickname'];
      }

      switch ($value['action']) {
        case LOG_ENTRY: // 회원등록
          $viewData['listHistory'][$key]['header'] = '[회원등록]';
          $viewData['listHistory'][$key]['subject'] = $viewData['listHistory'][$key]['userData']['userid'];
          break;
        case LOG_RESERVE: // 예약
          $viewData['listHistory'][$key]['header'] = '<span class="text-primary">[예약완료]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'];
          break;
        case LOG_CANCEL: // 예약취소
          $viewData['listHistory'][$key]['header'] = '<span class="text-danger">[예약취소]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'];
          break;
        case LOG_POINTUP: // 포인트 적립
          $viewData['listHistory'][$key]['header'] = '<span class="text-info">[포인트적립]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'] . ' - ' . number_format($value['point']) . ' 포인트적립';
          break;
        case LOG_POINTDN: // 포인트 감소
          $viewData['listHistory'][$key]['header'] = '<span class="text-warning">[포인트감소]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'] . ' - ' . number_format($value['point']) . ' 포인트감소';
          break;
        case LOG_PENALTYUP: // 페널티 추가
          $viewData['listHistory'][$key]['header'] = '<span class="text-warning">[페널티추가]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'] . ' - ' . number_format($value['point']) . ' 페널티추가';
          break;
        case LOG_PENALTYDN: // 페널티 감소
          $viewData['listHistory'][$key]['header'] = '<span class="text-info">[페널티감소]</span>';
          $viewData['listHistory'][$key]['subject'] = $value['subject'] . ' - ' . number_format($value['point']) . ' 페널티감소';
          break;
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
        case LOG_ADMIN_REFUND: // 비회원 환불기록
          $viewData['listHistory'][$key]['userData']['nickname'] = $value['nickname'];
          $viewEntry = $this->admin_model->viewEntry($value['fkey']);
          $viewData['listHistory'][$key]['header'] = '<span class="text-danger">[비회원환불기록]</span>';
          $viewData['listHistory'][$key]['subject'] = number_format($viewEntry['cost_total']) . '원<br>' . $value['subject'];
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
   * 활동관리 - 버스 변경기록
   *
   * @return view
   * @return json
   * @author bjchoi
   **/
  public function log_bus()
  {
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    $action = !empty($this->input->get('action')) ? html_escape($this->input->get('action')) : html_escape($this->input->post('action'));
    $viewData['subject'] = !empty($this->input->get('subject')) ? html_escape($this->input->get('subject')) : html_escape($this->input->post('subject'));
    $viewData['nickname'] = !empty($this->input->get('nickname')) ? html_escape($this->input->get('nickname')) : html_escape($this->input->post('nickname'));
    $viewData['status'] = !empty($this->input->get('status')) ? html_escape($this->input->get('status')) : !empty($this->input->post('status')) ? html_escape($this->input->post('status')) : 0;
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
    $viewData['pageUrl'] = BASE_URL . '/admin/log_bus';

    // 페이지 타이틀
    $viewData['pageTitle'] = '차량 변경기록';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'log_header';

    foreach ($viewData['listHistory'] as $key => $value) {
      $viewEntry = $this->admin_model->viewEntry($value['fkey']);

      switch ($value['action']) {
        case LOG_DRIVER_CHANGE: // 차량 변경
          $viewData['listHistory'][$key]['header'] = '<span class="text-danger">[차량변경]</span>';
          $viewData['listHistory'][$key]['subject'] = '<a href="' . BASE_URL . '/admin/main_view_progress/' . $value['fkey'] . '">' . $viewEntry['subject'] . '</a> - ' . $value['subject'];
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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    $action = !empty($this->input->get('action')) ? html_escape($this->input->get('action')) : html_escape($this->input->post('action'));
    $viewData['subject'] = !empty($this->input->get('subject')) ? html_escape($this->input->get('subject')) : html_escape($this->input->post('subject'));
    $viewData['nickname'] = !empty($this->input->get('nickname')) ? html_escape($this->input->get('nickname')) : html_escape($this->input->post('nickname'));
    $viewData['status'] = !empty($this->input->get('status')) ? html_escape($this->input->get('status')) : !empty($this->input->post('status')) ? html_escape($this->input->post('status')) : 0;
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
    $viewData['pageUrl'] = BASE_URL . '/admin/log_buy';

    // 페이지 타이틀
    $viewData['pageTitle'] = '구매기록';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'log_header';

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
   * 활동관리 - 클럽 댓글
   *
   * @return view
   * @author bjchoi
   **/
  public function log_reply()
  {
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;
    $paging['perPage'] = 30;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];

    $viewData['cntReply'] = $this->admin_model->cntReply($viewData['clubIdx']);
    $viewData['listReply'] = $this->admin_model->listReply($viewData['clubIdx'], $paging);

    // 페이지 타이틀
    $viewData['pageTitle'] = '댓글기록';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'log_header';

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('admin/log_reply_append', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      $viewData['listReply'] = $this->load->view('admin/log_reply_append', $viewData, true);
      // 1페이지에는 View 페이지로 전송
      $this->_viewPage('admin/log_reply', $viewData);
    }
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
    $this->admin_model->updateReply($updateValues, $idx);

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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    $viewData['keyword']      = $this->input->get('keyword') ? html_escape($this->input->get('keyword')) : '';
    $viewData['nowdate']      = $this->input->get('nowdate') ? html_escape($this->input->get('nowdate')) : date('Ymd');
    $viewData['searchYear']   = date('Y', strtotime($viewData['nowdate']));
    $viewData['searchMonth']  = date('m', strtotime($viewData['nowdate']));
    $viewData['searchDay']    = date('d', strtotime($viewData['nowdate']));
    $viewData['searchPrev']   = date('Ymd', strtotime('-1 day', strtotime($viewData['nowdate'])));
    $viewData['searchNext']   = date('Ymd', strtotime('+1 day', strtotime($viewData['nowdate'])));

    // 방문자
    $viewData['listVisitor'] = $this->admin_model->listVisitor($viewData);

    // 페이지 타이틀
    $viewData['pageTitle'] = '방문기록';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'log_header';

    $this->_viewPage('admin/log_visitor', $viewData);
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
   * 활동관리 - 댓글 확인 체크
   *
   * @return json
   * @author bjchoi
   **/
  public function reply_check()
  {
    $idx = html_escape($this->input->post('idx'));
    $viewReply = $this->admin_model->viewReply($idx);

    if ($viewReply['visible'] == 'Y') $visible = 'N'; else $visible = 'Y';

    $updateValues = array('visible' => $visible);
    $rtn = $this->admin_model->updateReply($updateValues, $idx);

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
    } else {
      $result = array('error' => 0, 'message' => $visible);
    }

    $this->output->set_output(json_encode($result));
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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($viewData['clubIdx']);
    $viewData['view']['club_type'] = is_null($viewData['view']['club_type']) || strlen($viewData['view']['club_type']) <= 3 ? array() : unserialize($viewData['view']['club_type']);
    $viewData['view']['club_option'] = is_null($viewData['view']['club_option']) || strlen($viewData['view']['club_option']) <= 3 ? array() : unserialize($viewData['view']['club_option']);
    $viewData['view']['club_cycle'] = is_null($viewData['view']['club_cycle']) || strlen($viewData['view']['club_cycle']) <= 3 ? array() : unserialize($viewData['view']['club_cycle']);
    $viewData['view']['club_week'] = is_null($viewData['view']['club_week']) || strlen($viewData['view']['club_week']) <= 3 ? array() : unserialize($viewData['view']['club_week']);
    $viewData['view']['club_geton'] = is_null($viewData['view']['club_geton']) || strlen($viewData['view']['club_geton']) <= 3 ? array() : unserialize($viewData['view']['club_geton']);
    $viewData['view']['club_getoff'] = is_null($viewData['view']['club_getoff']) || strlen($viewData['view']['club_getoff']) <= 3 ? array() : unserialize($viewData['view']['club_getoff']);
    $files = $this->file_model->getFile('club', $viewData['clubIdx']);

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
        if (!empty($area_gugun[$key])) {
          $gugun = $this->area_model->getName($area_gugun[$key]);
        }

        $viewData['list_sido'] = $this->area_model->listSido();
        $viewData['list_gugun'][$key] = $this->area_model->listGugun($value);
        $viewData['view']['sido'][$key] = $sido['name'];
        if (!empty($gugun)) {
          $viewData['view']['gugun'][$key] = $gugun['name'];
        }
      }

      $viewData['area_gugun'] = $this->area_model->listGugun($viewData['view']['area_sido']);
    } else {
      $viewData['area_gugun'] = array();
    }

    // 페이지 타이틀
    $viewData['pageTitle'] = '기본정보 수정';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'setup_header';

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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    $now = time();
    $page = 'club';
    $userIdx = $this->session->userData['idx'];
    $inputData = $this->input->post();
    $file = html_escape($inputData['file']);

    if (!empty($inputData['geton_short'])) {
      foreach ($inputData['geton_short'] as $key => $value) {
        $geton[] = html_escape($value) . '|' . html_escape($inputData['geton_title'][$key]) . '|' . html_escape($inputData['geton_time'][$key]);
      }
    }
    if (!empty($inputData['getoff_short'])) {
      foreach ($inputData['getoff_short'] as $key => $value) {
        $getoff[] = html_escape($value) . '|' . html_escape($inputData['getoff_title'][$key]) . '|' . html_escape($inputData['getoff_time'][$key]);
      }
    }

    $updateValues = array(
      'title'             => html_escape($inputData['title']),
      'area_sido'         => make_serialize($inputData['area_sido']),
      'area_gugun'        => make_serialize($inputData['area_gugun']),
      'homepage'          => html_escape($inputData['homepage']),
      'phone'             => html_escape($inputData['phone']),
      'establish'         => html_escape($inputData['establish']),
      'club_type'         => !empty($inputData['club_type']) ? make_serialize($inputData['club_type']) : NULL,
      'club_option'       => !empty($inputData['club_option']) ? make_serialize($inputData['club_option']) : NULL,
      'club_option_text'  => html_escape($inputData['club_option_text']),
      'club_cycle'        => !empty($inputData['club_cycle']) ? make_serialize($inputData['club_cycle']) : NULL,
      'club_week'         => !empty($inputData['club_week']) ? make_serialize($inputData['club_week']) : NULL,
      'club_geton'        => !empty($geton) ? serialize($geton) : NULL,
      'club_getoff'       => !empty($getoff) ? serialize($getoff) : NULL,
      'updated_by'        => $userIdx,
      'updated_at'        => $now
    );

    $rtn = $this->club_model->updateClub($updateValues, $viewData['clubIdx']);

    // 로고 파일 등록
    if (!empty($file)) {
      // 기존 로고 파일이 있는 경우 삭제
      $files = $this->file_model->getFile($page, $viewData['clubIdx']);
      foreach ($files as $value) {
        $this->file_model->deleteFile($value['filename']);
        if (file_exists(PHOTO_PATH . $value['filename'])) unlink(PHOTO_PATH . $value['filename']);
      }

      // 업로드 된 로고 파일이 있을 경우에만 등록 후 이동
      if (file_exists(UPLOAD_PATH . $file)) {
        $file_values = array(
          'page' => $page,
          'page_idx' => $viewData['clubIdx'],
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

    $result = array('error' => 0, 'message' => '');
    $this->output->set_output(json_encode($result));
  }

  /**
   * 설정 - 메인 페이지 슬라이더 그림 변경
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_topimage()
  {
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($viewData['clubIdx']);
    $viewData['arrTopImage'] = unserialize($viewData['view']['topimage']);

    // 페이지 타이틀
    $viewData['pageTitle'] = '대표사진';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'setup_header';

    $this->_viewPage('admin/setup_topimage', $viewData);
  }

  /**
   * 설정 - 메인 페이지 슬라이더 그림 변경 파일 업로드
   *
   * @return redirect
   * @author bjchoi
   **/
  public function setup_topimage_upload()
  {
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewclub($viewData['clubIdx']);

    if (!empty($_FILES['file']['tmp_name']) && $_FILES['file']['type'] == 'image/jpeg') {
      $filename = time() . mt_rand(10000, 99999) . ".jpg";

      if (move_uploaded_file($_FILES['file']['tmp_name'], FRONT_PATH . $filename)) {
        if (!empty($viewData['view']['topimage'])) {
          $arrTopImage = unserialize($viewData['view']['topimage']);
          array_push($arrTopImage, $filename);
          $arrResult = serialize($arrTopImage);
        } else {
          $arrResult = serialize(array($filename));
        }

        $updateValues = array('topimage' => $arrResult);
        $this->club_model->updateClub($updateValues, $viewData['clubIdx']);
      }
    }

    redirect(BASE_URL . '/admin/setup_topimage');
  }

  /**
   * 설정 - 메인 페이지 슬라이더 그림 이동
   *
   * @return redirect
   * @author bjchoi
   **/
  public function setup_topimage_move()
  {
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    $topImages = explode(',', html_escape($this->input->post('topimages')));
    $updateValues = array('topimage' => serialize($topImages));
    $this->club_model->updateClub($updateValues, $viewData['clubIdx']);
  }

  /**
   * 설정 - 메인 페이지 슬라이더 그림 파일 삭제
   *
   * @return redirect
   * @author bjchoi
   **/
  public function setup_topimage_delete()
  {
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewclub($viewData['clubIdx']);

    $filename = $this->input->post('filename');
    $arrResult = array();

    if (!empty($filename) && file_exists(FRONT_PATH . $filename)) {
      $arrTopImage = unserialize($viewData['view']['topimage']);
      foreach ($arrTopImage as $value) {
        if ($value != $filename) {
          array_push($arrResult, $value);
        }
      }

      $updateValues = array('topimage' => serialize($arrResult));
      $this->club_model->updateClub($updateValues, $viewData['clubIdx']);

      unlink(FRONT_PATH . $filename);
    }

    redirect(BASE_URL . '/admin/setup_topimage');
  }

  /**
   * 설정 - 소개 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_pages()
  {
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($viewData['clubIdx']);

    // 소개화면
    $viewData['listClubDetail'] = $this->admin_model->listClubDetail($viewData['clubIdx']);

    // 페이지 타이틀
    $viewData['pageTitle'] = '페이지관리';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'setup_header';

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
    $inputData = $this->input->post();
    $clubIdx = html_escape($inputData['clubIdx']);
    $userIdx = html_escape($this->session->userData['idx']);

    if (!empty($clubIdx)) {
      foreach ($inputData['title'] as $key => $value) {
        if (!empty($value)) {
          if (!empty($inputData['idx'][$key])) {
            // 수정
            $updateValues = array(
              'sort_idx' => $key + 1,
              'title' => $value,
              'content' => html_escape($inputData['content'][$key]),
              'updated_by' => $userIdx,
              'updated_at' => $now
            );
            $this->admin_model->updateClubDetail($updateValues, $inputData['idx'][$key]);
          } else {
            // 등록
            $insertValues = array(
              'club_idx' => $clubIdx,
              'sort_idx' => $key + 1,
              'title' => html_escape($value),
              'content' => html_escape($inputData['content'][$key]),
              'created_by' => $userIdx,
              'created_at' => $now
            );
            $this->admin_model->insertClubDetail($insertValues);
          }
        }
      }
    }

    $result = array('error' => 0, 'message' => '');
    $this->output->set_output(json_encode($result));
  }

  /**
   * 설정 - 소개 페이지 삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function setup_pages_delete()
  {
    $now = time();
    $inputData = $this->input->post();
    $idx = html_escape($inputData['idx']);
    $clubIdx = html_escape($inputData['clubIdx']);
    $userIdx = html_escape($this->session->userData['idx']);

    if (!empty($clubIdx)) {
      // 삭제
      $listClubDetail = $this->admin_model->listClubDetail($clubIdx);
      foreach ($listClubDetail as $value) {
        if ($value['idx'] == $idx) {
          $updateValues = array(
            'deleted_by' => $userIdx,
            'deleted_at' => $now
          );
          $this->admin_model->updateClubDetail($updateValues, $idx);
        }
      }
    }

    $result = array('error' => 0, 'message' => '');
    $this->output->set_output(json_encode($result));
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
          'message' => URL_FRONT . $filename,
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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    // 클럽 정보
    $viewData['viewClub'] = $this->club_model->viewClub($viewData['clubIdx']);

    $search['clubIdx'] = $viewData['clubIdx'];
    $search['status'] = array(STATUS_ABLE, STATUS_CONFIRM);
    $viewData['list'] = $this->admin_model->listNotice($search);

    // 페이지 타이틀
    $viewData['pageTitle'] = '문자양식';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'setup_header';

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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    // 차종목록
    $viewData['listBustype'] = $this->admin_model->listBustype();

    // 페이지 타이틀
    $viewData['pageTitle'] = '차종등록';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'setup_header';

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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

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

    // 페이지 타이틀
    $viewData['pageTitle'] = '차종등록';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'setup_header';

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
    $bus_type     = $this->input->post('bus_type') != '' ? html_escape($this->input->post('bus_type')) : NULL;
    $memo         = $this->input->post('memo') != '' ? html_escape($this->input->post('memo')) : NULL;

    $insertData = array(
      'bus_name'    => $bus_name,
      'bus_owner'   => $bus_owner,
      'bus_license' => $bus_license,
      'bus_color'   => $bus_color,
      'bus_seat'    => $bus_seat,
      'bus_type'    => $bus_type,
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
    $idx            = $this->input->post('idx') != '' ? html_escape($this->input->post('idx')) : NULL;
    $bus_name       = $this->input->post('bus_name') != '' ? html_escape($this->input->post('bus_name')) : NULL;
    $bus_owner      = $this->input->post('bus_owner') != '' ? html_escape($this->input->post('bus_owner')) : NULL;
    $bus_license    = $this->input->post('bus_license') != '' ? html_escape($this->input->post('bus_license')) : NULL;
    $bus_color      = $this->input->post('bus_color') != '' ? html_escape($this->input->post('bus_color')) : NULL;
    $bus_seat       = $this->input->post('bus_seat') != '' ? html_escape($this->input->post('bus_seat')) : NULL;
    $bus_type       = $this->input->post('bus_type') != '' ? html_escape($this->input->post('bus_type')) : NULL;
    $memo           = $this->input->post('memo') != '' ? html_escape($this->input->post('memo')) : NULL;
    $result         = 0;

    if (!is_null($idx)) {
      $updateData = array(
        'bus_name'    => $bus_name,
        'bus_owner'   => $bus_owner,
        'bus_license' => $bus_license,
        'bus_color'   => $bus_color,
        'bus_seat'    => $bus_seat,
        'bus_type'    => $bus_type,
        'memo'        => $memo,
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
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    $sdate = html_escape($this->input->get('d'));
    if (!empty($sdate)) $viewData['sdate'] = html_escape($sdate); else $viewData['sdate'] = NULL;

    // 캘린더 설정
    $viewData['listCalendar'] = $this->admin_model->listCalendar();

    // 페이지 타이틀
    $viewData['pageTitle'] = '달력관리';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'setup_header';

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
   * 닉네임으로 회원 정보 불러오기
   *
   * @return json
   * @author bjchoi
   **/
  public function search_by_nickname()
  {
    $search['club_idx'] = html_escape($this->input->post('clubIdx'));
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
   * 코로나19 대응 자동배정
   *
   * @return json
   * @author bjchoi
   **/
  public function autoseat()
  {
    $now = time();
    $idx = html_escape($this->input->post('idx'));
    $result = array('error' => 1, 'message' => $this->lang->line('error_all'));

    // 예약자가 있는지 확인
    $search['rescode'] = $idx;
    $reserve = $this->admin_model->viewReserve($search);

    if (!empty($reserve)) {
      $result = array('error' => 1, 'message' => '이미 예약된 정보가 있습니다.');
    } else {
      $notice = $this->admin_model->viewEntry($idx);
      $bustype = getBusType($notice['bustype'], $notice['bus']); // 버스 형태별 좌석 배치

      foreach ($bustype as $key => $value) {
        if ($value['seat'] == 44 && $value['direction'] == 0) {
          // 44석 순방향
          $postData = array('rescode' => $idx, 'bus' => ($key + 1), 'regdate' => $now);

          for ($i=0; $i<=5; $i++) {
            $postData['seat'] = 1+($i*8); $postData['nickname'] = '2인우선'; $prevIdx1 = $this->admin_model->insertReserve($postData);
            $postData['seat'] = 2+($i*8); $prevIdx2 = $this->admin_model->insertReserve($postData);
            $updateData = array('priority' => $prevIdx1); $this->admin_model->updateReserve($updateData, $prevIdx2);
            $updateData = array('priority' => $prevIdx2); $rtn = $this->admin_model->updateReserve($updateData, $prevIdx1);

            $postData['seat'] = 3+($i*8); $postData['nickname'] = '1인우등'; $prevIdx1 = $this->admin_model->insertReserve($postData);
            $postData['seat'] = 4+($i*8); $prevIdx2 = $this->admin_model->insertReserve($postData);
            $updateData = array('honor' => $prevIdx1); $this->admin_model->updateReserve($updateData, $prevIdx2);
            $updateData = array('honor' => $prevIdx2); $rtn = $this->admin_model->updateReserve($updateData, $prevIdx1);
          }
          for ($i=0; $i<=4; $i++) {
            $postData['seat'] = 5+($i*8); $postData['nickname'] = '1인우등'; $prevIdx1 = $this->admin_model->insertReserve($postData);
            $postData['seat'] = 6+($i*8); $prevIdx2 = $this->admin_model->insertReserve($postData);
            $updateData = array('honor' => $prevIdx1); $this->admin_model->updateReserve($updateData, $prevIdx2);
            $updateData = array('honor' => $prevIdx2); $rtn = $this->admin_model->updateReserve($updateData, $prevIdx1);

            $postData['seat'] = 7+($i*8); $postData['nickname'] = '2인우선'; $prevIdx1 = $this->admin_model->insertReserve($postData);
            $postData['seat'] = 8+($i*8); $prevIdx2 = $this->admin_model->insertReserve($postData);
            $updateData = array('priority' => $prevIdx1); $this->admin_model->updateReserve($updateData, $prevIdx2);
            $updateData = array('priority' => $prevIdx2); $rtn = $this->admin_model->updateReserve($updateData, $prevIdx1);
          }
        } elseif ($value['seat'] == 45 && $value['direction'] == 0) {
          // 45석 순방향
          $postData = array('rescode' => $idx, 'bus' => ($key + 1), 'regdate' => $now);

          for ($i=0; $i<=4; $i++) {
            $postData['seat'] = 1+($i*8); $postData['nickname'] = '2인우선'; $prevIdx1 = $this->admin_model->insertReserve($postData);
            $postData['seat'] = 2+($i*8); $prevIdx2 = $this->admin_model->insertReserve($postData);
            $updateData = array('priority' => $prevIdx1); $this->admin_model->updateReserve($updateData, $prevIdx2);
            $updateData = array('priority' => $prevIdx2); $rtn = $this->admin_model->updateReserve($updateData, $prevIdx1);

            $postData['seat'] = 3+($i*8); $postData['nickname'] = '1인우등'; $prevIdx1 = $this->admin_model->insertReserve($postData);
            $postData['seat'] = 4+($i*8); $prevIdx2 = $this->admin_model->insertReserve($postData);
            $updateData = array('honor' => $prevIdx1); $this->admin_model->updateReserve($updateData, $prevIdx2);
            $updateData = array('honor' => $prevIdx2); $rtn = $this->admin_model->updateReserve($updateData, $prevIdx1);
          }
          for ($i=0; $i<=4; $i++) {
            $postData['seat'] = 5+($i*8); $postData['nickname'] = '1인우등'; $prevIdx1 = $this->admin_model->insertReserve($postData);
            $postData['seat'] = 6+($i*8); $prevIdx2 = $this->admin_model->insertReserve($postData);
            $updateData = array('honor' => $prevIdx1); $this->admin_model->updateReserve($updateData, $prevIdx2);
            $updateData = array('honor' => $prevIdx2); $rtn = $this->admin_model->updateReserve($updateData, $prevIdx1);

            $postData['seat'] = 7+($i*8); $postData['nickname'] = '2인우선'; $prevIdx1 = $this->admin_model->insertReserve($postData);
            $postData['seat'] = 8+($i*8); $prevIdx2 = $this->admin_model->insertReserve($postData);
            $updateData = array('priority' => $prevIdx1); $this->admin_model->updateReserve($updateData, $prevIdx2);
            $updateData = array('priority' => $prevIdx2); $rtn = $this->admin_model->updateReserve($updateData, $prevIdx1);
          }

          // 41, 42번 좌석 (2인우선)
          $postData['seat'] = 41; $postData['nickname'] = '2인우선'; $prevIdx1 = $this->admin_model->insertReserve($postData);
          $postData['seat'] = 42; $prevIdx2 = $this->admin_model->insertReserve($postData);
          $updateData = array('priority' => $prevIdx1); $this->admin_model->updateReserve($updateData, $prevIdx2);
          $updateData = array('priority' => $prevIdx2); $rtn = $this->admin_model->updateReserve($updateData, $prevIdx1);

          // 44, 45번 좌석 (1인우등)
          $postData['seat'] = 44; $postData['nickname'] = '1인우등'; $prevIdx1 = $this->admin_model->insertReserve($postData);
          $postData['seat'] = 45; $prevIdx2 = $this->admin_model->insertReserve($postData);
          $updateData = array('honor' => $prevIdx1); $this->admin_model->updateReserve($updateData, $prevIdx2);
          $updateData = array('honor' => $prevIdx2); $rtn = $this->admin_model->updateReserve($updateData, $prevIdx1);

          // 운영진우선
          $postData['seat'] = 43; $postData['nickname'] = '운영진우선'; $postData['manager'] = 1; $postData['status'] = 1; $this->admin_model->insertReserve($postData);
        } else {
          $msg = $value['seat'] . '석 ';
          if ($value['direction'] == 1) $msg .= '역방향'; else $msg .= '순방향';
          $result = array('error' => 1, 'message' => $msg . '에 해당하는 버스 템플릿이 없습니다.');
        }
      }
    }

    if (!empty($rtn)) $result = array('error' => 0, 'message' => $rtn);

    $this->output->set_output(json_encode($result));
  }

  /**
   * 북마크 등록/수정
   *
   * @return json
   * @author bjchoi
   **/
  public function bookmark_update()
  {
    $now = time();
    $userData = $this->load->get_var('userData');
    $clubIdx = !empty($this->input->post('clubIdx')) ? html_escape($this->input->post('clubIdx')) : NULL;
    $idx = !empty($this->input->post('idx')) ? html_escape($this->input->post('idx')) : NULL;
    $parent_idx = !empty($this->input->post('parent_idx')) ? html_escape($this->input->post('parent_idx')) : 0;
    $link = !empty($this->input->post('link')) ? html_escape($this->input->post('link')) : NULL;
    $title = !empty($this->input->post('title')) ? html_escape($this->input->post('title')) : NULL;
    $bgcolor = !empty($this->input->post('bgcolor')) ? html_escape($this->input->post('bgcolor')) : '#929fba';
    $memo = !empty($this->input->post('memo')) ? html_escape($this->input->post('memo')) : NULL;

    if ($parent_idx == 0) {
      $listBookmark = $this->admin_model->listBookmark($clubIdx, $parent_idx);
      $max = count($listBookmark);
    } else {
      $max = 0;
    }

    if (!empty($idx)) {
      // 수정
      $updateValues['parent_idx'] = $parent_idx;
      $updateValues['sort_idx'] = $max;
      $updateValues['link'] = $link;
      $updateValues['title'] = $title;
      $updateValues['bgcolor'] = $bgcolor;
      $updateValues['memo'] = $memo;
      $this->desk_model->update(DB_BOOKMARKS, $updateValues, $idx);
    } else {
      // 등록
      $insertValues['parent_idx'] = $parent_idx;
      $insertValues['sort_idx'] = $max;
      $insertValues['link'] = $link;
      $insertValues['title'] = $title;
      $insertValues['bgcolor'] = $bgcolor;
      $insertValues['memo'] = $memo;
      $insertValues['created_by'] = $userData['idx'];
      $insertValues['created_at'] = $now;
      $idx = $this->desk_model->insert(DB_BOOKMARKS, $insertValues);
    }

    if (empty($idx)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
    } else {
      $result = array('error' => 0, 'message' => $idx);
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 북마크 이동
   *
   * @return json
   * @author bjchoi
   **/
  public function bookmark_sort()
  {
    $idx = !empty($this->input->post('idx')) ? html_escape($this->input->post('idx')) : NULL;

    if (empty($idx)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
    } else {
      $arrIdx = explode(',', $idx);

      foreach ($arrIdx as $key => $value) {
        $updateValues['sort_idx'] = $key + 1;
        $this->desk_model->update(DB_BOOKMARKS, $updateValues, $value);
      }

      $result = array('error' => 0, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 북마크 등록/수정
   *
   * @return json
   * @author bjchoi
   **/
  public function bookmark_delete()
  {
    $now = time();
    $userData = $this->load->get_var('userData');
    $idx = !empty($this->input->post('idx')) ? html_escape($this->input->post('idx')) : NULL;

    if (!empty($idx)) {
      // 수정
      $updateValues['deleted_by'] = $userData['idx'];
      $updateValues['deleted_at'] = $now;
      $rtn = $this->desk_model->update(DB_BOOKMARKS, $updateValues, $idx);
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_delete'));
    } else {
      $result = array('error' => 0, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 멀티 사진 업로드
   *
   * @return view
   * @author bjchoi
   **/
  public function upload()
  {
    $this->load->view('admin/upload');
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
        if (move_uploaded_file($value, EDITOR_PATH . $filename)) {
          // 썸네일 만들기
          $this->image_lib->clear();
          $config['image_library'] = 'gd2';
          $config['source_image'] = EDITOR_PATH . $filename;
          $config['new_image'] = EDITOR_PATH . 'thumb_' . $filename;
          $config['create_thumb'] = TRUE;
          $config['maintain_ratio'] = TRUE;
          $config['thumb_marker'] = '';
          $config['width'] = 500;
          $this->image_lib->initialize($config);
          $this->image_lib->resize();
        }
        $buf = array(
          'sFileName' => $filename,
          'sFileURL' => EDITOR_URL . $filename,
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
    $viewData['uri'] = $_SERVER['REQUEST_URI'];
    $viewData['keyword'] = html_escape($this->input->post('k'));

    // 클럽 정보
    $viewData['viewClub'] = $this->club_model->viewClub($viewData['clubIdx']);

    // 등록된 산행 목록
    $viewData['listNoticeSchedule'] = $this->reserve_model->listNotice($viewData['clubIdx'], array(STATUS_ABLE, STATUS_CONFIRM));

    // 진행중 산행
    $search['clubIdx'] = $viewData['clubIdx'];
    $search['status'] = array(STATUS_ABLE, STATUS_CONFIRM);
    $viewData['listNoticeFooter'] = $this->admin_model->listNotice($search);

    // 클럽 대표이미지
    $files = $this->file_model->getFile('club', $viewData['clubIdx']);
    if (!empty($files[0]['filename']) && file_exists(PHOTO_PATH . $files[0]['filename'])) {
      $size = getImageSize(PHOTO_PATH . $files[0]['filename']);
      $viewData['viewClub']['main_photo'] = PHOTO_URL . $files[0]['filename'];
      $viewData['viewClub']['main_photo_width'] = $size[0];
      $viewData['viewClub']['main_photo_height'] = $size[1];
    }

    // 최신 댓글
    $paging['perPage'] = 5; $paging['nowPage'] = 0;
    $viewData['listFooterReply'] = $this->admin_model->listReply($viewData['clubIdx'], $paging);

    foreach ($viewData['listFooterReply'] as $key => $value) {
      if ($value['reply_type'] == REPLY_TYPE_STORY):  $viewData['listFooterReply'][$key]['url'] = BASE_URL . '/story/view/' . $value['story_idx']; endif;
      if ($value['reply_type'] == REPLY_TYPE_NOTICE): $viewData['listFooterReply'][$key]['url'] = BASE_URL . '/admin/main_view_progress/' . $value['story_idx']; endif;
      if ($value['reply_type'] == REPLY_TYPE_SHOP):   $viewData['listFooterReply'][$key]['url'] = BASE_URL . '/shop/item/' . $value['story_idx']; endif;
    }

    // 리다이렉트 URL 추출
    if ($_SERVER['SERVER_PORT'] == '80') $HTTP_HEADER = 'http://'; else $HTTP_HEADER = 'https://';
    $viewData['redirectUrl'] = $HTTP_HEADER . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    if (empty($viewData['viewClub']['main_design'])) $viewData['viewClub']['main_design'] = 1;

    $this->load->view('admin/header_' . $viewData['viewClub']['main_design'], $viewData);
    if (!empty($viewData['headerMenu'])) $this->load->view('admin/' . $viewData['headerMenu'], $viewData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('admin/footer_' . $viewData['viewClub']['main_design'], $viewData);
  }
}
?>
