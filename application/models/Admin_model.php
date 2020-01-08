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

  // 오늘 방문자수
  public function cntTodayVisitor($clubIdx)
  {
    $this->db->select('COUNT(idx) AS CNT')
          ->from(DB_VISITOR)
          ->where('club_idx', $clubIdx)
          ->where('FROM_UNIXTIME(created_at, "%Y%m%d") =', date('Ymd'));
    return $this->db->get()->row_array(1);
  }

  // 산행 예약자 카운트
  public function cntReservation($resCode, $bus=NULL, $wait=NULL)
  {
    $this->db->select('COUNT(idx) AS CNT')
          ->from(DB_RESERVATION)
          ->where('rescode', $resCode)
          ->where('manager', 0)
          ->where('priority', 0);

    if (is_null($wait)) {
      $this->db->where('status !=', RESERVE_WAIT);
    }
    if (!is_null($bus)) {
      $this->db->where('bus', $bus);
    }

    return $this->db->get()->row_array(1);
  }

  // 회원 예약횟수
  public function cntMemberReservation($userid)
  {
    $this->db->select('COUNT(a.userid) as cnt')
          ->from(DB_RESERVATION . ' a')
          ->join(DB_NOTICE . ' b', 'a.rescode=b.idx', 'left')
          ->where('a.userid', $userid)
          ->where('a.status', 1)
          ->where('b.status', STATUS_CLOSED);
    return $this->db->get()->row_array(1);
  }

  // 산행 목록
  public function listNotice($search=NULL, $order='asc')
  {
    $this->db->select('*')
          ->from(DB_NOTICE)
          ->order_by('startdate', $order);

    if (!empty($search['past_sdate'])) {
      $this->db->where("DATE_FORMAT(startdate, '%m-%d') >= '" . $search['past_sdate'] . "'");
    }
    if (!empty($search['past_edate'])) {
      if ($search['past_edate'] < $search['past_sdate']) {
        $this->db->or_where("DATE_FORMAT(startdate, '%m-%d') <= '" . $search['past_edate'] . "'");
      } else {
        $this->db->where("DATE_FORMAT(startdate, '%m-%d') <= '" . $search['past_edate'] . "'");
      }
    }
    if (!empty($search['sdate'])) {
      $this->db->where("DATE_FORMAT(startdate, '%Y-%m-%d') >= '" . $search['sdate'] . "'");
    }
    if (!empty($search['edate'])) {
      if ($search['edate'] < $search['sdate']) {
        $this->db->or_where("DATE_FORMAT(startdate, '%Y-%m-%d') <= '" . $search['edate'] . "'");
      } else {
        $this->db->where("DATE_FORMAT(startdate, '%Y-%m-%d') <= '" . $search['edate'] . "'");
      }
    }
    if (!empty($search['subject'])) {
      $this->db->like('subject', $search['subject']);
    }
    if (!empty($search['status'])) {
      $this->db->where_in('status', $search['status']);
    }

    return $this->db->get()->result_array();
  }

  // 회원 예약 목록
  public function listReserve($paging, $nickname)
  {
    $this->db->select('a.rescode, a.userid, a.nickname, a.bus, a.seat, a.loc, a.memo, b.startdate, b.starttime, b.subject, b.bus AS notice_bus, b.bustype AS notice_bustype, b.status AS notice_status, b.cost, b.cost_total, c.idx AS member_idx')
          ->from(DB_RESERVATION . ' a')
          ->join(DB_NOTICE . ' b', 'a.rescode=b.idx', 'left')
          ->join(DB_MEMBER . ' c', 'a.nickname=c.nickname', 'left')
          ->where_in('b.status', array(STATUS_ABLE, STATUS_CONFIRM, STATUS_CLOSED))
          ->order_by('b.startdate', 'desc');

    if (!is_null($nickname)) {
      $this->db->like('a.nickname', $nickname);
    }
    if (!is_null($paging)) {
      $this->db->limit($paging['perPage'], $paging['nowPage']);
    }

    return $this->db->get()->result_array();
  }

  // 예약 정보 보기
  public function viewReserve($search)
  {
    $this->db->select('*')
          ->from(DB_RESERVATION);

    if (!empty($search['idx'])) {
      $this->db->where('idx', $search['idx']);
      return $this->db->get()->row_array(1);
    }
    if (!empty($search['rescode'])) {
      $this->db->where('rescode', $search['rescode']);
      return $this->db->get()->result_array();
    }
  }

  // 예약 정보 보기 : 종료시
  public function viewReserveClosed($rescode)
  {
    $this->db->select('userid')
          ->from(DB_RESERVATION)
          ->where('rescode', $rescode)
          ->where('userid !=', NULL)
          ->where('status', RESERVE_PAY)
          ->where('penalty', 0)
          ->group_by('userid');
    return $this->db->get()->result_array();
  }

  // 예약 정보 보기 : 종료시 추가 예약
  public function viewReserveClosedAdded($rescode, $userid)
  {
    $this->db->select('COUNT(*) AS cnt')
          ->from(DB_RESERVATION)
          ->where('rescode', $rescode)
          ->where('userid', $userid)
          ->where('status', RESERVE_PAY)
          ->where('penalty', 0);
    return $this->db->get()->row_array(1);
  }

  // 좌석 예약 확인
  public function checkReserve($idx, $bus, $seat)
  {
    $this->db->select('idx, userid, nickname, priority, status')
          ->from(DB_RESERVATION)
          ->where('rescode', $idx)
          ->where('bus', $bus)
          ->where('seat', $seat);
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
          ->where('priority=', 0)
          ->order_by('seat', 'asc');
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

  // 대기자 카운트
  public function cntWait($clubIdx, $noticeIdx)
  {
    $this->db->select('COUNT(created_at) as cnt')
          ->from(DB_WAIT)
          ->where('club_idx', $clubIdx)
          ->where('notice_idx', $noticeIdx);
    return $this->db->get()->row_array(1);
  }

  // 대기자 목록
  public function listWait($rescode)
  {
    $this->db->select('*')
          ->from(DB_WAIT)
          ->where('notice_idx', $rescode)
          ->order_by('created_at', 'asc');
    return $this->db->get()->result_array();
  }

  // 대기자 등록
  public function insertWait($data)
  {
    $this->db->insert(DB_WAIT, $data);
    return $this->db->insert_id();
  }

  // 대기자 삭제
  public function deleteWait($idx)
  {
    $this->db->where('idx', $idx);
    return $this->db->delete(DB_WAIT);
  }

  // 문자양식
  public function listSMS($rescode)
  {
    $this->db->select('a.idx, a.nickname, a.bus AS nowbus, a.seat, a.loc, b.subject, b.bus, b.bustype, b.startdate, b.starttime, b.schedule, b.distance')
          ->from(DB_RESERVATION . ' a')
          ->join(DB_NOTICE . ' b', 'a.rescode=b.idx', 'left')
          ->where('a.rescode', $rescode)
          ->where('a.manager !=', 1)
          ->order_by('a.bus', 'ASC')
          ->order_by('a.seat', 'ASC');
    return $this->db->get()->result_array();
  }

  // 전체 회원 목록
  public function listMembers($paging=NULL, $search=NULL)
  {
    $this->db->select('*')
          ->from(DB_MEMBER)
          ->where('quitdate', NULL)
          ->order_by('idx', 'desc');

    if (!empty($search['realname'])) {
      $this->db->like('realname', $search['realname']);
    }
    if (!empty($search['nickname'])) {
      $this->db->like('nickname', $search['nickname']);
    }
    if (!empty($search['resMin'])) {
      $this->db->where('(rescount - penalty) >=', $search['resMin']);
      $this->db->where('admin', 0);
      $this->db->where('level', 0);
    }
    if (!empty($search['resMax'])) {
      $this->db->where('(rescount - penalty) <=', $search['resMax']);
    }
    if (!empty($search['level'])) {
      $this->db->where('level', $search['level']);
    }
    if (!empty($search['admin'])) {
      $this->db->where('admin', $search['admin']);
    }
    if (!is_null($paging)) {
      $this->db->limit($paging['perPage'], $paging['nowPage']);
    }

    return $this->db->get()->result_array();
  }

  // 회원 정보
  public function viewMember($search)
  {
    $this->db->select('*')
          ->from(DB_MEMBER)
          ->where('quitdate', NULL);

    if (!empty($search['idx'])) {
      $this->db->where('idx', $search['idx']);
    }
    if (!empty($search['userid'])) {
      $this->db->where('userid', $search['userid']);
    }
    if (!empty($search['nickname'])) {
      $this->db->where('nickname', $search['nickname']);
    }

    return $this->db->get()->row_array(1);
  }

  // 회원 정보 수정
  public function updateMember($data, $idx)
  {
    $this->db->set($data);
    $this->db->where('idx', $idx);
    return $this->db->update(DB_MEMBER);
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

  // 출석체크 - 산행지로 보기
  public function listAttendanceResCode($nickname)
  {
    $this->db->select('rescode')
          ->from(DB_ATTENDANCE)
          ->where('nickname', $nickname);
    return $this->db->get()->result_array();
  }

  // 출석체크 - 산행지로 보기 - 산 이름 추출
  public function getAttendanceMountainName($rescode)
  {
    $this->db->select('mname')
          ->from(DB_NOTICE)
          ->where('idx', $rescode);
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

  // 출석체크 - 인증현황 입력
  public function insertAttendanceAuth($data)
  {
    $this->db->insert(DB_AUTH, $data);
    return $this->db->insert_id();
  }

  // 활동관리 - 회원 예약 기록
  public function listHistory($paging=NULL, $search=NULL)
  {
    $this->db->select('*')
          ->from(DB_HISTORY)
          ->where('status', $search['status'])
          ->order_by('idx', 'desc');

    if (!empty($search['action'])) {
      $this->db->where_in('action', $search['action']);
    }
    if (!empty($search['subject'])) {
      $this->db->like('subject', $search['subject']);
    }
    if (!empty($search['nickname'])) {
      $this->db->like('nickname', $search['nickname']);
    }
    if (!is_null($paging)) {
      $this->db->limit($paging['perPage'], $paging['nowPage']);
    }
    if (!empty($search['refund'])) {
      $this->db->where('userid', '');
    }

    return $this->db->get()->result_array();
  }

  // 활동관리 - 회원 예약 기록 카운트
  public function cntHistory($search=NULL)
  {
    $this->db->select('COUNT(*) AS cnt')
          ->from(DB_HISTORY)
          ->where('status', $search['status']);

    if (!empty($search['action'])) {
      $this->db->where_in('action', $search['action']);
    }
    if (!empty($search['subject'])) {
      $this->db->like('subject', $search['subject']);
    }
    if (!empty($search['nickname'])) {
      $this->db->like('nickname', $search['nickname']);
    }
    if (!empty($search['refund'])) {
      $this->db->where('userid', '');
    }

    return $this->db->get()->row_array(1);
  }

  // 활동관리 - 기록 상세 보기
  public function viewHistory($idx)
  {
    $this->db->select('*')
          ->from(DB_HISTORY)
          ->where('idx', $idx);
    return $this->db->get()->row_array(1);
  }

  // 활동관리 - 확인 체크
  public function updateHistory($data, $idx)
  {
    $this->db->set($data);
    $this->db->where('idx', $idx);
    return $this->db->update(DB_HISTORY);
  }

  // 활동관리 - 댓글 기록
  public function listReply()
  {
    $this->db->select('a.idx, a.club_idx, a.story_idx, a.reply_type, a.content, a.created_by, a.created_at, b.nickname')
          ->from(DB_STORY_REPLY . ' a')
          ->join(DB_MEMBER . ' b', 'a.created_by=b.idx', 'left')
          ->where('deleted_at', NULL)
          ->order_by('a.idx', 'desc');
    return $this->db->get()->result_array();
  }

  // 활동관리 - 댓글 수정/삭제
  public function deleteReply($data, $idx)
  {
    $this->db->set($data);
    $this->db->where('idx', $idx);
    return $this->db->update(DB_STORY_REPLY);
  }

  // 활동관리 - 리액션 수정/삭제
  public function deleteReaction($created_by, $created_at)
  {
    $this->db->where('created_by', $created_by);
    $this->db->where('created_at', $created_at);
    return $this->db->delete(DB_STORY_REACTION);
  }

  // 활동관리 - 좋아요/공유 기록
  public function listReaction()
  {
    $this->db->select('a.club_idx, a.story_idx, a.reaction_type, a.reaction_kind, a.share_type, a.created_by, a.created_at, b.nickname')
          ->from(DB_STORY_REACTION . ' a')
          ->join(DB_MEMBER . ' b', 'a.created_by=b.idx', 'left')
          ->order_by('a.created_at', 'desc');
    return $this->db->get()->result_array();
  }

  // 활동관리 - 방문자 기록
  public function listVisitor($search)
  {
    $this->db->select('a.*, b.nickname, b.quitdate')
          ->from(DB_VISITOR . ' a')
          ->join(DB_MEMBER . ' b', 'a.created_by=b.idx', 'left')
          ->order_by('a.created_at', 'desc');

    if (!empty($search['nowdate'])) {
      $this->db->where('FROM_UNIXTIME(created_at, "%Y%m%d") =', $search['nowdate']);
    }
    if (!empty($search['keyword'])) {
      $this->db->where($search['keyword'] . ' !=', NULL);
    }
          
    return $this->db->get()->result_array();
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
    $this->db->select('a.*, b.name AS bus_seat_name')
          ->from(DB_BUSTYPE . ' a')
          ->join(DB_BUSDATA . ' b', 'a.bus_seat=b.idx')
          ->order_by('idx', 'asc');
    return $this->db->get()->result_array();
  }

  // 설정 - 차종 정보
  public function getBustype($idx)
  {
    $this->db->select('a.*, b.seat, b.direction')
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

  // 달력 일정 목록
  public function listCalendar()
  {
    $this->db->select('*')->from(DB_CALENDAR);
    return $this->db->get()->result_array();
  }

  // 달력 일정 등록
  public function insertCalendar($data)
  {
    $this->db->insert(DB_CALENDAR, $data);
    return $this->db->insert_id();
  }

  // 달력 일정 수정
  public function updateCalendar($data, $idx)
  {
    $this->db->set($data);
    $this->db->where('idx', $idx);
    return $this->db->update(DB_CALENDAR);
  }

  // 달력 일정 삭제
  public function deleteCalendar($idx)
  {
    $this->db->where('idx', $idx);
    return $this->db->delete(DB_CALENDAR);
  }
}
?>
