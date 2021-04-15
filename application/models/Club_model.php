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
  public function listClub($search=NULL, $keyword=NULL, $order=NULL)
  {
    $this->db->select('*')
          ->from(DB_CLUBS)
          ->where('deleted_at', NULL);

    if (!is_null($search) && !is_null($keyword)) {
      $this->db->like($search, $keyword);
    }
    if (!empty($order)) {
      $this->db->order_by('idx', 'desc');
    } else {
      $this->db->order_by('title', 'asc');
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
  public function listAuth($limit=NULL)
  {
    $this->db->select('nickname, COUNT(idx) AS cnt')
          ->from(DB_AUTH)
          ->where('nickname !=', '캔총무')
          ->where('nickname !=', '아띠')
          ->group_by('nickname')
          ->order_by('cnt', 'desc')
          ->order_by('nickname', 'asc');

    if (!is_null($limit)) {
      $this->db->limit($limit);
    }

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
  public function listAlbum($clubIdx, $paging, $search=NULL)
  {
    $this->db->select('a.*, b.nickname, c.idx AS notice_idx, c.subject AS notice_subject, c.startdate AS notice_startdate')
          ->from(DB_ALBUM . ' a')
          ->join(DB_MEMBER . ' b', 'a.created_by=b.idx', 'left')
          ->join(DB_NOTICE . ' c', 'a.notice_idx=c.idx', 'left')
          ->where('a.club_idx', $clubIdx)
          ->where('a.deleted_at', NULL)
          ->order_by('c.startdate, a.idx', 'desc');

    if (!empty($search['created_by'])) {
      $this->db->where('a.created_by', $search['created_by']);
    }
    if (!empty($paging)) {
      $this->db->limit($paging['perPage'], $paging['nowPage']);
    }

    return $this->db->get()->result_array();
  }

  // 앨범 카운트
  public function cntAlbum($clubIdx, $search=NULL)
  {
    $this->db->select('COUNT(*) AS cnt')
          ->from(DB_ALBUM)
          ->where('club_idx', $clubIdx)
          ->where('deleted_at', NULL);

    if (!empty($search['created_by'])) {
      $this->db->where('created_by', $search['created_by']);
    }

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

  // 추천 사진
  public function listBestPhoto($clubIdx, $paging=NULL)
  {
    $this->db->select('a.idx, a.filename, a.refer, b.subject, c.nickname, d.idx AS notice_idx, d.subject AS notice_subject, d.startdate AS notice_startdate')
          ->from(DB_FILES . ' a')
          ->join(DB_ALBUM . ' b', 'b.idx=a.page_idx', 'left')
          ->join(DB_MEMBER . ' c', 'b.created_by=c.idx', 'left')
          ->join(DB_NOTICE . ' d', 'b.notice_idx=d.idx', 'left')
          ->where('a.pickup !=', NULL)
          ->where('b.club_idx', $clubIdx)
          ->where('b.deleted_at', NULL)
          ->order_by('d.startdate desc, b.idx desc, a.idx asc');

    if (!empty($paging)) {
      $this->db->limit($paging['perPage'], $paging['nowPage']);
    }

    return $this->db->get()->result_array();
  }

  // 추천 사진 보기
  public function viewBestPhoto($clubIdx, $idx)
  {
    $this->db->select('a.idx, a.filename, a.refer, b.subject, b.content, c.idx AS user_idx, c.nickname, d.idx AS notice_idx, d.subject AS notice_subject, d.startdate AS notice_startdate')
          ->from(DB_FILES . ' a')
          ->join(DB_ALBUM . ' b', 'b.idx=a.page_idx', 'left')
          ->join(DB_MEMBER . ' c', 'b.created_by=c.idx', 'left')
          ->join(DB_NOTICE . ' d', 'b.notice_idx=d.idx', 'left')
          ->where('a.idx', $idx)
          ->where('b.club_idx', $clubIdx)
          ->where('b.deleted_at', NULL);
    return $this->db->get()->row_array(1);
  }

  // 랭킹 - 산행 참여
  public function rankingRescount($clubIdx, $limit=NULL)
  {
    $this->db->select('nickname, rescount AS cnt')
          ->from(DB_MEMBER)
          ->where('club_idx', $clubIdx)
          ->order_by('cnt', 'desc')
          ->limit($limit);
    return $this->db->get()->result_array();
  }

  // 랭킹 - 홈페이지 방문
  public function rankingVisit($clubIdx, $limit=NULL)
  {
    $this->db->select('b.nickname, COUNT(a.idx) AS cnt')
          ->from(DB_VISITOR . ' a')
          ->join(DB_MEMBER . ' b', 'a.created_by=b.idx', 'left')
          ->where('a.club_idx', $clubIdx)
          ->where('a.created_by !=', NULL)
          ->where_not_in('a.created_by', array(1,2))
          ->group_by('b.nickname', 'desc')
          ->order_by('cnt', 'desc')
          ->limit($limit);
    return $this->db->get()->result_array();
  }

  // 페이지 주소로 찾기
  public function getUrl($url)
  {
    $this->db->select('idx')
          ->from(DB_CLUBS)
          ->where('url', $url);
    return $this->db->get()->row_array(1);
  }

  // 도메인으로 찾기
  public function getDomain($domain)
  {
    $this->db->select('idx')
          ->from(DB_CLUBS)
          ->where('domain', $domain);
    return $this->db->get()->row_array(1);
  }

  // 여행기 - 동영상 목록
  public function listVideo()
  {
    $this->db->select('*')
          ->from(DB_VIDEOS)
          ->where('deleted_at', NULL)
          ->order_by('idx', 'desc');
    return $this->db->get()->result_array();
  }
}
?>
