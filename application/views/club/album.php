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

      <script type="text/javascript">
      $(document).on('click', '.btn-album', function() {
        var $dom = $(this).parent();
        var index = $(this).data('index');
        var items = [];

        $('.btn-album[data-notice-idx=' + $(this).data('notice-idx') + ']').each(function(i, v) {
          items.push({
            src: '<?=PHOTO_URL?>' + $(this).data('src'),
            w: $(this).data('width'),
            h: $(this).data('height'),
            title: $(this).data('title')
          });
        });

        var pswpElement = document.querySelectorAll('.pswp')[0];
        var items = items;
        var options = {
          index: index,
          bgOpacity: 0.8,
          showHideOpacity: true,
          getThumbBoundsFn: function(index) {
            var thumbnail = $dom[0],
            pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
            rect = thumbnail.getBoundingClientRect(); 
            return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
          }
        };
        var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
        gallery.init();
      });
      </script>