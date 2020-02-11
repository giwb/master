<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  $uri = $_SERVER['REQUEST_URI'];
?>

        <h2 class="sub-header mb-4"><?=$pageTitle?></h2>
        <div class="admin-menu">
          <div class="row align-items-center text-center">
            <a href="<?=BASE_URL?>/ShopAdmin/order" class="col-3<?=strstr($uri, '/order') ? ' active' : ''?>">주문</a>
            <a href="<?=BASE_URL?>/ShopAdmin/index" class="col-3<?=strstr($uri, '/index') ? ' active' : ''?>">목록</a>
            <a href="<?=BASE_URL?>/ShopAdmin/entry" class="col-3<?=strstr($uri, '/entry') ? ' active' : ''?>">등록</a>
            <a href="<?=BASE_URL?>/ShopAdmin/category" class="col-3<?=strstr($uri, '/category') ? ' active' : ''?>">분류</a>
          </div>
        </div>
