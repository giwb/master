<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  $uri = $_SERVER['REQUEST_URI'];
?>

        <h2 class="sub-header mb-4"><?=$pageTitle?></h2>
        <div class="admin-menu">
          <div class="row align-items-center text-center">
            <a href="<?=BASE_URL?>/admin/setup_information" class="col-2<?=strstr($uri, 'setup_information') ? ' active' : ''?>">기본정보</a>
            <a href="<?=BASE_URL?>/admin/setup_pages" class="col-2<?=strstr($uri, 'setup_pages') ? ' active' : ''?>">소개화면</a>
            <a href="<?=BASE_URL?>/admin/setup_sms" class="col-2<?=strstr($uri, 'setup_sms') ? ' active' : ''?>">문자양식</a>
            <a href="<?=BASE_URL?>/admin/setup_bustype" class="col-2<?=strstr($uri, 'setup_bustype') ? ' active' : ''?>">차종등록</a>
            <a href="<?=BASE_URL?>/admin/setup_calendar" class="col-2<?=strstr($uri, 'setup_calendar') ? ' active' : ''?>">달력관리</a>
            <a target="_blank" href="https://analytics.google.com/analytics/web/" class="col-2">구글통계</a>
          </div>
        </div>
