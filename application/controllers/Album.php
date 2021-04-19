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
    $this->load->model(array('club_model', 'file_model', 'reaction_model', 'story_model'));
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
    $paging['perPage'] = $viewData['perPage'] = 10;
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
          $size = getImageSize(ALBUM_PATH . $photo['filename']);
          $size_thumb = getImageSize(ALBUM_PATH . 'thumb_' . $photo['filename']);
          $viewData['photos'][$key]['file_idx'][] = $photo['idx'];
          $viewData['photos'][$key]['pickup'][] = $photo['pickup'];
          $viewData['photos'][$key]['filename'][] = ALBUM_URL . 'thumb_' . $photo['filename'];
          $viewData['photos'][$key]['source'][] = $photo['filename'];
          $viewData['photos'][$key]['width'][] = $size[0];
          $viewData['photos'][$key]['height'][] = $size[1];
          $viewData['photos'][$key]['thumb_width'][] = $size_thumb[0];
          $viewData['photos'][$key]['thumb_height'][] = $size_thumb[1];
        } else {
          $viewData['photos'][$key]['filename'][] = '/public/images/noimage.png';
        }
        $viewData['photos'][$key]['idx'] = $value['idx'];
        $viewData['photos'][$key]['nickname'] = $value['nickname'];
        $viewData['photos'][$key]['subject'] = $value['subject'];
        $viewData['photos'][$key]['notice_idx'] = $value['notice_idx'];
        $viewData['photos'][$key]['notice_subject'] = $value['notice_subject'];
        $viewData['photos'][$key]['notice_startdate'] = $value['notice_startdate'];
        $viewData['photos'][$key]['content'] = $value['content'];
        $viewData['photos'][$key]['created_by'] = $value['created_by'];
        $viewData['photos'][$key]['created_at'] = $value['created_at'];
      }
    }

    if (!empty($viewData['photos'])) {
      foreach ($viewData['photos'] as $value) {
        $viewData['album'][$value['notice_idx']][] = $value;
        if (!empty($value['notice_subject'])) {
          $viewData['album'][$value['notice_idx']]['title'] = date('Y년 m월 d일', strtotime($value['notice_startdate'])) . ' - ' . $value['notice_subject'];
        } else {
          $viewData['album'][$value['notice_idx']]['title'] = '기타 사진들';
        }
      }
    }

    // 페이지 타이틀
    $viewData['pageTitle'] = '사진첩';

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      if (!empty($viewData['photos'])) {
        $result['page'] = $page;
        $result['html'] = $this->load->view('club/album_list', $viewData, true);
      } else {
        $result['html'] = '';
      }
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
    $idx = html_escape($this->input->post('idx'));
    $clubIdx = get_cookie('COOKIE_CLUBIDX');

    $viewAlbum = $this->club_model->viewAlbum($idx);

    $photos = $this->file_model->getFile('album', $idx);

    foreach ($photos as $key => $photo) {
      if (!empty($photo['filename'])) {
        if (empty($photo['width'])) {
          $size = getImageSize(ALBUM_PATH . $photo['filename']);
          $photo['width'] = $size[0];
          $photo['height'] = $size[1];
        }
        $result[$key]['src'] = ALBUM_URL . $photo['filename'];
        $result[$key]['width'] = $photo['width'];
        $result[$key]['height'] = $photo['height'];
      } else {
        $viewData['photos'][$key]['filename'][] = '/public/images/noimage.png';
      }
      $result[$key]['title'] = $viewAlbum['subject'];
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
  public function insert()
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

        if (move_uploaded_file($value, ALBUM_PATH . $filename)) {
          $size = getImageSize(ALBUM_PATH . $filename);
          $fileValues = array(
            'page' => $pageName,
            'page_idx' => $idx,
            'filename' => $filename,
            'width' => $size[0],
            'height' => $size[1],
            'created_at' => $now
          );
          $this->file_model->insertFile($fileValues);

          // 썸네일 만들기
          $this->image_lib->clear();
          $config['image_library'] = 'gd2';
          $config['source_image'] = ALBUM_PATH . $filename;
          $config['new_image'] = ALBUM_PATH . 'thumb_' . $filename;
          $config['create_thumb'] = TRUE;
          $config['maintain_ratio'] = TRUE;
          $config['thumb_marker'] = '';
          $config['width'] = 350;
          $this->image_lib->initialize($config);
          $this->image_lib->resize();
        }
      }
    }

    $result = array('error' => 0, 'message' => '');
    $this->output->set_output(json_encode($result));
  }

  /**
   * 사진 삭제
   *
   * @return view
   * @author bjchoi
   **/
  public function delete()
  {
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $userData = $this->load->get_var('userData');
    $viewData['userIdx'] = !empty($userData['idx']) ? $userData['idx'] : '';
    $search = array();

    $viewData['keyword'] = $paging['keyword'] = html_escape($this->input->post('k'));
    $page = html_escape($this->input->post('p'));
    if (empty($page)) $page = 1; else $page++;
    $paging['perPage'] = $viewData['perPage'] = 10;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 사진첩 목록 (관리자가 아니라면 회원이 올린 글만)
    if (empty($userData['admin'])) {
      $search['created_by'] = $userData['idx'];
    }
    // 사진첩 카운트
    $viewData['cntAlbum'] = $this->club_model->cntAlbum($clubIdx, $search);

    // 사진첩 목록
    $viewData['listAlbumMain'] = $this->club_model->listAlbum($clubIdx, $paging, $search);

    foreach ($viewData['listAlbumMain'] as $key => $value) {
      $photos = $this->file_model->getFile('album', $value['idx']);

      foreach ($photos as $i => $photo) {
        if (!empty($photo['filename'])) {
          $size = getImageSize(ALBUM_PATH . $photo['filename']);
          $size_thumb = getImageSize(ALBUM_PATH . 'thumb_' . $photo['filename']);
          $viewData['photos'][$key]['filename'][] = ALBUM_URL . 'thumb_' . $photo['filename'];
          $viewData['photos'][$key]['source'][] = $photo['filename'];
          $viewData['photos'][$key]['width'][] = $size[0];
          $viewData['photos'][$key]['height'][] = $size[1];
          $viewData['photos'][$key]['thumb_width'][] = $size_thumb[0];
          $viewData['photos'][$key]['thumb_height'][] = $size_thumb[1];
        } else {
          $viewData['photos'][$key]['filename'][] = '/public/images/noimage.png';
        }
        $viewData['photos'][$key]['idx'] = $value['idx'];
        $viewData['photos'][$key]['nickname'] = $value['nickname'];
        $viewData['photos'][$key]['subject'] = $value['subject'];
        $viewData['photos'][$key]['notice_idx'] = $value['notice_idx'];
        $viewData['photos'][$key]['notice_subject'] = $value['notice_subject'];
        $viewData['photos'][$key]['notice_startdate'] = $value['notice_startdate'];
        $viewData['photos'][$key]['content'] = $value['content'];
        $viewData['photos'][$key]['created_by'] = $value['created_by'];
        $viewData['photos'][$key]['created_at'] = $value['created_at'];
      }
    }

    if (!empty($viewData['photos'])) {
      foreach ($viewData['photos'] as $value) {
        $viewData['album'][$value['notice_idx']][] = $value;
        if (!empty($value['notice_subject'])) {
          $viewData['album'][$value['notice_idx']]['title'] = date('Y년 m월 d일', strtotime($value['notice_startdate'])) . ' - ' . $value['notice_subject'];
        } else {
          $viewData['album'][$value['notice_idx']]['title'] = '기타 사진들';
        }
      }
    }

    // 페이지 타이틀
    $viewData['pageTitle'] = '사진 삭제';

    if ($page >= 2) {
      // 2페이지 이상일 경우에는 Json으로 전송
      $result['page'] = $page;
      $result['html'] = $this->load->view('club/album_delete_list', $viewData, true);
      $this->output->set_output(json_encode($result));
    } else {
      // 아이템 목록 템플릿
      $viewData['listAlbumMain'] = $this->load->view('club/album_delete_list', $viewData, true);

      // 1페이지에는 View 페이지로 전송
      $this->_viewPage('club/album_delete', $viewData);
    }
  }

  /**
   * 사진 삭제 처리
   *
   * @return json
   * @author bjchoi
   **/
  public function delete_process()
  {
    $now = time();
    $userData = $this->load->get_var('userData');
    $inputData['albumIdx'] = explode(',', html_escape($this->input->post('albumIdx')));
    $inputData['sourceFile'] = explode(',', html_escape($this->input->post('sourceFile')));

    foreach ($inputData['sourceFile'] as $key => $value) {
      // 파일 삭제
      $this->file_model->deleteFile($value);
      if (file_exists(ALBUM_PATH . $value)) {
        unlink(ALBUM_PATH . $value);
      }

      // 해당 앨범에 사진이 하나도 없으면 앨범 데이터 삭제
      $chk = $this->file_model->getFile('album', $inputData['albumIdx'][$key]);
      if (empty($chk[0]['filename'])) {
        $updateValues['deleted_by'] = $userData['idx'];
        $updateValues['deleted_at'] = $now;
        $this->club_model->updateAlbum($updateValues, $inputData['albumIdx'][$key]);
      }
    }

    $result = array('error' => 0, 'message' => '');

    $this->output->set_output(json_encode($result));
  }

  /**
   * 사진 추천 처리
   *
   * @return json
   * @author bjchoi
   **/
  public function pickup()
  {
    $fileIdx = !empty($this->input->post('file_idx')) ? html_escape($this->input->post('file_idx')) : NULL;

    if (!empty($fileIdx)) {
      $viewFile = $this->file_model->viewFile(NULL, $fileIdx);
      if (!empty($viewFile['pickup']) && $viewFile['pickup'] == 'Y') {
        // 이미 추천이 되어 있으면 추천 해제
        $updateValues['pickup'] = NULL;
      } else {
        // 아무것도 없으면 추천
        $updateValues['pickup'] = 'Y';
      }
      $rtn = $this->file_model->updateFile($updateValues, $fileIdx);
    }

    if (empty($rtn)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
    } else {
      $result = array('error' => 0, 'message' => $updateValues['pickup']);
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 추천 사진
   *
   * @return view
   * @author bjchoi
   **/
  public function best($idx=NULL)
  {
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $userData = $this->load->get_var('userData');

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    if (!empty($idx)) {
      // 추천 사진 보기
      $viewData['viewBestPhoto'] = $this->club_model->viewBestPhoto($clubIdx, $idx);

      // 추천 사진 조회수 올리기
      $updateValues['refer'] = $viewData['viewBestPhoto']['refer'] = $viewData['viewBestPhoto']['refer'] + 1;
      $this->file_model->updateFile($updateValues, $idx);

      // 좋아요 개수
      $search = array(
        'club_idx'      => $clubIdx,
        'target_idx'    => $idx,
        'service_type'  => SERVICE_TYPE_ALBUM,
        'reaction_type' => REACTION_TYPE_LIKED
      );
      $viewData['viewBestPhoto']['liked'] = $this->reaction_model->cntReaction($search);

      // 좋아요 했는지 확인
      $search['ip_address'] = $_SERVER['REMOTE_ADDR'];
      $search['created_by'] = !empty($viewData['userData']['idx']) ? $viewData['userData']['idx'] : NULL;
      $viewData['checkLiked'] = $this->reaction_model->viewReaction($search);

      // 댓글
      $viewData['cntReply'] = $this->desk_model->cntReply($idx);
      $viewData['listReply'] = $this->desk_model->listReply($idx);

      foreach ($viewData['listReply'] as $key => $value) {
        $thread = $this->desk_model->listReply($idx, $value['idx']);
        $viewData['listReply'][$key]['listReplyThread'] = $thread;
      }

      // 페이지 타이틀
      $viewData['pageTitle'] = '추천 사진 보기';
      $viewPage = 'club/album_best_detail';
    } else {
      // 추천 사진
      $viewData['listBestPhoto'] = $this->club_model->listBestPhoto($clubIdx);

      foreach ($viewData['listBestPhoto'] as $key => $value) {
        // 좋아요 개수
        $search = array(
          'club_idx'      => $clubIdx,
          'target_idx'    => $value['idx'],
          'service_type'  => SERVICE_TYPE_ALBUM,
          'reaction_type' => REACTION_TYPE_LIKED
        );
        $viewData['listBestPhoto'][$key]['liked'] = $this->reaction_model->cntReaction($search);
      }

      // 페이지 타이틀
      $viewData['pageTitle'] = '추천 사진';
      $viewPage = 'club/album_best';
    }

    $this->_viewPage($viewPage, $viewData);
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
    $result = array('error' => 1, 'message' => $this->lang->line('error_all'));

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
   * 썸네일 만들기
   *
   * @return json
   * @author bjchoi
   **/
  public function thumbnail()
  {
    $listAlbum = $this->club_model->listAlbum(1, 50);

    foreach ($listAlbum as $value) {
      if (!empty($value['idx'])) {
        $photos = $this->file_model->getFile('album', $value['idx']);

        foreach ($photos as $i => $photo) {
          if (!empty($photo['filename']) && file_exists(PHOTO_PATH . $photo['filename'])) {
            copy(PHOTO_PATH . $photo['filename'], ALBUM_PATH . $photo['filename']);
            unlink(PHOTO_PATH . $photo['filename']);
            unlink(PHOTO_PATH . 'thumb_' . $photo['filename']);

            // 썸네일 만들기
            $this->image_lib->clear();
            $config['image_library'] = 'gd2';
            $config['source_image'] = ALBUM_PATH . $photo['filename'];
            $config['new_image'] = ALBUM_PATH . 'thumb_' . $photo['filename'];
            $config['create_thumb'] = TRUE;
            $config['maintain_ratio'] = TRUE;
            $config['thumb_marker'] = '';
            $config['width'] = 350;
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
          }
        }
      }
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
    $viewData['uri'] = 'album';

    // 회원 정보
    $viewData['userData'] = $this->load->get_var('userData');
    $viewData['userLevel'] = $this->load->get_var('userLevel');

    // 클럽 메뉴
    $viewData['listAbout'] = $this->club_model->listAbout($viewData['view']['idx']);

    // 등록된 산행 목록
    $viewData['listNoticeFooter'] = $viewData['listNoticeCalendar'] = $this->reserve_model->listNotice($viewData['view']['idx'], array(STATUS_ABLE, STATUS_CONFIRM));

    /* 캘린더가 복잡해지므로 2021년 4월 19일 삭제
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
    */

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
