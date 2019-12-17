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
  public function viewClub($clubIdx)
  {
    $this->db->select('a.*, b.nickname')
          ->from(DB_CLUBS . ' a')
          ->join(DB_MEMBER . ' b', 'a.created_by=b.idx')
          ->where('a.deleted_at', NULL)
          ->where('a.idx', $clubIdx);
    return $this->db->get()->row_array(1);
  }

  // 산악회정보 - 입력
  public function insertClub($data)
  {
    $this->db->insert(DB_CLUBS, $data);
    return $this->db->insert_id();
  }

  // 산악회정보 - 수정
  public function updateClub($data, $clubIdx)
  {
    $this->db->set($data);
    $this->db->where('idx', $clubIdx);
    return $this->db->update(DB_CLUBS);
  }

  // 백산백소
  public function listAuth()
  {
    $this->db->select('nickname, COUNT(idx) AS cnt')
          ->from(DB_AUTH)
          ->where('nickname !=', '캔총무')
          ->group_by('nickname')
          ->order_by('cnt', 'desc')
          ->order_by('nickname', 'asc');
    return $this->db->get()->result_array();
  }

  // 백산백소 닉네임별 정보
  public function listAuthNotice($nickname)
  {
    $this->db->select('ANY_VALUE(a.idx) AS idx, ANY_VALUE(a.rescode) AS rescode, ANY_VALUE(a.userid) AS userid, ANY_VALUE(a.nickname) AS nickname, ANY_VALUE(a.photo) AS photo, ANY_VALUE(a.title) AS title, ANY_VALUE(a.regdate) AS regdate, ANY_VALUE(b.startdate) AS startdate')
          ->from(DB_AUTH . ' a')
          ->join(DB_NOTICE . ' b', 'a.rescode=b.idx', 'left')
          ->where('a.nickname', $nickname)
          ->group_by('a.title')
          ->order_by('startdate', 'asc');
    return $this->db->get()->result_array();
  }
}
?>
