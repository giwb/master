<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 로그인 클래스
class Login extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('cookie', 'my_array_helper', 'url'));
    $this->load->library(array('image_lib', 'session'));
    $this->load->model(array('club_model', 'desk_model', 'file_model', 'member_model', 'reserve_model'));
  }

  /**
   * 로그인
   *
   * @return view
   * @author bjchoi
   **/
  public function index()
  {
    $viewData['redirect_url'] = !empty($this->input->get('r')) ? html_escape($this->input->get('r')) : BASE_URL;
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');
    $viewData['view'] = $this->club_model->viewClub($viewData['clubIdx']);
    $userid = html_escape($this->input->post('login_userid'));
    $password = html_escape($this->input->post('login_password'));
    $save = html_escape($this->input->post('save'));

    if (empty($userid) || empty($password)) {
      // 잘못된 접근을 한 사람은 되돌려 보내기
      /*if (empty($viewData['clubIdx']) && !empty($viewData['redirect_url'])) {
        redirect(BASE_URL . '/login/?r=' . $viewData['redirect_url']);
        exit;
      }

      // 로그인 상태의 회원은 되돌려 보내기
      checkUserLoginRedirect($viewData['redirect_url']);*/

      // 페이지 타이틀
      $viewData['pageTitle'] = '로그인';

      // 아이디와 패스워드가 없을때는 로그인 페이지를 보여준다.
      $this->_viewPage('login', $viewData);
    } else {
      // 이미 로그인 되어 있는지 확인
      $userIdx = $this->session->userData['idx'];

      if (!empty($userIdx)) {
        $result = array('error' => 0, 'message' => $viewData['redirect_url']);
      } else {
        // 아이디와 패스워드를 입력하면 로그인 처리를 실행한다.
        $userData = $this->member_model->checkLogin($userid);

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
          $rescount = $this->reserve_model->cntMemberReserve($viewData['clubIdx'], $userData['idx']);
          $updateValues['rescount'] = $rescount['cnt']; // 예약 횟수 갱신 (회원 레벨 체크를 위해)
          $updateValues['connect'] = $userData['connect'] + 1;
          $updateValues['lastdate'] = time();

          $this->member_model->updateMember($updateValues, $userData['idx']);

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

          $result = array('error' => 0, 'message' => $viewData['redirect_url']);
        }
      }

      $this->output->set_output(json_encode($result));
    }
  }

  /**
   * 한국여행 로그인
   *
   * @return view
   * @author bjchoi
   **/
  public function process()
  {
    $viewData['redirect_url'] = !empty($this->input->get('r')) ? html_escape($this->input->get('r')) : BASE_URL;
    $userid = html_escape($this->input->post('login_userid'));
    $password = html_escape($this->input->post('login_password'));
    $save = html_escape($this->input->post('save'));

    if (empty($userid) && empty($password)) {
      // 아이디와 패스워드가 없을때는 로그인 페이지를 보여준다.
      $result = array('error' => 1, 'message' => '아이디와 비밀번호를 입력해주세요.');
    } else {
      // 이미 로그인 되어 있는지 확인
      $userIdx = $this->session->userData['idx'];

      if (!empty($userIdx)) {
        $result = array('error' => 0, 'message' => $viewData['redirect_url']);
      } else {
        // 아이디와 패스워드를 입력하면 로그인 처리를 실행한다.
        $userData = $this->member_model->checkLogin($userid);

        if (empty($userData['idx'])) {
          // 정보가 없으면 로그인 실패
          $result = array('error' => 1, 'message' => '등록되지 않은 아이디입니다.');
        } elseif ($userData['password'] != md5($password)) {
          // 비밀번호가 다르면 로그인 실패
          $result = array('error' => 1, 'message' => '비밀번호가 일치하지 않습니다.');
        } else {
          // 로그인에 성공하면 회원정보 업데이트
          $updateValues['connect'] = $userData['connect'] + 1;
          $updateValues['lastdate'] = time();

          $this->member_model->updateMember($updateValues, $userData['idx']);

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

          $result = array('error' => 0, 'message' => $viewData['redirect_url']);
        }
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
    // 쿠키 삭제
    delete_cookie('cookie_userid');
    delete_cookie('cookie_passwd');

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
  public function check_userid()
  {
    $userid = html_escape($this->input->post('userid'));
    $check = $this->member_model->checkUserid($userid);

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
    $userid = html_escape($this->input->post('userid'));
    $nickname = html_escape($this->input->post('nickname'));
    $check = $this->member_model->checkNickname($userid, $nickname);

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
  public function check_phone()
  {
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
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
  public function entry()
  {
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');
    checkUserLoginRedirect(BASE_URL); // 로그인 상태의 회원은 메인 페이지로

    $viewData['view'] = $this->club_model->viewClub($viewData['clubIdx']);

    $this->_viewPage('member/entry', $viewData);
  }

  /**
   * 회원가입 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function entry_new()
  {
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');
    checkUserLoginRedirect(BASE_URL); // 로그인 상태의 회원은 메인 페이지로

    $viewData['view'] = $this->club_model->viewClub($viewData['clubIdx']);

    $this->_viewPage('member/entry_new', $viewData);
  }

  /**
   * 회원가입 체크 처리
   *
   * @return json
   * @author bjchoi
   **/
  public function check_member()
  {
    $clubIdx = html_escape($this->input->post('club_idx'));
    $nickname = html_escape($this->input->post('nickname'));
    $phone = html_escape($this->input->post('phone'));

    $checkMember = $this->member_model->checkPhone($clubIdx, $phone);

    if (!empty($checkMember['idx']) && $checkMember['nickname'] == $nickname && empty($checkMember['userid'])) {
      // 관리자 등록 회원 (정보 수정 페이지로)
      $result = array('error' => 2, 'message' => $checkMember['idx']);
    } elseif (!empty($checkMember['idx']) && $checkMember['nickname'] == $nickname && !empty($checkMember['userid'])) {
      // 이미 등록된 회원
      $result = array('error' => 1, 'message' => $this->lang->line('error_member_duplicate'));
    } elseif (!empty($checkMember['idx']) && $checkMember['nickname'] != $nickname) {
      // 이미 등록되었지만 닉네임이 다른 경우
      $result = array('error' => 1, 'message' => $this->lang->line('error_admin_duplicate'));
    } else {
      // 신규회원
      $checkNickname = $this->member_model->checkNickname($clubIdx, $nickname);

      if (!empty($checkNickname['idx'])) {
        $result = array('error' => 1, 'message' => $this->lang->line('error_nickname_duplicate'));
      } else {
        $result = array('error' => 0, 'message' => '');
      }
    }

    $this->output->set_output(json_encode($result));
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

    $clubIdx = html_escape($inputData['club_idx']);
    $userid = html_escape($inputData['userid']);
    $password = html_escape($inputData['password']);
    $nickname = html_escape($inputData['nickname']);
    $phone = html_escape($inputData['phone1']) . '-' . html_escape($inputData['phone2']) . '-' . html_escape($inputData['phone3']);

    $checkUserid = $this->member_model->checkUserid($userid);
    $checkNickname = $this->member_model->checkNickname(NULL, $nickname);
    $checkPhone = $this->member_model->checkPhone($phone);

    if (!empty($checkUserid['idx'])) {
      // 중복된 아이디
      $result = array('error' => 1, 'message' => '이미 등록된 아이디 입니다. 다른 아이디를 입력해주세요.');
    } elseif ($checkNickname['idx']) {
      // 중복된 닉네임
      $result = array('error' => 1, 'message' => '이미 등록된 닉네임 입니다. 다른 닉네임을 입력해주세요.');
    } elseif ($checkPhone['idx']) {
      // 중복된 전화번호
      $result = array('error' => 1, 'message' => '이미 등록된 전화번호 입니다.');
    } else {
      $insertValues = array(
        'club_idx'      => $clubIdx,
        'userid'        => $userid,
        'nickname'      => $nickname,
        'password'      => md5($password),
        'phone'         => $phone,
        'connect'       => 1,
        'regdate'       => $now
      );
      $idx = $this->member_model->insertMember($insertValues);

      if (empty($idx)) {
        $result = array('error' => 1, 'message' => '등록에 실패했습니다.');
      } else {
        // 회원 가입 기록
        setHistory($clubIdx, LOG_ENTRY, $idx, $idx, $nickname, '', $now);
        $result = array('error' => 0, 'message' => '');
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 회원 등록
   *
   * @return json
   * @author bjchoi
   **/
  public function insert_new()
  {
    $now = time();
    $inputData = $this->input->post();
    $result = array();

    $clubIdx = html_escape($inputData['club_idx']);
    $userid = html_escape($inputData['userid']);
    $password = html_escape($inputData['password']);
    $password_check = html_escape($inputData['password_check']);
    $nickname = html_escape($inputData['nickname']);
    $phone1 = html_escape($inputData['phone1']);
    $phone2 = html_escape($inputData['phone2']);
    $phone3 = html_escape($inputData['phone3']);
    $auth_code = html_escape($inputData['auth_code']);

    $checkUserid = $this->member_model->checkUserid($userid);
    if (!empty($checkUserid['idx'])) {
      // 중복된 아이디
      $result = array('error' => 1, 'message' => '이미 등록된 아이디 입니다. 다른 아이디를 입력해주세요.');
    }

    if (empty($result) && $password != $password_check) {
      // 비밀번호가 일치하지 않음
      $result = array('error' => 1, 'message' => '입력하신 비밀번호가 일치하지 않습니다.');
    }

    if (empty($result)) {
      $checkNickname = $this->member_model->checkNickname(NULL, $nickname);
      if ($checkNickname['idx']) {
        // 중복된 닉네임
        $result = array('error' => 1, 'message' => '이미 등록된 닉네임 입니다. 다른 닉네임을 입력해주세요.');
      }
    }

    if (empty($result)) {
      $checkPhone = $this->member_model->checkPhone($phone1.$phone2.$phone3);
      if ($checkPhone['idx']) {
        // 중복된 전화번호
        $result = array('error' => 1, 'message' => '이미 등록된 전화번호 입니다.');
      }
    }

    if (empty($result)) {
      $checkPhoneAuth = $this->member_model->checkPhoneAuth($phone1.$phone2.$phone3, $auth_code);
      if (empty($checkPhoneAuth['idx'])) {
        // 인증번호 없음
        $result = array('error' => 1, 'message' => '인증번호가 일치하지 않습니다.');
      } elseif ($checkPhoneAuth['created_at'] < ($now - 179)) {
        // 인증번호 유효시간 만료
        $result = array('error' => 1, 'message' => '인증번호 유효시간이 만료되었습니다.');
      }
    }

    if (empty($result)) {
      $insertValues = array(
        'club_idx'      => $clubIdx,
        'userid'        => $userid,
        'nickname'      => $nickname,
        'password'      => md5($password),
        'phone'         => $phone1 . '-' . $phone2 . '-' . $phone3,
        'connect'       => 1,
        'regdate'       => $now
      );
      $idx = $this->member_model->insertMember($insertValues);

      if (empty($idx)) {
        $result = array('error' => 1, 'message' => '등록에 실패했습니다.');
      } else {
        // 인증번호 삭제
        $updateValues = array('deleted_at' => time());
        $this->member_model->deletePhoneAuth($updateValues, $checkPhoneAuth['idx']);

        // 회원 가입 기록
        setHistory($clubIdx, LOG_ENTRY, $idx, $idx, $nickname, '', $now);
        $result = array('error' => 0, 'message' => '');
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 인증번호 발송
   *
   * @return json
   * @author bjchoi
   **/
  public function send_auth()
  {
    $now = time();
    $phone1 = html_escape($this->input->post('phone1'));
    $phone2 = html_escape($this->input->post('phone2'));
    $phone3 = html_escape($this->input->post('phone3'));

    mt_srand($now);
    $auth_code = mt_rand(100000, 999999);

    if ($phone1 != '010' || strlen($phone2) != 4 || strlen($phone3) != 4) {
      $result = array('error' => 1, 'message' => '전화번호 형식이 올바르지 않습니다.');
    } else {
      $phone = $phone1 . $phone2 . $phone3;

      // 같은 번호로 이미 받은 인증번호가 있을 경우 삭제
      $checkPhoneAuth = $this->member_model->checkPhoneAuth($phone);
      if (!empty($checkPhoneAuth['idx'])) {
        $updateValues = array('deleted_at' => time());
        $this->member_model->deletePhoneAuth($updateValues, $checkPhoneAuth['idx']);
      }

      // -----------------------------------------------
      // 네이버 SMS 인증 (SENS)
      // -----------------------------------------------
      if (ENVIRONMENT == 'production') {
        $access_key = 'ncp:sms:kr:264893982314:tripkorea';
        $url = 'https://sens.apigw.ntruss.com';
        $uri = '/sms/v2/services/' . $access_key . '/messages';
        $message = '[경인웰빙투어] 인증번호는 ' . $auth_code . ' 입니다.';
        $sens = array(
          'type' => 'sms',
          'from' => '01080715227',
          'content' => $message,
          'messages' => array(
            'to' => $phone,
            'content' => $message,
          )
        );
        $header = array(
          'Content-Type: application/json; charset=utf-8',
          'x-ncp-apigw-timestamp: ' . $now,
          'x-ncp-iam-access-key: ' . $access_key,
          'x-ncp-apigw-signature-v2: ' . hash_hmac(POST . $uri . "\n" . $now . "\n" + $access_key)
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($sens));
        curl_setopt($ch, CURLOPT_POST, true);
        $response = curl_exec($ch);
        curl_close($ch);
        // -----------------------------------------------
        $response = json_decode($response);
      } else {
        $response->statusCode = '202';
      }

      if (!empty($response->statusCode) && $response->statusCode == '202') {
        // 새로운 인증번호 등록
        $insertValues = array(
          'phone_number'  => $phone,
          'auth_code'     => $auth_code,
          'created_at'    => $now,
        );
        $idx = $this->member_model->insertPhoneAuth($insertValues);
      }

      if (empty($idx)) {
        $result = array('error' => 1, 'message' => '인증번호 발송에 실패했습니다.');
      } else {
        $result = array('error' => 0, 'message' => '인증번호가 발송되었습니다.');
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 가등록 회원 정식 등록
   *
   * @return json
   * @author bjchoi
   **/
  public function update()
  {
    $now = time();
    $inputData = $this->input->post();
    $clubIdx = html_escape($inputData['club_idx']);
    $idx = html_escape($inputData['idx']);
    $nickname = html_escape($inputData['nickname']);

    $updateValues = array(
      'userid'        => html_escape($inputData['userid']),
      'password'      => md5(html_escape($inputData['password'])),
      'realname'      => html_escape($inputData['realname']),
      'gender'        => html_escape($inputData['gender']),
      'birthday'      => html_escape($inputData['birthday_year']) . '/' . html_escape($inputData['birthday_month']) . '/' . html_escape($inputData['birthday_day']),
      'birthday_type' => html_escape($inputData['birthday_type']),
      'location'      => html_escape($inputData['location']),
      'lastdate'      => $now
    );

    $rtn = $this->member_model->updateMember($updateValues, $idx);

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => '등록에 실패했습니다.');
    } else {
      // 사진 등록
      if (!empty($inputData['filename']) && file_exists(UPLOAD_PATH . $inputData['filename'])) {
        // 파일 이동
        rename(UPLOAD_PATH . html_escape($inputData['filename']), PHOTO_PATH . $idx);
      }

      // 회원 가입 기록
      setHistory($clubIdx, LOG_ENTRY, $idx, $idx, $nickname, '', $now);

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
  public function forgot()
  {
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');
    checkUserLoginRedirect(BASE_URL); // 로그인 상태의 회원은 메인 페이지로

    $viewData['view'] = $this->club_model->viewClub($viewData['clubIdx']);

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
    $realname       = html_escape($this->input->post('realname'));
    $gender         = html_escape($this->input->post('gender'));
    $birthday_year  = html_escape($this->input->post('birthday_year'));
    $birthday_month = html_escape($this->input->post('birthday_month'));
    $birthday_day   = html_escape($this->input->post('birthday_day'));
    $phone1         = html_escape($this->input->post('phone1'));
    $phone2         = html_escape($this->input->post('phone2'));
    $phone3         = html_escape($this->input->post('phone3'));

    $userData = $this->member_model->searchId($realname, $gender, $birthday_year . '/' . $birthday_month . '/' . $birthday_day, $phone1 . '-' . $phone2 . '-' . $phone3);

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
    $userid         = html_escape($this->input->post('uid'));
    $realname       = html_escape($this->input->post('realname'));
    $gender         = html_escape($this->input->post('gender'));
    $birthday_year  = html_escape($this->input->post('birthday_year'));
    $birthday_month = html_escape($this->input->post('birthday_month'));
    $birthday_day   = html_escape($this->input->post('birthday_day'));
    $phone1         = html_escape($this->input->post('phone1'));
    $phone2         = html_escape($this->input->post('phone2'));
    $phone3         = html_escape($this->input->post('phone3'));
    $updateValues['password'] = md5(html_escape($this->input->post('new_password')));

    // 에러 메세지
    $result = array('error' => 1, 'message' => $this->lang->line('error_search_id'));

    // 해당 회원이 있는지 검색
    $userData = $this->member_model->searchId($realname, $gender, $birthday_year . '/' . $birthday_month . '/' . $birthday_day, $phone1 . '-' . $phone2 . '-' . $phone3, $userid);

    if (!empty($userData['idx']) && !empty($updateValues['password'])) {
      // 비밀번호 변경
      $rtn = $this->member_model->updateMember($updateValues, $userData['idx']);

      if (!empty($rtn)) {
        $result = array('error' => 0, 'message' => $this->lang->line('msg_change_password'));
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * OAuth 로그인
   *
   * @return redirect
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
        $url = 'https://kauth.kakao.com/oauth/authorize?client_id=' . API_KAKAO . '&redirect_uri=' . $redirectUrl. '&response_type=code&state=' . $state;
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
    $now = time();
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
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
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=authorization_code&client_id=' . API_KAKAO . '&redirect_uri=' . BASE_URL . '/' . API_KAKAO_URL . '&code=' . $code);
    $response = curl_exec($ch);
    curl_close($ch);

    // OAuth State 세션 제거
    $this->session->unset_userdata('OAuthState');

    // OAuth Access Token 세션 저장
    $accessToken = array(
      'response' => json_decode($response, TRUE),
      'created' => $now
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
        'password'      => md5($response['id'] . $now),
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
      $userData = $this->member_model->viewMember($idx);
      $this->session->set_userdata('userData', $userData);
    } else {
      // 기존 사용자는 로그인
      $rescount = $this->reserve_model->cntMemberReserve($clubIdx, $userData['idx']);
      $updateValues['rescount'] = $rescount['cnt']; // 예약 횟수 갱신 (회원 레벨 체크를 위해)
      $updateValues['connect'] = $userData['connect'] + 1;
      $updateValues['lastdate'] = $now;

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
      redirect(BASE_URL());
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

    // 클럽 메뉴
    $viewData['listAbout'] = $this->club_model->listAbout($viewData['view']['idx']);

    if (!empty($viewData['view'])) {
      // 진행 중 산행
      $viewData['listFooterNotice'] = $this->reserve_model->listNotice($viewData['view']['idx'], array(STATUS_ABLE, STATUS_CONFIRM));

      // 최신 댓글
      $paging['perPage'] = 5; $paging['nowPage'] = 0;
      $viewData['listFooterReply'] = $this->admin_model->listReply($viewData['view']['idx'], $paging);

      foreach ($viewData['listFooterReply'] as $key => $value) {
        if ($value['reply_type'] == REPLY_TYPE_STORY):  $viewData['listFooterReply'][$key]['url'] = BASE_URL . '/story/view/' . $value['story_idx']; endif;
        if ($value['reply_type'] == REPLY_TYPE_NOTICE): $viewData['listFooterReply'][$key]['url'] = BASE_URL . '/reserve/list/' . $value['story_idx']; endif;
        if ($value['reply_type'] == REPLY_TYPE_SHOP):   $viewData['listFooterReply'][$key]['url'] = BASE_URL . '/shop/item/' . $value['story_idx']; endif;
      }

      // 최신 사진첩
      $paging['perPage'] = 2; $paging['nowPage'] = 0;
      $viewData['listFooterAlbum'] = $this->club_model->listAlbum($viewData['view']['idx'], $paging);

      foreach ($viewData['listFooterAlbum'] as $key => $value) {
        $photo = $this->file_model->getFile('album', $value['idx'], NULL, 1);
        if (!empty($photo[0]['filename'])) {
          //$viewData['listAlbum'][$key]['photo'] = PHOTO_URL . 'thumb_' . $photo[0]['filename'];
          $viewData['listFooterAlbum'][$key]['photo'] = PHOTO_URL . $photo[0]['filename'];
        } else {
          $viewData['listFooterAlbum'][$key]['photo'] = '/public/images/noimage.png';
        }
      }

      // 클럽 대표이미지
      $files = $this->file_model->getFile('club', $viewData['view']['idx']);
      if (!empty($files[0]['filename']) && file_exists(PHOTO_PATH . $files[0]['filename'])) {
        $size = getImageSize(PHOTO_PATH . $files[0]['filename']);
        $viewData['view']['main_photo'] = PHOTO_URL . $files[0]['filename'];
        $viewData['view']['main_photo_width'] = $size[0];
        $viewData['view']['main_photo_height'] = $size[1];
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

    // 리다이렉트 URL 추출
    if ($_SERVER['SERVER_PORT'] == '80') $HTTP_HEADER = 'http://'; else $HTTP_HEADER = 'https://';
    $viewData['redirectUrl'] = $HTTP_HEADER . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    // 분류별 기사
    $viewData['listArticleCategory'] = $this->desk_model->listArticleCategory();

    // 분류별 기사 카운트
    foreach ($viewData['listArticleCategory'] as $key => $value) {
      $cnt = $this->desk_model->cntArticle($value['code']);
      $viewData['listArticleCategory'][$key]['cnt'] = $cnt['cnt'];
    }

    // 방문자 기록
    setVisitor();

    $this->load->view('club/header_' . $viewData['view']['main_design'], $viewData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('club/footer_' . $viewData['view']['main_design'], $viewData);
  }
}
?>
