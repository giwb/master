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
    $this->load->model(array('admin_model', 'club_model', 'file_model', 'notice_model', 'story_model'));
  }

  /**
   * 클럽 메인 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function index($club_idx=NULL)
  {
    if (is_null($club_idx)) {
      $club_idx = 1; // 최초는 경인웰빙
    } else {
      $club_idx = html_escape($club_idx);
    }

    $viewData['view'] = $this->club_model->viewClub($club_idx);
    $viewData['view']['photo'] = array();
    $viewData['view']['content'] = nl2br(reset_html_escape($viewData['view']['content']));

    $files = $this->file_model->getFile('club', $club_idx);

    foreach ($files as $key => $value) {
      if (!$value['filename'] == '') {
        $viewData['view']['photo'][$key] = $value['filename'];
      } else {
        $viewData['view']['photo'][$key] = '';
      }
    }

    // 등록된 산행 목록
    $viewData['listNotice'] = $this->club_model->listNotice($club_idx);

    // 클럽 이야기
    $viewData['listStory'] = $this->story_model->listStory($club_idx);

    $this->_viewPage('club/index', $viewData);
  }

  /**
   * 예약 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function reserve($club_idx=NULL)
  {
    if (is_null($club_idx)) {
      $club_idx = 1; // 최초는 경인웰빙
    } else {
      $club_idx = html_escape($club_idx);
    }
    $idx = html_escape($this->input->get('n'));

    $viewData['view'] = $this->club_model->viewClub($club_idx);
    $viewData['view']['photo'] = array();
    $viewData['view']['content'] = nl2br(reset_html_escape($viewData['view']['content']));

    $files = $this->file_model->getFile('club', $club_idx);

    foreach ($files as $key => $value) {
      if (!$value['filename'] == '') {
        $viewData['view']['photo'][$key] = $value['filename'];
      } else {
        $viewData['view']['photo'][$key] = '';
      }
    }

    // 등록된 산행 목록
    $viewData['listNotice'] = $this->club_model->listNotice($club_idx);

    if (!empty($idx)) {
      // 예약 공지
      $viewData['notice'] = $this->club_model->viewNotice($club_idx, $idx);

      // 버스 형태별 좌석 배치
      $viewData['busType'] = getBusType($viewData['notice']['bustype'], $viewData['notice']['bus']);

      // 예약 정보
      $viewData['reserve'] = $this->club_model->viewProgress($club_idx, $idx);
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
    //print_r($this->input->post());
    $result = array(
      'error' => 0,
      'url' => base_url() . 'club/reserve_check/1?n=407&c=1000'
    );
    $this->output->set_output(json_encode($result));
/*
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
*/
  }

  /**
   * 예약 확인
   *
   * @return json
   * @author bjchoi
   **/
  public function reserve_check($club_idx=NULL)
  {
    if (is_null($club_idx)) {
      $club_idx = 1; // 최초는 경인웰빙
    } else {
      $club_idx = html_escape($club_idx);
    }
    $idx = html_escape($this->input->get('n'));

    $viewData['view'] = $this->club_model->viewClub($club_idx);
    $viewData['view']['photo'] = array();
    $viewData['view']['content'] = nl2br(reset_html_escape($viewData['view']['content']));

    $files = $this->file_model->getFile('club', $club_idx);

    foreach ($files as $key => $value) {
      if (!$value['filename'] == '') {
        $viewData['view']['photo'][$key] = $value['filename'];
      } else {
        $viewData['view']['photo'][$key] = '';
      }
    }

    // 등록된 산행 목록
    $viewData['listNotice'] = $this->club_model->listNotice($club_idx);

    if (!empty($idx)) {
      // 예약 공지
      $viewData['notice'] = $this->club_model->viewNotice($club_idx, $idx);

      // 버스 형태별 좌석 배치
      $viewData['busType'] = getBusType($viewData['notice']['bustype'], $viewData['notice']['bus']);

      // 예약 정보
      $viewData['reserve'] = $this->club_model->viewProgress($club_idx, $idx);
    } else {
      $viewData['notice'] = array();
      $viewData['busType'] = array();
      $viewData['reserve'] = array();
    }

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
    $headerData['userData'] = $viewData['userData'] = $this->session->userData;
    $headerData['view'] = $viewData['view'];
    $headerData['uri'] = 'top';

    // 진행 중 산행
    $footerData['listNotice'] = $this->club_model->listNotice($viewData['view']['idx'], array(STATUS_NONE, STATUS_ABLE, STATUS_CONFIRM));

    $this->load->view('header', $headerData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('footer', $footerData);
  }
}
?>
