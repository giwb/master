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
    $this->load->model(array('club_model', 'file_model', 'member_model', 'reserve_model', 'shop_model', 'story_model'));
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

    $nowDate = time();
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
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
      $viewData['viewMember'] = $this->member_model->viewMember($userData['idx']);

      // 용품 인수를 위한 회원 예약 내역
      $viewData['listMemberReserve'] = $this->shop_model->listMemberReserve($clubIdx, $userData['idx']);

      // 구매 내역
      $paging['perPage'] = 5; $paging['nowPage'] = 0;
      $search['created_by'] = $userData['idx'];
      $viewData['listPurchase'] = $this->shop_model->listPurchase($paging, $search);

      foreach ($viewData['listPurchase'] as $key => $value) {
        // 상품 정보
        $items = unserialize($value['items']);
        $viewData['listPurchase'][$key]['listCart'] = $items;

        $viewData['listPurchase'][$key]['cost_total'] = 0;
        foreach ($items as $item) {
          $viewData['listPurchase'][$key]['cost_total'] += $item['cost'] * $item['amount'];
        }
      }

      // 구매 내역 템플릿 저장
      $viewData['listPurchase'] = $this->load->view('member/mypage_shop_list', $viewData, true);

      // 예약 내역
      $paging['perPage'] = 5; $paging['nowPage'] = 0;
      $viewData['maxReserve'] = $this->reserve_model->maxReserve($clubIdx, $userData['idx']);
      $viewData['userReserve'] = $this->reserve_model->userReserve($clubIdx, $userData['idx'], NULL, $paging);

      foreach ($viewData['userReserve'] as $key => $value) {
        $busType = getBusType($value['notice_bustype'], $value['notice_bus']); // 버스 형태별 좌석 배치

        if (empty($value['cost_total'])) {
          $value['cost_total'] = $value['cost'];
        }

        /*
        if (!empty($busType[$value['bus']-1]['bus_type']) && $busType[$value['bus']-1]['bus_type'] == 1) {
          // 우등버스 할증 (2020/12/08 추가)
          $viewData['userReserve'][$key]['cost'] = $value['cost'] = $viewData['userReserve'][$key]['cost'] + 10000;
          $viewData['userReserve'][$key]['cost_total'] = $value['cost_total'] = $viewData['userReserve'][$key]['cost_total'] + 10000;
        }
        */

        if ($userData['level'] == LEVEL_LIFETIME) {
          // 평생회원 할인
          $viewData['userReserve'][$key]['view_cost'] = '<s class="text-secondary">' . number_format($value['cost_total']) . '원</s> → ' . number_format($value['cost_total'] - 5000) . '원';
          $viewData['userReserve'][$key]['real_cost'] = $value['cost_total'] - 5000;
        } elseif ($userData['level'] == LEVEL_FREE) {
          // 무료회원 할인
          $viewData['userReserve'][$key]['view_cost'] = '<s class="text-secondary">' . number_format($value['cost_total']) . '원</s> → ' . '0원';
          $viewData['userReserve'][$key]['real_cost'] = 0;
        } elseif ($value['honor'] > 1) {
          // 1인우등 할인
          if ($key%2 == 0) {
            $honorCost = 10000;
            $viewData['userReserve'][$key]['view_cost'] = '<s class="text-secondary">' . number_format($value['cost_total']) . '원</s> → ' . number_format($honorCost) . '원';
            $viewData['userReserve'][$key]['real_cost'] = $honorCost;
          } else {
            $viewData['userReserve'][$key]['view_cost'] = number_format($value['cost_total']) . '원';
            $viewData['userReserve'][$key]['real_cost'] = $value['cost_total'];
          }
        } else {
          $viewData['userReserve'][$key]['view_cost'] = number_format($value['cost_total']) . '원';
          $viewData['userReserve'][$key]['real_cost'] = $value['cost_total'];
        }

        // 페널티 체크
        $startTime = explode(':', $value['starttime']);
        $startDate = explode('-', $value['startdate']);
        $limitDate = mktime($startTime[0], $startTime[1], 0, $startDate[1], $startDate[2], $startDate[0]);

        // 예약 페널티
        if ( $limitDate < ($nowDate + 86400) ) {
          // 1일전 취소시 3점 페널티
          $viewData['userReserve'][$key]['penalty'] = 3;
        } elseif ( $limitDate < ($nowDate + 172800) ) {
          // 2일전 취소시 1점 페널티
          $viewData['userReserve'][$key]['penalty'] = 1;
        }
      }

      // 예약 취소 내역 (로그)
      $viewData['maxReserveCancel'] = $this->reserve_model->maxReserveCancel($clubIdx, $userData['idx']);
      $viewData['userReserveCancel'] = $this->reserve_model->userReserveCancel($clubIdx, $userData['idx']);

      // 산행 내역
      $viewData['maxVisit'] = $this->reserve_model->maxVisit($clubIdx, $userData['idx']);
      $viewData['userVisit'] = $this->reserve_model->userVisit($clubIdx, $userData['idx']);

      // 산행 횟수
      $viewData['userVisitCount'] = $this->reserve_model->userVisitCount($clubIdx, $userData['idx']);

      // 포인트 내역
      $viewData['userPoint'] = $this->member_model->userPointLog($clubIdx, $userData['idx']);

      // 페널티 내역
      $viewData['userPenalty'] = $this->member_model->userPenaltyLog($clubIdx, $userData['idx']);

      // 페이지 타이틀
      $viewData['pageTitle'] = '마이페이지';

      $this->_viewPage('member/index', $viewData);
    }
  }

  /**
   * 드라이버 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function driver()
  {
    checkUserLogin();

    $now = time();
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $userData = $this->load->get_var('userData');
    $sdate = html_escape($this->input->get('sdate'));
    $edate = html_escape($this->input->get('edate'));
    $keyword = html_escape($this->input->get('keyword'));

    $viewData['searchData']['keyword'] = !empty($keyword) ? $keyword : NULL;
    $viewData['searchData']['sdate'] = !empty($sdate) ? $sdate : date('Y-m-01', $now);
    $viewData['searchData']['edate'] = !empty($edate) ? $edate : date('Y-m-31', $now);
    $viewData['searchData']['syear'] = !empty($sdate) ? date('Y', strtotime($sdate)) : date('Y');
    $viewData['searchData']['smonth'] = !empty($sdate) ? date('m', strtotime($sdate)) : date('m');
    $viewData['searchData']['prev'] = 'sdate=' . date('Y-m-01', strtotime('-1 months', strtotime($viewData['searchData']['sdate']))) . '&edate=' . date('Y-m-t', strtotime('-1 months', strtotime($viewData['searchData']['sdate'])));
    $viewData['searchData']['next'] = 'sdate=' . date('Y-m-01', strtotime('+1 months', strtotime($viewData['searchData']['sdate']))) . '&edate=' . date('Y-m-t', strtotime('+1 months', strtotime($viewData['searchData']['sdate'])));

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 모든 산행
    $viewData['listNoticeDriver'] = $this->reserve_model->listNotice($clubIdx, NULL, 'asc', $viewData['searchData']);

    // 버스 형태
    $viewData['listBustype'] = $this->reserve_model->listBustype();

    // 페이지 타이틀
    $viewData['pageTitle'] = '드라이버 페이지';

    $this->_viewPage('member/driver', $viewData);
  }

  /**
   * 드라이버 상세 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function driver_view()
  {
    checkUserLogin();

    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $userData = $this->load->get_var('userData');
    $noticeIdx = html_escape($this->input->get('n'));
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    if (!empty($noticeIdx)) {
      // 진행 중 산행
      $viewData['viewNotice'] = $this->reserve_model->viewNotice($noticeIdx);

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
        $viewData['viewNotice']['cost_standard'] = 0;
      } elseif ($viewData['viewNotice']['count'] >= 30 && $viewData['viewNotice']['count'] < 40) {
        $viewData['viewNotice']['cost_driver'] = 40000;
        $viewData['viewNotice']['cost_standard'] = 1;
      } elseif ($viewData['viewNotice']['count'] >= 40 && $viewData['viewNotice']['count'] < $viewData['maxSeat']) {
        $viewData['viewNotice']['cost_driver'] = 80000;
        $viewData['viewNotice']['cost_standard'] = 2;
      } elseif ($viewData['viewNotice']['count'] == $viewData['maxSeat']) {
        $viewData['viewNotice']['cost_driver'] = 120000;
        $viewData['viewNotice']['cost_standard'] = 3;
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
      $viewData['viewNotice']['total_add'] = 0;
      foreach ($viewData['viewNotice']['driving_add'] as $value) {
        if (!empty($value)) $viewData['viewNotice']['total_add'] += $value;
      }

      // 운행요금 계산
      $viewData['viewNotice']['total_driving_cost'] = ceil($viewData['viewNotice']['driving_total'] / 10000) * 10000;
      $viewData['viewNotice']['total_driving_cost1'] = ceil((($viewData['viewNotice']['driving_default'] + $viewData['viewNotice']['total_fuel'] + $viewData['viewNotice']['total_cost'] + $viewData['viewNotice']['driving_add'][0] + $viewData['viewNotice']['driving_add'][1])) / 10000) * 10000;
      $viewData['viewNotice']['total_driving_cost2'] = ceil((($viewData['viewNotice']['driving_default'] + $viewData['viewNotice']['total_fuel'] + $viewData['viewNotice']['total_cost'] + $viewData['viewNotice']['driving_add'][0] + $viewData['viewNotice']['driving_add'][1]) + 40000) / 10000) * 10000;
      $viewData['viewNotice']['total_driving_cost3'] = ceil((($viewData['viewNotice']['driving_default'] + $viewData['viewNotice']['total_fuel'] + $viewData['viewNotice']['total_cost'] + $viewData['viewNotice']['driving_add'][0] + $viewData['viewNotice']['driving_add'][1]) + 80000) / 10000) * 10000;
      $viewData['viewNotice']['total_driving_cost4'] = ceil((($viewData['viewNotice']['driving_default'] + $viewData['viewNotice']['total_fuel'] + $viewData['viewNotice']['total_cost'] + $viewData['viewNotice']['driving_add'][0] + $viewData['viewNotice']['driving_add'][1]) + 120000) / 10000) * 10000;

      // 버스 형태별 좌석 배치
      $viewData['busType'] = getBusType($viewData['viewNotice']['bustype'], $viewData['viewNotice']['bus']);

      // 시간별 승차위치
      $listLocation = arrLocation($viewData['view']['club_geton'], $viewData['viewNotice']['starttime']);
      $cnt = 0;

      foreach ($viewData['busType'] as $key1 => $bus) {
        $busNumber = $key1 + 1;
        $viewData['busType'][$key1]['total'] = 0;
        foreach ($listLocation as $key2 => $value) {
          $viewData['busType'][$key1]['listLocation'][] = $value;
          $resData = $this->admin_model->listReserveLocation($noticeIdx, $busNumber, $value['short']);
          foreach ($resData as $people) {
            if (!empty($people['honor'])) {
              $cnt++;
              if ($cnt > 1) {
                $viewData['busType'][$key1]['listLocation'][$key2]['nickname'][] = $people['nickname'];
                $cnt = 0;
                $viewData['busType'][$key1]['total']++;
              }
            } else {
              $viewData['busType'][$key1]['listLocation'][$key2]['nickname'][] = $people['nickname'];
              $viewData['busType'][$key1]['total']++;
            }
          }
        }
      }
    }

    // 페이지 타이틀
    $viewData['pageTitle'] = '드라이버 페이지';

    $this->_viewPage('member/driver_view', $viewData);
  }

  /**
   * 드라이버 변경
   *
   * @return json
   * @author bjchoi
   **/
  public function driver_change()
  {
    checkUserLogin();

    $now = time();
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $userData = $this->load->get_var('userData');
    $noticeIdx = html_escape($this->input->post('noticeIdx'));
    $bus = html_escape($this->input->post('bus'));
    $busType = html_escape($this->input->post('busType'));

    $viewNotice = $this->reserve_model->viewNotice($noticeIdx);
    $arrBusType = unserialize($viewNotice['bustype']);

    foreach ($arrBusType as $key => $value) {
      $busNo = $key + 1;
      if ($bus == $busNo) {
        $newBusType[$key] = $busType;
        $oldBus = $this->reserve_model->viewBustype($value);
        $newBus = $this->reserve_model->viewBustype($busType);
      } else {
        $newBusType[$key] = $value;
      }
    }
    $updateValues['bustype'] = serialize($newBusType);

    $rtn = $this->reserve_model->updateNotice($updateValues, $noticeIdx);

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
    } else {
      // 로그 남기기
      setHistory($clubIdx, LOG_DRIVER_CHANGE, $noticeIdx, $userData['idx'], $userData['nickname'], $oldBus['bus_name'] . ' → ' . $newBus['bus_name'], $now);
      $result = array('error' => 0, 'message' => $this->lang->line('msg_change_complete'));
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 마이페이지 - 구매 내역 상세 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function shop()
  {
    checkUserLogin();

    $nowDate = time();
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $userData = $this->load->get_var('userData');
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 페이징
    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;
    $paging['perPage'] = $viewData['perPage'] = 5;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];

    // 구매 내역
    $viewData['maxPurchase'] = $this->shop_model->cntPurchase();
    $search['created_by'] = $userData['idx'];
    $viewData['listPurchase'] = $this->shop_model->listPurchase($paging, $search);

    foreach ($viewData['listPurchase'] as $key => $value) {
      // 상품 정보
      $items = unserialize($value['items']);
      $viewData['listPurchase'][$key]['listCart'] = $items;

      $viewData['listPurchase'][$key]['cost_total'] = 0;
      foreach ($items as $item) {
        $viewData['listPurchase'][$key]['cost_total'] += $item['cost'] * $item['amount'];
      }
    }

    // 회원 정보
    $viewData['viewMember'] = $this->member_model->viewMember($userData['idx']);

    // 용품 인수를 위한 회원 예약 내역
    $viewData['listMemberReserve'] = $this->shop_model->listMemberReserve($clubIdx, $userData['idx']);

    // 페이지 타이틀
    $viewData['pageTitle'] = '구매 내역';

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('member/mypage_shop_list', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      // 목록 템플릿
      $viewData['listPurchase'] = $this->load->view('member/mypage_shop_list', $viewData, true);

      // 1페이지에는 View 페이지로 전송
      $this->_viewPage('member/mypage_shop.php', $viewData);
    }
  }

  /**
   * 마이페이지 - 예약 내역 상세 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function reserve()
  {
    checkUserLogin();

    $nowDate = time();
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $userData = $this->load->get_var('userData');
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 페이징
    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;
    $paging['perPage'] = $viewData['perPage'] = 20;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];

    // 예약 내역
    $viewData['maxReserve'] = $this->reserve_model->maxReserve($clubIdx, $userData['idx']);
    $viewData['userReserve'] = $this->reserve_model->userReserve($clubIdx, $userData['idx'], NULL, $paging);

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
      } elseif ($value['honor'] > 1) {
          // 1인우등 할인
          if ($key == 1) {
            $honorCost = 10000;
            $viewData['userReserve'][$key]['view_cost'] = '<s class="text-secondary">' . number_format($value['cost_total']) . '원</s> → ' . number_format($honorCost) . '원';
            $viewData['userReserve'][$key]['real_cost'] = $honorCost;
          } else {
            $viewData['userReserve'][$key]['view_cost'] = number_format($value['cost_total']) . '원';
            $viewData['userReserve'][$key]['real_cost'] = $value['cost_total'];
          }
      } else {
        $viewData['userReserve'][$key]['view_cost'] = number_format($value['cost_total']) . '원';
        $viewData['userReserve'][$key]['real_cost'] = $value['cost_total'];
      }
      // 페널티 체크
      $startTime = explode(':', $value['starttime']);
      $startDate = explode('-', $value['startdate']);
      $limitDate = mktime($startTime[0], $startTime[1], 0, $startDate[1], $startDate[2], $startDate[0]);

      // 예약 페널티
      if ( $limitDate < ($nowDate + 86400) ) {
        // 1일전 취소시 3점 페널티
        $viewData['userReserve'][$key]['penalty'] = 3;
      } elseif ( $limitDate < ($nowDate + 172800) ) {
        // 2일전 취소시 1점 페널티
        $viewData['userReserve'][$key]['penalty'] = 1;
      }
    }

    // 회원 정보
    $viewData['viewMember'] = $this->member_model->viewMember($userData['idx']);

    // 페이지 타이틀
    $viewData['pageTitle'] = '예약 내역';

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('member/mypage_reserve_list', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      // 목록 템플릿
      $viewData['userReserve'] = $this->load->view('member/mypage_reserve_list', $viewData, true);

      // 1페이지에는 View 페이지로 전송
      $this->_viewPage('member/mypage_reserve', $viewData);
    }
  }

  /**
   * 마이페이지 - 예약취소 내역 상세 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function reserve_cancel()
  {
    checkUserLogin();

    $nowDate = time();
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $userData = $this->load->get_var('userData');
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 페이징
    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;
    $paging['perPage'] = $viewData['perPage'] = 30;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];

    // 예약취소 내역 (로그)
    $viewData['maxReserveCancel'] = $this->reserve_model->maxReserveCancel($clubIdx, $userData['idx']);
    $viewData['userReserveCancel'] = $this->reserve_model->userReserveCancel($clubIdx, $userData['idx'], $paging);

    // 회원 정보
    $viewData['viewMember'] = $this->member_model->viewMember($userData['idx']);

    // 페이지 타이틀
    $viewData['pageTitle'] = '예약취소 내역';

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('member/mypage_reserve_cancel_list', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      // 목록 템플릿
      $viewData['userReserveCancel'] = $this->load->view('member/mypage_reserve_cancel_list', $viewData, true);

      // 1페이지에는 View 페이지로 전송
      $this->_viewPage('member/mypage_reserve_cancel', $viewData);
    }
  }

  /**
   * 마이페이지 - 산행 내역 상세 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function reserve_past()
  {
    checkUserLogin();

    $nowDate = time();
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $userData = $this->load->get_var('userData');
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 페이징
    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;
    $paging['perPage'] = $viewData['perPage'] = 30;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];

    // 산행 내역
    $viewData['maxVisit'] = $this->reserve_model->maxVisit($clubIdx, $userData['idx']);
    $viewData['userVisit'] = $this->reserve_model->userVisit($clubIdx, $userData['idx'], $paging);

    // 회원 정보
    $viewData['viewMember'] = $this->member_model->viewMember($userData['idx']);

    // 페이지 타이틀
    $viewData['pageTitle'] = '산행 내역';

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('member/mypage_reserve_past_list', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      // 목록 템플릿
      $viewData['userVisit'] = $this->load->view('member/mypage_reserve_past_list', $viewData, true);

      // 1페이지에는 View 페이지로 전송
      $this->_viewPage('member/mypage_reserve_past', $viewData);
    }
  }

  /**
   * 마이페이지 - 포인트 내역 상세 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function point()
  {
    checkUserLogin();

    $nowDate = time();
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $userData = $this->load->get_var('userData');
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 페이징
    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;
    $paging['perPage'] = $viewData['perPage'] = 30;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];

    // 포인트 내역
    $viewData['maxPoint'] = $this->member_model->maxPointLog($clubIdx, $userData['idx']);
    $viewData['userPoint'] = $this->member_model->userPointLog($clubIdx, $userData['idx'], $paging);

    // 회원 정보
    $viewData['viewMember'] = $this->member_model->viewMember($userData['idx']);

    // 페이지 타이틀
    $viewData['pageTitle'] = '포인트 내역';

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('member/mypage_point_list', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      // 목록 템플릿
      $viewData['userPoint'] = $this->load->view('member/mypage_point_list', $viewData, true);

      // 1페이지에는 View 페이지로 전송
      $this->_viewPage('member/mypage_point', $viewData);
    }
  }

  /**
   * 마이페이지 - 페널티 내역 상세 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function penalty()
  {
    checkUserLogin();

    $nowDate = time();
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $userData = $this->load->get_var('userData');
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 페이징
    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;
    $paging['perPage'] = $viewData['perPage'] = 30;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];

    // 포인트 내역
    $viewData['maxPenalty'] = $this->member_model->maxPenaltyLog($clubIdx, $userData['idx']);
    $viewData['userPenalty'] = $this->member_model->userPenaltyLog($clubIdx, $userData['idx'], $paging);

    // 회원 정보
    $viewData['viewMember'] = $this->member_model->viewMember($userData['idx']);

    // 페이지 타이틀
    $viewData['pageTitle'] = '페널티 내역';

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('member/mypage_penalty_list', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      // 목록 템플릿
      $viewData['userPenalty'] = $this->load->view('member/mypage_penalty_list', $viewData, true);

      // 1페이지에는 View 페이지로 전송
      $this->_viewPage('member/mypage_penalty', $viewData);
    }
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

    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $userData = $this->load->get_var('userData');
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 회원정보
    $viewData['viewMember'] = $this->member_model->viewMember($userData['idx']);

    // 생년월일 나누기
    if (!empty($viewData['viewMember']['birthday'])) {
      $buf = explode('/', $viewData['viewMember']['birthday']);
      $viewData['viewMember']['birthday_year'] = $buf[0];
      $viewData['viewMember']['birthday_month'] = $buf[1];
      $viewData['viewMember']['birthday_day'] = $buf[2];
    }

    // 전화번호 나누기
    $buf = explode('-', $viewData['viewMember']['phone']);
    $viewData['viewMember']['phone1'] = $buf[0];
    $viewData['viewMember']['phone2'] = $buf[1];
    $viewData['viewMember']['phone3'] = $buf[2];

    // 아이콘
    if (file_exists(AVATAR_PATH . $viewData['viewMember']['idx'])) {
      $viewData['viewMember']['photo'] = AVATAR_URL . $viewData['viewMember']['idx'];
    } elseif (!empty($viewData['viewMember']['icon_thumbnail'])) {
      $viewData['viewMember']['photo'] = $viewData['viewMember']['icon_thumbnail'];
    } else {
      $viewData['viewMember']['photo'] = '/public/images/user.png';
    }

    // 페이지 타이틀
    $viewData['pageTitle'] = '개인정보수정';

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
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
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

      $rtn = $this->member_model->updateMember($updateValues, $userData['idx']);

      if (!empty($rtn)) {
        // 사진 등록
        $filename = html_escape($_FILES['photo']['tmp_name']);
        if (!empty($filename) && file_exists($filename)) {
          if (file_exists(PHOTO_PATH . $userData['idx'])) {
            unlink(PHOTO_PATH . $userData['idx']);
          }
          if (file_exists(AVATAR_PATH . $userData['idx'])) {
            unlink(AVATAR_PATH . $userData['idx']);
          }
          move_uploaded_file($filename, AVATAR_PATH . $userData['idx']);
        }

        $result = array('error' => 0, 'message' => $this->lang->line('msg_update_complete'));
      } else {
        $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 아바타 삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function photo_delete()
  {
    $userData = $this->load->get_var('userData');

    if (file_exists(PHOTO_PATH . $userData['idx'])) {
      unlink(PHOTO_PATH . $userData['idx']);
    }
    if (file_exists(AVATAR_PATH . $userData['idx'])) {
      unlink(AVATAR_PATH . $userData['idx']);
    }
    $result = array('error' => 0, 'message' => '');

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
    $userData = $this->load->get_var('userData');
    $userIdx = $this->input->post('userIdx');
    $result = array('error' => 1, 'message' => $this->lang->line('error_all'));

    if ($userData['idx'] == $userIdx) {
      $updateValues['quitdate'] = time();
      $rtn = $this->member_model->updateMember($updateValues, $userIdx);

      if (!empty($rtn)) {
        // 세션 삭제
        $this->session->unset_userdata('userData');
        $result = array('error' => 0, 'message' => '');
      }
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

    // 클럽 메뉴
    $viewData['listAbout'] = $this->club_model->listAbout($viewData['view']['idx']);

    // 등록된 산행 목록
    $viewData['listNoticeFooter'] = $viewData['listNoticeCalendar'] = $this->reserve_model->listNotice($viewData['view']['idx'], array(STATUS_ABLE, STATUS_CONFIRM));

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

    // 안부 인사
    $page = 1;
    $paging['perPage'] = 8;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];
    $viewData['listStory'] = $this->story_model->listStory($viewData['view']['idx'], $paging);

    foreach ($viewData['listStory'] as $key => $value) {
      if (file_exists(PHOTO_PATH . $value['user_idx'])) {
        $viewData['listStory'][$key]['avatar'] = AVATAR_URL . $value['user_idx'];
      } else {
        $viewData['listStory'][$key]['avatar'] = '/public/images/user.png';
      }
    }

    // 클럽 로고
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

    if (empty($viewData['view']['main_design'])) $viewData['view']['main_design'] = 1;

    $this->load->view('club/header_' . $viewData['view']['main_design'], $viewData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('club/footer_' . $viewData['view']['main_design'], $viewData);
  }
}
?>
