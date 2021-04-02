<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  $uri = $_SERVER['REQUEST_URI'];
?>

          <section class="mb-3">
            <h4 class="font-weight-bold"><?=$pageTitle?></h4>
            <hr class="text-default">

            <div class="header-menu">
              <div class="header-menu-item<?=strstr($uri, 'setup_information') ? ' active' : ''?>"><a href="<?=BASE_URL?>/admin/setup_information">기본정보</a></div>
              <div class="header-menu-item<?=strstr($uri, 'setup_topimage') ? ' active' : ''?>"><a href="<?=BASE_URL?>/admin/setup_topimage">대표사진</a></div>
              <div class="header-menu-item<?=strstr($uri, 'setup_pages') ? ' active' : ''?>"><a href="<?=BASE_URL?>/admin/setup_pages">소개화면</a></div>
              <div class="header-menu-item<?=strstr($uri, 'setup_sms') ? ' active' : ''?>"><a href="<?=BASE_URL?>/admin/setup_sms">문자양식</a></div>
              <div class="header-menu-item<?=strstr($uri, 'setup_bustype') ? ' active' : ''?>"><a href="<?=BASE_URL?>/admin/setup_bustype">차종등록</a></div>
              <div class="header-menu-item<?=strstr($uri, 'setup_calendar') ? ' active' : ''?>"><a href="<?=BASE_URL?>/admin/setup_calendar">달력관리</a></div>
            </div>
          </section>
