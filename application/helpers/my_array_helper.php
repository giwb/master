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
?>
