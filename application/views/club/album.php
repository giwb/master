<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <script type="text/javascript" src="/public/js/masonry.pkgd.min.js"></script>
  <script type="text/javascript" src="/public/js/album.js"></script>
  <main id="club">
    <div class="container-fluid club-main">
      <div class="row mt-1 mb-5">
        <div class="col-md-12">

          <div class="row align-items-center">
            <div class="col-6"><h4 class="font-weight-bold"><?=$pageTitle?></h4></div>
            <?php if (!empty($userData['idx'])): ?>
            <div class="col-6 text-right">
              <a href="<?=BASE_URL?>/album/entry" class="btn-custom btn-giwbblue">사진등록</a>
              <a href="<?=BASE_URL?>/album/delete" class="btn-custom btn-giwbred">삭제</a>
            </div>
            <?php endif; ?>
          </div>
          <hr class="text-default mb-0">

          <div class="d-block d-sm-none">
            <div class="header-menu mt-3">
              <div class="header-menu-item active"><a href="<?=BASE_URL?>/album">사진첩</a></div>
              <div class="header-menu-item"><a href="<?=BASE_URL?>/club/search/?code=news">여행정보</a></div>
              <div class="header-menu-item"><a href="<?=BASE_URL?>/club/search/?code=review">여행후기</a></div>
            </div>
          </div>

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
      <input type="hidden" name="photoUrl" value="<?=PHOTO_URL?>">

      <!-- Photo Swipe -->
      <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="pswp__bg"></div>
        <div class="pswp__scroll-wrap">
          <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
          </div>
          <div class="pswp__ui pswp__ui--hidden">
            <div class="pswp__top-bar">
              <div class="pswp__counter"></div>
              <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
              <button class="pswp__button pswp__button--share" title="Share"></button>
              <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
              <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
              <div class="pswp__preloader">
                <div class="pswp__preloader__icn">
                  <div class="pswp__preloader__cut">
                    <div class="pswp__preloader__donut"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
              <div class="pswp__share-tooltip"></div> 
            </div>
            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
            <div class="pswp__caption">
              <div class="pswp__caption__center"></div>
            </div>
          </div>
        </div>
      </div>
