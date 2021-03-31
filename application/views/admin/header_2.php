<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title><?=!empty($viewClub['title']) ? $viewClub['title'] : '한국여행 :: TripKorea.net'?><?=!empty($pageTitle) ? ' - ' . $pageTitle : ''?></title>

  <meta property="og:title" content="경인웰빙산악회" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="<?=BASE_URL?>" />
  <meta property="og:image" content="/public/images/logo.png" />
  <meta property="og:description" content="인천과 부천 지역을 기반으로 토요산행과 일요산행을 매주 운행하는 안내산악회입니다. 차내 음주가무 없으며 초보자도 함께할 수 있도록 여유롭게 산행을 진행합니다.">
  <meta name="description" content="인천과 부천 지역을 기반으로 토요산행과 일요산행을 매주 운행하는 안내산악회입니다. 차내 음주가무 없으며 초보자도 함께할 수 있도록 여유롭게 산행을 진행합니다.">

  <link rel="icon" type="image/png" href="/public/images/favicon.png">
  <link rel="shortcut icon" type="image/png" href="/public/images/favicon.png">
  <link rel="apple-touch-icon" sizes="76x76" href="/public/images/apple-touch-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="120x120" href="/public/images/apple-touch-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="152x152" href="/public/images/apple-touch-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/public/images/apple-touch-icon-180x180.png">
  <link rel="apple-touch-icon" sizes="256x256" href="/public/images/apple-touch-icon-256x256.png">

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
  <link href="/public/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR&display=swap" rel="stylesheet">

  <script type="text/javascript" src="/public/js/jquery-3.3.1.min.js"></script>
  <script type="text/javascript" src="/public/js/jquery-ui.custom.min.js"></script>
  <script type="text/javascript" src="/public/js/jquery.easing.min.js"></script>
  <script type="text/javascript" src="/public/js/bootstrap.min.js"></script>
  <script src="/public/lib/touchSwipe/jquery.touchSwipe.min.js"></script>
  <script src="/public/js/fullcalendar.js" type="text/javascript"></script>
  <script src="/public/js/clipboard.min.js" type="text/javascript"></script>
  <script src="/public/photoswipe/photoswipe.min.js" type="text/javascript"></script>
  <script src="/public/photoswipe/photoswipe-ui-default.min.js" type="text/javascript"></script>
  <script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
  <script src="/public/js/admin.js?<?=time()?>" type="text/javascript"></script>
  <script src="/public/js/tripkorea.js?<?=time()?>" type="text/javascript"></script>
  <?php if (ENVIRONMENT == 'production' && $_SERVER['REMOTE_ADDR'] != '49.166.0.82'): ?>
  <script data-ad-client="ca-pub-2424708381875991" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
  <?php endif; ?>

  <link href="/public/photoswipe/photoswipe.css" rel="stylesheet">
  <link href="/public/photoswipe/default-skin/default-skin.css" rel="stylesheet">

  <link href="/public/css/style_<?=$viewClub['main_design']?>.css?<?=time()?>" rel="stylesheet">

  <link href="/public/css/fullcalendar.css" rel="stylesheet">
  <link href="/public/css/fullcalendar.print.css" rel="stylesheet">
