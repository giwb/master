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
require_once (BASEPATH . 'database/DB.php');
$db =& DB();

// 각 클럽 도메인별 이동
$domain = $_SERVER['HTTP_HOST'];
$query = $db->query("SELECT idx FROM clubs WHERE domain='$domain'");
$result = $query->row_array(1);

if (empty($result) && !empty($_SERVER['REDIRECT_URL'])) {
  $arrUrl = explode('/', $_SERVER['REDIRECT_URL']);
  $domain = html_escape($arrUrl[1]);
  $query = $db->query("SELECT idx, domain FROM clubs WHERE url='$domain'");
  $result = $query->row_array(1);
}

if (!empty($result['idx'])) {
  setcookie('COOKIE_CLUBIDX', $result['idx']);
  $_COOKIE['COOKIE_CLUBIDX'] = $result['idx'];

  // 도메인이 있을 경우
  $route['default_controller'] = 'club/index';
  $route[$domain] = 'club/index';
  $uri = '';
  if (!empty($arrUrl)) {
    foreach ($arrUrl as $key => $value) {
      if ($key > 1 && !empty($value)) {
        if ($key > 2) $uri .= '/';
        $uri .= $value;
      }
    }
  }
  $route[$domain . '/' . $uri] = $uri;
} else {
  setcookie('COOKIE_CLUBIDX', '');
  $_COOKIE['COOKIE_CLUBIDX'] = '';

  $route['default_controller']  = 'welcome';
  $route['top']                 = 'welcome';
  $route['article/(:num)']      = 'welcome/article/$1';
  $route['search']              = 'welcome/search';
  $route['area']                = 'welcome/area';
  $route['schedule']            = 'welcome/schedule';
  $route['login']               = 'login/index';
  $route['logout']              = 'login/logout';
  $route['member']              = 'member/index';
  $route['video']               = 'welcome/video';
  $route['club']                = 'welcome/club_listing';
  $route['club/entry']          = 'welcome/club_entry';
  $route['club/insert']         = 'welcome/club_insert';
  $route['club/check_domain']   = 'welcome/check_domain';
}
