<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$CI = get_instance();
$CI->load->model(array('admin_model', 'member_model', 'reserve_model', 'desk_model'));

// html_escape 리셋
if (!function_exists('reset_html_escape')) {
  function reset_html_escape($data) {
    $trans = get_html_translation_table();
    $trans = array_flip($trans);
    $result = strtr($data, $trans);
    return $result;
  }
}

// serialize
if (!function_exists('make_serialize')) {
  function make_serialize($data, $blank=FALSE) {
    foreach ($data as $value) {
      if ($value != '' && $blank == FALSE) {
        $result[] = $value;
      }
    }
    if (!empty($result)) {
      $result = serialize(html_escape($result));
    } else {
      $result = '';
    }
    return $result;
  }
}

// substr
if (!function_exists('ksubstr'))
{
  function ksubstr($str, $n=500, $end='...')
  {
    $CI =& get_instance();
    $charset = $CI->config->item('charset');
     
    if ( mb_strlen( $str , $charset) < $n ) {
      return $str ;
    }
   
    $str = preg_replace( "/\s+/iu", ' ', str_replace( array( "\r\n", "\r", "\n" ), ' ', $str ) );
   
    if ( mb_strlen( $str , $charset) <= $n ) {
      return $str;
    }
    return mb_substr(trim($str), 0, $n ,$charset) . $end;
  }
}

// 여행 포인트
if (!function_exists('getPoint')) {
  function getPoint($point) {
    $result = '';
    $arr = unserialize($point);

    if ($arr != '') {
      foreach ($arr as $value) {
        if ($result != '') $result .= ' / ';
        switch ($value) {
          case 'point1':
            $result .= '문화재';
          break;
          case 'point2':
            $result .= '천연기념물';
          break;
          case 'point3':
            $result .= '보물';
          break;
        }
      }
    }
    return $result;
  }
}

// 해발
if (!function_exists('getHeight'))
{
  function getHeight($n, $d = 1)
  {
    if (!empty($n)) {
      $result = number_format($n, $d);
      $result = rtrim($result, 0);
      $result = rtrim($result, '.');
      $result = '해발 ' . $result . 'm';
    } else {
      $result = '해발 0m';
    }
    return $result;
  }
}

// 지역
if (!function_exists('getAreaName')) {
  function getAreaName($sido, $gugun, $limit=NULL) {
    $result = NULL;
    if ($sido != '' || $gugun != '') {
      $result = '';
      $arr_sido = unserialize($sido);
      $arr_gugun = unserialize($gugun);

      foreach ($arr_sido as $key => $value) {
        if ($key >= 1) $result .= ', ';
        $sido = $GLOBALS['CI']->area_model->getName($value);
        $gugun = $GLOBALS['CI']->area_model->getName($arr_gugun[$key]);
        $result .= $sido['name'] . ' ' . $gugun['name'];
        if (!is_null($limit)) break;
      }
    }
    return $result;
  }
}

// 클럽 홈 URL
if (!function_exists('goHome')) {
  function goHome($club) {
    if (!empty($club['domain'])) {
      $result = $club['domain'];
    } elseif (!empty($club['url'])) {
      $result = base_url() . $club['url'];
    }
    return $result;
  }
}

// 방문자 기록
if (!function_exists('setVisitor')) {
  function setVisitor() {
    $CI =& get_instance();
    if (!empty($_COOKIE['COOKIE_CLUBIDX'])) {
      $clubIdx = $_COOKIE['COOKIE_CLUBIDX'];
      $userData = $CI->load->get_var('userData');
      $ipAddress = $_SERVER['REMOTE_ADDR'];

      if (!empty($clubIdx) && !empty($userData['idx'])) {
        $visitor = $GLOBALS['CI']->member_model->viewVisitor($clubIdx, NULL, $ipAddress);

        // 최근 30분 이내 접속했다면 동일 접속으로 취급
        $limitTime = time() - (60 * 30);

        if ($visitor['ip_address'] == $ipAddress && empty($visitor['created_by'])) {
          // 직전 접속한 같은 IP의 사람이 로그인 했다면 닉네임으로 변경
          $updateValues = array(
            'created_by' => $userData['idx']
          );
          $GLOBALS['CI']->member_model->updateVisitor($updateValues, $visitor['idx']);
        } elseif ($visitor['created_at'] <= $limitTime) {
          $insertValues = array(
            'club_idx'      => $clubIdx,
            'ip_address'    => $ipAddress,
            'user_agent'    => $_SERVER['HTTP_USER_AGENT'],
            'http_referer'  => !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL,
            'created_by'    => $userData['idx'],
            'created_at'    => time()
          );
          $GLOBALS['CI']->member_model->insertVisitor($insertValues);
        }
      }
    }
  }
}

// 사용자 로그인 확인
if (!function_exists('checkUserLogin')) {
  function checkUserLogin() {
    $CI =& get_instance();
    $clubIdx = $_COOKIE['COOKIE_CLUBIDX'];
    $userData = $CI->load->get_var('userData');
    if (empty($userData['idx'])) {
      redirect(BASE_URL . '/login/?r=' . $_SERVER['REQUEST_URI']);
    }
  }
}

