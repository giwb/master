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
  public function checkLogin($userid, $password, $clubIdx)
  {
    $this->db->select('idx, userid, nickname, realname, gender, birthday, birthday_type, phone, admin')
          ->from(DB_MEMBER)
          ->where('userid', $userid)
          ->where('password', $password)
          ->where('club_idx', $clubIdx);
    return $this->db->get()->row_array(1);
  }

  // 아이디 중복 확인
  public function checkUserid($userid, $clubIdx)
  {
    $this->db->select('*')
          ->from(DB_MEMBER)
          ->where('club_idx', $clubIdx)
          ->where('userid', $userid);
    return $this->db->get()->row_array(1);
  }

  // 닉네임 중복 확인
  public function checkNickname($nickname, $clubIdx)
  {
    $this->db->select('idx')
          ->from(DB_MEMBER)
          ->where('nickname', $nickname)
          ->where('club_idx', $clubIdx);
    return $this->db->get()->row_array(1);
  }

  // 총 예약 횟수
  public function cntReserve($userData, $status, $group=NULL)
  {
    $this->db->select('COUNT(idx) AS cntReserve')
          ->from(DB_RESERVATION)
          ->where('club_idx', $userData['club_idx'])
          ->where('userid', $userData['userid'])
          ->where('status', $status);

    if (!is_null($group)) {
      $this->db->group_by('rescode');
    }

    return $this->db->get()->row_array(1);
  }

  // 회원 정보
  public function viewMember($clubIdx, $memberIdx)
  {
    $this->db->select('*')
          ->from(DB_MEMBER)
          ->where('club_idx', $clubIdx)
          ->where('idx', $memberIdx);
    return $this->db->get()->row_array(1);
  }

  // 회원 등록
  public function insertMember($data)
  {
    $this->db->insert(DB_MEMBER, $data);
    return $this->db->insert_id();
  }

  // 로그 기록
  public function insertHistory($data)
  {
    $this->db->insert(DB_HISTORY, $data);
    return $this->db->insert_id();
  }
}
?>
