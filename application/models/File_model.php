<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 파일 모델
class File_model extends CI_Model
{
  // 파일 취득
  public function getFile($page, $page_idx, $type=NULL, $limit=NULL)
  {
    $this->db->select('idx, type, pickup, filename')
          ->from(DB_FILES)
          ->where('page', $page)
          ->where('page_idx', $page_idx)
          ->order_by('idx', 'asc');

    if (!is_null($type)) {
      $this->db->where('type', $type);
    }
    if (!is_null($limit)) {
      $this->db->limit($limit);
    }

    return $this->db->get()->result_array();
  }

  // 파일 보기
  public function viewFile($filename, $idx=NULL)
  {
    $this->db->select('*')
          ->from(DB_FILES);

    if (!empty($idx)) {
      $this->db->where('idx', $idx);
    } else {
      $this->db->where('filename', $filename);
    }
    return $this->db->get()->row_array(1);
  }

  // 파일 등록
  public function insertFile($data)
  {
    $this->db->insert(DB_FILES, $data);
    return $this->db->insert_id();
  }

  // 파일 수정
  public function updateFile($data, $idx)
  {
    $this->db->set($data);
    $this->db->where('idx', $idx);
    return $this->db->update(DB_FILES);
  }

  // 파일 삭제
  public function deleteFile($filename)
  {
    $this->db->where('filename', $filename);
    return $this->db->delete(DB_FILES);
  }
}
?>
