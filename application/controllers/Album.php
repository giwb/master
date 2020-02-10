<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 첫 페이지 클래스
class Album extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('my_array_helper'));
    $this->load->library(array('image_lib'));
    $this->load->model(array('club_model', 'file_model'));
  }

  /**
   * 사진첩
   *
   * @return view
   * @author bjchoi
   **/
  public function index()
  {
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $userData = $this->load->get_var('userData');
    $viewData['userIdx'] = $userData['idx'];
    $viewData['adminCheck'] = $userData['admin'];

    $viewData['keyword'] = $paging['keyword'] = html_escape($this->input->post('k'));
    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;
    $paging['perPage'] = $viewData['perPage'] = 30;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 사진첩 카운트
    $viewData['cntAlbum'] = $this->club_model->cntAlbum($clubIdx);

    // 사진첩
    $viewData['listAlbumMain'] = $this->club_model->listAlbum($clubIdx, $paging);

    foreach ($viewData['listAlbumMain'] as $key => $value) {
      $photo = $this->file_model->getFile('album', $value['idx'], NULL, 1);
      if (!empty($photo[0]['filename'])) {
        $viewData['listAlbumMain'][$key]['photo'] = PHOTO_URL . $photo[0]['filename'];
      } else {
        $viewData['listAlbumMain'][$key]['photo'] = '/public/images/noimage.png';
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
      $viewData['listAlbumMain'] = $this->load->view('club/album_list', $viewData, true);

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
  public function view()
  {
    $userData = $this->load->get_var('userData');
    $idx = html_escape($this->input->post('idx'));
    $result = array();
    $cnt = 0;

    // 사진첩
    $viewData['viewAlbum'] = $this->club_model->viewAlbum($idx);

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
  public function entry()
  {
    checkUserLogin();

    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $userData = $this->load->get_var('userData');
    $idx = html_escape($this->input->get('n'));

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 수정
    if (!empty($idx)) {
      $viewData['viewAlbum'] = $this->club_model->viewAlbum($idx);
      $viewData['photos'] = $this->file_model->getFile('album', $idx);
    } else {
      $viewData['photos'] = array();
    }

    // 페이지 타이틀
    $viewData['pageTitle'] = '사진 등록';

    $this->_viewPage('club/album_entry', $viewData);
  }

  /**
   * 사진 등록/수정
   *
   * @return view
   * @author bjchoi
   **/
  public function update()
  {
    $now = time();
    $pageName = 'album';
    $userData = $this->load->get_var('userData');
    $postData = $this->input->post();
    $idx = html_escape($postData['idx']);
    $photos = html_escape($postData['photos']);
    $redirectUrl = html_escape($postData['redirectUrl']);

    $updateValues = array(
      'club_idx' => html_escape($postData['clubIdx']),
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

    redirect($redirectUrl);
  }

  /**
   * 사진첩 삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function delete()
  {
    $now = time();
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
        // 사진 사이즈 줄이기 (가로 사이즈가 2000보다 클 경우)
        $maxSize = 2000;
        $size = getImageSize(UPLOAD_PATH . $filename);
        if ($size[1] >= $maxSize) {
          $this->image_lib->clear();
          $config['image_library'] = 'gd2';
          $config['source_image'] = UPLOAD_PATH . $filename;
          $config['maintain_ratio'] = TRUE;
          $config['width'] = $maxSize;
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
    $viewData['uri'] = 'album';

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
