<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 첫 페이지 클래스
class Welcome extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('url', 'my_array_helper'));
    $this->load->model(array('admin_model'));
  }

  public function index()
  {
    // PHP Ver 7.x
    //$syear = !empty($this->input->get('syear')) ? $this->input->get('syear') : date('Y');
    //$smonth = !empty($this->input->get('smonth')) ? $this->input->get('syear') : date('m');

    // PHP Ver 5.x
    $syear = $this->input->get('syear') ? $this->input->get('syear') : date('Y');
    $smonth = $this->input->get('smonth') ? $this->input->get('syear') : date('m');

    // 이번 달 산행 목록
    $viewData['listMonthNotice'] = $this->admin_model->listMonthNotice($syear, $smonth);

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
    $this->load->view('header');
    $this->load->view($viewPage, $viewData);
    $this->load->view('footer');
  }
}
?>