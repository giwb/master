<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html5>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>경인웰빙</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="height=device-height, width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="robots" content="noindex">
  <meta property="og:title" content="경인웰빙" />
  <meta property="og:image" content="<?=base_url()?>public/images/logo.jpg" />
  <meta property="og:url" content="http://giwb.kr/" />

  <!-- Favicons -->
  <link href="/public/images/favicon.png" rel="icon">
  <link href="/public/images/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS File -->
  <link href="/public/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Libraries CSS Files -->
  <link href="/public/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="/public/lib/animate/animate.min.css" rel="stylesheet">
  <link href="/public/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="/public/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="/public/lib/lightbox/css/lightbox.min.css" rel="stylesheet">
  <link href="/public/css/fullcalendar.css" rel="stylesheet">
  <link href="/public/css/fullcalendar.print.css" rel="stylesheet">

  <!-- Main Stylesheet File -->
  <link href="/public/css/style.css" rel="stylesheet">

  <!--<script src="/public/js/jquery-1.11.1.min.js" type="text/javascript"></script>
  <script src="/public/js/jquery-ui.custom.min.js" type="text/javascript"></script>-->
<script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
<script src="http://code.jquery.com/ui/1.8.21/jquery-ui.min.js"></script>

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
  <script src="/public/js/fullcalendar.js" type="text/javascript"></script>

  <!-- SmartEditor -->
  <script src="/public/editor/js/service/HuskyEZCreator.js" type="text/javascript" charset="utf-8"></script>

  <!-- Template Main Javascript File -->
  <script src="/public/js/main.js"></script>

</head>
<body>

  <!-- HEADER -->
  <header id="header">
    <!-- NAV -->
    <div id="nav">
      <!-- Top Nav -->
      <div id="nav-top">
        <div class="container">
          <h1 class="nav-logo">
            <a href="<?=base_url()?>" class="logo">경인웰빙</a>
          </h1>
          <ul class="nav-menu">
            <li<?=$uri == 'top' ? ' class="active"' : ''?>><a href="<?=base_url()?>">TOP</a></li>
            <li<?=$uri == 'place' ? ' class="active"' : ''?>><a href="http://tripkorea.net/place">여행정보</a></li>
            <li<?=$uri == 'club' ? ' class="active"' : ''?>><a href="http://tripkorea.net/club">산악회정보</a></li>
<?php if ($userData['idx'] == ''): ?>
            <li><a href="javascript:;" class="login-popup">로그인</a></li>
            <!--<li><button class="search-btn"><i class="fa fa-search"></i></button></li>-->
<?php else: ?>
            <!--<li><button class="search-btn"><i class="fa fa-search"></i></button></li>-->
            <li>
              <img class="img-profile" src="<?=base_url()?>public/images/profile.png">
              <div class="profile-box">
                <strong><?=$userData['nickname']?></strong><hr>
                <a href="javascript:;">내 정보</a><br>
                <a href="javascript:;">내 클럽</a><hr>
                <a href="javascript:;" class="logout">로그아웃</a>
              </div>
            </li>
<?php endif; ?>
          </ul>
        </div>
      </div>
      <!-- /Top Nav -->

      <!-- Aside Nav -->
      <div class="nav-btns">
        <button class="aside-btn"><i class="fa fa-bars"></i></button>
        <button class="search-btn"><i class="fa fa-search"></i></button>
      </div>
      <div id="nav-aside">
        <ul class="nav-aside-menu">
<?php if ($userData['idx'] != ''): ?>
          <li><img class="img-profile" src="<?=base_url()?>public/images/profile.png"><span class="header-nickname"><?=$userData['nickname']?></span></li>
<?php else: ?>
          <li><p>&nbsp;</p></li>
<?php endif; ?>
          <li><a href="<?=base_url()?>">TOP</a></li>
          <li><a href="javascript:;">산행일정</a></li>
          <li><a href="javascript:;">사진첩</a></li>
          <li><a href="http://tripkorea.net/place">여행정보</a></li>
          <li><a href="http://tripkorea.net/club">산악회정보</a></li>
<?php if ($userData['idx'] == ''): ?>
          <li><a href="javascript:;" class="login-popup">로그인</a></li>
<?php else: ?>
          <li><a href="javascript:;">내 정보</a></li>
          <li><a href="javascript:;">내 클럽</a></li>
          <li><a href="javascript:;" class="logout">로그아웃</a></li>
<?php endif; ?>
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
