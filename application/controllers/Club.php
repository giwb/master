<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 첫 페이지 클래스
class Club extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('cookie', 'security', 'url', 'my_array_helper'));
    $this->load->library(array('cart', 'image_lib'));
    $this->load->model(array('admin_model', 'area_model', 'club_model', 'file_model', 'notice_model', 'member_model', 'reserve_model', 'shop_model', 'story_model'));
  }

  /**
   * 클럽 메인 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function index()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    if (empty($userData['idx'])) $userData['idx'] = NULL;

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    if (empty($viewData['view'])) {
      redirect(base_url());
      exit;
    }

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

    // 최초 스토리 로딩
    $paging['perPage'] = 5;
    $paging['nowPage'] = (1 * $paging['perPage']) - $paging['perPage'];
    $viewData['listStory'] = $this->story_model->listStory($clubIdx, $paging);
    $viewData['listStory'] = $this->load->view('story/index', $viewData, true);

    $this->_viewPage('club/index', $viewData);
  }

  /**
   * 지난 산행보기
   *
   * @return view
   * @author bjchoi
   **/
  public function past()
  {
    $now = time();
    $clubIdx = $this->load->get_var('clubIdx');
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

    // 지난 산행
    $viewData['listPastNotice'] = $this->reserve_model->listNotice($clubIdx, array(STATUS_CLOSED), 'desc', $viewData['searchData']);

    // 페이지 타이틀
    $viewData['pageTitle'] = '지난 산행보기';

    $this->_viewPage('club/past', $viewData);
  }

  /**
   * 소개
   *
   * @return view
   * @author bjchoi
   **/
  public function about()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 페이지 타이틀
    $viewData['pageTitle'] = '산악회 소개';

    $this->_viewPage('club/about', $viewData);
  }

  /**
   * 안내인 소개
   *
   * @return view
   * @author bjchoi
   **/
  public function guide()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 페이지 타이틀
    $viewData['pageTitle'] = '등산 안내인 소개';

    $this->_viewPage('club/guide', $viewData);
  }

  /**
   * 이용안내
   *
   * @return view
   * @author bjchoi
   **/
  public function howto()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 페이지 타이틀
    $viewData['pageTitle'] = '이용안내';

    $this->_viewPage('club/howto', $viewData);
  }

  /**
   * 백산백소 소개
   *
   * @return view
   * @author bjchoi
   **/
  public function auth_about()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 페이지 타이틀
    $viewData['pageTitle'] = '백산백소 소개';

    $this->_viewPage('club/auth_about', $viewData);
  }

  /**
   * 백산백소 인증현황
   *
   * @return view
   * @author bjchoi
   **/
  public function auth()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $sDate = '2019-04-06';
    $nDate = date('Y-m-d');
    $rank = 0;
    $buf = 0;

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 백산백소 인증 데이터 불러오기
    $viewData['auth'] = $this->club_model->listAuth();

    foreach ($viewData['auth'] as $key => $value) {
      if ($buf != $value['cnt']) { $rank = $key; $rank++; }
      $viewData['auth'][$key]['rank'] = $rank;
      $viewData['auth'][$key]['title'] = '';

      $authList = $this->club_model->listAuthNotice($value['nickname']);
      foreach ($authList as $auth) {
        $viewData['auth'][$key]['title'] .= "<a target='_blank' href='" . $auth['photo'] . "'>" . $auth['title'] . "</a> / ";
      }
      $buf = $value['cnt'];
    }

    // 페이지 타이틀
    $viewData['pageTitle'] = '백산백소 인증현황';

    $this->_viewPage('club/auth', $viewData);
  }

  /**
   * 용품 목록
   *
   * @return view
   * @author bjchoi
   **/
  public function shop()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');

    $viewData['search'] = array(
      'item_name' => !empty($this->input->post('item_name')) ? html_escape($this->input->post('item_name')) : NULL,
      'item_category1' => !empty($this->input->post('item_category1')) ? html_escape($this->input->post('item_category1')) : NULL,
      'item_category2' => !empty($this->input->post('item_category2')) ? html_escape($this->input->post('item_category2')) : NULL,
    );
    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;
    $paging['perPage'] = $viewData['perPage'] = 20;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];

    // 용품 카운트
    $viewData['cntItem'] = $this->shop_model->cntItem($viewData['search']);

    // 용품 목록
    $viewData['listItem'] = $this->shop_model->listItem($paging, $viewData['search']);

    foreach ($viewData['listItem'] as $key => $value) {
      // 대표 사진 추출
      $photo = unserialize($value['item_photo']);
      if (!empty($photo[0]) && file_exists(PHOTO_PATH . $photo[0])) {
        $viewData['listItem'][$key]['item_photo'] = base_url() . PHOTO_URL . $photo[0];
      } else {
        $viewData['listItem'][$key]['item_photo'] = base_url() . 'public/images/noimage.png';
      }

      // 카테고리명
      $itemCategory = unserialize($value['item_category']);
      $cnt = 0;
      foreach ($itemCategory as $category) {
        $buf = $this->shop_model->viewCategory($category);
        $viewData['listItem'][$key]['item_category_name'][$cnt] = $buf['name'];
        $cnt++;
      }

      // 가격
      $itemCost = unserialize($value['item_cost']);
      $cnt = 0;
      foreach ($itemCost as $cost) {
        $viewData['listItem'][$key]['item_option'][$cnt] = $cost['item_option'];
        $viewData['listItem'][$key]['item_option_cost'][$cnt] = $cost['item_cost'];
        $cnt++;
      }
    }

    // 검색 분류
    $viewData['listCategory1'] = $this->shop_model->listCategory();

    if (!empty($viewData['search']['item_category1'])) {
      // 하위 분류
      $viewData['listCategory2'] = $this->shop_model->listCategory($viewData['search']['item_category1']);
    }

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 페이지 타이틀
    $viewData['pageTitle'] = '용품판매';

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('club/shop_list', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      // 아이템 목록 템플릿
      $viewData['listItem'] = $this->load->view('club/shop_list', $viewData, true);

      // 1페이지에는 View 페이지로 전송
      $this->_viewPage('club/shop', $viewData);
    }
  }

  /**
   * 용품 상세
   *
   * @return view
   * @author bjchoi
   **/
  public function shop_item()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $idx = html_escape($this->input->get('n'));

    // 상품이 없을때는 목록으로
    if (empty($idx)) {
      redirect(base_url() . 'club/shop/' . $clubIdx);
      exit;
    }

    $viewData['viewItem'] = $this->shop_model->viewItem($idx);

    // 사진이 실제로 업로드 되어 있는지 확인
    $arrPhotos = unserialize($viewData['viewItem']['item_photo']);
    $arr = array();
    foreach ($arrPhotos as $value) {
      if (!empty($value) && file_exists(PHOTO_PATH . $value)) {
        $arr[] = base_url() . PHOTO_URL . $value;
      }
    }
    $viewData['viewItem']['item_photo'] = $arr;

    // 카테고리명
    $itemCategory = unserialize($viewData['viewItem']['item_category']);
    $cnt = 0;
    foreach ($itemCategory as $category) {
      $buf = $this->shop_model->viewCategory($category);
      $viewData['viewItem']['item_category_name'][$cnt] = $buf['name'];
      $cnt++;
    }

    // 가격
    $itemCost = unserialize($viewData['viewItem']['item_cost']);
    $cnt = 0;
    foreach ($itemCost as $cost) {
      $viewData['viewItem']['item_option'][$cnt] = $cost['item_option'];
      $viewData['viewItem']['item_option_cost'][$cnt] = $cost['item_cost'];
      $cnt++;
    }

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 페이지 타이틀
    $viewData['pageTitle'] = '용품판매';

    $this->_viewPage('club/shop_item', $viewData);
  }

  /**
   * 장바구니
   *
   * @return view
   * @author bjchoi
   **/
  public function shop_cart()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $cnt = 0;

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 카트 정보
    $viewData['listCart'] = array();
    $viewData['total_amount'] = $viewData['total_cost'] = 0;

    foreach ($this->cart->contents() as $value) {
      $view = $this->shop_model->viewItem($value['id']);

      if (!empty($view['idx'])) {
        $viewData['listCart'][$cnt]['rowid'] = $value['rowid'];
        $viewData['listCart'][$cnt]['item_qty'] = $value['qty'];
        $viewData['listCart'][$cnt]['item_name'] = $view['item_name'];
        $viewData['listCart'][$cnt]['item_option'] = '';
        $viewData['listCart'][$cnt]['item_cost'] = $value['price'];
        $viewData['listCart'][$cnt]['subtotal'] = $value['subtotal'];
        $viewData['listCart'][$cnt]['item_photo'] = array();

        // 가격
        $itemCost = unserialize($view['item_cost']);
        $key = 0;
        foreach ($itemCost as $cost) {
          if ($value['name'] == $key) $viewData['listCart'][$cnt]['item_option'] = $cost['item_option'];
          $key++;
        }

        // 사진
        $arrPhotos = unserialize($view['item_photo']);
        if (!empty($arrPhotos[0]) && file_exists(PHOTO_PATH . $arrPhotos[0])) {
          $viewData['listCart'][$cnt]['item_photo'] = base_url() . PHOTO_URL . $arrPhotos[0];
        }

        $viewData['total_amount'] += $value['qty'];
        $viewData['total_cost'] += $value['subtotal'];
        $cnt++;
      }
    }

    // 페이지 타이틀
    $viewData['pageTitle'] = '용품판매';

    $this->_viewPage('club/shop_cart', $viewData);
  }

  /**
   * 장바구니에 담기
   *
   * @return json
   * @author bjchoi
   **/
  public function shop_cart_insert()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $idx = html_escape($this->input->post('idx'));
    $itemKey = html_escape($this->input->post('item_key'));
    $itemCost = html_escape($this->input->post('item_cost'));
    $result = array('error' => 1, 'message' => $this->lang->line('error_all'));

    if (!empty($idx)) {
      $view = $this->shop_model->viewItem($idx);

      // 장바구니에 담기
      $cartItem = array(
        'id'    => $idx,      // 상품번호
        'qty'   => 1,         // 개수
        'price' => $itemCost, // 가격
        'name'  => $itemKey,  // 옵션키
      );
      $rtn = $this->cart->insert($cartItem);

      if (!empty($rtn)) {
        $result = array('error' => 0, 'message' => base_url() . 'club/shop_cart/' . $clubIdx);
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 장바구니 수정/삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function shop_cart_update()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $rowid = html_escape($this->input->post('rowid'));
    $amount = html_escape($this->input->post('amount')); // 수량이 0이면 삭제
    $result = array('error' => 1, 'message' => $this->lang->line('error_all'));

    if (!empty($rowid)) {
      // 장바구니 수정
      $cartItem = array(
        'rowid' => $rowid,
        'qty'   => $amount,
      );
      $this->cart->update($cartItem);
      $result = array('error' => 0, 'message' => base_url() . 'club/shop_cart/' . $clubIdx);
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 구매진행
   *
   * @return view
   * @author bjchoi
   **/
  public function shop_checkout()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $cnt = 0;

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 카트 정보
    $viewData['listCart'] = array();
    $viewData['total_amount'] = $viewData['total_cost'] = 0;

    foreach ($this->cart->contents() as $value) {
      $view = $this->shop_model->viewItem($value['id']);

      if (!empty($view['idx'])) {
        $viewData['listCart'][$cnt]['rowid'] = $value['rowid'];
        $viewData['listCart'][$cnt]['item_qty'] = $value['qty'];
        $viewData['listCart'][$cnt]['item_name'] = $view['item_name'];
        $viewData['listCart'][$cnt]['item_option'] = '';
        $viewData['listCart'][$cnt]['item_cost'] = $value['price'];
        $viewData['listCart'][$cnt]['subtotal'] = $value['subtotal'];
        $viewData['listCart'][$cnt]['item_photo'] = array();

        // 가격
        $itemCost = unserialize($view['item_cost']);
        $key = 0;
        foreach ($itemCost as $cost) {
          if ($value['name'] == $key) $viewData['listCart'][$cnt]['item_option'] = $cost['item_option'];
          $key++;
        }

        // 사진
        $arrPhotos = unserialize($view['item_photo']);
        if (!empty($arrPhotos[0]) && file_exists(PHOTO_PATH . $arrPhotos[0])) {
          $viewData['listCart'][$cnt]['item_photo'] = base_url() . PHOTO_URL . $arrPhotos[0];
        }

        $viewData['total_amount'] += $value['qty'];
        $viewData['total_cost'] += $value['subtotal'];
        $cnt++;
      }
    }

    // 용품 인수를 위한 회원 예약 내역
    $viewData['listMemberReserve'] = $this->shop_model->listMemberReserve($clubIdx, $userData['userid']);

    // 페이지 타이틀
    $viewData['pageTitle'] = '용품판매 - 구매진행';

    $this->_viewPage('club/shop_checkout', $viewData);
  }

  /**
   * 구매 완료하기
   *
   * @return json
   * @author bjchoi
   **/
  public function shop_insert()
  {
    $now = time();
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $postData = $this->input->post();

    $insertValues = array(
      'notice_idx'    => !empty($postData['reserveIdx']) ? html_escape($postData['reserveIdx']) : NULL, // 인수받을 산행
      'point'         => !empty($postData['usingPoint']) ? html_escape($postData['usingPoint']) : 0,    // 사용한 포인트
      'deposit_name'  => !empty($postData['depositName']) ? html_escape($postData['depositName']) : '', // 입금자명
      'created_by'    => $userData['idx'],
      'created_at'    => $now
    );

    // 카트에 담긴 상품 입력
    $arrItem = array();
    $totalCost = 0;
    $cnt = 0;
    foreach ($this->cart->contents() as $value) {
      $viewItem = $this->shop_model->viewItem($value['id']);

      $arrItem[$cnt]['idx'] = $viewItem['idx']; // 상품 고유번호
      $arrItem[$cnt]['name'] = $viewItem['item_name']; // 상품명
      $arrItem[$cnt]['cost'] = $value['price']; // 가격
      $arrItem[$cnt]['amount']  = $value['qty']; // 수량
      $totalCost += $value['subtotal']; // 합계

      // 옵션명 추출
      $itemCost = unserialize($viewItem['item_cost']);
      $key = 0;
      foreach ($itemCost as $cost) {
        if ($value['name'] == $key) $arrItem[$cnt]['option'] = $cost['item_option']; // 옵션명
        $key++;
      }

      // 사진
      $photo = unserialize($viewItem['item_photo']);
      $arrItem[$cnt]['photo'] = $photo[0];

      $cnt++;
    }
    $insertValues['items'] = serialize($arrItem);

    if ($totalCost == $insertValues['point']) {
      // 포인트로 전부 결제했을때는 곧바로 입금완료
      $insertValues['status'] = ORDER_PAY;
    }

    // 구매한 상품 저장
    $rtn = $this->shop_model->insertPurchase($insertValues);

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_purchase'));
    } else {
      // 카트 초기화
      $this->cart->destroy();

      // 포인트 차감
      if ($insertValues['point'] > 0) {
        $this->member_model->updatePoint($clubIdx, $userData['userid'], ($userData['point'] - $insertValues['point']));
      }

      // 구매 기록
      $cntItem = count($arrItem);
      $subject = $arrItem[0]['name'];
      if ($cntItem > 1) $subject .= ' 외 ' . ($cntItem - 1) . '개';
      setHistory(LOG_SHOP_BUY, $rtn, $userData['userid'], $userData['nickname'], $subject, $now);

      if ($totalCost == $insertValues['point']) {
        // 포인트로 전부 결제했을때 결제 기록
        setHistory(LOG_SHOP_CHECKOUT, $rtn, $userData['userid'], $userData['nickname'], '전액 포인트 결제 완료', $now, $insertValues['point']);
      } elseif (!empty($insertValues['deposit_name'])) {
        // 은행 입금을 위한 입금자명을 입력했을 경우 결제 기록
        setHistory(LOG_SHOP_CHECKOUT, $rtn, $userData['userid'], $userData['nickname'], '입금자명 입력 - ' . $insertValues['deposit_name'], $now);
      }

      $result = array('error' => 0, 'message' => base_url() . 'club/shop_complete/' . $clubIdx . '?n=' . $rtn);
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 구매 완료 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function shop_complete()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $idx = !empty($this->input->get('n')) ? html_escape($this->input->get('n')) : NULL;

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    if (empty($idx)) {
      redirect(base_url() . 'club/shop/' . $clubIdx);
    } else {
      // 구매 정보
      $viewData['viewPurchase'] = $this->shop_model->viewPurchase($idx);

      // 상품 정보
      $viewData['listCart'] = unserialize($viewData['viewPurchase']['items']);

      // 인수할 산행
      if (!empty($viewData['viewPurchase']['notice_idx'])) {
        $viewData['viewNotice'] = $this->reserve_model->viewNotice($clubIdx, $viewData['viewPurchase']['notice_idx']);
      }
    }

    $this->_viewPage('club/shop_complete', $viewData);
  }

  /**
   * 결제정보 입력
   *
   * @return json
   * @author bjchoi
   **/
  public function shop_payment()
  {
    $nowDate = time();
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');

    if (!empty($userData['idx'])) {
      $checkPurchase = $this->input->post('checkPurchase');
      $paymentCost = html_escape($this->input->post('paymentCost'));

      $updateValues = array(
        'point' => html_escape($this->input->post('usingPoint')),
        'deposit_name' => html_escape($this->input->post('depositName')),
        'notice_idx' => html_escape($this->input->post('noticeIdx'))
      );

      if ($paymentCost > 0) {
        $updateValues['status'] = ORDER_ON;
      } else {
        $updateValues['status'] = ORDER_PAY;
      }

      foreach ($checkPurchase as $key => $value) {
        $idx = html_escape($value);
        $point = 0;
        $viewPurchase = $this->shop_model->viewPurchase($idx); // 구매정보

        // 최초 1회, 포인트 확인
        if ($key == 0) {
          if (empty($viewPurchase['point']) && !empty($updateValues['point']) && $viewPurchase['point'] < $updateValues['point']) {
            // 포인트 차감
            $this->member_model->updatePoint($clubIdx, $userData['userid'], ($userData['point'] - $updateValues['point']));
            // 포인트 차감 로그 기록
            setHistory(LOG_POINTDN, $idx, $userData['userid'], $userData['nickname'], '구매 포인트 사용', $nowDate, $updateValues['point']);
          } elseif ($viewPurchase['point'] > $updateValues['point']) {
            // 포인트 환불
            $this->member_model->updatePoint($clubIdx, $userData['userid'], ($userData['point'] + ($viewPurchase['point'] - $updateValues['point'])));
            // 포인트 환불 로그 기록
            setHistory(LOG_POINTUP, $idx, $userData['userid'], $userData['nickname'], '구매 포인트 환불', $nowDate, ($viewPurchase['point'] - $updateValues['point']));
          }
        }

        $rtn = $this->shop_model->updatePurchase($updateValues, $idx);
      }
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_payment'));
    } else {
      $result = array('error' => 0, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 구매 취소
   *
   * @return json
   * @author bjchoi
   **/
  public function shop_cancel()
  {
    $nowDate = time();
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $idx = html_escape($this->input->post('resIdx'));
    $viewPurchase = $this->shop_model->viewPurchase($idx); // 구매정보
    $arrItem = unserialize($viewPurchase['items']);
    $subject = '';

    if (!empty($arrItem)) {
      $maxItem = count($arrItem) - 1;
      $subject = $arrItem[0]['name'];
      if ($maxItem > 0) {
        $subject .= ' 외 ' . $maxItem . '개';
      }
    }

    $updateValues = array(
      'deleted_by' => $userData['idx'],
      'deleted_at' => $nowDate
    );
    $rtn = $this->shop_model->updatePurchase($updateValues, $idx);

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_cancel'));
    } else {
      // 구매 취소 로그 기록
      setHistory(LOG_SHOP_CANCEL, $idx, $userData['userid'], $userData['nickname'], $subject, $nowDate);

      if ($viewPurchase['point'] > 0) {
        // 사용했던 포인트 환불
        $this->member_model->updatePoint($clubIdx, $userData['userid'], ($userData['point'] + $viewPurchase['point']));

        // 포인트 환불 로그 기록
        setHistory(LOG_POINTUP, $idx, $userData['userid'], $userData['nickname'], '구매 취소', $nowDate, $viewPurchase['point']);
      }

      $result = array('error' => 0, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 사진첩
   *
   * @return view
   * @author bjchoi
   **/
  public function album()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $viewData['userIdx'] = $userData['idx'];
    $viewData['adminCheck'] = $userData['admin'];

    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;
    $paging['perPage'] = $viewData['perPage'] = 30;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 사진첩 카운트
    $viewData['cntAlbum'] = $this->club_model->cntAlbum($clubIdx);

    // 사진첩
    $viewData['listAlbum'] = $this->club_model->listAlbum($clubIdx, $paging);

    foreach ($viewData['listAlbum'] as $key => $value) {
      $photo = $this->file_model->getFile('album', $value['idx'], NULL, 1);
      if (!empty($photo[0]['filename'])) {
        $viewData['listAlbum'][$key]['photo'] = base_url() . PHOTO_URL . $photo[0]['filename'];
      } else {
        $viewData['listAlbum'][$key]['photo'] = base_url() . 'public/images/noimage.png';
      }
    }

    // 페이지 타이틀
    $viewData['pageTitle'] = '사진첩';

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('club/album_list', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      // 아이템 목록 템플릿
      $viewData['listAlbum'] = $this->load->view('club/album_list', $viewData, true);

      // 1페이지에는 View 페이지로 전송
      $this->_viewPage('club/album', $viewData);
    }
  }

  /**
   * 사진첩 보기
   *
   * @return json
   * @author bjchoi
   **/
  public function album_view()
  {
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $idx = html_escape($this->input->post('idx'));
    $result = array();
    $cnt = 0;

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 사진첩
    $viewData['viewAlbum'] = $this->club_model->viewAlbum($clubIdx, $idx);

    $photos = $this->file_model->getFile('album', $idx);
    foreach ($photos as $value) {
      if (!empty($value['filename'])) {
        $size = getImageSize(PHOTO_PATH . $value['filename']);
        $result[$cnt]['width'] = $size[0];
        $result[$cnt]['height'] = $size[1];
        $result[$cnt]['src'] = base_url() . PHOTO_URL . $value['filename'];
        $result[$cnt]['title'] = $viewData['viewAlbum']['subject'];
        $cnt++;
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 사진 등록폼
   *
   * @return view
   * @author bjchoi
   **/
  public function album_upload()
  {
    checkUserLogin();

    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $idx = html_escape($this->input->get('n'));

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 수정
    if (!empty($idx)) {
      $viewData['viewAlbum'] = $this->club_model->viewAlbum($clubIdx, $idx);
      $viewData['photos'] = $this->file_model->getFile('album', $idx);
    } else {
      $viewData['photos'] = array();
    }

    // 페이지 타이틀
    $viewData['pageTitle'] = '사진첩';

    $this->_viewPage('club/album_upload', $viewData);
  }

  /**
   * 사진 등록/수정
   *
   * @return view
   * @author bjchoi
   **/
  public function album_update()
  {
    $now = time();
    $pageName = 'album';
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $postData = $this->input->post();
    $idx = html_escape($postData['idx']);
    $photos = html_escape($postData['photos']);

    $updateValues = array(
      'club_idx' => $clubIdx,
      'subject' => html_escape($postData['subject']),
      'content' => html_escape($postData['content']),
    );

    if (empty($idx)) {
      // 등록
      $updateValues['created_by'] = $userData['idx'];
      $updateValues['created_at'] = $now;
      $idx = $rtn = $this->club_model->insertAlbum($updateValues);
    } else {
      // 수정
      $updateValues['updated_by'] = $userData['idx'];
      $updateValues['updated_at'] = $now;
      $rtn = $this->club_model->updateAlbum($updateValues, $idx);
    }

    // 사진 처리
    if (!empty($idx) && !empty($photos)) {
      $arrPhoto = explode(',', $photos);

      foreach ($arrPhoto as $value) {
        if (!empty($value) && file_exists(UPLOAD_PATH . $value)) {
          $fileValues = array(
            'page' => $pageName,
            'page_idx' => $idx,
            'filename' => $value,
            'created_at' => $now
          );
          $this->file_model->insertFile($fileValues);

          // 파일 이동
          rename(UPLOAD_PATH . $value, PHOTO_PATH . $value);

          // 썸네일 만들기
          $this->image_lib->clear();
          $config['image_library'] = 'gd2';
          $config['source_image'] = PHOTO_PATH . $value;
          $config['new_image'] = PHOTO_PATH . 'thumb_' . $value;
          $config['create_thumb'] = TRUE;
          $config['maintain_ratio'] = FALSE;
          $config['thumb_marker'] = '';
          $config['width'] = 200;
          $config['height'] = 200;
          $this->image_lib->initialize($config);
          $this->image_lib->resize();
        }
      }
    }

    redirect(base_url() . 'club/album');
  }

  /**
   * 사진첩 삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function album_delete()
  {
    $now = time();
    $clubIdx = $this->load->get_var('clubIdx');
    $userData = $this->load->get_var('userData');
    $idx = html_escape($this->input->post('idx'));

    if (!empty($idx)) {
      $updateValues['deleted_by'] = $userData['idx'];
      $updateValues['deleted_at'] = $now;
      $rtn = $this->club_model->updateAlbum($updateValues, $idx);
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_delete'));
    } else {
      $result = array('error' => 0, 'message' => $this->lang->line('msg_delete_complete'));
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

    if (!empty($filename)) {
      if (file_exists(PHOTO_PATH . $filename)) {
        unlink(PHOTO_PATH . $filename);
      }
      if (file_exists(PHOTO_PATH . 'thumb_' . $filename)) {
        unlink(PHOTO_PATH . 'thumb_' . $filename);
      }
      $this->file_model->deleteFile($filename);
    }

    $result = array('error' => 0);
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
        // 사진 사이즈 줄이기 (세로 사이즈가 1024보다 클 경우)
        $maxSize = 1024;
        $size = getImageSize(UPLOAD_PATH . $filename);
        if ($size[1] >= $maxSize) {
          $this->image_lib->clear();
          $config['image_library'] = 'gd2';
          $config['source_image'] = UPLOAD_PATH . $filename;
          $config['maintain_ratio'] = TRUE;
          $config['height'] = $maxSize;
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
    $viewData['uri'] = 'club';

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
