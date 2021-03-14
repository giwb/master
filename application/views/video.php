<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<link href="https://vjs.zencdn.net/7.10.2/video-js.css" rel="stylesheet" />
<script src="https://vjs.zencdn.net/7.10.2/video.min.js"></script>
<style>
  video { width: 100%; }
</style>

  <main>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-8 col-md-12">
          <div class="row">
            <div class="col-7"><h4 class="font-weight-bold"><strong>실시간 현지영상</strong></h4></div>
            <div class="col-5"><div class="row"><div class="col-10"><input type="text" class="form-control form-control-sm"></div><div class="col-2 pl-0 pr-0"><button type="button" class="btn btn-sm btn-default pt-2 pb-2 pl-3 pr-3 m-0">검색</button></div></div></div>
          </div><hr class="red mt-2">
          <div class="row">
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body">
                  <h4 class="text-center">한려해상 국립공원 각산</h4>
                  <video class="video-js" controls preload="auto" width="440" height="249" data-setup="{}">
                    <source src="http://220.70.164.229/hls/gaksan.m3u8" type="application/x-mpegurl" class="video-source" />
                  </video>
                </div>
                <div class="mdb-color lighten-3 text-center">
                  <ul class="list-unstyled list-inline font-small mt-3">
                    <li class="list-inline-item pr-1"><a href="detail.html" class="white-text"><i class="far fa-eye pr-1"></i>조회 70</a></li>
                    <li class="list-inline-item pr-1"><a href="detail.html" class="white-text"><i class="far fa-comments pr-1"></i>댓글 15</a></li>
                    <li class="list-inline-item pr-1"><a href="detail.html" class="white-text"><i class="far fa-calendar-check pr-1"></i>예약 32</a></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body">
                  <h4 class="text-center">덕유산 국립공원 설천봉</h4>
                  <video class="video-js" controls preload="auto" width="440" height="249" data-setup="{}">
                    <source src="http://220.70.164.229/hls/zsulchun.m3u8" type="application/x-mpegurl" class="video-source" />
                  </video>
                </div>
                <div class="mdb-color lighten-3 text-center">
                  <ul class="list-unstyled list-inline font-small mt-3">
                    <li class="list-inline-item pr-1"><a href="detail.html" class="white-text"><i class="far fa-eye pr-1"></i>조회 70</a></li>
                    <li class="list-inline-item pr-1"><a href="detail.html" class="white-text"><i class="far fa-comments pr-1"></i>댓글 15</a></li>
                    <li class="list-inline-item pr-1"><a href="detail.html" class="white-text"><i class="far fa-calendar-check pr-1"></i>예약 32</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
