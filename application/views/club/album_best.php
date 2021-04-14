<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <script type="text/javascript" src="/public/js/masonry.pkgd.min.js"></script>
  <script type="text/javascript" src="/public/js/imagesloaded.pkgd.min.js"></script>
  <script type="text/javascript" src="/public/js/album.js?<?=time()?>"></script>
  <main id="club">
    <div class="container-fluid club-main">
      <div class="row mt-1 mb-5">
        <div class="col-xl-8 col-md-12 mb-5">
          <h4 class="font-weight-bold"><?=$pageTitle?></h4>
          <hr class="text-default">

          <div class="d-block d-sm-none">
            <div class="header-menu mt-3">
              <div class="header-menu-item active"><a href="<?=BASE_URL?>/album">사진첩</a></div>
              <div class="header-menu-item"><a href="<?=BASE_URL?>/album/best">추천사진</a></div>
              <div class="header-menu-item"><a href="<?=BASE_URL?>/club/video">동영상</a></div>
              <div class="header-menu-item"><a href="<?=BASE_URL?>/club/search/?code=news">여행정보</a></div>
              <div class="header-menu-item"><a href="<?=BASE_URL?>/club/search/?code=review">여행후기</a></div>
            </div>
          </div>

          <div id="album" class="row align-items-center mt-4">
            <?php if (empty($listBestPhoto)): ?>
            <div class="col-12 text-center pt-5 pb-5">아직 등록된 추천 사진이 없습니다.</div>
            <?php else: ?>
            <?php foreach ($listBestPhoto as $value): ?>
            <div class="col-4 mb-4">
              <div class="card">
                <div class="view overlay">
                  <img src="<?=ALBUM_URL . 'thumb_' . $value['filename']?>">
                  <a href="<?=BASE_URL?>/album/best/<?=$value['idx']?>"><div class="mask rgba-white-slight"></div></a>
                </div>
                <div class="mdb-color lighten-3 text-center">
                  <ul class="list-unstyled list-inline font-small mt-3">
                    <li class="list-inline-item pr-1 white-text"><i class="far fa-eye pr-1"></i><?=$value['refer']?></li>
                    <li class="list-inline-item pr-1 white-text"><i class="far fa-heart pr-1"></i><?=$value['liked']['cnt']?></li>
                    <li class="list-inline-item pr-1 white-text"><i class="far fa-comments pr-1"></i>0</li>
                  </ul>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
          </div>

        </div>
