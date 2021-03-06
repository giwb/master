<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 산행 모델
class Notice_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  // 산행 횟수
  public function cntNotice($clubIdx)
  {
    $this->db->select('COUNT(*) AS cnt')
          ->from(DB_NOTICE)
          ->where('club_idx', $clubIdx)
          ->where_in('status', array(STATUS_ABLE, STATUS_CONFIRM, STATUS_CLOSED));
    return $this->db->get()->row_array(1);
  }

  // 진행중 산행
  public function listNotice()
  {
    $this->db->select('*')
          ->from(DB_NOTICE)
          ->where('status <=', 7)
          ->order_by('startdate', 'asc');
    return $this->db->get()->result_array();
  }

  // 이번 주 산행
  public function listThisWeekNotice($now, $till)
  {
    $this->db->select('*')
          ->from(DB_NOTICE)
          ->where('status <=', 7)
          ->where('startdate >=', $now)
          ->where('startdate <=', $till)
          ->order_by('startdate', 'asc');
    return $this->db->get()->result_array();
  }

  // 지난 산행
  public function listBeforeNotice($now)
  {
    $this->db->select('*')
          ->from(DB_NOTICE)
          ->where('status', 9)
          ->where('startdate <', $now)
          ->order_by('startdate', 'desc')
          ->limit(9);
    return $this->db->get()->result_array();
  }

  // 산행 정보
  public function viewNotice($idx)
  {
    $this->db->select('*')
          ->from(DB_NOTICE)
          ->where('idx', $idx);
    return $this->db->get()->row_array(1);
  }

  // 조회수 올리기
  public function updateNoticeRefer($idx, $refer)
  {
    $this->db->set('refer', $refer);
    $this->db->where('idx', $idx);
    return $this->db->update(DB_NOTICE);
  }
}
?>
