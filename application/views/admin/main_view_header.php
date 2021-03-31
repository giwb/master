<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  $uri = $_SERVER['REQUEST_URI'];
?>

            <div class="header-menu mb-4">
              <div class="header-menu-item<?=strstr($uri, 'main_entry') ? ' active' : ''?>"><a href="<?=BASE_URL?>/admin/main_entry/<?=$view['idx']?>">수정</a></div>
              <div class="header-menu-item<?=strstr($uri, 'main_notice') ? ' active' : ''?>"><a href="<?=BASE_URL?>/admin/main_notice/<?=$view['idx']?>">공지</a></div>
              <div class="header-menu-item<?=strstr($uri, 'main_view_progress') ? ' active' : ''?>"><a href="<?=BASE_URL?>/admin/main_view_progress/<?=$view['idx']?>">예약</a></div>
              <div class="header-menu-item<?=strstr($uri, 'main_view_boarding') ? ' active' : ''?>"><a href="<?=BASE_URL?>/admin/main_view_boarding/<?=$view['idx']?>">승차</a></div>
              <div class="header-menu-item<?=strstr($uri, 'main_view_sms') ? ' active' : ''?>"><a href="<?=BASE_URL?>/admin/main_view_sms/<?=$view['idx']?>">문자</a></div>
              <div class="header-menu-item<?=strstr($uri, 'main_view_adjust') ? ' active' : ''?>"><a href="<?=BASE_URL?>/admin/main_view_adjust/<?=$view['idx']?>">정산</a></div>
            </div>
