<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 회원 페이지 클래스
class Member extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('url', 'my_array_helper'));
    $this->load->library(array('image_lib', 'session'));
    $this->load->model(array('club_model', 'member_model'));
  }

  /**
   * 마이페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function index()
  {
    // 회원 정보
    $memberIdx = html_escape($this->session->userData['idx']);
    $viewData['viewMember'] = $this->member_model->viewMember($memberIdx);

    $this->_viewPage('member/index', $viewData);
  }

  /**
   * 로그인
   *
   * @param $userid
   * @param $password
   * @return json
   * @author bjchoi
   **/
  public function login($club_idx)
  {
    $club_idx = html_escape($club_idx);
    $userid = html_escape($this->input->post('userid'));
    $password = html_escape($this->input->post('password'));

    $result = array(
      'error' => 1,
      'message' => '로그인에 실패했습니다. 다시 로그인 해주세요.'
    );

    if ($userid != '' || $password != '') {
      $userData = $this->member_model->checkLogin($userid, md5($password), $club_idx);

      if ($userData['idx'] != '') {
        $this->session->set_userdata('userData', $userData);

        $result = array(
          'error' => 0,
          'message' => ''
        );
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 로그아웃
   *
   * @return json
   * @author bjchoi
   **/
  public function logout()
  {
    $this->session->unset_userdata('userData');
    $this->output->set_output(0);
  }

  /**
   * 아이디 중복 체크
   *
   * @return json
   * @author bjchoi
   **/
  public function check_userid()
  {
    $club_idx = html_escape($this->input->post('club_idx'));
    $userid = html_escape($this->input->post('userid'));
    $check = $this->member_model->checkUserid($userid, $club_idx);

    if (empty($check)) {
      $result = array(
        'error' => 0,
        'message' => '<img class="check-userid-complete" src="/public/images/icon_check.png">'
      );
    } else {
      $result = array(
        'error' => 1,
        'message' => '<img src="/public/images/icon_cross.png">'
      );
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 닉네임 중복 체크
   *
   * @return json
   * @author bjchoi
   **/
  public function check_nickname()
  {
    $club_idx = html_escape($this->input->post('club_idx'));
    $userid = html_escape($this->input->post('userid'));
    $check = $this->member_model->checkNickname($userid, $club_idx);

    if (empty($check)) {
      $result = array(
        'error' => 0,
        'message' => '<img class="check-nickname-complete" src="/public/images/icon_check.png">'
      );
    } else {
      $result = array(
        'error' => 1,
        'message' => '<img src="/public/images/icon_cross.png">'
      );
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 회원가입 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function entry($club_idx)
  {
    $viewData['view'] = $this->club_model->viewClub($club_idx);
    $this->_viewPage('member/entry', $viewData);
  }

  /**
   * 회원 등록
   *
   * @return json
   * @author bjchoi
   **/
  public function insert()
  {
    $inputData = $this->input->post();
    $insertValues = array(
      'club_idx'      => html_escape($inputData['club_idx']),
      'userid'        => html_escape($inputData['userid']),
      'password'      => md5(html_escape($inputData['password'])),
      'nickname'      => html_escape($inputData['nickname']),
      'realname'      => html_escape($inputData['realname']),
      'gender'        => html_escape($inputData['gender']),
      'birthday'      => html_escape($inputData['birthday_year']) . '/' . html_escape($inputData['birthday_month']) . '/' . html_escape($inputData['birthday_day']),
      /*'birthday_type' => html_escape($inputData['birthday_type']),*/
      'phone'         => html_escape($inputData['phone1']) . '-' . html_escape($inputData['phone2']) . '-' . html_escape($inputData['phone3']),
      'regdate'       => time()
    );

    $idx = $this->member_model->insertMember($insertValues);

    if (empty($idx)) {
      $result = array(
        'error' => 1,
        'message' => '등록에 실패했습니다.'
      );
    } else {
      // 사진 등록
      if (!empty($inputData['filename']) && file_exists(UPLOAD_PATH . $inputData['filename'])) {
        // 파일 이동
        rename(UPLOAD_PATH . html_escape($inputData['filename']), PHOTO_PATH . $idx);
      }

      $result = array(
        'error' => 0,
        'message' => ''
      );
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 아이디/비밀번호 찾기 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function forgot()
  {
    $viewData = array();
    $this->_viewPage('member/forgot', $viewData);
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
    $headerData['userData'] = $this->session->userData;
    $headerData['uri'] = 'member';
    $this->load->view('header', $headerData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('footer');
  }
}
?>
