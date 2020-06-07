<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 첫 페이지 클래스
class Reserve extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('cookie', 'security', 'url', 'my_array_helper'));
    $this->load->library('session');
    $this->load->model(array('area_model', 'club_model', 'file_model', 'notice_model', 'member_model', 'reserve_model', 'shop_model', 'story_model'));
  }

  /**
   * 메인
   *
   * @return view
   * @author bjchoi
   **/
  public function index($noticeIdx=NULL)
  {
    $noticeIdxOld = html_escape($this->input->get('n'));

    if (!empty($noticeIdxOld)) {
      redirect(BASE_URL . '/reserve/list/' . $noticeIdxOld);
    } else {
      redirect(BASE_URL . '/reserve/list/' . $noticeIdx);
    }
  }

  /**
   * 예약 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function list($noticeIdx=NULL)
  {
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $userData = $this->load->get_var('userData');
    $noticeIdx = html_escape($noticeIdx);
    $checkIdx = html_escape($this->input->get('c'));

    if (empty($clubIdx)) {
      redirect(BASE_URL . '/reserve/list/' . $noticeIdx);
      exit;
    }

    if (empty($noticeIdx)) {
      redirect(BASE_URL . '/reserve/schedule/');
      exit;
    }

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    if (empty($checkIdx)) {
      // 페이지 타이틀
      $viewData['pageTitle'] = '산행 예약';

      // 등록된 산행 목록
      $viewData['listNotice'] = $this->reserve_model->listNotice($clubIdx);

      if (!empty($noticeIdx)) {
        // 예약 공지
        $viewData['notice'] = $this->reserve_model->viewNotice($noticeIdx);

        // 버스 형태별 좌석 배치
        $viewData['busType'] = getBusType($viewData['notice']['bustype'], $viewData['notice']['bus']);

        // 예약 정보
        $viewData['reserve'] = $this->reserve_model->viewProgress($noticeIdx);

        // 지역
        $viewData['area_sido'] = $this->area_model->listSido();
        if (!empty($viewData['notice']['area_sido'])) {
          $area_sido = unserialize($viewData['notice']['area_sido']);
          $area_gugun = unserialize($viewData['notice']['area_gugun']);

          foreach ($area_sido as $key => $value) {
            $sido = $this->area_model->getName($value);
            $gugun = $this->area_model->getName($area_gugun[$key]);
            $viewData['list_sido'] = $this->area_model->listSido();
            $viewData['list_gugun'][$key] = $this->area_model->listGugun($value);
            $viewData['notice']['sido'][$key] = $sido['name'];
            $viewData['notice']['gugun'][$key] = $gugun['name'];
          }

          $viewData['area_gugun'] = $this->area_model->listGugun($viewData['notice']['area_sido']);
        } else {
          $viewData['area_gugun'] = array();
        }
      } else {
        $viewData['notice'] = array();
        $viewData['busType'] = array();
        $viewData['reserve'] = array();
      }

      // 탑승 위치
      $viewData['arrLocation'] = arrLocation();

      $cntReply = $this->story_model->cntStoryReply($noticeIdx, REPLY_TYPE_NOTICE);
      $cntLike = $this->story_model->cntStoryReaction($noticeIdx, REACTION_TYPE_NOTICE, REACTION_KIND_LIKE);
      $cntShare = $this->story_model->cntStoryReaction($noticeIdx, REACTION_TYPE_NOTICE, REACTION_KIND_SHARE);
      $viewData['notice']['reply_cnt'] = $cntReply['cnt'];
      $viewData['notice']['like_cnt'] = $cntLike['cnt'];
      $viewData['notice']['share_cnt'] = $cntShare['cnt'];

      // 댓글
      $reply = $this->story_model->listStoryReply($noticeIdx, REPLY_TYPE_NOTICE);

      foreach ($reply as $key => $value) {
        $viewData['listReply'][] = $value;

        // 댓글에 대한 답글
        $replyResponse = $this->story_model->listStoryReply($noticeIdx, REPLY_TYPE_NOTICE, $value['idx']);
        foreach ($replyResponse as $response) {
          $viewData['listReply'][] = $response;
        }
      }

      $viewData['listReply'] = $this->load->view('story/reply', $viewData, true);

      $this->_viewPage('reserve/index', $viewData);
    } else {
      // 페이지 타이틀
      $viewData['pageTitle'] = '산행 예약확인';

      $viewData['view']['noticeIdx'] = $noticeIdx;

      // 회원 정보
      $viewData['viewMember'] = $this->member_model->viewMember($userData['idx']);

      // 예약 번호
      $reserveIdx = explode(',', $checkIdx);

      // 예약 내역
      $viewData['listReserve'] = $this->reserve_model->listReserve($clubIdx, $reserveIdx);

      foreach ($viewData['listReserve'] as $key => $value) {
        if (empty($value['cost_total'])) {
          $value['cost_total'] = $value['cost'];
        }
        if ($userData['level'] == LEVEL_LIFETIME) {
          // 평생회원 할인
          $viewData['listReserve'][$key]['view_cost'] = '<s class="text-secondary">' . number_format($value['cost_total']) . '원</s> → ' . number_format($value['cost_total'] - 5000) . '원';
          $viewData['listReserve'][$key]['real_cost'] = $value['cost_total'] - 5000;
        } elseif ($userData['level'] == LEVEL_FREE) {
          // 무료회원 할인
          $viewData['listReserve'][$key]['view_cost'] = '<s class="text-secondary">' . number_format($value['cost_total']) . '원</s> → ' . '0원';
          $viewData['listReserve'][$key]['real_cost'] = 0;
        } elseif (!empty($value['honor'])) {
          // 1인우등 할인
          if ($key == 1) { // 1인우등의 2번째 좌석은 무조건 1만원
            $honorCost = 10000;
            $viewData['listReserve'][$key]['view_cost'] = '<s class="text-secondary">' . number_format($value['cost_total']) . '원</s> → ' . number_format($honorCost) . '원';
            $viewData['listReserve'][$key]['real_cost'] = $honorCost;
          } else {
            $viewData['listReserve'][$key]['view_cost'] = number_format($value['cost_total']) . '원';
            $viewData['listReserve'][$key]['real_cost'] = $value['cost_total'];
          }
        } else {
          $viewData['listReserve'][$key]['view_cost'] = number_format($value['cost_total']) . '원';
          $viewData['listReserve'][$key]['real_cost'] = $value['cost_total'];
        }
      }

      // 추천상품
      $search['item_visible'] = 'Y';
      $search['item_recommend'] = 'Y';
      $viewData['listItem'] = $this->shop_model->listItem(NULL, $search);
      foreach ($viewData['listItem'] as $key => $value) {
        // 대표 사진 추출
        $photo = unserialize($value['item_photo']);
        if (!empty($photo[0]) && file_exists(PHOTO_PATH . $photo[0])) {
          $viewData['listItem'][$key]['item_photo'] = PHOTO_URL . $photo[0];
        } else {
          $viewData['listItem'][$key]['item_photo'] = '/public/images/noimage.png';
        }

        // 카테고리명
        $itemCategory = unserialize($value['item_category']);
        foreach ($itemCategory as $cnt => $category) {
          $buf = $this->shop_model->viewCategory($category);
          $viewData['listItem'][$key]['item_category_name'][$cnt] = $buf['name'];
        }
      }
      $viewData['listItem'] = $this->load->view('/shop/list', $viewData, true);

      $this->_viewPage('reserve/check', $viewData);
    }
  }

  /**
   * 목록 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function schedule()
  {
    $clubIdx = get_cookie('COOKIE_CLUBIDX');

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 등록된 산행 목록
    $viewData['listNoticeCalendar'] = $this->reserve_model->listNotice($clubIdx);

    // 캘린더 설정
    $listCalendar = $this->admin_model->listCalendar();

    foreach ($listCalendar as $key => $value) {
      if ($value['holiday'] == 1) {
        $class = 'holiday';
      } else {
        $class = 'dayname';
      }
      $viewData['listNoticeCalendar'][] = array(
        'idx' => 0,
        'startdate' => $value['nowdate'],
        'enddate' => $value['nowdate'],
        'schedule' => 0,
        'status' => 'schedule',
        'mname' => $value['dayname'],
        'class' => $class,
      );
    }

    // 진행 중 산행
    $viewData['listNotice'] = $this->reserve_model->listNotice($viewData['view']['idx'], array(STATUS_ABLE, STATUS_CONFIRM));

    // 페이지 타이틀
    $viewData['pageTitle'] = '산행 일정';

    $this->_viewPage('reserve/schedule', $viewData);
  }

  /**
   * 공지 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function notice($noticeIdx=NULL)
  {
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $noticeIdx = html_escape($noticeIdx);
    $noticeIdxOld = html_escape($this->input->get('n'));

    if (!empty($noticeIdxOld)) {
      redirect(BASE_URL . '/reserve/notice/' . $noticeIdxOld);
      exit;
    }

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 산행 공지
    $viewData['notice'] = $this->reserve_model->viewNotice($noticeIdx);

    // 산행 공지 상세
    $viewData['listNoticeDetail'] = $this->reserve_model->listNoticeDetail($noticeIdx);

    foreach ($viewData['listNoticeDetail'] as $key => $notice) {
      $viewData['listNoticeDetail'][$key]['content'] = reset_html_escape($viewData['listNoticeDetail'][$key]['content']);
      preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $viewData['listNoticeDetail'][$key]['content'], $matches);
      foreach ($matches[1] as $value) {
        if (file_exists(BASE_PATH . $value)) {
          $size = getImageSize(BASE_PATH . $value);
          $viewData['listNoticeDetail'][$key]['content'] = str_replace('src="' . $value, 'data-width="' . $size[0] . '" data-height="' . $size[1] . '" src="'. $value, $viewData['listNoticeDetail'][$key]['content']);
        }
      }
    }

    // 댓글
    $cntReply = $this->story_model->cntStoryReply($noticeIdx, REPLY_TYPE_NOTICE);
    $cntLike = $this->story_model->cntStoryReaction($noticeIdx, REACTION_TYPE_NOTICE, REACTION_KIND_LIKE);
    $cntShare = $this->story_model->cntStoryReaction($noticeIdx, REACTION_TYPE_NOTICE, REACTION_KIND_SHARE);
    $viewData['notice']['reply_cnt'] = $cntReply['cnt'];
    $viewData['notice']['like_cnt'] = $cntLike['cnt'];
    $viewData['notice']['share_cnt'] = $cntShare['cnt'];

    $viewData['listReply'] = $this->story_model->listStoryReply($noticeIdx, REPLY_TYPE_NOTICE);
    $viewData['listReply'] = $this->load->view('story/reply', $viewData, true);

    // 페이지 타이틀
    $viewData['pageTitle'] = '산행 공지사항';

    $this->_viewPage('reserve/notice', $viewData);
  }

  /**
   * 예약 정보
   *
   * @return json
   * @author bjchoi
   **/
  public function information()
  {
    $nowDate = time();
    $userData = $this->load->get_var('userData');
    $clubIdx = html_escape($this->input->post('clubIdx'));
    $noticeIdx = html_escape($this->input->post('idx'));
    $resIdx = html_escape($this->input->post('resIdx'));
    $nowBus = html_escape($this->input->post('bus'));
    $nowSeat = html_escape($this->input->post('seat'));

    $notice = $this->reserve_model->viewNotice($noticeIdx);

    if (!empty($resIdx)) {
      $result['reserve'] = $this->reserve_model->viewReserve($clubIdx, $resIdx);
      if (is_null($result['reserve']['depositname'])) $result['reserve']['depositname'] = '';
      if (is_null($result['reserve']['memo'])) $result['reserve']['memo'] = '';
    } else {
      $result['reserve']['loc'] = '';
      $result['reserve']['memo'] = '';
    }

    $result['busType'] = getBusType($notice['bustype'], $notice['bus']); // 버스 형태별 좌석 배치

    // 해당 버스의 좌석
    foreach ($result['busType'] as $key => $busType) {
      foreach (range(1, $busType['seat']) as $seat) {
        $bus = $key + 1;
        $seat = checkDirection($seat, ($bus), $notice['bustype'], $notice['bus']);
        $result['seat'][$bus][] = $seat;
      }
    }

    $result['location'] = arrLocation(); // 승차위치
    $result['breakfast'] = arrBreakfast(); // 아침식사
    $result['nowSeat'] = checkDirection($nowSeat, $nowBus, $notice['bustype'], $notice['bus']);
    $result['userLocation'] = $userData['location'];

    // 페널티 계산
    $startTime = explode(':', $notice['starttime']);
    $startDate = explode('-', $notice['startdate']);
    $limitDate = mktime($startTime[0], $startTime[1], 0, $startDate[1], $startDate[2], $startDate[0]);

    // 예약 페널티
    $result['penalty'] = 0;
    if ( !empty($result['reserve']['regdate']) && ($result['reserve']['regdate'] + 43200) < $nowDate ) {
      // 예약 후 12시간 이후인 경우에만 페널티 부과
      if ( $limitDate < $nowDate ) {
        // 출발 이후 취소했다면 5점 페널티
        $result['penalty'] = 5;
      } elseif ( $limitDate < ($nowDate + 86400) ) {
        // 1일전 취소시 3점 페널티
        $result['penalty'] = 3;
      } elseif ( $limitDate < ($nowDate + 172800) ) {
        // 2일전 취소시 1점 페널티
        $result['penalty'] = 1;
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 예약 정보 - 버스
   *
   * @return json
   * @author bjchoi
   **/
  public function information_bus()
  {
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $noticeIdx = html_escape($this->input->post('idx'));
    $viewData['view'] = $this->reserve_model->viewNotice($noticeIdx);

    $result['busType'] = getBusType($viewData['view']['bustype'], $viewData['view']['bus']); // 버스 형태별 좌석 배치

    // 해당 버스의 좌석
    foreach ($result['busType'] as $key => $busType) {
      foreach (range(1, $busType['seat']) as $seat) {
        $bus = $key + 1;
        $seat = checkDirection($seat, ($bus), $viewData['view']['bustype'], $viewData['view']['bus']);
        $result['seat'][$bus][] = $seat;
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 예약/수정 처리
   *
   * @return json
   * @author bjchoi
   **/
  public function insert()
  {
    checkUserLogin();

    $now = time();
    $userData   = $this->load->get_var('userData');
    $postData   = $this->input->post();
    $clubIdx    = html_escape($postData['clubIdx']);
    $noticeIdx  = html_escape($postData['noticeIdx']);
    $bus        = $postData['bus'];
    $location   = $postData['location'];
    $memo       = $postData['memo'];
    $resIdx     = $postData['resIdx'];

    // 페널티 지급 적용자인지 체크
    $viewNotice = $this->reserve_model->viewNotice($noticeIdx);
    $startTime = explode(':', $viewNotice['starttime']);
    $startDate = explode('-', $viewNotice['startdate']);
    $limitDate = mktime($startTime[0], $startTime[1], 0, $startDate[1], $startDate[2], $startDate[0]);
    if ( $limitDate < ($now + 172800) ) $penalty = 1; else $penalty = 0;

    foreach ($postData['seat'] as $key => $seat) {
      if (!empty($resIdx[$key])) $resIdxNow = html_escape($resIdx[$key]); else $resIdxNow = 0;
      $nowBus = html_escape($bus[$key]);
      $seat = html_escape($seat);
      $processData  = array(
        'club_idx'  => $clubIdx,
        'rescode'   => $noticeIdx,
        'userid'    => $userData['userid'],
        'nickname'  => $userData['nickname'],
        'gender'    => $userData['gender'],
        'bus'       => $nowBus,
        'seat'      => $seat,
        'loc'       => html_escape($location[$key]),
        'memo'      => html_escape($memo[$key]),
        'penalty'   => $penalty,
        'regdate'   => $now,
      );

      // 무료회원은 곧바로 입금 완료
      if ($userData['level'] == LEVEL_FREE) {
        $processData['status'] = RESERVE_PAY;
      }

      if (empty($resIdxNow)) {
        // 등록
        $result = $this->reserve_model->insertReserve($processData);

        // 예약 번호 저장
        $reserveIdx[] = $result;

        // 로그 기록
        setHistory(LOG_RESERVE, $noticeIdx, $userData['userid'], $userData['nickname'], $viewNotice['subject'], $now);
      } else {
        // 수정
        // 선택한 좌석 예약 여부 확인
        $checkReserve = $this->reserve_model->checkReserve($noticeIdx, $nowBus, $seat);

        // 자신이 예약한 좌석만 수정 가능, 2인우선석/1인우등석은 수정
        if (  $checkReserve['userid'] == $userData['userid']
            || empty($checkReserve['idx'])
            || (!empty($checkReserve['priority']) && $checkReserve['nickname'] == '2인우선')
            || (!empty($checkReserve['honor']) && $checkReserve['nickname'] == '1인우등')
          ) {
          if (!empty($checkReserve['priority']) && $checkReserve['nickname'] == '2인우선') {
            $processData['status'] = RESERVE_ON;
          } elseif (!empty($checkReserve['honor']) && $checkReserve['nickname'] == '1인우등') {
            $processData['status'] = RESERVE_ON;
          }
          $result = $this->reserve_model->updateReserve($processData, $resIdxNow);

          // 예약 번호 저장
          $reserveIdx[] = $resIdxNow;
        }
      }
    }

    if (empty($result)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_seat_duplicate'));
    } else {
      if ($viewNotice['status'] == STATUS_ABLE) {
        $cntReserve = $this->reserve_model->cntReserve($noticeIdx);
        $cntReserveHonor = $this->reserve_model->cntReserveHonor($noticeIdx);
        if ($cntReserve['cnt'] - ($cntReserveHonor['cnt'] / 2) >= 15) {
          // 예약자가 15명 이상일 경우 확정으로 변경
          $processData = array('status' => STATUS_CONFIRM);
          $this->reserve_model->updateNotice($processData, $noticeIdx);
        }
      }

      // 회원 예약 횟수 갱신 (회원 레벨 체크를 위해)
      $rescount = $this->reserve_model->cntMemberReserve($userData['userid']);
      $updateValues['rescount'] = $rescount['cnt'];
      $this->member_model->updateMember($updateValues, $userData['idx']);

      $result = array('error' => 0, 'message' => '/reserve/list/' . $noticeIdx . '?c=' . implode(',', $reserveIdx));
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 대기자 처리
   *
   * @return json
   * @author bjchoi
   **/
  public function wait_insert()
  {
    checkUserLogin();

    $now = time();
    $userData   = $this->load->get_var('userData');
    $postData   = $this->input->post();
    $clubIdx    = html_escape($postData['clubIdx']);
    $noticeIdx  = html_escape($postData['noticeIdx']);
    $result = array('error' => 1, 'message' => $this->lang->line('error_all'));

    // 해당 회원이 현재 같은 예약에 대기중인지 확인
    $check = $this->reserve_model->viewReserveWait($clubIdx, $noticeIdx, $userData['idx']);

    if (!empty($check['created_at'])) {
      // 이미 대기중이면 삭제
      $rtn = $this->reserve_model->deleteReserveWait($clubIdx, $noticeIdx, $userData['idx']);

      if (!empty($rtn)) {
        $cntReserveWait = $this->reserve_model->cntReserveWait($clubIdx, $noticeIdx);
        $result = array('error' => 0, 'message' => $this->lang->line('msg_wait_delete'));
      }
    } else {
      // 대기중이지 않았을때는 등록
      foreach ($postData['location'] as $key => $value) {
        $processData  = array(
          'club_idx'    => $clubIdx,
          'notice_idx'  => $noticeIdx,
          'nickname'    => html_escape($userData['nickname']),
          'location'    => html_escape($value),
          'gender'      => html_escape($postData['gender'][$key]),
          'memo'        => html_escape($postData['memo'][$key]),
          'created_by'  => $userData['idx'],
          'created_at'  => $now,
        );
        $rtn = $this->reserve_model->insertReserveWait($processData);
      }

      if (!empty($rtn)) {
        $result = array('error' => 0, 'message' => $this->lang->line('msg_wait_insert'));
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 예약 취소
   *
   * @return json
   * @author bjchoi
   **/
  public function cancel()
  {
    checkUserLogin(); // 로그인 확인

    $nowDate = time();
    $userData = $this->load->get_var('userData');
    $clubIdx = html_escape($this->input->post('clubIdx'));
    $resIdx = explode(',', html_escape($this->input->post('resIdx')));
    $result = array('error' => 1, 'message' => '예약좌석 취소 중 문제가 발생했습니다. 다시 확인해주세요.');
    $penalty = 0;

    foreach ($resIdx as $idx) {
      // 유저 예약 정보
      $userReserve = $this->reserve_model->userReserve($clubIdx, NULL, $idx);
      $userBus = $userReserve['bus'];

      // 산행 정보
      $viewNotice = $this->reserve_model->viewNotice($userReserve['rescode']);

      // 해당 산행과 버스의 예약자 수
      $cntReserve = $this->reserve_model->cntReserve($userReserve['rescode'], $userBus);

      // 해당 산행의 대기자수
      $cntReserveWait = $this->reserve_model->cntReserveWait($userReserve['rescode']);

      $busType = getBusType($viewNotice['bustype'], $viewNotice['bus']);
      $maxSeat = array();
      foreach ($busType as $key => $value) {
        $cnt = $key + 1;
        $maxSeat[$cnt] = $value['seat'];
      }

      // 예약자가 초과됐을 경우, 대기자수가 1명이라도 있을 경우
      if ($cntReserve['cnt'] >= $maxSeat[$userBus] || $cntReserveWait['cnt'] >= 1) {
        // 예약 삭제 처리
        $updateValues = array(
          'userid' => '',
          'nickname' => '대기자 우선',
          'gender' => 'M',
          'loc' => 0,
          'memo' => '',
          'depositname' => '',
          'point' => 0,
          'priority' => 0,
          'honor' => 0,
          'vip' => 0,
          'manager' => 0,
          'penalty' => 0,
          'status' => RESERVE_WAIT
        );
        // 만석일 경우에는 대기자 우선석으로 변경
        $rtn = $this->reserve_model->updateReserve($updateValues, $idx);
      } else {
        // 좌석이 남아있을 경우
        if (!empty($userReserve['priority'])) {
          // 2인우선석이었던 경우 변경
          $updateValues = array(
            'userid' => '',
            'nickname' => '2인우선',
            'gender' => 'M',
            'loc' => 0,
            'memo' => '',
            'depositname' => '',
            'point' => 0,
            'vip' => 0,
            'manager' => 0,
            'penalty' => 0,
            'status' => 0
          );
          $this->reserve_model->updateReserve($updateValues, $userReserve['priority']);
          $rtn = $this->reserve_model->updateReserve($updateValues, $idx);
        } elseif (!empty($userReserve['honor'])) {
          // 1인우등석이었던 경우 변경
          $updateValues = array(
            'userid' => '',
            'nickname' => '1인우등',
            'gender' => 'M',
            'loc' => 0,
            'memo' => '',
            'depositname' => '',
            'point' => 0,
            'vip' => 0,
            'manager' => 0,
            'penalty' => 0,
            'status' => 0
          );
          $this->reserve_model->updateReserve($updateValues, $userReserve['honor']);
          $rtn = $this->reserve_model->updateReserve($updateValues, $idx);
        } else {
          // 일반 예약의 경우 삭제
          $rtn = $this->reserve_model->deleteReserve($idx);
        }
        
      }

      if (!empty($rtn)) {
        if ($viewNotice['status'] == STATUS_CONFIRM) {
          $cntReserve = $this->reserve_model->cntReserve($userReserve['rescode']);
          $cntReserveHonor = $this->reserve_model->cntReserveHonor($userReserve['rescode']);
          if ($cntReserve['cnt'] - ($cntReserveHonor['cnt'] / 2) < 15) {
            // 예약자가 15명 이하일 경우 예정으로 변경
            $updateValues = array('status' => STATUS_ABLE);
            $this->reserve_model->updateNotice($updateValues, $userReserve['rescode']);
          }
        }

        $startTime = explode(':', $userReserve['starttime']);
        $startDate = explode('-', $userReserve['startdate']);
        $limitDate = mktime($startTime[0], $startTime[1], 0, $startDate[1], $startDate[2], $startDate[0]);

        // 예약 페널티
        if ( !empty($userReserve['regdate']) && ($userReserve['regdate'] + 43200) < $nowDate ) {
          // 예약 후 12시간 이후인 경우에만 페널티 부과
          if ( $limitDate < $nowDate ) {
            // 출발 이후 취소했다면 5점 페널티
            $penalty = 5;
          } elseif ( $limitDate < ($nowDate + 86400) ) {
            // 1일전 취소시 3점 페널티
            $penalty = 3;
          } elseif ( $limitDate < ($nowDate + 172800) ) {
            // 2일전 취소시 1점 페널티
            $penalty = 1;
          }
        }

        $this->member_model->updatePenalty($userReserve['userid'], ($userData['penalty'] + $penalty));

        // 예약 페널티 로그 기록
        if ($penalty > 0) {
          setHistory(LOG_PENALTYUP, $userReserve['resCode'], $userReserve['userid'], $userReserve['nickname'], $userReserve['subject'] . ' 예약 취소', $nowDate, $penalty);
        }

        if ($userReserve['status'] == RESERVE_PAY) {
          // 요금 합계 (기존 버젼 호환용)
          $userReserve['cost'] = $userReserve['cost_total'] == 0 ? $userReserve['cost'] : $userReserve['cost_total'];

          // 이미 입금을 마친 상태라면, 전액 포인트로 환불 (무료회원은 환불 안함)
          if (empty($userData['level']) || $userData['level'] != 2) {
            if ($userData['level'] == 1) {
              // 평생회원은 할인 적용된 가격을 환불
              $userReserve['cost'] = $userReserve['cost'] - 5000;
              $this->member_model->updatePoint($userReserve['userid'], ($userData['point'] + $userReserve['cost']));
            } elseif ($userReserve['honor'] > 0) {
              // 1인우등 좌석의 취소는 1만원 추가 환불
              $userReserve['cost'] = $userReserve['cost'] + 10000;
              $this->member_model->updatePoint($userReserve['userid'], ($userData['point'] + $userReserve['cost']));
            } else {
              $this->member_model->updatePoint($userReserve['userid'], ($userData['point'] + $userReserve['cost']));
            }
            // 포인트 반환 로그 기록
            setHistory(LOG_POINTUP, $userReserve['resCode'], $userReserve['userid'], $userReserve['nickname'], $userReserve['subject'] . ' 예약 취소', $nowDate, $userReserve['cost']);
          }
        } elseif ($userReserve['status'] == RESERVE_ON && $userReserve['point'] > 0) {
          // 예약정보에 포인트가 있을때 반환
          $this->member_model->updatePoint($userReserve['userid'], ($userData['point'] + $userReserve['point']));

          // 포인트 반환 로그 기록
          setHistory(LOG_POINTUP, $userReserve['resCode'], $userReserve['userid'], $userReserve['nickname'], $userReserve['subject'] . ' 예약 취소', $nowDate, $userReserve['point']);
        }

        // 예약 취소 로그 기록
        setHistory(LOG_CANCEL, $userReserve['resCode'], $userReserve['userid'], $userReserve['nickname'], $userReserve['subject'], $nowDate);

        $result = array('error' => 0, 'message' => '');
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 결제정보 입력 처리
   *
   * @return json
   * @author bjchoi
   **/
  public function payment()
  {
    checkUserLogin();

    $nowDate = time();
    $userData = $this->load->get_var('userData');

    $checkReserve = $this->input->post('checkReserve');
    $clubIdx = html_escape($this->input->post('clubIdx'));
    $paymentCost = html_escape($this->input->post('paymentCost'));
    $processData['point'] = html_escape($this->input->post('usingPoint'));
    $processData['depositname'] = html_escape($this->input->post('depositName'));

    if ($paymentCost > 0) {
      $processData['status'] = RESERVE_ON;
    } else {
      $processData['status'] = RESERVE_PAY;
    }

    foreach ($checkReserve as $key => $value) {
      $idx = html_escape($value);

      // 최초 1회, 포인트를 사용했을때 포인트 차감
      if ($key == 0 && !empty($processData['point'])) {
        $userReserve = $this->reserve_model->userReserve($clubIdx, NULL, $idx);

        // 포인트 차감
        $this->member_model->updatePoint($userReserve['userid'], ($userData['point'] - $processData['point']));

        // 포인트 차감 로그 기록
        setHistory(LOG_POINTDN, $userReserve['resCode'], $userReserve['userid'], $userReserve['nickname'], $userReserve['subject'] . ' 예약', $nowDate, $processData['point']);
      }

      $rtn = $this->reserve_model->updateReserve($processData, $idx);
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_payment'));
    } else {
      $result = array('error' => 0, 'message' => '');
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
    $viewData['uri'] = 'reserve';

    // 회원 정보
    $viewData['userData'] = $this->load->get_var('userData');
    $viewData['userLevel'] = $this->load->get_var('userLevel');

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

    // 방문자 기록
    setVisitor();

    $this->load->view('club/header', $viewData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('club/footer', $viewData);
  }
}
?>
