<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 산행예약 모델
class Reserve_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  // 등록된 산행 목록
  public function listNotice($clubIdx, $status=NULL, $order='asc', $searchData=NULL)
  {
    $this->db->select('*')
          ->from(DB_NOTICE)
          ->where('club_idx', $clubIdx)
          ->where('visible', VISIBLE_ABLE)
          ->order_by('startdate', $order);

    if (!empty($searchData['sdate'])) {
      $this->db->where('startdate >=', $searchData['sdate']);
    }

    if (!empty($searchData['edate'])) {
      $this->db->where('startdate <=', $searchData['edate']);
    }

    if (!empty($searchData['keyword'])) {
      $this->db->like('subject', $searchData['keyword']);
    }

    if (!empty($status)) {
      $this->db->where_in('status', $status);
    }

    return $this->db->get()->result_array();
  }

  // 등록된 산행 상세 정보
  public function viewNotice($clubIdx, $noticeIdx)
  {
    $this->db->select('*')
          ->from(DB_NOTICE)
          ->where('club_idx', $clubIdx)
          ->where('visible', VISIBLE_ABLE)
          ->where('idx', $noticeIdx);
    return $this->db->get()->row_array(1);
  }

  // 선택된 산행 예약 목록
  public function viewProgress($clubIdx, $noticeIdx)
  {
    $this->db->select('*')
          ->from(DB_RESERVATION)
          ->where('club_idx', $clubIdx)
          ->where('rescode', $noticeIdx);
    return $this->db->get()->result_array();
  }

  // 산행 예약자 카운트
  public function cntReserve($clubIdx, $noticeIdx, $bus=NULL)
  {
    $this->db->select('COUNT(*) AS cnt')
          ->from(DB_RESERVATION)
          ->where('club_idx', $clubIdx)
          ->where('rescode', $noticeIdx);

    if (!empty($bus)) {
      $this->db->where('bus', $bus);
    }

    return $this->db->get()->row_array(1);
  }

  // 마이페이지 사용자 예약 내역
  public function userReserve($clubIdx, $userId=NULL, $idx=NULL)
  {
    $this->db->select('a.*, b.idx as resCode, b.subject, b.startdate, b.starttime, b.cost, b.cost_total, b.bus AS notice_bus, b.bustype AS notice_bustype, b.status AS notice_status')
          ->from(DB_RESERVATION . ' a')
          ->join(DB_NOTICE . ' b', 'a.rescode=b.idx', 'left')
          ->where('a.club_idx', $clubIdx)
          ->where('b.visible', VISIBLE_ABLE);

    if (!empty($userId)) {
      $this->db->where('a.userid', $userId)
            ->where('a.status <=', 7)
            ->where('b.status <=', 7)
            ->order_by('b.idx', 'desc')
            ->order_by('a.seat', 'asc');
      return $this->db->get()->result_array();
    };
    if (!empty($idx)) {
      $this->db->where('a.idx', $idx);
      return $this->db->get()->row_array(1);
    };
  }

  // 마이페이지 사용자 산행 내역
  public function userVisit($clubIdx, $userId)
  {
    $this->db->select('a.*, b.idx as resCode, b.subject, b.startdate, b.starttime, b.cost, b.cost_total, b.bus AS notice_bus, b.bustype AS notice_bustype, b.status AS notice_status')
          ->from(DB_RESERVATION . ' a')
          ->join(DB_NOTICE . ' b', 'a.rescode=b.idx', 'left')
          ->where('a.club_idx', $clubIdx)
          ->where('a.userid', $userId)
          ->where('a.status', STATUS_ABLE)
          ->where('b.status', STATUS_CLOSED)
          ->order_by('b.idx', 'desc')
          ->limit(5);
    return $this->db->get()->result_array();
  }

  // 마이페이지 사용자 산행 횟수
  public function userVisitCount($clubIdx, $userId)
  {
    $this->db->select('b.idx')
          ->from(DB_RESERVATION . ' a')
          ->join(DB_NOTICE . ' b', 'a.rescode=b.idx', 'left')
          ->where('a.club_idx', $clubIdx)
          ->where('a.userid', $userId)
          ->where('a.status', STATUS_ABLE)
          ->where('b.status', STATUS_CLOSED)
          ->group_by('b.idx');
    return $this->db->get()->result_array();
  }

  // 예약 정보 보기
  public function viewReserve($clubIdx, $idx)
  {
    $this->db->select('*')
          ->from(DB_RESERVATION)
          ->where('club_idx', $clubIdx)
          ->where('idx', $idx);
    return $this->db->get()->row_array(1);
  }

  // 예약 정보 등록
  public function insertReserve($data)
  {
    $this->db->insert(DB_RESERVATION, $data);
    return $this->db->insert_id();
  }

  // 예약 정보 수정
  public function updateReserve($data, $reserveIdx)
  {
    $this->db->set($data);
    $this->db->where('idx', $reserveIdx);
    return $this->db->update(DB_RESERVATION);
  }

  // 예약 취소
  public function deleteReserve($idx)
  {
    $this->db->where('idx', $idx);
    return $this->db->delete(DB_RESERVATION);
  }

  // 좌석 예약 확인
  public function checkReserve($clubIdx, $noticeIdx, $bus, $seat)
  {
    $this->db->select('idx')
          ->from(DB_RESERVATION)
          ->where('club_idx', $clubIdx)
          ->where('rescode', $noticeIdx)
          ->where('bus', $bus)
          ->where('seat', $seat);
    return $this->db->get()->row_array(1);
  }

  // 대기자 카운트
  public function cntReserveWait($clubIdx, $noticeIdx)
  {
    $this->db->select('COUNT(created_at) as cnt')
          ->from(DB_WAIT)
          ->where('club_idx', $clubIdx)
          ->where('notice_idx', $noticeIdx);
    return $this->db->get()->row_array(1);
  }

  // 대기자 확인
  public function viewReserveWait($clubIdx, $noticeIdx, $userIdx)
  {
    $this->db->select('created_at')
          ->from(DB_WAIT)
          ->where('club_idx', $clubIdx)
          ->where('notice_idx', $noticeIdx)
          ->where('created_by', $userIdx);
    return $this->db->get()->row_array(1);
  }

  // 대기자 등록
  public function insertReserveWait($data)
  {
    return $this->db->insert(DB_WAIT, $data);
  }

  // 대기자 삭제
  public function deleteReserveWait($clubIdx, $noticeIdx, $userIdx)
  {
    $this->db->where('club_idx', $clubIdx);
    $this->db->where('notice_idx', $noticeIdx);
    $this->db->where('created_by', $userIdx);
    return $this->db->delete(DB_WAIT);
  }
}
?>
