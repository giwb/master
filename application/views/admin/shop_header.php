<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  $uri = $_SERVER['REQUEST_URI'];
?>

          <section class="mb-3">
            <h4 class="font-weight-bold"><?=$pageTitle?></h4>
            <hr class="text-default">

            <div class="header-menu">
              <div class="header-menu-item col-6<?=strstr($uri, '/order') ? ' active' : ''?>"><a href="<?=BASE_URL?>/ShopAdmin/order">주문</a></div>
              <div class="header-menu-item col-6<?=strstr($uri, '/index') ? ' active' : ''?>"><a href="<?=BASE_URL?>/ShopAdmin/index">목록</a></div>
              <div class="header-menu-item col-6<?=strstr($uri, '/entry') ? ' active' : ''?>"><a href="<?=BASE_URL?>/ShopAdmin/entry">등록</a></div>
              <div class="header-menu-item col-6<?=strstr($uri, '/category') ? ' active' : ''?>"><a href="<?=BASE_URL?>/ShopAdmin/category">분류</a></div>
            </div>
          </section>
