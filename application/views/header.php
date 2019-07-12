<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>경인웰빙</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
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
  <link href="/public/lib/animate/animate.min.css" rel="stylesheet">
  <link href="/public/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="/public/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="/public/lib/lightbox/css/lightbox.min.css" rel="stylesheet">

  <!-- Main Stylesheet File -->
  <link href="/public/css/style.css" rel="stylesheet">

  <link href='/public/css/fullcalendar.css' rel='stylesheet' />
  <link href='/public/css/fullcalendar.print.css' rel='stylesheet' media='print' />
  <script src='/public/js/jquery-1.10.2.js' type="text/javascript"></script>
  <script src='/public/js/jquery-ui.custom.min.js' type="text/javascript"></script>
  <script src='/public/js/fullcalendar.js' type="text/javascript"></script>
  <script>
    $(document).ready(function() {
      var date = new Date();
      var d = date.getDate();
      var m = date.getMonth();
      var y = date.getFullYear();

      /* initialize the calendar
      -----------------------------------------------------------------*/
      var calendar =  $('#calendar').fullCalendar({
        header: {
          left: 'prev',
          center: 'title',
          right: 'next'
        },
        editable: false,
        selectable: false,
        defaultView: 'month',
        axisFormat: 'h:mm',
        columnFormat: {
                  month: 'ddd',
                  week: 'ddd d',
                  day: 'dddd M/d',
                  agendaDay: 'dddd d'
              },
              titleFormat: {
                  month: 'yyyy년 MMMM',
                  week: "yyyy년 MMMM",
                  day: 'yyyy년 MMMM'
              },
        allDaySlot: false,
        selectHelper: true,
        select: function(start, end, allDay) {
          var title = prompt('Event Title:');
          if (title) {
            calendar.fullCalendar('renderEvent',
              {
                title: title,
                start: start,
                end: end,
                allDay: allDay
              },
              true // make the event "stick"
            );
          }
          calendar.fullCalendar('unselect');
        },
        droppable: false, // this allows things to be dropped onto the calendar !!!
        drop: function(date, allDay) { // this function is called when something is dropped

          // retrieve the dropped element's stored Event Object
          var originalEventObject = $(this).data('eventObject');

          // we need to copy it, so that multiple events don't have a reference to the same object
          var copiedEventObject = $.extend({}, originalEventObject);

          // assign it the date that was reported
          copiedEventObject.start = date;
          copiedEventObject.allDay = allDay;

          // render the event on the calendar
          // the last `true` argument determines if the event "sticks" (https://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
          $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

          // is the "remove after drop" checkbox checked?
          if ($('#drop-remove').is(':checked')) {
            // if so, remove the element from the "Draggable Events" list
            $(this).remove();
          }
        },
        events: [
          {
            title: 'Click for Google',
            start: new Date(y, m, 28),
            end: new Date(y, m, 29),
            url: 'https://google.com/',
            className: 'success'
          }
        ],
      });
    });
  </script>

  <!-- JavaScript Libraries -->
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
  <!-- Contact Form JavaScript File -->
  <script src="/public/js/contactform.js"></script>

  <!-- Template Main Javascript File -->
  <script src="/public/js/main.js"></script>

</head>

<body>

  <!--==========================
    Header
  ============================-->
  <header id="header">
    <div class="container-fluid">

      <div id="logo" class="pull-left">
        <h1><a href="#intro" class="scrollto">경인 웰빙 산악회</a></h1>
        <!-- <a href="#intro"><img src="/public/images/logo.png" alt="" title="" /></a>-->
      </div>

      <nav id="nav-menu-container">
        <ul class="nav-menu">
          <li class="menu-active"><a href="#intro">TOP</a></li>
          <li><a href="#">산행일정</a></li>
          <li><a href="#">백산백소</a></li>
          <li><a href="#">마이페이지</a></li>
          <!--
          <li class="menu-has-children"><a href="">Drop Down</a>
            <ul>
              <li><a href="#">Drop Down 1</a></li>
              <li><a href="#">Drop Down 3</a></li>
              <li><a href="#">Drop Down 4</a></li>
              <li><a href="#">Drop Down 5</a></li>
            </ul>
          </li>-->
          <li><a href="#">로그인</a></li>
        </ul>
      </nav><!-- #nav-menu-container -->
    </div>
  </header><!-- #header -->
