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
  <meta property="og:url" content="<?=base_url()?>" />
  <meta property="og:image" content="<?=base_url()?>public/images/logo.png" />
  <meta property="og:description" content="인천과 부천 지역을 기반으로 토요산행과 일요산행을 매주 운행하는 안내산악회입니다. 차내 음주가무 없으며 초보자도 함께할 수 있도록 여유롭게 산행을 진행합니다.">
  <meta name="description" content="인천과 부천 지역을 기반으로 토요산행과 일요산행을 매주 운행하는 안내산악회입니다. 차내 음주가무 없으며 초보자도 함께할 수 있도록 여유롭게 산행을 진행합니다.">

  <link rel="icon" type="image/png" href="<?=base_url()?>public/images/favicon.png">
  <link rel="shortcut icon" type="image/png" href="<?=base_url()?>public/images/favicon.png">
  <link rel="apple-touch-icon" sizes="76x76" href="<?=base_url()?>public/images/apple-touch-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="120x120" href="<?=base_url()?>public/images/apple-touch-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="152x152" href="<?=base_url()?>public/images/apple-touch-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="<?=base_url()?>public/images/apple-touch-icon-180x180.png">
  <link rel="apple-touch-icon" sizes="256x256" href="<?=base_url()?>public/images/apple-touch-icon-256x256.png">

  <link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR&display=swap" rel="stylesheet">
  <link href="<?=base_url()?>public/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?=base_url()?>public/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?=base_url()?>public/lib/animate/animate.min.css" rel="stylesheet">
  <link href="<?=base_url()?>public/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="<?=base_url()?>public/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="<?=base_url()?>public/lib/lightbox/css/lightbox.min.css" rel="stylesheet">
  <link href="<?=base_url()?>public/css/fullcalendar.css" rel="stylesheet">
  <link href="<?=base_url()?>public/css/fullcalendar.print.css" rel="stylesheet">
  <link href="<?=base_url()?>public/css/style.css?<?=time()?>" rel="stylesheet">

  <script src="<?=base_url()?>public/js/jquery-2.1.4.min.js" type="text/javascript"></script>
  <script src="<?=base_url()?>public/js/jquery-ui.custom.min.js" type="text/javascript"></script>
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
  <script src="<?=base_url()?>public/ckeditor/ckeditor.js" type="text/javascript" charset="utf-8"></script>
  <script src="<?=base_url()?>public/js/fullcalendar.js" type="text/javascript"></script>
  <script src="<?=base_url()?>public/js/clipboard.min.js" type="text/javascript"></script>
  <script src="<?=base_url()?>public/js/main.js?<?=time()?>" type="text/javascript"></script>
  <script data-ad-client="ca-pub-2424708381875991" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

  <!--[if lt IE 9]>
  <script src="<?=base_url()?>public/js/html5shiv.js"></script>
  <script src="<?=base_url()?>public/js/respond.min.js"></script>
  <![endif]-->

