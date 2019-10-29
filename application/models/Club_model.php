<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 산악회정보 모델
class Club_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  // 산악회정보 - 목록
  public function listClub($search=NULL, $keyword=NULL)
  {
    $this->db->select('*')
          ->from(DB_CLUBS)
          ->where('deleted_at', NULL)
          ->order_by('title', 'asc');

    if (!is_null($search) && !is_null($keyword)) {
      $this->db->like($search, $keyword);
    }

    return $this->db->get()->result_array();
  }

  // 산악회정보 - 보기
  public function viewClub($idx)
  {
    $this->db->select('*')
          ->from(DB_CLUBS)
          ->where('deleted_at', NULL)
          ->where('idx', $idx);
    return $this->db->get()->row_array(1);
  }

  // 산악회정보 - 입력
  public function insertClub($data)
  {
    $this->db->insert(DB_CLUBS, $data);
    return $this->db->insert_id();
  }

  // 산악회정보 - 수정
  public function updateClub($data, $idx)
  {
    $this->db->set($data);
    $this->db->where('idx', $idx);
    return $this->db->update(DB_CLUBS);
  }

  // 등록된 산행 목록
  public function listNotice($club_idx, $status=NULL)
  {
    $this->db->select('*')
          ->from(DB_NOTICE)
          ->where('club_idx', $club_idx)
          ->order_by('startdate', 'asc');

    if (!is_null($status)) {
      $this->db->where_in('status', $status);
    }

    return $this->db->get()->result_array();
  }

  // 등록된 산행 상세 정보
  public function viewNotice($club_idx, $idx)
  {
    $this->db->select('*')
          ->from(DB_NOTICE)
          ->where('club_idx', $club_idx)
          ->where('idx', $idx);
    return $this->db->get()->row_array(1);
  }

  // 선택된 산행 예약 목록
  public function viewProgress($club_idx, $rescode)
  {
    $this->db->select('*')
          ->from(DB_RESERVATION)
          ->where('club_idx', $club_idx)
          ->where('rescode', $rescode);
    return $this->db->get()->result_array();
  }
}
?>
