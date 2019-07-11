<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 관리 페이지 클래스
class Admin extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('url', 'my_array_helper'));
    $this->load->model(array('admin_model'));
  }

  /**
   * 관리자 인덱스
   *
   * @return view
   * @author bjchoi
   **/
  public function index()
  {
    $viewData['cntTotalMember'] = $this->admin_model->cntTotalMember();
    $viewData['cntTotalTour'] = $this->admin_model->cntTotalTour();
    $viewData['cntTotalCustomer'] = $this->admin_model->cntTotalCustomer();

    $this->_viewPage('admin/index', $viewData);
  }

  /**
   * 진행중 산행 목록
   *
   * @return view
   * @author bjchoi
   **/
  public function list_progress()
  {
    $viewData['list'] = $this->admin_model->listProgress();

    $this->_viewPage('admin/list_progress', $viewData);
  }

  /**
   * 다녀온 산행 목록
   *
   * @return view
   * @author bjchoi
   **/
  public function list_closed()
  {
    // PHP Ver 7.x
    //$syear = !empty($this->input->get('syear')) ? $this->input->get('syear') : date('Y');
    //$smonth = !empty($this->input->get('smonth')) ? $this->input->get('syear') : date('m');

    // PHP Ver 5.x
    $syear = $this->input->get('syear') ? $this->input->get('syear') : date('Y');
    $smonth = $this->input->get('smonth') ? $this->input->get('syear') : date('m');
    $viewData['list'] = $this->admin_model->listClosed($syear, $smonth, STATUS_CLOSED);

    $this->_viewPage('admin/list_closed', $viewData);
  }

  /**
   * 취소된 산행 목록
   *
   * @return view
   * @author bjchoi
   **/
  public function list_canceled()
  {
    // PHP Ver 7.x
    //$syear = !empty($this->input->get('syear')) ? $this->input->get('syear') : date('Y');
    //$smonth = !empty($this->input->get('smonth')) ? $this->input->get('syear') : date('m');

    // PHP Ver 5.x
    $syear = $this->input->get('syear') ? $this->input->get('syear') : date('Y');
    $smonth = $this->input->get('smonth') ? $this->input->get('syear') : date('m');
    $viewData['list'] = $this->admin_model->listClosed($syear, $smonth, STATUS_CANCLE);

    $this->_viewPage('admin/list_canceled', $viewData);
  }

  /**
   * 전체 회원 목록
   *
   * @return view
   * @author bjchoi
   **/
  public function list_members()
  {
    $viewData['list'] = $this->admin_model->listMembers();

    $this->_viewPage('admin/list_members', $viewData);
  }

  /**
   * 출석체크 보기
   *
   * @return view
   * @author bjchoi
   **/
  public function list_attendance()
  {
    // PHP Ver 7.x
    //$viewData['viewType'] = !empty($this->input->get('action')) ? $this->input->get('action') : '';

    // PHP Ver 5.x
    $viewData['viewType'] = $this->input->get('action') ? $this->input->get('action') : '';
    $cnt = 0;
    $buf = 0;

    // 산행 추출
    $dateStart = '2019-04-06';
    $dateEnd = date('Y-m-d');
    $viewData['listAttendanceNotice'] = $this->admin_model->listAttendanceNotice($dateStart, $dateEnd);
    $maxNotice = count($viewData['listAttendanceNotice']);
    $viewData['width'] = 88 / ($maxNotice);

    // 랭킹별 닉네임 추출
    $viewData['listNickname'] = $this->admin_model->listAttendanceNickname();

    // PHP Ver 7.x
    //if (!empty($viewData['listNickname'])) {
    // PHP Ver 5.x
    if ($viewData['listNickname'] != '') {
      foreach ($viewData['listNickname'] as $key => $list) {
        if ($buf != $list['cnt']) {
          $viewData['listNickname'][$key]['rank'] = $key;
          $viewData['listNickname'][$key]['rank']++;
        }

        foreach ($viewData['listAttendanceNotice'] as $notice) {
          $viewData['listNickname'][$key]['listNotice'][] = $this->admin_model->checkAttendance($notice['idx'], $list['nickname']);
        }
      }
    }

    $this->_viewPage('admin/list_attendance', $viewData);
  }

  /**
   * 출석체크 추출
   *
   * @return redirect
   * @author bjchoi
   **/
  public function get_attendance()
  {
    // 산행 추출
    $dateStart = '2019-04-06';
    $dateEnd = date('Y-m-d');
    $dataNotice = $this->admin_model->listAttendanceNotice($dateStart, $dateEnd);
    $maxNotice = count($dataNotice);
    $listMember = array();

    foreach ($dataNotice as $value) {
      $date = date("ymd", strtotime($value['startdate']));
      $entryMember = $this->admin_model->getAttendanceNickname($value['idx']);

      $cnt = 0;
      foreach ($entryMember as $key => $entry) {
        // PHP Ver 7.x
        //if (!empty($arrDummy[$value['idx']])) {
        // PHP Ver 5.x
        if ($arrDummy[$value['idx']] != '') {
          $cnt = 1;
          foreach ($arrDummy[$value['idx']] as $list) {
            if ($list == $entry['nickname']) $cnt++;
          }
        }
        $arrDummy[$value['idx']][] = $entry['nickname'];
        if ($cnt > 1) $nickname = $entry['nickname'] . $cnt; else $nickname = $entry['nickname'];
        $listMember[$value['idx']][] = $nickname;
      }
    }

    // 이전 데이터 삭제
    $this->admin_model->deleteAttendance();

    // 새로운 데이터 갱신
    foreach ($listMember as $key => $list) {
      foreach ($list as $nickname) {
        $insertData = array(
          'rescode' => $key,
          'nickname' => $nickname,
        );
        $rtn = $this->admin_model->insertAttendance($insertData);
      }
    }

    // PHP Ver 7.x
    //if (empty($rtn)) {
    // PHP Ver 5.x
    if (!$rtn) {
      $result['message'] = '에러가 발생했습니다.';
    } else {
      $result['message'] = '최신 데이터로 갱신했습니다.';
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
    $this->load->view('admin/header');
    $this->load->view($viewPage, $viewData);
    $this->load->view('admin/footer');
  }
}
?>