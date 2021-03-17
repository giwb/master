<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <script type="text/javascript" src="/public/js/album.js"></script>
  <main id="club">
    <div class="container-fluid club-main">
      <div class="row mt-1 mb-5">
        <div class="col-md-12">

          <div class="row align-items-center">
            <div class="col-6"><h4 class="font-weight-bold"><?=$pageTitle?></h4></div>
            <?php if (!empty($userData['idx'])): ?><div class="col-6 text-right"><a href="<?=BASE_URL?>/album/entry" class="btn-custom btn-giwb">사진등록</a></div><?php endif; ?>
          </div>
          <hr class="text-default mb-0">

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
