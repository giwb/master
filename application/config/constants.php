<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
|--------------------------------------------------------------------------
| Custom Constants
|--------------------------------------------------------------------------
*/

// 테이블명 - 경인웰빙
defined('DB_ADJUST')      OR define('DB_ADJUST', 'giwb_adjust');
defined('DB_ATTENDANCE')  OR define('DB_ATTENDANCE', 'giwb_attendance');
defined('DB_AUTH')        OR define('DB_AUTH', 'giwb_auth');
defined('DB_BOARD')       OR define('DB_BOARD', 'giwb_board');
defined('DB_BUSDATA')     OR define('DB_BUSDATA', 'giwb_busdata');
defined('DB_BUSTYPE')     OR define('DB_BUSTYPE', 'giwb_bustype');
defined('DB_CALENDAR')    OR define('DB_CALENDAR', 'giwb_calendar');
defined('DB_FRONT')       OR define('DB_FRONT', 'giwb_front');
defined('DB_HISTORY')     OR define('DB_HISTORY', 'giwb_history');
defined('DB_MEMBER')      OR define('DB_MEMBER', 'giwb_member');
defined('DB_MTDB')        OR define('DB_MTDB', 'giwb_mtdb');
defined('DB_NOTICE')      OR define('DB_NOTICE', 'giwb_notice');
defined('DB_RESERVATION') OR define('DB_RESERVATION', 'giwb_reservation');
defined('DB_SCHEDULE')    OR define('DB_SCHEDULE', 'giwb_schedule');
defined('DB_SHOP')        OR define('DB_SHOP', 'giwb_shop');
defined('DB_SHOP_CATEGORY') OR define('DB_SHOP_CATEGORY', 'giwb_shop_category');
defined('DB_SHOP_PURCHASE') OR define('DB_SHOP_PURCHASE', 'giwb_shop_purchase');
defined('DB_VISITOR')     OR define('DB_VISITOR', 'giwb_visitor');
defined('DB_WAIT')        OR define('DB_WAIT', 'giwb_wait');

// 산행 상태
defined('STATUS_PLAN')    OR define('STATUS_PLAN', 0); // 계획
defined('STATUS_ABLE')    OR define('STATUS_ABLE', 1); // 예정
defined('STATUS_CONFIRM') OR define('STATUS_CONFIRM', 2); // 확정
defined('STATUS_CANCEL')  OR define('STATUS_CANCEL', 8); // 취소
defined('STATUS_CLOSED')  OR define('STATUS_CLOSED', 9); // 종료

// 예약 상태
defined('RESERVE_ON')     OR define('RESERVE_ON', 0); // 예약
defined('RESERVE_PAY')    OR define('RESERVE_PAY', 1); // 입금
defined('RESERVE_WAIT')   OR define('RESERVE_WAIT', 9); // 대기

// 구매 상태
defined('ORDER_ON')       OR define('ORDER_ON', 0); // 구매 (미입금)
defined('ORDER_PAY')      OR define('ORDER_PAY', 1); // 입금완료
defined('ORDER_CANCEL')   OR define('ORDER_CANCEL', 8); // 구매취소
defined('ORDER_END')      OR define('ORDER_END', 9); // 판매완료

// 산행 숨김
defined('VISIBLE_NONE')   OR define('VISIBLE_NONE', 0); // 숨김
defined('VISIBLE_ABLE')   OR define('VISIBLE_ABLE', 1); // 공개

// 스토리 리액션 형태
defined('REACTION_KIND_LIKE') OR define('REACTION_KIND_LIKE', 1); // 좋아요
defined('REACTION_KIND_SHARE') OR define('REACTION_KIND_SHARE', 2); // 공유하기

// 스토리 공유 형태
defined('SHARE_TYPE_URL') OR define('SHARE_TYPE_URL', 1); // 페이스북
defined('SHARE_TYPE_FACEBOOK') OR define('SHARE_TYPE_FACEBOOK', 2); // 페이스북
defined('SHARE_TYPE_TWITTER') OR define('SHARE_TYPE_TWITTER', 3); // 트위터
defined('SHARE_TYPE_KAKAO') OR define('SHARE_TYPE_KAKAO', 4); // 카카오톡
defined('SHARE_TYPE_DAUM') OR define('SHARE_TYPE_DAUM', 5); // 다음

