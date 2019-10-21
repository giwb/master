<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 클럽 스토리 모델
class Story_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  // 클럽 이야기
  public function listStory($club_idx)
  {
    $this->db->select('a.*, b.nickname AS user_nickname, c.filename')
          ->from(DB_STORY . ' a')
          ->join(DB_MEMBER . ' b', 'a.member_idx=b.idx', 'left')
          ->join(DB_FILES . ' c', 'c.page="story" AND a.idx=c.page_idx', 'left')
          ->where('a.club_idx', $club_idx)
          ->order_by('a.created_at', 'desc');
    return $this->db->get()->result_array();
  }

  // 클럽 이야기 - 등록
  public function insertStory($data)
  {
    $this->db->insert(DB_STORY, $data);
    return $this->db->insert_id();
  }
}
?>
