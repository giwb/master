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
    $viewData['view'] = $this->club_model->viewclub($clubIdx);

    // 예약 내역
    $viewData['userReserve'] = $this->reserve_model->userReserve($clubIdx, $userData['userid']);

    // 산행 내역
    $viewData['userVisit'] = $this->reserve_model->userVisit($clubIdx, $userData['userid']);

    // 포인트 내역
    $viewData['userPoint'] = $this->member_model->userPointLog($clubIdx, $userData['userid']);

    // 페널티 내역
    $viewData['userPenalty'] = $this->member_model->userPenaltyLog($clubIdx, $userData['userid']);

    $this->_viewPage('member/index', $viewData);
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

    $this->_viewPage('member/modify', $viewData);
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

        $result = array('error' => 0, 'message' => $this->lang->line('update_complete'));
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
    $viewData['listNotice'] = $this->reserve_model->listNotice($viewData['view']['idx'], array(STATUS_PLAN, STATUS_ABLE, STATUS_CONFIRM));

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

    // 방문자 기록
    setVisitor();

    $this->load->view('header', $viewData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('footer', $viewData);
  }
}
?>
