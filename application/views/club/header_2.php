<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title><?=!empty($view['title']) ? $view['title'] : '한국여행 :: TripKorea.net'?><?=!empty($pageTitle) ? ' - ' . $pageTitle : ''?></title>
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
  <link href="/public/css/fontawesome/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="/public/lib/animate/animate.min.css" rel="stylesheet">
  <link href="/public/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="/public/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="/public/lib/lightbox/css/lightbox.min.css" rel="stylesheet">
  <link href="/public/css/fullcalendar.css" rel="stylesheet">
  <link href="/public/css/fullcalendar.print.css" rel="stylesheet">
  <link href="/public/photoswipe/photoswipe.css" rel="stylesheet">
  <link href="/public/photoswipe/default-skin/default-skin.css" rel="stylesheet">

  <?php if ($view['main_color'] == 'default'): ?>
  <style type="text/css">
    #club a:link, #club a:active, #club a:visited { color: #FF6C00; }
    #club a:hover { color: #0AB031; }
  </style>
  <?php endif; ?>
  <link href="/public/css/style_<?=$view['main_design']?>.css?<?=time()?>" rel="stylesheet">

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
  <script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
  <!--<script src="/public/js/jquery.magnific-popup.min.js" type="text/javascript"></script>-->
  <script src="/public/js/main.js?<?=time()?>" type="text/javascript"></script>
  <?php if (ENVIRONMENT == 'production' && $_SERVER['REMOTE_ADDR'] != '49.166.0.82'): ?>
  <script data-ad-client="ca-pub-2424708381875991" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
  <?php endif; ?>

  <!--[if lt IE 9]>
  <script src="/public/js/html5shiv.js"></script>
  <script src="/public/js/respond.min.js"></script>
  <![endif]-->

