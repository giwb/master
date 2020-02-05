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
    $userData = $this->load->get_var('userData');
    if (empty($userData['idx'])) $userData['idx'] = NULL;

    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    if (empty($clubIdx)) {
      // 각 클럽 도메인별 이동
      $domain = $_SERVER['HTTP_HOST'];
      $result = $this->club_model->getDomain($domain);

      if (!empty($result)) {
        $clubIdx = $result['idx'];
      } elseif (!empty($_SERVER['REDIRECT_URL'])) {
        $arrUrl = explode('/', $_SERVER['REDIRECT_URL']);
        $domain = html_escape($arrUrl[1]);
        $result = $this->club_model->getDomain($domain);
        $clubIdx = $result['idx'];
      }
    }

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    if (empty($viewData['view'])) {
      redirect(BASE_URL);
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
    $paging['perPage'] = $viewData['perPage'] = 10;
    $paging['nowPage'] = (1 * $paging['perPage']) - $paging['perPage'];
    $viewData['maxStory'] = $this->story_model->cntStory($clubIdx);
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
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
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
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
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
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
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
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
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
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
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

    // 최신 댓글
    $paging['perPage'] = 5; $paging['nowPage'] = 0;
    $viewData['listReply'] = $this->admin_model->listReply($viewData['view']['idx'], $paging);

    foreach ($viewData['listReply'] as $key => $value) {
      if ($value['reply_type'] == REPLY_TYPE_STORY):  $viewData['listReply'][$key]['url'] = BASE_URL . '/story/view/?n=' . $value['story_idx']; endif;
      if ($value['reply_type'] == REPLY_TYPE_NOTICE): $viewData['listReply'][$key]['url'] = BASE_URL . '/admin/main_view_progress/' . $value['story_idx']; endif;
    }

    // 최신 사진첩
    $paging['perPage'] = 2; $paging['nowPage'] = 0;
    $viewData['listAlbum'] = $this->club_model->listAlbum($viewData['view']['idx'], $paging);

    foreach ($viewData['listAlbum'] as $key => $value) {
      $photo = $this->file_model->getFile('album', $value['idx'], NULL, 1);
      if (!empty($photo[0]['filename'])) {
        $viewData['listAlbum'][$key]['photo'] = PHOTO_URL . $photo[0]['filename'];
      } else {
        $viewData['listAlbum'][$key]['photo'] = '/public/images/noimage.png';
      }
    }

    // 회원수
    $viewData['view']['cntMember'] = $this->member_model->cntMember($viewData['view']['idx']);
    $viewData['view']['cntMemberToday'] = $this->member_model->cntMemberToday($viewData['view']['idx']);

    // 방문자수
    $viewData['view']['cntVisitor'] = $this->member_model->cntVisitor($viewData['view']['idx']);
    $viewData['view']['cntVisitorToday'] = $this->member_model->cntVisitorToday($viewData['view']['idx']);

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
