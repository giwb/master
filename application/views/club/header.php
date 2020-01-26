<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html5>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>경인웰빙산악회<?=!empty($pageTitle) ? ' - ' . $pageTitle : ''?></title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="height=device-height, width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">

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

  <link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR&display=swap" rel="stylesheet">
  <link href="/public/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/public/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="/public/lib/animate/animate.min.css" rel="stylesheet">
  <link href="/public/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="/public/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="/public/lib/lightbox/css/lightbox.min.css" rel="stylesheet">
  <link href="/public/css/fullcalendar.css" rel="stylesheet">
  <link href="/public/css/fullcalendar.print.css" rel="stylesheet">
  <link href="/public/photoswipe/photoswipe.css" rel="stylesheet">
  <link href="/public/photoswipe/default-skin/default-skin.css" rel="stylesheet">
  <!--<link href="/public/css/magnific-popup.css" rel="stylesheet">-->
  <link href="/public/css/style.css?<?=time()?>" rel="stylesheet">

  <script src="/public/js/jquery-2.1.4.min.js" type="text/javascript"></script>
  <script src="/public/js/jquery-ui.custom.min.js" type="text/javascript"></script>
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
  <script src="/public/js/clipboard.min.js" type="text/javascript"></script>
  <script src="/public/photoswipe/photoswipe.min.js" type="text/javascript"></script>
  <script src="/public/photoswipe/photoswipe-ui-default.min.js" type="text/javascript"></script>
  <!--<script src="/public/js/jquery.magnific-popup.min.js" type="text/javascript"></script>-->
  <script src="/public/js/main.js?<?=time()?>" type="text/javascript"></script>
  <script data-ad-client="ca-pub-2424708381875991" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

  <!--[if lt IE 9]>
  <script src="/public/js/html5shiv.js"></script>
  <script src="/public/js/respond.min.js"></script>
  <![endif]-->

