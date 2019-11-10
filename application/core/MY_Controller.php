<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('cookie', 'security', 'url', 'my_array_helper'));
    $this->load->library('session');
    $this->load->model('member_model');

    // 클럽 설정
    $loginData['clubIdx'] = 1; // 최초는 경인웰빙
    if (!empty($_SERVER['REDIRECT_URL'])) {
      $arrUrl = explode('/', $_SERVER['REDIRECT_URL']);
      $clubIdx = array_pop($arrUrl);

      if (is_numeric($clubIdx)) {
        $loginData['clubIdx'] = html_escape($clubIdx);
      }
    }

    // 회원 로그인 설정
    if (!empty($this->session->userData['idx'])) {
      $loginData['userData'] = $this->member_model->viewMember($loginData['clubIdx'], html_escape($this->session->userData['idx']));
      $loginData['userLevel'] = memberLevel($loginData['userData']);
    }

    $this->load->vars($loginData);
  }
}
