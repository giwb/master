<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="robots" content="noindex,nofollow">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>한국여행 :: TripKorea.net<?=!empty($pageTitle) ? ' - ' . $pageTitle : ''?></title>

  <link rel="icon" type="image/png" href="/public/images/tripkorea/favicon.png">
  <link rel="shortcut icon" type="image/png" href="/public/images/tripkorea/favicon.png">

  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR&display=swap" rel="stylesheet">
  <link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet">
  <link href="/public/css/bootstrap.min.css" rel="stylesheet">
  <link href="/public/css/tripkorea.css" rel="stylesheet">

  <script type="text/javascript" src="/public/js/jquery-3.3.1.min.js"></script>
  <script type="text/javascript" src="/public/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="/public/js/tripkorea.js"></script>
</head>
<body class="fixed-sn homepage-v2">

<header>
  <nav class="navbar navbar-expand-lg navbar-dark stylish-color-dark">
    <a class="navbar-brand" href="<?=base_url()?>"><h2><img width="40" src="/public/images/tripkorea/logo_tripkorea.png"> 한국여행</h2></a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-map-marker-alt"></i> 여행정보</a>
          <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="<?=base_url()?>place">전체보기</a>
            <a class="dropdown-item" href="<?=base_url()?>place/forest">산림청 100대 명산</a>
            <a class="dropdown-item" href="<?=base_url()?>place/bac">블랙야크 명산 100</a>
          </div>
        </li>
        <li class="nav-item">
          <a href="<?=base_url()?>list" class="nav-link"><i class="far fa-calendar-alt"></i> 여행일정</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false"><i class="fas fa-mountain"></i> 산악회</a>
          <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="<?=base_url()?>area">전체보기</a>
            <a class="dropdown-item" href="<?=base_url()?>area/?=area_sido&k=11000">서울특별시</a>
            <a class="dropdown-item" href="<?=base_url()?>area/?=area_sido&k=12000">부산광역시</a>
            <a class="dropdown-item" href="<?=base_url()?>area/?=area_sido&k=13000">대구광역시</a>
            <a class="dropdown-item" href="<?=base_url()?>area/?=area_sido&k=14000">인천광역시</a>
            <a class="dropdown-item" href="<?=base_url()?>area/?=area_sido&k=15000">광주광역시</a>
            <a class="dropdown-item" href="<?=base_url()?>area/?=area_sido&k=16000">대전광역시</a>
            <a class="dropdown-item" href="<?=base_url()?>area/?=area_sido&k=17000">울산광역시</a>
            <a class="dropdown-item" href="<?=base_url()?>area/?=area_sido&k=19000">경기도</a>
            <a class="dropdown-item" href="<?=base_url()?>area/?=area_sido&k=20000">강원도</a>
            <a class="dropdown-item" href="<?=base_url()?>area/?=area_sido&k=22000">충청도</a>
            <a class="dropdown-item" href="<?=base_url()?>area/?=area_sido&k=24000">전라도</a>
            <a class="dropdown-item" href="<?=base_url()?>area/?=area_sido&k=26000">경상도</a>
            <a class="dropdown-item" href="<?=base_url()?>area/?=area_sido&k=27000">제주도</a>
          </div>
        </li>
        <li class="nav-item">
          <a href="http://cctv.tripkorea.net/video" class="nav-link" aria-haspopup="true" aria-expanded="false"><i class="fas fa-video"></i> 현지영상</a>
        </li>
        <?php if (!empty($userData['idx'])): ?>
        <li class="nav-item">
          <a href="<?=base_url()?>mypage" class="nav-link" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user-circle"></i> 마이페이지</a>
        </li>
        <li class="nav-item">
          <a href="javascript:;" class="nav-link logout" aria-haspopup="true" aria-expanded="false"><i class="fas fa-sign-out-alt"></i> 로그아웃</a>
        </li>
        <?php else: ?>
        <li class="nav-item">
          <a href="<?=base_url()?>login/entry" class="nav-link" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user-plus"></i> 회원가입</a>
        </li>
        <li class="nav-item">
          <a href="javascript:;" class="nav-link login-popup" aria-haspopup="true" aria-expanded="false"><i class="fas fa-sign-in-alt"></i> 로그인</a>
        </li>
        <?php endif; ?>
        <li class="nav-item dropdown">
          <a class="nav-link" id="navbarDropdownMenuLink3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-search"></i> 검색</a>
          <div class="dropdown-menu dropdown-search" aria-labelledby="navbarDropdownMenuLink">
            <form method="get" action="<?=base_url()?>search">
            <div class="row align-items-center">
                <div class="col-9 pl-4 pr-0"><input type="text" name="keyword" class="form-control"></div>
                <div class="col-3 pl-0 text-center"><button type="submit" class="btn btn-primary pt-2 pb-2 pl-3 pr-3">검색</button></div>
            </div>
            </form>
          </div>
        </li>
      </ul>
    </div>
  </nav>
</header>
