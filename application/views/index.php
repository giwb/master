<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <!--==========================
    Intro Section
  ============================-->
  <section id="intro">
    <div class="intro-container">
      <div id="introCarousel" class="carousel slide carousel-fade" data-ride="carousel">
        <ol class="carousel-indicators"></ol>
        <div class="carousel-inner" role="listbox">
          <div class="carousel-item active">
            <div class="carousel-background"><img src="/public/images/main_1.jpg" alt=""></div>
            <div class="carousel-container">
              <div class="carousel-content">
                <h2>경인 웰빙 산악회</h2>
                <!--<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                <a href="#featured-services" class="btn-get-started scrollto">Get Started</a>-->
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="carousel-background"><img src="/public/images/main_2.jpg" alt=""></div>
            <div class="carousel-container">
              <div class="carousel-content">
                <h2>경인 웰빙 산악회</h2>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="carousel-background"><img src="/public/images/main_3.jpg" alt=""></div>
            <div class="carousel-container">
              <div class="carousel-content">
                <h2>경인 웰빙 산악회</h2>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="carousel-background"><img src="/public/images/main_4.jpg" alt=""></div>
            <div class="carousel-container">
              <div class="carousel-content">
                <h2>경인 웰빙 산악회</h2>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="carousel-background"><img src="/public/images/main_5.jpg" alt=""></div>
            <div class="carousel-container">
              <div class="carousel-content">
                <h2>경인 웰빙 산악회</h2>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="carousel-background"><img src="/public/images/main_6.jpg" alt=""></div>
            <div class="carousel-container">
              <div class="carousel-content">
                <h2>경인 웰빙 산악회</h2>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="carousel-background"><img src="/public/images/main_7.jpg" alt=""></div>
            <div class="carousel-container">
              <div class="carousel-content">
                <h2>경인 웰빙 산악회</h2>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="carousel-background"><img src="/public/images/main_8.jpg" alt=""></div>
            <div class="carousel-container">
              <div class="carousel-content">
                <h2>경인 웰빙 산악회</h2>
              </div>
            </div>
          </div>
        </div>
        <a class="carousel-control-prev" href="#introCarousel" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon ion-chevron-left" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#introCarousel" role="button" data-slide="next">
          <span class="carousel-control-next-icon ion-chevron-right" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </div>
  </section><!-- #intro -->

  <main id="main">

    <link href="/public/css/fullcalendar.css" rel="stylesheet" />
    <link href="/public/css/fullcalendar.print.css" rel="stylesheet" media="print" />
    <script src="/public/js/fullcalendar.js" type="text/javascript"></script>
    <script>
      $(document).ready(function() {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        /* initialize the calendar
        -----------------------------------------------------------------*/
        var calendar =  $("#calendar").fullCalendar({
          header: {
            left: "prev",
            center: "title",
            right: "next"
          },
          editable: false,
          selectable: false,
          defaultView: "month",
          axisFormat: "h:mm",
          columnFormat: {
                    month: "ddd",
                    week: "ddd d",
                    day: "dddd M/d",
                    agendaDay: "dddd d"
                },
                titleFormat: {
                    month: "yyyy년 MMMM",
                    week: "yyyy년 MMMM",
                    day: "yyyy년 MMMM"
                },
          allDaySlot: false,
          selectHelper: true,
          select: function(start, end, allDay) {
            var title = prompt("Event Title:");
            if (title) {
              calendar.fullCalendar("renderEvent",
                {
                  title: title,
                  start: start,
                  end: end,
                  allDay: allDay
                },
                true // make the event "stick"
              );
            }
            calendar.fullCalendar("unselect");
          },
          droppable: false, // this allows things to be dropped onto the calendar !!!
          drop: function(date, allDay) { // this function is called when something is dropped

            // retrieve the dropped element"s stored Event Object
            var originalEventObject = $(this).data("eventObject");

            // we need to copy it, so that multiple events don"t have a reference to the same object
            var copiedEventObject = $.extend({}, originalEventObject);

            // assign it the date that was reported
            copiedEventObject.start = date;
            copiedEventObject.allDay = allDay;

            // render the event on the calendar
            // the last `true` argument determines if the event "sticks" (https://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
            $("#calendar").fullCalendar("renderEvent", copiedEventObject, true);

            // is the "remove after drop" checkbox checked?
            if ($("#drop-remove").is(":checked")) {
              // if so, remove the element from the "Draggable Events" list
              $(this).remove();
            }
          },
          events: [
  <?php
    foreach ($listMonthNotice as $value) {
      $startDate = strtotime($value['startdate']);
      $endDate = calcEndDate($value['startdate'], $value['schedule']);
      $viewNoticeStatus = viewNoticeStatus($value['status'])
  ?>
              {
                title: '<?=$viewNoticeStatus?><?=$value['mname']?>',
                start: new Date(y, m, <?=date('j', $startDate)?>),
                end: new Date(y, m, <?=date('j', $endDate)?>),
                url: '<?=base_url()?>admin/list_progress/<?=$value['idx']?>',
                className: 'notice-status<?=$value['status']?>'
              },
  <?php
    }
  ?>
          ],
        });
      });
    </script>

    <section id="schedule">
      <div class="container">
        <header class="section-header">
          <h3>산행 일정</h3>
        </header>
        <div id="calendar"></div>
      </div>
    </section>

    <!--==========================
      About Us Section
    ============================-->
    <section id="about">
      <div class="container">

        <header class="section-header">
          <h3>예약 진행중인 산행 내역</h3>
        </header>

        <div class="row about-cols">

          <div class="col-md-4 wow fadeInUp">
            <div class="about-col">
              <div class="img">
                <img src="/public/images/mt_1.png" alt="" class="img-fluid">
              </div>
              <h2 class="title"><a href="#">[확정] 울릉도 성인봉 & 독도 1무1박3일</a></h2>
              <p>
                일시 : 2019-06-28 (금) 23:30<br>
                분담금 : 310,000원<br>
                예약인원 : 42명
              </p>
            </div>
          </div>

          <div class="col-md-4 wow fadeInUp" data-wow-delay="0.1s">
            <div class="about-col">
              <div class="img">
                <img src="/public/images/mt_2.png" alt="" class="img-fluid">
              </div>
              <h2 class="title"><a href="#">[확정] 울릉도 성인봉 & 독도 유람선</a></h2>
              <p>
                일시 : 2019-06-28 (금) 23:30<br>
                분담금 : 322,000원<br>
                예약인원 : 44명
              </p>
            </div>
          </div>

          <div class="col-md-4 wow fadeInUp" data-wow-delay="0.2s">
            <div class="about-col">
              <div class="img">
                <img src="/public/images/mt_3.png" alt="" class="img-fluid">
              </div>
              <h2 class="title"><a href="#">[확정] 금대봉 매봉산 바람의언덕</a></h2>
              <p>
                일시 : 2019-07-06 (토) 6:30<br>
                분담금 : 30,000원<br>
                예약인원 : 28명
              </p>
            </div>
          </div>

        </div>

      </div>
    </section><!-- #about -->

    <!--==========================
      Portfolio Section
    ============================-->
    <section id="portfolio"  class="section-bg" >
      <div class="container">

        <header class="section-header">
          <h3 class="section-title">지난 산행 내역</h3>
        </header>

        <div class="row portfolio-container">

          <div class="col-lg-4 col-md-6 portfolio-item filter-app wow fadeInUp">
            <div class="portfolio-wrap">
              <figure>
                <img src="/public/images/oldmt_1.jpg" class="img-fluid" alt="">
                <a href="#" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
                <a href="#" class="link-details" title="More Details"><i class="ion ion-android-open"></i></a>
              </figure>

              <div class="portfolio-info">
                <h4><a href="#">소백산 국망봉</a></h4>
                <p>2019-06-23 (일)</p>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-web wow fadeInUp" data-wow-delay="0.1s">
            <div class="portfolio-wrap">
              <figure>
                <img src="/public/images/oldmt_2.jpg" class="img-fluid" alt="">
                <a href="#" class="link-preview" data-lightbox="portfolio" data-title="Web 3" title="Preview"><i class="ion ion-eye"></i></a>
                <a href="#" class="link-details" title="More Details"><i class="ion ion-android-open"></i></a>
              </figure>

              <div class="portfolio-info">
                <h4><a href="#">설악산 십이선녀탕 계곡</a></h4>
                <p>2019-06-22 (토)</p>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-app wow fadeInUp" data-wow-delay="0.2s">
            <div class="portfolio-wrap">
              <figure>
                <img src="/public/images/oldmt_3.jpg" class="img-fluid" alt="">
                <a href="#" class="link-preview" data-lightbox="portfolio" data-title="App 2" title="Preview"><i class="ion ion-eye"></i></a>
                <a href="#" class="link-details" title="More Details"><i class="ion ion-android-open"></i></a>
              </figure>

              <div class="portfolio-info">
                <h4><a href="#">쉰움산 두타산 무릉계곡</a></h4>
                <p>2019-06-16 (일)</p>
              </div>
            </div>
          </div>