// 로그인이 필요없는 페이지에 로그인 했을 경우 메인 페이지로 되돌리기
if (!function_exists('checkUserLoginRedirect')) {
  function checkUserLoginRedirect($redirectUrl=NULL) {
    $CI =& get_instance();
    $idx = $CI->session->userData['idx'];
    if (!empty($idx)) {
      redirect(!empty($redirectUrl) ? $redirectUrl : BASE_URL);
    }
  }
}

// 관리자 로그인 확인
if (!function_exists('checkAdminLogin')) {
  function checkAdminLogin() {
    $CI =& get_instance();
    $clubIdx = $_COOKIE['COOKIE_CLUBIDX'];
    $userData = $CI->load->get_var('userData');
    if (empty($userData['idx']) || empty($userData['admin']) || $clubIdx != $userData['club_idx']) {
      redirect(BASE_URL . '/login/?r=' . $_SERVER['REQUEST_URI']);
    }
  }
}

// 산행 상태
if (!function_exists('viewStatus')) {
  function viewStatus($status=0, $visible=1) {
    if ($visible != 1) $visible = ' blur'; else $visible = '';
    switch ($status) {
      case STATUS_ABLE:     $result = '<span class="status-wait' . $visible . '">[예정]</span>'; break;
      case STATUS_CONFIRM:  $result = '<span class="status-confirm' . $visible . '">[확정]</span>'; break;
      case STATUS_CANCEL:   $result = '<span class="status-cancel' . $visible . '">[취소]</span>'; break;
      case STATUS_CLOSED:   $result = '<span class="status-closed' . $visible . '">[종료]</span>'; break;
      default:              $result = '<span class="status-plan' . $visible . '">[계획]</span>'; break;
    }
    return $result;
  }
}

// 요일 계산
if (!function_exists('calcWeek')) {
  function calcWeek($week) {
    $week = explode("-", $week);
    $week = date("w", mktime(0, 0, 0, $week[1], $week[2], $week[0]));
    switch ($week) {
      case "1": $week = "월"; break;
      case "2": $week = "화"; break;
      case "3": $week = "수"; break;
      case "4": $week = "목"; break;
      case "5": $week = "금"; break;
      case "6": $week = "토"; break;
      default : $week = "일"; break;
    }
    return $week;
  }
}

// 날짜 계산
if (!function_exists('calcDate')) {
  function calcDate($date) {
    $week = date("w", $date);
    switch ($week) {
      case "1": $week = "월"; break;
      case "2": $week = "화"; break;
      case "3": $week = "수"; break;
      case "4": $week = "목"; break;
      case "5": $week = "금"; break;
      case "6": $week = "토"; break;
      default : $week = "일"; break;
    }

    return date("Y년 m월 d일 (" . $week . ") H:i:s", $date);
  }
}

// 산행 예약자 카운트
if (!function_exists('cntRes')) {
  function cntRes($resCode, $bus=NULL) {
    $cnt1 = $GLOBALS['CI']->admin_model->cntReservation($resCode, $bus);
    $cnt2 = $GLOBALS['CI']->admin_model->cntReservationHonor($resCode, $bus);

    if ($cnt2 > 0) {
      $result = $cnt1['CNT'] - ($cnt2['CNT'] / 2);
    } else {
      $result = $cnt1['CNT'];
    }

    return $result;
  }
}

// 산행 기간 계산
if (!function_exists('calcTerm')) {
  function calcTerm($startDate, $startTime, $endDate, $schedule = NULL) {
    if (!is_null($schedule)) {
      // 예전 방식
      switch ($schedule) {
        case "1": $result = "무박"; break;
        case "2": $result = "1박2일"; break;
        case "3": $result = "1무1박3일"; break;
        case "4": $result = "2박3일"; break;
        case "5": $result = "3박4일"; break;
        case "6": $result = "3박5일"; break;
        case "7": $result = "4박5일"; break;
        case "8": $result = "7박9일"; break;
        default : $result = "당일";
      }
    } else {
      // 새로운 방식
      $sTime = explode(':', $startTime);
      $dTerm = date('j', (strtotime($endDate) - strtotime($startDate)));

      if ($startDate == $endDate) {
        $result = '당일';
      } else {
        if ($sTime[0].$sTime[1] < '2200') {
          // 22시 이전 출발은 1박 확정
          $result = ($dTerm - 1) . '박 ' . $dTerm . '일';
        } else {
          // 22시 이후 출발은 1일 무박
          $sleepDay = $dTerm - 2;
          if ($sleepDay == 0) {
            $result = '무박';
          } else {
            $result = '1무 ' . ($sleepDay) . '박 ' . $dTerm . '일';
          }
        }
      }
    }

    return $result;
  }
}

