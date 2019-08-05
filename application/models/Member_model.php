<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 회원 모델
class Member_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  // 로그인 확인
  public function checkLogin($userid, $password)
  {
    $this->db->select('idx, userid, nickname, realname, gender, birthday, birthday_type, phone, admin')
          ->from(DB_MEMBER)
          ->where('userid', $userid)
          ->where('password', $password);
    return $this->db->get()->row_array(1);
  }
}
?>
