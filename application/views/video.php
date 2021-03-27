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
                <div class="card-body">
                  <h4 class="text-center">북한산 국립공원 백운대</h4>
                  <video id="video1" class="video-js" autoplay controls></video>
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body">
                  <h4 class="text-center">소백산 국립공원 연화봉</h4>
                  <video id="video2" class="video-js" autoplay controls></video>
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body">
                  <h4 class="text-center">설악산 국립공원 울산바위</h4>
                  <video id="video3" class="video-js" autoplay controls></video>
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body">
                  <h4 class="text-center">설악산 국립공원 대청봉</h4>
                  <video id="video4" class="video-js" autoplay controls></video>
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body">
                  <h4 class="text-center">덕유산 국립공원 설천봉</h4>
                  <video id="video5" class="video-js" autoplay controls></video>
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body">
                  <h4 class="text-center">오대산 국립공원 두로령</h4>
                  <video id="video6" class="video-js" autoplay controls></video>
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body">
                  <h4 class="text-center">지리산 국립공원 천왕봉</h4>
                  <video id="video7" class="video-js" autoplay controls></video>
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body">
                  <h4 class="text-center">지리산 국립공원 장터목</h4>
                  <video id="video8" class="video-js" autoplay controls></video>
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body">
                  <h4 class="text-center">태백산 국립공원 천제단</h4>
                  <video id="video9" class="video-js" autoplay controls></video>
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body">
                  <h4 class="text-center">주왕산 국립공원 절재</h4>
                  <video id="video10" class="video-js" autoplay controls></video>
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body">
                  <h4 class="text-center">다도해 해상국립공원 조도</h4>
                  <video id="video11" class="video-js" autoplay controls></video>
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body">
                  <h4 class="text-center">한려해상 국립공원 각산</h4>
                  <video id="video12" class="video-js" autoplay controls></video>
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
                <div class="card-body">
                  <h4 class="text-center">제주공항</h4>
                  <video id="video-jeju1" class="video-js" autoplay controls></video>
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body">
                  <h4 class="text-center">용두암 해안</h4>
                  <video id="video-jeju2" class="video-js" autoplay controls></video>
                </div>
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
  });

  // 북한산 백운대
  var hls1 = new Hls();
  var source = 'http://220.70.164.229/hls/bukhan.m3u8';
  var video1 = document.getElementById('video1');
  hls1.loadSource(source);
  hls1.attachMedia(video1);
  hls1.on(Hls.Events.MANIFEST_PARSED,function() {
    video1.muted = true;
    video1.play();
  });

  // 소백산 연화봉
  var hls2 = new Hls();
  var source = 'http://220.70.164.229/hls/sobak.m3u8';
  var video2 = document.getElementById('video2');
  hls2.loadSource(source);
  hls2.attachMedia(video2);
  hls2.on(Hls.Events.MANIFEST_PARSED,function() {
    video2.muted = true;
    video2.play();
  });

  // 설악산 울산바위
  var hls3 = new Hls();
  var source = 'http://220.70.164.229/hls/solak.m3u8';
  var video3 = document.getElementById('video3');
  hls3.loadSource(source);
  hls3.attachMedia(video3);
  hls3.on(Hls.Events.MANIFEST_PARSED,function() {
    video3.muted = true;
    video3.play();
  });

  // 설악산 대청봉
  var hls4 = new Hls();
  var source = 'http://220.70.164.229/hls/zjungchung.m3u8';
  var video4 = document.getElementById('video4');
  hls4.loadSource(source);
  hls4.attachMedia(video4);
  hls4.on(Hls.Events.MANIFEST_PARSED,function() {
    video4.muted = true;
    video4.play();
  });

  // 덕유산 설천봉
  var hls5 = new Hls();
  var source = 'http://220.70.164.229/hls/zsulchun.m3u8';
  var video5 = document.getElementById('video5');
  hls5.loadSource(source);
  hls5.attachMedia(video5);
  hls5.on(Hls.Events.MANIFEST_PARSED,function() {
    video5.muted = true;
    video5.play();
  });

  // 오대산 두로령
  var hls6 = new Hls();
  var source = 'http://220.70.164.229/hls/zduro.m3u8';
  var video6 = document.getElementById('video6');
  hls6.loadSource(source);
  hls6.attachMedia(video6);
  hls6.on(Hls.Events.MANIFEST_PARSED,function() {
    video6.muted = true;
    video6.play();
  });

  // 지리산 천왕봉
  var hls7 = new Hls();
  var source = 'http://220.70.164.229/hls/jirisan.m3u8';
  var video7 = document.getElementById('video7');
  hls7.loadSource(source);
  hls7.attachMedia(video7);
  hls7.on(Hls.Events.MANIFEST_PARSED,function() {
    video7.muted = true;
    video7.play();
  });

  // 지리산 장터목
  var hls8 = new Hls();
  var source = 'http://220.70.164.229/hls/zjangteo.m3u8';
  var video8 = document.getElementById('video8');
  hls8.loadSource(source);
  hls8.attachMedia(video8);
  hls8.on(Hls.Events.MANIFEST_PARSED,function() {
    video8.muted = true;
    video8.play();
  });

  // 태백산 천제단
  var hls9 = new Hls();
  var source = 'http://220.70.164.229/hls/zchunje.m3u8';
  var video9 = document.getElementById('video9');
  hls9.loadSource(source);
  hls9.attachMedia(video9);
  hls9.on(Hls.Events.MANIFEST_PARSED,function() {
    video9.muted = true;
    video9.play();
  });

  // 주왕산 절재
  var hls10 = new Hls();
  var source = 'http://220.70.164.229/hls/zjulje.m3u8';
  var video10 = document.getElementById('video10');
  hls10.loadSource(source);
  hls10.attachMedia(video10);
  hls10.on(Hls.Events.MANIFEST_PARSED,function() {
    video10.muted = true;
    video10.play();
  });

  // 다도해 조도
  var hls11 = new Hls();
  var source = 'http://220.70.164.229/hls/jodo.m3u8';
  var video11 = document.getElementById('video11');
  hls11.loadSource(source);
  hls11.attachMedia(video11);
  hls11.on(Hls.Events.MANIFEST_PARSED,function() {
    video11.muted = true;
    video11.play();
  });

  // 한려해상 각산
  var hls12 = new Hls();
  var source = 'http://220.70.164.229/hls/gaksan.m3u8';
  var video12 = document.getElementById('video12');
  hls12.loadSource(source);
  hls12.attachMedia(video12);
  hls12.on(Hls.Events.MANIFEST_PARSED,function() {
    video12.muted = true;
    video12.play();
  });

  // 제주공항
  var hls_jeju1 = new Hls();
  var source = 'http://123.140.197.51/stream/33/play.m3u8';
  var video_jeju1 = document.getElementById('video-jeju1');
  hls_jeju1.loadSource(source);
  hls_jeju1.attachMedia(video_jeju1);
  hls_jeju1.on(Hls.Events.MANIFEST_PARSED,function() {
    video_jeju1.muted = true;
    video_jeju1.play();
  });

  // 용두암 해안
  var hls_jeju2 = new Hls();
  var source = 'http://59.8.86.15:1935/live/51.stream/playlist.m3u8';
  var video_jeju2 = document.getElementById('video-jeju2');
  hls_jeju2.loadSource(source);
  hls_jeju2.attachMedia(video_jeju2);
  hls_jeju2.on(Hls.Events.MANIFEST_PARSED,function() {
    video_jeju2.muted = true;
    video_jeju2.play();
  });
</script>