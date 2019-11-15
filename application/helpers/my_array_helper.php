<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$CI = get_instance();
$CI->load->model(array('admin_model', 'member_model'));

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
  function make_serialize($data) {
    foreach ($data as $value) {
      if ($value != '') {
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

// 사용자 로그인 확인
if (!function_exists('checkUserLogin')) {
  function checkUserLogin() {
    $CI =& get_instance();
    $clubIdx = $CI->load->get_var('clubIdx');
    $userData = $CI->load->get_var('userData');
    if (empty($userData['idx'])) {
      redirect(base_url() . 'login/' . $clubIdx . '?r=' . $_SERVER['REQUEST_URI']);
    }
  }
}

// 관리자 로그인 확인
if (!function_exists('checkAdminLogin')) {
  function checkAdminLogin() {
    $CI =& get_instance();
    $clubIdx = $CI->load->get_var('clubIdx');
    $userData = $CI->load->get_var('userData');
    if (empty($userData['idx']) || (!empty($userData['admin']) && $clubIdx != $userData['club_idx'])) {
      redirect(base_url() . 'login/' . $clubIdx . '?r=' . $_SERVER['REQUEST_URI']);
    }
  }
}

// 산행 상태
if (!function_exists('viewStatus')) {
  function viewStatus($status=0) {
    switch ($status) {
      case '2': $result = '출발확정'; break;
      case '8': $result = '취소'; break;
      case '9': $result = '종료'; break;
      default : $result = '출발예정';
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
  function cntRes($rescode) {
    $result = $GLOBALS['CI']->admin_model->cntReservation($rescode);
    return $result['CNT'];
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
      $dTerm = date('j', strtotime($endDate) - strtotime($startDate));

      if ($sTime[0].$sTime[1] <= '2200') {
        // 22시 이전 출발은 1박 확정
        $result = $dTerm . '박 ' . ($dTerm + 1) . '일';
      } else {
        // 22시 이후 출발은 1일 무박
        $result = '1무' . ($dTerm - 1) . '박 ' . ($dTerm + 1) . '일';
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

// 거리, 산행분담금
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

// 거리, 산행분담금
if (!function_exists('calcDistance')) {
  function calcDistance($distance) {
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
    }
    return $result;
  }
}

// 산행 상태 표시
if (!function_exists('viewNoticeStatus')) {
  function viewNoticeStatus($status) {
    switch ($status) {
      case '1': // 예정
        $result = '[예정]';
      break;
      case '2': // 확정
        $result = '[확정]';
      break;
      case '8': // 취소
        $result = '[취소]';
      break;
      case '9': // 종료
        $result = '[종료]';
      break;
      default: // 무박
        $result = '';
    }
    return $result;
  }
}

// 승차위치
if (!function_exists('arrLocation')) {
  function arrLocation() {
    return array(
      '',
      '계산역 4번출구',
      '작전역 5번출구',
      '갈산역 4번출구',
      '부평구청역 4번출구',
      '삼산체육관 맞은편',
      '부천터미널 소풍',
      '복사골 문화센터',
      '송내남부 맥도날드',
      '원종동'
    );
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

// 해당 산행에 등록된 버스
if (!function_exists('getBusType')) {
  function getBusType($busType, $bus) {
    $result = array();
    if (is_null($busType)) {
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
  function getBusTableMake($seatType, $direction, $seat) {
    $result = '';
    if ($seat == 1) $result = '<tr>';
    switch ($seatType) {
      case '40': // 40번 순방향
      case '44': // 44번 순방향
        if ($seat != 1 && $seat%4 == 1) $result = '</tr><tr>';
        if ($seat%4 == 3) $result = '<td colspan="2" class="table-blank"></td>';
        break;
      case '41': // 41번 순방향
        if ($seat != 1 && $seat%4 == 1 && $seat < 39) $result = '</tr><tr>';
        if ($seat%4 == 3 && $seat < 39) $result = '<td colspan="2" class="table-blank"></td>';
        break;
    }
    return $result;
  }
}

// 예약자 정보 (관리용)
if (!function_exists('getAdminReserve')) {
  function getAdminReserve($reservation, $bus, $seat) {
    $result = array('idx' => '', 'nickname' => '예약가능', 'class' => '');
    foreach ($reservation as $value) {
      if ($value['bus'] == $bus && $value['seat'] == $seat) {
        if ($value['gender'] == 'M') $value['class'] = ' male';
        elseif ($value['gender'] == 'F') $value['class'] = ' female';
        $result = $value;
      }
    }
    return $result;
  }
}

// 예약자 정보
if (!function_exists('getReserve')) {
  function getReserve($reservation, $bus, $seat, $userData) {
    $result = array('idx' => '', 'userid' => '', 'nickname' => '예약가능', 'class' => 'seat ');
    foreach ($reservation as $key => $value) {
      if ($value['bus'] == $bus && $value['seat'] == $seat) {
        if ($userData['userid'] == $value['userid']) {
          $value['class'] = 'seat ';
        } else {
          $value['class'] = '';
        }
        $value['class'] .= 'reserved';
        $result = $value;
      }
      $checkGender[$value['bus']][$value['seat']] = $value['gender'];
    }
    if ($result['idx'] == '') {
      // 붙어있는 좌석은 같은 성별로만
      $message = "<span class='text-danger'>예약불가</span>";
      if ($seat %2 == 1) {
        // 현재 좌석은 홀수
        $nextSeat = $seat + 1;
        if (!empty($checkGender[$bus][$nextSeat]) && $userData['gender'] != $checkGender[$bus][$nextSeat]) {
          $result['class'] = '';
          $result['nickname'] = $message;
        }
      } elseif ($seat %2 == 0) {
        // 현재 좌석은 짝수
        $prevSeat = $seat - 1;
        if (!empty($checkGender[$bus][$prevSeat]) && $userData['gender'] != $checkGender[$bus][$prevSeat]) {
          $result['class'] = '';
          $result['nickname'] = $message;
        }
      }
    }
    return $result;
  }
}

// 예약자 정보 확인
if (!function_exists('getCheck')) {
  function getCheck($reservation, $bus, $seat) {
    $result = array('idx' => '', 'nickname' => '', 'class' => '');
    foreach ($reservation as $value) {
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
  function setHistory($action, $fkey, $userid, $subject, $regdate, $point=NULL) {
    /*
      action
      1 - 회원가입
      2 - 산행예약
      3 - 산행취소
      4 - 포인트 적립
      5 - 포인트 감소
      6 - 페널티 추가
      7 - 페널티 감소
      8 - 관리자 예약
      9 - 관리자 취소
    */
    $data = array(
      'action' => $action,
      'fkey' => $fkey,
      'userid' => $userid,
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
  function memberLevel($userData) {
    // 예약횟수 체크
    $CI =& get_instance();
    $CI->load->model('member_model');
    $reserveGroup = $CI->member_model->cntReserve($userData, 1, 1);
    $result = $CI->member_model->cntReserve($userData, 1);

    // 페널티 횟수 차감 = 레벨
    $result['level'] = $result['cntReserve'] - $userData['penalty'];

    // 산행 횟수 (예약 그룹)
    $result['cntNotice'] = number_format($reserveGroup['cntReserve']);

    // 관리자 이외
    if ($userData['admin'] != 1 && $userData['level'] != 1) {
      if ($result['level'] >= 10 && $result['level'] <= 29) {
        $result['levelType'] = 2;
        $result['levelName'] = '두그루 회원';
        $result['point'] = 2000;
      } else if ($result['level'] >= 30 && $result['level'] <= 49) {
        $result['levelType'] = 3;
        $result['levelName'] = '세그루 회원';
        $result['point'] = 3000;
      } else if ($result['level'] >= 50 && $result['level'] <= 99) {
        $result['levelType'] = 4;
        $result['levelName'] = '네그루 회원';
        $result['point'] = 4000;
      } else if ($result['level'] >= 100) {
        $result['levelType'] = 5;
        $result['levelName'] = '다섯그루 회원';
        $result['point'] = 5000;
      } else {
        $result['levelType'] = 1;
        $result['levelName'] = '한그루 회원';
        $result['point'] = 1000;
      }
    } else if ($userData['level'] == '1') {
      $result['levelType'] = 8;
      $result['levelName'] = '평생회원';
      $result['point'] = 1000;
    } else if ($userData['admin'] == '1') {
      $result['levelType'] = 9;
      $result['levelName'] = '관리자';
      $result['point'] = 0;
    } else {
      $result['levelType'] = 0;
      $result['levelName'] = '비회원';
      $result['point'] = 0;
    }

    return $result;
  }
}
?>
