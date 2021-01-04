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

  // 산악회정보 - 소개 메뉴
  public function listAbout($clubIdx)
  {
    $this->db->select('idx, title, content')
          ->from(DB_CLUB_DETAIL)
          ->where('deleted_at', NULL)
          ->where('club_idx', $clubIdx)
          ->order_by('sort_idx', 'asc');
    return $this->db->get()->result_array();
  }

  // 산악회정보 - 소개 메뉴 보기
  public function viewAbout($clubIdx, $idx)
  {
    $this->db->select('idx, title, content')
          ->from(DB_CLUB_DETAIL)
          ->where('deleted_at', NULL)
          ->where('club_idx', $clubIdx)
          ->where('idx', $idx);
    return $this->db->get()->row_array(1);
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

  // 앨범 목록
  public function listAlbum($clubIdx, $paging)
  {
    $this->db->select('a.*, b.nickname')
          ->from(DB_ALBUM . ' a')
          ->join(DB_MEMBER . ' b', 'a.created_by=b.idx', 'left')
          ->where('a.club_idx', $clubIdx)
          ->where('a.deleted_at', NULL)
          ->order_by('a.idx', 'desc');

    if (!empty($paging['keyword'])) {
      $this->db->like('a.subject', $paging['keyword']);
    }
    if (!empty($paging)) {
      $this->db->limit($paging['perPage'], $paging['nowPage']);
    }

    return $this->db->get()->result_array();
  }

  // 앨범 카운트
  public function cntAlbum($clubIdx)
  {
    $this->db->select('COUNT(*) AS cnt')
          ->from(DB_ALBUM)
          ->where('club_idx', $clubIdx)
          ->where('deleted_at', NULL);
    return $this->db->get()->row_array(1);
  }

  // 앨범 상세
  public function viewAlbum($idx)
  {
    $this->db->select('*')
          ->from(DB_ALBUM)
          ->where('deleted_at', NULL)
          ->where('idx', $idx);
    return $this->db->get()->row_array(1);
  }

  // 앨범 등록
  public function insertAlbum($data)
  {
    $this->db->insert(DB_ALBUM, $data);
    return $this->db->insert_id();
  }

  // 앨범 수정
  public function updateAlbum($data, $idx)
  {
    $this->db->set($data);
    $this->db->where('idx', $idx);
    return $this->db->update(DB_ALBUM);
  }

  // 도메인 찾기
  public function getDomain($domain)
  {
    $this->db->select('idx')
          ->from(DB_CLUBS)
          ->where('domain', $domain);
    return $this->db->get()->row_array(1);
  }

/*
  public function getMember($userid)
  {
    $this->db->select('idx')
          ->from(DB_MEMBER)
          ->where('userid', $userid);
    return $this->db->get()->row_array(1);
  }

  public function listHistory()
  {
    $this->db->select('*')
          ->from(DB_HISTORY);
    return $this->db->get()->result_array();
  }

  public function updateHistory($data, $idx)
  {
    $this->db->set($data);
    $this->db->where('idx', $idx);
    return $this->db->update(DB_HISTORY);
  }

  public function listReserve()
  {
    $this->db->select('*')
          ->from(DB_RESERVATION);
    return $this->db->get()->result_array();
  }

  public function updateReserve($data, $idx)
  {
    $this->db->set($data);
    $this->db->where('idx', $idx);
    return $this->db->update(DB_RESERVATION);
  }
*/
}
?>
