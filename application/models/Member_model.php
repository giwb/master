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
  public function viewMember($clubIdx, $userIdx)
  {
    $this->db->select('*')
          ->from(DB_MEMBER)
          ->where('club_idx', $clubIdx)
          ->where('idx', $userIdx);
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

  // 사용자 포인트 기록
  public function userPointLog($clubIdx, $userId)
  {
    $this->db->select('*')
          ->from(DB_HISTORY)
          ->where('club_idx', $clubIdx)
          ->where('userid', $userId)
          ->where_in('action', array(LOG_POINTUP, LOG_POINTDN))
          ->order_by('regdate', 'desc')
          ->limit(5);
    return $this->db->get()->result_array();
  }

  // 사용자 페널티 기록
  public function userPenaltyLog($clubIdx, $userId)
  {
    $this->db->select('*')
          ->from(DB_HISTORY)
          ->where('club_idx', $clubIdx)
          ->where('userid', $userId)
          ->where_in('action', array(LOG_PENALTYUP, LOG_PENALTYDN))
          ->order_by('regdate', 'desc')
          ->limit(5);
    return $this->db->get()->result_array();
  }

  // 포인트 수정
  public function updatePoint($clubIdx, $userId, $point)
  {
    $this->db->set('point', $point);
    $this->db->where('club_idx', $clubIdx);
    $this->db->where('userid', $userId);
    $this->db->update(DB_MEMBER);
  }

  // 페널티 수정
  public function updatePenalty($clubIdx, $userId, $penalty)
  {
    $this->db->set('penalty', $penalty);
    $this->db->where('club_idx', $clubIdx);
    $this->db->where('userid', $userId);
    $this->db->update(DB_MEMBER);
  }

  // 회원수
  public function cntMember($clubIdx)
  {
    $this->db->select('COUNT(idx) AS cnt')
          ->from(DB_MEMBER)
          ->where('club_idx', $clubIdx);
    return $this->db->get()->row_array(1);
  }

  // 오늘 회원수
  public function cntMemberToday($clubIdx)
  {
    $this->db->select('COUNT(idx) AS cnt')
          ->from(DB_MEMBER)
          ->where('club_idx', $clubIdx)
          ->where('FROM_UNIXTIME(regdate, "%Y%m%d") =', date('Ymd', time()));
    return $this->db->get()->row_array(1);
  }

  // 방문자수
  public function cntVisitor($clubIdx)
  {
    $this->db->select('COUNT(idx) AS cnt')
          ->from(DB_VISITOR)
          ->where('club_idx', $clubIdx);
    return $this->db->get()->row_array(1);
  }

  // 오늘 방문자수
  public function cntVisitorToday($clubIdx)
  {
    $this->db->select('COUNT(idx) AS cnt')
          ->from(DB_VISITOR)
          ->where('club_idx', $clubIdx)
          ->where('FROM_UNIXTIME(created_at, "%Y%m%d") =', date('Ymd', time()));
    return $this->db->get()->row_array(1);
  }

  // 해당 사용자의 최근 방문 정보
  public function viewVisitor($clubIdx, $userIdx=NULL, $ipAddress)
  {
    $this->db->select('*')
          ->from(DB_VISITOR)
          ->where('club_idx', $clubIdx)
          ->where('ip_address', $ipAddress)
          ->order_by('idx', 'desc')
          ->limit(1);

    if (!is_null($userIdx)) {
      $this->db->where('created_by', $userIdx);
    }

    return $this->db->get()->row_array(1);
  }

  // 방문자 기록
  public function insertVisitor($data)
  {
    $this->db->insert(DB_VISITOR, $data);
    return $this->db->insert_id();
  }
}
?>
