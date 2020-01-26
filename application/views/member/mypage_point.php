<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="mypage mt-2">
          <h2><?=$pageTitle?></h2>
          <h3>■ 포인트 내역 <small>- 잔액 <?=number_format($viewMember['point'])?> 포인트</small></h3>
          <ul>
            <form id="formList" method="post" action="<?=BASE_URL?>/member/point">
            <input type="hidden" name="p" value="1">
            <?=$userPoint?>
            <div class="area-append"></div>
            <?php if ($maxPoint['cnt'] > $perPage): ?>
            <button class="btn btn-page-next">다음 페이지 보기 ▼</button>
            <?php endif; ?>
          </form>
          </ul>
        </div>
        <div class="ad-sp">
          <!-- CENTER -->
          <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-2424708381875991" data-ad-slot="7579588805" data-ad-format="auto" data-full-width-responsive="true"></ins>
          <script> (adsbygoogle = window.adsbygoogle || []).push({}); </script>
        </div>
      </div>