</head>
<body id="page-top">

  <header id="header">
    <div id="nav">
      <div id="nav-top">
        <div class="container">
          <h1 class="nav-logo">
            <a href="<?=base_url()?><?=$view['idx']?>" class="logo">경인웰빙</a>
          </h1>
          <ul class="nav-menu">
            <li<?=$uri == 'top' ? ' class="active"' : ''?>><a href="<?=base_url()?><?=$view['idx']?>"><i class="fa fa-home" aria-hidden="true"></i></a></li>
            <?php if (empty($userData['idx'])): ?>
            <li><a href="javascript:;" class="login-popup">로그인</a></li>
            <?php else: ?>
            <li>
            <?php if (file_exists(PHOTO_PATH . $userData['idx'])): ?>
            <img class="img-profile" src="<?=base_url()?>public/photos/<?=$userData['idx']?>">
            <?php else: ?>
            <img class="img-profile" src="<?=base_url()?>public/images/user.png">
            <?php endif; ?>
              <div class="profile-box">
                <strong><?=$userData['nickname']?></strong> (<?=$userLevel['levelName']?>)<hr>
                <a href="<?=base_url()?>member/<?=$view['idx']?>"><?=$userData['level'] == LEVEL_DRIVER ? '드라이버 페이지' : '마이페이지'?></a><br>
                <a href="<?=base_url()?>member/modify/<?=$view['idx']?>">개인정보수정</a><br>
                <a href="javascript:;" class="logout">로그아웃</a>
              </div>
            </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
      <div class="nav-btns">
        <button type="button" class="aside-btn"><i class="fa fa-bars"></i></button>
        <a href="<?=base_url()?><?=$view['idx']?>"><button type="button" class="search-btn"><i class="fa fa-home"></i></button></a>
      </div>
      <div id="nav-aside">
        <ul class="nav-aside-menu">
          <?php if (!empty($userData['idx'])): ?>
          <li>
            <?php if (file_exists(PHOTO_PATH . $userData['idx'])): ?>
            <img class="img-profile" src="<?=base_url()?>public/photos/<?=$userData['idx']?>">
            <?php else: ?>
            <img class="img-profile" src="<?=base_url()?>public/images/user.png">
            <?php endif; ?>
            <span class="header-nickname"><?=$userData['nickname']?> (<?=$userLevel['levelName']?>)</span></li>
          <?php else: ?>
          <li><p>&nbsp;</p></li>
          <?php endif; ?>
          <li class="row">
            <div class="pl-3"><a target="_blank" title="카카오채널 링크" href="https://pf.kakao.com/_BxaPRxb"><img src="<?=base_url()?>public/images/icon_kakaotalk.png"></a></div>
            <div class="pl-3"><a target="_blank" title="페이스북 링크" href="https://facebook.com/giwb.kr"><img src="<?=base_url()?>public/images/icon_facebook.png"></a></div>            
          </li>
          <li><a href="<?=base_url()?>club/about/<?=$view['idx']?>"> 산악회 소개</a></li>
          <li><a href="<?=base_url()?>club/guide/<?=$view['idx']?>"> 등산 안내인 소개</a></li>
          <?php if ($userLevel['levelType'] >= 2): ?>
          <li><a href="<?=base_url()?>club/past/<?=$view['idx']?>"> 지난 산행보기</a></li>
          <?php endif; ?>
          <li><a href="<?=base_url()?>club/howto/<?=$view['idx']?>"> 이용안내</a></li>
          <?php if ($view['idx'] == 1): ?>
          <br><li><a href="<?=base_url()?>club/auth_about/<?=$view['idx']?>"> 백산백소 소개</a></li>
          <li><a href="<?=base_url()?>club/auth/<?=$view['idx']?>"> 백산백소 인증현황</a></li>
          <?php endif; ?>
          <li><a href="<?=base_url()?>club/album/<?=$view['idx']?>"> 사진첩</a></li>
          <?php if (!empty($userData['admin']) && $userData['admin'] == 1): ?>
          <li><a href="<?=base_url()?>club/shop/<?=$view['idx']?>"> 용품판매</a></li>
          <br><li><a href="<?=base_url()?>admin"> 설정</a></li>
          <?php endif; ?>
          <?php if (empty($userData['idx'])): ?>
          <br><li><a href="javascript:;" class="login-popup">로그인</a></li>
          <?php else: ?>
          <br><li><a href="<?=base_url()?>member/<?=$view['idx']?>"><?=$userData['level'] == LEVEL_DRIVER ? '드라이버 페이지' : '마이페이지'?></a></li>
          <li><a href="<?=base_url()?>member/modify/<?=$view['idx']?>">개인정보수정</a></li>
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
            <?php if (!empty($view['photo'][0])): ?>
            <!-- 대표 사진 -->
            <a href="<?=base_url()?>"><img src="<?=base_url()?><?=PHOTO_URL?><?=$view['photo'][0]?>"></a>
            <?php endif; ?>
            <h3><?=$view['title']?></h3>
          </div>
          <?=$view['homepage'] != '' ? '<a href="' . $view['homepage'] . '" class="url">' . $view['homepage'] . '</a>' : ''?>
          <ul class="navi">
            <li><a href="<?=base_url()?>club/about/<?=$view['idx']?>"><i class="fa fa-picture-o" aria-hidden="true"></i> 산악회 소개</a></li>
            <li><a href="<?=base_url()?>club/guide/<?=$view['idx']?>"><i class="fa fa-user-circle" aria-hidden="true"></i> 등산 안내인 소개</a></li>
            <?php if ($userLevel['levelType'] >= 2): ?>
            <li><a href="<?=base_url()?>club/past/<?=$view['idx']?>"><i class="fa fa-calendar" aria-hidden="true"></i> 지난 산행보기</a></li>
            <?php endif; ?>
            <li><a href="<?=base_url()?>club/howto/<?=$view['idx']?>"><i class="fa fa-map-o" aria-hidden="true"></i> 이용안내</a></li>
            <?php if ($view['idx'] == 1): ?><br>
            <li><a href="<?=base_url()?>club/auth_about/<?=$view['idx']?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> 백산백소 소개</a></li>
            <li><a href="<?=base_url()?>club/auth/<?=$view['idx']?>"><i class="fa fa-check-square" aria-hidden="true"></i> 백산백소 인증현황</a></li>
            <?php endif; ?><br>
            <li><a href="<?=base_url()?>club/album/<?=$view['idx']?>"><i class="fa fa-photo" aria-hidden="true"></i> 사진첩</a></li>
            <?php if (!empty($userData['admin']) && $userData['admin'] == 1): ?>
            <li><a href="<?=base_url()?>club/shop/<?=$view['idx']?>"><i class="fa fa-shopping-basket" aria-hidden="true"></i> 용품판매</a></li>
            <br>
            <li><a href="<?=base_url()?>admin"><i class="fa fa-cog" aria-hidden="true"></i> 설정</a></li>
            <?php endif; ?>
          </ul>
          <div class="desc">
          ・개설일 : <?=$view['establish']?>년<br>
          ・관리자 : <?=$view['nickname']?><br>
          ・회원수 : <?=number_format($view['cntMember']['cnt'])?>명 / 오늘 <?=number_format($view['cntMemberToday']['cnt'])?>명<br>
          ・방문수 : <?=number_format($view['cntVisitor']['cnt'])?>회 / 오늘 <?=number_format($view['cntVisitorToday']['cnt'])?>명<br>
          </div>
          <div class="text-center border-top mt-3 pt-3">
            <a target="_blank" title="카카오채널 링크" href="https://pf.kakao.com/_BxaPRxb"><img src="<?=base_url()?>public/images/icon_kakaotalk.png"></a>
            <a target="_blank" title="페이스북 링크" href="https://facebook.com/giwb.kr"><img src="<?=base_url()?>public/images/icon_facebook.png"></a>
          </div>
        </div>
      </div>