</head>
<body id="page-top" class="fixed-sn homepage-v2">

  <header>
    <nav id="mainNav" class="navbar navbar-expand-lg navbar-dark">
      <a class="navbar-brand" href="<?=BASE_URL?>"><img width="45" src="/public/images/icon.png" style="margin-right: 10px; float: left;"><span class="logo">경인웰빙</span></a>
      <button class="navbar-toggler" type="button"><i class="fa fa-cog text-white" aria-hidden="true"></i>
      </button>
      <div class="navbar-sideview">
        <hr>
        <a href="http://<?=goHome($viewClub)?>" class="nav-link"><i class="fas fa-chalkboard" aria-hidden="true"></i> 산악회 홈</a>
        <a href="<?=BASE_URL?>/admin/main_list_progress" class="nav-link"><i class="fas fa-mountain" aria-hidden="true"></i> 산행관리</a>
        <a href="<?=BASE_URL?>/ShopAdmin/order" class="nav-link"><i class="fas fa-shopping-cart" aria-hidden="true"></i> 구매대행</a>
        <a href="<?=BASE_URL?>/admin/member_list" class="nav-link"><i class="fas fa-users" aria-hidden="true"></i> 회원관리</a>
        <a href="<?=BASE_URL?>/admin/log_user" class="nav-link"><i class="fas fa-users" aria-hidden="true"></i> 활동관리</a>
        <a href="<?=BASE_URL?>/admin/attendance_auth" class="nav-link"><i class="fa fa-check-square" aria-hidden="true"></i> 백산백소</a>
        <a href="<?=BASE_URL?>/admin/setup_information" class="nav-link"><i class="fas fa-users" aria-hidden="true"></i> 기본설정</a>
        <img src="/public/images/banner_cafe.png" onClick="window.open('https://cafe.daum.net/giwb');">
      </div>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a href="http://<?=goHome($viewClub)?>" class="nav-link"><i class="fas fa-chalkboard" aria-hidden="true"></i> 산악회 홈</a>
          </li>
          <li class="nav-item">
            <a href="<?=BASE_URL?>/admin/main_list_progress" class="nav-link"><i class="fas fa-mountain" aria-hidden="true"></i> 산행관리</a>
          </li>
          <li class="nav-item">
            <a href="<?=BASE_URL?>/ShopAdmin/order" class="nav-link"><i class="fas fa-shopping-cart" aria-hidden="true"></i> 구매대행</a>
          </li>
          <li class="nav-item">
            <a href="<?=BASE_URL?>/admin/member_list" class="nav-link"><i class="fas fa-users" aria-hidden="true"></i> 회원관리</a>
          </li>
          <li class="nav-item">
            <a href="<?=BASE_URL?>/admin/log_user" class="nav-link"><i class="fas fa-users" aria-hidden="true"></i> 활동관리</a>
          </li>
          <?php if ($viewClub['idx'] == 1): ?>
          <li class="nav-item">
            <a href="<?=BASE_URL?>/admin/attendance_auth" class="nav-link"><i class="fa fa-check-square" aria-hidden="true"></i> 백산백소</a>
          </li>
          <?php endif; ?>
          <li class="nav-item">
            <a href="<?=BASE_URL?>/admin/setup_information" class="nav-link"><i class="fas fa-users" aria-hidden="true"></i> 기본설정</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropMenuProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <?php if (!empty($userData['idx']) && file_exists(AVATAR_PATH . $userData['idx'])): ?>
              <img class="avatar" src="<?=AVATAR_URL . $userData['idx']?>">
              <?php elseif (!empty($userData['icon_thumbnail'])): ?>
              <img class="avatar" src="<?=$userData['icon_thumbnail']?>">
              <?php else: ?>
              <img class="avatar" src="/public/images/user.png">
              <?php endif; ?>
            </a>
            <div class="dropdown-menu dropdown-endmenu dropdown-primary" aria-labelledby="navbarDropMenuProfile">
              <strong><?=$userData['nickname']?></strong> (<?=$userLevel['levelName']?>)<hr>
              <a href="<?=BASE_URL?>/member">마이페이지</a><br>
              <?php if ($userData['level'] == LEVEL_DRIVER || $userData['level'] == LEVEL_DRIVER_ADMIN || (!empty($userData['admin']) && $userData['admin'] == 1)): ?>
              <a href="<?=BASE_URL?>/member/driver">드라이버 페이지</a><br>
              <?php endif; ?>
              <a href="<?=BASE_URL?>/member/modify">개인정보수정</a><br>
              <a href="javascript:;" class="logout">로그아웃</a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <main>
    <div class="container-fluid mt-4">
      <div class="row mt-1">
        <div class="col-xl-8 col-md-12">
