<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 첫 페이지 클래스
class Welcome extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('url', 'my_array_helper'));
    $this->load->model(array('area_model', 'club_model', 'desk_model', 'file_model', 'notice_model', 'story_model', 'reaction_model', 'reserve_model'));
  }

  /**
   * 메인 인덱스
   *
   * @return view
   * @author bjchoi
   **/
  public function index()
  {
    $paging['perPage'] = $viewData['perPage'] = 10;
    $paging['nowPage'] = (1 * $paging['perPage']) - $paging['perPage'];
    $viewData['maxArticle'] = $this->desk_model->cntArticle();

    // 메인 기사
    $search['main_status'] = 'Y';
    $viewData['listArticleMain'] = $this->desk_model->listMainArticle($search);

    // 최신 기사
    $viewData['listArticle'] = $this->desk_model->listMainArticle(NULL, $paging);
    foreach ($viewData['listArticle'] as $key => $value) {
      // 조회수
      $search = array(
        'target_idx' => $value['idx'],
        'service_type' => SERVICE_TYPE_ARTICLE,
        'reaction_type' => REACTION_TYPE_REFER
      );
      $cntRefer = $this->reaction_model->cntReaction($search);
      $viewData['listArticle'][$key]['cntRefer'] = $cntRefer['cnt'];

      // 좋아요
      $search['reaction_type'] = REACTION_TYPE_LIKED;
      $cntLiked = $this->reaction_model->cntReaction($search);
      $viewData['listArticle'][$key]['cntLiked'] = $cntLiked['cnt'];

      // 댓글
      $cntReply = $this->desk_model->cntReply($value['idx']);
      $viewData['listArticle'][$key]['cntReply'] = $cntReply['cnt'];
    }
    $viewData['listArticle'] = $this->load->view('/article_list', $viewData, true);

    $this->_viewPage('index', $viewData);
  }

  /**
   * 기사 목록
   *
   * @return view
   * @author bjchoi
   **/
  public function article_list()
  {
    $page = html_escape($this->input->post('p'));
    $result = '';

    $paging['perPage'] = 10;
    $paging['nowPage'] = ($page * $paging['perPage']) - $paging['perPage'];
    $viewData['listArticle'] = $this->desk_model->listMainArticle(NULL, $paging);

    if (!empty($viewData['listArticle'])) {
      $result = $this->load->view('/article_list', $viewData, true);
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

    $this->_viewPage('search', $viewData);
  }

  /**
   * 개별 기사 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function article($idx=NULL)
  {
    $viewData['userData'] = $this->load->get_var('userData');
    $idx = html_escape($idx);

    if (is_null($idx)) {
      $viewData['viewArticle']['title'] = '';
      $viewData['viewArticle']['content'] = '<div style="pt-5 pb-5">관련 기사가 없습니다.</div>';
    } else {
      $viewData['viewArticle'] = $this->desk_model->viewArticle($idx);

      // 분류명
      $viewData['category'] = $this->desk_model->viewArticleCategory($viewData['viewArticle']['category']);
      $viewData['categoryParent'] = $this->desk_model->viewArticleParentCategory($viewData['category']['parent']);

      // 조회수 올리기
      $ipaddr = $_SERVER['REMOTE_ADDR'];
      $insertValues = array(
        'target_idx' => $idx,
        'service_type' => SERVICE_TYPE_ARTICLE,
        'reaction_type' => REACTION_TYPE_REFER,
        'ip_address' => $_SERVER['REMOTE_ADDR'],
        'created_by' => !empty($viewData['userData']['idx']) ? $viewData['userData']['idx'] : NULL,
        'created_at' => time(),
      );
      $this->reaction_model->insertReaction($insertValues);

      // 조회수
      $search = array(
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
      $search['created_by'] = !empty($viewData['userData']['idx']) ? $viewData['userData']['idx'] : NULL;
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
   * 기사 삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function article_delete()
  {
    $userData = $this->load->get_var('userData');
    $idx = html_escape($this->input->post('idx'));

    if (!empty($idx)) {
      $updateValues['deleted_by'] = $userData['idx'];
      $updateValues['deleted_at'] = time();
      $this->desk_model->update(DB_ARTICLE, $updateValues, $idx);

      $result = array('error' => 0, 'message' => '');
    } else {
      $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 좋아요
   *
   * @return json
   * @author bjchoi
   **/
  public function liked()
  {
    $now          = time();
    $userData     = $this->load->get_var('userData');
    $userIdx      = !empty($userData['idx']) ? $userData['idx'] : NULL;
    $idx          = !empty($this->input->post('idx')) ? html_escape($this->input->post('idx')) : NULL;
    $clubIdx      = !empty($this->input->post('club_idx')) ? html_escape($this->input->post('club_idx')) : 0;
    $typeService  = !empty($this->input->post('type_service')) ? html_escape($this->input->post('type_service')) : SERVICE_TYPE_ARTICLE;
    $typeReaction = !empty($this->input->post('type_reaction')) ? html_escape($this->input->post('type_reaction')) : REACTION_TYPE_LIKED;
    $ipAddress    = $_SERVER['REMOTE_ADDR'];

    if (!empty($userData) && !empty($idx)) {
      $search = array(
        'club_idx'        => $clubIdx,
        'target_idx'      => $idx,
        'service_type'    => $typeService,
        'reaction_type'   => $typeReaction,
        'ip_address'      => $ipAddress,
        'created_by'      => $userIdx,
      );
      $check = $this->reaction_model->viewReaction($search);

      if (!empty($check)) {
        $this->reaction_model->deleteReaction($search);

        // 좋아요 개수
        $search = array(
          'club_idx'      => $clubIdx,
          'target_idx'    => $idx,
          'service_type'  => $typeService,
          'reaction_type' => $typeReaction,
        );
        $liked = $this->reaction_model->cntReaction($search);

        $result = array('error' => 0, 'message' => 0, 'liked' => $liked['cnt']);
      } else {
        // 좋아요 올리기
        $insertValues = array(
          'club_idx'      => $clubIdx,
          'target_idx'    => $idx,
          'service_type'  => $typeService,
          'reaction_type' => $typeReaction,
          'ip_address'    => $ipAddress,
          'created_by'    => $userIdx,
          'created_at'    => $now,
        );
        $this->reaction_model->insertReaction($insertValues);

        // 좋아요 개수
        $search = array(
          'club_idx'      => $clubIdx,
          'target_idx'    => $idx,
          'service_type'  => $typeService,
          'reaction_type' => $typeReaction,
        );
        $liked = $this->reaction_model->cntReaction($search);

        $result = array('error' => 0, 'message' => 1, 'liked' => $liked['cnt']);
      }
    } else {
      $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 댓글
   *
   * @return json
   * @author bjchoi
   **/
  public function reply_insert()
  {
    $now = time();
    $viewData['userData'] = $this->load->get_var('userData');
    $articleIdx = html_escape($this->input->post('articleIdx'));
    $replyIdx = html_escape($this->input->post('replyIdx'));
    $nickname = html_escape($this->input->post('nickname'));
    $content = html_escape($this->input->post('content'));
    $ipaddr = $_SERVER['REMOTE_ADDR'];

    if (!empty($articleIdx) && !empty($content)) {
      $insertValues = array(
        'idx_article' => $articleIdx,
        'idx_reply' => $replyIdx,
        'ip_address' => $ipaddr,
        'nickname' => $nickname,
        'content' => $content,
        'created_by' => !empty($viewData['userData']['idx']) ? $viewData['userData']['idx'] : NULL,
        'created_at' => $now,
      );
      $idx = $this->desk_model->insert(DB_ARTICLE_REPLY, $insertValues);

      // 댓글
      $cntReply = $this->desk_model->cntReply($articleIdx);

      // 아바타가 있는지 확인
      if (file_exists(AVATAR_PATH . $viewData['userData']['idx'])) {
        $avatar = AVATAR_URL . $viewData['userData']['idx'];
      } else {
        $avatar = '/public/images/user.png';
      }

      $result = array('error' => 0, 'message' => $cntReply['cnt'], 'idx' => $idx, 'avatar' => $avatar, 'date' => date('Y-m-d H:i', $now));
    } else {
      $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 댓글 삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function reply_delete()
  {
    $idx = html_escape($this->input->post('idx'));
    $articleIdx = html_escape($this->input->post('idx_article'));

    if (!empty($idx)) {
      $this->desk_model->deleteReply($idx);
      $cntReply = $this->desk_model->cntReply($articleIdx);
      $result = array('error' => 0, 'message' => $cntReply['cnt']);
    } else {
      $result = array('error' => 1, 'message' => $this->lang->line('error_all'));
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 현지영상
   *
   * @return view
   * @author bjchoi
   **/
  public function video()
  {
    $viewData['category'] = html_escape($this->input->get('c'));
    if (empty($viewData['category'])) $viewData['category'] = 'national';

    // 현지영상 목록
    $viewData['listCctv'] = $this->desk_model->listCctv($viewData, 'asc');

    // 현지영상 카테고리
    $viewData['listCctvCategory'] = $this->desk_model->listCctvCategory();

    $this->_viewPage('video', $viewData);
  }

  /**
   * 산행 목록
   *
   * @return view
   * @author bjchoi
   **/
  public function schedule()
  {
    $search['sdate'] = $viewData['startdate'] = !empty($this->input->get('sdate')) ? date('Y-m-d', html_escape($this->input->get('sdate'))) : NULL;
    $search['edate'] = !empty($this->input->get('sdate')) ? date('Y-m-d', html_escape($this->input->get('sdate'))) : NULL;
    $search['keyword'] = $viewData['keyword'] = !empty($this->input->get('keyword')) ? html_escape($this->input->get('keyword')) : NULL;
    $viewData['listNoticeSchedule'] = array();

    // 내부 여행일정
    $viewData['listNotice'] = $this->reserve_model->listNotice(NULL, array(STATUS_ABLE, STATUS_CONFIRM), 'asc', $search);
    foreach ($viewData['listNotice'] as $key1 => $value) {
      // 클럽 정보
      $viewClub = $this->club_model->viewClub($value['club_idx']);
      $viewData['listNotice'][$key1]['club_name'] = $viewClub['title'];
      $viewData['listNotice'][$key1]['url'] = base_url() . $viewClub['url'] . '/reserve/list/' . $value['idx'];

      // 댓글수
      $cntReply = $this->story_model->cntStoryReply($value['idx'], REPLY_TYPE_NOTICE);
      $viewData['listNotice'][$key1]['reply_cnt'] = $cntReply['cnt'];

      // 지역
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

      // 사진
      if (!empty($value['photo']) && file_exists(PHOTO_PATH . $value['photo'])) {
        $viewData['listNotice'][$key1]['photo'] = PHOTO_URL . $value['photo'];
      }
    }

    // 외부 여행일정
    $viewData['listSchedule'] = $this->desk_model->listSchedule($search, 'asc');
    foreach ($viewData['listSchedule'] as $key1 => $value) {
      // 지역
      if (!empty($value['area_sido'])) {
        $area_sido = unserialize($value['area_sido']);
        $area_gugun = unserialize($value['area_gugun']);

        foreach ($area_sido as $key2 => $value2) {
          $sido = $this->area_model->getName($value2);
          $gugun = $this->area_model->getName($area_gugun[$key2]);
          $viewData['listSchedule'][$key1]['sido'][$key2] = $sido['name'];
          $viewData['listSchedule'][$key1]['gugun'][$key2] = $gugun['name'];
        }
      }
    }

    // 내부 - 월간 여행일정
    $listNoticeFooter = $this->reserve_model->listNotice(NULL, array(STATUS_ABLE, STATUS_CONFIRM), 'asc');
    foreach ($listNoticeFooter as $key1 => $value) {
      $flag = false;
      foreach ($viewData['listNoticeSchedule'] as $key2 => $value2) {
        if ($value2['startdate'] == $value['startdate']) {
          $viewData['listNoticeSchedule'][$key2]['count'] = $viewData['listNoticeSchedule'][$key2]['count'] + 1;
          $flag = true;
        }
      }
      if ($flag == false) {
        $viewData['listNoticeSchedule'][] = array(
          'startdate' => $value['startdate'],
          'count' => 1
        );
      }
    }

    // 외부 - 월간 여행일정
    $listFooterSchedule = $this->desk_model->listSchedule();
    foreach ($listFooterSchedule as $key1 => $value) {
      $flag = false;
      foreach ($viewData['listNoticeSchedule'] as $key2 => $value2) {
        if ($value2['startdate'] == $value['startdate']) {
          $viewData['listNoticeSchedule'][$key2]['count'] = $viewData['listNoticeSchedule'][$key2]['count'] + 1;
          $flag = true;
        }
      }
      if ($flag == false) {
        $viewData['listNoticeSchedule'][] = array(
          'startdate' => $value['startdate'],
          'count' => 1
        );
      }
    }

    $this->_viewPage('schedule', $viewData);
  }

  /**
   * 산악회 목록
   *
   * @return view
   * @author bjchoi
   **/
  public function area()
  {
    $viewData['search'] = html_escape($this->input->get('s'));
    $viewData['keyword'] = html_escape($this->input->get('k'));
    $viewData['list'] = $this->club_model->listClub($viewData['search'], $viewData['keyword']);

    if ($viewData['search'] == 'area_sido') {
      $viewData['searchTitle'] = $this->area_model->getName($viewData['keyword']);
    } else {
      $viewData['searchTitle']['name'] = '전체';
    }

    foreach ($viewData['list'] as $key1 => $value) {
      // 지역
      if (!empty($value['area_sido'])) {
        $area_sido = unserialize($value['area_sido']);
        $area_gugun = unserialize($value['area_gugun']);

        foreach ($area_sido as $key2 => $value2) {
          $sido = $this->area_model->getName($value2);
          if (!empty($area_gugun[$key2])) {
            $gugun = $this->area_model->getName($area_gugun[$key2]);
          } else {
            $gugun['name'] = '';
          }
          $viewData['list'][$key1]['sido'][$key2] = $sido['name'];
          $viewData['list'][$key1]['gugun'][$key2] = $gugun['name'];
        }
      }

      // 사진
      $file = $this->file_model->getFile('club', $value['idx'], NULL, 1);
      if (!empty($file[0]['filename'])) {
        $viewData['list'][$key1]['thumbnail'] = UPLOAD_CLUB_URL . $value['idx'] . '/thumb_' . $file[0]['filename'];
      } else {
        $viewData['list'][$key1]['thumbnail'] = '/public/images/noimage.png';
      }

      // 회원수
      $cntMember = $this->member_model->cntMember($value['idx']);
      $viewData['list'][$key1]['cntMember'] = $cntMember['cnt'];

      // 산행횟수
      $cntNotice = $this->notice_model->cntNotice($value['idx']);
      $viewData['list'][$key1]['cntNotice'] = $cntNotice['cnt'];
    }

    $this->_viewPage('area', $viewData);
  }

  /**
   * 클럽 리스트 페이지
   *
   * @return view
   * @author bjchoi
   **/
  public function club_listing()
  {
    $viewData['search'] = html_escape($this->input->get('s'));
    $viewData['keyword'] = html_escape($this->input->get('k'));
    $viewData['list'] = $this->club_model->listClub($viewData['search'], $viewData['keyword']);

    if ($viewData['search'] == 'area_sido') {
      $viewData['searchTitle'] = $this->area_model->getName($viewData['keyword']);
    } else {
      $viewData['searchTitle']['name'] = '전체';
    }

    foreach ($viewData['list'] as $key => $value) {
      // 사진
      $file = $this->file_model->getFile('club', $value['idx'], NULL, 1);
      if (!empty($file[0]['filename'])) {
        $viewData['list'][$key]['photo'] = 'thumb_' . $file[0]['filename'];
      } else {
        $viewData['list'][$key]['photo'] = 'noimage.png';
      }
    }

    $this->_viewPage('club/listing', $viewData);
  }


  /**
   * 클럽 등록폼
   *
   * @return view
   * @author bjchoi
   **/
  public function club_entry($idx=NULL)
  {
    if (!is_null($idx)) {
      $viewData['view'] = $this->club_model->viewClub($idx);
      $viewData['view']['club_type'] = is_null($viewData['view']['club_type']) || strlen($viewData['view']['club_type']) <= 3 ? array() : unserialize($viewData['view']['club_type']);
      $viewData['view']['club_option'] = is_null($viewData['view']['club_option']) || strlen($viewData['view']['club_option']) <= 3 ? array() : unserialize($viewData['view']['club_option']);
      $viewData['view']['club_cycle'] = is_null($viewData['view']['club_cycle']) || strlen($viewData['view']['club_cycle']) <= 3 ? array() : unserialize($viewData['view']['club_cycle']);
      $viewData['view']['club_week'] = is_null($viewData['view']['club_week']) || strlen($viewData['view']['club_week']) <= 3 ? array() : unserialize($viewData['view']['club_week']);
      $viewData['view']['club_geton'] = is_null($viewData['view']['club_geton']) || strlen($viewData['view']['club_geton']) <= 3 ? array() : unserialize($viewData['view']['club_geton']);
      $viewData['view']['club_getoff'] = is_null($viewData['view']['club_getoff']) || strlen($viewData['view']['club_getoff']) <= 3 ? array() : unserialize($viewData['view']['club_getoff']);
      $files = $this->file_model->getFile('club', $idx);

      if (empty($files)) {
        $viewData['view']['photo'][0] = '';
      } else {
        foreach ($files as $key => $value) {
          if (!$value['filename'] == '') {
            $viewData['view']['photo'][$key] = $value['filename'];
          } else {
            $viewData['view']['photo'][$key] = '';
          }
        }
      }
    } else {
      $viewData['view']['idx'] = '';
      $viewData['view']['title'] = '';
      $viewData['view']['url'] = '';
      $viewData['view']['domain'] = '';
      $viewData['view']['homepage'] = '';
      $viewData['view']['main_color'] = '';
      $viewData['view']['area_sido'] = '';
      $viewData['view']['area_gugun'] = '';
      $viewData['view']['phone'] = '';
      $viewData['view']['content'] = '';
      $viewData['view']['photo'][0] = '';
      $viewData['view']['establish'] = '';
      $viewData['view']['club_type'] = array();
      $viewData['view']['club_option'] = array();
      $viewData['view']['club_cycle'] = array();
      $viewData['view']['club_week'] = array();
      $viewData['view']['club_geton'] = array();
      $viewData['view']['club_getoff'] = array();
      $viewData['view']['club_option_text'] = '';
    }

    $viewData['area_sido'] = $this->area_model->listSido();
    if (!empty($viewData['view']['area_sido'])) {
      $area_sido = unserialize($viewData['view']['area_sido']);
      $area_gugun = unserialize($viewData['view']['area_gugun']);

      foreach ($area_sido as $key => $value) {
        $sido = $this->area_model->getName($value);
        $gugun = $this->area_model->getName($area_gugun[$key]);
        $viewData['list_sido'] = $this->area_model->listSido();
        $viewData['list_gugun'][$key] = $this->area_model->listGugun($value);
        $viewData['view']['sido'][$key] = $sido['name'];
        $viewData['view']['gugun'][$key] = $gugun['name'];
      }

      $viewData['area_gugun'] = $this->area_model->listGugun($viewData['view']['area_sido']);
    } else {
      $viewData['area_gugun'] = array();
    }

    $this->_viewPage('club/entry', $viewData);
  }

  /**
   * 클럽 등록
   *
   * @return json
   * @author bjchoi
   **/
  public function club_insert()
  {
    $now = time();
    $input_data = $this->input->post();
    $files = explode(',', html_escape($input_data['file_' . TYPE_MAIN]));

    $insert_values = array(
      'title'             => html_escape($input_data['title']),
      'url'               => html_escape($input_data['url']),
      'domain'            => html_escape($input_data['domain']),
      'homepage'          => html_escape($input_data['homepage']),
      'phone'             => html_escape($input_data['phone']),
      'main_color'        => html_escape($input_data['main_color']),
      'area_sido'         => make_serialize($input_data['area_sido']),
      'area_gugun'        => make_serialize($input_data['area_gugun']),
      'about'             => html_escape($input_data['about']),
      'establish'         => html_escape($input_data['establish']),
      'club_type'         => make_serialize($input_data['club_type']),
      'club_option'       => make_serialize($input_data['club_option']),
      'club_option_text'  => html_escape($input_data['club_option_text']),
      'club_cycle'        => make_serialize($input_data['club_cycle']),
      'club_week'         => make_serialize($input_data['club_week']),
      'club_geton'        => make_serialize($input_data['club_geton']),
      'club_getoff'       => make_serialize($input_data['club_getoff']),
      'created_by'        => 1,
      'created_at'        => $now
    );

    $idx = $this->club_model->insertClub($insert_values);

    if (!empty($idx)) {
      // 파일 등록
      foreach ($files as $value) {
        // 업로드 된 파일이 있을 경우에만 등록 후 이동
        if (!empty($value) && file_exists(UPLOAD_PATH . $value)) {
          $file_values = array(
            'page' => html_escape($input_data['page']),
            'page_idx' => $idx,
            'filename' => $value,
            'created_at' => $now
          );
          $this->file_model->insertFile($file_values);

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

    redirect('/club');
  }

  /**
   * 클럽 수정
   *
   * @return json
   * @author bjchoi
   **/
  public function club_update()
  {
    $now = time();
    $input_data = $this->input->post();
    $idx = html_escape($input_data['idx']);
    $files = explode(',', html_escape($input_data['file_' . TYPE_MAIN]));

    $update_values = array(
      'title'             => html_escape($input_data['title']),
      'url'               => html_escape($input_data['url']),
      'domain'            => html_escape($input_data['domain']),
      'homepage'          => html_escape($input_data['homepage']),
      'phone'             => html_escape($input_data['phone']),
      'main_color'        => html_escape($input_data['main_color']),
      'area_sido'         => make_serialize($input_data['area_sido']),
      'area_gugun'        => make_serialize($input_data['area_gugun']),
      'content'           => html_escape($input_data['content']),
      'establish'         => html_escape($input_data['establish']),
      'club_type'         => make_serialize($input_data['club_type']),
      'club_option'       => make_serialize($input_data['club_option']),
      'club_option_text'  => html_escape($input_data['club_option_text']),
      'club_cycle'        => make_serialize($input_data['club_cycle']),
      'club_week'         => make_serialize($input_data['club_week']),
      'club_geton'        => make_serialize($input_data['club_geton']),
      'club_getoff'       => make_serialize($input_data['club_getoff']),
      'updated_by'        => 1,
      'updated_at'        => $now
    );
    $this->club_model->updateClub($update_values, $idx);

    // 파일 등록
    if ($files[0] != '') {
      foreach ($files as $value) {
        // 업로드 된 파일이 있을 경우에만 등록 후 이동
        if (file_exists(UPLOAD_PATH . $value)) {
          $file_values = array(
            'page' => html_escape($input_data['page']),
            'page_idx' => $idx,
            'filename' => $value,
            'created_at' => $now
          );
          $this->file_model->insertFile($file_values);

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

    $result = array(
      'error' => 0,
      'message' => '수정이 완료되었습니다.'
    );

    $this->output->set_output(json_encode($result));
  }

  /**
   * 클럽 삭제
   *
   * @return json
   * @author bjchoi
   **/
  public function club_delete()
  {
    $input_data = $this->input->post();
    $idx = html_escape($input_data['idx']);

    $update_values = array(
      'deleted_by' => 1,
      'deleted_at' => time()
    );

    $result = $this->club_model->updateClub($update_values, $idx);

    if ($result != '') {
      $result = array(
        'error' => 0,
        'message' => '삭제가 완료되었습니다.'
      );
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 도메인 중복 체크
   *
   * @return json
   * @author bjchoi
   **/
  public function check_url()
  {
    $url = html_escape($this->input->post('url'));
    $check = $this->club_model->getUrl($url);

    if (empty($check['idx'])) {
      $result = array('error' => 0, 'message' => '');
    } else {
      $result = array('error' => 1, 'message' => '');
    }

    $this->output->set_output(json_encode($result));
  }

  /**
   * 산행일정
   *
   * @return view
   * @author bjchoi
   **/
  public function calendar()
  {
    // 클럽ID
    $viewData['clubIdx'] = 1;

    // 등록된 산행 목록
    $search['sdate'] = date('Y-m-01');
    $search['edate'] = date('Y-m-t', time() + (60 * 60 * 24 * 30 * 12));
    $search['status'] = array(STATUS_PLAN, STATUS_ABLE, STATUS_CONFIRM, STATUS_CANCEL, STATUS_CLOSED);
    $search['clubIdx'] = $viewData['clubIdx'];
    $viewData['listNotice'] = $this->admin_model->listNotice($search);

    foreach ($viewData['listNotice'] as $key => $value) {
      $viewData['listCalendar'][] = array(
        'idx' => $value['idx'],
        'startdate' => $value['startdate'],
        'starttime' => $value['starttime'],
        'enddate' => $value['enddate'],
        'schedule' => $value['schedule'],
        'status' => $value['status'],
        'mname' => $value['mname'],
        'class' => '',
      );
    }

    // 캘린더 설정
    $listCalendar = $this->admin_model->listCalendar();

    foreach ($listCalendar as $key => $value) {
      if ($value['holiday'] == 1) {
        $class = 'holiday';
      } else {
        $class = 'dayname';
      }
      $viewData['listCalendar'][] = array(
        'idx' => 0,
        'startdate' => $value['nowdate'],
        'enddate' => $value['nowdate'],
        'schedule' => 0,
        'status' => 'schedule',
        'mname' => $value['dayname'],
        'class' => $class,
      );
    }

    // 페이지 타이틀
    $viewData['pageTitle'] = '산행 일정 복사';

    // 헤더 메뉴
    $viewData['headerMenu'] = 'main_header';

    $this->load->view('calendar', $viewData);
  }

  public function test()
  {
    $listMembers = $this->desk_model->listMembers();
    $cnt = 1;

    echo "<table border='1' cellspacing='0' cellpadding='5'><tr><th>번호</th><th>닉네임</th><th>현재 예약수</th><th>개정 예약수</th><th>페널티</th><th>페널티 빼면</th></tr>";
    foreach ($listMembers as $key => $value) {
      $checkReservation = $this->desk_model->checkReservation($value['idx']);
      $memberLevel1 = memberLevel($value['rescount'], !empty($value['penalty']) ? $value['penalty'] : 0, $value['level'], $value['admin']);
      $memberLevel2 = memberLevel(!empty($checkReservation) ? count($checkReservation) : 0, !empty($value['penalty']) ? $value['penalty'] : 0, $value['level'], $value['admin']);
      $memberLevel3 = memberLevel(!empty($checkReservation) ? count($checkReservation) : 0, 0, $value['level'], $value['admin']);

      if ($memberLevel1['levelName'] != $memberLevel2['levelName']) {
        $bgcolor = "#FFFFFF";

      echo "<tr align='center' bgcolor='" . $bgcolor . "'><td>";
      echo $cnt;
      echo "</td><td>";
      echo $value['nickname'];
      echo "</td><td>";
      echo $value['rescount'];
      echo " (" . $memberLevel1['levelName'] . ")";
      echo "</td><td>";
      echo number_format(count($checkReservation));
      echo " (" . $memberLevel2['levelName'] . ")";
      echo "</td><td>";
      echo $value['penalty'];
      echo "</td><td>";
      echo " (" . $memberLevel3['levelName'] . ")";
      echo "</td></tr>";
$cnt++;
    }
    }
    echo "</table>";
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
    // 리다이렉트 URL 추출
    if ($_SERVER['SERVER_PORT'] == '80') $HTTP_HEADER = 'http://'; else $HTTP_HEADER = 'https://';
    $viewData['redirectUrl'] = $HTTP_HEADER . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    // 회원 정보
    $viewData['userData'] = $this->load->get_var('userData');

    // 분류별 기사
    $viewData['listArticleCategory'] = $this->desk_model->listArticleCategory();

    // 분류별 기사 카운트
    foreach ($viewData['listArticleCategory'] as $key => $value) {
      $cnt = $this->desk_model->cntArticle($value['code']);
      $viewData['listArticleCategory'][$key]['cnt'] = $cnt['cnt'];
    }

    // 여행일정
    $viewData['listFooterNotice'] = $this->reserve_model->listNotice(NULL, array(STATUS_ABLE, STATUS_CONFIRM), 'asc');

    foreach ($viewData['listFooterNotice'] as $key1 => $value) {
      $viewClub = $this->club_model->viewClub($value['club_idx']);
      $viewData['listFooterNotice'][$key1]['club_name'] = $viewClub['title'];
      $viewData['listFooterNotice'][$key1]['url'] = base_url() . $viewClub['url'] . '/reserve/list/' . $value['idx'];

      // 댓글수
      $cntReply = $this->story_model->cntStoryReply($value['idx'], REPLY_TYPE_NOTICE);
      $viewData['listFooterNotice'][$key1]['reply_cnt'] = $cntReply['cnt'];

      // 지역
      if (!empty($value['area_sido'])) {
        $area_sido = unserialize($value['area_sido']);
        $area_gugun = unserialize($value['area_gugun']);

        foreach ($area_sido as $key2 => $value2) {
          $sido = $this->area_model->getName($value2);
          $gugun = $this->area_model->getName($area_gugun[$key2]);
          $viewData['listFooterNotice'][$key1]['sido'][$key2] = $sido['name'];
          $viewData['listFooterNotice'][$key1]['gugun'][$key2] = $gugun['name'];
        }
      }

      // 사진
      if (!empty($value['photo']) && file_exists(PHOTO_PATH . $value['photo'])) {
        $viewData['listFooterNotice'][$key1]['photo'] = PHOTO_URL . $value['photo'];
      } else {
        $viewData['listFooterNotice'][$key1]['photo'] = '/public/images/nophoto.png';
      }
    }

    $this->load->view('header', $viewData);
    $this->load->view($viewPage, $viewData);
    $this->load->view('footer');
  }
}
?>