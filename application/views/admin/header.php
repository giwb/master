<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>경인웰빙 관리페이지</title>
  <meta name="viewport" content="height=device-height, width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="robots" content="noindex">
  <meta property="og:title" content="경인웰빙" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://giwb.kr/" />
  <meta property="og:image" content="<?=base_url()?>public/images/logo.png" />
  <meta property="og:description" content="매주 토, 일. 차내 음주가무 없으며, 초보자도 함께할 수 있는 여유있는 산행.">
  <meta name="description" content="매주 토, 일. 차내 음주가무 없으며, 초보자도 함께할 수 있는 여유있는 산행.">
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>public/lib/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>public/css/jquery-ui.css">
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>public/css/fontawesome/css/all.min.css">
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>public/css/fullcalendar.css">
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>public/css/fullcalendar.print.css">
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>public/css/admin.css?<?=time()?>">
  <script type="text/javascript" src="<?=base_url()?>public/js/jquery-1.11.1.min.js"></script>
  <script type="text/javascript" src="<?=base_url()?>public/js/jquery-ui.custom.min.js"></script>
  <script type="text/javascript" src="<?=base_url()?>public/js/jquery-ui.min.js"></script>
  <script type="text/javascript" src="<?=base_url()?>public/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="<?=base_url()?>public/js/fullcalendar.js"></script>
  <script type="text/javascript" src="<?=base_url()?>public/ckeditor/ckeditor.js" charset="utf-8"></script>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center">
        <div class="sidebar-brand-text mx-3">경인웰빙 관리자</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="<?=base_url()?>admin">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>대시보드</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <!--
      <div class="sidebar-heading">
        Interface
      </div>
      -->

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseItem" aria-expanded="true" aria-controls="collapseItem">
          <i class="fas fa-fw fa-mountain"></i>
          <span>산행관리</span>
        </a>
        <div id="collapseItem" class="collapse<?=strstr($uri, 'main') ? " show" : ""?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="<?=base_url()?>admin/main_list_progress"> <i class="menu-icon fas fa-calendar"></i> 진행중 산행 목록</a>
            <a class="collapse-item" href="<?=base_url()?>admin/main_list_closed"> <i class="menu-icon fas fa-calendar-check"></i> 다녀온 산행 목록</a>
            <a class="collapse-item" href="<?=base_url()?>admin/main_list_canceled"> <i class="menu-icon fas fa-calendar-times"></i> 취소된 산행 목록</a>
            <a class="collapse-item" href="<?=base_url()?>admin/main_list_planned"> <i class="menu-icon fas fa-calendar-alt"></i> 계획중 산행 목록</a>
            <a class="collapse-item" href="<?=base_url()?>admin/main_entry"> <i class="menu-icon fas fa-calendar-plus"></i> 신규 산행 등록</a>
            <a class="collapse-item" href="<?=base_url()?>admin/setup_schedule"> <i class="menu-icon fas fa-calendar-alt"></i> 산행계획</a>
          </div>
        </div>
      </li>

      <!-- Nav Item - Utilities Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=base_url()?>admin/member_list">
          <i class="fas fa-fw fa-users"></i>
          <span>회원관리</span>
        </a><!--
        <div id="collapseMember" class="collapse<?=strstr($uri, 'member') ? " show" : ""?>" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="<?=base_url()?>admin/member_list"> <i class="menu-icon fas fa-user"></i> 전체 회원 목록</a>
          </div>
        </div>-->
      </li>

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAttendance" aria-expanded="true" aria-controls="collapseAttendance">
          <i class="fas fa-fw fa-user-check"></i>
          <span>출석현황</span>
        </a>
        <div id="collapseAttendance" class="collapse<?=strstr($uri, 'attendance') ? " show" : ""?>" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="<?=base_url()?>admin/attendance_list"> <i class="menu-icon fas fa-check-square"></i> 출석체크 보기</a>
            <a class="collapse-item" href="<?=base_url()?>admin/attendance_mountain"> <i class="menu-icon fas fa-child"></i> 산행지로 보기</a>
          </div>
        </div>
      </li>

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLog" aria-expanded="true" aria-controls="collapseLog">
          <i class="fas fa-fw fa-list"></i>
          <span>활동관리</span>
        </a>
        <div id="collapseLog" class="collapse<?=strstr($uri, 'log') ? " show" : ""?>" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="<?=base_url()?>admin/log_user"> <i class="menu-icon fas fa-th-list"></i> 회원 예약 기록</a>
            <a class="collapse-item" href="<?=base_url()?>admin/log_admin"> <i class="menu-icon fas fa-list-alt"></i> 관리자 예약 기록</a>
            <a class="collapse-item" href="<?=base_url()?>admin/log_reply"> <i class="menu-icon fas fa-reply"></i> 댓글 기록</a>
            <a class="collapse-item" href="<?=base_url()?>admin/log_reaction"> <i class="menu-icon fas fa-heart"></i> 좋아요/공유 기록</a>
            <a class="collapse-item" href="<?=base_url()?>admin/log_visitor"> <i class="menu-icon fas fa-user-check"></i> 방문자 기록</a>
          </div>
        </div>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSetup" aria-expanded="true" aria-controls="collapseSetup">
          <i class="fas fa-fw fa-cog"></i>
          <span>설정</span>
        </a>
        <div id="collapseSetup" class="collapse<?=strstr($uri, 'setup') ? " show" : ""?>" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="<?=base_url()?>admin/setup_information"> <i class="menu-icon fas fa-tools"></i> 클럽 정보</a>
            <a class="collapse-item" href="<?=base_url()?>admin/setup_pages"> <i class="menu-icon fas fa-scroll"></i> 소개 페이지</a>
            <a class="collapse-item" href="<?=base_url()?>admin/setup_sms"> <i class="menu-icon fas fa-sms"></i> 문자양식보기</a>
            <a class="collapse-item" href="<?=base_url()?>admin/setup_bustype"> <i class="menu-icon fas fa-bus"></i> 차종등록</a>
            <a class="collapse-item" href="<?=base_url()?>admin/setup_calendar"> <i class="menu-icon fas fa-calendar"></i> 달력관리</a>
            <!--<a class="collapse-item" href="<?=base_url()?>admin/setup_front"> <i class="menu-icon fas fa-square"></i> 대문관리</a>-->
          </div>
        </div>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search -->
          <!--
          <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>
          -->

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?=$userData['nickname']?></span>
                <img class="img-profile rounded-circle" src="<?=base_url()?>public/photos/<?=$userData['idx']?>">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  관리자 정보
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  정보 수정
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  접속 기록
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item logout" href="javascript:;">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  로그아웃
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
