<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="row-category mb-3">
          <div class="row m-0 p-0 border-right border-bottom">
            <a href="<?=BASE_URL?>/club/about" class="col-6 col-sm-2 border-left pt-2 pb-2 pl-0 pr-0 small text-center<?=strstr($_SERVER['REQUEST_URI'], '/about') ? ' active' : ''?>">산악회 소개</a>
            <a href="<?=BASE_URL?>/club/guide" class="col-6 col-sm-2 border-left pt-2 pb-2 pl-0 pr-0 small text-center<?=strstr($_SERVER['REQUEST_URI'], '/guide') ? ' active' : ''?>">등산안내인</a>
            <a href="<?=BASE_URL?>/club/past" class="col-6 col-sm-2 border-left pt-2 pb-2 pl-0 pr-0 small text-center<?=strstr($_SERVER['REQUEST_URI'], '/past') ? ' active' : ''?>">지난 산행보기</a>
            <a href="<?=BASE_URL?>/club/howto" class="col-6 col-sm-2 border-left pt-2 pb-2 pl-0 pr-0 small text-center<?=strstr($_SERVER['REQUEST_URI'], '/howto') ? ' active' : ''?>">이용안내</a>
            <a href="<?=BASE_URL?>/club/auth_about" class="col-6 col-sm-2 border-left pt-2 pb-2 pl-0 pr-0 small text-center<?=strstr($_SERVER['REQUEST_URI'], '_about') ? ' active' : ''?>">백산백소 소개</a>
            <a href="<?=BASE_URL?>/club/auth" class="col-6 col-sm-2 border-left pt-2 pb-2 pl-0 pr-0 small text-center<?=strstr($_SERVER['REQUEST_URI'], '/auth') && !strstr($_SERVER['REQUEST_URI'], 'auth_about') ? ' active' : ''?>">인증현황</a>
          </div>
        </div>
        <h2 class="sub-header"><?=$pageTitle?></h2>
        <div class="sub-content">
          <?=reset_html_escape($view['guide'])?>
        </div>
      </div>
