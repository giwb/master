<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="sub-header-row row align-items-center text-center small bg-secondary">
          <div class="col-6 col-sm-2<?=strstr($_SERVER['REQUEST_URI'], '/about') ? ' active' : ''?>"><a href="<?=BASE_URL?>/club/about">산악회 소개</a></div>
          <div class="col-6 col-sm-2<?=strstr($_SERVER['REQUEST_URI'], '/guide') ? ' active' : ''?>"><a href="<?=BASE_URL?>/club/guide">등산안내인</a></div>
          <div class="col-6 col-sm-2<?=strstr($_SERVER['REQUEST_URI'], '/past') ? ' active' : ''?>"><a href="<?=BASE_URL?>/club/past">지난 산행보기</a></div>
          <div class="col-6 col-sm-2<?=strstr($_SERVER['REQUEST_URI'], '/howto') ? ' active' : ''?>"><a href="<?=BASE_URL?>/club/howto">이용안내</a></div>
          <div class="col-6 col-sm-2<?=strstr($_SERVER['REQUEST_URI'], '/_about') ? ' active' : ''?>"><a href="<?=BASE_URL?>/club/auth_about">백산백소 소개</a></div>
          <div class="col-6 col-sm-2<?=strstr($_SERVER['REQUEST_URI'], '/auth') ? ' active' : ''?>"><a href="<?=BASE_URL?>/club/auth">인증현황</a></div>
        </div>
        <div class="sub-content">
          <?=reset_html_escape($view['about'])?>
        </div>
        <div class="ad-sp">
          <!-- SP_CENTER -->
          <ins class="adsbygoogle"
            style="display:block"
            data-ad-client="ca-pub-2424708381875991"
            data-ad-slot="4319659782"
            data-ad-format="auto"
            data-full-width-responsive="true"></ins>
          <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
          </script>
        </div>
      </div>
