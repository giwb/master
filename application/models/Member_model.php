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
  public function checkLogin($userid)
  {
    $this->db->select('idx, club_idx, userid, password, nickname, realname, gender, birthday, birthday_type, phone, rescount, point, penalty, level, admin, connect')
          ->from(DB_MEMBER)
          ->where('userid', $userid)
          ->where('quitdate', NULL);
    return $this->db->get()->row_array(1);
  }

  // 아이디 찾기
  public function searchId($realname, $gender, $birthday, $phone, $userid=NULL)
  {
    $this->db->select('idx, userid, quitdate')
          ->from(DB_MEMBER)
          ->where('realname', $realname)
          ->where('gender', $gender)
          ->where('birthday', $birthday)
          ->where('phone', $phone);

    if (!is_null($userid)) {
      $this->db->where('userid', $userid);
    }

    return $this->db->get()->row_array(1);
  }

  // 아이디 중복 확인
  public function checkUserid($userid)
  {
    $this->db->select('*')
          ->from(DB_MEMBER)
          ->where('userid', $userid);
    return $this->db->get()->row_array(1);
  }

  // 닉네임 중복 확인
  public function checkNickname($userid=NULL, $nickname)
  {
    $this->db->select('idx')
          ->from(DB_MEMBER)
          ->where('nickname', $nickname);

    if (!is_null($userid)) {
      $this->db->where('userid !=', $userid);
    }

    return $this->db->get()->row_array(1);
  }

  // 전화번호 중복 확인
  public function checkPhone($phone)
  {
    $this->db->select('*')
          ->from(DB_MEMBER)
          ->where('phone', $phone);
    return $this->db->get()->row_array(1);
  }

  // 전화번호 인증 확인
  public function checkPhoneAuth($phone, $auth_code=NULL)
  {
    $this->db->select('idx, created_at')
          ->from(DB_MEMBER_SMS_AUTH)
          ->where('phone_number', $phone)
          ->where('deleted_at', NULL);

    if (!empty($auth_code)) {
      $this->db->where('auth_code', $auth_code);
    }

    return $this->db->get()->row_array(1);
  }

  // 전화번호 인증 등록
  public function insertPhoneAuth($data)
  {
    $this->db->insert(DB_MEMBER_SMS_AUTH, $data);
    return $this->db->insert_id();
  }

  // 전화번호 인증 삭제
  public function deletePhoneAuth($data, $idx)
  {
    $this->db->set($data);
    $this->db->where('idx', $idx);
    return $this->db->update(DB_MEMBER_SMS_AUTH);
  }

  // 회원 정보
  public function viewMember($userIdx)
  {
    $this->db->select('*')
          ->from(DB_MEMBER)
          ->where('idx', $userIdx)
          ->where('quitdate', NULL);
    return $this->db->get()->row_array(1);
  }

  // 회원 등록
  public function insertMember($data)
  {
    $this->db->insert(DB_MEMBER, $data);
    return $this->db->insert_id();
  }

  // 개인정보 수정
  public function updateMember($data, $userIdx)
  {
    $this->db->set($data);
    $this->db->where('idx', $userIdx);
    return $this->db->update(DB_MEMBER);
  }

  // 로그 기록
  public function insertHistory($data)
  {
    $this->db->insert(DB_HISTORY, $data);
    return $this->db->insert_id();
  }

  // 사용자 포인트 기록
  public function userPointLog($clubIdx, $userIdx, $paging=NULL)
  {
    $this->db->select('*')
          ->from(DB_HISTORY)
          ->where('club_idx', $clubIdx)
          ->where('user_idx', $userIdx)
          ->where_in('action', array(LOG_POINTUP, LOG_POINTDN))
          ->order_by('regdate', 'desc');

    if (!empty($paging)) {
      $this->db->limit($paging['perPage'], $paging['nowPage']);
    }

    return $this->db->get()->result_array();
  }

  // 사용자 포인트 카운트
  public function maxPointLog($clubIdx, $userIdx)
  {
    $this->db->select('COUNT(*) AS cnt')
          ->from(DB_HISTORY)
          ->where('club_idx', $clubIdx)
          ->where('user_idx', $userIdx)
          ->where_in('action', array(LOG_POINTUP, LOG_POINTDN));
    return $this->db->get()->row_array(1);
  }

  // 사용자 페널티 기록
  public function userPenaltyLog($clubIdx, $userIdx, $paging=NULL)
  {
    $this->db->select('*')
          ->from(DB_HISTORY)
          ->where('club_idx', $clubIdx)
          ->where('user_idx', $userIdx)
          ->where_in('action', array(LOG_PENALTYUP, LOG_PENALTYDN))
          ->order_by('regdate', 'desc');

    if (!empty($paging)) {
      $this->db->limit($paging['perPage'], $paging['nowPage']);
    }

    return $this->db->get()->result_array();
  }

  // 사용자 페널티 카운트
  public function maxPenaltyLog($clubIdx, $userIdx)
  {
    $this->db->select('COUNT(*) AS cnt')
          ->from(DB_HISTORY)
          ->where('club_idx', $clubIdx)
          ->where('user_idx', $userIdx)
          ->where_in('action', array(LOG_PENALTYUP, LOG_PENALTYDN));
    return $this->db->get()->row_array(1);
  }

  // 포인트 수정
  public function updatePoint($clubIdx, $userIdx, $point)
  {
    $this->db->set('point', $point);
    $this->db->where('club_idx', $clubIdx);
    $this->db->where('idx', $userIdx);
    $this->db->update(DB_MEMBER);
  }

  // 페널티 수정
  public function updatePenalty($clubIdx, $userIdx, $penalty)
  {
    $this->db->set('penalty', $penalty);
    $this->db->where('club_idx', $clubIdx);
    $this->db->where('idx', $userIdx);
    $this->db->update(DB_MEMBER);
  }

  // 회원수
  public function cntMember($clubIdx)
  {
    $this->db->select('COUNT(idx) AS cnt')
          ->from(DB_MEMBER)
          ->where('club_idx', $clubIdx)
          ->where('quitdate', NULL);
    return $this->db->get()->row_array(1);
  }

  // 오늘 회원수
  public function cntMemberToday($clubIdx)
  {
    $this->db->select('COUNT(idx) AS cnt')
          ->from(DB_MEMBER)
          ->where('club_idx', $clubIdx)
          ->where('FROM_UNIXTIME(regdate, "%Y%m%d") =', date('Ymd'))
          ->where('quitdate', NULL);
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
          ->where('FROM_UNIXTIME(created_at, "%Y%m%d") =', date('Ymd'));
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

  // 방문자 수정
  public function updateVisitor($data, $idx)
  {
    $this->db->set($data);
    $this->db->where('idx', $idx);
    return $this->db->update(DB_VISITOR);
  }

  // OAuth User Check
  public function checkOAuthUser($provider, $email)
  {
    $this->db->select('*')
          ->from(DB_MEMBER)
          ->where('provider', $provider)
          ->where('email', $email)
          ->where('quitdate', NULL);
    return $this->db->get()->row_array(1);
  }
}
?>
