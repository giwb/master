<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 회원 페이지 클래스
class Member extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('url', 'my_array_helper'));
    $this->load->library(array('image_lib', 'session'));
    $this->load->model(array('club_model', 'file_model', 'member_model', 'reserve_model'));
  }

  /**
   * 마이페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function index()
  {
    checkUserLogin();

    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    if ($userData['level'] == LEVEL_DRIVER) {
      // 진행 중 산행
      $viewData['listNoticeDriver'] = $this->reserve_model->listNotice($clubIdx, array(STATUS_ABLE, STATUS_CONFIRM));

      // 페이지 타이틀
      $viewData['pageTitle'] = '드라이버 페이지';

      $this->_viewPage('member/driver', $viewData);
    } else {
      // 회원 정보
      $viewData['viewMember'] = $this->member_model->viewMember($clubIdx, $userData['idx']);

      // 예약 내역
      $viewData['userReserve'] = $this->reserve_model->userReserve($clubIdx, $userData['userid']);

      foreach ($viewData['userReserve'] as $key => $value) {
        if (empty($value['cost_total'])) {
          $value['cost_total'] = $value['cost'];
        }
        if ($userData['level'] == LEVEL_LIFETIME) {
          // 평생회원 할인
          $viewData['userReserve'][$key]['view_cost'] = '<s class="text-secondary">' . number_format($value['cost_total']) . '원</s> → ' . number_format($value['cost_total'] - 5000) . '원';
          $viewData['userReserve'][$key]['real_cost'] = $value['cost_total'] - 5000;
        } elseif ($userData['level'] == LEVEL_FREE) {
          // 무료회원 할인
          $viewData['userReserve'][$key]['view_cost'] = '<s class="text-secondary">' . number_format($value['cost_total']) . '원</s> → ' . '0원';
          $viewData['userReserve'][$key]['real_cost'] = 0;
        } else {
          $viewData['userReserve'][$key]['view_cost'] = number_format($value['cost_total']) . '원';
          $viewData['userReserve'][$key]['real_cost'] = $value['cost_total'];
        }
      }

      // 예약 취소 내역 (로그)
      $viewData['userReserveCancel'] = $this->reserve_model->userReserveCancel($clubIdx, $userData['userid']);

      // 산행 내역
      $viewData['userVisit'] = $this->reserve_model->userVisit($clubIdx, $userData['userid']);

      // 산행 횟수
      $viewData['userVisitCount'] = $this->reserve_model->userVisitCount($clubIdx, $userData['userid']);

      // 포인트 내역
      $viewData['userPoint'] = $this->member_model->userPointLog($clubIdx, $userData['userid']);

      // 페널티 내역
      $viewData['userPenalty'] = $this->member_model->userPenaltyLog($clubIdx, $userData['userid']);

      // 페이지 타이틀
      $viewData['pageTitle'] = '마이페이지';

      $this->_viewPage('member/index', $viewData);
    }
  }

  /**
   * 드라이버 상세 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function driver()
  {
    checkUserLogin();

    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $noticeIdx = html_escape($this->input->get('n'));
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    if (!empty($noticeIdx)) {
      // 진행 중 산행
      $viewData['viewNotice'] = $this->reserve_model->viewNotice($clubIdx, $noticeIdx);

      // 버스 종류 확인
      $bus_type = getBusType($viewData['viewNotice']['bustype'], $viewData['viewNotice']['bus']);

      $viewData['maxSeat'] = 0; // 최대 좌석 계산
      foreach ($bus_type as $bus) {
        $viewData['maxSeat'] += $bus['seat'];
      }

      // 승객수
      $viewData['viewNotice']['count'] = cntRes($viewData['viewNotice']['idx']);

      // 승객수당
      if ($viewData['viewNotice']['count'] < 30) {
        $viewData['viewNotice']['cost_driver'] = 0;
      } elseif ($viewData['viewNotice']['count'] >= 30 && $viewData['viewNotice']['count'] < 40) {
        $viewData['viewNotice']['cost_driver'] = 40000;
      } elseif ($viewData['viewNotice']['count'] >= 30 && $viewData['viewNotice']['count'] < $viewData['maxSeat']) {
        $viewData['viewNotice']['cost_driver'] = 80000;
      } elseif ($viewData['viewNotice']['count'] == $viewData['maxSeat']) {
        $viewData['viewNotice']['cost_driver'] = 120000;
      }

      // 운행구간
      $viewData['viewNotice']['road_course'] = unserialize($viewData['viewNotice']['road_course']);
      
      // 도착지주소
      $viewData['viewNotice']['road_address'] = unserialize($viewData['viewNotice']['road_address']);

      // 거리
      $viewData['viewNotice']['road_distance'] = unserialize($viewData['viewNotice']['road_distance']);

      // 총 주행거리
      $viewData['viewNotice']['total_distance'] = 0;
      if (!empty($viewData['viewNotice']['road_distance'])) {
        foreach ($viewData['viewNotice']['road_distance'] as $value) {
          if (!empty($value)) $viewData['viewNotice']['total_distance'] += $value;
        }
      }

      // 운행 소요시간
      $viewData['viewNotice']['road_runtime'] = unserialize($viewData['viewNotice']['road_runtime']);

      // 운행 통행료
      $viewData['viewNotice']['road_cost'] = unserialize($viewData['viewNotice']['road_cost']);

      // 주유비
      $viewData['viewNotice']['driving_fuel'] = unserialize($viewData['viewNotice']['driving_fuel']);

      // 총 주유비
      if (empty($viewData['viewNotice']['driving_fuel'][1]) || empty($viewData['viewNotice']['driving_fuel'][2])) {
        $viewData['viewNotice']['total_fuel'] = 0;
      } else {
        $viewData['viewNotice']['total_fuel'] = $viewData['viewNotice']['driving_fuel'][1] * $viewData['viewNotice']['driving_fuel'][2];
      }

      // 운행비
      $viewData['viewNotice']['driving_cost'] = unserialize($viewData['viewNotice']['driving_cost']);

      // 총 운행비
      $viewData['viewNotice']['total_cost'] = 0;
      foreach ($viewData['viewNotice']['driving_cost'] as $value) {
        if (!empty($value)) $viewData['viewNotice']['total_cost'] += $value;
      }

      // 추가비용
      $viewData['viewNotice']['driving_add'] = unserialize($viewData['viewNotice']['driving_add']);

      // 추가비용 합계
      $viewData['viewNotice']['total_add'] = $viewData['viewNotice']['cost_driver'];
      foreach ($viewData['viewNotice']['driving_add'] as $value) {
        if (!empty($value)) $viewData['viewNotice']['total_cost'] += $value;
      }
    }

    // 페이지 타이틀
    $viewData['pageTitle'] = '드라이버 페이지';

    $this->_viewPage('member/driver_view', $viewData);
  }

  /**
   * 개인정보수정
   *
   * @return view
   * @author bjchoi
   **/
  public function modify()
  {
    checkUserLogin();

    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $viewData['view'] = $this->club_model->viewclub($clubIdx);

    // 회원정보
    $viewData['viewMember'] = $this->member_model->viewMember($clubIdx, $userData['idx']);

    // 생년월일 나누기
    $buf = explode('/', $viewData['viewMember']['birthday']);
    $viewData['viewMember']['birthday_year'] = $buf[0];
    $viewData['viewMember']['birthday_month'] = $buf[1];
    $viewData['viewMember']['birthday_day'] = $buf[2];

    // 전화번호 나누기
    $buf = explode('-', $viewData['viewMember']['phone']);
    $viewData['viewMember']['phone1'] = $buf[0];
    $viewData['viewMember']['phone2'] = $buf[1];
    $viewData['viewMember']['phone3'] = $buf[2];

    // 아이콘
    if (file_exists(PHOTO_PATH . $viewData['viewMember']['idx'])) {
      $viewData['viewMember']['photo'] = base_url() . PHOTO_URL . $viewData['viewMember']['idx'];
    } else {
      $viewData['viewMember']['photo'] = base_url() . 'public/images/noimage.png';
    }

    // 페이지 타이틀
    $viewData['pageTitle'] = '개인정보수정';

    $this->_viewPage('member/modify', $viewData);
  }

  /**
   * 사진 삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function photo_delete()
  {
    $userIdx = html_escape($this->input->post('userIdx'));
    $filename = html_escape($this->input->post('filename'));

    if (!empty($filename) && file_exists(UPLOAD_PATH . $filename)) unlink(UPLOAD_PATH . $filename);
    if (!empty($userIdx) && file_exists(PHOTO_PATH . $userIdx)) unlink(PHOTO_PATH . $userIdx);

    $result = array('error' => 0);
    $this->output->set_output(json_encode($result));
  }

  /**
   * 개인정보수정 처리
   *
   * @return json
   * @author bjchoi
   **/
  public function update()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $inputData = $this->input->post();

    if (empty($userData['idx'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_login'));
    } else {
      $updateValues = array(
        'club_idx'      => html_escape($clubIdx),
        'nickname'      => html_escape($inputData['nickname']),
        'realname'      => html_escape($inputData['realname']),
        'gender'        => html_escape($inputData['gender']),
        'location'      => html_escape($inputData['location']),
        'birthday'      => html_escape($inputData['birthday_year']) . '/' . html_escape($inputData['birthday_month']) . '/' . html_escape($inputData['birthday_day']),
        'birthday_type' => html_escape($inputData['birthday_type']),
        'phone'         => html_escape($inputData['phone1']) . '-' . html_escape($inputData['phone2']) . '-' . html_escape($inputData['phone3']),
      );

      // 비밀번호는 입력했을때만 저장
      if (!empty($inputData['password'])) {
        $updateValues['password'] = md5(html_escape($inputData['password']));
      }

      $rtn = $this->member_model->updateMember($updateValues, $clubIdx, $userData['idx']);

      if (!empty($rtn)) {
        // 사진 등록
        if (!empty($inputData['filename']) && file_exists(UPLOAD_PATH . $inputData['filename'])) {
          rename(UPLOAD_PATH . html_escape($inputData['filename']), PHOTO_PATH . $userData['idx']);
        }

        $result = array('error' => 0, 'message' => $this->lang->line('msg_update_complete'));
      } else {
        $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 탈퇴하기
   *
   * @return json
   * @author bjchoi
   **/
  public function quit()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $userIdx = $this->input->post('userIdx');

    if ($userData['idx'] != $userIdx) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
    } else {
      $updateValues['quitdate'] = time();
      $rtn = $this->member_model->updateMember($updateValues, $clubIdx, $userIdx);

      if (!empty($rtn)) {
        // 세션 삭제
        $this->session->unset_userdata('userData');

        $result = array('error' => 0, 'message' => '');
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 파일 업로드
   *
   * @return json
   * @author bjchoi
   **/
  public function upload()
  {
    if ($_FILES['file_obj']['type'] == 'image/jpeg') {
      $filename = time() . mt_rand(10000, 99999) . ".jpg";

      if (move_uploaded_file($_FILES['file_obj']['tmp_name'], UPLOAD_PATH . $filename)) {
        // 사진 사이즈 줄이기 (가로가 사이즈가 200보다 클 경우)
        $size = getImageSize(UPLOAD_PATH . $filename);
        if ($size[0] >= 200) {
          $this->image_lib->clear();
          $config['image_library'] = 'gd2';
          $config['source_image'] = UPLOAD_PATH . $filename;
          $config['maintain_ratio'] = FALSE;
          $config['width'] = 200;
          $config['height'] = 200;
          $this->image_lib->initialize($config);
          $this->image_lib->resize();
        }

        $result = array(
          'error' => 0,
          'message' => base_url() . UPLOAD_URL . $filename,
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
   * 페이지 표시
   *
   * @param $viewPage
   * @param $viewData
   * @return view
   * @author bjchoi
   **/
  private function _viewPage($viewPage, $viewData=NULL)
  {
    $viewData['uri'] = 'member';

    // 회원 정보
    $viewData['userData'] = $this->load->get_var('userData');
    $viewData['userLevel'] = $this->load->get_var('userLevel');

    // 진행 중 산행
    $viewData['listNotice'] = $this->reserve_model->listNotice($viewData['view']['idx'], array(STATUS_ABLE, STATUS_CONFIRM));

    // 회원수
    $viewData['view']['cntMember'] = $this->member_model->cntMember($viewData['view']['idx']);
    $viewData['view']['cntMemberToday'] = $this->member_model->cntMemberToday($viewData['view']['idx']);

    // 방문자수
    $viewData['view']['cntVisitor'] = $this->member_model->cntVisitor($viewData['view']['idx']);
    $viewData['view']['cntVisitorToday'] = $this->member_model->cntVisitorToday($viewData['view']['idx']);

    // 클럽 대표이미지
    $files = $this->file_model->getFile('club', $viewData['view']['idx']);

    if (empty($files)) {
      $viewData['view']['photo'][0] = 'noimage.png';
    } else {
      foreach ($files as $key => $value) {
        if (!empty($value['filename'])) {
          $viewData['view']['photo'][$key] = $value['filename'];
        } else {
          $viewData['view']['photo'][$key] = 'noimage.png';
        }
      }
    }

    // 로그인 쿠키 처리
    if (!empty(get_cookie('cookie_userid'))) {
      $viewData['cookieUserid'] = get_cookie('cookie_userid');
    } else {
      $viewData['cookieUserid'] = '';
    }
    if (!empty(get_cookie('cookie_passwd'))) {
      $viewData['cookiePasswd'] = get_cookie('cookie_passwd');
    } else {
      $viewData['cookiePasswd'] = '';
    }

    // 방문자 기록
    setVisitor();

    $this->load->view('header', $viewData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('footer', $viewData);
  }
}
?>
