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
  public function cntReservation($resCode, $bus=NULL)
  {
    $this->db->select('COUNT(idx) AS CNT')
          ->from(DB_RESERVATION)
          ->where('rescode', $resCode)
          ->where('manager !=', 1)
          ->where('priority', 0);

    if (!is_null($bus)) {
      $this->db->where('bus', $bus);
    }

    return $this->db->get()->row_array(1);
  }

  // 산행 목록
  public function listNotice($sdate=NULL, $edate=NULL, $status=NULL)
  {
    $this->db->select('*')
          ->from(DB_NOTICE)
          ->order_by('startdate', 'asc');

    if (!is_null($sdate)) {
      $this->db->where("DATE_FORMAT(startdate, '%m%d') >= '" . $sdate . "'");
    }

    if (!is_null($edate)) {
      if ($edate < $sdate) {
        $this->db->or_where("DATE_FORMAT(startdate, '%m%d') <= '" . $edate . "'");
      } else {
        $this->db->where("DATE_FORMAT(startdate, '%m%d') <= '" . $edate . "'");
      }
    }

    if (!is_null($status)) {
      $this->db->where('status', $status);
    }

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

  // 예약 정보 보기
  public function viewReserve($idx)
  {
    $this->db->select('*')
          ->from(DB_RESERVATION)
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
  public function updateReserve($data, $idx)
  {
    $this->db->set($data);
    $this->db->where('idx', $idx);
    return $this->db->update(DB_RESERVATION);
  }

  // 예약 취소
  public function deleteReserve($idx)
  {
    $this->db->where('idx', $idx);
    return $this->db->delete(DB_RESERVATION);
  }

  // 시간대별 탑승자 보기
  public function listReserveLocation($rescode, $bus, $location)
  {
    $this->db->select('*')
          ->from(DB_RESERVATION)
          ->where('rescode', $rescode)
          ->where('bus', $bus)
          ->where('loc', $location)
          ->where('manager !=', 1)
          ->where('priority !=', 1)
          ->order_by('seat', 'asc');
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

  // 등록된 산행 정보 보기
  public function viewEntry($idx)
  {
    $this->db->select('*')
          ->from(DB_NOTICE)
          ->where('idx', $idx);
    return $this->db->get()->row_array(1);
  }

  // 산행 정보 등록
  public function insertEntry($data)
  {
    $this->db->insert(DB_NOTICE, $data);
    return $this->db->insert_id();
  }

  // 산행 정보 수정
  public function updateEntry($data, $idx)
  {
    $this->db->set($data);
    $this->db->where('idx', $idx);
    return $this->db->update(DB_NOTICE);
  }

  // 산행 정보 삭제
  public function deleteEntry($idx)
  {
    $this->db->where('idx', $idx);
    return $this->db->delete(DB_NOTICE);
  }

  // 문자양식
  public function listSMS($rescode)
  {
    $this->db->select('a.idx, a.nickname, a.bus AS nowbus, a.seat, a.loc, b.subject, b.bus, b.startdate, b.starttime, b.schedule, b.distance')
          ->from(DB_RESERVATION . ' a')
          ->join(DB_NOTICE . ' b', 'a.rescode=b.idx', 'left')
          ->where('a.rescode', $rescode)
          ->where('a.manager !=', 1)
          ->order_by('a.bus', 'ASC')
          ->order_by('a.seat', 'ASC');
    return $this->db->get()->result_array();
  }

  // 전체 회원 목록
  public function listMembers()
  {
    $this->db->select('*')
          ->from(DB_MEMBER)
          ->order_by('idx', 'desc');
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

  // 설정 - 차종 목록
  public function listBustype()
  {
    $this->db->select('a.idx, a.bus_name, a.bus_owner, a.created_at, b.name AS bus_seat_name')
          ->from(DB_BUSTYPE . ' a')
          ->join(DB_BUSDATA . ' b', 'a.bus_seat=b.idx')
          ->order_by('idx', 'asc');
    return $this->db->get()->result_array();
  }

  // 설정 - 차종 정보
  public function getBustype($idx)
  {
    $this->db->select('a.idx, a.bus_name, a.bus_owner, a.bus_seat, b.seat, b.direction')
          ->from(DB_BUSTYPE . ' a')
          ->join(DB_BUSDATA . ' b', 'a.bus_seat=b.idx', 'left')
          ->where('a.idx', $idx);
    return $this->db->get()->row_array(1);
  }

  // 설정 - 차종 추가
  public function insertBustype($data)
  {
    $this->db->insert(DB_BUSTYPE, $data);
    return $this->db->insert_id();
  }

  // 설정 - 차종 수정
  public function updateBustype($data, $idx)
  {
    $this->db->set($data);
    $this->db->where('idx', $idx);
    return $this->db->update(DB_BUSTYPE);
  }

  // 설정 - 차종 삭제
  public function deleteBustype($idx)
  {
    $this->db->where('idx', $idx);
    return $this->db->delete(DB_BUSTYPE);
  }

  // 설정 - 등록된 차량 데이터
  public function listBusdata()
  {
    $this->db->select('*')
          ->from(DB_BUSDATA)
          ->order_by('idx', 'asc');
    return $this->db->get()->result_array();
  }

  // 캘린더 설정
  public function listCalendar()
  {
    $this->db->select('*')->from(DB_CALENDAR);
    return $this->db->get()->result_array();
  }
}
?>
