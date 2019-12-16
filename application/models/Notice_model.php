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
}
?>
