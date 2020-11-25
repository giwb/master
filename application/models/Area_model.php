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
    $this->db->select('name, regid, nx, ny')
          ->from(DB_AREAS)
          ->where('idx', $idx);
    return $this->db->get()->row_array(1);
  }

  // 날씨 불러오기
  public function getWeather($noticeIdx)
  {
    $this->db->select('json_data')
          ->from(DB_WEATHER)
          ->where('notice_idx', $noticeIdx);
    return $this->db->get()->row_array(1);
  }

  // 날씨 저장
  public function insertWeather($data)
  {
    $this->db->insert(DB_WEATHER, $data);
  }

  // 날씨 비우기
  public function emptyWeather()
  {
    $this->db->truncate(DB_WEATHER);
  }
}
?>
