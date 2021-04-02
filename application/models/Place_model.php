<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 여행정보 모델
class Place_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  // 여행정보 - 목록
  public function listPlace($search=NULL, $keyword=NULL)
  {
    $this->db->select('*')
          ->from(DB_PLACES)
          ->where('deleted_at', NULL)
          ->order_by('title', 'asc');

    if (!empty($search['code'])) {
      $this->db->where('category', $search['code']);
    }
    if (!is_null($search) && !is_null($keyword)) {
      $this->db->like($search, $keyword);
    }

    return $this->db->get()->result_array();
  }

  // 여행정보 - 보기
  public function viewPlace($idx)
  {
    $this->db->select('*')
          ->from(DB_PLACES)
          ->where('deleted_at', NULL)
          ->where('idx', $idx);
    return $this->db->get()->row_array(1);
  }

  // 여행정보 - 입력
  public function insertPlace($data)
  {
    $this->db->insert(DB_PLACES, $data);
    return $this->db->insert_id();
  }

  // 여행정보 - 수정
  public function updatePlace($data, $idx)
  {
    $this->db->set($data);
    $this->db->where('idx', $idx);
    return $this->db->update(DB_PLACES);
  }
}
?>
