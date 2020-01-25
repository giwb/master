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
$result = $query->result();

if (!empty($result)) {
  $result = $query->result();
  $result = $result[0]->idx;
} elseif (!empty($_SERVER['REDIRECT_URL'])) {
  $arrUrl = explode('/', $_SERVER['REDIRECT_URL']);
  $domain = html_escape($arrUrl[1]);
  $query = $db->query("SELECT idx FROM clubs WHERE domain='$domain'");
  $result = $query->result();

  if (!empty($result)) {
    $result = $result[0]->idx;
  }
}

if (!empty($result)) {
  // 도메인이 있을 경우
  $route[$domain]                             = 'club/index/' . $result;
  $route[$domain . '/club/about']             = 'club/about/' . $result;
  $route[$domain . '/club/guide']             = 'club/guide/' . $result;
  $route[$domain . '/club/howto']             = 'club/howto/' . $result;
  $route[$domain . '/club/past']              = 'club/past/' . $result;
  $route[$domain . '/club/auth_about']        = 'club/auth_about/' . $result;
  $route[$domain . '/club/auth']              = 'club/auth/' . $result;
  $route[$domain . '/club/upload']            = 'club/upload/' . $result;
  $route[$domain . '/story/view']             = 'story/view/' . $result;
  $route[$domain . '/story/edit']             = 'story/edit/' . $result;
  $route[$domain . '/album']                  = 'album/index/' . $result;
  $route[$domain . '/album/entry']            = 'album/entry/' . $result;
  $route[$domain . '/reserve']                = 'reserve/index/' . $result;
  $route[$domain . '/reserve/notice']         = 'reserve/notice/' . $result;
  $route[$domain . '/member']                 = 'member/index/' . $result;
  $route[$domain . '/member/modify']          = 'member/modify/' . $result;
  $route[$domain . '/member/reserve']         = 'member/reserve/' . $result;
  $route[$domain . '/member/reserve_cancel']  = 'member/reserve_cancel/' . $result;
  $route[$domain . '/member/reserve_past']    = 'member/reserve_past/' . $result;
  $route[$domain . '/member/point']           = 'member/point/' . $result;
  $route[$domain . '/member/penalty']         = 'member/penalty/' . $result;
  $route[$domain . '/shop']                   = 'shop/index/' . $result;
  $route[$domain . '/shop/item']              = 'shop/item/' . $result;
  $route[$domain . '/shop/cart']              = 'shop/cart/' . $result;
  $route[$domain . '/shop/checkout']          = 'shop/checkout/' . $result;
  $route[$domain . '/shop/complete']          = 'shop/complete/' . $result;
} else {
  $route['default_controller']  = 'welcome';
  $route['login']               = 'login/index';
  $route['logout']              = 'login/logout';
  $route['member']              = 'member/index';
  $route['club']                = 'welcome/listing';
}
