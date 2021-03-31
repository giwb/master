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

// 업로드 디렉토리
defined('UPLOAD_URL')           OR define('UPLOAD_URL', '/public/uploads/');
defined('UPLOAD_PATH')          OR define('UPLOAD_PATH', BASE_PATH . UPLOAD_URL);

// 에디터 업로드 디렉토리
defined('EDITOR_URL')           OR define('EDITOR_URL', '/public/uploads/editor/');
defined('EDITOR_PATH')          OR define('EDITOR_PATH', BASE_PATH . EDITOR_URL);

// 산악회 업로드 디렉토리
defined('UPLOAD_CLUB_URL')      OR define('UPLOAD_CLUB_URL', UPLOAD_URL . 'club/');
defined('UPLOAD_CLUB_PATH')     OR define('UPLOAD_CLUB_PATH', BASE_PATH . UPLOAD_CLUB_URL);

// 사진 디렉토리
defined('PHOTO_URL')            OR define('PHOTO_URL', '/public/photos/');
defined('PHOTO_PATH')           OR define('PHOTO_PATH', BASE_PATH . PHOTO_URL);

// 사진첩(앨범) 디렉토리
defined('ALBUM_URL')            OR define('ALBUM_URL', '/public/album/');
defined('ALBUM_PATH')           OR define('ALBUM_PATH', BASE_PATH . ALBUM_URL);

// 기사 사진 업로드 디렉토리
defined('PHOTO_ARTICLE_URL')    OR define('PHOTO_ARTICLE_URL', PHOTO_URL . 'article/');
defined('PHOTO_ARTICLE_PATH')   OR define('PHOTO_ARTICLE_PATH', BASE_PATH . PHOTO_ARTICLE_URL);

// 여행정보 사진 업로드 디렉토리
defined('PHOTO_PLACE_URL')      OR define('PHOTO_PLACE_URL', PHOTO_URL . 'place/');
defined('PHOTO_PLACE_PATH')     OR define('PHOTO_PLACE_PATH', BASE_PATH . PHOTO_PLACE_URL);

// 현지영상 썸네일 업로드 디렉토리
defined('CCTV_THUMBNAIL_URL')   OR define('CCTV_THUMBNAIL_URL', UPLOAD_URL . 'cctv/');
defined('CCTV_THUMBNAIL_PATH')  OR define('CCTV_THUMBNAIL_PATH', BASE_PATH . CCTV_THUMBNAIL_URL);

// 사용자 아바타 사진
defined('AVATAR_URL')           OR define('AVATAR_URL', PHOTO_URL . 'avatar/');
defined('AVATAR_PATH')          OR define('AVATAR_PATH', BASE_PATH . AVATAR_URL);

// 대표사진
defined('FRONT_URL')            OR define('FRONT_URL', '/public/uploads/front/');
defined('FRONT_PATH')           OR define('FRONT_PATH', BASE_PATH . FRONT_URL);

