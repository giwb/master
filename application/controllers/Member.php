<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 회원 페이지 클래스
class Member extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('url', 'my_array_helper'));
    $this->load->library('session');
    $this->load->model('member_model');
  }

  /**
   * 마이페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function index()
  {
    // 회원 정보
    $memberIdx = html_escape($this->session->userData['idx']);
    $viewData['viewMember'] = $this->member_model->viewMember($memberIdx);

    $this->_viewPage('member/index', $viewData);
  }

  /**
   * 로그인
   *
   * @param $userid
   * @param $password
   * @return json
   * @author bjchoi
   **/
  public function login()
  {
    $userid = html_escape($this->input->post('userid'));
    $password = html_escape($this->input->post('password'));

    $result = array(
      'error' => 1,
      'message' => '로그인에 실패했습니다. 다시 로그인 해주세요.'
    );

    if ($userid != '' || $password != '') {
      $userData = $this->member_model->checkLogin($userid, md5($password));

      if ($userData['idx'] != '') {
        $this->session->set_userdata('userData', $userData);

        $result = array(
          'error' => 0,
          'message' => ''
        );
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 로그아웃
   *
   * @return json
   * @author bjchoi
   **/
  public function logout()
  {
    $this->session->unset_userdata('userData');
    $this->output->set_output(0);
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
    $this->load->view('member/header', $headerData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('member/footer');
  }
}
?>
