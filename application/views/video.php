<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
  <main>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-8 col-md-12 mb-5">
          <h4 class="font-weight-bold"><strong>실시간 현지영상</strong></h4>
          <hr class="red">
          <div class="header-menu">
            <?php foreach ($listCctvCategory as $value): ?>
            <div class="header-menu-item<?=$category == $value['code'] ? ' active' : ''?>"><a class="btn-screen" data-category="<?=$value['code']?>"><?=$value['name']?></a></div>
            <?php endforeach; ?>
          </div>
          <div class="row screen" data-idx="1">
            <?php foreach ($listCctv as $value): ?>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="<?=$value['link']?>">
                  <h4 class="text-center"><?=$value['title']?></h4>
                  <img class="w-100" src="<?=CCTV_THUMBNAIL_URL . $value['thumbnail']?>">
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Video Modal -->
        <div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="smallmodalLabel">현지영상</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body text-center">
                <video id="video" class="video-js" autoplay controls></video>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
              </div>
            </div>
          </div>
        </div>

        <script>
          $(document).on('click', '.btn-screen', function() {
            location.replace('/video?c=' + $(this).data('category'));
          }).on('click', '.btn-video-modal', function() {
            $('#videoModal').modal('show');
            $('#videoModal .modal-title').text($(this).find('h4').text());
            var hls = new Hls();
            var source = $(this).data('source');
            var video = document.getElementById('video');
            hls.loadSource(source);
            hls.attachMedia(video);
            hls.on(Hls.Events.MANIFEST_PARSED,function() {
              video.muted = true;
              video.play();
            });
          });
        </script>