// 산행 종료일 계산
if (!function_exists('calcEndDate')) {
  function calcEndDate($startDate, $endDate) {
    if (strlen($endDate) <= 1) {
      // 예전 방식
      switch ($endDate) {
        case '2': // 1박 2일
          $addedDate = ' +1 day';
          break;
        case '3': // 1무 1박 3일
        case '4': // 2박 3일
          $addedDate = ' +2 day';
          break;
        case '5': // 3박 4일
          $addedDate = ' +3 day';
          break;
        case '6': // 3박 5일
        case '7': // 4박 5일
          $addedDate = ' +4 day';
          break;
        case '8': // 7박 9일
          $addedDate = ' +9 day';
          break;
        default: // 무박
          $addedDate = '';
      }
      $result = strtotime($startDate . $addedDate);
    } else {
      // 새로운 방식
      $result = strtotime($endDate);
    }

    return $result;
  }
}

// 거리, 요금
if (!function_exists('calcSchedule')) {
  function calcSchedule($schedule) {
    switch ($schedule) {
      case "1": $result = "무박"; break;
      case "2": $result = "1박2일"; break;
      case "3": $result = "1무1박3일"; break;
      case "4": $result = "2박3일"; break;
      case "5": $result = "3박4일"; break;
      case "6": $result = "3박5일"; break;
      case "7": $result = "4박5일"; break;
      case "8": $result = "7박9일"; break;
      default : $result = "당일";
    }
    return $result;
  }
}

// 거리, 요금
if (!function_exists('calcDistance')) {
  function calcDistance($distance) {
    /*
    switch ($distance) {
      case  "1": $result = "왕복 200km 미만구간"; break;
      case  "2": $result = "왕복 250km 미만구간"; break;
      case  "3": $result = "왕복 300km 미만구간"; break;
      case  "4": $result = "왕복 350km 미만구간"; break;
      case  "5": $result = "왕복 400km 미만구간"; break;
      case  "6": $result = "왕복 450km 미만구간"; break;
      case  "7": $result = "왕복 500km 미만구간"; break;
      case  "8": $result = "왕복 550km 미만구간"; break;
      case  "9": $result = "왕복 600km 미만구간"; break;
      case "10": $result = "왕복 650km 미만구간"; break;
      case "11": $result = "왕복 700km 미만구간"; break;
      case "12": $result = "왕복 750km 미만구간"; break;
      case "13": $result = "왕복 800km 미만구간"; break;
      case "14": $result = "왕복 850km 미만구간"; break;
      case "15": $result = "왕복 900km 미만구간"; break;
      case "16": $result = "왕복 950km 미만구간"; break;
      case "17": $result = "왕복 1000km 미만구간"; break;
      case "18": $result = "왕복 1000km 이상구간"; break;
      case "99": $result = "해외트래킹"; break;
      default  : $result = "";
    }
    */

    if ($distance < 200) $result = "왕복 200km 미만구간";
    elseif ($distance >= 200 && $distance < 250) $result = "왕복 250km 미만구간";
    elseif ($distance >= 250 && $distance < 300) $result = "왕복 300km 미만구간";
    elseif ($distance >= 300 && $distance < 350) $result = "왕복 350km 미만구간";
    elseif ($distance >= 350 && $distance < 400) $result = "왕복 400km 미만구간";
    elseif ($distance >= 400 && $distance < 450) $result = "왕복 450km 미만구간";
    elseif ($distance >= 450 && $distance < 500) $result = "왕복 500km 미만구간";
    elseif ($distance >= 500 && $distance < 550) $result = "왕복 550km 미만구간";
    elseif ($distance >= 550 && $distance < 600) $result = "왕복 600km 미만구간";
    elseif ($distance >= 600 && $distance < 650) $result = "왕복 650km 미만구간";
    elseif ($distance >= 650 && $distance < 700) $result = "왕복 700km 미만구간";
    elseif ($distance >= 700 && $distance < 750) $result = "왕복 750km 미만구간";
    elseif ($distance >= 750 && $distance < 800) $result = "왕복 800km 미만구간";
    elseif ($distance >= 800 && $distance < 850) $result = "왕복 850km 미만구간";
    elseif ($distance >= 850 && $distance < 900) $result = "왕복 900km 미만구간";
    elseif ($distance >= 900 && $distance < 950) $result = "왕복 950km 미만구간";
    elseif ($distance >= 950 && $distance < 1000) $result = "왕복 1000km 미만구간";
    elseif ($distance >= 1000 && $distance < 9000) $result = "왕복 1000km 이상구간";
    elseif ($distance >= 9000) $result = "해외트래킹";
    else $result = "";

    return $result;
  }
}

// 승차위치
if (!function_exists('arrLocation')) {
  function arrLocation($club_geton=NULL, $starttime=NULL) {
    $result = array(array('time' => '', 'title' => '', 'short' => ''));
    if (!empty($club_geton)) {
      if (!is_null($starttime)) {
        $starttime = strtotime($starttime);
      }
      $arrGeton = unserialize($club_geton);

      foreach ($arrGeton as $key => $value) {
        $buf = explode('|', $value);
        if (empty($buf[2])) $buf[2] = 0; // 시간이 비었을때는 계산을 위해 0으로 고정

        $arr = array('time' => !is_null($starttime) ? date('H:i', strtotime(date('H:i', $starttime) . '+' . $buf[2] . ' minutes')) : '', 'title' => $buf[1], 'short' => $buf[0]);
        array_push($result, $arr);
      }
    }
    return $result;
  }
}