<!--
          <div class="col-lg-4 col-md-6 portfolio-item filter-card wow fadeInUp">
            <div class="portfolio-wrap">
              <figure>
                <img src="/public/images/portfolio/card2.jpg" class="img-fluid" alt="">
                <a href="/public/images/portfolio/card2.jpg" class="link-preview" data-lightbox="portfolio" data-title="Card 2" title="Preview"><i class="ion ion-eye"></i></a>
                <a href="#" class="link-details" title="More Details"><i class="ion ion-android-open"></i></a>
              </figure>

              <div class="portfolio-info">
                <h4><a href="#">Card 2</a></h4>
                <p>Card</p>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-web wow fadeInUp" data-wow-delay="0.1s">
            <div class="portfolio-wrap">
              <figure>
                <img src="/public/images/portfolio/web2.jpg" class="img-fluid" alt="">
                <a href="/public/images/portfolio/web2.jpg" class="link-preview" data-lightbox="portfolio" data-title="Web 2" title="Preview"><i class="ion ion-eye"></i></a>
                <a href="#" class="link-details" title="More Details"><i class="ion ion-android-open"></i></a>
              </figure>

              <div class="portfolio-info">
                <h4><a href="#">Web 2</a></h4>
                <p>Web</p>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-app wow fadeInUp" data-wow-delay="0.2s">
            <div class="portfolio-wrap">
              <figure>
                <img src="/public/images/portfolio/app3.jpg" class="img-fluid" alt="">
                <a href="/public/images/portfolio/app3.jpg" class="link-preview" data-lightbox="portfolio" data-title="App 3" title="Preview"><i class="ion ion-eye"></i></a>
                <a href="#" class="link-details" title="More Details"><i class="ion ion-android-open"></i></a>
              </figure>

              <div class="portfolio-info">
                <h4><a href="#">App 3</a></h4>
                <p>App</p>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-card wow fadeInUp">
            <div class="portfolio-wrap">
              <figure>
                <img src="/public/images/portfolio/card1.jpg" class="img-fluid" alt="">
                <a href="/public/images/portfolio/card1.jpg" class="link-preview" data-lightbox="portfolio" data-title="Card 1" title="Preview"><i class="ion ion-eye"></i></a>
                <a href="#" class="link-details" title="More Details"><i class="ion ion-android-open"></i></a>
              </figure>

              <div class="portfolio-info">
                <h4><a href="#">Card 1</a></h4>
                <p>Card</p>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-card wow fadeInUp" data-wow-delay="0.1s">
            <div class="portfolio-wrap">
              <figure>
                <img src="/public/images/portfolio/card3.jpg" class="img-fluid" alt="">
                <a href="/public/images/portfolio/card3.jpg" class="link-preview" data-lightbox="portfolio" data-title="Card 3" title="Preview"><i class="ion ion-eye"></i></a>
                <a href="#" class="link-details" title="More Details"><i class="ion ion-android-open"></i></a>
              </figure>

              <div class="portfolio-info">
                <h4><a href="#">Card 3</a></h4>
                <p>Card</p>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-web wow fadeInUp" data-wow-delay="0.2s">
            <div class="portfolio-wrap">
              <figure>
                <img src="/public/images/portfolio/web1.jpg" class="img-fluid" alt="">
                <a href="/public/images/portfolio/web1.jpg" class="link-preview" data-lightbox="portfolio" data-title="Web 1" title="Preview"><i class="ion ion-eye"></i></a>
                <a href="#" class="link-details" title="More Details"><i class="ion ion-android-open"></i></a>
              </figure>

              <div class="portfolio-info">
                <h4><a href="#">Web 1</a></h4>
                <p>Web</p>
              </div>
            </div>
          </div>

        </div>
