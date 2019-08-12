<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 관리 페이지 모델
class Admin_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  // 현재 회원수
  public function cntTotalMember()
  {
    $this->db->select('COUNT(idx) AS CNT')->from(DB_MEMBER);
    return $this->db->get()->row_array(1);
  }

  // 다녀온 산행 횟수
  public function cntTotalTour()
  {
    $this->db->select('COUNT(idx) AS CNT')
          ->from(DB_NOTICE)
          ->where('status', 9);
    return $this->db->get()->row_array(1);
  }

  // 다녀온 산행 인원수
  public function cntTotalCustomer()
  {
    $this->db->select('COUNT(b.idx) AS CNT')
          ->from(DB_NOTICE . ' a')
          ->from(DB_RESERVATION . ' b')
          ->where('a.idx=b.rescode')
          ->where('a.status', 9)
          ->where('b.status', 1);
    return $this->db->get()->row_array(1);
  }

  // 산행 예약자 카운트
  public function cntReservation($idx)
  {
    $this->db->select('COUNT(idx) AS CNT')
          ->from(DB_RESERVATION)
          ->where('rescode', $idx)
          ->where('manager !=', 1)
          ->where('priority', 0);
    return $this->db->get()->row_array(1);
  }

  // 이번 달 산행 목록
  public function listNotice()
  {
    $this->db->select('*')
          ->from(DB_NOTICE)
/*          ->where("DATE_FORMAT(startdate, '%Y%m') = '" . $syear . $smonth . "'")*/
          ->order_by('startdate', 'asc');
    return $this->db->get()->result_array();
  }

  // 진행중 산행 목록
  public function listProgress()
  {
    $this->db->select('*')
          ->from(DB_NOTICE)
          ->where('status <=', 7)
          ->order_by('startdate', 'asc');
    return $this->db->get()->result_array();
  }

  // 진행중 산행 예약 보기
  public function viewProgress($rescode)
  {
    $this->db->select('*')
          ->from(DB_RESERVATION)
          ->where('rescode', $rescode);
    return $this->db->get()->result_array();
  }

  // 다녀온 산행 목록, 취소된 산행 목록
  public function listClosed($syear, $smonth, $status)
  {
    $this->db->select('*')
          ->from(DB_NOTICE)
          ->where('status', $status)
          ->where("DATE_FORMAT(startdate, '%Y%m') <= '" . $syear . $smonth . "'")
          ->order_by('startdate', 'desc');
    return $this->db->get()->result_array();
  }

  // 전체 회원 목록
  public function listMembers()
  {
    $this->db->select('*')
          ->from(DB_MEMBER)
          ->order_by('regdate', 'desc');
    return $this->db->get()->result_array();
  }

  // 회원 정보
  public function viewMember($idx)
  {
    $this->db->select('*')
          ->from(DB_MEMBER)
          ->where('idx', $idx);
    return $this->db->get()->row_array(1);
  }

  // 산행횟수
  public function cntPersonalReservation($userid)
  {
    $this->db->select('COUNT(b.idx) AS cnt')
          ->from(DB_RESERVATION . ' a')
          ->join(DB_NOTICE . ' b', 'a.rescode=b.idx', 'left')
          ->where('a.userid', $userid)
          ->where('a.status', 1)
          ->where('b.status', 9)
          ->group_by('b.idx');
    return $this->db->get()->result_array();
  }

  // 출석체크 - 산행
  public function listAttendanceNotice($dateStart, $dateEnd)
  {
    $this->db->select('*')
          ->from(DB_NOTICE)
          ->where('status', STATUS_CLOSED)
          ->where('startdate >=', $dateStart)
          ->where('startdate <=', $dateEnd)
          ->order_by('startdate', 'asc');
    return $this->db->get()->result_array();
  }

  // 출석체크 - 랭킹
  public function listAttendanceNickname()
  {
    $this->db->select('nickname, count(idx) AS cnt')
          ->from(DB_ATTENDANCE)
          ->group_by('nickname')
          ->order_by('cnt', 'desc')
          ->order_by('nickname', 'asc')
          ->limit(100);
    return $this->db->get()->result_array();
  }

  // 출석체크 - 랭킹별 산행 확인
  public function checkAttendance($rescode, $nickname)
  {
    $this->db->select('idx')
          ->from(DB_ATTENDANCE)
          ->where('rescode', $rescode)
          ->where('nickname', $nickname);
    return $this->db->get()->row_array(1);
  }

  // 출석체크 - 추출
  public function getAttendanceNickname($idx)
  {
    $this->db->select('a.nickname')
          ->from(DB_RESERVATION . ' a')
          ->join(DB_NOTICE . ' b', 'a.rescode=b.idx', 'left')
          ->where('b.idx', $idx)
          ->order_by('a.nickname', 'asc');
    return $this->db->get()->result_array();
  }

  // 출석체크 - 입력
  public function insertAttendance($data)
  {
    $this->db->insert(DB_ATTENDANCE, $data);
    return $this->db->insert_id();
  }

  // 출석체크 - 삭제
  public function deleteAttendance()
  {
    $this->db->empty_table(DB_ATTENDANCE);
  }

  // 대문관리 - 목록
  public function listFront()
  {
    $this->db->select('sort_idx, filename')
          ->from(DB_FRONT)
          ->order_by('sort_idx', 'asc');
    return $this->db->get()->result_array();
  }

  // 대문관리 - 정렬 최대값 가져오기
  public function getFrontSortMaxIdx()
  {
    $this->db->select('MAX(sort_idx) AS sort_idx')
          ->from(DB_FRONT);
    return $this->db->get()->row_array(1);
  }

  // 대문관리 - 등록
  public function insertFront($data)
  {
    $this->db->insert(DB_FRONT, $data);
    return $this->db->insert_id();
  }

  // 대문관리 - 정렬 순서 갱신
  public function updateFrontSortIdx($filename, $sort_idx)
  {
    $this->db->set('sort_idx', $sort_idx);
    $this->db->where('filename', $filename);
    return $this->db->update(DB_FRONT);
  }

  // 대문관리 - 삭제
  public function deleteFront($filename)
  {
    $this->db->where('filename', $filename);
    return $this->db->delete(DB_FRONT);
  }
}
?>
