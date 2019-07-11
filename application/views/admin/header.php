<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>경인웰빙 관리페이지</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex">
  <link rel="stylesheet" href="/public/vendors/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/public/vendors/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="/public/vendors/themify-icons/css/themify-icons.css">
  <link rel="stylesheet" href="/public/vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="/public/vendors/selectFX/css/cs-skin-elastic.css">
  <link rel="stylesheet" href="/public/vendors/jqvmap/dist/jqvmap.min.css">
  <link rel="stylesheet" href="/public/css/fullcalendar.css">
  <link rel="stylesheet" href="/public/css/fullcalendar.print.css">
  <link rel="stylesheet" href="/public/css/admin.css">
  <script type="text/javascript" src="/public/js/jquery-1.10.2.js"></script>
  <script type="text/javascript" src="/public/js/jquery-ui.custom.min.js"></script>
  <script type="text/javascript" src="/public/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="/public/js/fullcalendar.js"></script>
  <script type="text/javascript" src="/public/js/admin.js"></script>
</head>

<body>

    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">

            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <h1>경인웰빙 관리자</h1>
            </div>

            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active dashboard">
                        <a href="<?=base_url()?>admin"> <i class="menu-icon fa fa-dashboard"></i>대시보드 </a>
                    </li>

                    <h3 class="menu-title">산행관리</h3>
                    <li>
                        <a href="<?=base_url()?>admin/list_progress"> <i class="menu-icon fa fa-calendar"></i>진행중 산행 목록</a>
                    </li>
                    <li>
                        <a href="<?=base_url()?>admin/list_closed"> <i class="menu-icon fa fa-calendar-check-o"></i>다녀온 산행 목록</a>
                    </li>
                    <li>
                        <a href="<?=base_url()?>admin/list_canceled"> <i class="menu-icon fa fa-calendar-times-o"></i>취소된 산행 목록</a>
                    </li>
                    <li>
                        <a href="#"> <i class="menu-icon fa fa-calendar-plus-o"></i>신규 산행 등록</a>
                    </li>

                    <h3 class="menu-title">회원관리</h3>
                    <li>
                        <a href="<?=base_url()?>admin/list_members"> <i class="menu-icon fa fa-users"></i>전체 회원 목록</a>
                    </li>
                    <li>
                        <a href="#"> <i class="menu-icon fa fa-user-secret"></i>관리자 등록</a>
                    </li>

                    <h3 class="menu-title">출석현황</h3>
                    <li>
                        <a href="<?=base_url()?>admin/list_attendance"> <i class="menu-icon fa fa-check-square-o"></i>출석체크 보기</a>
                    </li>
                    <li>
                        <a href="#"> <i class="menu-icon fa fa-child"></i>산행지로 보기</a>
                    </li>
                    <li>
                        <a href="#"> <i class="menu-icon fa fa-download"></i>최신 데이터 받기</a>
                    </li>

                    <h3 class="menu-title">활동관리</h3>
                    <li>
                        <a href="#"> <i class="menu-icon fa fa-list"></i>회원 활동 목록</a>
                    </li>
                    <li>
                        <a href="#"> <i class="menu-icon fa fa-list-alt"></i>관리자 활동 목록</a>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside><!-- /#left-panel -->
