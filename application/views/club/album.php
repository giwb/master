<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <script type="text/javascript" src="/public/js/album.js"></script>
  <main id="club">
    <div class="container-fluid club-main">
      <div class="row mt-1 mb-5">
        <div class="col-xl-8 col-md-12">

          <h4 class="font-weight-bold"><?=$pageTitle?></h4>
          <hr class="text-default">
<!--
          <div class="row align-items-center">
            <div class="col-8 col-sm-9"></div>
            <div class="col-4 col-sm-3 text-right">
              <form method="post" action="<?=BASE_URL?>/album" class="row align-items-center m-0 mb-3">
                <div class="col-8 col-sm-8 p-0"><input type="text" name="k" class="form-control form-control-sm" value="<?=!empty($keyword) ? $keyword : ''?>"></div>
                <div class="col-2 col-sm-2 p-0 pl-2"><button class="btn btn-sm btn-<?=$view['main_color']?> w-100">검색</button></div>
                <div class="col-2 col-sm-2 p-0 pl-2"><a href="<?=BASE_URL?>/album/entry"><button type="button" class="btn btn-sm btn-<?=$view['main_color']?> w-100">등록</button></a></div>
              </form>
            </div>
          </div>
          <hr class="text-default mt-2">
-->
          <form id="album">
            <input type="hidden" name="p" value="1">
            <?=$listAlbumMain?>
            <div class="area-append"></div>
            <?php if ($cntAlbum['cnt'] > $perPage): ?>
            <button type="button" class="btn btn-page-next">다음 페이지 보기 ▼</button>
            <?php endif; ?>
          </form>
        </div>

  <script src="/public/js/masonry.pkgd.min.js" type="text/javascript"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('.grid').masonry({
        itemSelector: '.grid-item',
        columnWidth: 200
      });
    });
  </script>
