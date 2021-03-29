<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

  <main>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-8 col-md-12 mb-5">
          <h4 class="font-weight-bold"><strong>실시간 현지영상</strong></h4>
          <hr class="red">
          <div class="header-menu">
            <div class="header-menu-item active"><a href="javascript:;" class="btn-screen" data-idx="1">국립공원</a></div>
            <div class="header-menu-item"><a href="javascript:;" class="btn-screen" data-idx="2">제주도</a></div>
          </div>
          <div class="row screen" data-idx="1">
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://220.70.164.229/hls/bukhan.m3u8">
                  <h4 class="text-center">북한산 국립공원 백운대</h4>
                  <img class="w-100" src="/public/images/video/video_1_1.png">
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://220.70.164.229/hls/sobak.m3u8">
                  <h4 class="text-center">소백산 국립공원 연화봉</h4>
                  <img class="w-100" src="/public/images/video/video_1_2.png">
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://220.70.164.229/hls/solak.m3u8">
                  <h4 class="text-center">설악산 국립공원 울산바위</h4>
                  <img class="w-100" src="/public/images/video/video_1_3.png">
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://220.70.164.229/hls/zjungchung.m3u8">
                  <h4 class="text-center">설악산 국립공원 대청봉</h4>
                  <img class="w-100" src="/public/images/video/video_1_4.png">
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://220.70.164.229/hls/zsulchun.m3u8">
                  <h4 class="text-center">덕유산 국립공원 설천봉</h4>
                  <img class="w-100" src="/public/images/video/video_1_5.png">
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://220.70.164.229/hls/zduro.m3u8">
                  <h4 class="text-center">오대산 국립공원 두로령</h4>
                  <img class="w-100" src="/public/images/video/video_1_6.png">
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://220.70.164.229/hls/jirisan.m3u8">
                  <h4 class="text-center">지리산 국립공원 천왕봉</h4>
                  <img class="w-100" src="/public/images/video/video_1_7.png">
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://220.70.164.229/hls/zjangteo.m3u8">
                  <h4 class="text-center">지리산 국립공원 장터목</h4>
                  <img class="w-100" src="/public/images/video/video_1_8.png">
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://220.70.164.229/hls/zchunje.m3u8">
                  <h4 class="text-center">태백산 국립공원 천제단</h4>
                  <img class="w-100" src="/public/images/video/video_1_9.png">
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://220.70.164.229/hls/zjulje.m3u8">
                  <h4 class="text-center">주왕산 국립공원 절재</h4>
                  <img class="w-100" src="/public/images/video/video_1_10.png">
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://220.70.164.229/hls/jodo.m3u8">
                  <h4 class="text-center">다도해 해상국립공원 조도</h4>
                  <img class="w-100" src="/public/images/video/video_1_11.png">
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://220.70.164.229/hls/gaksan.m3u8">
                  <h4 class="text-center">한려해상 국립공원 각산</h4>
                  <img class="w-100" src="/public/images/video/video_1_12.png">
                </div>
              </div>
            </div>
            <div class="col-12">
              <img src="/public/images/tripkorea/img_opencode1_m.jpg"><br><small>영상 출처 : 국립공원공단</small>
            </div>
          </div>
          <div class="row screen d-none" data-idx="2">
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://123.140.197.51/stream/33/play.m3u8">
                  <h4 class="text-center">제주공항</h4>
                  <img class="w-100" src="/public/images/video/video_2_1.png">
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://59.8.86.15:1935/live/51.stream/playlist.m3u8">
                  <h4 class="text-center">용두암 해안</h4>
                  <img class="w-100" src="/public/images/video/video_2_2.png">
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://59.8.86.15:1935/live/52.stream/playlist.m3u8">
                  <h4 class="text-center">탑동 해안</h4>
                  <img class="w-100" src="/public/images/video/video_2_3.png">
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://59.8.86.15:1935/live/63.stream/playlist.m3u8">
                  <h4 class="text-center">신창 해안</h4>
                  <img class="w-100" src="/public/images/video/video_2_4.png">
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://59.8.86.15:1935/live/60.stream/playlist.m3u8">
                  <h4 class="text-center">화순 해안</h4>
                  <img class="w-100" src="/public/images/video/video_2_5.png">
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://123.140.197.51/stream/35/play.m3u8">
                  <h4 class="text-center">서귀포항</h4>
                  <img class="w-100" src="/public/images/video/video_2_6.png">
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://59.8.86.15:1935/live/54.stream/playlist.m3u8">
                  <h4 class="text-center">법환포구</h4>
                  <img class="w-100" src="/public/images/video/video_2_7.png">
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://59.8.86.15:1935/live/55.stream/playlist.m3u8">
                  <h4 class="text-center">법환 해안</h4>
                  <img class="w-100" src="/public/images/video/video_2_8.png">
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://59.8.86.15:1935/live/59.stream/playlist.m3u8">
                  <h4 class="text-center">중문 해안</h4>
                  <img class="w-100" src="/public/images/video/video_2_9.png">
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://123.140.197.51/stream/34/play.m3u8">
                  <h4 class="text-center">성산일출봉</h4>
                  <img class="w-100" src="/public/images/video/video_2_10.png">
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://59.8.86.15:1935/live/56.stream/playlist.m3u8">
                  <h4 class="text-center">온평 해안</h4>
                  <img class="w-100" src="/public/images/video/video_2_11.png">
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body btn-video-modal" data-source="http://119.65.216.155:1935/live/cctv01.stream_360p/playlist.m3u8">
                  <h4 class="text-center">한라산 백록담</h4>
                  <img class="w-100" src="/public/images/video/video_2_12.png">
                </div>
              </div>
            </div>
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
    $('.btn-screen').parent().removeClass('active');
    $(this).parent().addClass('active');
    $('.screen').addClass('d-none');
    $('.screen[data-idx=' + $(this).data('idx') + ']').removeClass('d-none');
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