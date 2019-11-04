<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 첫 페이지 클래스
class Club extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('url', 'my_array_helper'));
    $this->load->library('session');
    $this->load->model(array('club_model', 'file_model', 'notice_model', 'member_model', 'story_model'));
  }

  /**
   * 클럽 메인 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function index($clubIdx=NULL)
  {
    if (is_null($clubIdx)) {
      $clubIdx = 1; // 최초는 경인웰빙
    } else {
      $clubIdx = html_escape($clubIdx);
    }

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
  public function reserve($clubIdx=NULL)
  {
    if (is_null($clubIdx)) {
      $clubIdx = 1; // 최초는 경인웰빙
    } else {
      $clubIdx = html_escape($clubIdx);
    }
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
   * 예약 처리
   *
   * @return json
   * @author bjchoi
   **/
  public function reserve_insert()
  {
    $now = time();
    $userData   = $this->session->userData;
    $postData   = $this->input->post();
    $clubIdx    = html_escape($postData['club_idx']);
    $noticeIdx  = html_escape($postData['notice_idx']);

    // 페널티 지급 적용자인지 체크
    $viewNotice = $this->club_model->viewNotice($clubIdx, $noticeIdx);
    $startTime = explode(':', $viewNotice['starttime']);
    $startDate = explode('-', $viewNotice['startdate']);
    $limitDate = mktime($startTime[0], $startTime[1], 0, $startDate[1], $startDate[2], $startDate[0]);
    if ( $limitDate < ($now + 172800) ) $penalty = 1; else $penalty = 0;

    foreach ($postData['seat'] as $key => $seat) {
      $bus          = $postData['bus'];
      $location     = $postData['location'];
      $memo         = $postData['memo'];
      $resIdx       = $postData['resIdx'];

      // 이미 예약이 되어 있는지 체크
      $checkReserve = $this->club_model->checkReserve($clubIdx, $noticeIdx, $bus[$key], $seat);

      if (empty($checkReserve['idx'])) {
        $processData  = array(
          'club_idx'  => $clubIdx,
          'rescode'   => $noticeIdx,
          'userid'    => $userData['userid'],
          'nickname'  => $userData['nickname'],
          'gender'    => $userData['gender'],
          'bus'       => $bus[$key],
          'seat'      => $seat,
          'loc'       => $location[$key],
          'memo'      => $memo[$key],
          'penalty'   => $penalty,
          'regdate'   => $now,
        );

        $resIdx = html_escape($resIdx[$key]);

        if (empty($resIdx)) {
          $result = $this->club_model->insertReserve($processData);
        } else {
          $result = $this->club_model->updateReserve($processData, $resIdx);
        }
      }
    }

    // 로그 기록
    setHistory(2, $noticeIdx, $userData['userid'], $viewNotice['subject'], $now);

    if (empty($result)) {
      $result = array(
        'error' => 1,
        'message' => '이미 예약된 좌석이 포함되어 있습니다. 다시 예약해주세요.'
      );
    } else {
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
  public function reserve_check($clubIdx=NULL)
  {
    if (is_null($clubIdx)) {
      $clubIdx = 1; // 최초는 경인웰빙
    } else {
      $clubIdx = html_escape($clubIdx);
    }
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
   * 페이지 표시
   *
   * @param $viewPage
   * @param $viewData
   * @return view
   * @author bjchoi
   **/
  private function _viewPage($viewPage, $viewData=NULL)
  {
    $viewData['uri'] = 'top';
    $viewData['userData'] = $this->session->userData;

    // 진행 중 산행
    $viewData['listNotice'] = $this->club_model->listNotice($viewData['view']['idx'], array(STATUS_NONE, STATUS_ABLE, STATUS_CONFIRM));

    // 회원 정보
    $viewData['viewMember'] = $this->member_model->viewMember($viewData['view']['idx'], html_escape($viewData['userData']['idx']));
    $viewData['viewLevel'] = memberLevel($viewData['viewMember']);

    $this->load->view('header', $viewData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('footer', $viewData);
  }
}
?>
