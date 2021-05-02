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
    $this->load->model(array('admin_model', 'area_model', 'club_model', 'desk_model', 'file_model', 'notice_model', 'member_model', 'reaction_model', 'reserve_model', 'shop_model', 'story_model'));
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
        $url = html_escape($arrUrl[1]);
        $result = $this->club_model->getUrl($url);
        $clubIdx = $result['idx'];
      }
    }

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 대표 사진
    $viewData['arrTopImage'] = unserialize($viewData['view']['topimage']);

    if (empty($viewData['view'])) {
      redirect(BASE_URL);
      exit;
    }

    // 다음 산행
    $viewData['listNotice'] = $this->reserve_model->listNotice($clubIdx, array(STATUS_ABLE, STATUS_CONFIRM), 'asc');

    foreach ($viewData['listNotice'] as $key1 => $value) {
      // 댓글수
      $cntReply = $this->story_model->cntStoryReply($value['idx'], REPLY_TYPE_NOTICE);
      $viewData['listNotice'][$key1]['reply_cnt'] = $cntReply['cnt'];

      // 지역
      $viewData['area_sido'] = $this->area_model->listSido();
      if (!empty($value['area_sido'])) {
        $area_sido = unserialize($value['area_sido']);
        $area_gugun = unserialize($value['area_gugun']);

        foreach ($area_sido as $key2 => $value2) {
          $sido = $this->area_model->getName($value2);
          $gugun = $this->area_model->getName($area_gugun[$key2]);
          $viewData['listNotice'][$key1]['sido'][$key2] = $sido['name'];
          $viewData['listNotice'][$key1]['gugun'][$key2] = $gugun['name'];
        }
      }

      if (!empty($value['photo'])) {
        $viewData['listNotice'][$key1]['photo'] = PHOTO_URL . 'thumb_' . $value['photo'];
      } else {
        $viewData['listNotice'][$key1]['photo'] = '/public/images/noimage.png';
      }
    }

    // 추천 사진
    $paging['perPage'] = 6; $paging['nowPage'] = 0;
    $viewData['listBestPhoto'] = $this->club_model->listBestPhoto($viewData['view']['idx'], $paging);

    foreach ($viewData['listBestPhoto'] as $key => $value) {
      // 좋아요 개수
      $search = array(
        'club_idx'      => $viewData['view']['idx'],
        'target_idx'    => $value['idx'],
        'service_type'  => SERVICE_TYPE_ALBUM,
        'reaction_type' => REACTION_TYPE_LIKED
      );
      $viewData['listBestPhoto'][$key]['liked'] = $this->reaction_model->cntReaction($search);
    }

    // 최신 사진첩
    $paging['perPage'] = 6; $paging['nowPage'] = 0;
    $viewData['listAlbum'] = $this->club_model->listAlbum($viewData['view']['idx'], $paging);

    foreach ($viewData['listAlbum'] as $key => $value) {
      $photo = $this->file_model->getFile('album', $value['idx'], NULL, 1);
      if (!empty($photo[0]['filename'])) {
        $viewData['listAlbum'][$key]['photo'] = ALBUM_URL . 'thumb_'. $photo[0]['filename'];
      } else {
        $viewData['listAlbum'][$key]['photo'] = '/public/images/noimage.png';
      }
    }

    // 여행 소식
    $paging['perPage'] = 1; $paging['nowPage'] = 0;
    $search['clubIdx'] = $clubIdx;
    $search['code'] = 'news';
    $viewData['viewNews'] = $this->desk_model->listMainArticle($search, $paging);

    if (empty($viewData['viewNews'][0])) {
      $search['clubIdx'] = 0;
      $viewData['viewNews'] = $this->desk_model->listMainArticle($search, $paging);
    }

    // 조회수
    $search = array(
      'target_idx' => $viewData['viewNews'][0]['idx'],
      'service_type' => SERVICE_TYPE_ARTICLE,
      'reaction_type' => REACTION_TYPE_REFER
    );
    $cntRefer = $this->reaction_model->cntReaction($search);
    $viewData['viewNews'][0]['cntRefer'] = $cntRefer['cnt'];

    // 좋아요
    $search['reaction_type'] = REACTION_TYPE_LIKED;
    $cntLiked = $this->reaction_model->cntReaction($search);
    $viewData['viewNews'][0]['cntLiked'] = $cntLiked['cnt'];

    // 댓글
    $cntReply = $this->desk_model->cntReply($viewData['viewNews'][0]['idx']);
    $viewData['viewNews'][0]['cntReply'] = $cntReply['cnt'];

    // 여행 후기
    $search['clubIdx'] = $clubIdx;
    $search['code'] = 'review';
    $viewData['viewLogs'] = $this->desk_model->listMainArticle($search, $paging);

    if (empty($viewData['viewLogs'][0])) {
      $search['clubIdx'] = 0;
      $viewData['viewLogs'] = $this->desk_model->listMainArticle($search, $paging);
    }

    // 조회수
    $search = array(
      'target_idx' => $viewData['viewLogs'][0]['idx'],
      'service_type' => SERVICE_TYPE_ARTICLE,
      'reaction_type' => REACTION_TYPE_REFER
    );
    $cntRefer = $this->reaction_model->cntReaction($search);
    $viewData['viewLogs'][0]['cntRefer'] = $cntRefer['cnt'];

    // 좋아요
    $search['reaction_type'] = REACTION_TYPE_LIKED;
    $cntLiked = $this->reaction_model->cntReaction($search);
    $viewData['viewLogs'][0]['cntLiked'] = $cntLiked['cnt'];

    // 댓글
    $cntReply = $this->desk_model->cntReply($viewData['viewLogs'][0]['idx']);
    $viewData['viewLogs'][0]['cntReply'] = $cntReply['cnt'];

    // 백산백소 랭킹
    $rank = 0; $buf = 0;
    $viewData['auth'] = $this->club_model->listAuth(5);

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

    $this->_viewPage('club/index', $viewData);
  }

  /**
   * 개별 기사 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function article($idx=NULL)
  {
    $idx = html_escape($idx);
    $ipaddr = $_SERVER['REMOTE_ADDR'];
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');
    $userData = $this->load->get_var('userData');
    if (empty($userData['idx'])) $userData['idx'] = NULL;

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($viewData['clubIdx']);

    if (is_null($idx)) {
      $viewData['viewArticle']['title'] = '';
      $viewData['viewArticle']['content'] = '<div style="pt-5 pb-5">관련 기사가 없습니다.</div>';
    } else {
      $viewData['viewArticle'] = $this->desk_model->viewArticle($idx);

      // 분류명
      $viewData['category'] = $this->desk_model->viewArticleCategory($viewData['viewArticle']['category']);
      $viewData['categoryParent'] = $this->desk_model->viewArticleParentCategory($viewData['category']['parent']);

      // 조회수 올리기
      $insertValues = array(
        'club_idx' => $viewData['clubIdx'],
        'target_idx' => $idx,
        'service_type' => SERVICE_TYPE_ARTICLE,
        'reaction_type' => REACTION_TYPE_REFER,
        'ip_address' => $ipaddr,
        'created_by' => !empty($userData['idx']) ? $userData['idx'] : NULL,
        'created_at' => time(),
      );
      $this->reaction_model->insertReaction($insertValues);

      // 조회수
      $search = array(
        'club_idx' => $viewData['clubIdx'],
        'target_idx' => $idx,
        'service_type' => SERVICE_TYPE_ARTICLE,
        'reaction_type' => REACTION_TYPE_REFER
      );
      $viewData['refer'] = $this->reaction_model->cntReaction($search);

      // 좋아요
      $search['reaction_type'] = REACTION_TYPE_LIKED;
      $viewData['liked'] = $this->reaction_model->cntReaction($search);

      // 좋아요 했는지 확인
      $search['ip_address'] = $ipaddr;
      $search['created_by'] = $userData['idx'];
      $viewData['checkLiked'] = $this->reaction_model->viewReaction($search);

      // 댓글
      $viewData['cntReply'] = $this->desk_model->cntReply($idx);
      $viewData['listReply'] = $this->desk_model->listReply($idx);

      foreach ($viewData['listReply'] as $key => $value) {
        $thread = $this->desk_model->listReply($idx, $value['idx']);
        $viewData['listReply'][$key]['listReplyThread'] = $thread;
      }
    }

    $this->_viewPage('article', $viewData);
  }

  /**
   * 기사 작성/수정 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function article_post($idx=NULL)
  {
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $viewData['userData'] = $this->session->userData;
    $viewData['idx'] = html_escape($idx);

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    if (!empty($idx)) {
      $viewData['viewArticle'] = $this->desk_model->viewArticle($viewData['idx']);

      if ($viewData['viewArticle']['created_by'] != $viewData['userData']['idx']) {
        $viewData['viewArticle'] = array();
      }
    } else {
      $code = html_escape($this->input->get('code'));
      if (!empty($code)) {
        $viewData['viewArticle']['category'] = $code;
      }
    }

    $this->_viewPage('club/post', $viewData);
  }

  /**
   * 기사 등록
   *
   * @return json
   * @author bjchoi
   **/
  public function article_update()
  {
    $now = time();
    $userData = $this->session->userData;
    $inputData = $this->input->post();
    $clubIdx = html_escape($inputData['club_idx']);
    $idx = !empty($inputData['idx']) ? html_escape($inputData['idx']) : NULL;
    $category = html_escape($inputData['category']);
    $content = html_escape($inputData['content']);

    if (empty($userData['idx'])) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_login'));
    } else {
      if (!empty($idx)) {
        $updateValues = array(
          'category'    => $category,
          'title'       => html_escape($inputData['title']),
          'content'     => $content,
          'updated_by'  => html_escape($userData['idx']),
          'updated_at'  => $now
        );
        $this->desk_model->update(DB_ARTICLE, $updateValues, $idx);
      } else {
        $insertValues = array(
          'club_idx'    => html_escape($clubIdx),
          'category'    => $category,
          'title'       => html_escape($inputData['title']),
          'content'     => $content,
          'viewing_at'  => $now,
          'created_by'  => html_escape($userData['idx']),
          'created_at'  => $now
        );
        $idx = $this->desk_model->insert(DB_ARTICLE, $insertValues);

        /* ----------------------------------------------------------------- */
        // 기사 작성 포인트 지급 시작
        /* ----------------------------------------------------------------- */
        // 현재는 경인웰빙만 기사 작성 포인트 지급 (내용이 1KBytes 이상일 경우에만 지급)
        if (!empty($clubIdx) && $clubIdx == 1 && strlen($content) > 1000) {
          if ($category == 'review') {
            $title = '후기 작성';
          } else {
            $title = '기사 작성';
          }
          // 기사 작성 포인트는 1000포인트
          $addPoint = 1000;
          $this->member_model->updatePoint($clubIdx, $userData['idx'], ($userData['point'] + $addPoint));
          setHistory($clubIdx, LOG_POINTUP, $idx, $userData['idx'], $userData['nickname'], $title, $now, $addPoint);
        }
        /* ----------------------------------------------------------------- */
        // 기사 작성 포인트 지급 끝
        /* ----------------------------------------------------------------- */
      }

      if (empty($idx)) {
        $result = array('error' => 1, 'message' => $this->lang->line('error_insert'));
      } else {
        $result = array('error' => 0, 'message' => $category);
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 기사 검색 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function search()
  {
    // 클럽 정보
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');
    $viewData['view'] = $this->club_model->viewClub($viewData['clubIdx']);
    $viewData['code'] = '';

    if (!empty($this->input->get('keyword'))) {
      $search['keyword'] = html_escape($this->input->get('keyword'));
      $viewData['type'] = $search['keyword'];
    }
    if (!empty($this->input->get('code'))) {
      $viewData['code'] = $search['code'] = html_escape($this->input->get('code'));
      $type = $this->desk_model->viewArticleCategory($search['code']);
      $viewData['type'] = $type['name'];
    }

    // 기사 검색
    $viewData['listArticle'] = $this->desk_model->listMainArticle($search);

    // 페이지 타이틀
    if ($viewData['code'] == 'review') {
      $viewData['pageTitle'] = '여행후기';
    } else {
      $viewData['pageTitle'] = '여행소식';
    }

    $this->_viewPage('search', $viewData);
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
  public function about($idx=NULL)
  {
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $userData = $this->load->get_var('userData');

    if (is_null($idx)) {
      redirect('https://giwb.kr');
      exit;
    } else {
      $viewData['pageIdx'] = html_escape($idx);
    }

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 클럽 메뉴
    $viewData['viewAbout'] = $this->club_model->viewAbout($clubIdx, $viewData['pageIdx']);

    // 페이지 타이틀
    $viewData['pageTitle'] = $viewData['viewAbout']['title'];

    $this->_viewPage('club/about', $viewData);
  }

  /**
   * 각 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function page()
  {
    $viewData['type'] = !empty($this->input->get('type')) ? html_escape($this->input->get('type')) : '';

    // 클럽 정보
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    // 페이지 타이틀
    if ($viewData['type'] == 'agreement') {
      $viewData['pageTitle'] = "이용약관";
    } elseif ($viewData['type'] == 'personal') {
      $viewData['pageTitle'] = "개인정보 취급방침";
    } elseif ($viewData['type'] == 'mountain') {
      $viewData['pageTitle'] = "경인웰빙 100대명산";
    } else {
      $viewData['pageTitle'] = "경인웰빙 100대명소";
    }

    $this->_viewPage('club/page', $viewData);
  }

  /**
   * 임시 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function howto()
  {
    redirect('https://giwb.kr');
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
   * 여행기 - 동영상 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function video()
  {
    // 클럽 정보
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');
    $viewData['view'] = $this->club_model->viewClub($viewData['clubIdx']);

    // 동영상 검색
    $viewData['listVideo'] = $this->club_model->listVideo();

    foreach ($viewData['listVideo'] as $key => $value) {
      if (!empty($value['video_link'])) {
        $buf = explode('v=', $value['video_link']);
        $viewData['listVideo'][$key]['video_link'] = '<iframe class="area-video" src="https://www.youtube.com/embed/' . $buf[1] . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
      } else {
        $viewData['listVideo'][$key]['video_link'] = '<div class="area-video"><img class="w-100" src="/public/images/noimage.png"></div>';
      }
    }

    // 페이지 타이틀
    $viewData['pageTitle'] = '동영상 여행기';

    $this->_viewPage('club/video', $viewData);
  }

  /**
   * 여행기 - 동영상 등록/수정 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function video_post($idx=NULL)
  {
    // 클럽 정보
    $viewData['clubIdx'] = get_cookie('COOKIE_CLUBIDX');
    $viewData['view'] = $this->club_model->viewClub($viewData['clubIdx']);

    // 동영상 검색
    if (!empty($idx)) {
      $viewData['viewVideo'] = $this->club_model->viewVideo(html_escape($idx));
    }

    $this->_viewPage('club/video_post', $viewData);
  }

  /**
   * 여행기 - 동영상 등록/수정
   *
   * @return json
   * @author bjchoi
   **/
  public function video_update()
  {
    $now = time();
    $inputData = $this->input->post();
    $userIdx = $this->session->userData['idx'];
    $clubIdx = html_escape($inputData['club_idx']);
    $idx = !empty($inputData['idx']) ? html_escape($inputData['idx']) : NULL;

    if (empty($userIdx)) {
      $result = array('error' => 1, 'message' => $this->lang->line('error_login'));
    } else {
      if (!empty($idx)) {
        $updateValues = array(
          'subject'     => html_escape($inputData['subject']),
          'video_link'  => html_escape($inputData['video_link']),
          //'content'     => html_escape($inputData['content']),
          'updated_by'  => html_escape($userIdx),
          'updated_at'  => $now
        );
        $this->desk_model->update(DB_VIDEOS, $updateValues, $idx);
      } else {
        $insertValues = array(
          'club_idx'    => html_escape($clubIdx),
          'subject'     => html_escape($inputData['subject']),
          'video_link'  => html_escape($inputData['video_link']),
          //'content'     => html_escape($inputData['content']),
          'created_by'  => html_escape($userIdx),
          'created_at'  => $now
        );
        $idx = $this->desk_model->insert(DB_VIDEOS, $insertValues);
      }

      if (empty($idx)) {
        $result = array('error' => 1, 'message' => $this->lang->line('error_insert'));
      } else {
        $result = array('error' => 0, 'message' => '');
      }
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 회원랭킹
   *
   * @return view
   * @author bjchoi
   **/
  public function status()
  {
    $clubIdx = get_cookie('COOKIE_CLUBIDX');
    $viewData['userData'] = $this->session->userData;
    $viewData['type'] = !empty($this->input->get('type')) ? html_escape($this->input->get('type')) : 0;

    // 클럽 정보
    $viewData['view'] = $this->club_model->viewClub($clubIdx);

    switch ($viewData['type']) {
      case '1':
        $title = ' - 포인트 적립';

        $paging['perPage'] = 100; $paging['nowPage'] = 0;
        $search = array('clubIdx' => $clubIdx, 'action' => LOG_POINTUP);
        $viewData['status'] = $this->admin_model->listHistory($paging, $search);

        foreach ($viewData['status'] as $key => $value) {
          if (file_exists(AVATAR_PATH . $value['user_idx'])) {
            $viewData['status'][$key]['avatar'] = AVATAR_URL . $value['user_idx'];
          } else {
            $viewData['status'][$key]['avatar'] = '/public/images/user.png';
          }
        }
        break;
      case '2':
        $title = ' - 산행 참여';
        $viewData['status'] = $this->club_model->rankingRescount($clubIdx, 100);
        break;
      case '3':
        $title = ' - 백산백소 인증';
        $viewData['status'] = $this->club_model->listAuth(100);
        break;
      case '4':
        if (!empty($viewData['userData']['admin']) && $viewData['userData']['admin'] == 1) {
          $title = ' - 홈페이지 방문';
          $viewData['status'] = $this->club_model->rankingVisit($clubIdx, 100);
        } else {
          $title = '';
          $viewData['status'] = array();
        }
        break;
      default:
        $paging['perPage'] = 10; $paging['nowPage'] = 0;
        $search = array('clubIdx' => $clubIdx, 'action' => LOG_POINTUP);
        $viewData['statusPoint'] = $this->admin_model->listHistory($paging, $search);

        foreach ($viewData['statusPoint'] as $key => $value) {
          if (file_exists(AVATAR_PATH . $value['user_idx'])) {
            $viewData['statusPoint'][$key]['avatar'] = AVATAR_URL . $value['user_idx'];
          } else {
            $viewData['statusPoint'][$key]['avatar'] = '/public/images/user.png';
          }
        }

        $viewData['statusRescount'] = $this->club_model->rankingRescount($clubIdx, $paging['perPage']);
        $viewData['statusAuth'] = $this->club_model->listAuth($paging['perPage']);
        $viewData['statusVisit'] = $this->club_model->rankingVisit($clubIdx, $paging['perPage']);
        $title = ' - 전체보기';
    }

    $viewData['pageTitle'] = '활동내역' . $title;

    $this->_viewPage('club/status', $viewData);
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

    // 클럽 메뉴
    $viewData['listAbout'] = $this->club_model->listAbout($viewData['view']['idx']);

    // 등록된 산행 목록
    $viewData['listNoticeFooter'] = $viewData['listNoticeCalendar'] = $this->reserve_model->listNotice($viewData['view']['idx'], array(STATUS_ABLE, STATUS_CONFIRM));

    // 회원 활동내역 (경인웰빙 전용)
    if ($viewData['view']['idx'] == 1) {
      $paging['perPage'] = 10; $paging['nowPage'] = 0;
      $search = array('clubIdx' => $viewData['view']['idx'], 'action' => LOG_POINTUP);
      $viewData['listHistory'] = $this->admin_model->listHistory($paging, $search);

      foreach ($viewData['listHistory'] as $key => $value) {
        if (file_exists(AVATAR_PATH . $value['user_idx'])) {
          $viewData['listHistory'][$key]['avatar'] = AVATAR_URL . $value['user_idx'];
        } else {
          $viewData['listHistory'][$key]['avatar'] = '/public/images/user.png';
        }
      }
    }

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

    // 안부 인사
    $page = 1;
    $paging['perPage'] = 10;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];
    $viewData['listStory'] = $this->story_model->listStory($viewData['view']['idx'], $paging);

    foreach ($viewData['listStory'] as $key => $value) {
      if (file_exists(AVATAR_PATH . $value['user_idx'])) {
        $viewData['listStory'][$key]['avatar'] = AVATAR_URL . $value['user_idx'];
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
