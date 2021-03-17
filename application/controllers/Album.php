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
    $this->load->model(array('club_model', 'file_model', 'story_model'));
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
    $viewData['userIdx'] = !empty($userData['idx']) ? $userData['idx'] : '';
    $viewData['adminCheck'] = !empty($userData['admin']) ? $userData['admin'] : '';

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
      $photos = $this->file_model->getFile('album', $value['idx']);

      foreach ($photos as $i => $photo) {
        if (!empty($photo['filename'])) {
          $viewData['photos'][$key]['filename'][] = PHOTO_URL . 'thumb_' . $photo['filename'];
          $viewData['photos'][$key]['source'][] = $photo['filename'];
        } else {
          $viewData['photos'][$key]['filename'][] = '/public/images/noimage.png';
        }
        $viewData['photos'][$key]['idx'] = $value['idx'];
        $viewData['photos'][$key]['nickname'] = $value['nickname'];
        $viewData['photos'][$key]['subject'] = $value['subject'];
        $viewData['photos'][$key]['notice_subject'] = $value['notice_subject'];
        $viewData['photos'][$key]['content'] = $value['content'];
        $viewData['photos'][$key]['created_by'] = $value['created_by'];
        $viewData['photos'][$key]['created_at'] = $value['created_at'];
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
/*
    $userData = $this->load->get_var('userData');
    $idx = html_escape($this->input->post('idx'));
    $result = array();
    $cnt = 0;
*/
    $result = array();
    $filename = html_escape($this->input->post('source'));
    $photo = $this->file_model->viewFile($filename);

    // 사진첩
    $viewData['viewAlbum'] = $this->club_model->viewAlbum($photo['page_idx']);
    $size = getImageSize(PHOTO_PATH . $filename);
    $result[0]['width'] = $size[0];
    $result[0]['height'] = $size[1];
    $result[0]['src'] = PHOTO_URL . $filename;
    $result[0]['title'] = $viewData['viewAlbum']['subject'];

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

    // 다녀온 산행
    $viewData['listNotice'] = $this->reserve_model->listNotice($clubIdx, array(STATUS_CLOSED), 'desc');

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
    $userData = $this->load->get_var('userData');
    $postData = $this->input->post();
    $arrPhoto = $_FILES['files'];
    $pageName = 'album';

    $insertValues = array(
      'club_idx'    => html_escape($postData['clubIdx']),
      'notice_idx'  => html_escape($postData['noticeIdx']),
      'subject'     => html_escape($postData['subject']),
      'created_by'  => $userData['idx'],
      'created_at'  => $now,
    );

    $idx = $this->club_model->insertAlbum($insertValues);

    // 사진 처리
    foreach ($arrPhoto['tmp_name'] as $value) {
      if (!empty($value)) {
        $filename = $now . mt_rand(10000, 99999) . '.jpg';

        if (move_uploaded_file($value, PHOTO_PATH . $filename)) {
          $fileValues = array(
            'page' => $pageName,
            'page_idx' => $idx,
            'filename' => $filename,
            'created_at' => $now
          );
          $this->file_model->insertFile($fileValues);

          // 썸네일 만들기
          $this->image_lib->clear();
          $config['image_library'] = 'gd2';
          $config['source_image'] = PHOTO_PATH . $filename;
          $config['new_image'] = PHOTO_PATH . 'thumb_' . $filename;
          $config['create_thumb'] = TRUE;
          $config['maintain_ratio'] = TRUE;
          $config['thumb_marker'] = '';
          $config['width'] = 200;
          $this->image_lib->initialize($config);
          $this->image_lib->resize();
        }
      }
    }

    $result = array('error' => 0, 'message' => '');
    $this->output->set_output(json_encode($result));
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
    $maxSize = 2000;
    $result = array('error' => 1, 'message' => $this->lang->line('error_photo_upload'));

    foreach ($_FILES['files']['tmp_name'] as $tmp_name) {
      if (!empty($tmp_name)) {
        $filename = time() . mt_rand(10000, 99999) . '.jpg';

        if (move_uploaded_file($tmp_name, UPLOAD_PATH . $filename)) {
          // 사진 사이즈 줄이기 (가로 사이즈가 2000보다 클 경우)
          $size = getImageSize(UPLOAD_PATH . $filename);
          if ($size[0] >= $maxSize) {
            $this->image_lib->clear();
            $config['image_library'] = 'gd2';
            $config['source_image'] = UPLOAD_PATH . $filename;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = $maxSize;
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
          }

          $message[] = UPLOAD_URL . $filename;
          $message_filename[] = $filename;
        }
      }
    }    
    if (!empty($message)) {
      $result = array('error' => 0, 'message' => $message, 'filename' => $message_filename);
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

    // 클럽 메뉴
    $viewData['listAbout'] = $this->club_model->listAbout($viewData['view']['idx']);

    // 등록된 산행 목록
    $viewData['listNoticeCalendar'] = $this->reserve_model->listNotice($viewData['view']['idx']);

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
        $viewData['listStory'][$key]['avatar'] = PHOTO_URL . $value['user_idx'];
      } else {
        $viewData['listStory'][$key]['avatar'] = '/public/images/user.png';
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
