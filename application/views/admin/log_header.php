<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  $uri = $_SERVER['REQUEST_URI'];
?>

        <h2 class="sub-header mb-4"><?=$pageTitle?></h2>
        <div class="admin-menu">
          <div class="row align-items-center text-center">
            <a href="<?=BASE_URL?>/admin/log_user" class="col-2<?=strstr($uri, 'log_user') ? ' active' : ''?>">기록</a>
            <a href="<?=BASE_URL?>/admin/log_reserve" class="col-2<?=strstr($uri, 'log_reserve') ? ' active' : ''?>">예약</a>
            <a href="<?=BASE_URL?>/admin/log_bus" class="col-2<?=strstr($uri, 'log_bus') ? ' active' : ''?>">차량</a>
            <a href="<?=BASE_URL?>/admin/log_buy" class="col-2<?=strstr($uri, 'log_buy') ? ' active' : ''?>">구매</a>
            <a href="<?=BASE_URL?>/admin/log_reply" class="col-2<?=strstr($uri, 'log_reply') ? ' active' : ''?>">댓글</a>
            <a href="<?=BASE_URL?>/admin/log_visitor" class="col-2<?=strstr($uri, 'log_visitor') ? ' active' : ''?>">방문</a>
          </div>
        </div>
