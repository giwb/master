<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 데스크 페이지 클래스
class Desk extends Admin_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('cookie', 'security', 'url', 'my_array_helper'));
    $this->load->library(array('image_lib'));
    $this->load->model(array('desk_model'));
  }

  /**
   * 데스크 인덱스
   *
   * @return view
   * @author bjchoi
   **/
  public function index()
  {
    $this->_viewPage('desk/index');
  }

  /**
   * 기사 관리
   *
   * @return view
   * @author bjchoi
   **/
  public function article()
  {
    $viewData['list'] = $this->desk_model->listArticle();
    $viewData['max'] = count($viewData['list']);
    $this->_viewPage('desk/article', $viewData);
  }

  /**
   * 기사 열람
   *
   * @return view
   * @author bjchoi
   **/
  public function article_view($idx)
  {
    $viewData['view'] = $this->desk_model->viewArticle(html_escape($idx));
    $this->_viewPage('desk/article_view', $viewData);
  }

  /**
   * 기사 등록
   *
   * @return view
   * @author bjchoi
   **/
  public function article_post($idx=NULL)
  {
    $viewData['userData'] = $this->load->get_var('userData');
    $viewData['category'] = $this->desk_model->listCategory();

    if (!is_null($idx)) {
      $viewData['view'] = $this->desk_model->viewArticle(html_escape($idx));
    }

    $this->_viewPage('desk/article_post', $viewData);
  }

  /**
   * 기사 등록/수정
   *
   * @return view
   * @author bjchoi
   **/
  public function article_update()
  {
    $now = time();
    $inputData = $this->input->post();

    if (empty($inputData['title'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_no_title'));
    }
    if (empty($inputData['category'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_no_category'));
    }
    if (empty($inputData['content'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_no_content'));
    }
    if (empty($inputData['viewing_date']) || empty($inputData['viewing_time'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_no_viewing'));
    }
    if (empty($result)) {
      if (!empty($inputData['idx'])) {
        $idx = html_escape($inputData['idx']);
        $updateValues = array(
          'category'    => html_escape($inputData['category']),
          'title'       => html_escape($inputData['title']),
          'content'     => html_escape($inputData['content']),
          'viewing_at'  => strtotime(html_escape($inputData['viewing_date']) . ' ' . html_escape($inputData['viewing_time'])),
          'updated_by'  => html_escape($inputData['useridx']),
          'updated_at'  => $now,
        );
        $this->desk_model->update(DB_ARTICLE, $updateValues, $idx);
      } else {
        $updateValues = array(
          'category'    => html_escape($inputData['category']),
          'title'       => html_escape($inputData['title']),
          'content'     => html_escape($inputData['content']),
          'viewing_at'  => strtotime(html_escape($inputData['viewing_date']) . ' ' . html_escape($inputData['viewing_time'])),
          'created_by'  => html_escape($inputData['useridx']),
          'created_at'  => $now,
        );
        $this->desk_model->insert(DB_ARTICLE, $updateValues);
      }
      $result = array('error' => 0, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 기사 삭제
   *
   * @return view
   * @author bjchoi
   **/
  public function article_delete()
  {
    $now = time();
    $idx = html_escape($this->input->post('idx'));
    $userData = $this->load->get_var('userData');

    $updateValues = array(
      'deleted_by' => $userData['idx'],
      'deleted_at' => $now
    );
    $this->desk_model->update(DB_ARTICLE, $updateValues, $idx);

    redirect('/desk/article');
  }

  /**
   * 분류 편집
   *
   * @return view
   * @author bjchoi
   **/
  public function category_update()
  {
    $now = time();
    $inputData = $this->input->post();
    $message = '<option value="">분류를 선택해주세요</option>';

    if (!empty($inputData['category_code'][0]) && !empty($inputData['category_name'][0])) {
      $this->desk_model->delete(DB_ARTICLE_CATEGORY);

      foreach ($inputData['category_code'] as $key => $value) {
        if (!empty($value) && !empty($inputData['category_name'][$key])) {
          $code = html_escape($value);
          $name = html_escape($inputData['category_name'][$key]);
          $updateValues = array(
            'code' => $code,
            'name' => $name,
          );
          $message .= "<option value='" . $code . "'>" . $name . "</option>";
          $rtn = $this->desk_model->insert(DB_ARTICLE_CATEGORY, $updateValues);
        }
      }
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
    } else {
      $result = array('error' => 0, 'message' => $message);
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 페이지 표시
   *
   * @param $viewPage
   * @param $viewData
   * @return view
   * @author bjchoi
   **/
  private function _viewPage($viewPage, $viewData=NULL)
  {
    $this->load->view('desk/header');
    $this->load->view($viewPage, $viewData);
    $this->load->view('desk/footer');
  }
}
?>
