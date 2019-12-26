<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <link href="<?=base_url()?>public/css/magnific-popup.css" rel="stylesheet">
      <div id="album" class="club-main">
        <div class="sub-header">사진첩</div>
        <div class="sub-content">
          <div class="text-right mt-3 mb-3">
            <?php if (!empty($userIdx)): ?>
            <a href="<?=base_url()?>club/album_upload/<?=$view['idx']?>"><button class="btn btn-sm btn-primary">사진 등록</button></a>
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
      </div>
      <script src="<?=base_url()?>public/js/jquery.magnific-popup.min.js" type="text/javascript"></script>
      <script src="<?=base_url()?>public/js/album.js" type="text/javascript"></script>