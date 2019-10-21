<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 파일 모델
class File_model extends CI_Model
{
  // 파일 취득
  public function getFile($page, $page_idx, $type=NULL, $limit=NULL)
  {
    $this->db->select('type, filename')
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

  // 파일 등록
  public function insertFile($data)
  {
    $this->db->insert(DB_FILES, $data);
    return $this->db->insert_id();
  }

  // 파일 삭제
  public function deleteFile($data)
  {
    $this->db->where('filename', $data);
    return $this->db->delete(DB_FILES);
  }
}
?>