</head>
<body id="page-top">

  <header id="header">
    <div id="nav">
      <div id="nav-top">
        <div class="container">
          <div class="row align-items-center">
            <h1 class="col-sm-6 nav-logo"><a href="<?=BASE_URL?>" class="logo"><?=$view['title']?></a></h1>
            <ul class="col-sm-6 text-right nav-menu">
              <li><a href="javascript:;" class="btn-post-modal"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></li>
              <?php if (empty($userData['idx'])): ?>
              <li><a href="javascript:;" class="login-popup">로그인</a></li>
              <?php else: ?>
              <li>
              <?php if (!empty($userData['idx']) && file_exists(PHOTO_PATH . $userData['idx'])): ?>
              <img class="img-profile" src="<?=PHOTO_URL . $userData['idx']?>">
              <?php elseif (!empty($userData['icon_thumbnail'])): ?>
              <img class="img-profile" src="<?=$userData['icon_thumbnail']?>">
              <?php else: ?>
              <img class="img-profile" src="/public/images/user.png">
              <?php endif; ?>
                <div class="profile-box">
                  <strong><?=$userData['nickname']?></strong> (<?=$userLevel['levelName']?>)<hr>
                  <a href="<?=BASE_URL?>/member">마이페이지</a><br>
                  <?php if ($userData['level'] == LEVEL_DRIVER || $userData['level'] == LEVEL_DRIVER_ADMIN || (!empty($userData['admin']) && $userData['admin'] == 1)): ?>
                  <a href="<?=BASE_URL?>/member/driver">드라이버 페이지</a><br>
                  <?php endif; ?>
                  <a href="<?=BASE_URL?>/member/modify">개인정보수정</a><br>
                  <a href="javascript:;" class="logout">로그아웃</a>
                </div>
              </li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="nav-btns">
        <!--<a href="<?=BASE_URL?>"><button type="button" class="search-btn"><i class="fa fa-search"></i></button></a>-->
        <a href="javascript:;" class="btn-post-modal"><button type="button" class="search-btn"><i class="fa fa-pencil-square-o"></i></button></a>
        <?php if (!empty($userData['idx']) && file_exists(PHOTO_PATH . $userData['idx'])): ?>
        <button type="button"><img class="img-profile" src="<?=PHOTO_URL . $userData['idx']?>"></button>
        <?php elseif (!empty($userData['icon_thumbnail'])): ?>
        <button type="button"><img class="img-profile" src="<?=$userData['icon_thumbnail']?>"></button>
        <?php else: ?>
        <button type="button" class="login-popup"><i class="fa fa-user-circle" aria-hidden="true"></i></button>
        <?php endif; ?>
        
      </div>
      <div id="nav-aside">
        <ul class="nav-aside-menu">
          <?php if (!empty($userData['idx'])): ?>
          <li>
            <?php if (file_exists(PHOTO_PATH . $userData['idx'])): ?>
            <img class="img-profile" src="<?=PHOTO_URL . $userData['idx']?>">
            <?php elseif (!empty($userData['icon_thumbnail'])): ?>
            <img class="img-profile" src="<?=$userData['icon_thumbnail']?>">
            <?php else: ?>
            <img class="img-profile" src="/public/images/user.png">
            <?php endif; ?>
            <span class="header-nickname"><?=$userData['nickname']?> (<?=$userLevel['levelName']?>)</span></li>
          <?php else: ?>
          <li><p>&nbsp;</p></li>
          <?php endif; ?>
          <li class="row">
            <div class="pl-3"><a target="_blank" title="페이스북 링크" href="https://facebook.com/giwb.kr"><img src="/public/images/icon_facebook.png"></a></div>
            <div class="pl-3"><a target="_blank" title="트위터 링크" href="https://twitter.com/giwb_alpine"><img src="/public/images/icon_twitter.png"></a></div>
            <div class="pl-3"><a target="_blank" title="카카오채널 링크" href="https://pf.kakao.com/_BxaPRxb"><img src="/public/images/icon_kakaotalk.png"></a></div>
            <div class="pl-3"><a target="_blank" title="경인웰빙 카페 링크" href="http://cafe.daum.net/giwb"><img src="/public/images/icon_cafe.png"></a></div>
          </li>
          <li><a href="<?=BASE_URL?>/club/about"> 산악회 소개</a></li>
          <li><a href="<?=BASE_URL?>/club/guide"> 등산 안내인 소개</a></li>
          <?php if ($userLevel['levelType'] >= 2): ?>
          <li><a href="<?=BASE_URL?>/club/past"> 지난 산행보기</a></li>
          <?php endif; ?>
          <li><a href="<?=BASE_URL?>/club/howto"> 이용안내</a></li>
          <?php if ($view['idx'] == 1): ?>
          <br><li><a href="<?=BASE_URL?>club/auth_about"> 백산백소 소개</a></li>
          <li><a href="<?=BASE_URL?>/club/auth"> 백산백소 인증현황</a></li>
          <?php endif; ?>
          <li><a href="<?=BASE_URL?>/album"> 사진첩</a></li>
          <?php if (!empty($userData['admin']) && $userData['admin'] == 1): ?>
          <li><a href="<?=BASE_URL?>/shop"> 구매대행 상품</a></li>
          <br><li><a href="/admin"> 설정</a></li>
          <?php endif; ?>
          <?php if (empty($userData['idx'])): ?>
          <br><li><a href="javascript:;" class="login-popup">로그인</a></li>
          <?php else: ?>
          <br>
          <li><a href="<?=BASE_URL?>/member">마이페이지</a></li>
          <?php if ($userData['level'] == LEVEL_DRIVER || $userData['level'] == LEVEL_DRIVER_ADMIN || (!empty($userData['admin']) && $userData['admin'] == 1)): ?>
          <li><a href="<?=BASE_URL?>/member/driver">드라이버 페이지</a></li>
          <?php endif; ?>
          <li><a href="<?=BASE_URL?>/member/modify">개인정보수정</a></li>
          <li><a href="javascript:;" class="logout">로그아웃</a></li>
          <?php endif; ?>
        </ul>
        <button class="nav-close nav-aside-close"><span></span></button>
      </div>
    </div>
  </header>
  <!-- /HEADER -->

  <section id="club">
    <div class="container">
      <div class="club-left">
        <div class="club-left-layer">
          <div class="club-header">
            <?php if (!empty($view['main_photo'])): ?>
            <!-- 대표 사진 -->
            <a href="javascript:;" class="photo-zoom" data-filename="<?=$view['main_photo']?>" data-width="<?=$view['main_photo_width']?>" data-height="<?=$view['main_photo_height']?>"><img src="<?=$view['main_photo']?>"></a>
            <?php endif; ?>
            <h3><?=$view['title']?></h3>
          </div>
          <?=$view['homepage'] != '' ? '<a href="' . $view['homepage'] . '" class="url">' . $view['homepage'] . '</a>' : ''?>
          <ul class="navi">
            <li><a href="<?=BASE_URL?>/club/about"><i class="fa fa-picture-o" aria-hidden="true"></i> 산악회 소개</a></li>
            <li><a href="<?=BASE_URL?>/club/guide"><i class="fa fa-user-circle" aria-hidden="true"></i> 등산 안내인 소개</a></li>
            <?php if ($userLevel['levelType'] >= 1): ?>
            <li><a href="<?=BASE_URL?>/club/past"><i class="fa fa-calendar" aria-hidden="true"></i> 지난 산행보기</a></li>
            <?php endif; ?>
            <li><a href="<?=BASE_URL?>/club/howto"><i class="fa fa-map-o" aria-hidden="true"></i> 이용안내</a></li>
            <?php if ($view['idx'] == 1): ?><br>
            <li><a href="<?=BASE_URL?>/club/auth_about"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> 백산백소 소개</a></li>
            <li><a href="<?=BASE_URL?>/club/auth"><i class="fa fa-check-square" aria-hidden="true"></i> 백산백소 인증현황</a></li>
            <?php endif; ?><br>
            <li><a href="<?=BASE_URL?>/album"><i class="fa fa-photo" aria-hidden="true"></i> 사진첩</a></li>
            <?php if (!empty($userData['admin']) && $userData['admin'] == 1): ?>
            <li><a href="<?=BASE_URL?>/shop"><i class="fa fa-shopping-basket" aria-hidden="true"></i> 구매대행 상품</a></li>
            <br>
            <li><a href="/admin"><i class="fa fa-cog" aria-hidden="true"></i> 설정</a></li>
            <?php endif; ?>
          </ul>
          <div class="text-center">
            <a target="_blank" title="페이스북 링크" href="https://facebook.com/giwb.kr"><img src="/public/images/icon_facebook.png"></a>
            <a target="_blank" title="트위터 링크" href="https://twitter.com/giwb_alpine"><img class="ml-2 mr-2" src="/public/images/icon_twitter.png"></a>
            <a target="_blank" title="카카오채널 링크" href="https://pf.kakao.com/_BxaPRxb"><img class="mr-2" src="/public/images/icon_kakaotalk.png"></a>
            <a target="_blank" title="경인웰빙 카페 링크" href="http://cafe.daum.net/giwb"><img src="/public/images/icon_cafe.png"></a>
          </div>
        </div>
      </div>
