<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$CI = get_instance();
$CI->load->model('admin_model');

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
      case '6': // 3박 5일
      case '7': // 4박 5일
        $addedDate = ' +4 day';
      default: // 무박
        $addedDate = '';
    }
    return strtotime($startDate . $addedDate);
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

?>
