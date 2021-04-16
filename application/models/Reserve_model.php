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
  public function listNotice($clubIdx=NULL, $status=NULL, $order='asc', $searchData=NULL, $limit=NULL)
  {
    $this->db->select('*')
          ->from(DB_NOTICE)
          ->where('visible', VISIBLE_ABLE)
          ->order_by('startdate', $order);

    if (!empty($clubIdx)) {
      $this->db->where('club_idx', $clubIdx);
    }
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
  public function viewNotice($noticeIdx)
  {
    $this->db->select('*')
          ->from(DB_NOTICE)
          ->where('idx', $noticeIdx)
          ->where('visible', VISIBLE_ABLE);
    return $this->db->get()->row_array(1);
  }

  // 선택된 산행 예약 목록
  public function viewProgress($noticeIdx)
  {
    $this->db->select('*')
          ->from(DB_RESERVATION)
          ->where('rescode', $noticeIdx);
    return $this->db->get()->result_array();
  }

  // 회원 예약횟수
  public function cntMemberReserve($userIdx)
  {
    $this->db->select('a.rescode')
          ->from(DB_RESERVATION . ' a')
          ->join(DB_NOTICE . ' b', 'a.rescode=b.idx', 'left')
          ->where('a.user_idx', $userIdx)
          ->where('a.status', RESERVE_PAY)
          ->where('b.status', STATUS_CLOSED)
          ->group_by('a.rescode');
    return $this->db->get()->result_array();
  }

  // 산행 예약자 카운트
  public function cntReserve($noticeIdx, $bus=NULL)
  {
    $this->db->select('COUNT(*) AS cnt')
          ->from(DB_RESERVATION)
          ->where('rescode', $noticeIdx)
          ->where('manager', 0)
          ->where_not_in('nickname', array('1인우등', '2인우선'));

    if (!empty($bus)) {
      $this->db->where('bus', $bus);
    }

    return $this->db->get()->row_array(1);
  }

  // 산행 예약자 중 1인우등 예약자 카운트
  public function cntReserveHonor($noticeIdx, $bus=NULL)
  {
    $this->db->select('COUNT(*) AS cnt')
          ->from(DB_RESERVATION)
          ->where('rescode', $noticeIdx)
          ->where('nickname !=', '1인우등')
          ->where('honor >', 0);

    if (!is_null($bus)) {
      $this->db->where('bus', $bus);
    }

    return $this->db->get()->row_array(1);
  }

  // 마이페이지 사용자 예약 내역
  public function userReserve($clubIdx, $userIdx=NULL, $idx=NULL, $paging=NULL)
  {
    $this->db->select('a.*, b.idx as resCode, b.subject, b.startdate, b.starttime, b.cost, b.cost_total, b.bus AS notice_bus, b.bustype AS notice_bustype, b.status AS notice_status')
          ->from(DB_RESERVATION . ' a')
          ->join(DB_NOTICE . ' b', 'a.rescode=b.idx', 'left')
          ->where('a.club_idx', $clubIdx)
          ->where('b.visible', VISIBLE_ABLE);

    if (!empty($paging)) {
      $this->db->limit($paging['perPage'], $paging['nowPage']);
    } else {
      $this->db->limit(5);
    }
    if (!empty($userIdx)) {
      $this->db->where('a.user_idx', $userIdx)
            ->where('a.status <=', 7)
            ->where('b.status <=', 7)
            ->order_by('b.idx', 'desc')
            ->order_by('a.seat', 'asc');
      return $this->db->get()->result_array();
    }
    if (!empty($idx)) {
      $this->db->where('a.idx', $idx);
      return $this->db->get()->row_array(1);
    }
  }

  // 마이페이지 사용자 예약 카운트
  public function maxReserve($clubIdx, $userIdx=NULL)
  {
    $this->db->select('COUNT(*) AS cnt')
          ->from(DB_RESERVATION . ' a')
          ->join(DB_NOTICE . ' b', 'a.rescode=b.idx', 'left')
          ->where('a.club_idx', $clubIdx)
          ->where('a.user_idx', $userIdx)
          ->where('a.status <=', 7)
          ->where('b.status <=', 7)
          ->where('b.visible', VISIBLE_ABLE);
    return $this->db->get()->row_array(1);
  }

  // 마이페이지 사용자 예약취소 내역
  public function userReserveCancel($clubIdx, $userIdx, $paging=NULL)
  {
    $this->db->select('a.*, b.idx as resCode, b.subject, b.startdate, b.starttime, b.cost, b.cost_total, b.bus AS notice_bus, b.bustype AS notice_bustype, b.status AS notice_status')
          ->from(DB_HISTORY . ' a')
          ->join(DB_NOTICE . ' b', 'a.fkey=b.idx', 'left')
          ->where('a.club_idx', $clubIdx)
          ->where('a.user_idx', $userIdx)
          ->where_in('a.action', array(LOG_CANCEL, LOG_ADMIN_CANCEL))
          ->where('b.visible', VISIBLE_ABLE)
          ->order_by('a.idx', 'desc');

    if (!empty($paging)) {
      $this->db->limit($paging['perPage'], $paging['nowPage']);
    } else {
      $this->db->limit(5);
    }

    return $this->db->get()->result_array();
  }

  // 마이페이지 사용자 예약취소 카운트
  public function maxReserveCancel($clubIdx, $userIdx)
  {
    $this->db->select('COUNT(*) AS cnt')
          ->from(DB_HISTORY . ' a')
          ->join(DB_NOTICE . ' b', 'a.fkey=b.idx', 'left')
          ->where('a.club_idx', $clubIdx)
          ->where('a.user_idx', $userIdx)
          ->where_in('a.action', array(LOG_CANCEL, LOG_ADMIN_CANCEL))
          ->where('b.visible', VISIBLE_ABLE);
    return $this->db->get()->row_array(1);
  }

  // 마이페이지 사용자 산행 내역
  public function userVisit($clubIdx, $userIdx, $paging=NULL)
  {
    $this->db->select('a.*, b.idx as resCode, b.subject, b.startdate, b.starttime, b.cost, b.cost_total, b.bus AS notice_bus, b.bustype AS notice_bustype, b.status AS notice_status')
          ->from(DB_RESERVATION . ' a')
          ->join(DB_NOTICE . ' b', 'a.rescode=b.idx', 'left')
          ->where('a.club_idx', $clubIdx)
          ->where('a.user_idx', $userIdx)
          ->where('b.status', STATUS_CLOSED)
          ->order_by('b.startdate', 'desc');

    if (!empty($paging)) {
      $this->db->limit($paging['perPage'], $paging['nowPage']);
    } else {
      $this->db->limit(5);
    }

    return $this->db->get()->result_array();
  }

  // 마이페이지 사용자 산행 카운트
  public function maxVisit($clubIdx, $userIdx)
  {
    $this->db->select('COUNT(*) AS cnt')
          ->from(DB_RESERVATION . ' a')
          ->join(DB_NOTICE . ' b', 'a.rescode=b.idx', 'left')
          ->where('a.club_idx', $clubIdx)
          ->where('a.user_idx', $userIdx)
          ->where('b.status', STATUS_CLOSED);
    return $this->db->get()->row_array(1);
  }

  // 마이페이지 사용자 산행 횟수
  public function userVisitCount($clubIdx, $userIdx)
  {
    $this->db->select('b.idx')
          ->from(DB_RESERVATION . ' a')
          ->join(DB_NOTICE . ' b', 'a.rescode=b.idx', 'left')
          ->where('a.club_idx', $clubIdx)
          ->where('a.user_idx', $userIdx)
          ->where('b.status', STATUS_CLOSED)
          ->group_by('b.idx');
    return $this->db->get()->result_array();
  }

  // 산행 정보 수정
  public function updateNotice($data, $noticeIdx)
  {
    $this->db->set($data);
    $this->db->where('idx', $noticeIdx);
    return $this->db->update(DB_NOTICE);
  }

  // 예약 목록
  public function listReserve($clubIdx, $idx)
  {
    $this->db->select('a.*, b.idx as resCode, b.subject, b.startdate, b.starttime, b.cost, b.cost_total, b.bus AS notice_bus, b.bustype AS notice_bustype, b.status AS notice_status')
          ->from(DB_RESERVATION . ' a')
          ->join(DB_NOTICE . ' b', 'a.rescode=b.idx', 'left')
          ->where('a.club_idx', $clubIdx)
          ->where('b.visible', VISIBLE_ABLE)
          ->where_in('a.idx', $idx);
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
  public function checkReserve($noticeIdx, $bus, $seat)
  {
    $this->db->select('*')
          ->from(DB_RESERVATION)
          ->where('rescode', $noticeIdx)
          ->where('bus', $bus)
          ->where('seat', $seat);
    return $this->db->get()->row_array(1);
  }

  // 대기자 카운트
  public function cntReserveWait($noticeIdx)
  {
    $this->db->select('COUNT(created_at) as cnt')
          ->from(DB_WAIT)
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

  // 차종 목록
  public function listBustype()
  {
    $this->db->select('a.idx, a.bus_name, a.bus_owner, a.created_at, b.name AS bus_seat_name')
          ->from(DB_BUSTYPE . ' a')
          ->join(DB_BUSDATA . ' b', 'a.bus_seat=b.idx')
          ->where('visible', 'Y')
          ->order_by('idx', 'asc');
    return $this->db->get()->result_array();
  }

  // 차종 상세
  public function viewBustype($idx)
  {
    $this->db->select('a.bus_name, a.bus_owner, a.created_at, b.name AS bus_seat_name')
          ->from(DB_BUSTYPE . ' a')
          ->join(DB_BUSDATA . ' b', 'a.bus_seat=b.idx')
          ->where('a.idx', $idx);
    return $this->db->get()->row_array(1);
  }

  // 산행 공지사항 목록
  public function listNoticeDetail($noticeIdx)
  {
    $this->db->select('idx, title, content')
          ->from(DB_NOTICE_DETAIL)
          ->where('notice_idx', $noticeIdx)
          ->where('deleted_at', NULL)
          ->order_by('sort_idx', 'asc');
    return $this->db->get()->result_array();
  }
}
?>
