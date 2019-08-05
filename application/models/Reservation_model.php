<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 산행예약 모델
class Reservation_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  // 예약 진행중인 다음 산행
  public function listReservation()
  {
    $this->db->select('*')
          ->from(DB_NOTICE)
          ->where('status <=', 7)
          ->order_by('startdate', 'asc');
    return $this->db->get()->result_array();
  }

  // 산행 정보
  public function viewReservation($idx)
  {
    $this->db->select('*')
          ->from(DB_NOTICE)
          ->where('idx', $idx);
    return $this->db->get()->row_array(1);
  }
}
?>
