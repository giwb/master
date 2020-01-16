<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 로그인 클래스
class Login extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('cookie', 'my_array_helper', 'url'));
    $this->load->library(array('image_lib', 'session'));
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

    checkUserLoginRedirect($clubIdx); // 로그인 상태의 회원은 메인 페이지로

    $viewData['view'] = $this->club_model->viewClub($clubIdx);
    $viewData['redirect_url'] = html_escape($this->input->get('r'));

    $userid = html_escape($this->input->post('userid'));
    $password = html_escape($this->input->post('password'));
    $save = html_escape($this->input->post('save'));

    if (empty($userid) || empty($password)) {
      // 페이지 타이틀
      $viewData['pageTitle'] = '로그인';

      // 아이디와 패스워드가 없을때는 로그인 페이지를 보여준다.
      $this->_viewPage('login', $viewData);
    } else {
      // 아이디와 패스워드를 입력하면 로그인 처리를 실행한다.
      $userData = $this->member_model->checkLogin($clubIdx, $userid);

      if (empty($userData['idx'])) {
        // 정보가 없으면 로그인 실패
        $result = array(
          'error' => 1,
          'message' => '등록되지 않은 아이디입니다.'
        );
      } elseif ($userData['password'] != md5($password)) {
        // 비밀번호가 다르면 로그인 실패
        $result = array(
          'error' => 1,
          'message' => '비밀번호가 일치하지 않습니다.'
        );
      } else {
        // 로그인에 성공하면 회원정보 업데이트
        $rescount = $this->admin_model->cntMemberReservation($userData['userid']);
        $updateValues['rescount'] = $rescount['cnt']; // 예약 횟수 갱신 (회원 레벨 체크를 위해)
        $updateValues['connect'] = $userData['connect'] + 1;
        $updateValues['lastdate'] = time();

        $this->member_model->updateMember($updateValues, $clubIdx, $userData['idx']);

        // 세션 저장
        $this->session->set_userdata('userData', $userData);

        if (empty($save)) {
          // 쿠키 삭제
          delete_cookie('cookie_userid');
          delete_cookie('cookie_passwd');
        } else {
          // 쿠키 저장
          set_cookie('cookie_userid', $userid, COOKIE_STRAGE_PERIOD);
          set_cookie('cookie_passwd', $password, COOKIE_STRAGE_PERIOD);
        }

        // 아이콘 사이즈 변경 (가로 사이즈가 200보다 클 경우)
        $filename = PHOTO_PATH . $userData['idx'];
        if (file_exists($filename)) {
          $size = getImageSize($filename);
          if ($size[0] > 200) {
            $this->image_lib->clear();
            $config['image_library'] = 'gd2';
            $config['source_image'] = $filename;
            $config['maintain_ratio'] = FALSE;
            $config['width'] = 200;
            $config['height'] = 200;
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
          }
        }

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
    // 세션 삭제
    $this->session->unset_userdata('userData');
    $this->output->set_output(0);
  }

  /**
   * 아이디 중복 체크
   *
   * @return json
   * @author bjchoi
   **/
  public function check_userid($clubIdx)
  {
    $clubIdx = html_escape($clubIdx);
    $userid = html_escape($this->input->post('userid'));
    $check = $this->member_model->checkUserid($clubIdx, $userid);

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
  public function check_nickname($clubIdx)
  {
    $clubIdx = html_escape($clubIdx);
    $userid = html_escape($this->input->post('userid'));
    $nickname = html_escape($this->input->post('nickname'));
    $check = $this->member_model->checkNickname($clubIdx, $userid, $nickname);

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
   * 전화번호 중복 체크
   *
   * @return json
   * @author bjchoi
   **/
  public function check_phone($clubIdx)
  {
    $clubIdx = html_escape($clubIdx);
    $phone = html_escape($this->input->post('phone'));
    $check = $this->member_model->checkPhone($clubIdx, $phone);

    if (!empty($check['idx'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_phone_duplicate'));
    } else {
      $result = array('error' => 0);
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

    checkUserLoginRedirect($clubIdx); // 로그인 상태의 회원은 메인 페이지로

    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 페이지 타이틀
    $viewData['pageTitle'] = '회원가입';

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
    $now = time();
    $inputData = $this->input->post();
    $userid = html_escape($inputData['userid']);
    $nickname = html_escape($inputData['nickname']);

    $insertValues = array(
      'club_idx'      => html_escape($inputData['club_idx']),
      'userid'        => $userid,
      'nickname'      => $nickname,
      'password'      => md5(html_escape($inputData['password'])),
      'realname'      => html_escape($inputData['realname']),
      'gender'        => html_escape($inputData['gender']),
      'birthday'      => html_escape($inputData['birthday_year']) . '/' . html_escape($inputData['birthday_month']) . '/' . html_escape($inputData['birthday_day']),
      'birthday_type' => html_escape($inputData['birthday_type']),
      'phone'         => html_escape($inputData['phone1']) . '-' . html_escape($inputData['phone2']) . '-' . html_escape($inputData['phone3']),
      'location'      => html_escape($inputData['location']),
      'connect'       => 1,
      'regdate'       => $now
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

      // 회원 가입 기록
      setHistory(LOG_ENTRY, $idx, $userid, $nickname, '', $now);

      $result = array('error' => 0, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 사진 삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function photo_delete()
  {
    $filename = html_escape($this->input->post('filename'));
    if (file_exists(UPLOAD_PATH . $filename)) unlink(UPLOAD_PATH . $filename);
    if (file_exists(PHOTO_PATH . $filename)) unlink(PHOTO_PATH . $filename);

    $result = array('error' => 0);
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
    if (is_null($clubIdx)) {
      $clubIdx = 1; // 최초는 경인웰빙
    } else {
      $clubIdx = html_escape($clubIdx);
    }

    checkUserLoginRedirect($clubIdx); // 로그인 상태의 회원은 메인 페이지로

    $viewData['view'] = $this->club_model->viewclub($clubIdx);

    // 페이지 타이틀
    $viewData['pageTitle'] = '아이디/비밀번호 찾기';

    $this->_viewPage('member/forgot', $viewData);
  }

  /**
   * 아이디 찾기
   *
   * @return json
   * @author bjchoi
   **/
  public function search_id()
  {
    $clubIdx        = html_escape($this->input->post('clubIdx'));
    $realname       = html_escape($this->input->post('realname'));
    $gender         = html_escape($this->input->post('gender'));
    $birthday_year  = html_escape($this->input->post('birthday_year'));
    $birthday_month = html_escape($this->input->post('birthday_month'));
    $birthday_day   = html_escape($this->input->post('birthday_day'));
    $phone1         = html_escape($this->input->post('phone1'));
    $phone2         = html_escape($this->input->post('phone2'));
    $phone3         = html_escape($this->input->post('phone3'));

    $userData = $this->member_model->searchId($clubIdx, $realname, $gender, $birthday_year . '/' . $birthday_month . '/' . $birthday_day, $phone1 . '-' . $phone2 . '-' . $phone3);

    if (empty($userData['userid'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_search_id'));
    } elseif (!empty($userData['quit'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('msg_quit_member'));
    } else {
      $result = array('error' => 0, 'message' => '회원님의 아이디는 ' . $userData['userid'] . ' 입니다.');
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 비밀번호 변경
   *
   * @return json
   * @author bjchoi
   **/
  public function change_pw()
  {
    $clubIdx        = html_escape($this->input->post('clubIdx'));
    $userid         = html_escape($this->input->post('userid'));
    $realname       = html_escape($this->input->post('realname'));
    $gender         = html_escape($this->input->post('gender'));
    $birthday_year  = html_escape($this->input->post('birthday_year'));
    $birthday_month = html_escape($this->input->post('birthday_month'));
    $birthday_day   = html_escape($this->input->post('birthday_day'));
    $phone1         = html_escape($this->input->post('phone1'));
    $phone2         = html_escape($this->input->post('phone2'));
    $phone3         = html_escape($this->input->post('phone3'));
    $updateValues['password'] = md5(html_escape($this->input->post('password')));

    // 에러 메세지
    $result = array('error' => 1, 'message' => $this->lang->line('error_search_id'));

    // 해당 회원이 있는지 검색
    $userData = $this->member_model->searchId($clubIdx, $realname, $gender, $birthday_year . '/' . $birthday_month . '/' . $birthday_day, $phone1 . '-' . $phone2 . '-' . $phone3, $userid);

    if (!empty($userData['idx']) && !empty($updateValues['password'])) {
      // 비밀번호 변경
      $rtn = $this->member_model->updateMember($updateValues, $clubIdx, $userData['idx']);

      if (!empty($rtn)) {
        $result = array('error' => 0, 'message' => $this->lang->line('msg_change_password'));
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * OAuth 로그인
   *
   * @return json
   * @author bjchoi
   **/
  public function oauth()
  {
    $provider = html_escape($this->input->get('provider'));
    $redirectUrl = html_escape($this->input->get('redirectUrl'));
    $state = md5('TRIPKOREA_' . time());

    // OAuth State 세션 저장
    $this->session->set_userdata('OAuthState', $state);

    // Redirect Url 세션 저장
    $this->session->set_userdata('redirectUrl', $redirectUrl);

    switch ($provider) {
      case 'kakao':
        $url = 'https://kauth.kakao.com/oauth/authorize?client_id=' . API_KAKAO . '&redirect_uri=' . base_url() . API_KAKAO_URL . '&response_type=code&state=' . $state;
        break;
    }

    redirect($url);
  }

  /**
   * OAuth : 카카오 로그인
   *
   * @return json
   * @author bjchoi
   **/
  public function kakao()
  {
    $clubIdx = 1;
    $code = html_escape($this->input->get('code'));
    $state = html_escape($this->input->get('state'));

    // OAuth State 세션 불러오기
    $OAuthState = $this->session->userdata('OAuthState');

    // 리턴값이 정상이고 세션값이 일치하면 통과
    if (empty($code) || $state != $OAuthState) {
      echo '로그인에 실패했습니다.';
      exit;
    }

    // POST 형식으로 토큰값 받아오기
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://kauth.kakao.com/oauth/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=authorization_code&client_id=' . API_KAKAO . '&redirect_uri=' . base_url() . API_KAKAO_URL . '&code=' . $code);
    $response = curl_exec($ch);
    curl_close($ch);

    // OAuth State 세션 제거
    $this->session->unset_userdata('OAuthState');

    // OAuth Access Token 세션 저장
    $accessToken = array(
      'response' => json_decode($response, TRUE),
      'created' => time()
    );
    $this->session->set_userdata('OAuthAccessToken', $accessToken);

    // 사용자 정보 받아오기
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://kapi.kakao.com/v2/user/me');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $accessToken['response']['access_token']));
    $response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response, TRUE);

    // 신규 사용자인지 확인
    $userData = $this->member_model->checkOAuthUser(PROVIDER_KAKAO, $response['kakao_account']['email']);

    if (empty($userData)) {
      // 신규 사용자는 가입
      $insertValues = array(
        'provider'      => PROVIDER_KAKAO,
        'userid'        => $response['kakao_account']['email'],
        'email'         => $response['kakao_account']['email'],
        'nickname'      => $response['kakao_account']['profile']['nickname'],
        'realname'      => $response['kakao_account']['profile']['nickname'],
        'password'      => md5($response['id'] . time()),
        'gender'        => !empty($response['kakao_account']['gender']) && $response['kakao_account']['gender'] == 'male' ? 'M' : 'F',
        'birthday'      => !empty($response['kakao_account']['birthday']) ? $response['kakao_account']['birthday'] : NULL,
        'birthday_type' => !empty($response['kakao_account']['birthday_type']) && $response['kakao_account']['birthday_type'] == 'SOLAR' ? '1' : '2',
        'icon'          => !empty($response['kakao_account']['profile']['profile_image_url']) ? $response['kakao_account']['profile']['profile_image_url'] : NULL,
        'icon_thumbnail' => !empty($response['kakao_account']['profile']['thumbnail_image_url']) ? $response['kakao_account']['profile']['thumbnail_image_url'] : NULL,
        'connect'       => 1,
        'regdate'       => $now
      );

      $idx = $this->member_model->insertMember($insertValues);

      // 세션 저장
      $userData = $this->member_model->viewMember($clubIdx, $idx);
      $this->session->set_userdata('userData', $userData);
    } else {
      // 기존 사용자는 로그인
      $rescount = $this->admin_model->cntMemberReservation($userData['userid']);
      $updateValues['rescount'] = $rescount['cnt']; // 예약 횟수 갱신 (회원 레벨 체크를 위해)
      $updateValues['connect'] = $userData['connect'] + 1;
      $updateValues['lastdate'] = time();

      $this->member_model->updateMember($updateValues, $userData['clubIdx'], $userData['idx']);

      // 세션 저장
      $this->session->set_userdata('userData', $userData);
    }

    // Redirect Url
    $redirectUrl = $this->session->userdata('redirectUrl');

    if (!empty($redirectUrl)) {
      $this->session->unset_userdata('redirectUrl');
      redirect($redirectUrl);
    } else {
      redirect(base_url());
    }
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
    $viewData['listNotice'] = $this->reserve_model->listNotice($viewData['view']['idx'], array(STATUS_ABLE, STATUS_CONFIRM));

    // 회원수
    $viewData['view']['cntMember'] = $this->member_model->cntMember($viewData['view']['idx']);
    $viewData['view']['cntMemberToday'] = $this->member_model->cntMemberToday($viewData['view']['idx']);

    // 방문자수
    $viewData['view']['cntVisitor'] = $this->member_model->cntVisitor($viewData['view']['idx']);
    $viewData['view']['cntVisitorToday'] = $this->member_model->cntVisitorToday($viewData['view']['idx']);

    // 클럽 대표이미지
    $files = $this->file_model->getFile('club', $viewData['view']['idx']);
    if (!empty($files[0]['filename']) && file_exists(PHOTO_PATH . $files[0]['filename'])) {
      $size = getImageSize(PHOTO_PATH . $files[0]['filename']);
      $viewData['view']['main_photo'] = base_url() . PHOTO_URL . $files[0]['filename'];
      $viewData['view']['main_photo_width'] = $size[0];
      $viewData['view']['main_photo_height'] = $size[1];
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
