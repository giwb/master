<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  $uri = $_SERVER['REQUEST_URI'];
?>

          <section class="mb-3">
            <h4 class="font-weight-bold"><?=$pageTitle?></h4>
            <hr class="text-default">

            <div class="header-menu">
              <div class="header-menu-item<?=strstr($uri, 'main_list_progress') || (!empty($view['status']) && $view['status'] == STATUS_CONFIRM) ? ' active' : ''?>"><a href="<?=BASE_URL?>/admin/main_list_progress">진행</a></div>
              <div class="header-menu-item<?=strstr($uri, 'closed') || (!empty($view['status']) && $view['status'] == STATUS_CLOSED) ? ' active' : ''?>"><a href="<?=BASE_URL?>/admin/main_list_closed">종료</a></div>
              <div class="header-menu-item<?=strstr($uri, 'canceled') || (!empty($view['status']) && $view['status'] == STATUS_CANCEL) ? ' active' : ''?>"><a href="<?=BASE_URL?>/admin/main_list_canceled">취소</a></div>
              <div class="header-menu-item<?=strstr($uri, 'entry') && empty($view['idx']) ? ' active' : ''?>"><a href="<?=BASE_URL?>/admin/main_entry">신규</a></div>
              <div class="header-menu-item<?=strstr($uri, 'schedule') ? ' active' : ''?>"><a href="<?=BASE_URL?>/admin/main_schedule">계획</a></div>
              <div class="header-menu-item<?=strstr($uri, 'copy') ? ' active' : ''?>"><a target="_blank" href="<?=BASE_URL?>/admin/main_list_copy">복사</a></div>
            </div>
          </section>