</head>
<body id="page-top">

  <header id="header">
    <div id="nav">
      <div id="nav-top" class="bg-<?=$view['main_color']?>">
          <div class="row align-items-center pt-3">
            <div class="col-sm-3"><a href="<?=BASE_URL?>"><h1 class="nav-logo"><?=!empty($view['title']) ? $view['title'] : '한국여행 <small>TripKorea.net</small>'?></h1></a></div>
            <div class="col-sm-9 text-right">
              <ul>

                <?php if (!empty($view['idx']) && $view['idx'] == 1): // 경인웰빙 메뉴 ?>

                  <li class="mr-3">
                    <a href="javascript:;" class="submenu-nav" data-nav-idx="1"><i class="fas fa-chalkboard"></i> 경인웰빙</a>
                    <div class="submenu d-none" data-nav-idx="1">
                      <a href="<?=BASE_URL?>/club/about/?p=top">산악회 소개</a><br>
                      <a href="<?=BASE_URL?>/club/about/?p=guide">등산안내인 소개</a><br>
                      <a href="<?=BASE_URL?>/club/about/?p=howto">이용안내</a><br>
                      <?php if (!empty($userLevel['levelType']) && $userLevel['levelType'] >= 1): ?>
                      <a href="<?=BASE_URL?>/club/past">지난산행</a><br>
                      <?php endif; ?>
                    </div>
                  </li>
                  <li class="mr-3">
                    <a href="javascript:;" class="submenu-nav" data-nav-idx="2"><i class="fas fa-mountain"></i> 백산백소</a>
                    <div class="submenu d-none" data-nav-idx="2">
                      <a href="<?=BASE_URL?>/club/about/?p=mountain">경인웰빙 100대명산</a><br>
                      <a href="<?=BASE_URL?>/club/about/?p=place">경인웰빙 100대명소</a><br>
                      <a href="<?=BASE_URL?>/club/auth">인증현황</a><br>
                    </div>
                  </li>
                  <li class="mr-3"><a href="<?=BASE_URL?>/album"><i class="fas fa-camera"></i> 산행앨범</a></li>
                  <li class="mr-3"><a href="<?=BASE_URL?>/shop"><i class="fas fa-shopping-cart"></i> 구매대행</a></li>
                  <li class="mr-3"><a target="_blank" href="http://giwb.co.kr"><i class="fa fa-desktop" aria-hidden="true"></i> 다음카페</a></li>
                  <?php if (!empty($userData['idx']) && ($userData['level'] == LEVEL_DRIVER || $userData['level'] == LEVEL_DRIVER_ADMIN)): ?>
                  <li class="mr-3"><a href="<?=BASE_URL?>/member/driver"><i class="fas fa-bus"></i> 드라이버</a></li>
                  <?php endif; ?>

                <?php else: // 일반 산악회 메뉴 ?>

                  <li class="mr-3"><a href="<?=BASE_URL?>/club/about/?p=top">산악회 소개</a></li>
                  <li class="mr-3"><a href="<?=BASE_URL?>/club/about/?p=howto">이용안내</a></li>
                  <?php if (!empty($userLevel['levelType']) && $userLevel['levelType'] >= 1): ?>
                  <li class="mr-3"><a href="<?=BASE_URL?>/club/past">지난산행</a></li>
                  <?php endif; ?>
                  <li class="mr-3"><a href="<?=BASE_URL?>/album">산행앨범</a></li>

                <?php endif; ?>

                <?php if (!empty($userData['admin']) && $userData['admin'] == 1): ?>
                <li class="mr-2"><a href="<?=BASE_URL?>/admin"><i class="fas fa-cog"></i> 설정</a></li>
                <?php endif; ?>

                <?php if (empty($userData['idx'])): ?>
                <li><a href="javascript:;" class="login-popup"><i class="fas fa-user-circle"></i> 로그인</a></li>
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
      </div>
      <div id="nav-sp">
        <ul>
          <li><a href="<?=BASE_URL?>"><i class="fa fa-home btn-header"></i></a></li>
          <li><a href="<?=BASE_URL?>"><h1><?=!empty($pageTitle) ? $pageTitle : $view['title'] ?></h1></a></li>
          <li>
            <?php if (!empty($userData['idx']) && ($userData['level'] == LEVEL_DRIVER || $userData['level'] == LEVEL_DRIVER_ADMIN)): ?>
            <a href="<?=BASE_URL?>/member/driver"><h3 class="m-0 p-0"><i class="fa fa-bus text-white"></i></h3></a>
            <?php else: ?>
            <?php if (strstr($_SERVER['REQUEST_URI'], 'member')): ?>
            <a href="javascript:;" class="btn-mypage"><i class="fa fa-cog btn-header"></i></a>
            <?php elseif (strstr($_SERVER['REQUEST_URI'], 'shop')): ?>
            <a href="javascript:;" title="장바구니"><i class="fa fa-shopping-cart btn-header btn-cart"></i></a>
            <?php elseif (strstr($_SERVER['REQUEST_URI'], 'album')): ?>
            <a href="<?=BASE_URL?>/album/entry" title="사진 업로드"><i class="fa fa-cloud-upload btn-header"></i></a>
            <?php elseif (strstr($_SERVER['REQUEST_URI'], 'login')): ?>
            <?php else: ?>
            <a target="_blank" href="http://giwb.co.kr"><img src="//m1.daumcdn.net/cafeimg/mobile/m640/tit_cafe_s_161214.png" width="50"></a>
            <?php endif; ?>
            <?php endif; ?>
          </li>
        </ul>
        <div class="nav-sp-mypage">
          <a href="<?=BASE_URL?>/member">・마이페이지</a><br>
          <a href="<?=BASE_URL?>/member/modify">・개인정보수정</a><br>
          <?php if (!empty($userData['idx']) && ($userData['level'] == LEVEL_DRIVER || $userData['level'] == LEVEL_DRIVER_ADMIN) || (!empty($userData['admin']) && $userData['admin'] == 1)): ?>
          <a href="<?=BASE_URL?>/member/driver">・드라이버 페이지</a><br>
          <?php endif; ?>
          <?php if (!empty($userData['admin']) && $userData['admin'] == 1): ?>
          <a href="<?=BASE_URL?>/admin">・설정</a><br>
          <?php endif; ?>
          <a href="javascript:;" class="logout" title="로그아웃">・로그아웃</a>
        </div>
      </div>
    </div>
  </header>
  <!-- /HEADER -->

  <section id="club">
    <div class="container">
