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

  // 스토리 목록
  public function listStory($clubIdx)
  {
    $this->db->select('a.*, b.nickname AS user_nickname, c.filename')
          ->from(DB_STORY . ' a')
          ->join(DB_MEMBER . ' b', 'a.member_idx=b.idx', 'left')
          ->join(DB_FILES . ' c', 'c.page="story" AND a.idx=c.page_idx', 'left')
          ->where('a.club_idx', $clubIdx)
          ->order_by('a.created_at', 'desc');
    return $this->db->get()->result_array();
  }

  // 스토리 보기
  public function viewStory($clubIdx, $storyIdx)
  {
    $this->db->select('*')
          ->from(DB_STORY)
          ->where('idx', $storyIdx)
          ->where('club_idx', $clubIdx);
    return $this->db->get()->row_array(1);
  }

  // 스토리 등록
  public function insertStory($data)
  {
    $this->db->insert(DB_STORY, $data);
    return $this->db->insert_id();
  }

  // 스토리 수정
  public function updateStory($data, $clubIdx, $storyIdx)
  {
    $this->db->set($data);
    $this->db->where('club_idx', $clubIdx);
    $this->db->where('idx', $storyIdx);
    return $this->db->update(DB_STORY);
  }

  // 스토리 댓글
  public function listStoryReply($clubIdx, $storyIdx)
  {
    $this->db->select('a.idx, a.content, FROM_UNIXTIME(a.created_at, "%Y/%m/%d %H:%i:%s") AS created_at, b.idx AS member_idx, b.nickname')
          ->from(DB_STORY_REPLY . ' a')
          ->join(DB_MEMBER . ' b', 'a.created_by=b.idx', 'left')
          ->where('a.club_idx', $clubIdx)
          ->where('a.story_idx', $storyIdx);
    return $this->db->get()->result_array();
  }

  // 스토리 댓글 카운트
  public function cntStoryReply($clubIdx, $storyIdx)
  {
    $this->db->select('COUNT(idx) AS cnt')
          ->from(DB_STORY_REPLY)
          ->where('club_idx', $clubIdx)
          ->where('story_idx', $storyIdx);
    return $this->db->get()->row_array(1);
  }

  // 스토리 댓글 등록
  public function insertStoryReply($data)
  {
    $this->db->insert(DB_STORY_REPLY, $data);
    return $this->db->insert_id();
  }

  // 스토리 리액션 회원별 목록
  public function listStoryReaction($clubIdx, $userIdx)
  {
    $this->db->select('*')
          ->from(DB_STORY_REACTION)
          ->where('club_idx', $clubIdx)
          ->where('created_by', $userIdx);
    return $this->db->get()->result_array();
  }

  // 스토리 리액션 카운트
  public function cntStoryReaction($clubIdx, $storyIdx, $typeReaction)
  {
    $this->db->select('COUNT(*) AS cnt')
          ->from(DB_STORY_REACTION)
          ->where('club_idx', $clubIdx)
          ->where('story_idx', $storyIdx)
          ->where('type_reaction', $typeReaction);
    return $this->db->get()->row_array(1);
  }

  // 스토리 리액션 보기
  public function viewStoryReaction($clubIdx, $storyIdx, $userIdx)
  {
    $this->db->select('*')
          ->from(DB_STORY_REACTION)
          ->where('club_idx', $clubIdx)
          ->where('story_idx', $storyIdx)
          ->where('created_by', $userIdx);
    return $this->db->get()->row_array(1);
  }

  // 스토리 리액션 추가
  public function insertStoryReaction($data)
  {
    return $this->db->insert(DB_STORY_REACTION, $data);
  }

  // 스토리 리액션 삭제
  public function deleteStoryReaction($clubIdx, $storyIdx, $userIdx)
  {
    $this->db->where('club_idx', $clubIdx);
    $this->db->where('story_idx', $storyIdx);
    $this->db->where('created_by', $userIdx);
    return $this->db->delete(DB_STORY_REACTION);
  }
}
?>
