<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|   example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|   https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|   $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|   $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|   $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples: my-controller/index -> my_controller/index
|       my-controller/my-method -> my_controller/my_method
*/
$route['404_override']          = '';
$route['translate_uri_dashes']  = FALSE;

// route에서 데이터베이스 사용
require_once (BASEPATH."database/DB.php");
$db =& DB();

// 각 클럽 도메인별 이동
$domain = $_SERVER['HTTP_HOST'];
$query = $db->query("SELECT idx FROM clubs WHERE domain='$domain'");
$result = $query->row_array(1);

if (!empty($result)) {
  setcookie('COOKIE_CLUBIDX', $result['idx']);
} elseif (!empty($_SERVER['REDIRECT_URL'])) {
  $arrUrl = explode('/', $_SERVER['REDIRECT_URL']);
  $domain = html_escape($arrUrl[1]);
  $query = $db->query("SELECT idx FROM clubs WHERE domain='$domain'");
  $result = $query->row_array(1);

  if (!empty($result)) {
    setcookie('COOKIE_CLUBIDX', $result['idx']);
  }
}

if (!empty($result)) {
  // 도메인이 있을 경우
  $route['default_controller']                = 'club/index';
  $route[$domain]                             = 'club/index';
  $route[$domain . '/club/about']             = 'club/about';
  $route[$domain . '/club/guide']             = 'club/guide';
  $route[$domain . '/club/howto']             = 'club/howto';
  $route[$domain . '/club/past']              = 'club/past';
  $route[$domain . '/club/auth_about']        = 'club/auth_about';
  $route[$domain . '/club/auth']              = 'club/auth';
  $route[$domain . '/club/upload']            = 'club/upload';
  $route[$domain . '/story/view']             = 'story/view';
  $route[$domain . '/story/edit']             = 'story/edit';
  $route[$domain . '/album']                  = 'album/index';
  $route[$domain . '/album/entry']            = 'album/entry';
  $route[$domain . '/reserve']                = 'reserve/index';
  $route[$domain . '/reserve/list']           = 'reserve/list';
  $route[$domain . '/reserve/notice']         = 'reserve/notice';
  $route[$domain . '/member']                 = 'member/index';
  $route[$domain . '/member/modify']          = 'member/modify';
  $route[$domain . '/member/reserve']         = 'member/reserve';
  $route[$domain . '/member/reserve_cancel']  = 'member/reserve_cancel';
  $route[$domain . '/member/reserve_past']    = 'member/reserve_past';
  $route[$domain . '/member/point']           = 'member/point';
  $route[$domain . '/member/penalty']         = 'member/penalty';
  $route[$domain . '/member/shop']            = 'member/shop';
  $route[$domain . '/shop']                   = 'shop/index';
  $route[$domain . '/shop/item']              = 'shop/item';
  $route[$domain . '/shop/cart']              = 'shop/cart';
  $route[$domain . '/shop/checkout']          = 'shop/checkout';
  $route[$domain . '/shop/complete']          = 'shop/complete';
  $route[$domain . '/login']                  = 'login';
  $route[$domain . '/login/entry']            = 'login/entry';
  $route[$domain . '/login/forgot']           = 'login/forgot';
} else {
  $route['default_controller']  = 'welcome';
  $route['top']                 = 'welcome';
  $route['login']               = 'login/index';
  $route['logout']              = 'login/logout';
  $route['member']              = 'member/index';
  $route['club']                = 'welcome/listing';
}
