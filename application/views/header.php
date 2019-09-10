<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>경인웰빙</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
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
  <link href="/public/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="/public/lib/animate/animate.min.css" rel="stylesheet">
  <link href="/public/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="/public/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="/public/lib/lightbox/css/lightbox.min.css" rel="stylesheet">
  <link href="/public/css/slick.css" rel="stylesheet">
  <link href="/public/css/slick-theme.css" rel="stylesheet">

  <!-- Main Stylesheet File -->
  <link href="/public/css/style.css?<?=time()?>" rel="stylesheet">

  <script src="/public/js/jquery-1.11.1.min.js" type="text/javascript"></script>
  <script src="/public/js/jquery-ui.custom.min.js" type="text/javascript"></script>
  <script src="/public/js/slick.min.js" type="text/javascript"></script>
</head>

<body>

  <!--==========================
    Header
  ============================-->
  <header id="header">
    <div class="container-fluid">

      <div id="logo" class="pull-left">
        <h1><a href="#intro" class="scrollto">경인 웰빙 산악회</a></h1>
      </div>

      <nav id="nav-menu-container">
        <ul class="nav-menu">
          <li class="menu-active"><a href="#intro">TOP</a></li>
          <li><a href="<?=base_url()?>reservation">산행일정</a></li>
          <li class="menu-has-children">
            <a href="javascript:;" class="sf-with-ul">산악회소개</a>
            <ul>
              <li><a href="#">산행 이력</a></li>
              <li><a href="#">등산안내인 소개</a></li>
              <li><a href="#">운영진 소개</a></li>
              <li><a href="#">이용안내</a></li>
            </ul>
          </li>
          <li><a href="<?=base_url()?>member">마이페이지</a></li>
<?php if ($userData['idx'] == ''): ?>
          <li><a href="javascript:;" class="login-popup">로그인</a></li>
<?php else: ?>
          <li><a href="javascript:;" class="logout">로그아웃</a></li>
<?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>
