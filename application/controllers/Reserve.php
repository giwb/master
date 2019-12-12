<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 첫 페이지 클래스
class Reserve extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('cookie', 'security', 'url', 'my_array_helper'));
    $this->load->library('session');
    $this->load->model(array('area_model', 'club_model', 'file_model', 'notice_model', 'member_model', 'reserve_model', 'story_model'));
  }

  /**
   * 예약 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function index()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $noticeIdx = html_escape($this->input->get('n'));

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 등록된 산행 목록
    $viewData['listNotice'] = $this->reserve_model->listNotice($clubIdx);

    if (!empty($noticeIdx)) {
      // 예약 공지
      $viewData['notice'] = $this->reserve_model->viewNotice($clubIdx, $noticeIdx);

      // 버스 형태별 좌석 배치
      $viewData['busType'] = getBusType($viewData['notice']['bustype'], $viewData['notice']['bus']);

      // 예약 정보
      $viewData['reserve'] = $this->reserve_model->viewProgress($clubIdx, $noticeIdx);
    } else {
      $viewData['notice'] = array();
      $viewData['busType'] = array();
      $viewData['reserve'] = array();
    }

    // 탑승 위치
    $viewData['arrLocation'] = arrLocation();

    $this->_viewPage('reserve/index', $viewData);
  }

  /**
   * 공지 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function notice()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $noticeIdx = html_escape($this->input->get('n'));

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 공지 정보
    $viewData['notice'] = $this->reserve_model->viewNotice($clubIdx, $noticeIdx);

    $cntReply = $this->story_model->cntStoryReply($clubIdx, $noticeIdx, REPLY_TYPE_NOTICE);
    $cntLike = $this->story_model->cntStoryReaction($clubIdx, $noticeIdx, REPLY_TYPE_NOTICE, REACTION_KIND_LIKE);
    $cntShare = $this->story_model->cntStoryReaction($clubIdx, $noticeIdx, REPLY_TYPE_NOTICE, REACTION_KIND_SHARE);
    $viewData['notice']['reply_cnt'] = $cntReply['cnt'];
    $viewData['notice']['like_cnt'] = $cntLike['cnt'];
    $viewData['notice']['share_cnt'] = $cntShare['cnt'];

    // 공지 댓글
    $viewData['listReply'] = $this->story_model->listStoryReply($clubIdx, $noticeIdx, REPLY_TYPE_NOTICE);

    if (!empty($viewData['notice']['photo']) && file_exists(PHOTO_PATH . $viewData['notice']['photo'])) {
      $viewData['notice']['photo'] = base_url() . '/' . PHOTO_URL . $viewData['notice']['photo'];
    }

    if (!empty($viewData['notice']['map']) && file_exists(PHOTO_PATH . $viewData['notice']['map'])) {
      $viewData['notice']['map'] = base_url() . '/' . PHOTO_URL . $viewData['notice']['map'];
    }

    $this->_viewPage('reserve/notice', $viewData);
  }

  /**
   * 예약 정보
   *
   * @return json
   * @author bjchoi
   **/
  public function information()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $noticeIdx = html_escape($this->input->post('idx'));
    $resIdx = html_escape($this->input->post('resIdx'));
    $nowBus = html_escape($this->input->post('bus'));
    $nowSeat = html_escape($this->input->post('seat'));

    $notice = $this->reserve_model->viewNotice($clubIdx, $noticeIdx);

    if (!empty($resIdx)) {
      $result['reserve'] = $this->reserve_model->viewReserve($clubIdx, $resIdx);
      if (is_null($result['reserve']['depositname'])) $result['reserve']['depositname'] = '';
      if (is_null($result['reserve']['memo'])) $result['reserve']['memo'] = '';
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

    $result['busType'] = getBusType($notice['bustype'], $notice['bus']); // 버스 형태별 좌석 배치

    // 해당 버스의 좌석
    foreach ($result['busType'] as $key => $busType) {
      foreach (range(1, $busType['seat']) as $seat) {
        $seat = checkDirection($seat, ($key+1), $notice['bustype'], $notice['bus']);
        $result['seat'][] = $seat;
      }
    }

    $result['location'] = arrLocation(); // 승차위치
    $result['breakfast'] = arrBreakfast(); // 아침식사
    $result['nowSeat'] = checkDirection($nowSeat, $nowBus, $notice['bustype'], $notice['bus']);
    $result['userLocation'] = $userData['location'];

    $this->output->set_output(json_encode($result));
  }

  /**
   * 예약/수정 처리
   *
   * @return json
   * @author bjchoi
   **/
  public function insert()
  {
    checkUserLogin();

    $now = time();
    $userData   = $this->load->get_var('userData');
    $postData   = $this->input->post();
    $clubIdx    = html_escape($postData['clubIdx']);
    $noticeIdx  = html_escape($postData['noticeIdx']);
    $bus        = $postData['bus'];
    $location   = $postData['location'];
    $memo       = $postData['memo'];
    $resIdx     = $postData['resIdx'];

    // 페널티 지급 적용자인지 체크
    $viewNotice = $this->reserve_model->viewNotice($clubIdx, $noticeIdx);
    $startTime = explode(':', $viewNotice['starttime']);
    $startDate = explode('-', $viewNotice['startdate']);
    $limitDate = mktime($startTime[0], $startTime[1], 0, $startDate[1], $startDate[2], $startDate[0]);
    if ( $limitDate < ($now + 172800) ) $penalty = 1; else $penalty = 0;

    foreach ($postData['seat'] as $key => $seat) {
      if (!empty($resIdx[$key])) $resIdxNow = html_escape($resIdx[$key]); else $resIdxNow = 0;
      $nowBus = html_escape($bus[$key]);
      $seat = html_escape($seat);
      $processData  = array(
        'club_idx'  => $clubIdx,
        'rescode'   => $noticeIdx,
        'userid'    => $userData['userid'],
        'nickname'  => $userData['nickname'],
        'gender'    => $userData['gender'],
        'bus'       => $nowBus,
        'seat'      => $seat,
        'loc'       => html_escape($location[$key]),
        'memo'      => html_escape($memo[$key]),
        'penalty'   => $penalty,
        'regdate'   => $now,
      );

      if (empty($resIdxNow)) {
        $result = $this->reserve_model->insertReserve($processData);

        // 예약 번호 저장
        $reserveIdx[] = $result;

        // 로그 기록
        setHistory(LOG_RESERVE, $noticeIdx, $userData['userid'], $userData['nickname'], $viewNotice['subject'], $now);
      } else {
        // 선택한 좌석 예약 여부 확인
        $checkReserve = $this->reserve_model->checkReserve($clubIdx, $noticeIdx, $nowBus, $seat);

        // 자신이 예약한 좌석만 수정 가능
        if ($checkReserve['userid'] == $userData['userid'] || empty($checkReserve['idx'])) {
          $result = $this->reserve_model->updateReserve($processData, $resIdxNow);

          // 예약 번호 저장
          $reserveIdx[] = $resIdxNow;
        }
      }
    }

    if (empty($result)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_seat_duplicate'));
    } else {
      if ($viewNotice['status'] == STATUS_ABLE) {
        $cntReserve = $this->reserve_model->cntReserve($clubIdx, $noticeIdx);
        if ($cntReserve['cnt'] >= 15) {
          // 예약자가 15명 이상일 경우 확정으로 변경
          $processData = array('status' => STATUS_CONFIRM);
          $this->reserve_model->updateNotice($processData, $clubIdx, $noticeIdx);
        }
      }

      // 회원 예약 횟수 갱신 (회원 레벨 체크를 위해)
      $rescount = $this->reserve_model->cntMemberReserve($userData['userid']);
      $updateValues['rescount'] = $rescount['cnt'];
      $this->member_model->updateMember($updateValues, $clubIdx, $userData['idx']);

      $result = array('error' => 0, 'message' => base_url() . 'reserve/check/' . $clubIdx . '?n=' . $noticeIdx . '&c=' . implode(',', $reserveIdx));
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 대기자 처리
   *
   * @return json
   * @author bjchoi
   **/
  public function wait_insert()
  {
    checkUserLogin();

    $now = time();
    $userData   = $this->load->get_var('userData');
    $postData   = $this->input->post();
    $clubIdx    = html_escape($postData['clubIdx']);
    $noticeIdx  = html_escape($postData['noticeIdx']);
    $result = array('error' => 1, 'message' => $this->lang->line('error_all'));

    // 해당 회원이 현재 같은 예약에 대기중인지 확인
    $check = $this->reserve_model->viewReserveWait($clubIdx, $noticeIdx, $userData['idx']);

    if (!empty($check['created_at'])) {
      // 이미 대기중이면 삭제
      $rtn = $this->reserve_model->deleteReserveWait($clubIdx, $noticeIdx, $userData['idx']);

      if (!empty($rtn)) {
        $cntReserveWait = $this->reserve_model->cntReserveWait($clubIdx, $noticeIdx);
        $result = array('error' => 0, 'message' => $this->lang->line('msg_wait_delete'));
      }
    } else {
      // 대기중이지 않았을때는 등록
      foreach ($postData['location'] as $key => $value) {
        $processData  = array(
          'club_idx'    => $clubIdx,
          'notice_idx'  => $noticeIdx,
          'nickname'    => html_escape($userData['nickname']),
          'location'    => html_escape($value),
          'gender'      => html_escape($postData['gender'][$key]),
          'memo'        => html_escape($postData['memo'][$key]),
          'created_by'  => $userData['idx'],
          'created_at'  => $now,
        );
        $rtn = $this->reserve_model->insertReserveWait($processData);
      }

      if (!empty($rtn)) {
        $result = array('error' => 0, 'message' => $this->lang->line('msg_wait_insert'));
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 예약 확인
   *
   * @return json
   * @author bjchoi
   **/
  public function check()
  {
    checkUserLogin();

    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $noticeIdx = html_escape($this->input->get('n'));
    $reserveIdx = explode(',', (html_escape($this->input->get('c'))));

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);
    $viewData['view']['noticeIdx'] = $noticeIdx;

    // 예약 내역
    $viewData['listReserve'] = $this->reserve_model->listReserve($clubIdx, $reserveIdx);

    $this->_viewPage('reserve/check', $viewData);
  }

  /**
   * 예약 취소
   *
   * @return json
   * @author bjchoi
   **/
  public function cancel()
  {
    checkUserLogin(); // 로그인 확인

    $nowDate = time();
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $resIdx = explode(',', html_escape($this->input->post('resIdx')));
    $result = array('error' => 1, 'message' => '예약좌석 취소 중 문제가 발생했습니다. 다시 확인해주세요.');
    $penalty = 0;

    foreach ($resIdx as $idx) {
      // 유저 예약 정보
      $userReserve = $this->reserve_model->userReserve($clubIdx, NULL, $idx);
      $userBus = $userReserve['bus'];

      // 산행 정보
      $viewNotice = $this->reserve_model->viewNotice($clubIdx, $userReserve['rescode']);

      // 해당 산행과 버스의 예약자 수
      $cntReserve = $this->reserve_model->cntReserve($clubIdx, $userReserve['rescode'], $userBus);

      $busType = getBusType($viewNotice['bustype'], $viewNotice['bus']);
      $maxSeat = array();
      foreach ($busType as $key => $value) {
        $cnt = $key + 1;
        $maxSeat[$cnt] = $value['seat'];
      }

      // 예약자가 초과됐을 경우
      if ($cntReserve['cnt'] >= $maxSeat[$userBus]) {
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
        $rtn = $this->reserve_model->updateReserve($updateValues, $idx);
      } else {
        // 좌석이 남아있을 경우에는 그냥 삭제
        $rtn = $this->reserve_model->deleteReserve($idx);
      }

      if (!empty($rtn)) {
        if ($viewNotice['status'] == STATUS_CONFIRM) {
          $cntReserve = $this->reserve_model->cntReserve($clubIdx, $userReserve['rescode']);
          if ($cntReserve['cnt'] < 15) {
            // 예약자가 15명 이하일 경우 예정으로 변경
            $updateValues = array('status' => STATUS_ABLE);
            $this->reserve_model->updateNotice($updateValues, $clubIdx, $userReserve['rescode']);
          }
        }

        $startTime = explode(':', $userReserve['starttime']);
        $startDate = explode('-', $userReserve['startdate']);
        $limitDate = mktime($startTime[0], $startTime[1], 0, $startDate[1], $startDate[2], $startDate[0]);

        // 예약 페널티
        if ( $limitDate < ($nowDate + 86400) ) {
          // 1일전 취소시 3점 페널티
          $penalty = 3;
        } elseif ( $limitDate < ($nowDate + 172800) ) {
          // 2일전 취소시 1점 페널티
          $penalty = 1;
        }
        $this->member_model->updatePenalty($clubIdx, $userReserve['userid'], ($userData['penalty'] + $penalty));

        // 예약 페널티 로그 기록
        if ($penalty > 0) {
          setHistory(LOG_PENALTYUP, $userReserve['resCode'], $userReserve['userid'], $userReserve['nickname'], $userReserve['subject'] . ' 예약 취소', $nowDate, $penalty);
        }

        if ($userReserve['status'] == RESERVE_PAY) {
          // 분담금 합계 (기존 버젼 호환용)
          $userReserve['cost'] = $userReserve['cost_total'] == 0 ? $userReserve['cost'] : $userReserve['cost_total'];

          // 이미 입금을 마친 상태라면, 전액 포인트로 환불 (무료회원은 환불 안함)
          if (empty($userData['level']) || $userData['level'] != 2) {
            if ($userData['level'] == 1) {
              // 평생회원은 할인 적용된 가격을 환불
              $userReserve['cost'] = $userReserve['cost'] - 5000;
              $this->member_model->updatePoint($clubIdx, $userReserve['userid'], ($userData['point'] + $userReserve['cost']));
            } else {
              $this->member_model->updatePoint($clubIdx, $userReserve['userid'], ($userData['point'] + $userReserve['cost']));
            }
            // 포인트 반환 로그 기록
            setHistory(LOG_POINTUP, $userReserve['resCode'], $userReserve['userid'], $userReserve['nickname'], $userReserve['subject'] . ' 예약 취소', $nowDate, $userReserve['cost']);
          }
        } elseif ($userReserve['status'] == RESERVE_ON && $userReserve['point'] > 0) {
          // 예약정보에 포인트가 있을때 반환
          $this->member_model->updatePoint($clubIdx, $userReserve['userid'], ($userData['point'] + $userReserve['point']));

          // 포인트 반환 로그 기록
          setHistory(LOG_POINTUP, $userReserve['resCode'], $userReserve['userid'], $userReserve['nickname'], $userReserve['subject'] . ' 예약 취소', $nowDate, $userReserve['point']);
        }

        // 예약 취소 로그 기록
        setHistory(LOG_CANCEL, $userReserve['resCode'], $userReserve['userid'], $userReserve['nickname'], $userReserve['subject'], $nowDate);

        $result = array('error' => 0, 'message' => '');
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 결제정보 입력 처리
   *
   * @return json
   * @author bjchoi
   **/
  public function payment()
  {
    checkUserLogin();

    $nowDate = time();
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');

    $checkReserve = $this->input->post('checkReserve');
    $paymentCost = html_escape($this->input->post('paymentCost'));
    $processData['point'] = html_escape($this->input->post('usingPoint'));
    $processData['depositname'] = html_escape($this->input->post('depositName'));

    if ($paymentCost > 0) {
      $processData['status'] = RESERVE_ON;
    } else {
      $processData['status'] = RESERVE_PAY;
    }

    foreach ($checkReserve as $key => $value) {
      $idx = html_escape($value);

      // 최초 1회, 포인트를 사용했을때 포인트 차감
      if ($key == 0 && !empty($processData['point'])) {
        $userReserve = $this->reserve_model->userReserve($clubIdx, NULL, $idx);

        // 포인트 차감
        $this->member_model->updatePoint($clubIdx, $userReserve['userid'], ($userData['point'] - $processData['point']));

        // 포인트 차감 로그 기록
        setHistory(LOG_POINTDN, $userReserve['resCode'], $userReserve['userid'], $userReserve['nickname'], $userReserve['subject'] . ' 예약', $nowDate, $processData['point']);
      }

      $rtn = $this->reserve_model->updateReserve($processData, html_escape($value));
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => '결제정보 입력 중 문제가 발생했습니다. 다시 시도해주세요.');
    } else {
      $result = array('error' => 0, 'message' => '');
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
    $viewData['uri'] = 'reserve';

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