// 조식 메뉴
if (!function_exists('arrBreakfast')) {
  function arrBreakfast() {
    return array(
      '',
      '김밥',
      '김치주먹밥',
      '참치주먹밥',
      '김치왕만두',
      '고기왕만두',
      '찐빵',
    );
  }
}

// 행사 유형
if (!function_exists('getNoticeType')) {
  function getNoticeType($type) {
    switch ($type) {
      case TYPE_WALKING: $result = '도보'; break;
      case TYPE_TOUR: $result = '여행'; break;
      default: $result = '산행';
    }
    return $result;
  }
}

// 산행 옵션
if (!function_exists('getOptions')) {
  function getOptions($options) {
    $arr = unserialize($options);
    $result = '';
    foreach ($arr as $key => $value) {
      if ($key != 0) $result .= ', ';
      $result .= $value;
    }
    return $result;
  }
}

// 해당 산행에 등록된 버스
if (!function_exists('getBusType')) {
  function getBusType($busType, $bus) {
    $result = array();
    if (empty($busType)) {
      // 예전 방식
      $busType = explode(',', $bus);
      foreach ($busType as $value) {
        $busTypeIdx = explode('/', $value);
        if (!empty($busTypeIdx[1])) {
          $result[] = $GLOBALS['CI']->admin_model->getBustype($busTypeIdx[1]); 
        }
      }
    } else {
      // 새로운 방식
      $busTypeIdx = unserialize($busType);
      foreach ($busTypeIdx as $value) {
        $result[] = $GLOBALS['CI']->admin_model->getBustype($value); 
      }
    }
    return $result;
  }
}

// 버스 형태별 테이블 HTML 출력
if (!function_exists('getBusTableMake')) {
  function getBusTableMake($seatType, $seat) {
    $result = '';
    if ($seat == 1) $result = '<tr>';
    switch ($seatType) {
      case '40': // 40석
      case '44': // 44석
        if ($seat != 1 && $seat%4 == 1) $result = '</tr><tr>';
        elseif ($seat%4 == 3) $result = '<td colspan="2" class="table-blank"></td>';
        break;
      case '41': // 41석
        if ($seat != 1 && $seat%4 == 1 && $seat < 39) $result = '</tr><tr>';
        elseif ($seat%4 == 3 && $seat < 39) $result = '<td colspan="2" class="table-blank"></td>';
        break;
      case '45': // 45석
        if ($seat != 1 && $seat%4 == 1 && $seat < 43) $result = '</tr><tr>';
        elseif ($seat%4 == 3 && $seat < 43) $result = '<td colspan="2" class="table-blank"></td>';
        break;
      case '31': // 31석
        if ($seat != 1 && $seat%3 == 1 && $seat < 29) $result = '</tr><tr>';
        elseif ($seat%3 == 0 && $seat < 29) $result = '<td colspan="2" class="table-blank"></td>';
        break;
      case '28': // 28석
        if ($seat != 1 && $seat%3 == 1 && $seat < 26) $result = '</tr><tr>';
        elseif ($seat%3 == 0 && $seat < 26) $result = '<td colspan="2" class="table-blank"></td>';
        break;
      case '25': // 25석
        if ($seat != 1 && $seat%4 == 1 && $seat < 23) $result = '</tr><tr>';
        elseif ($seat%4 == 3 && $seat < 23) $result = '<td colspan="2" class="table-blank"></td>';
        break;
      case '15': // 15석
      case '12': // 12석
        if ($seat%3 == 1) $result = '</tr><tr>';
        break;
      case '5': // 5석
        if ($seat == 2) $result = '<td colspan="2"></td>';
        elseif ($seat%3 == 0) $result = '</tr><tr>';
        break;
    }
    return $result;
  }
}

// 버스별 보조석
if (!function_exists('getBusAssist')) {
  function getBusAssist($assist, $i) {
    $result = '가이드석';
    $value = unserialize($assist);
    $bus = $i - 1;
    if (!empty($value[$bus])) {
      $result = $value[$bus];
    }
    return $result;
  }
}

// 순방향/역방향 좌석 번호 확인
if (!function_exists('checkDirection')) {
  function checkDirection($seat, $bus, $noticeBusType, $noticeBus) {
    $result = $seat;
    $key = $bus - 1;
    $busType = getBusType($noticeBusType, $noticeBus); // 버스 형태별 좌석 배치

    if (!empty($busType[$key]['direction']) && $busType[$key]['direction'] == 1) {
      // 역방향 (좌석 번호는 그대로 놔둔 상태에서 표시만 역방향으로 한다)
      if ($seat%4 == 1) $result = $seat + 3;
      elseif ($seat%4 == 2) $result = $seat + 1;
      elseif ($seat%4 == 3) $result = $seat - 1;
      elseif ($seat%4 == 0) $result = $seat - 3;

      // 맨 뒤의 좌석이 5석인 경우
      switch ($busType[$key]['seat']) {
        case '41':
          if ($seat == '37') $result = '41';
          elseif ($seat == '38') $result = '40';
          elseif ($seat == '39') $result = '39';
          elseif ($seat == '40') $result = '38';
          elseif ($seat == '41') $result = '37';
          break;
        case '45':
          if ($seat == '41') $result = '45';
          elseif ($seat == '42') $result = '44';
          elseif ($seat == '43') $result = '43';
          elseif ($seat == '44') $result = '42';
          elseif ($seat == '45') $result = '41';
          break;
      }
    }
    return $result;
  }
}

