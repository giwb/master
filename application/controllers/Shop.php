<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 쇼핑몰 페이지 클래스
class Shop extends Admin_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('url', 'my_array_helper'));
    $this->load->library(array('image_lib', 'session'));
    $this->load->model(array('shop_model'));
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
    $viewData['search'] = array(
      'item_name' => !empty($this->input->post('item_name')) ? html_escape($this->input->post('item_name')) : NULL,
      'item_category1' => !empty($this->input->post('item_category1')) ? html_escape($this->input->post('item_category1')) : NULL,
      'item_category2' => !empty($this->input->post('item_category2')) ? html_escape($this->input->post('item_category2')) : NULL
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
   * 쇼핑몰 : 신규 용품 등록폼
   *
   * @return view
   * @author bjchoi
   **/
  public function entry($idx = NULL)
  {
    $viewData = array();
    $viewData['item_photo'] = array();
    $idx = html_escape($idx);

    // 분류
    $viewData['listCategory1'] = $this->shop_model->listCategory();

    if (!empty($idx)) {
      // 수정
      $viewData['view'] = $this->shop_model->viewItem($idx);

      // 가격
      $itemCost = unserialize($viewData['view']['item_cost']);
      $cnt = 0;
      foreach ($itemCost as $cost) {
        $viewData['view']['item_option'][$cnt] = $cost['item_option'];
        $viewData['view']['item_option_cost'][$cnt] = $cost['item_cost'];
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

    $this->_viewPage('shop/entry', $viewData);
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
    $itemCost = html_escape($postData['item_cost']);
    $arrCost = array();

    // 카테고리
    $itemCategory = array(
      html_escape($postData['item_category1']),
      html_escape($postData['item_category2'])
    );

    // 옵션/가격
    foreach ($itemCost as $key => $value) {
      if (!empty($value)) {
        $arrCost[] = array(
          'item_option' => $itemOption[$key],
          'item_cost' => $value
        );
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
      'item_name'       => html_escape($postData['item_name']),
      'item_category'   => serialize($itemCategory),
      'item_cost'       => serialize($arrCost),
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
    if (empty($parent)) {
      // 부모가 없으면 뷰로 출력
      $viewData['listCategory'] = $this->shop_model->listCategory();
      $this->_viewPage('shop/category', $viewData);
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
    $viewData['search'] = array(
      'item_name' => !empty($this->input->post('item_name')) ? html_escape($this->input->post('item_name')) : NULL,
      'nickname' => !empty($this->input->post('nickname')) ? html_escape($this->input->post('nickname')) : NULL,
    );
    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;
    $paging['perPage'] = $viewData['perPage'] = 30;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];

    $viewData['maxOrder'] = $this->shop_model->cntOrder($viewData['search']);
    $viewData['listOrder'] = $this->shop_model->listOrder($paging, $viewData['search']);

    foreach ($viewData['listOrder'] as $key => $value) {
      $items = unserialize($value['items']);
      $viewData['listOrder'][$key]['listCart'] = $items;

      $viewData['listOrder'][$key]['totalCost'] = 0;
      foreach ($items as $item) {
        $viewData['listOrder'][$key]['totalCost'] += $item['cost'] * $item['amount'];
      }
    }

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('shop/order_list', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      // 아이템 목록 템플릿
      $viewData['listOrder'] = $this->load->view('shop/order_list', $viewData, true);

      // 1페이지에는 View 페이지로 전송
      $this->_viewPage('shop/order', $viewData);
    }
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
    $headerData['uri'] = $_SERVER['REQUEST_URI'];
    $headerData['keyword'] = html_escape($this->input->post('k'));
    $this->load->view('admin/header', $headerData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('admin/footer');
  }
}
?>
