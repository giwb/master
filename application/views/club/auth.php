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
        <div class="sub-content"><br>
          <table class="auth">
            <colgroup>
              <col width="5%">
              <col width="17%">
              <col width="8%">
              <col width="70%">
            </colgroup>
            <tr>
              <th>No.</th>
              <th>닉네임</th>
              <th>횟수</th>
              <th>산행인증내역</th>
            </tr>
            <?php foreach ($auth as $value): ?>
            <tr>
              <td><?=$value['rank']?></td>
              <td><?=$value['nickname']?>님</td>
              <td><?=$value['cnt']?></td>
              <td><?=$value['title']?></td>
            </tr>
            <?php endforeach; ?>
          </table>
        </div><br>
        <div class="ad-sp">
          <!-- CENTER -->
          <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-2424708381875991" data-ad-slot="7579588805" data-ad-format="auto" data-full-width-responsive="true"></ins>
          <script> (adsbygoogle = window.adsbygoogle || []).push({}); </script>
        </div>
      </div>
