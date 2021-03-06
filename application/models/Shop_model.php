<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 쇼핑몰 모델
class Shop_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  // 용품 목록
  public function listItem($paging, $search)
  {
    $this->db->select('*')
          ->from(DB_SHOP)
          ->where('deleted_at', NULL)
          ->order_by('idx', 'desc');

    if (!empty($search['item_name'])) {
      $this->db->like('item_name', $search['item_name']);
    }
    if (!empty($search['item_category1']) && empty($search['item_category2'])) {
      $this->db->like('item_category', '"' . $search['item_category1'] . '"');
    }
    if (!empty($search['item_category2'])) {
      $this->db->like('item_category', '"' . $search['item_category2'] . '"');
    }
    if (!empty($search['item_recommend'])) {
      $this->db->where('item_recommend', $search['item_recommend']);
    }
    if (!empty($search['item_visible'])) {
      $this->db->where('item_visible', $search['item_visible']);
    }
    if (!empty($paging)) {
      $this->db->limit($paging['perPage'], $paging['nowPage']);
    }

    return $this->db->get()->result_array();
  }

  // 용품 카운트
  public function cntItem($search)
  {
    $this->db->select('COUNT(*) AS cnt')
          ->from(DB_SHOP)
          ->where('item_visible', 'Y')
          ->where('deleted_at', NULL);

    if (!empty($search['item_name'])) {
      $this->db->like('item_name', $search['item_name']);
    }
    if (!empty($search['item_category1']) && empty($search['item_category2'])) {
      $this->db->like('item_category', '"' . $search['item_category1'] . '"');
    }
    if (!empty($search['item_category2'])) {
      $this->db->like('item_category', '"' . $search['item_category2'] . '"');
    }

    return $this->db->get()->row_array(1);
  }

  // 등록된 용품 상세
  public function viewItem($idx)
  {
    $this->db->select('*')
          ->from(DB_SHOP)
          ->where('deleted_at', NULL)
          ->where('idx', $idx);
    return $this->db->get()->row_array(1);
  }

  // 신규 용품 등록
  public function insertItem($data)
  {
    $this->db->insert(DB_SHOP, $data);
    return $this->db->insert_id();
  }

  // 용품 정보 수정/삭제
  public function updateItem($data, $idx)
  {
    $this->db->set($data);
    $this->db->where('idx', $idx);
    return $this->db->update(DB_SHOP);
  }

  // 카테고리 목록
  public function listCategory($parent=0)
  {
    $this->db->select('idx, name')
          ->from(DB_SHOP_CATEGORY)
          ->where('deleted_at', NULL)
          ->where('parent', $parent)
          ->order_by('idx', 'asc');
    return $this->db->get()->result_array();
  }

  // 카테고리 상세
  public function viewCategory($idx)
  {
    $this->db->select('name')
          ->from(DB_SHOP_CATEGORY)
          ->where('deleted_at', NULL)
          ->where('idx', $idx);
    return $this->db->get()->row_array(1);
  }

  // 카테고리 등록
  public function insertCategory($data)
  {
    $this->db->insert(DB_SHOP_CATEGORY, $data);
    return $this->db->insert_id();
  }

  // 카테고리 수정/삭제
  public function updateCategory($data, $idx)
  {
    $this->db->set($data);
    $this->db->where('idx', $idx);
    return $this->db->update(DB_SHOP_CATEGORY);
  }

  // 구매진행 - 예약된 산행보기
  public function listMemberReserve($clubIdx, $userIdx)
  {
    $this->db->select('b.idx, b.startdate, b.mname')
          ->from(DB_RESERVATION . ' a')
          ->join(DB_NOTICE . ' b', 'a.rescode=b.idx', 'left')
          ->where('a.club_idx', $clubIdx)
          ->where('a.user_idx', $userIdx)
          ->where('b.visible', VISIBLE_ABLE)
          ->where('b.startdate >', date('Y-m-d'))
          ->where_in('b.status', array(STATUS_ABLE, STATUS_CONFIRM))
          ->group_by('b.mname, b.idx, b.startdate')
          ->order_by('b.startdate', 'asc');
    return $this->db->get()->result_array();
  }

  // 구매 목록
  public function listPurchase($paging, $search=NULL)
  {
    $this->db->select('a.*, b.nickname, c.idx AS noticeIdx, c.startdate, c.mname')
          ->from(DB_SHOP_PURCHASE . ' a')
          ->join(DB_MEMBER . ' b', 'a.created_by=b.idx', 'left')
          ->join(DB_NOTICE . ' c', 'a.notice_idx=c.idx', 'left')
          ->where('a.deleted_at', NULL)
          ->order_by('a.idx', 'desc');

    if (!empty($search['item_name'])) {
      $this->db->like('a.items', $search['item_name']);
    }
    if (!empty($search['created_by'])) {
      $this->db->like('a.created_by', $search['created_by']);
    }
    if (!empty($search['nickname'])) {
      $this->db->like('b.nickname', $search['nickname']);
    }
    if (!empty($search['mname'])) {
      $this->db->like('c.mname', $search['mname']);
    }
    if (!empty($search['notice_idx'])) {
      $this->db->where('a.notice_idx', $search['notice_idx']);
    }
    if (!empty($paging)) {
      $this->db->limit($paging['perPage'], $paging['nowPage']);
    }

    return $this->db->get()->result_array();
  }

  // 구매 내역 카운트
  public function cntPurchase($search=NULL)
  {
    $this->db->select('COUNT(*) AS cnt')
          ->from(DB_SHOP_PURCHASE . ' a')
          ->join(DB_MEMBER . ' b', 'a.created_by=b.idx', 'left')
          ->join(DB_NOTICE . ' c', 'a.notice_idx=c.idx', 'left')
          ->where('a.deleted_at', NULL);

    if (!empty($search['item_name'])) {
      $this->db->like('a.items', $search['item_name']);
    }
    if (!empty($search['created_by'])) {
      $this->db->like('a.created_by', $search['created_by']);
    }
    if (!empty($search['nickname'])) {
      $this->db->like('b.nickname', $search['nickname']);
    }
    if (!empty($search['mname'])) {
      $this->db->like('c.mname', $search['mname']);
    }
    if (!empty($search['notice_idx'])) {
      $this->db->where('a.notice_idx', $search['notice_idx']);
    }

    return $this->db->get()->row_array(1);
  }

  // 구매 내역 보기
  public function viewPurchase($idx)
  {
    $this->db->select('a.*, b.userid, b.nickname, b.point AS userPoint, c.startdate, c.mname')
          ->from(DB_SHOP_PURCHASE . ' a')
          ->join(DB_MEMBER . ' b', 'a.created_by=b.idx', 'left')
          ->join(DB_NOTICE . ' c', 'a.notice_idx=c.idx', 'left')
          ->where('a.idx', $idx)
          ->where('a.deleted_at', NULL);
    return $this->db->get()->row_array(1);
  }

  // 구매 등록
  public function insertPurchase($data)
  {
    $this->db->insert(DB_SHOP_PURCHASE, $data);
    return $this->db->insert_id();
  }

  // 구매 정보 수정
  public function updatePurchase($data, $idx)
  {
    $this->db->set($data);
    $this->db->where('idx', $idx);
    return $this->db->update(DB_SHOP_PURCHASE);
  }
}
?>
