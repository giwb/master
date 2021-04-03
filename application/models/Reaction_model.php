<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 리액션 모델
class Reaction_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  // 리액션 개수
  public function cntReaction($search)
  {
    $this->db->select('COUNT(*) as cnt')
          ->from(DB_REACTION)
          ->where('service_type', $search['service_type'])
          ->where('reaction_type', $search['reaction_type'])
          ->where('target_idx', $search['target_idx']);

	if (!empty($search['club_idx'])) {
      $this->db->where('club_idx', $search['club_idx']);
  	}

    return $this->db->get()->row_array(1);
  }

  // 리액션 확인
  public function viewReaction($search)
  {
    $this->db->select('ip_address, created_by')
          ->from(DB_REACTION)
          ->where('service_type', $search['service_type'])
          ->where('reaction_type', $search['reaction_type'])
          ->where('target_idx', $search['target_idx']);

	if (!empty($search['club_idx'])) {
      $this->db->where('club_idx', $search['club_idx']);
  	}
    if (!empty($search['ip_address'])) {
      $this->db->where('ip_address', $search['ip_address']);
    }
    if (!empty($search['created_by'])) {
      $this->db->where('created_by', $search['created_by']);
    }

    return $this->db->get()->row_array(1);
  }

  // 리액션 등록
  public function insertReaction($data)
  {
    $this->db->insert(DB_REACTION, $data);
    return $this->db->insert_id();
  }

  // 리액션 삭제
  public function deleteReaction($data)
  {
    $this->db->where('target_idx', $data['target_idx'])
              ->where('service_type', $data['service_type'])
              ->where('reaction_type', $data['reaction_type'])
              ->where('ip_address', $data['ip_address'])
              ->where('created_by', $data['created_by']);

    if (!empty($data['club_idx'])) {
		$this->db->where('club_idx', $data['club_idx']);
	}

    return $this->db->delete(DB_REACTION);
  }
}
?>
