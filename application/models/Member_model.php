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
  public function checkLogin($userid, $password, $club_idx)
  {
    $this->db->select('idx, userid, nickname, realname, gender, birthday, birthday_type, phone, admin')
          ->from(DB_MEMBER)
          ->where('userid', $userid)
          ->where('password', $password)
          ->where('club_idx', $club_idx);
    return $this->db->get()->row_array(1);
  }

  // 아이디 중복 확인
  public function checkUserid($userid, $club_idx)
  {
    $this->db->select('idx')
          ->from(DB_MEMBER)
          ->where('userid', $userid)
          ->where('club_idx', $club_idx);
    return $this->db->get()->row_array(1);
  }

  // 닉네임 중복 확인
  public function checkNickname($nickname, $club_idx)
  {
    $this->db->select('idx')
          ->from(DB_MEMBER)
          ->where('nickname', $nickname)
          ->where('club_idx', $club_idx);
    return $this->db->get()->row_array(1);
  }

  // 회원 정보
  public function viewMember($idx)
  {
    $this->db->select('*')
          ->from(DB_MEMBER)
          ->where('idx', $idx);
    return $this->db->get()->row_array(1);
  }

  // 회원 등록
  public function insertMember($data)
  {
    $this->db->insert(DB_MEMBER, $data);
    return $this->db->insert_id();
  }
}
?>
