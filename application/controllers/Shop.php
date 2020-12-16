<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 쇼핑몰 페이지 클래스
class Shop extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('url', 'my_array_helper'));
    $this->load->library(array('cart', 'image_lib', 'session'));
    $this->load->model(array('club_model', 'file_model', 'member_model', 'shop_model', 'story_model'));
  }
  /**
   * 용품 목록
   *
   * @return view
   * @author bjchoi
   **/
  public function index()
  {
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $userData = $this->load->get_var('userData');

    $viewData['search'] = array(
      'item_name' => !empty($this->input->get('k')) ? html_escape($this->input->get('k')) : NULL,
      'item_category1' => !empty($this->input->get('c')) ? html_escape($this->input->get('c')) : NULL,
      'item_visible' => 'Y',
    );

    if (empty($viewData['search']['item_category1'])) {
      // 카테고리 지정을 안했다면 인기상품만 보여주기
      $viewData['search']['item_recommend'] = 'Y';
    }

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
        $viewData['listItem'][$key]['item_photo'] = PHOTO_URL . $photo[0];
      } else {
        $viewData['listItem'][$key]['item_photo'] = 'public/images/noimage.png';
      }

      // 카테고리명
      $itemCategory = unserialize($value['item_category']);
      foreach ($itemCategory as $cnt => $category) {
        $buf = $this->shop_model->viewCategory($category);
        $viewData['listItem'][$key]['item_category_name'][$cnt] = $buf['name'];
      }
    }

    // 검색 분류
    $viewData['listCategory'] = $this->shop_model->listCategory();

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 페이지 타이틀
    $viewData['pageTitle'] = '구매대행 상품';

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('shop/list', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      // 아이템 목록 템플릿
      $viewData['listItem'] = $this->load->view('shop/list', $viewData, true);

      // 1페이지에는 View 페이지로 전송
      $this->_viewPage('shop/index', $viewData);
    }
  }

  /**
   * 용품 상세
   *
   * @return view
   * @author bjchoi
   **/
  public function item($itemIdx)
  {
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $userData = $this->load->get_var('userData');
    $itemIdx = html_escape($itemIdx);

    // 상품이 없을때는 목록으로
    if (empty($itemIdx)) {
      redirect(BASE_URL . '/shop');
      exit;
    }

    // 상품 정보
    $viewData['viewItem'] = $this->shop_model->viewItem($itemIdx);

    // 사진이 실제로 업로드 되어 있는지 확인
    $arrPhotos = unserialize($viewData['viewItem']['item_photo']);
    $arr = array();
    foreach ($arrPhotos as $value) {
      if (!empty($value) && file_exists(PHOTO_PATH . $value)) {
        $arr[] = PHOTO_URL . $value;
      }
    }
    $viewData['viewItem']['item_photo'] = $arr;

    // 카테고리명
    $itemCategory = unserialize($viewData['viewItem']['item_category']);
    foreach ($itemCategory as $cnt => $category) {
      $buf = $this->shop_model->viewCategory($category);
      $viewData['viewItem']['item_category_name'][$cnt] = $buf['name'];
    }
    if (!empty($itemCategory[0])) {
      $viewData['search']['item_category1'] = $itemCategory[0];
    } else {
      $viewData['search']['item_category1'] = 0;
    }

    // 옵션
    $itemOptions = unserialize($viewData['viewItem']['item_option']);
    if (!empty($itemOptions)) {
      foreach ($itemOptions as $option) {
        $viewData['viewItem']['item_options'][] = $option;
      }
    }

    // 검색 분류
    $viewData['listCategory'] = $this->shop_model->listCategory();

    // 댓글
    $cntReply = $this->story_model->cntStoryReply($itemIdx, REPLY_TYPE_SHOP);
    $cntLike = $this->story_model->cntStoryReaction($itemIdx, REACTION_TYPE_SHOP, REACTION_KIND_LIKE);
    $cntShare = $this->story_model->cntStoryReaction($itemIdx, REACTION_TYPE_SHOP, REACTION_KIND_SHARE);
    $viewData['viewItem']['reply_cnt'] = $cntReply['cnt'];
    $viewData['viewItem']['like_cnt'] = $cntLike['cnt'];
    $viewData['viewItem']['share_cnt'] = $cntShare['cnt'];

    $viewData['listReply'] = $this->story_model->listStoryReply($itemIdx, REPLY_TYPE_SHOP);
    $viewData['listReply'] = $this->load->view('story/reply', $viewData, true);

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 페이지 타이틀
    $viewData['pageTitle'] = '구매대행 상품';

    $this->_viewPage('shop/item', $viewData);
  }

  /**
   * 장바구니
   *
   * @return view
   * @author bjchoi
   **/
  public function cart()
  {
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $userData = $this->load->get_var('userData');
    $cnt = 0;

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 카트 정보
    $viewData['listCart'] = array();
    $viewData['total_amount'] = $viewData['total_price'] = $viewData['total_cost'] = 0;

    foreach ($this->cart->contents() as $value) {
      $view = $this->shop_model->viewItem($value['id']);
      if (!empty($view['idx'])) {
        $viewData['listCart'][$cnt]['rowid'] = $value['rowid'];
        $viewData['listCart'][$cnt]['item_qty'] = $value['qty'];
        $viewData['listCart'][$cnt]['item_name'] = $view['item_name'];
        if (!empty($value['options']['item_option'])) {
          $viewData['listCart'][$cnt]['item_option'] = $value['options']['item_option'];
        }
        $viewData['listCart'][$cnt]['item_price'] = $value['options']['item_price'];
        $viewData['listCart'][$cnt]['item_cost'] = $value['price'];
        $viewData['listCart'][$cnt]['subtotal_price'] = $value['qty'] * $value['options']['item_price'];
        $viewData['listCart'][$cnt]['subtotal_cost'] = $value['qty'] * $value['price'];
        $viewData['listCart'][$cnt]['item_photo'] = array();

        // 사진
        $arrPhotos = unserialize($view['item_photo']);
        if (!empty($arrPhotos[0]) && file_exists(PHOTO_PATH . $arrPhotos[0])) {
          $viewData['listCart'][$cnt]['item_photo'] = PHOTO_URL . $arrPhotos[0];
        }

        $viewData['total_amount'] += $value['qty'];
        $viewData['total_cost'] += $value['subtotal'];
        $cnt++;
      }
    }

    // 페이지 타이틀
    $viewData['pageTitle'] = '구매대행 상품';

    $this->_viewPage('shop/cart', $viewData);
  }

  /**
   * 장바구니에 담기
   *
   * @return json
   * @author bjchoi
   **/
  public function cart_insert()
  {
    $userData = $this->load->get_var('userData');
    $postData = $this->input->post();
    $result = array('error' => 1, 'message' => $this->lang->line('error_all'));

    $idx = $code = html_escape($postData['idx']);
    $amount = html_escape($postData['amount']);
    $option = !empty($postData['option']) ? html_escape($postData['option']) : NULL;
    $type = html_escape($postData['type']);

    if (!empty($idx)) {
      // 상품 정보
      $view = $this->shop_model->viewItem($idx);

      // 옵션
      $itemOptions = unserialize($view['item_option']);

      // 장바구니에 담기
      foreach ($amount as $key => $value) {
        foreach ($itemOptions as $cnt => $op) {
          if ($option[$key] == $cnt) {
            $view['item_option_check'] = true;
            if (!empty($op['item_option'])) $view['item_option'] = $op['item_option'];
            if (!empty($op['added_price'])) $view['item_price'] = $op['added_price'];
            if (!empty($op['added_cost']))  $view['item_cost'] = $op['added_cost'];
            $code .= '_' . $cnt;
          }
        }

        $cartItem = array(
          'id'    => $code, // 상품번호
          'qty'   => $value, // 개수
          'price' => $view['item_cost'], // 가격
          'name'  => time(),
          'options' => array('item_price' => $view['item_price'])
        );

        if (!empty($view['item_option_check'])) {
          $cartItem['options']['item_option'] = $view['item_option'];
        }

        $rtn = $this->cart->insert($cartItem);
      }

      if (!empty($rtn)) {
        $result = array('error' => 0, 'message' => '');
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
  public function cart_update()
  {
    $userData = $this->load->get_var('userData');
    $clubIdx = html_escape($this->input->post('clubIdx'));
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
      $result = array('error' => 0, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 구매진행
   *
   * @return view
   * @author bjchoi
   **/
  public function checkout()
  {
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
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
        if (!empty($value['options']['item_option'])) {
          $viewData['listCart'][$cnt]['item_option'] = $value['options']['item_option'];
        }
        $viewData['listCart'][$cnt]['item_price'] = $value['options']['item_price'];
        $viewData['listCart'][$cnt]['item_cost'] = $value['price'];
        $viewData['listCart'][$cnt]['subtotal_price'] = $value['qty'] * $value['options']['item_price'];
        $viewData['listCart'][$cnt]['subtotal_cost'] = $value['qty'] * $value['price'];
        $viewData['listCart'][$cnt]['item_photo'] = array();

        // 사진
        $arrPhotos = unserialize($view['item_photo']);
        if (!empty($arrPhotos[0]) && file_exists(PHOTO_PATH . $arrPhotos[0])) {
          $viewData['listCart'][$cnt]['item_photo'] = PHOTO_URL . $arrPhotos[0];
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

    $this->_viewPage('shop/checkout', $viewData);
  }

  /**
   * 구매 완료하기
   *
   * @return json
   * @author bjchoi
   **/
  public function insert()
  {
    $now = time();
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
      $arrItem[$cnt]['amount'] = $value['qty']; // 수량
      if (!empty($value['options']['item_option'])) {
        $arrItem[$cnt]['option'] = $value['options']['item_option']; // 수량
      }
      $totalCost += $value['subtotal']; // 합계

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
    if ($cnt > 0) {
      $rtn = $this->shop_model->insertPurchase($insertValues);
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_purchase'));
    } else {
      // 카트 초기화
      $this->cart->destroy();

      // 포인트 차감
      if ($insertValues['point'] > 0) {
        $this->member_model->updatePoint($userData['userid'], ($userData['point'] - $insertValues['point']));
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

      $result = array('error' => 0, 'message' => $rtn);
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 구매 완료 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function complete($idx)
  {
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $userData = $this->load->get_var('userData');
    $idx = html_escape($idx);

    if (empty($idx)) {
      redirect(BASE_URL . '/shop');
    } else {
      // 클럽 정보
      $viewData['view'] = $this->club_model->viewClub($clubIdx);

      // 구매 정보
      $viewData['viewPurchase'] = $this->shop_model->viewPurchase($idx);

      // 상품 정보
      $viewData['listCart'] = unserialize($viewData['viewPurchase']['items']);

      // 인수할 산행
      if (!empty($viewData['viewPurchase']['notice_idx'])) {
        $viewData['viewNotice'] = $this->reserve_model->viewNotice($viewData['viewPurchase']['notice_idx']);
      }
    }

    // 페이지 타이틀
    $viewData['pageTitle'] = '구매대행 상품';

    $this->_viewPage('shop/complete', $viewData);
  }

  /**
   * 결제정보 입력
   *
   * @return json
   * @author bjchoi
   **/
  public function payment()
  {
    $nowDate = time();
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
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
  public function cancel()
  {
    $nowDate = time();
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
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

    if (empty($viewData['view']['main_design'])) $viewData['view']['main_design'] = 1;

    $this->load->view('club/header_' . $viewData['view']['main_design'], $viewData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('club/footer_' . $viewData['view']['main_design'], $viewData);
  }
}
?>
