<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>경인웰빙 - 산행일정</title>
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

  <!-- Main Stylesheet File -->
  <link href="/public/css/style.css" rel="stylesheet">

  <script src="/public/js/jquery-1.11.1.min.js" type="text/javascript"></script>
  <script src="/public/js/jquery-ui.custom.min.js" type="text/javascript"></script>
</head>

<body>

  <!--==========================
    Header
  ============================-->
  <header id="header" class="subpage">
    <div class="container-fluid">

      <div id="logo" class="pull-left">
        <h1><a href="<?=base_url()?>" class="scrollto">경인 웰빙 산악회</a></h1>
      </div>

      <nav id="nav-menu-container">
        <ul class="nav-menu">
          <li><a href="<?=base_url()?>">TOP</a></li>
          <li><a href="<?=base_url()?>reservation">산행일정</a></li>
          <li class="menu-active"><a href="<?=base_url()?>auth">백산백소</a></li>
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
