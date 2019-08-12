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
    // 등록된 산행 목록
    $viewData['listNotice'] = $this->admin_model->listNotice();

    // 현재 회원수
    $viewData['cntTotalMember'] = $this->admin_model->cntTotalMember();
    // 다녀온 산행횟수
    $viewData['cntTotalTour'] = $this->admin_model->cntTotalTour();
    // 다녀온 산행 인원수
    $viewData['cntTotalCustomer'] = $this->admin_model->cntTotalCustomer();

    $this->_viewPage('admin/index', $viewData);
  }

  /** ---------------------------------------------------------------------------------------
   * 산행관리
  --------------------------------------------------------------------------------------- **/

  /**
   * 진행중 산행 목록
   *
   * @return view
   * @author bjchoi
   **/
  public function main_list_progress()
  {
    $viewData['list'] = $this->admin_model->listProgress();

    $this->_viewPage('admin/main_list_progress', $viewData);
  }

  /**
   * 진행중 산행 예약 보기
   *
   * @return view
   * @author bjchoi
   **/
  public function main_view_progress($rescode)
  {
    $viewData['view'] = $this->admin_model->viewProgress(html_escape($rescode));

    $this->_viewPage('admin/main_view_progress', $viewData);
  }

  /**
   * 다녀온 산행 목록
   *
   * @return view
   * @author bjchoi
   **/
  public function main_list_closed()
  {
    // PHP Ver 7.x
    //$syear = !empty($this->input->get('syear')) ? $this->input->get('syear') : date('Y');
    //$smonth = !empty($this->input->get('smonth')) ? $this->input->get('syear') : date('m');

    // PHP Ver 5.x
    $syear = $this->input->get('syear') ? $this->input->get('syear') : date('Y');
    $smonth = $this->input->get('smonth') ? $this->input->get('syear') : date('m');
    $viewData['list'] = $this->admin_model->listClosed($syear, $smonth, STATUS_CLOSED);

    $this->_viewPage('admin/main_list_closed', $viewData);
  }

  /**
   * 취소된 산행 목록
   *
   * @return view
   * @author bjchoi
   **/
  public function main_list_canceled()
  {
    // PHP Ver 7.x
    //$syear = !empty($this->input->get('syear')) ? $this->input->get('syear') : date('Y');
    //$smonth = !empty($this->input->get('smonth')) ? $this->input->get('syear') : date('m');

    // PHP Ver 5.x
    $syear = $this->input->get('syear') ? $this->input->get('syear') : date('Y');
    $smonth = $this->input->get('smonth') ? $this->input->get('syear') : date('m');
    $viewData['list'] = $this->admin_model->listClosed($syear, $smonth, STATUS_CANCLE);

    $this->_viewPage('admin/main_list_canceled', $viewData);
  }

  /**
   * 산행 등록
   *
   * @return view
   * @author bjchoi
   **/
  public function main_entry()
  {
    $viewData = array();
    $this->_viewPage('admin/main_entry', $viewData);
  }

  /** ---------------------------------------------------------------------------------------
   * 회원관리
  --------------------------------------------------------------------------------------- **/

  /**
   * 전체 회원 목록
   *
   * @return view
   * @author bjchoi
   **/
  public function member_list()
  {
    $viewData['list'] = $this->admin_model->listMembers();

    $this->_viewPage('admin/member_list', $viewData);
  }

  /**
   * 회원 정보 보기
   *
   * @return view
   * @author bjchoi
   **/
  public function member_view($idx)
  {
    $viewData['view'] = $this->admin_model->viewMember(html_escape($idx));
    $viewData['view']['birthday'] = explode('/', $viewData['view']['birthday']);
    $viewData['view']['phone'] = explode('-', $viewData['view']['phone']);

    // 산행/예약횟수 구하기
    $max = 0; $res = 0;
    $viewData['view']['cntPersonalReservation'] = $this->admin_model->cntPersonalReservation($viewData['view']['userid']);
    foreach ($viewData['view']['cntPersonalReservation'] as $value) {
      // 예약횟수는 자신 이외의 사람을 포함하여 전체 예약한 숫자
      $max += $value['cnt'];

      // 산행횟수는 자신만 카운트
      if ($value['cnt'] != 0) {
        $res++;
      }
    }

    // 산행횟수
    $viewData['view']['cntPersonalReservation'] = $res;

    // 예약횟수
    $viewData['view']['cntTotalReservation'] = $max;

    // 레벨
    $viewData['view']['memberLevel'] = $viewData['view']['cntTotalReservation'] - $viewData['view']['penalty'];

    $this->_viewPage('admin/member_view', $viewData);
  }

  /**
   * 회원 정보 수정
   *
   * @return view
   * @author bjchoi
   **/
  public function member_update()
  {
    $result = 0;
    $this->output->set_output(json_encode($result));
  }

  /**
   * 회원 정보 삭제
   *
   * @return view
   * @author bjchoi
   **/
  public function member_delete()
  {
    $idx = html_escape($this->input->post());

    $result = 0;
    $this->output->set_output(json_encode($result));
  }

  /**
   * 회원 등록
   *
   * @return view
   * @author bjchoi
   **/
  public function member_entry()
  {
    $viewData = array();
    $this->_viewPage('admin/member_entry', $viewData);
  }

  /** ---------------------------------------------------------------------------------------
   * 출석현황
  --------------------------------------------------------------------------------------- **/

  /**
   * 출석체크 보기
   *
   * @return view
   * @author bjchoi
   **/
  public function attendance_list()
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

    $this->_viewPage('admin/attendance_list', $viewData);
  }

  /**
   * 산행지로 보기
   *
   * @return view
   * @author bjchoi
   **/
  public function attendance_mountain()
  {
    $viewData = array();
    $this->_viewPage('admin/attendance_mountain', $viewData);
  }

  /**
   * 출석체크 추출
   *
   * @return redirect
   * @author bjchoi
   **/
  public function attendance_make()
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

  /** ---------------------------------------------------------------------------------------
   * 활동관리
  --------------------------------------------------------------------------------------- **/

  /**
   * 활동관리 - 회원 활동 목록
   *
   * @return view
   * @author bjchoi
   **/
  public function log_user()
  {
    $viewData = array();
    $this->_viewPage('admin/log_user', $viewData);
  }

  /**
   * 활동관리 - 관리자 활동 목록
   *
   * @return view
   * @author bjchoi
   **/
  public function log_admin()
  {
    $viewData = array();
    $this->_viewPage('admin/log_admin', $viewData);
  }

  /** ---------------------------------------------------------------------------------------
   * 설정
  --------------------------------------------------------------------------------------- **/

  /**
   * 설정 - 대문관리
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_front()
  {
    $viewData['listFront'] = $this->admin_model->listFront();

    $this->_viewPage('admin/setup_front', $viewData);
  }

  /**
   * 설정 - 대문등록
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_front_insert()
  {
    if ($_FILES['file_obj']['type'] == 'image/jpeg') {
      $filename = time() . mt_rand(10000, 99999) . ".jpg";

      if (move_uploaded_file($_FILES['file_obj']['tmp_name'], PATH_FRONT . $filename)) {
        $maxIdx = $this->admin_model->getFrontSortMaxIdx();
        $insertData = array(
          'sort_idx' => $maxIdx['sort_idx'] + 1,
          'filename' => $filename,
        );
        $this->admin_model->insertFront($insertData);

        $result = array(
          'error' => 0,
          'message' => base_url() . URL_FRONT . $filename,
          'filename' => $filename
        );
      } else {
        $result = array(
          'error' => 1,
          'message' => '사진 업로드에 실패했습니다.'
        );
      }
    } else {
      $result = array(
        'error' => 1,
        'message' => 'jpg 형식의 사진만 업로드 할 수 있습니다.'
      );
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 설정 - 정렬 순서 수정
   *
   * @return view
   * @author bjchoi
   **/

  public function setup_front_sort()
  {
    $sortIdx = html_escape($this->input->post('sort_idx'));
    $listFront = $this->admin_model->listFront();

    foreach ($listFront as $key => $value) {
      $result = $this->admin_model->updateFrontSortIdx($value['filename'], $sortIdx[$key]);
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 설정 - 대문삭제
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_front_delete()
  {
    $filename = html_escape($this->input->post('idx'));

    // 데이터 삭제
    $result = $this->admin_model->deleteFront($filename);

    // 파일 삭제
    if (file_exists(PATH_FRONT . $filename)) {
      unlink(PATH_FRONT . $filename);
    }

    // 정렬 순서 갱신
    $listFront = $this->admin_model->listFront();

    foreach ($listFront as $key => $value) {
      $this->admin_model->updateFrontSortIdx($value['filename'], $key + 1);
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 설정 - 달력관리
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_calendar()
  {
    $viewData = array();
    $this->_viewPage('admin/setup_calendar', $viewData);
  }

  /**
   * 설정 - 차종등록
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_bustype()
  {
    $viewData = array();
    $this->_viewPage('admin/setup_bustype', $viewData);
  }

  /**
   * 설정 - 문자양식보기
   *
   * @return view
   * @author bjchoi
   **/
  public function setup_sms()
  {
    $viewData = array();
    $this->_viewPage('admin/setup_sms', $viewData);
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
    $headerData['uri'] = $_SERVER['REQUEST_URI'];
    $this->load->view('admin/header', $headerData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('admin/footer');
  }
}
?>