// 예약자 정보 (관리용)
if (!function_exists('getReserveAdmin')) {
  function getReserveAdmin($reserve, $bus, $seat, $userData, $boarding=0) {
    if ($boarding == 1) {
      $result = array('idx' => '', 'user_idx' => '', 'nickname' => '', 'class' => '');
    } else {
      $result = array('idx' => '', 'user_idx' => '', 'nickname' => '', 'class' => '');
    }

    foreach ($reserve as $key => $value) {
      if ($value['bus'] == $bus && $value['seat'] == $seat) {
        $result['idx'] = $value['idx'];
        $result['user_idx'] = $value['user_idx'];
        $result['nickname'] = $value['nickname'];

        if ($value['status'] == RESERVE_PAY) {
          $result['class'] .= 'seat paid ';
        } elseif ($value['status'] == RESERVE_WAIT) {
          $result['class'] .= 'seat wait ';
        } else {
          if ($value['nickname'] != '1인우등' && $value['nickname'] != '2인우선') {
            if ($value['gender'] == 'M') {
              $result['class'] .= 'seat male ';
            } elseif ($value['gender'] == 'F') {
              $result['class'] .= 'seat female ';
            }
          }
        }
        if (!empty($value['priority'])) {
          $result['class'] .= 'priority';
          $result['priority'] = $value['priority'];
        } elseif (!empty($value['honor'])) {
          $result['class'] .= 'honor';
          $result['honor'] = $value['honor'];
        }
      }
      $checkGender[$value['bus']][$value['seat']] = $value['gender'];
    }

    if (empty($result['class'])) $result['class'] = 'seat';

    return $result;
  }
}

