<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 회원 페이지 클래스
class Member extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('url', 'my_array_helper'));
    $this->load->library(array('image_lib', 'session'));
    $this->load->model(array('club_model', 'member_model', 'reserve_model'));
  }

  /**
   * 마이페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function index()
  {
    checkUserLogin();

    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $viewData['view'] = $this->club_model->viewclub($clubIdx);

    // 예약 내역
    $viewData['userReserve'] = $this->reserve_model->userReserve($clubIdx, $userData['userid']);

    // 산행 내역
    $viewData['userVisit'] = $this->reserve_model->userVisit($clubIdx, $userData['userid']);

    // 포인트 내역
    $viewData['userPoint'] = $this->member_model->userPointLog($clubIdx, $userData['userid']);

    // 페널티 내역
    $viewData['userPenalty'] = $this->member_model->userPenaltyLog($clubIdx, $userData['userid']);

    $this->_viewPage('member/index', $viewData);
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
    $viewData['uri'] = 'member';

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