// 테이블명
defined('DB_ADJUST')            OR define('DB_ADJUST', 'adjust'); // 정산
defined('DB_ALBUM')             OR define('DB_ALBUM', 'album'); // 사진첩
defined('DB_ATTENDANCE')        OR define('DB_ATTENDANCE', 'attendance'); // 출석
defined('DB_AREAS')             OR define('DB_AREAS', 'areas'); // 지역
defined('DB_ARTICLE')           OR define('DB_ARTICLE', 'article'); // 한국여행 기사
defined('DB_ARTICLE_CATEGORY')  OR define('DB_ARTICLE_CATEGORY', 'article_category'); // 한국여행 기사 카테고리
defined('DB_ARTICLE_REACTION')  OR define('DB_ARTICLE_REACTION', 'article_reaction'); // 한국여행 리액션
defined('DB_ARTICLE_REPLY')     OR define('DB_ARTICLE_REPLY', 'article_reply'); // 한국여행 리액션
defined('DB_AUTH')              OR define('DB_AUTH', 'auth'); // 백산백소 인증
defined('DB_BOARD')             OR define('DB_BOARD', 'board'); // 안부방 (OLD)
defined('DB_BOOKMARKS')         OR define('DB_BOOKMARKS', 'bookmark'); // 북마크
defined('DB_BUSDATA')           OR define('DB_BUSDATA', 'busdata'); // 버스 좌석 데이터 (기본)
defined('DB_BUSTYPE')           OR define('DB_BUSTYPE', 'bustype'); // 버스 형태
defined('DB_CALENDAR')          OR define('DB_CALENDAR', 'calendar'); // 캘린더 휴일 (관리자 작성)
defined('DB_CCTVS')             OR define('DB_CCTVS', 'cctv'); // 실시간 현지영상
defined('DB_CCTVS_CATEGORY')    OR define('DB_CCTVS_CATEGORY', 'cctv_category'); // 실시간 현지영상 카테고리
defined('DB_CLUBS')             OR define('DB_CLUBS', 'clubs'); // 클럽
defined('DB_CLUB_DETAIL')       OR define('DB_CLUB_DETAIL', 'club_detail'); // 클럽 소개페이지
defined('DB_FILES')             OR define('DB_FILES', 'files'); // 파일
defined('DB_FRONT')             OR define('DB_FRONT', 'front'); // 대문사진 (OLD)
defined('DB_HISTORY')           OR define('DB_HISTORY', 'history'); // 활동기록
defined('DB_MEMBER')            OR define('DB_MEMBER', 'member'); // 회원
defined('DB_MEMBER_SMS_AUTH')   OR define('DB_MEMBER_SMS_AUTH', 'member_sms_auth'); // 회원 휴대폰 인증번호
defined('DB_MTDB')              OR define('DB_MTDB', 'mtdb'); // 산행DB
defined('DB_NOTICE')            OR define('DB_NOTICE', 'notice'); // 산행공지
defined('DB_NOTICE_DETAIL')     OR define('DB_NOTICE_DETAIL', 'notice_detail'); // 산행공지 상세
defined('DB_PLACES')            OR define('DB_PLACES', 'places'); // 산행정보
defined('DB_PLACES_CATEGORY')   OR define('DB_PLACES_CATEGORY', 'places_category'); // 산행정보 카테고리
defined('DB_RESERVATION')       OR define('DB_RESERVATION', 'reservation'); // 예약
defined('DB_SCHEDULES')         OR define('DB_SCHEDULES', 'schedule'); // 여행일정
defined('DB_STORY')             OR define('DB_STORY', 'story'); // 스토리
defined('DB_STORY_REACTION')    OR define('DB_STORY_REACTION', 'story_reaction'); // 스토리 좋아요/공유
defined('DB_STORY_REPLY')       OR define('DB_STORY_REPLY', 'story_reply'); // 스토리 댓글
defined('DB_SHOP')              OR define('DB_SHOP', 'shop'); // 판매대행
defined('DB_SHOP_CATEGORY')     OR define('DB_SHOP_CATEGORY', 'shop_category'); // 판매대행 분류
defined('DB_SHOP_PURCHASE')     OR define('DB_SHOP_PURCHASE', 'shop_purchase'); // 판매대행 주문내역
defined('DB_VIDEOS')            OR define('DB_VIDEOS', 'video'); // 산행기 - 동영상
defined('DB_VISITOR')           OR define('DB_VISITOR', 'visitor'); // 방문자
defined('DB_WAIT')              OR define('DB_WAIT', 'wait'); // 대기자 명단
defined('DB_WEATHER')           OR define('DB_WEATHER', 'weather'); // 날씨

// 사진 형식
defined('TYPE_MAIN')          OR define('TYPE_MAIN', 1);
defined('TYPE_ADDED')         OR define('TYPE_ADDED', 2);
defined('TYPE_MAP')           OR define('TYPE_MAP', 3);

// 산행 상태
defined('STATUS_PLAN')        OR define('STATUS_PLAN', 0); // 계획
defined('STATUS_ABLE')        OR define('STATUS_ABLE', 1); // 예정
defined('STATUS_CONFIRM')     OR define('STATUS_CONFIRM', 2); // 확정
defined('STATUS_CANCEL')      OR define('STATUS_CANCEL', 8); // 취소
defined('STATUS_CLOSED')      OR define('STATUS_CLOSED', 9); // 종료

// 예약 상태
defined('RESERVE_ON')         OR define('RESERVE_ON', 0); // 예약
defined('RESERVE_PAY')        OR define('RESERVE_PAY', 1); // 입금
defined('RESERVE_WAIT')       OR define('RESERVE_WAIT', 9); // 대기

// 구매 상태
defined('ORDER_ON')           OR define('ORDER_ON', 0); // 구매 (미입금)
defined('ORDER_PAY')          OR define('ORDER_PAY', 1); // 입금완료
defined('ORDER_CANCEL')       OR define('ORDER_CANCEL', 8); // 구매취소
defined('ORDER_END')          OR define('ORDER_END', 9); // 판매완료

// 산행 숨김
defined('VISIBLE_NONE')       OR define('VISIBLE_NONE', 0); // 숨김
defined('VISIBLE_ABLE')       OR define('VISIBLE_ABLE', 1); // 공개

// 스토리 리액션 형태
defined('REACTION_KIND_LIKE')   OR define('REACTION_KIND_LIKE', 1); // 좋아요
defined('REACTION_KIND_SHARE')  OR define('REACTION_KIND_SHARE', 2); // 공유하기