-->
      </div>
    </section><!-- #portfolio -->

  </main>

  <!--==========================
    Footer
  ============================-->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6 footer-info">
            <h3>경인웰빙</h3>
            <p>경인웰빙은 토요산행과 일요산행을 매주 운행하는 산악회입니다. 차내 음주가무 없으며, 산림청 인증 공인 등산안내인이 산행을 안내해 드립니다. 최초 계산역을 출발, 작전역, 갈산역, 부평구청역, 삼산체육관, 부천터미널소풍, 복사골문화센터, 송내남부를 경유하며, 44인승 대형 관광버스로 운행합니다.</p>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Site Links</h4>
            <ul>
              <li><i class="ion-ios-arrow-right"></i> <a href="#">TOP</a></li>
              <li><i class="ion-ios-arrow-right"></i> <a href="#">산행일정</a></li>
              <li><i class="ion-ios-arrow-right"></i> <a href="#">백산백소</a></li>
              <li><i class="ion-ios-arrow-right"></i> <a href="#">마이페이지</a></li>
              <li><i class="ion-ios-arrow-right"></i> <a href="#">로그인</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-contact">
            <h4>Contact Us</h4>
            <p>
              14545<br>
              경기도 부천시 상동로 90<br>
              메가플러스빌딩 6층<br>
              <strong>Phone :</strong> 010-7271-3050<br>
              <strong>Email :</strong> giwb@giwb.kr<br>
            </p>

            <div class="social-links">
              <a href="#" class="twitter"><i class="fa fa-twitter"></i></a>
              <a href="#" class="facebook"><i class="fa fa-facebook"></i></a>
              <a href="#" class="instagram"><i class="fa fa-instagram"></i></a>
              <a href="#" class="google-plus"><i class="fa fa-google-plus"></i></a>
              <a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a>
            </div>

          </div>

          <div class="col-lg-3 col-md-6 footer-newsletter">
            <h4>인터넷 대행 서비스 SayHome 안내</h4>
            <p>SayHome은 2005년 창업하여 다양한 인터넷 사업을 진행하고 있는 인터넷 솔루션 전문 기업입니다. 인터넷 홈페이지, 모바일페이지 등과, 다양한 웹 솔루션 제작의 노하우를 가지고 있으며, 서버 운영과 관리, 사이트 분석까지 대행하는 토탈 인터넷 대행 서비스 입니다.</p>
            URL : <a target="_blank" href="http://sayhome.co.kr">http://sayhome.co.kr</a><br>
            <!--
            <form action="" method="post">
              <input type="email" name="email"><input type="submit"  value="Subscribe">
            </form>-->
          </div>

        </div>
      </div>
    </div>
