<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 로그인 클래스
class Login extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $this->load->view('login');
  }
}
?>