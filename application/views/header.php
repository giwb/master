<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html5>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>한국여행 :: TripKorea.net<?=!empty($pageTitle) ? ' - ' . $pageTitle : ''?></title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="viewport" content="height=device-height, width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="robots" content="noindex">
  <meta content="" name="description">

  <!-- Favicons -->
  <link href="/public/images/favicon.png" rel="icon">
  <link href="/public/images/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS File -->
  <link href="/public/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Libraries CSS Files -->
  <link href="/public/css/fontawesome/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="/public/lib/animate/animate.min.css" rel="stylesheet">
  <link href="/public/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="/public/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="/public/lib/lightbox/css/lightbox.min.css" rel="stylesheet">
  <link href="/public/css/fullcalendar.css" rel="stylesheet">
  <link href="/public/css/fullcalendar.print.css" rel="stylesheet">

  <!-- Main Stylesheet File -->
  <link href="/public/css/tripkorea.css?<?=time()?>" rel="stylesheet">

  <script src="/public/js/jquery-1.11.1.min.js" type="text/javascript"></script>
  <script src="/public/js/jquery-ui.custom.min.js" type="text/javascript"></script>

  <!-- JavaScript Libraries -->
  <script src="/public/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/public/lib/easing/easing.min.js"></script>
  <script src="/public/lib/superfish/hoverIntent.js"></script>
  <script src="/public/lib/superfish/superfish.min.js"></script>
  <script src="/public/lib/wow/wow.min.js"></script>
  <script src="/public/lib/waypoints/waypoints.min.js"></script>
  <script src="/public/lib/counterup/counterup.min.js"></script>
  <script src="/public/lib/owlcarousel/owl.carousel.min.js"></script>
  <script src="/public/lib/isotope/isotope.pkgd.min.js"></script>
  <script src="/public/lib/lightbox/js/lightbox.min.js"></script>
  <script src="/public/lib/touchSwipe/jquery.touchSwipe.min.js"></script>
  <script src="/public/ckeditor/ckeditor.js" type="text/javascript" charset="utf-8"></script>
  <script src="/public/js/fullcalendar.js" type="text/javascript"></script>

  <!-- Template Main Javascript File -->
  <script src="/public/js/main.js"></script>

</head>
<body>

<header>
  <div class="container p-0">
    <div class="row align-items-center">
      <div class="col-sm-3 pl-4"><a href="<?=BASE_URL?>"><h1>한국여행 <small>TripKorea.net</small></h1></a></div>
      <div class="col-sm-6 row">
        <div class="col-sm-11"><input type="text" class="form-control form-control-sm"></div>
        <div class="col-sm-1 pl-0"><button class="btn btn-sm btn-secondary w-100">검색</button></div>
      </div>
      <div class="col-sm-3 text-right d-none d-sm-block">
        <ul class="row align-items-center">
          <li class="col p-0"></li>
          <li class="col p-0"><a href="/place" class="font-weight-bold">여행정보</a></li>
          <li class="col p-0"><a href="/club" class="font-weight-bold">산악회</a></li>
          <?php if (!empty($userData['idx'])): ?>
          <li class="col p-0 pr-3">
            <img class="img-profile" src="<?=$userData['icon']?>">
            <div class="profile-box">
              <strong><?=$userData['nickname']?></strong><hr>
              <a href="#">내 정보</a><br>
              <a href="#">내 클럽</a><hr>
              <a href="#">초대 확인</a><br>
              <a href="#">공지사항</a><br>
              <a href="#">설정</a><hr>
              <a href="javascript:;" class="logout">로그아웃</a>
            </div>
          </li>
          <?php else: ?>
          <li class="col p-0 pr-3"><a href="javascript:;" class="font-weight-bold login-popup">로그인</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
</header>

<main>
  <nav>
    <ul>
      <li class="title"><a href="<?=BASE_URL?>"><i class="fas fa-home"></i> 홈</a></li>
    </ul>
    <ul class="mt-3 pt-3 border-top">
      <li class="title"><i class="fas fa-map-marked-alt"></i> 여행정보</li>
      <li><a href="/place/?search=type&keyword=type1">전체보기</a></li>
      <li><a href="/place/?search=type&keyword=type2">산림청 100대 명산</a></li>
      <li><a href="/place/?search=type&keyword=type3">블랙야크 명산100</a></li>
      <li><a href="/place/?search=type&keyword=type4">국내여행 1001</a></li>
      <li><a href="/place/?search=type&keyword=type5">백두대간</a></li>
      <li><a href="/place/?search=type&keyword=type6">도보 트레킹</a></li>
      <li><a href="/place/?search=type&keyword=type7">유명 관광지</a></li>
      <li><a href="/place/?search=type&keyword=type8">섬 여행</a></li>
      <li><a href="/place/?search=type&keyword=type8">자연 휴양림</a></li>
      <li><a href="/place/?search=type&keyword=type8">캠핑장</a></li>
    </ul>
    <ul class="mt-3 pt-3 border-top">
      <li class="title"><i class="fas fa-mountain"></i> 산악회</li>
      <li><a href="/club">전체보기</a></li>
      <li><a href="/club/?s=area_sido&k=11000">서울특별시</a><li>
      <li><a href="/club/?s=area_sido&k=12000">부산광역시</a><li>
      <li><a href="/club/?s=area_sido&k=13000">대구광역시</a><li>
      <li><a href="/club/?s=area_sido&k=14000">인천광역시</a><li>
      <li><a href="/club/?s=area_sido&k=15000">광주광역시</a><li>
      <li><a href="/club/?s=area_sido&k=16000">대전광역시</a><li>
      <li><a href="/club/?s=area_sido&k=17000">울산광역시</a><li>
      <li><a href="/club/?s=area_sido&k=18000">세종특별자치시</a><li>
      <li><a href="/club/?s=area_sido&k=19000">경기도</a><li>
      <li><a href="/club/?s=area_sido&k=20000">강원도</a><li>
      <li><a href="/club/?s=area_sido&k=21000">충청북도</a><li>
      <li><a href="/club/?s=area_sido&k=22000">충청남도</a><li>
      <li><a href="/club/?s=area_sido&k=23000">전라북도</a><li>
      <li><a href="/club/?s=area_sido&k=24000">전라남도</a><li>
      <li><a href="/club/?s=area_sido&k=25000">경상북도</a><li>
      <li><a href="/club/?s=area_sido&k=26000">경상남도</a><li>
      <li><a href="/club/?s=area_sido&k=27000">제주도</a><li>
    </ul>
  </nav>
