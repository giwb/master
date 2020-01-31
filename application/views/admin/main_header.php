<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  $uri = $_SERVER['REQUEST_URI'];
?>

        <h2 class="sub-header mb-4"><?=$pageTitle?></h2>
        <div class="admin-menu">
          <div class="row align-items-center text-center">
            <a href="/admin/main_list_progress" class="col-2<?=strstr($uri, 'progress') ? ' active' : ''?>">진행</a>
            <a href="/admin/main_list_closed" class="col-2<?=strstr($uri, 'closed') ? ' active' : ''?>">종료</a>
            <a href="/admin/main_list_canceled" class="col-2<?=strstr($uri, 'canceled') ? ' active' : ''?>">취소</a>
            <a href="/admin/main_entry" class="col-2<?=strstr($uri, 'entry') ? ' active' : ''?>">신규</a>
            <a href="/admin/main_schedule" class="col-2<?=strstr($uri, 'schedule') ? ' active' : ''?>">계획</a>
            <a href="/admin/main_list_copy" class="col-2<?=strstr($uri, 'copy') ? ' active' : ''?>">복사</a>
          </div>
        </div>