// 예약자 정보 (일반용)
if (!function_exists('getReserve')) {
  function getReserve($reserve, $bus, $seat, $userData, $status, $seatType) {
    if ($status == STATUS_CLOSED) {
      $result = array('idx' => '', 'user_idx' => '', 'nickname' => '', 'class' => '');
    } else {
      $result = array('idx' => '', 'user_idx' => '', 'nickname' => '예약가능', 'class' => 'seat');
    }
    foreach ($reserve as $key => $value) {
      if ($value['bus'] == $bus && $value['seat'] == $seat) {
        if (!empty($value['user_idx']) && $userData['idx'] == $value['user_idx']) {
          if (!empty($value['priority'])) {
            $value['class'] = 'my-priority';
          } elseif (!empty($value['honor'])) {
            $value['class'] = 'my-honor';
          } else {
            $value['class'] = 'my-seat';
          }
        } else {
          $value['class'] = '';
        }
        if ($value['status'] == RESERVE_PAY) {
          $value['class'] .= '';
        } elseif ($value['status'] == RESERVE_WAIT) {
          $value['class'] .= ' wait';
        } else {
          if ($value['nickname'] != '1인우등' && $value['nickname'] != '2인우선') {
            $value['class'] .= ' reserved';
          } else {
            if (!empty($value['priority'])) {
              $value['class'] .= ' priority';
            } elseif (!empty($value['honor'])) {
              if (empty($value['status'])) {
                $value['class'] .= ' honor';
              }
            }
          }
        }
        $result = $value;
      }
      $checkGender[$value['bus']][$value['seat']] = $value['gender'];
    }

    /* 여성우선 관련 알고리즘인데, 코로나 대응과 우등버스 대응으로 인해 잠시 가려둠. 추후 우등버스에 여성우선 관련 알고리즘이 제대로 작동할 수 있도록 수정 (2020/12/09)
    if ($result['idx'] == '') {
      // 붙어있는 좌석은 같은 성별로만
      if ($status == STATUS_CLOSED) {
        $message = '';
      } else {
        if ($userData['gender'] == 'F') $message = '<span class="text-primary">남성우선</span>';
        elseif ($userData['gender'] == 'M') $message = '<span class="text-danger">여성우선</span>';
        else $message = '예약가능';
      }

      if ($seat %2 == 1) {
        // 현재 좌석은 홀수
        $nextSeat = $seat + 1;
        if (!empty($checkGender[$bus][$nextSeat]) && $userData['gender'] != $checkGender[$bus][$nextSeat]) {
          if (empty($userData['gender'])) $result['class'] = 'seat'; else $result['class'] = '';
          $result['nickname'] = $message;
          //$result['nickname'] = !empty($message) ? $message : $checkGender[$bus][$nextSeat] == 'M' ? '<span class="text-primary">남성우선</span>' : '<span class="text-danger">여성우선</span>';
        }
      } elseif ($seat %2 == 0) {
        // 현재 좌석은 짝수
        $prevSeat = $seat - 1;
        if (!empty($checkGender[$bus][$prevSeat]) && $userData['gender'] != $checkGender[$bus][$prevSeat]) {
          if (empty($userData['gender'])) $result['class'] = 'seat'; else $result['class'] = '';
          $result['nickname'] = $message;
          //$result['nickname'] = !empty($message) ? $message : $checkGender[$bus][$prevSeat] == 'M' ? '<span class="text-primary">남성우선</span>' : '<span class="text-danger">여성우선</span>';
        }
      }
    }
    */

    /* 여성우선 관련 알고리즘 - 우등버스 대응 (2021/03/29) */
    if ($result['idx'] == '') {
      if ($status == STATUS_CLOSED) {
        // 종료된 산행
        $message = '';
      } else {
        // 붙어있는 좌석은 같은 성별로만
        if ($userData['gender'] == 'F') $message = '<span class="text-primary">남성우선</span>';
        elseif ($userData['gender'] == 'M') $message = '<span class="text-danger">여성우선</span>';
        else $message = '예약가능';
      }
      if ($seat <= $seatType-4) {
        if ($seat %3 == 1) {
          // 현재 좌석은 홀수
          $nextSeat = $seat + 1;
          if (!empty($checkGender[$bus][$nextSeat]) && $userData['gender'] != $checkGender[$bus][$nextSeat]) {
            if (empty($userData['gender'])) $result['class'] = 'seat'; else $result['class'] = '';
            $result['nickname'] = $message;
          }
        } elseif ($seat %3 == 2) {
          // 현재 좌석은 짝수
          $prevSeat = $seat - 1;
          if (!empty($checkGender[$bus][$prevSeat]) && $userData['gender'] != $checkGender[$bus][$prevSeat]) {
            if (empty($userData['gender'])) $result['class'] = 'seat'; else $result['class'] = '';
            $result['nickname'] = $message;
          }
        }
      } else {
        if ($seat %3 == 0) {
          // 현재 좌석은 홀수
          $prevSeat = $seat - 1;
          $nextSeat = $seat + 1;
          if (!empty($checkGender[$bus][$nextSeat]) && $userData['gender'] != $checkGender[$bus][$nextSeat]) {
            if (empty($userData['gender'])) $result['class'] = 'seat'; else $result['class'] = '';
            $result['nickname'] = $message;
          } elseif (!empty($checkGender[$bus][$prevSeat]) && $userData['gender'] != $checkGender[$bus][$prevSeat]) {
            if (empty($userData['gender'])) $result['class'] = 'seat'; else $result['class'] = '';
            $result['nickname'] = $message;
          }
        } elseif ($seat %3 == 1) {
          // 현재 좌석은 홀수
          $prevSeat = $seat - 1;
          $nextSeat = $seat + 1;
          if (!empty($checkGender[$bus][$nextSeat]) && $userData['gender'] != $checkGender[$bus][$nextSeat]) {
            if (empty($userData['gender'])) $result['class'] = 'seat'; else $result['class'] = '';
            $result['nickname'] = $message;
          } elseif (!empty($checkGender[$bus][$prevSeat]) && $userData['gender'] != $checkGender[$bus][$prevSeat]) {
            if (empty($userData['gender'])) $result['class'] = 'seat'; else $result['class'] = '';
            $result['nickname'] = $message;
          }
        } elseif ($seat %3 == 2) {
          // 현재 좌석은 짝수
          $prevSeat = $seat - 1;
          $nextSeat = $seat + 1;
          if (!empty($checkGender[$bus][$nextSeat]) && $userData['gender'] != $checkGender[$bus][$nextSeat]) {
            if (empty($userData['gender'])) $result['class'] = 'seat'; else $result['class'] = '';
            $result['nickname'] = $message;
          } elseif (!empty($checkGender[$bus][$prevSeat]) && $userData['gender'] != $checkGender[$bus][$prevSeat]) {
            if (empty($userData['gender'])) $result['class'] = 'seat'; else $result['class'] = '';
            $result['nickname'] = $message;
          }
        }
      }
    }

    return $result;
  }
}

// 예약자 정보 확인
if (!function_exists('getCheck')) {
  function getCheck($reserve, $bus, $seat) {
    $result = array('idx' => '', 'nickname' => '', 'class' => '');
    foreach ($reserve as $value) {
      if ($value['bus'] == $bus && $value['seat'] == $seat) {
        if ($value['gender'] == 'M') $value['class'] = ' male';
        elseif ($value['gender'] == 'F') $value['class'] = ' female';
        $result = $value;
      }
    }
    return $result;
  }
}

// 단체 유형
if (!function_exists('getClubType')) {
  function getClubType($data) {
    $arr = '';
    $result = '';    
    if (strlen($data) >= 3) {
      $arr = unserialize($data);
    }

    if ($arr != '') {
      foreach ($arr as $value) {
        if ($result != '') $result .= ' / ';
        switch ($value) {
          case '1':
            $result .= '친목';
          break;
          case '2':
            $result .= '안내';
          break;
          case '3':
            $result .= '동호회';
          break;
          case '4':
            $result .= '여행사';
          break;
        }
      }
    }
    return $result;
  }
}

