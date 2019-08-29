<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$CI = get_instance();
$CI->load->model('admin_model');

// serialize
if (!function_exists('make_serialize'))
{
  function make_serialize($data)
  {
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

// 산행 예약자 카운트
if (!function_exists('cntRes')) {
  function cntRes($rescode) {
    $result = $GLOBALS['CI']->admin_model->cntReservation($rescode);
    return $result['CNT'];
  }
}

// 산행 종료일 계산
if (!function_exists('calcEndDate')) {
  function calcEndDate($startDate, $schedule) {
    switch ($schedule) {
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
    return strtotime($startDate . $addedDate);
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
?>
