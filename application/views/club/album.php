<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <script type="text/javascript" src="/public/js/album.js"></script>
  <main id="club">
    <div class="container-fluid club-main">
      <div class="row mt-1 mb-5">
        <div class="col-md-12">

          <div class="row align-items-center">
            <div class="col-6"><h5><strong><?=$pageTitle?></strong></h5></div>
            <?php if (!empty($userData['idx'])): ?><div class="col-6 text-right"><a href="<?=BASE_URL?>/album/entry" class="btn-custom btn-giwb">사진등록</a></div><?php endif; ?>
          </div>
          <hr class="text-default mb-0">

          <form id="formList">
            <div id="album">
              <input type="hidden" name="p" value="1">
              <?=$listAlbumMain?>
              <div class="area-append"></div>
              <?php if ($cntAlbum['cnt'] > $perPage): ?>
                <div class="row mt-5">
                  <div class="col-3"></div>
                  <div class="col-6"><button type="button" class="btn btn-page-next">다음 페이지 보기 ▼</button></div>
                  <div class="col-3"></div>
                </div>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>