// 제공사항
if (!function_exists('getClubOption')) {
  function getClubOption($data) {
    $arr = '';
    $result = '';    
    if (strlen($data) >= 3) {
      $arr = unserialize($data);
    }

    if ($arr != '') {
      foreach ($arr as $value) {
        if ($result != '') $result .= ' / ';
        switch ($value) {
          case '1':
            $result .= '조식';
          break;
          case '2':
            $result .= '중식';
          break;
          case '3':
            $result .= '석식';
          break;
          case '4':
            $result .= '하산주';
          break;
          case '5':
            $result .= '산행지도';
          break;
          case '6':
            $result .= '기념품';
          break;
        }
      }
    }
    return $result;
  }
}

// 운행주간
if (!function_exists('getClubCycle')) {
  function getClubCycle($data) {
    $arr = '';
    $result = '';    
    if (strlen($data) >= 3) {
      $arr = unserialize($data);
    }

    if ($arr != '') {
      foreach ($arr as $value) {
        if ($result != '') $result .= ' / ';
        switch ($value) {
          case '1':
            $result .= '1주';
          break;
          case '2':
            $result .= '2주';
          break;
          case '3':
            $result .= '3주';
          break;
          case '4':
            $result .= '4주';
          break;
          case '5':
            $result .= '5주';
          break;
        }
      }
    }
    return $result;
  }
}

// 운행시기
if (!function_exists('getClubWeek')) {
  function getClubWeek($data) {
    $arr = '';
    $result = '';    
    if (strlen($data) >= 3) {
      $arr = unserialize($data);
    }

    if ($arr != '') {
      foreach ($arr as $value) {
        if ($result != '') $result .= ' / ';
        switch ($value) {
          case '1':
            $result .= '월';
          break;
          case '2':
            $result .= '화';
          break;
          case '3':
            $result .= '수';
          break;
          case '4':
            $result .= '목';
          break;
          case '5':
            $result .= '금';
          break;
          case '6':
            $result .= '토';
          break;
          case '7':
            $result .= '일';
          break;
        }
      }
    }
    return $result;
  }
}

// 승하차 위치
if (!function_exists('getClubGetonoff')) {
  function getClubGetonoff($data) {
    $arr = '';
    $result = '';    
    if (strlen($data) >= 3) {
      $arr = unserialize($data);
    }

    if ($arr != '') {
      foreach ($arr as $value) {
        if ($value != '') {
          if ($result != '') $result .= ' / ';
          $result .= $value;
        }
      }
    }
    return $result;
  }
}

// 로그 기록
if (!function_exists('setHistory')) {
  function setHistory($clubIdx, $action, $fkey, $userIdx, $nickname, $subject, $regdate, $point=NULL) {
    $data = array(
      'club_idx' => $clubIdx,
      'action' => $action,
      'fkey' => $fkey,
      'user_idx' => $userIdx,
      'nickname' => $nickname,
      'subject' => $subject,
      'regdate' => $regdate,
    );
    if (!is_null($point)) {
      $data['point'] = $point;
    }
    $GLOBALS['CI']->member_model->insertHistory($data);
  }
}

// 회원 레벨 계산
if (!function_exists('memberLevel')) {
  function memberLevel($rescount, $penalty, $level, $admin) {
    // 예약횟수 체크
    $result['level'] = $rescount - $penalty;

    // 관리자 이외
    if ($admin != 1 && $level != 1) {
      if ($result['level'] >= 10 && $result['level'] <= 29) {
        $result['levelType'] = 2;
        $result['levelName'] = '두그루 회원';
        $result['point'] = 2000;
      } elseif ($result['level'] >= 30 && $result['level'] <= 49) {
        $result['levelType'] = 3;
        $result['levelName'] = '세그루 회원';
        $result['point'] = 3000;
      } elseif ($result['level'] >= 50 && $result['level'] <= 99) {
        $result['levelType'] = 4;
        $result['levelName'] = '네그루 회원';
        $result['point'] = 4000;
      } elseif ($result['level'] >= 100) {
        $result['levelType'] = 5;
        $result['levelName'] = '다섯그루 회원';
        $result['point'] = 5000;
      } else {
        $result['levelType'] = 1;
        $result['levelName'] = '한그루 회원';
        $result['point'] = 1000;
      }
    } elseif ($admin == '1') {
      $result['levelType'] = 9;
      $result['levelName'] = '관리자';
      $result['point'] = 0;
    } elseif ($level == '1') {
      $result['levelType'] = 8;
      $result['levelName'] = '평생회원';
      $result['point'] = 1000;
    } elseif ($level == '2') {
      $result['levelType'] = 7;
      $result['levelName'] = '무료회원';
      $result['point'] = 0;
    } elseif ($level == '3') {
      $result['levelType'] = 6;
      $result['levelName'] = '드라이버';
      $result['point'] = 0;
    } else {
      $result['levelType'] = 0;
      $result['levelName'] = '비회원';
      $result['point'] = 0;
    }

    return $result;
  }
}

// 스토리 댓글 작성 시간 계산
if (!function_exists('calcStoryTime')) {
  function calcStoryTime($date) {
    $diff = time() - $date;
    $s = 60;
    $h = $s * 60;
    $d = $h * 24;
    $y = $d * 30;

    if ($diff < $s) {
      $result = gmdate('s', $diff)+0 . '초전';
    } elseif ($h > $diff && $diff >= $s) {
      $result = round($diff/$s) . '분전';
    } elseif ($d > $diff && $diff >= $h) {
      $result = round($diff/$h) . '시간전';
    } elseif ($y > $diff && $diff >= $d) {
      $result = round($diff/$d) . '일전';
    } else {
      $result = date('Y년 m월 d일', $date);
    }

    return $result;
  }
}

