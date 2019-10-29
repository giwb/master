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
    $this->load->model(array('club_model', 'member_model', 'notice_model'));
  }

  /**
   * 톱 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function index($idx=NULL)
  {
    if (is_null($idx)) {
      $idx = 1; // 최초는 경인웰빙
    }

    $viewData['view'] = $this->club_model->viewClub($idx);

    $this->_viewPage('reservation/index', $viewData);
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
    $headerData['uri'] = 'reservation';
    $this->load->view('header', $headerData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('footer');
  }
}
?>