// 로그 키
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
  10 - 관리자 입금확인
  11 - 관리자 입금취소
  12 - 비회원 환불내역
  21 - 용품판매 - 구매
  22 - 용품판매 - 결제
  23 - 용품판매 - 취소
  24 - 용품판매 관리자 - 구매
  25 - 용품판매 관리자 - 취소
  26 - 용품판매 관리자 - 입금확인
  27 - 용품판매 관리자 - 입금취소
  28 - 용품판매 관리자 - 판매완료
  51 - 드라이버 변경
*/
defined('LOG_ENTRY')          OR define('LOG_ENTRY', 1);
defined('LOG_RESERVE')        OR define('LOG_RESERVE', 2);
defined('LOG_CANCEL')         OR define('LOG_CANCEL', 3);
defined('LOG_POINTUP')        OR define('LOG_POINTUP', 4);
defined('LOG_POINTDN')        OR define('LOG_POINTDN', 5);
defined('LOG_PENALTYUP')      OR define('LOG_PENALTYUP', 6);
defined('LOG_PENALTYDN')      OR define('LOG_PENALTYDN', 7);
defined('LOG_ADMIN_RESERVE')  OR define('LOG_ADMIN_RESERVE', 8);
defined('LOG_ADMIN_CANCEL')   OR define('LOG_ADMIN_CANCEL', 9);
defined('LOG_ADMIN_DEPOSIT_CONFIRM')  OR define('LOG_ADMIN_DEPOSIT_CONFIRM', 10);
defined('LOG_ADMIN_DEPOSIT_CANCEL')   OR define('LOG_ADMIN_DEPOSIT_CANCEL', 11);
defined('LOG_ADMIN_REFUND')   OR define('LOG_ADMIN_REFUND', 12);
defined('LOG_SHOP_BUY')       OR define('LOG_SHOP_BUY', 21);
defined('LOG_SHOP_CHECKOUT')  OR define('LOG_SHOP_CHECKOUT', 22);
defined('LOG_SHOP_CANCEL')    OR define('LOG_SHOP_CANCEL', 23);
defined('LOG_ADMIN_SHOP_BUY') OR define('LOG_ADMIN_SHOP_BUY', 24);
defined('LOG_ADMIN_SHOP_CANCEL') OR define('LOG_ADMIN_SHOP_CANCEL', 25);
defined('LOG_ADMIN_SHOP_DEPOSIT_CONFIRM') OR define('LOG_ADMIN_SHOP_DEPOSIT_CONFIRM', 26);
defined('LOG_ADMIN_SHOP_DEPOSIT_CANCEL') OR define('LOG_ADMIN_SHOP_DEPOSIT_CANCEL', 27);
defined('LOG_ADMIN_SHOP_COMPLETE') OR define('LOG_ADMIN_SHOP_COMPLETE', 28);
defined('LOG_DRIVER_CHANGE')  OR define('LOG_DRIVER_CHANGE', 51);

// 경로 설정
defined('PATH_FRONT')         OR define('PATH_FRONT', PATH_MAIN . '/public/uploads/front/');
defined('URL_FRONT')          OR define('URL_FRONT', 'public/uploads/front/');

// 레벨 형태
defined('LEVEL_NORMAL')       OR define('LEVEL_NORMAL', 0);   // 일반회원
defined('LEVEL_LIFETIME')     OR define('LEVEL_LIFETIME', 1); // 평생회원
defined('LEVEL_FREE')         OR define('LEVEL_FREE', 2);     // 무료회원
defined('LEVEL_DRIVER')       OR define('LEVEL_DRIVER', 3);   // 드라이버
defined('LEVEL_DRIVER_ADMIN') OR define('LEVEL_DRIVER_ADMIN', 4); // 드라이버 관리자
defined('LEVEL_BLACKLIST')    OR define('LEVEL_BLACKLIST', 9); // 블랙리스트

// 댓글 형태
defined('REPLY_TYPE_STORY')   OR define('REPLY_TYPE_STORY', 1);
defined('REPLY_TYPE_NOTICE')  OR define('REPLY_TYPE_NOTICE', 2);

// 공유 형태
defined('REACTION_TYPE_STORY')   OR define('REACTION_TYPE_STORY', 1);
defined('REACTION_TYPE_NOTICE')  OR define('REACTION_TYPE_NOTICE', 2);

// 쿠키 시간 (1년)
define('COOKIE_STRAGE_PERIOD', 60 * 60 * 24 * 30 * 12);
