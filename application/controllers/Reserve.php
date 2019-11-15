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
    checkUserLogin();

    $clubIdx = $this->load->get_var('clubIdx');
    $noticeIdx = html_escape($this->input->get('n'));

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 등록된 산행 목록
    $viewData['listNotice'] = $this->club_model->listNotice($clubIdx);

    if (!empty($noticeIdx)) {
      // 예약 공지
      $viewData['notice'] = $this->club_model->viewNotice($clubIdx, $noticeIdx);

      // 버스 형태별 좌석 배치
      $viewData['busType'] = getBusType($viewData['notice']['bustype'], $viewData['notice']['bus']);

      // 예약 정보
      $viewData['reserve'] = $this->reserve_model->viewProgress($clubIdx, $noticeIdx);
    } else {
      $viewData['notice'] = array();
      $viewData['busType'] = array();
      $viewData['reserve'] = array();
    }

    $this->_viewPage('reserve/index', $viewData);
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
    $noticeIdx = html_escape($this->input->post('idx'));
    $resIdx = html_escape($this->input->post('resIdx'));

    $notice = $this->club_model->viewNotice($clubIdx, $noticeIdx);

    if (!empty($resIdx)) {
      $result['reserve'] = $this->reserve_model->viewReserve($clubIdx, $resIdx);
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
  public function insert()
  {
    checkUserLogin();

    $now = time();
    $userData   = $this->load->get_var('userData');
    $postData   = $this->input->post();
    $clubIdx    = html_escape($postData['club_idx']);
    $noticeIdx  = html_escape($postData['notice_idx']);
    $bus        = $postData['bus'];
    $location   = $postData['location'];
    $memo       = $postData['memo'];
    $resIdx     = $postData['resIdx'];

    // 페널티 지급 적용자인지 체크
    $viewNotice = $this->club_model->viewNotice($clubIdx, $noticeIdx);
    $startTime = explode(':', $viewNotice['starttime']);
    $startDate = explode('-', $viewNotice['startdate']);
    $limitDate = mktime($startTime[0], $startTime[1], 0, $startDate[1], $startDate[2], $startDate[0]);
    if ( $limitDate < ($now + 172800) ) $penalty = 1; else $penalty = 0;

    foreach ($postData['seat'] as $key => $seat) {
      if (!empty($resIdx[$key])) $resIdxNow = html_escape($resIdx[$key]); else $resIdxNow = 0;
      $processData  = array(
        'club_idx'  => $clubIdx,
        'rescode'   => $noticeIdx,
        'userid'    => $userData['userid'],
        'nickname'  => $userData['nickname'],
        'gender'    => $userData['gender'],
        'bus'       => html_escape($bus[$key]),
        'seat'      => html_escape($seat),
        'loc'       => html_escape($location[$key]),
        'memo'      => html_escape($memo[$key]),
        'penalty'   => $penalty,
        'regdate'   => $now,
      );

      // 이미 예약이 되어 있는지 체크
      $checkReserve = $this->reserve_model->checkReserve($clubIdx, $noticeIdx, $bus[$key], $seat);

      if (empty($resIdxNow) && empty($checkReserve['idx'])) {
        $result = $this->reserve_model->insertReserve($processData);
      } elseif (!empty($resIdxNow) && $checkReserve['userid'] == $userData['userid']) {
        $result = $this->reserve_model->updateReserve($processData, $resIdxNow);
      }
    }

    if (empty($result)) {
      $result = array(
        'error' => 1,
        'message' => '이미 예약된 좌석이 포함되어 있습니다. 다시 예약해주세요.'
      );
    } else {
      // 로그 기록
      setHistory(2, $noticeIdx, $userData['userid'], $viewNotice['subject'], $now);

      $result = array(
        'error' => 0,
        'message' => base_url() . 'reserve/check/' . $clubIdx . '?n=' . $noticeIdx . '&c=' . $result
      );
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
    $noticeIdx = html_escape($this->input->get('n'));
    $reserveIdx = html_escape($this->input->get('c'));

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);
    $viewData['view']['noticeIdx'] = $noticeIdx;

    // 등록된 산행 목록
    $viewData['listNotice'] = $this->club_model->listNotice($clubIdx);

    $this->_viewPage('club/check', $viewData);
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
    $checkReserve = $this->input->post('checkReserve');
    $result = array('error' => 1, 'message' => '예약좌석 취소 중 문제가 발생했습니다. 다시 확인해주세요.');

    foreach ($checkReserve as $idx) {
      $userReserve = $this->reserve_model->userReserve($clubIdx, NULL, $idx);

      // 예약 삭제 처리
      $rtn = $this->reserve_model->cancelReserve($idx);

      if (!empty($rtn)) {
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
          setHistory(6, $userReserve['resCode'], $userReserve['userid'], $userReserve['subject'] . ' 예약 취소', $nowDate, $penalty);
        }

        // 예약정보에 포인트가 있을때 되돌려줌
        if ($userReserve['point'] > 0) {
          $this->member_model->updatePoint($clubIdx, $userReserve['userid'], ($userData['point'] + $userReserve['point']));

          // 포인트 반환 로그 기록
          setHistory(4, $userReserve['resCode'], $userReserve['userid'], $userReserve['subject'] . ' 예약 취소', $nowDate, $userReserve['point']);
        }

        // 예약 취소 로그 기록
        setHistory(3, $userReserve['resCode'], $userReserve['userid'], $userReserve['subject'], $nowDate);

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
      $processData['status'] = 0;
    } else {
      $processData['status'] = 1;
    }

    foreach ($checkReserve as $key => $value) {
      $idx = html_escape($value);

      // 최초 1회, 포인트를 사용했을때 포인트 차감
      if ($key == 0 && !empty($processData['point'])) {
        $userReserve = $this->reserve_model->userReserve($clubIdx, NULL, $idx);

        // 포인트 차감
        $this->member_model->updatePoint($clubIdx, $userReserve['userid'], ($userData['point'] - $processData['point']));

        // 포인트 차감 로그 기록
        setHistory(5, $userReserve['resCode'], $userReserve['userid'], $userReserve['subject'] . ' 예약', $nowDate, $processData['point']);
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
    $viewData['uri'] = 'club';

    // 회원 정보
    $viewData['userData'] = $this->load->get_var('userData');
    $viewData['userLevel'] = $this->load->get_var('userLevel');

    // 진행 중 산행
    $viewData['listNotice'] = $this->club_model->listNotice($viewData['view']['idx'], array(STATUS_NONE, STATUS_ABLE, STATUS_CONFIRM));

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

    $this->load->view('header', $viewData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('footer', $viewData);
  }
}
?>
