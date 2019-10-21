<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 첫 페이지 클래스
class Welcome extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('url', 'my_array_helper'));
    $this->load->library('session');
    $this->load->model(array('admin_model', 'club_model', 'file_model', 'notice_model', 'story_model'));
  }

  /**
   * 클럽 메인 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function index($idx=NULL)
  {
    if (is_null($idx)) {
      $idx = 1; // 최초는 경인웰빙
    }

    $viewData['view'] = $this->club_model->viewClub($idx);
    $viewData['view']['photo'] = array();
    $viewData['view']['content'] = nl2br(reset_html_escape($viewData['view']['content']));

    $files = $this->file_model->getFile('club', $idx);

    foreach ($files as $key => $value) {
      if (!$value['filename'] == '') {
        $viewData['view']['photo'][$key] = $value['filename'];
      } else {
        $viewData['view']['photo'][$key] = '';
      }
    }

    // 등록된 산행 목록
    $viewData['listNotice'] = $this->club_model->listNotice($idx);

    // 클럽 이야기
    $viewData['listStory'] = $this->story_model->listStory($idx);

    $this->_viewPage('club/index', $viewData);
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
    $headerData['userData'] = $viewData['userData'] = $this->session->userData;
    $headerData['uri'] = 'top';
    $this->load->view('header', $headerData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('footer');
  }
}
?>
