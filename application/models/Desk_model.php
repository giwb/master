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


  /**
    ====================================================================================================================
      기사 관리 섹션
    ====================================================================================================================
  **/

  // 메인 페이지 기사 목록
  public function listMainArticle($search=NULL, $paging=NULL, $order='desc')
  {
    $this->db->select('a.*, b.name AS category_name, c.nickname')
          ->from(DB_ARTICLE . ' a')
          ->join(DB_ARTICLE_CATEGORY . ' b', 'a.category=b.code', 'left')
          ->join(DB_MEMBER . ' c', 'a.created_by=c.idx', 'left')
          ->where('a.deleted_at', NULL)
          ->order_by('a.viewing_at', $order);

    if (!empty($search['clubIdx'])) {
      $this->db->where('a.club_idx', $search['clubIdx']);
    }
    if (!empty($search['publish'])) {
      $this->db->where('a.publish', $search['publish']);
    }
    if (!empty($search['code'])) {
      $this->db->where('a.category', $search['code']);
    }
    if (!empty($search['main_status'])) {
      $this->db->where('a.main_status', $search['main_status']);
    }
    if (!empty($search['keyword'])) {
      $this->db->like('a.title', $search['keyword']);
    }
    if (!is_null($paging)) {
      $this->db->limit($paging['perPage'], $paging['nowPage']);
    }

    return $this->db->get()->result_array();
  }

  // 기사 개수
  public function cntArticle($code=NULL)
  {
    $this->db->select('COUNT(*) as cnt')
          ->from(DB_ARTICLE);

    if (!empty($code)) {
      $this->db->where('category', $code);
    }

    return $this->db->get()->row_array(1);
  }

  // 기사 목록
  public function listArticle($search=NULL, $order='desc')
  {
    $this->db->select('a.*, b.name AS category_name, c.nickname')
          ->from(DB_ARTICLE . ' a')
          ->join(DB_ARTICLE_CATEGORY . ' b', 'a.category=b.code', 'left')
          ->join(DB_MEMBER . ' c', 'a.created_by=c.idx', 'left')
          ->where('a.deleted_at', NULL)
          ->order_by('a.created_at', $order);
    return $this->db->get()->result_array();
  }

  // 기사 열람
  public function viewArticle($idx)
  {
    $this->db->select('a.*, b.name AS category_name, c.idx AS user_idx, c.nickname')
          ->from(DB_ARTICLE . ' a')
          ->join(DB_ARTICLE_CATEGORY . ' b', 'a.category=b.code', 'left')
          ->join(DB_MEMBER . ' c', 'a.created_by=c.idx', 'left')
          ->where('a.deleted_at', NULL)
          ->where('a.idx', $idx);
    return $this->db->get()->row_array(1);
  }

  // 댓글 개수
  public function cntReply($idx)
  {
    $this->db->select('COUNT(*) as cnt')
          ->from(DB_ARTICLE_REPLY)
          ->where('idx_article', $idx)
          ->where('deleted_at', NULL);

    return $this->db->get()->row_array(1);
  }

  // 댓글 목록
  public function listReply($idx, $reply=NULL)
  {
    $this->db->select('a.*, b.idx AS user_idx, b.nickname')
          ->from(DB_ARTICLE_REPLY . ' a')
          ->join(DB_MEMBER . ' b', 'a.created_by=b.idx', 'left')
          ->where('a.idx_article', $idx)
          ->where('a.deleted_at', NULL);

    if (!is_null($reply)) {
      $this->db->where('a.idx_reply', $reply);
    } else {
      $this->db->where('a.idx_reply', 0);
    }

    return $this->db->get()->result_array();
  }

  // 댓글 삭제 (논리적 삭제)
  public function deleteReply($idx)
  {
    $this->db->set('deleted_by', 1);
    $this->db->set('deleted_at', time());
    $this->db->where('idx', $idx);
    $this->db->update(DB_ARTICLE_REPLY);

    $this->db->set('deleted_by', 1);
    $this->db->set('deleted_at', time());
    $this->db->where('idx_reply', $idx);
    $this->db->update(DB_ARTICLE_REPLY);
  }

  // 분류 목록
  public function listArticleCategory()
  {
    $this->db->select('*')
          ->from(DB_ARTICLE_CATEGORY)
          ->order_by('idx', 'asc');
    return $this->db->get()->result_array();
  }

  // 분류 이름
  public function viewArticleCategory($code)
  {
    $this->db->select('name, parent')
          ->from(DB_ARTICLE_CATEGORY)
          ->where('code', $code);
    return $this->db->get()->row_array(1);
  }

  // 분류 부모 이름
  public function viewArticleParentCategory($parent)
  {
    $this->db->select('name')
          ->from(DB_ARTICLE_CATEGORY)
          ->where('code', $parent);
    return $this->db->get()->row_array(1);
  }


  /**
    ====================================================================================================================
      여행정보 관리 섹션
    ====================================================================================================================
  **/

  // 분류별 여행정보 개수
  public function cntPlace($code)
  {
    $this->db->select('COUNT(*) as cnt')
          ->from(DB_PLACES)
          ->like('category', $code);
    return $this->db->get()->row_array(1);
  }

  // 여행정보 목록
  public function listPlace($search=NULL)
  {
    $this->db->select('a.*, b.nickname')
          ->from(DB_PLACES . ' a')
          ->join(DB_MEMBER . ' b', 'a.created_by=b.idx', 'left')
          ->where('a.deleted_at', NULL);

    if (!empty($search['keyword'])) {
      $this->db->like('a.title', $search['keyword']);
    }
    if (empty($search['sort']) || $search['sort'] == 'idx') {
      $this->db->order_by('a.idx', 'desc');
    } elseif (!empty($search['sort'])) {
      $this->db->order_by('a.title', 'asc');
    }

    return $this->db->get()->result_array();
  }

  // 여행정보 열람
  public function viewPlace($idx)
  {
    $this->db->select('a.*, b.name AS category_name, c.nickname')
          ->from(DB_PLACES . ' a')
          ->join(DB_PLACES_CATEGORY . ' b', 'a.category=b.code', 'left')
          ->join(DB_MEMBER . ' c', 'a.created_by=c.idx', 'left')
          ->where('a.idx', $idx);
    return $this->db->get()->row_array(1);
  }

  // 여행정보 분류 목록
  public function listPlaceCategory()
  {
    $this->db->select('*')
          ->from(DB_PLACES_CATEGORY)
          ->order_by('idx', 'asc');
    return $this->db->get()->result_array();
  }

  // 여행정보 분류 이름
  public function viewPlaceCategory($code)
  {
    $this->db->select('name')
          ->from(DB_PLACES_CATEGORY)
          ->where('code', $code);
    return $this->db->get()->row_array(1);
  }


  /**
    ====================================================================================================================
      여행일정 관리 섹션
    ====================================================================================================================
  **/

  // 여행일정 목록
  public function listSchedule($search=NULL, $order='desc')
  {
    $this->db->select('a.*, b.nickname')
          ->from(DB_SCHEDULES . ' a')
          ->join(DB_MEMBER . ' b', 'a.created_by=b.idx', 'left')
          ->where('a.deleted_at', NULL)
          ->order_by('a.created_at', $order);

    if (!empty($search['keyword'])) {
      $this->db->like('a.title', $search['keyword']);
    }
    if (!empty($search['sdate'])) {
      $this->db->where('a.startdate >=', $search['sdate']);
    }
    if (!empty($search['edate'])) {
      $this->db->where('a.startdate <=', $search['edate']);
    }

    return $this->db->get()->result_array();
  }

  // 여행일정 열람
  public function viewSchedule($idx)
  {
    $this->db->select('a.*, b.nickname')
          ->from(DB_SCHEDULES . ' a')
          ->join(DB_MEMBER . ' b', 'a.created_by=b.idx', 'left')
          ->where('a.idx', $idx);
    return $this->db->get()->row_array(1);
  }


  /**
    ====================================================================================================================
      현지영상 관리 섹션
    ====================================================================================================================
  **/

  // 현지영상 목록
  public function listCctv($search=NULL, $order='desc')
  {
    $this->db->select('a.*, b.name AS category_name, c.nickname')
          ->from(DB_CCTVS . ' a')
          ->join(DB_CCTVS_CATEGORY . ' b', 'a.category=b.code', 'left')
          ->join(DB_MEMBER . ' c', 'a.created_by=c.idx', 'left')
          ->where('a.deleted_at', NULL)
          ->order_by('a.created_at', $order);

    if (!empty($search['category'])) {
      $this->db->where('a.category', $search['category']);
    }

    return $this->db->get()->result_array();
  }

  // 현지영상 열람
  public function viewCctv($idx)
  {
    $this->db->select('a.*, b.name AS category_name, c.nickname')
          ->from(DB_CCTVS . ' a')
          ->join(DB_CCTVS_CATEGORY . ' b', 'a.category=b.code', 'left')
          ->join(DB_MEMBER . ' c', 'a.created_by=c.idx', 'left')
          ->where('a.idx', $idx);
    return $this->db->get()->row_array(1);
  }

  // 현지영상 분류 목록
  public function listCctvCategory()
  {
    $this->db->select('*')
          ->from(DB_CCTVS_CATEGORY)
          ->order_by('idx', 'asc');
    return $this->db->get()->result_array();
  }

  // 현지영상 분류 이름
  public function viewCctvCategory($code)
  {
    $this->db->select('name')
          ->from(DB_CCTVS_CATEGORY)
          ->where('code', $code);
    return $this->db->get()->row_array(1);
  }


  /**
    ====================================================================================================================
      기타
    ====================================================================================================================
  **/

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
