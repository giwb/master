<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 관리 페이지 모델
class Desk_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  // 메인 페이지 기사 목록
  public function listMainArticle($search=NULL, $order='desc')
  {
    $this->db->select('a.*, b.name AS category_name, c.nickname')
          ->from(DB_ARTICLE . ' a')
          ->join(DB_ARTICLE_CATEGORY . ' b', 'a.category=b.code', 'left')
          ->join(DB_MEMBER . ' c', 'a.created_by=c.idx', 'left')
          ->where('a.deleted_at', NULL)
          ->order_by('a.viewing_at', $order);

    if (!empty($search['keyword'])) {
      $this->db->like('a.title', $search['keyword']);
    }
    if (!empty($search['code'])) {
      $this->db->where('a.category', $search['code']);
    }

    return $this->db->get()->result_array();
  }

  // 분류별 기사 개수
  public function cntArticle($code)
  {
    $this->db->select('COUNT(*) as cnt')
          ->from(DB_ARTICLE)
          ->where('category', $code);
    return $this->db->get()->row_array(1);
  }

  // 기사 목록
  public function listArticle($search=NULL, $order='desc')
  {
    $this->db->select('a.*, b.name AS category_name, c.nickname')
          ->from(DB_ARTICLE . ' a')
          ->join(DB_ARTICLE_CATEGORY . ' b', 'a.category=b.code', 'left')
          ->join(DB_MEMBER . ' c', 'a.created_by=c.idx', 'left')
          ->where('deleted_at', NULL)
          ->order_by('created_at', $order);
    return $this->db->get()->result_array();
  }

  // 기사 열람
  public function viewArticle($idx)
  {
    $this->db->select('a.*, b.name AS category_name, c.nickname')
          ->from(DB_ARTICLE . ' a')
          ->join(DB_ARTICLE_CATEGORY . ' b', 'a.category=b.code', 'left')
          ->join(DB_MEMBER . ' c', 'a.created_by=c.idx', 'left')
          ->where('a.idx', $idx);
    return $this->db->get()->row_array(1);
  }

  // 분류 목록
  public function listCategory()
  {
    $this->db->select('*')
          ->from(DB_ARTICLE_CATEGORY)
          ->order_by('idx', 'asc');
    return $this->db->get()->result_array();
  }

  // 분류 이름
  public function viewCategory($code)
  {
    $this->db->select('name')
          ->from(DB_ARTICLE_CATEGORY)
          ->where('code', $code);
    return $this->db->get()->row_array(1);
  }

  // 등록
  public function insert($table, $data)
  {
    $this->db->insert($table, $data);
    return $this->db->insert_id();
  }

  // 수정
  public function update($table, $data, $idx)
  {
    $this->db->set($data);
    $this->db->where('idx', $idx);
    return $this->db->update($table);
  }

  // 삭제
  public function delete($table, $idx=NULL)
  {
    if (!is_null($idx)) {
      $this->db->where('idx', $idx);
      return $this->db->delete($table);
    } else {
      return $this->db->truncate($table);
    }
  }
}
?>
