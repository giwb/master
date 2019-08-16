<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 백산백소 클래스
class Auth extends CI_Controller
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
   * @return view
   * @author bjchoi
   **/
  public function index()
  {
    $viewData = array();

    $this->_viewPage('auth/index', $viewData);
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
    $this->load->view('auth/header', $headerData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('auth/footer');
  }
}
?>
