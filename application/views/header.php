<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html5>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>한국여행 :: TripKorea.net</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="viewport" content="height=device-height, width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="robots" content="noindex">
  <meta content="" name="description">

  <!-- Favicons -->
  <link href="<?=base_url()?>public/images/favicon.png" rel="icon">
  <link href="<?=base_url()?>public/images/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS File -->
  <link href="<?=base_url()?>public/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Libraries CSS Files -->
  <link href="<?=base_url()?>public/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?=base_url()?>public/lib/animate/animate.min.css" rel="stylesheet">
  <link href="<?=base_url()?>public/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="<?=base_url()?>public/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="<?=base_url()?>public/lib/lightbox/css/lightbox.min.css" rel="stylesheet">
  <link href="<?=base_url()?>public/css/fullcalendar.css" rel="stylesheet">
  <link href="<?=base_url()?>public/css/fullcalendar.print.css" rel="stylesheet">

  <!-- Main Stylesheet File -->
  <link href="<?=base_url()?>public/css/style.css" rel="stylesheet">

  <script src="<?=base_url()?>public/js/jquery-1.11.1.min.js" type="text/javascript"></script>
  <script src="<?=base_url()?>public/js/jquery-ui.custom.min.js" type="text/javascript"></script>

  <!-- JavaScript Libraries -->
  <script src="<?=base_url()?>public/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?=base_url()?>public/lib/easing/easing.min.js"></script>
  <script src="<?=base_url()?>public/lib/superfish/hoverIntent.js"></script>
  <script src="<?=base_url()?>public/lib/superfish/superfish.min.js"></script>
  <script src="<?=base_url()?>public/lib/wow/wow.min.js"></script>
  <script src="<?=base_url()?>public/lib/waypoints/waypoints.min.js"></script>
  <script src="<?=base_url()?>public/lib/counterup/counterup.min.js"></script>
  <script src="<?=base_url()?>public/lib/owlcarousel/owl.carousel.min.js"></script>
  <script src="<?=base_url()?>public/lib/isotope/isotope.pkgd.min.js"></script>
  <script src="<?=base_url()?>public/lib/lightbox/js/lightbox.min.js"></script>
  <script src="<?=base_url()?>public/lib/touchSwipe/jquery.touchSwipe.min.js"></script>
  <script src="<?=base_url()?>public/js/fullcalendar.js" type="text/javascript"></script>

  <!-- Template Main Javascript File -->
  <script src="<?=base_url()?>public/js/main.js"></script>

</head>
<body>

  <!-- HEADER -->
  <header id="header">
    <!-- NAV -->
    <div id="nav">
      <!-- Top Nav -->
      <div id="nav-top">
        <div class="container">
          <div class="row align-items-center">
            <h1 class="col-sm-6 nav-logo"><a href="<?=base_url()?>" class="logo">한국여행 <small>TripKorea.net</small></a></h1>
            <ul class="col-sm-6 text-right nav-menu">
              <li<?=$uri == 'top' ? ' class="active"' : ''?>><a href="<?=base_url()?>">TOP</a></li>
              <li<?=$uri == 'place' ? ' class="active"' : ''?>><a href="<?=base_url()?>place">여행정보</a></li>
              <li<?=$uri == 'club' ? ' class="active"' : ''?>><a href="<?=base_url()?>club">산악회정보</a></li>
              <?php if (!empty($userData['idx'])): ?>
              <li>
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
              <li><a href="javascript:;" class="login-popup">로그인</a></li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </div>
      <!-- /Top Nav -->

      <!-- Aside Nav -->
      <div class="nav-btns">
        <button class="aside-btn"><i class="fa fa-bars"></i></button>
      </div>
      <div id="nav-aside">
        <ul class="nav-aside-menu">
          <li><img class="img-profile" src="<?=base_url()?>public/images/user.png"></li>
          <li<?=$uri == 'top' ? ' class="active"' : ''?>><a href="<?=base_url()?>">TOP</a></li>
          <li<?=$uri == 'place' ? ' class="active"' : ''?>><a href="<?=base_url()?>place">여행정보</a></li>
          <li<?=$uri == 'club' ? ' class="active"' : ''?>><a href="<?=base_url()?>club">산악회정보</a></li>
        </ul>
        <button class="nav-close nav-aside-close"><span></span></button>
      </div>
      <!-- /Aside Nav -->

      <!-- /Nav Search -->
      <div id="nav-search">
        <form>
          <input class="input" name="search" placeholder="검색할 내용을 입력해 주세요.">
        </form>
        <button class="nav-close search-close">
          <span></span>
        </button>
      </div>
      <!-- /Nav Search -->
    </div>
    <!-- /NAV -->
  </header>
  <!-- /HEADER -->
