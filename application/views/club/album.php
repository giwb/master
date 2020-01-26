<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div id="album" class="club-main">
        <h2 class="sub-header">사진첩</h2>
        <div class="sub-content">
          <form method="post" action="<?=BASE_URL?>/album" class="row align-items-center m-0 mb-3">
            <div class="col-9 col-sm-10 p-0"><input type="text" name="k" class="form-control" value="<?=!empty($keyword) ? $keyword : ''?>"></div>
            <div class="col-3 col-sm-2 p-0 pl-2"><button class="btn btn-primary w-100">검색</button></div>
          </form>
          <form id="formList">
            <input type="hidden" name="p" value="1">
            <?=$listAlbum?>
            <div class="area-append"></div>
            <?php if ($cntAlbum['cnt'] > $perPage): ?>
            <button type="button" class="btn btn-page-next">다음 페이지 보기 ▼</button>
            <?php endif; ?>
          </form>
        </div>
      </div>
      <script src="/public/js/album.js" type="text/javascript"></script>