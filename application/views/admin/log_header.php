<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  $uri = $_SERVER['REQUEST_URI'];
?>

          <section class="mb-3">
            <h4 class="font-weight-bold"><?=$pageTitle?></h4>
            <hr class="text-default">

            <div class="header-menu">
              <div class="header-menu-item<?=strstr($uri, 'log_user') ? ' active' : ''?>"><a href="<?=BASE_URL?>/admin/log_user">기록</a></div>
              <div class="header-menu-item<?=strstr($uri, 'log_reserve') ? ' active' : ''?>"><a href="<?=BASE_URL?>/admin/log_reserve">예약</a></div>
              <div class="header-menu-item<?=strstr($uri, 'log_bus') ? ' active' : ''?>"><a href="<?=BASE_URL?>/admin/log_bus">차량</a></div>
              <div class="header-menu-item<?=strstr($uri, 'log_buy') ? ' active' : ''?>"><a href="<?=BASE_URL?>/admin/log_buy">구매</a></div>
              <div class="header-menu-item<?=strstr($uri, 'log_reply') ? ' active' : ''?>"><a href="<?=BASE_URL?>/admin/log_reply">댓글</a></div>
              <div class="header-menu-item<?=strstr($uri, 'log_visitor') ? ' active' : ''?>"><a href="<?=BASE_URL?>/admin/log_visitor">방문</a></div>
            </div>
          </section>
