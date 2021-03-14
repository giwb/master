<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 클럽 여행기 모델
class Travelog_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  // 여행기 목록
  public function listTravelog($clubIdx, $type=NULL, $paging=NULL)
  {
    $this->db->select('a.*, b.idx AS user_idx, b.nickname AS user_nickname')
          ->from(DB_TRAVELOG . ' a')
          ->join(DB_MEMBER . ' b', 'a.created_by=b.idx', 'left')
          ->where('a.club_idx', $clubIdx)
          ->where('a.deleted_at', NULL)
          ->order_by('a.created_at', 'desc');

    if (!is_null($type)) {
      $this->db->where('category', $type);
    }
    if (!is_null($paging)) {
      $this->db->limit($paging['perPage'], $paging['nowPage']);
    }

    return $this->db->get()->result_array();
  }

  // 여행기 카운트
  public function cntTravelog($clubIdx)
  {
    $this->db->select('COUNT(*) AS cnt')
          ->from(DB_TRAVELOG)
          ->where('club_idx', $clubIdx)
          ->where('deleted_at', NULL);
    return $this->db->get()->row_array(1);
  }

  // 여행기 보기
  public function viewTravelog($clubIdx, $idx=NULL, $type=NULL)
  {
    $this->db->select('a.*, b.idx AS user_idx, b.nickname AS user_nickname')
          ->from(DB_TRAVELOG . ' a')
          ->join(DB_MEMBER . ' b', 'a.created_by=b.idx', 'left')
          ->where('a.club_idx', $clubIdx)
          ->where('a.deleted_at', NULL);

    if (!is_null($idx)) {
      $this->db->where('a.idx', $idx);
    }
    if (!is_null($type)) {
      $this->db->where('a.category', $type);
      $this->db->order_by('a.created_at', 'desc');
    }

    return $this->db->get()->row_array(1);
  }

  // 여행기 등록
  public function insert($data)
  {
    $this->db->insert(DB_TRAVELOG, $data);
    return $this->db->insert_id();
  }

  // 여행기 수정
  public function update($data, $idx)
  {
    $this->db->set($data);
    $this->db->where('idx', $idx);
    return $this->db->update(DB_TRAVELOG);
  }

  // 여행기 댓글
  public function listStoryReply($storyIdx, $replyType, $parentIdx=NULL)
  {
    $this->db->select('a.idx, a.parent_idx, a.content, a.created_by, a.created_at, a.updated_at, b.nickname')
          ->from(DB_STORY_REPLY . ' a')
          ->join(DB_MEMBER . ' b', 'a.created_by=b.idx', 'left')
          ->where('a.story_idx', $storyIdx)
          ->where('a.reply_type', $replyType)
          ->where('a.deleted_at', NULL)
          ->order_by('a.idx', 'desc');

    if (!is_null($parentIdx)) {
      $this->db->where('a.parent_idx', $parentIdx);
    } else {
      $this->db->where('a.parent_idx', 0);
    }

    return $this->db->get()->result_array();
  }

  // 여행기 댓글 보기 (댓글 삭제 확인용)
  public function viewStoryReply($idx)
  {
    $this->db->select('a.*, b.nickname')
          ->from(DB_STORY_REPLY . ' a')
          ->join(DB_MEMBER . ' b', 'a.created_by=b.idx', 'left')
          ->where('a.idx', $idx);
    return $this->db->get()->row_array(1);
  }

  // 여행기 댓글 카운트
  public function cntStoryReply($storyIdx, $replyType)
  {
    $this->db->select('COUNT(idx) AS cnt')
          ->from(DB_STORY_REPLY)
          ->where('story_idx', $storyIdx)
          ->where('reply_type', $replyType)
          ->where('deleted_at', NULL);
    return $this->db->get()->row_array(1);
  }

  // 여행기 댓글 등록
  public function insertStoryReply($data)
  {
    $this->db->insert(DB_STORY_REPLY, $data);
    return $this->db->insert_id();
  }

  // 여행기 댓글 수정
  public function updateStoryReply($data, $storyIdx, $replyType, $storyReplyIdx=NULL, $replyParentIdx=NULL)
  {
    $this->db->set($data);
    $this->db->where('story_idx', $storyIdx);
    $this->db->where('reply_type', $replyType);

    if (!is_null($storyReplyIdx)) {
      $this->db->where('idx', $storyReplyIdx);
    }
    if (!is_null($replyParentIdx)) {
      $this->db->where('parent_idx', $replyParentIdx);
    }

    return $this->db->update(DB_STORY_REPLY);
  }

  // 여행기 리액션 회원별 목록
  public function listStoryReaction($clubIdx, $userIdx, $reactionType)
  {
    $this->db->select('*')
          ->from(DB_STORY_REACTION)
          ->where('club_idx', $clubIdx)
          ->where('reaction_type', $reactionType)
          ->where('created_by', $userIdx);
    return $this->db->get()->result_array();
  }

  // 여행기 리액션 카운트
  public function cntStoryReaction($storyIdx, $reactionType, $reactionKind)
  {
    $this->db->select('COUNT(*) AS cnt')
          ->from(DB_STORY_REACTION)
          ->where('story_idx', $storyIdx)
          ->where('reaction_type', $reactionType)
          ->where('reaction_kind', $reactionKind);
    return $this->db->get()->row_array(1);
  }

  // 여행기 리액션 보기
  public function viewStoryReaction($storyIdx, $reactionType, $userIdx, $shareType=NULL)
  {
    $this->db->select('reaction_kind')
          ->from(DB_STORY_REACTION)
          ->where('story_idx', $storyIdx)
          ->where('reaction_type', $reactionType)
          ->where('created_by', $userIdx)
          ->order_by('created_at', 'desc');

    if (!is_null($shareType)) {
      $this->db->where('share_type', $shareType);
    }

    return $this->db->get()->row_array(1);
  }

  // 여행기 리액션 추가
  public function insertStoryReaction($data)
  {
    return $this->db->insert(DB_STORY_REACTION, $data);
  }

  // 여행기 리액션 삭제
  public function deleteStoryReaction($storyIdx, $userIdx, $reactionType=NULL)
  {
    $this->db->where('story_idx', $storyIdx);
    $this->db->where('created_by', $userIdx);

    if (!empty($reactionType)) {
      $this->db->where('reaction_type', $reactionType);
    }

    return $this->db->delete(DB_STORY_REACTION);
  }
}
?>
