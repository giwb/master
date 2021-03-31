<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 쇼핑몰 관리자 페이지 클래스
class ShopAdmin extends Admin_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('url', 'my_array_helper'));
    $this->load->library(array('image_lib', 'session'));
    $this->load->model(array('file_model', 'member_model', 'shop_model', 'story_model'));
  }

  /**
   * 쇼핑몰 : 등록된 용품 목록
   *
   * @return view
   * @return json
   * @author bjchoi
   **/
  public function index()
  {
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    $viewData['search'] = array(
      'item_name' => !empty($this->input->get('item_name')) ? html_escape($this->input->get('item_name')) : NULL,
      'item_category1' => !empty($this->input->get('item_category1')) ? html_escape($this->input->get('item_category1')) : NULL,
      'item_category2' => !empty($this->input->get('item_category2')) ? html_escape($this->input->get('item_category2')) : NULL
    );

    // 검색 분류
    $viewData['listCategory1'] = $this->shop_model->listCategory();

    if (!empty($viewData['search']['item_category1'])) {
      // 하위 분류
      $viewData['listCategory2'] = $this->shop_model->listCategory($viewData['search']['item_category1']);
    }

    // 페이징
    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;
    $paging['perPage'] = 20;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];

    // 등록된 용품 목록
    $viewData['listItem'] = $this->shop_model->listItem($paging, $viewData['search']);

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

    // 페이지 타이틀
    $viewData['pageTitle'] = '등록된 상품 관리';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'shop_header';

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('shop_admin/list', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      // 아이템 목록 템플릿
      $viewData['listItem'] = $this->load->view('shop_admin/list', $viewData, true);

      // 1페이지에는 View 페이지로 전송
      $this->_viewPage('shop_admin/index', $viewData);
    }
  }

  /**
   * 쇼핑몰 : 신규 용품 등록폼
   *
   * @return view
   * @author bjchoi
   **/
  public function entry($idx=NULL)
  {
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    $viewData['item_photo'] = array();
    $idx = html_escape($idx);

    // 분류
    $viewData['listCategory1'] = $this->shop_model->listCategory();

    if (!empty($idx)) {
      // 수정
      $viewData['view'] = $this->shop_model->viewItem($idx);

      // 옵션
      $itemOptions = unserialize($viewData['view']['item_option']);
      $cnt = 0;
      foreach ($itemOptions as $cost) {
        $viewData['view']['added_option'][$cnt] = $cost['item_option'];
        $viewData['view']['added_price'][$cnt] = !empty($cost['added_price']) ? $cost['added_price'] : 0;
        $viewData['view']['added_cost'][$cnt] = !empty($cost['added_cost']) ? $cost['added_cost'] : 0;
        $cnt++;
      }

      // 사진
      $itemPhoto = unserialize($viewData['view']['item_photo']);
      foreach ($itemPhoto as $value) {
        if (!empty($value) && file_exists(PHOTO_PATH . $value)) {
          $viewData['item_photo'][] = $value;
        }
      }

      // 분류
      $itemCategory = unserialize($viewData['view']['item_category']);
      $cnt = 0;
      foreach ($itemCategory as $category) {
        $viewData['view']['item_category_name'][$cnt] = $category;
        $cnt++;
      }

      if (!empty($viewData['view']['item_category_name'][0])) {
        // 하위 분류
        $viewData['listCategory2'] = $this->shop_model->listCategory($viewData['view']['item_category_name'][0]);
      }
    }

    // 페이지 타이틀
    $viewData['pageTitle'] = '신규 상품 등록';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'shop_header';

    $this->_viewPage('shop_admin/entry', $viewData);
  }

  /**
   * 쇼핑몰 : 신규 용품 등록/수정
   *
   * @return json
   * @author bjchoi
   **/
  public function update()
  {
    $now = time();
    $adminIdx = html_escape($this->session->userData['idx']);
    $postData = $this->input->post();
    $idx = html_escape($postData['idx']);
    $photo = html_escape($postData['filename']);
    $itemOption = html_escape($postData['item_option']);
    $addedPrice = html_escape($postData['added_price']);
    $addedCost = html_escape($postData['added_cost']);
    $postData['item_recommend'] = !empty($postData['item_recommend']) ? html_escape($postData['item_recommend']) : 'N';
    $arrCost = array();

    // 카테고리
    $itemCategory = array(
      html_escape($postData['item_category1']),
      html_escape($postData['item_category2'])
    );

    // 옵션/가격
    $cnt = 0;
    foreach ($itemOption as $value) {
      if (!empty($value)) {
        $arrCost[] = array(
          'item_option' => $value,
          'added_price' => !empty($addedPrice[$cnt]) ? $addedPrice[$cnt] : 0,
          'added_cost' => !empty($addedCost[$cnt]) ? $addedCost[$cnt] : 0
        );
        $cnt++;
      }
    }

    // 사진 처리
    if (!empty($photo)) {
      $arrPhoto = explode(',', $photo);
      foreach ($arrPhoto as $value) {
        if (!empty($value) && file_exists(UPLOAD_PATH . $value)) {
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
        $arrPhotoData[] = $value;
      }
    }

    $updateValues = array(
      'item_recommend'  => html_escape($postData['item_recommend']),
      'item_name'       => html_escape($postData['item_name']),
      'item_price'      => html_escape($postData['item_price']),
      'item_cost'       => html_escape($postData['item_cost']),
      'item_category'   => serialize($itemCategory),
      'item_option'     => serialize($arrCost),
      'item_photo'      => serialize($arrPhotoData),
      'item_content'    => html_escape($postData['item_content']),
    );

    if (empty($idx)) {
      // 등록
      $message = $this->lang->line('msg_insert_complete');
      $updateValues['created_by'] = $adminIdx;
      $updateValues['created_at'] = $now;
      $rtn = $this->shop_model->insertItem($updateValues);
    } else {
      // 수정
      $message = $this->lang->line('msg_update_complete');
      $updateValues['updated_by'] = $adminIdx;
      $updateValues['updated_at'] = $now;
      $rtn = $this->shop_model->updateItem($updateValues, $idx);
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_insert'));
    } else {
      $result = array('error' => 0, 'message' => $message);
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 쇼핑몰 : 용품 삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function delete()
  {
    $now = time();
    $adminIdx = html_escape($this->session->userData['idx']);
    $idx = html_escape($this->input->post('idx'));

    if (!empty($idx)) {
      $updateValues['deleted_by'] = $adminIdx;
      $updateValues['deleted_at'] = $now;
      $rtn = $this->shop_model->updateItem($updateValues, $idx);
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_delete'));
    } else {
      $result = array('error' => 0, 'message' => $this->lang->line('msg_delete_complete'));
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 쇼핑몰 : 카테고리 관리
   *
   * @return view
   * @author bjchoi
   **/
  public function category($parent = NULL)
  {
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    // 페이지 타이틀
    $viewData['pageTitle'] = '분류 설정';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'shop_header';

    if (empty($parent)) {
      // 부모가 없으면 뷰로 출력
      $viewData['listCategory'] = $this->shop_model->listCategory();
      $this->_viewPage('shop_admin/category', $viewData);
    } else {
      // 부모가 있으면 Json으로 출력
      $result = $this->shop_model->listCategory(html_escape($parent));
      $this->output->set_output(json_encode($result));
    }
  }

  /**
   * 쇼핑몰 : 카테고리 등록/수정
   *
   * @return json
   * @author bjchoi
   **/
  public function category_update()
  {
    $now = time();
    $adminIdx = html_escape($this->session->userData['idx']);
    $categoryIdx = html_escape($this->input->post('category_idx'));
    $categoryParent = html_escape($this->input->post('category_parent'));
    $updateValues['name'] = html_escape($this->input->post('category_name'));

    if (!empty($categoryParent)) {
      // 2차 카테고리의 경우
      $updateValues['parent'] = $categoryParent;
    }

    if (empty($categoryIdx)) {
      // 등록
      $message = $this->lang->line('msg_insert_complete');
      $updateValues['created_by'] = $adminIdx;
      $updateValues['created_at'] = $now;
      $categoryIdx = $rtn = $this->shop_model->insertCategory($updateValues);
    } else {
      // 수정
      $message = $this->lang->line('msg_update_complete');
      $updateValues['updated_by'] = $adminIdx;
      $updateValues['updated_at'] = $now;
      $rtn = $this->shop_model->updateCategory($updateValues, $categoryIdx);
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_insert'));
    } else {
      $result = array('error' => 0, 'message' => $categoryIdx);
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 쇼핑몰 : 카테고리 삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function category_delete()
  {
    $now = time();
    $adminIdx = html_escape($this->session->userData['idx']);
    $categoryIdx = html_escape($this->input->post('category_idx'));

    if (!empty($categoryIdx)) {
      $updateValues['deleted_by'] = $adminIdx;
      $updateValues['deleted_at'] = $now;
      $rtn = $this->shop_model->updateCategory($updateValues, $categoryIdx);
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_delete'));
    } else {
      $result = array('error' => 0, 'message' => $this->lang->line('msg_delete_complete'));
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 쇼핑몰 : 주문 관리
   *
   * @return view
   * @return json
   * @author bjchoi
   **/
  public function order()
  {
    // 클럽ID
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');

    $viewData['search'] = array(
      'item_name' => !empty($this->input->get('item_name')) ? html_escape($this->input->get('item_name')) : NULL,
      'nickname' => !empty($this->input->get('nickname')) ? html_escape($this->input->get('nickname')) : NULL,
      'mname' => !empty($this->input->get('mname')) ? html_escape($this->input->get('mname')) : NULL,
    );
    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;
    $paging['perPage'] = $viewData['perPage'] = 30;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];

    $viewData['maxPurchase'] = $this->shop_model->cntPurchase($viewData['search']);
    $viewData['listPurchase'] = $this->shop_model->listPurchase($paging, $viewData['search']);

    foreach ($viewData['listPurchase'] as $key => $value) {
      $items = unserialize($value['items']);
      $viewData['listPurchase'][$key]['listCart'] = $items;

      $viewData['listPurchase'][$key]['totalCost'] = 0;
      foreach ($items as $item) {
        $viewData['listPurchase'][$key]['totalCost'] += $item['cost'] * $item['amount'];
      }
    }

    // 페이지 타이틀
    $viewData['pageTitle'] = '주문 관리';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'shop_header';

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('shop_admin/order_list', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      // 아이템 목록 템플릿
      $viewData['listPurchase'] = $this->load->view('shop_admin/order_list', $viewData, true);

      // 1페이지에는 View 페이지로 전송
      $this->_viewPage('shop_admin/order', $viewData);
    }
  }

  /**
   * 쇼핑몰 : 상태 변경
   *
   * @return json
   * @author bjchoi
   **/
  public function change_status()
  {
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $now = time();
    $adminIdx = html_escape($this->session->userData['idx']);
    $idx = html_escape($this->input->post('idx'));
    $status = html_escape($this->input->post('status'));

    // 상태 수정
    $updateValues = array('status' => $status);
    $rtn = $this->shop_model->updatePurchase($updateValues, $idx);

    $viewPurchase = $this->shop_model->viewPurchase($idx);
    $arrItem = unserialize($viewPurchase['items']);
    $subject = '';

    if (!empty($arrItem)) {
      $maxItem = count($arrItem) - 1;
      $subject = $arrItem[0]['name'];
      if ($maxItem > 0) {
        $subject .= ' 외 ' . $maxItem . '개';
      }
    }

    // 로그 기록
    switch ($status) {
      case ORDER_PAY: // 입금확인
        // 관리자 입금확인 로그 기록
        setHistory($clubIdx, LOG_ADMIN_SHOP_DEPOSIT_CONFIRM, $idx, $viewPurchase['userid'], $viewPurchase['nickname'], $subject, $now);
        break;

      case ORDER_CANCEL: // 구매취소
        // 관리자 구매 취소 로그 기록
        setHistory($clubIdx, LOG_ADMIN_SHOP_CANCEL, $idx, $viewPurchase['userid'], $viewPurchase['nickname'], $subject, $now);

        if ($viewPurchase['point'] > 0) {
          // 사용했던 포인트 환불
          $this->member_model->updatePoint(1, $viewPurchase['userid'], ($viewPurchase['userPoint'] + $viewPurchase['point']));

          // 포인트 환불 로그 기록
          setHistory($clubIdx, LOG_POINTUP, $idx, $viewPurchase['userid'], $viewPurchase['nickname'], '관리자 구매 취소', $now, $viewPurchase['point']);
        }
        break;

      case ORDER_END: // 판매완료
        // 관리자 판매완료 로그 기록
        setHistory($clubIdx, LOG_ADMIN_SHOP_COMPLETE, $idx, $viewPurchase['userid'], $viewPurchase['nickname'], $subject, $now);
        break;
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
    } else {
      $result = array('error' => 0, 'message' => $this->lang->line('msg_complete'), 'type' => 1, 'idx' => $idx, 'status' => $status);
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 쇼핑몰 : 구매 정보 삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function delete_purchase()
  {
    $now = time();
    $adminIdx = html_escape($this->session->userData['idx']);
    $idx = html_escape($this->input->post('idx'));

    if (!empty($idx)) {
      $updateValues = array(
        'deleted_by' => $adminIdx,
        'deleted_at' => $now
      );
      $rtn = $this->shop_model->updatePurchase($updateValues, $idx);
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_delete'));
    } else {
      $result = array('error' => 0, 'message' => $this->lang->line('msg_delete_complete'), 'type' => 2, 'idx' => $idx);
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 쇼핑몰 : 상품 숨기기/보이기
   *
   * @return json
   * @author bjchoi
   **/
  public function change_visible()
  {
    $idx = html_escape($this->input->post('idx'));
    $result = array('error' => 1, 'message' => $this->lang->line('error_all'));

    if (!empty($idx)) {
      $viewItem = $this->shop_model->viewItem($idx);

      if ($viewItem['item_visible'] == 'Y') $itemVisible = 'N'; else $itemVisible = 'Y';

      $updateValues = array('item_visible' => $itemVisible);
      $this->shop_model->updateItem($updateValues, $idx);

      $result = array('error' => 0, 'message' => $itemVisible);
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
    $file = $_FILES['file_obj'];
    $arr = explode('.', $file['name']);
    $ext = array_pop($arr);

    if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {
      $filename = time() . mt_rand(10000, 99999) . '.' . $ext;

      if (move_uploaded_file($_FILES['file_obj']['tmp_name'], UPLOAD_PATH . $filename)) {
        // 사진 사이즈 줄이기 (가로 사이즈가 800보다 클 경우)
        $size = getImageSize(UPLOAD_PATH . $filename);
        if ($size[0] >= 800) {
          $this->image_lib->clear();
          $config['image_library'] = 'gd2';
          $config['source_image'] = UPLOAD_PATH . $filename;
          $config['maintain_ratio'] = TRUE;
          $config['width'] = 800;
          $this->image_lib->initialize($config);
          $this->image_lib->resize();
        }

        $result = array('error' => 0, 'message' => base_url() . UPLOAD_URL . $filename, 'filename' => $filename);
      } else {
        $result = array('error' => 1, 'message' => $this->lang->line('error_photo_delete'));
      }
    } else {
      $result = array('error' => 1, 'message' => $this->lang->line('error_photo_ext'));
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
      if (file_exists(UPLOAD_PATH . $filename)) {
        $rtn = unlink(UPLOAD_PATH . $filename);
      }
      if (file_exists(PHOTO_PATH . $filename)) {
        $rtn = unlink(PHOTO_PATH . $filename);
      }
      if (file_exists(PHOTO_PATH . 'thumb_' . $filename)) {
        $rtn = unlink(PHOTO_PATH . 'thumb_' . $filename);
      }
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_photo_delete'));
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
    $viewData['uri'] = $_SERVER['REQUEST_URI'];
    $viewData['keyword'] = html_escape($this->input->post('k'));

    // 클럽 정보
    $viewData['viewClub'] = $this->club_model->viewClub($viewData['clubIdx']);

    // 진행중 산행
    $search['clubIdx'] = $viewData['clubIdx'];
    $search['status'] = array(STATUS_ABLE, STATUS_CONFIRM);
    $viewData['listNoticeFooter'] = $this->admin_model->listNotice($search);

    // 클럽 대표이미지
    $files = $this->file_model->getFile('club', $viewData['clubIdx']);
    if (!empty($files[0]['filename']) && file_exists(PHOTO_PATH . $files[0]['filename'])) {
      $size = getImageSize(PHOTO_PATH . $files[0]['filename']);
      $viewData['viewClub']['main_photo'] = PHOTO_URL . $files[0]['filename'];
      $viewData['viewClub']['main_photo_width'] = $size[0];
      $viewData['viewClub']['main_photo_height'] = $size[1];
    }

    // 최신 댓글
    $paging['perPage'] = 5; $paging['nowPage'] = 0;
    $viewData['listFooterReply'] = $this->admin_model->listReply($viewData['clubIdx'], $paging);

    foreach ($viewData['listFooterReply'] as $key => $value) {
      if ($value['reply_type'] == REPLY_TYPE_STORY):  $viewData['listFooterReply'][$key]['url'] = BASE_URL . '/story/view/' . $value['story_idx']; endif;
      if ($value['reply_type'] == REPLY_TYPE_NOTICE): $viewData['listFooterReply'][$key]['url'] = BASE_URL . '/admin/main_view_progress/' . $value['story_idx']; endif;
      if ($value['reply_type'] == REPLY_TYPE_SHOP):   $viewData['listFooterReply'][$key]['url'] = BASE_URL . '/shop/item/' . $value['story_idx']; endif;
    }

    // 리다이렉트 URL 추출
    if ($_SERVER['SERVER_PORT'] == '80') $HTTP_HEADER = 'http://'; else $HTTP_HEADER = 'https://';
    $viewData['redirectUrl'] = $HTTP_HEADER . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    if (empty($viewData['viewClub']['main_design'])) $viewData['viewClub']['main_design'] = 1;

    $this->load->view('admin/header_' . $viewData['viewClub']['main_design'], $viewData);
    if (!empty($viewData['headerMenu'])) $this->load->view('admin/' . $viewData['headerMenu'], $viewData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('admin/footer_' . $viewData['viewClub']['main_design'], $viewData);
  }
}
?>
