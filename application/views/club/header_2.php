<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title><?=!empty($view['title']) ? $view['title'] : '한국여행 :: TripKorea.net'?><?=!empty($pageTitle) ? ' - ' . $pageTitle : ''?></title>

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
  <script src="/public/js/fullcalendar/main.min.js" type="text/javascript"></script>
  <script src="/public/js/clipboard.min.js" type="text/javascript"></script>
  <script src="/public/photoswipe/photoswipe.min.js" type="text/javascript"></script>
  <script src="/public/photoswipe/photoswipe-ui-default.min.js" type="text/javascript"></script>
  <script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
  <script src="/public/js/main.js?<?=time()?>" type="text/javascript"></script>
  <?php if (ENVIRONMENT == 'production' && $_SERVER['REMOTE_ADDR'] != '49.166.0.82'): ?>
  <script data-ad-client="ca-pub-2424708381875991" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
  <?php endif; ?>

  <link href="/public/photoswipe/photoswipe.css" rel="stylesheet">
  <link href="/public/photoswipe/default-skin/default-skin.css" rel="stylesheet">

  <link href="/public/css/style_<?=$view['main_design']?>.css?<?=time()?>" rel="stylesheet">
  <link href="/public/js/fullcalendar/main.min.css" rel="stylesheet">

  <script type="text/javascript">
    //new ClipboardJS('.btn-share-url');
  </script>
  <script type="text/javascript">(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script>
</head>
<body id="page-top" class="fixed-sn homepage-v2">

  <header>
    <nav id="mainNav" class="navbar navbar-expand-lg navbar-dark">
      <a class="navbar-brand" href="<?=BASE_URL?>"><img width="45" src="/public/images/icon.png" style="margin-right: 10px; float: left;"><span class="logo">경인웰빙</span></a>
      <button class="navbar-toggler" type="button"><i class="fa fa-cog text-white" aria-hidden="true"></i>
      </button>
      <div class="navbar-sideview">
        <hr>
        <?php if (empty($userData['idx'])): ?>
        <?php if (!strstr($_SERVER['REQUEST_URI'], 'login')): ?><a class="nav-link login-popup">로그인</a><?php endif; ?>
        <a href="<?=BASE_URL?>/login/entry" class="nav-link">회원가입</a>
        <?php else: ?>
        <div class="text-center"><img src="<?=file_exists(AVATAR_PATH . $userData['idx']) ? AVATAR_URL . $userData['idx'] : '/public/images/user.png'?>" class="avatar"></div>
        <div class="text-center"><strong><?=$userData['nickname']?></strong> <small>(<?=$userLevel['levelName']?>)</small></div><hr>
        <a href="<?=BASE_URL?>/member" class="nav-link">마이페이지</a>
        <?php if ($userData['level'] == LEVEL_DRIVER || $userData['level'] == LEVEL_DRIVER_ADMIN || (!empty($userData['admin']) && $userData['admin'] == 1)): ?>
        <a href="<?=BASE_URL?>/member/driver" class="nav-link">드라이버 페이지</a>
        <?php endif; ?>
        <a href="<?=BASE_URL?>/member/modify" class="nav-link">개인정보수정</a>
        <?php if (!empty($userData['admin']) && $userData['admin'] == 1): ?>
        <a href="<?=BASE_URL?>/admin">관리 페이지</a>
        <?php endif; ?>
        <a class="nav-link logout">로그아웃</a>
        <?php endif; ?>
        <br><img src="/public/images/banner_cafe.png" onClick="window.open('https://cafe.daum.net/giwb');">
      </div>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a href="<?=BASE_URL?>/reserve/schedule" class="nav-link"><i class="far fa-calendar-alt"></i> 일정</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false"><i class="fas fa-chalkboard"></i> 소개</a>
            <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink1">
              <?php foreach ($listAbout as $value): ?>
              <a class="dropdown-item" href="<?=BASE_URL?>/club/about/<?=$value['idx']?>"><?=$value['title']?></a>
              <?php endforeach; ?>
              <?php if (!empty($userLevel['levelType']) && $userLevel['levelType'] >= 1): ?>
              <a class="dropdown-item" href="<?=BASE_URL?>/club/past">지난산행</a>
              <?php endif; ?>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false"><i class="fa fa-check-square" aria-hidden="true"></i> 백산백소</a>
            <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink2">
              <a class="dropdown-item" href="<?=BASE_URL?>/club/auth">백산백소 인증현황</a>
              <a class="dropdown-item" href="<?=BASE_URL?>/club/page?type=mountain">경인웰빙 100대명산</a>
              <a class="dropdown-item" href="<?=BASE_URL?>/club/page?type=forest">경인웰빙 100대명소</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink3" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false"><i class="fas fa-map-marked-alt"></i> 여행기</a>
            <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink3">
              <a class="dropdown-item" href="<?=BASE_URL?>/album">사진첩</a>
              <a class="dropdown-item" href="<?=BASE_URL?>/album/best">추천 사진</a>
              <a class="dropdown-item" href="<?=BASE_URL?>/club/video">동영상</a>
              <a class="dropdown-item" href="<?=BASE_URL?>/club/search/?code=news">여행 소식</a>
              <a class="dropdown-item" href="<?=BASE_URL?>/club/search/?code=review">여행 후기</a>
            </div>
          </li>
          <li class="nav-item">
            <a href="<?=BASE_URL?>/shop" class="nav-link"><i class="fas fa-shopping-cart"></i> 용품샵</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink4" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false"><i class="fas fa-chart-line"></i> 활동내역</a>
            <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink4">
              <a class="dropdown-item" href="<?=BASE_URL?>/club/status">전체보기</a>
              <a class="dropdown-item" href="<?=BASE_URL?>/club/status?type=1">산행현황</a>
              <a class="dropdown-item" href="<?=BASE_URL?>/club/status?type=2">백산백소 인증현황</a>
              <?php if (!empty($userData['admin']) && $userData['admin'] == 1): ?><a class="dropdown-item" href="<?=BASE_URL?>/club/status?type=3">홈페이지 방문</a><?php endif; ?>
            </div>
          </li>
          <?php if (empty($userData['idx'])): ?>
          <?php if (!strstr($_SERVER['REQUEST_URI'], 'login')): ?>
          <li class="nav-item">
            <a class="nav-link login-popup"><i class="fas fa-user-circle"></i> 로그인</a>
          </li>
          <?php endif; ?>
          <li class="nav-item">
            <a href="<?=BASE_URL?>/login/entry" class="nav-link"><i class="fas fa-user-plus"></i> 회원가입</a>
          </li>
          <?php else: ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink5" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false"><i class="fas fa-user"></i> 마이페이지</a>
            <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink5" style="white-space: nowrap;">
              <a><strong><?=$userData['nickname']?></strong> (<?=$userLevel['levelName']?>)</a><hr>
              <a href="<?=BASE_URL?>/member">마이페이지</a><br>
              <?php if ($userData['level'] == LEVEL_DRIVER || $userData['level'] == LEVEL_DRIVER_ADMIN || (!empty($userData['admin']) && $userData['admin'] == 1)): ?>
              <a href="<?=BASE_URL?>/member/driver">드라이버 페이지</a><br>
              <?php endif; ?>
              <a href="<?=BASE_URL?>/member/modify">개인정보수정</a><br>
              <a href="javascript:;" class="logout">로그아웃</a>
            </div>
          </li>
          <?php endif; ?>
          <li class="nav-item">
            <a href="https://cafe.daum.net/giwb" target="_blank" class="nav-link"><i class="fa fa-desktop"></i> 다음카페</a>
          </li>
          <?php if (!empty($userData['admin']) && $userData['admin'] == 1): ?>
          <li class="nav-item">
            <a href="<?=BASE_URL?>/admin" class="nav-link"><i class="fas fa-cog"></i> 관리</a>
          </li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>
  </header>
