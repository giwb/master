<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="mypage mt-2">
          <h2><?=$pageTitle?></h2>
          <h3>■ 산행 내역</h3>
          <form id="formList" method="post" action="<?=base_url()?>member/reserve_past/<?=$clubIdx?>">
            <input type="hidden" name="p" value="1">
            <?=$userVisit?>
            <div class="area-append"></div>
            <?php if ($maxVisit['cnt'] > $perPage): ?>
            <button class="btn btn-page-next">다음 페이지 보기 ▼</button>
            <?php endif; ?>
          </form>
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
