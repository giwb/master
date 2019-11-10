<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 첫 페이지 클래스
class Club extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('cookie', 'security', 'url', 'my_array_helper'));
    $this->load->library('session');
    $this->load->model(array('club_model', 'file_model', 'notice_model', 'member_model', 'story_model'));
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

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 등록된 산행 목록
    $viewData['listNotice'] = $this->club_model->listNotice($clubIdx);

    // 클럽 이야기
    $viewData['listStory'] = $this->story_model->listStory($clubIdx);

    $this->_viewPage('club/index', $viewData);
  }

  /**
   * 예약 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function reserve()
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
      $viewData['reserve'] = $this->club_model->viewProgress($clubIdx, $noticeIdx);
    } else {
      $viewData['notice'] = array();
      $viewData['busType'] = array();
      $viewData['reserve'] = array();
    }

    $this->_viewPage('club/reserve', $viewData);
  }

  /**
   * 예약 정보
   *
   * @return json
   * @author bjchoi
   **/
  public function reserve_information()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $noticeIdx = html_escape($this->input->post('idx'));
    $resIdx = html_escape($this->input->post('resIdx'));

    $notice = $this->club_model->viewNotice($clubIdx, $noticeIdx);

    if (!empty($resIdx)) {
      $result['reserve'] = $this->club_model->viewReserve($clubIdx, $resIdx);
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
  public function reserve_insert()
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
      $resIdx   = html_escape($resIdx[$key]);
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
      $checkReserve = $this->club_model->checkReserve($clubIdx, $noticeIdx, $bus[$key], $seat);

      if (empty($resIdx) && empty($checkReserve['idx'])) {
        $result = $this->club_model->insertReserve($processData);
      } elseif (!empty($resIdx) && $checkReserve['userid'] == $userData['userid']) {
        $result = $this->club_model->updateReserve($processData, $resIdx);
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
        'message' => base_url() . 'club/reserve_check/' . $clubIdx . '?n=' . $noticeIdx . '&c=' . $result
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
  public function reserve_check()
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
   * 설정
   *
   * @return json
   * @author bjchoi
   **/
  public function setup()
  {
    checkAdminLogin();

    $this->_viewPage('club/check', $viewData);
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

    $this->load->view('header', $viewData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('footer', $viewData);
  }
}
?>