// 구매 입금체크
if (!function_exists('getPurchaseStatus')) {
  function getPurchaseStatus($status) {
    switch ($status) {
      case ORDER_PAY: $result = '<strong class="text-info">[입금완료]</strong>'; break;
      case ORDER_CANCEL: $result = '<strong class="text-secondary">[구매취소]</strong>'; break;
      case ORDER_END: $result = '<strong class="text-primary">[인수완료]</strong>'; break;
      default: $result = '<strong class="text-danger">[입금대기]</strong>';
    }
    return $result;
  }
}

// 대기자 확인
if (!function_exists('checkWait')) {
  function checkWait($clubIdx, $noticeIdx, $userIdx) {
    $result = $GLOBALS['CI']->reserve_model->viewReserveWait($clubIdx, $noticeIdx, $userIdx);
    return $result['created_at'];
  }
}

// 대기자 카운트
if (!function_exists('cntWait')) {
  function cntWait($noticeIdx) {
    $result = $GLOBALS['CI']->reserve_model->cntReserveWait($noticeIdx);
    return $result['cnt'];
  }
}

// 성별
if (!function_exists('getGender')) {
  function getGender($gender) {
    if ($gender == 'M') {
      $result = '남성';
    } elseif ($gender == 'F') {
      $result = '여성';
    }
    return $result;
  }
}

// 브라우저 종류
if (!function_exists('getUserAgent')) {
  function getUserAgent($agent) {
    $result = '';

    if (strstr($agent, 'Windows'))      $result .= '윈도우즈 ';
    if (strstr($agent, 'Android'))      $result .= '안드로이드 ';
    if (strstr($agent, 'iPhone'))       $result .= '아이폰 ';
    if (strstr($agent, 'SM-G'))         $result .= '갤럭시 ';
    if (strstr($agent, 'Chrome'))       $result .= '구글 크롬 브라우저 ';
    elseif (strstr($agent, 'Safari'))   $result .= '애플 사파리 브라우저 ';
    if (strstr($agent, 'MSIE 10'))  $result .= 'MS 인터넷 익스플로러 10';
    elseif (strstr($agent, 'MSIE 9'))   $result .= 'MS 인터넷 익스플로러 9';
    elseif (strstr($agent, 'MSIE 8'))   $result .= 'MS 인터넷 익스플로러 8';
    elseif (strstr($agent, 'MSIE 7'))   $result .= 'MS 인터넷 익스플로러 7';
    elseif (strstr($agent, 'rv:11'))    $result .= 'MS 인터넷 익스플로러 11';
    if (strstr($agent, 'Firefox'))      $result .= '파이어폭스 ';
    if (strstr($agent, 'Googlebot'))    $result .= '구글 검색엔진 로봇 ';
    if (strstr($agent, 'msnbot'))       $result .= 'MS 검색엔진 로봇 ';
    if (strstr($agent, 'BingPreview'))  $result .= 'Bing 검색엔진 로봇 ';
    if (strstr($agent, 'bingbot'))      $result .= 'Bing 검색엔진 로봇 ';
    if (strstr($agent, 'yandex'))       $result .= 'Yandex 검색엔진 로봇 ';
    if (strstr($agent, 'MJ12bot'))      $result .= 'MJ12BOT 검색엔진 로봇 ';
    if (strstr($agent, 'DaumApps'))     $result .= '다음앱 ';
    if (strstr($agent, 'AppleWebKit') && strstr($agent, 'Mobile/15E148')) $result .= '다음앱 ';
    if (strstr($agent, 'kakaotalk'))    $result .= '카카오톡 ';
    if (strstr($agent, 'facebook'))     $result .= '페이스북 ';
    if (empty($result)) {
      $result = strlen($agent) > 35 ? substr($agent, 0, 35) . '...' : $agent;
    }

    return $result;
  }
}

// 기사 콘텐츠 깔끔하게 가져오기
if (!function_exists('articleContent')) {
  function articleContent($content, $limit=100) {
    $content = ksubstr(trim(str_replace('&nbsp;', ' ', strip_tags(reset_html_escape($content)))), $limit);
    return $content;
  }
}

// 기사 썸네일 가져오기
if (!function_exists('getThumbnail')) {
  function getThumbnail($content) {
    preg_match("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", reset_html_escape($content), $match);
    if (empty($match[1])) {
      //$match[1] = '/public/images/noimage.png';
      $match[1] = '';
    } else {
      $match[1] = str_replace('/article/', '/article/thumb_', $match[1]);
    }
    return $match[1];
  }
}

// 기사 댓글수 가져오기
if (!function_exists('cntReply')) {
  function cntReply($idx) {
    $CI =& get_instance();
    $result = $GLOBALS['CI']->story_model->cntStoryReply($idx, REPLY_TYPE_NOTICE);
    return $result['cnt'];
  }
}
?>
