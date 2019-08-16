<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 첫 페이지 클래스
class Welcome extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('url', 'my_array_helper'));
    $this->load->library('session');
    $this->load->model(array('admin_model', 'notice_model'));
  }

  /**
   * 톱 페이지
   *
   * @param $syear
   * @param $smonth
   * @return view
   * @author bjchoi
   **/
  public function index()
  {
    // 대문
    $viewData['listFront'] = $this->admin_model->listFront();

    // 등록된 산행 목록
    $viewData['listNotice'] = $this->admin_model->listNotice();

    // 이번 주 산행
    $now = date('Y-m-d');
    $till = date('Y-m-d', strtotime('+1 week', time()));
    $viewData['listThisWeekNotice'] = $this->notice_model->listThisWeekNotice($now, $till);

    // 지난 산행 목록
    $viewData['listBeforeNotice'] = $this->notice_model->listBeforeNotice($now);

    $this->_viewPage('index', $viewData);
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
    $this->load->view('header', $headerData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('footer');
  }
}
?>
