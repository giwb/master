<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 지역 모델
class Area_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  // 시도
  public function listSido()
  {
    $this->db->select('idx, name')
          ->from(DB_AREAS)
          ->where('parent', 0)
          ->order_by('idx', 'asc');
    return $this->db->get()->result_array();
  }

  // 구군
  public function listGugun($parent)
  {
    $this->db->select('idx, name')
          ->from(DB_AREAS)
          ->where('parent', $parent)
          ->order_by('idx', 'asc');
    return $this->db->get()->result_array();
  }

  // 지역명
  public function getName($idx)
  {
    $this->db->select('name')
          ->from(DB_AREAS)
          ->where('idx', $idx);
    return $this->db->get()->row_array(1);
  }
}
?>
