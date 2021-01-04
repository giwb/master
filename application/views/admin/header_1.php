<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html5>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title><?=$viewClub['title']?><?=!empty($pageTitle) ? ' - ' . $pageTitle : ''?></title>
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
  <link href="/public/css/jquery-ui.css" rel="stylesheet" type="text/css">
  <link href="/public/css/fontawesome/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="/public/lib/animate/animate.min.css" rel="stylesheet">
  <link href="/public/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="/public/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="/public/lib/lightbox/css/lightbox.min.css" rel="stylesheet">
  <link href="/public/css/fullcalendar.css" rel="stylesheet">
  <link href="/public/css/fullcalendar.print.css" rel="stylesheet">
  <link href="/public/photoswipe/photoswipe.css" rel="stylesheet">
  <link href="/public/photoswipe/default-skin/default-skin.css" rel="stylesheet">

  <?php if ($viewClub['main_color'] == 'default'): ?>
  <style type="text/css">
    #club a:link, #club a:active, #club a:visited { color: #FF6C00; }
    #club a:hover { color: #0AB031; }
  </style>
  <?php endif; ?>
  <link href="/public/css/admin_<?=$viewClub['main_design']?>.css?<?=time()?>" rel="stylesheet">

  <script src="/public/js/jquery-2.1.4.min.js" type="text/javascript"></script>
  <script src="/public/js/jquery-ui.custom.min.js" type="text/javascript"></script>
  <script src="/public/js/jquery-ui.min.js" type="text/javascript"></script>
  <script src="/public/js/jquery.ui.touch-punch.min.js" type="text/javascript"></script>
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
  <!--<script src="/public/js/main.js?<?=time()?>" type="text/javascript"></script>-->
  <script src="/public/js/admin.js?<?=time()?>" type="text/javascript"></script>
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
      <div id="nav-top" class="bg-<?=$viewClub['main_color']?>">
        <div class="container">
          <div class="row align-items-center pt-3">
            <div class="col-sm-8"><a href="<?=goHome($viewClub['domain'])?>"><h1 class="nav-logo"><?=!empty($viewClub['title']) ? $viewClub['title'] : ''?></h1></a></div>
            <div class="col-sm-4 text-right">
              <?php if (empty($userData['idx'])): ?>
              <a href="javascript:;" class="login-popup">로그인</a>
              <?php else: ?>
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
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <div id="nav-sp">
        <ul>
          <li><a href="<?=BASE_URL?>/admin"><i class="fas fa-chalkboard btn-header"></i></a></li>
          <li><a href="<?=BASE_URL?>/admin"><h1><?=!empty($pageTitle) ? $pageTitle : $viewClub['title'] ?></h1></a></li>
          <li class="btn-mypage">
            <?php if (!empty($userData['idx']) && file_exists(PHOTO_PATH . $userData['idx'])): ?>
            <img class="img-profile" src="<?=PHOTO_URL . $userData['idx']?>">
            <?php elseif (!empty($userData['icon_thumbnail'])): ?>
            <img class="img-profile" src="<?=$userData['icon_thumbnail']?>">
            <?php else: ?>
            <img class="img-profile" src="/public/images/user.png">
            <?php endif; ?>
          </li>
        </ul>
        <div class="nav-sp-mypage">
          <strong><?=$userData['nickname']?></strong> (<?=$userLevel['levelName']?>)<hr>
          <a href="<?=goHome($viewClub['domain'])?>">・HOME</a><br>
          <a href="<?=BASE_URL?>/member">・마이페이지</a><br>
          <a href="<?=BASE_URL?>/member/modify">・개인정보수정</a><br>
          <?php if ($userData['level'] == LEVEL_DRIVER || $userData['level'] == LEVEL_DRIVER_ADMIN || (!empty($userData['admin']) && $userData['admin'] == 1)): ?>
          <a href="<?=BASE_URL?>/member/driver">・드라이버 페이지</a><br>
          <?php endif; ?>
          <?php if (!empty($userData['admin']) && $userData['admin'] == 1): ?>
          <a href="<?=BASE_URL?>/admin">・설정</a><br>
          <?php endif; ?>
          <a href="javascript:;" class="logout" title="로그아웃">・로그아웃</a><br>
          <div class="container">
            <form method="post" action="<?=BASE_URL?>/admin/log_reserve" class="row align-items-center">
              <div class="col-9 p-0 p-2"><input type="text" name="k" value="<?=!empty($keyword) ? $keyword : ''?>" class="form-control form-control-sm"></div>
              <div class="col-3 p-0"><button class="btn btn-sm btn-<?=$viewClub['main_color']?>">검색</button></div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </header>
  <!-- /HEADER -->

  <section id="club">
    <div class="container">
      <div class="club-left">
        <div class="club-left-layer">
          <div class="club-header">
            <?php if (!empty($viewClub['main_photo'])): ?>
            <!-- 대표 사진 -->
            <a href="javascript:;" class="photo-zoom" data-filename="<?=$viewClub['main_photo']?>" data-width="<?=$viewClub['main_photo_width']?>" data-height="<?=$viewClub['main_photo_height']?>"><img src="<?=$viewClub['main_photo']?>"></a>
            <?php endif; ?>
            <h3><?=!empty($viewClub['title']) ? $viewClub['title'] : ''?></h3>
          </div>
          <?=!empty($viewClub['homepage']) ? '<a href="' . $viewClub['homepage'] . '" class="url">' . $viewClub['homepage'] . '</a>' : ''?>
          <ul class="navi">
            <li class="mb-1"><a href="<?=BASE_URL?>/admin"><i class="fas fa-chalkboard" aria-hidden="true"></i> 대시보드</a></li>
            <li class="mb-1"><a href="<?=BASE_URL?>/admin/main_list_progress"><i class="fas fa-mountain" aria-hidden="true"></i> 산행관리</a></li>
            <?php if ($viewClub['idx'] == 1): ?>
            <li class="mb-1"><a href="<?=BASE_URL?>/ShopAdmin/order"><i class="fas fa-shopping-cart" aria-hidden="true"></i> 구매대행관리</a></li>
            <?php endif; ?>
            <li class="mb-1"><a href="<?=BASE_URL?>/admin/member_list"><i class="fas fa-users" aria-hidden="true"></i> 회원관리</a></li>
            <li class="mb-1"><a href="<?=BASE_URL?>/admin/log_user"><i class="fas fa-exchange-alt" aria-hidden="true"></i> 활동관리</a></li>
            <?php if ($viewClub['idx'] == 1): ?>
            <li class="mb-1"><a href="<?=BASE_URL?>/admin/attendance_auth"><i class="fa fa-check-square" aria-hidden="true"></i> 백산백소 인증</a></li>
            <?php endif; ?>
            <li><a href="<?=BASE_URL?>/admin/setup_information"><i class="fas fa-cog" aria-hidden="true"></i> 설정</a></li>
          </ul>
          <div class="club-search">
            <form method="post" action="<?=BASE_URL?>/admin/log_reserve">
              <div class="club-search-item1"><input type="text" name="k" value="<?=!empty($keyword) ? $keyword : ''?>" class="form-control form-control-sm"></div>
              <div class="club-search-item2"><button class="btn btn-sm btn-<?=$viewClub['main_color']?>">검색</button></div>
            </form>
          </div>
        </div>
      </div>
      <div class="club-main">
