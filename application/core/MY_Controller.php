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

    // 클럽 도메인 설정
    if ($_SERVER['SERVER_PORT'] == '80') $header = 'http://'; else $header = 'https://';
    define('BASE_URL', $header . $_SERVER['HTTP_HOST']);
    if (!empty($_SERVER['REDIRECT_URL'])) {
      $arrUrl = explode('/', $_SERVER['REDIRECT_URL']);
      $domain = html_escape($arrUrl[1]);
      if (!empty($domain)) {
        define('BASE_URL', $header . $_SERVER['HTTP_HOST'] . '/' . $domain);
      }
    }

    // 회원 로그인 설정
    if (!empty($this->session->userData['idx'])) {
      $loginData['userData'] = $this->member_model->viewMember(html_escape($this->session->userData['idx']));
      $loginData['userLevel'] = memberLevel($loginData['userData']['rescount'], $loginData['userData']['penalty'], $loginData['userData']['level'], $loginData['userData']['admin']);

      if (!empty($loginData['userData']['icon'])) {
        $loginData['userData']['icon'] = $loginData['userData']['icon_thumbnail'];
      } else {
        $loginData['userData']['icon'] = base_url() . PHOTO_URL . $loginData['userData']['idx'];
      }
    }

    if (!empty($loginData)) {
      $this->load->vars($loginData);
    }
  }
}

class Admin_Controller extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('cookie', 'security', 'url', 'my_array_helper'));
    $this->load->library('session');
    $this->load->model('member_model');

    $clubIdx = html_escape($this->session->userData['club_idx']);
    $userIdx = html_escape($this->session->userData['idx']);
    $adminCheck = html_escape($this->session->userData['admin']);

    // 모든 페이지에 로그인 체크
    if (!empty($adminCheck) && ($clubIdx == 1 && $adminCheck == 1)) {
      $loginData['userData'] = $this->member_model->viewMember($userIdx);
    } else {
      redirect(base_url() . 'login/?r=/admin');
    }

    $this->load->vars($loginData);
  }
}