// 스토리 공유 형태
defined('SHARE_TYPE_URL')       OR define('SHARE_TYPE_URL', 1); // 페이스북
defined('SHARE_TYPE_FACEBOOK')  OR define('SHARE_TYPE_FACEBOOK', 2); // 페이스북
defined('SHARE_TYPE_TWITTER')   OR define('SHARE_TYPE_TWITTER', 3); // 트위터
defined('SHARE_TYPE_KAKAO')     OR define('SHARE_TYPE_KAKAO', 4); // 카카오톡
defined('SHARE_TYPE_DAUM')      OR define('SHARE_TYPE_DAUM', 5); // 다음

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
defined('LOG_ENTRY')                      OR define('LOG_ENTRY', 1);
defined('LOG_RESERVE')                    OR define('LOG_RESERVE', 2);
defined('LOG_CANCEL')                     OR define('LOG_CANCEL', 3);
defined('LOG_POINTUP')                    OR define('LOG_POINTUP', 4);
defined('LOG_POINTDN')                    OR define('LOG_POINTDN', 5);
defined('LOG_PENALTYUP')                  OR define('LOG_PENALTYUP', 6);
defined('LOG_PENALTYDN')                  OR define('LOG_PENALTYDN', 7);
defined('LOG_ADMIN_RESERVE')              OR define('LOG_ADMIN_RESERVE', 8);
defined('LOG_ADMIN_CANCEL')               OR define('LOG_ADMIN_CANCEL', 9);
defined('LOG_ADMIN_DEPOSIT_CONFIRM')      OR define('LOG_ADMIN_DEPOSIT_CONFIRM', 10);
defined('LOG_ADMIN_DEPOSIT_CANCEL')       OR define('LOG_ADMIN_DEPOSIT_CANCEL', 11);
defined('LOG_ADMIN_REFUND')               OR define('LOG_ADMIN_REFUND', 12);
defined('LOG_SHOP_BUY')                   OR define('LOG_SHOP_BUY', 21);
defined('LOG_SHOP_CHECKOUT')              OR define('LOG_SHOP_CHECKOUT', 22);
defined('LOG_SHOP_CANCEL')                OR define('LOG_SHOP_CANCEL', 23);
defined('LOG_ADMIN_SHOP_BUY')             OR define('LOG_ADMIN_SHOP_BUY', 24);
defined('LOG_ADMIN_SHOP_CANCEL')          OR define('LOG_ADMIN_SHOP_CANCEL', 25);
defined('LOG_ADMIN_SHOP_DEPOSIT_CONFIRM') OR define('LOG_ADMIN_SHOP_DEPOSIT_CONFIRM', 26);
defined('LOG_ADMIN_SHOP_DEPOSIT_CANCEL')  OR define('LOG_ADMIN_SHOP_DEPOSIT_CANCEL', 27);
defined('LOG_ADMIN_SHOP_COMPLETE')        OR define('LOG_ADMIN_SHOP_COMPLETE', 28);
defined('LOG_DRIVER_CHANGE')              OR define('LOG_DRIVER_CHANGE', 51);

// 레벨 형태
defined('LEVEL_NORMAL')                   OR define('LEVEL_NORMAL', 0);   // 일반회원
defined('LEVEL_LIFETIME')                 OR define('LEVEL_LIFETIME', 1); // 평생회원
defined('LEVEL_FREE')                     OR define('LEVEL_FREE', 2);     // 무료회원
defined('LEVEL_DRIVER')                   OR define('LEVEL_DRIVER', 3);   // 드라이버
defined('LEVEL_DRIVER_ADMIN')             OR define('LEVEL_DRIVER_ADMIN', 4); // 드라이버 관리자
defined('LEVEL_BLACKLIST')                OR define('LEVEL_BLACKLIST', 9); // 블랙리스트

// 댓글 형태
defined('REPLY_TYPE_STORY')               OR define('REPLY_TYPE_STORY', 1);
defined('REPLY_TYPE_NOTICE')              OR define('REPLY_TYPE_NOTICE', 2);
defined('REPLY_TYPE_SHOP')                OR define('REPLY_TYPE_SHOP', 3);

// 공유 형태
defined('REACTION_TYPE_STORY')            OR define('REACTION_TYPE_STORY', 1);
defined('REACTION_TYPE_NOTICE')           OR define('REACTION_TYPE_NOTICE', 2);
defined('REACTION_TYPE_SHOP')             OR define('REACTION_TYPE_SHOP', 3);

// 기사 리액션 형태
defined('REACTION_TYPE_REFER')            OR define('REACTION_TYPE_REFER', 1); // 기사 조회수
defined('REACTION_TYPE_LIKED')            OR define('REACTION_TYPE_LIKED', 2); // 기사 좋아요

// 쿠키 시간 (1년)
define('COOKIE_STRAGE_PERIOD', 60 * 60 * 24 * 30 * 12);

// Providr
defined('PROVIDER_NONE')  OR define('PROVIDER_NONE', 0);
defined('PROVIDER_KAKAO') OR define('PROVIDER_KAKAO', 1);
defined('PROVIDER_NAVER') OR define('PROVIDER_NAVER', 2);
defined('PROVIDER_GMAIL') OR define('PROVIDER_GMAIL', 3);
defined('PROVIDER_ADMIN') OR define('PROVIDER_ADMIN', 9); // 관리자 등록

// API
defined('API_KAKAO')      OR define('API_KAKAO', 'ac8c155b86aa3885c643ba50a7cd4442');
defined('API_KAKAO_JS')   OR define('API_KAKAO_JS', 'bc341ce483d209b1712bf3a88b598ddb');
defined('API_KAKAO_URL')  OR define('API_KAKAO_URL', 'login/kakao');
