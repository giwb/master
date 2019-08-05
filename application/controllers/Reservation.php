<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 산행예약 클래스
class Reservation extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('url', 'my_array_helper'));
    $this->load->library('session');
    $this->load->model(array('member_model', 'notice_model'));
  }

  /**
   * 톱 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function index()
  {
    // 예약 진행중인 다음 산행
    $viewData['listNotice'] = $this->notice_model->listNotice();

    $this->_viewPage('reservation/index', $viewData);
  }

  /**
   * 예약 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function view($idx)
  {
    // 예약 진행중인 다음 산행
    $viewData['viewNotice'] = $this->notice_model->viewNotice($idx);

    if ($viewData['viewNotice']['schedule'] != '') {
      $viewData['viewNotice']['schedule'] = calcSchedule($viewData['viewNotice']['schedule']);
    }
    if ($viewData['viewNotice']['distance'] != '') {
      $viewData['viewNotice']['distance'] = calcDistance($viewData['viewNotice']['distance']);
    }

    $this->_viewPage('reservation/view', $viewData);
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
    $headerData['userData'] = $this->session->userData;
    $this->load->view('reservation/header', $headerData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('reservation/footer');
  }
}
?>
