<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 로그인 클래스
class Login extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('url', 'my_array_helper'));
    $this->load->library('session');
    $this->load->model(array('club_model', 'file_model', 'member_model', 'reserve_model'));
  }

  /**
   * 로그인
   *
   * @return view
   * @author bjchoi
   **/
  public function index($clubIdx=NULL)
  {
    if (is_null($clubIdx)) {
      $clubIdx = 1; // 최초는 경인웰빙
    } else {
      $clubIdx = html_escape($clubIdx);
    }
    $viewData['view'] = $this->club_model->viewclub($clubIdx);
    $viewData['redirect_url'] = $this->input->get('r');

    $userid = html_escape($this->input->post('userid'));
    $password = html_escape($this->input->post('password'));

    if (empty($userid) || empty($password)) {
      // 아이디와 패스워드가 없을때는 로그인 페이지를 보여준다.
      $this->_viewPage('login', $viewData);
    } else {
      // 아이디와 패스워드를 입력하면 로그인 처리를 실행한다.
      $userData = $this->member_model->checkLogin($userid, md5($password), $clubIdx);

      if (empty($userData['idx'])) {
        // 정보가 없으면 로그인 실패
        $result = array(
          'error' => 1,
          'message' => '로그인에 실패했습니다. 다시 로그인 해주세요.'
        );
      } else {
        // 로그인 성공
        $this->session->set_userdata('userData', $userData);

        $result = array(
          'error' => 0,
          'message' => '',
          'url' => empty($viewData['redirect_url']) ? base_url() . $clubIdx : $viewData['redirect_url']
        );
      }

      $this->output->set_output(json_encode($result));
    }
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
    $clubIdx = html_escape($this->input->post('club_idx'));
    $userid = html_escape($this->input->post('userid'));
    $check = $this->member_model->checkUserid($userid, $clubIdx);

    if (empty($check['idx'])) {
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
    $clubIdx = html_escape($this->input->post('club_idx'));
    $userid = html_escape($this->input->post('userid'));
    $check = $this->member_model->checkNickname($userid, $clubIdx);

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
  public function entry($clubIdx=NULL)
  {
    if (is_null($clubIdx)) {
      $clubIdx = 1; // 최초는 경인웰빙
    } else {
      $clubIdx = html_escape($clubIdx);
    }

    $viewData['view'] = $this->club_model->viewClub($clubIdx);
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
  public function forgot($clubIdx)
  {
    $viewData['view'] = $this->club_model->viewclub($clubIdx);
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
    $viewData['uri'] = 'login';

    // 회원 정보
    $viewData['userData'] = $this->load->get_var('userData');
    $viewData['userLevel'] = $this->load->get_var('userLevel');

    // 진행 중 산행
    $viewData['listNotice'] = $this->reserve_model->listNotice($viewData['view']['idx'], array(STATUS_NONE, STATUS_ABLE, STATUS_CONFIRM));

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
