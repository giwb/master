<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div id="album" class="club-main">
        <div class="sub-header">사진첩</div>
        <div class="sub-content">
          <div class="text-right mt-3 mb-3">
            <?php if (!empty($userIdx)): ?>
            <a href="<?=BASE_URL?>/album/entry"><button class="btn btn-sm btn-primary">사진 등록</button></a>
            <?php endif; ?>
          </div>
          <form id="formList">
            <input type="hidden" name="p" value="1">
            <?=$listAlbum?>
            <div class="area-append"></div>
            <?php if ($cntAlbum['cnt'] > $perPage): ?>
            <button type="button" class="btn btn-page-next">다음 페이지 보기 ▼</button>
            <?php endif; ?>
          </form>
        </div>
        <div class="ad-sp">
          <!-- CENTER -->
          <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-2424708381875991" data-ad-slot="7579588805" data-ad-format="auto" data-full-width-responsive="true"></ins>
          <script> (adsbygoogle = window.adsbygoogle || []).push({}); </script>
        </div>
      </div>
      <script src="/public/js/album.js" type="text/javascript"></script>