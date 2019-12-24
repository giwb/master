<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 첫 페이지 클래스
class Club extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('cookie', 'security', 'url', 'my_array_helper'));
    $this->load->library('image_lib');
    $this->load->model(array('admin_model', 'area_model', 'club_model', 'file_model', 'notice_model', 'member_model', 'reserve_model', 'story_model'));
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

    // 클럽 스토리
    //$viewData['listStory'] = $this->story_model->listStory($clubIdx, 5);

/*
    // 클럽 스토리 좋아요 확인 (로그인시에만)
    if (!empty($userData['idx'])) {
      $listStoryReaction = $this->story_model->listStoryReaction($clubIdx, $userData['idx']);

      foreach ($viewData['listStory'] as $key => $value) {
        foreach ($listStoryReaction as $reaction) {
          if ($value['idx'] == $reaction['story_idx']) {
            $viewData['listStory'][$key]['like'] = $reaction['created_by'];
          }
        }
      }
    }
*/
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

    $this->_viewPage('club/auth', $viewData);
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

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 사진첩
    $viewData['listAlbum'] = $this->club_model->listAlbum($clubIdx);

    foreach ($viewData['listAlbum'] as $key => $value) {
      $photo = $this->file_model->getFile('album', $value['idx'], NULL, 1);
      if (!empty($photo[0]['filename'])) {
        $viewData['listAlbum'][$key]['photo'] = base_url() . PHOTO_URL . $photo[0]['filename'];
      } else {
        $viewData['listAlbum'][$key]['photo'] = base_url() . 'public/images/noimage.png';
      }
    }

    $this->_viewPage('club/album', $viewData);
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
        // 사진 사이즈 줄이기 (가로가 사이즈가 1024보다 클 경우)
        $maxSize = 800;
        $size = getImageSize(UPLOAD_PATH . $filename);
        if ($size[0] >= $maxSize) {
          $this->image_lib->clear();
          $config['image_library'] = 'gd2';
          $config['source_image'] = UPLOAD_PATH . $filename;
          //$config['new_image'] = UPLOAD_PATH . 'thumb_' . $filename;
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

    // 방문자 기록
    setVisitor();

    $this->load->view('header', $viewData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('footer', $viewData);
  }
}
?>
