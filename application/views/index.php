<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <!--==========================
    Intro Section
  ============================-->
  <section id="intro">
    <div class="intro-video">
      <div class="intro-background">
        <!--<iframe src="https://www.youtube.com/embed/TMTul_kn1xw?loop=1&amp;autoplay=1&amp;controls=0&amp;modestbranding=0&amp;start=65&amp;playlist=TMTul_kn1xw" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture; loop" allowfullscreen></iframe>-->
      </div>
    </div>
    <div class="intro-container">
      <div id="introCarousel" class="carousel slide carousel-fade" data-ride="carousel">
        <ol class="carousel-indicators"></ol>
        <div class="carousel-inner" role="listbox">
          <div class="carousel-item">
            <div class="carousel-background"><img src="/public/images/main_1.jpg" alt=""></div>
            <div class="carousel-container">
              <div class="carousel-content">
                <h2>인터넷 세상에 내 집을 만들다</h2>
                <p>무한한 인터넷이라는 영토에 당신만의 집을 만들고,<br>전 세계 사람들과 소통하세요!</p>
                <a href="#featured-services" class="btn-get-started scrollto">지금 시작합시다!</a>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="carousel-background"><img src="/public/images/main_1.jpg" alt=""></div>
            <div class="carousel-container">
              <div class="carousel-content">
                <h2>인터넷 세상에 내 집을 만들다</h2>
                <p>무한한 인터넷이라는 영토에 당신만의 집을 만들고,<br>전 세계 사람들과 소통하세요!</p>
                <a href="#featured-services" class="btn-get-started scrollto">지금 시작합시다!</a>
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
      Services Section
    ============================-->
    <!--
    <section id="services">
      <div class="container">

        <header class="section-header wow fadeInUp">
          <h3>서비스</h3>
          <p>우리의 서비스는 인터넷상의 모든 업무를 대행합니다. 아래의 대표적인 서비스 이외에도, 다양한 상담을 받고 있으니,<br>인터넷 비지니스에 대해 궁금한 것이 있다면 언제든 문의해주세요!</p>
        </header>

        <div class="row">

          <div class="col-lg-4 col-md-6 box wow bounceInUp" data-wow-duration="1.4s">
            <div class="icon"><i class="ion-ios-analytics-outline"></i></div>
            <h4 class="title"><a href="">홈페이지 개발</a></h4>
            <p class="description">간단한 1페이지 랜딩페이지(LP)부터 대규모의 홈페이지 제작까지, 고객께서 원하시는 최적의 인터넷상의 내 집을 만들어 드립니다.</p>
          </div>
          <div class="col-lg-4 col-md-6 box wow bounceInUp" data-wow-duration="1.4s">
            <div class="icon"><i class="ion-ios-bookmarks-outline"></i></div>
            <h4 class="title"><a href="">모바일페이지 개발</a></h4>
            <p class="description">모바일페이지의 최초 구축부터, 기존의 낡았던 모바일페이지 개선 등, 어떤 기종에도 대응하는 최신 스타일의 반응형으로 제작합니다.</p>
          </div>
          <div class="col-lg-4 col-md-6 box wow bounceInUp" data-wow-duration="1.4s">
            <div class="icon"><i class="ion-ios-paper-outline"></i></div>
            <h4 class="title"><a href="">워드프레스 구축</a></h4>
            <p class="description">가장 인기있는 CMS 워드프레스로 홈페이지를 구축하여, 언제든지 고객께서 홈페이지의 유지보수가 가능하도록 제작해 드립니다.</p>
          </div>
          <div class="col-lg-4 col-md-6 box wow bounceInUp" data-wow-delay="0.1s" data-wow-duration="1.4s">
            <div class="icon"><i class="ion-ios-speedometer-outline"></i></div>
            <h4 class="title"><a href="">웹 솔루션 개발</a></h4>
            <p class="description">예약 시스템, 업무 자동화 시스템, 고객 관리 시스템 등, 지금까지 불편했던 오프라인상의 모든 업무를 인터넷으로 가능하게 개발합니다.</p>
          </div>
          <div class="col-lg-4 col-md-6 box wow bounceInUp" data-wow-delay="0.1s" data-wow-duration="1.4s">
            <div class="icon"><i class="ion-ios-barcode-outline"></i></div>
            <h4 class="title"><a href="">서버 운영, 관리</a></h4>
            <p class="description">이제 서버 때문에 고생하지 마세요. 24시간 365일 아무런 걱정없이 맡길 수 있도록 당신의 서버를 대신 관리해 드립니다.</p>
          </div>
          <div class="col-lg-4 col-md-6 box wow bounceInUp" data-wow-delay="0.1s" data-wow-duration="1.4s">
            <div class="icon"><i class="ion-ios-people-outline"></i></div>
            <h4 class="title"><a href="">고객 동향, 통계 분석</a></h4>
            <p class="description">구글 애널리틱스와 서치콘솔 등을 이용하여 홈페이지를 인터넷 세상에 널리 알리고, 누가 내 홈페이지에 들어오는지 분석해 드립니다.</p>
          </div>

        </div>

      </div>
    </section>
    --><!-- #services -->

    <!--==========================
      Call To Action Section
    ============================-->
    <!--
    <section id="call-to-action" class="wow fadeIn">
      <div class="container text-center">
        <h3>지금 문의하세요!</h3>
        <p>우리의 서비스에 대해 궁금한 점이 있으시다면, 아래의 버튼을 눌러 지금 곧바로 문의하세요!</p>
        <a class="cta-btn" href="#">전화합니다</a>
      </div>
    </section>
    --><!-- #call-to-action -->

    <!--==========================
      Skills Section
    ============================-->
    <!--
    <section id="skills">
      <div class="container">

        <header class="section-header">
          <h3>Our Skills</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip</p>
        </header>

        <div class="skills-content">

          <div class="progress">
            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
              <span class="skill">HTML <i class="val">100%</i></span>
            </div>
          </div>

          <div class="progress">
            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">
              <span class="skill">CSS <i class="val">90%</i></span>
            </div>
          </div>

          <div class="progress">
            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
              <span class="skill">JavaScript <i class="val">75%</i></span>
            </div>
          </div>

          <div class="progress">
            <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100">
              <span class="skill">Photoshop <i class="val">55%</i></span>
            </div>
          </div>

        </div>

      </div>
    </section>
    -->

    <!--==========================
      Facts Section
    ============================-->
    <!--
    <section id="facts"  class="wow fadeIn">
      <div class="container">

        <header class="section-header">
          <h3>Facts</h3>
          <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque</p>
        </header>

        <div class="row counters">

          <div class="col-lg-3 col-6 text-center">
            <span data-toggle="counter-up">274</span>
            <p>Clients</p>
          </div>

          <div class="col-lg-3 col-6 text-center">
            <span data-toggle="counter-up">421</span>
            <p>Projects</p>
          </div>

          <div class="col-lg-3 col-6 text-center">
            <span data-toggle="counter-up">1,364</span>
            <p>Hours Of Support</p>
          </div>

          <div class="col-lg-3 col-6 text-center">
            <span data-toggle="counter-up">18</span>
            <p>Hard Workers</p>
          </div>

        </div>

        <div class="facts-img">
          <img src="/public/images/facts-img.png" alt="" class="img-fluid">
        </div>

      </div>
    </section>
    --><!-- #facts -->

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

    <!--==========================
      Clients Section
    ============================-->
    <!--
    <section id="clients" class="wow fadeInUp">
      <div class="container">

        <header class="section-header">
          <h3>Our Clients</h3>
        </header>

        <div class="owl-carousel clients-carousel">
          <img src="/public/images/clients/client-1.png" alt="">
          <img src="/public/images/clients/client-2.png" alt="">
          <img src="/public/images/clients/client-3.png" alt="">
          <img src="/public/images/clients/client-4.png" alt="">
          <img src="/public/images/clients/client-5.png" alt="">
          <img src="/public/images/clients/client-6.png" alt="">
          <img src="/public/images/clients/client-7.png" alt="">
          <img src="/public/images/clients/client-8.png" alt="">
        </div>

      </div>
    </section>
    -->
    <!-- #clients -->

    <!--==========================
      Clients Section
    ============================-->
    <!--
    <section id="testimonials" class="section-bg wow fadeInUp">
      <div class="container">

        <header class="section-header">
          <h3>고객의 소리</h3>
        </header>

        <div class="owl-carousel testimonials-carousel">

          <div class="testimonial-item">
            <img src="/public/images/testimonial-1.jpg" class="testimonial-img" alt="">
            <h3>Saul Goodman</h3>
            <h4>Ceo &amp; Founder</h4>
            <p>
              <img src="/public/images/quote-sign-left.png" class="quote-sign-left" alt="">
              Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam, risus at semper.
              <img src="/public/images/quote-sign-right.png" class="quote-sign-right" alt="">
            </p>
          </div>

          <div class="testimonial-item">
            <img src="/public/images/testimonial-2.jpg" class="testimonial-img" alt="">
            <h3>Sara Wilsson</h3>
            <h4>Designer</h4>
            <p>
              <img src="/public/images/quote-sign-left.png" class="quote-sign-left" alt="">
              Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet legam anim culpa.
              <img src="/public/images/quote-sign-right.png" class="quote-sign-right" alt="">
            </p>
          </div>

          <div class="testimonial-item">
            <img src="/public/images/testimonial-3.jpg" class="testimonial-img" alt="">
            <h3>Jena Karlis</h3>
            <h4>Store Owner</h4>
            <p>
              <img src="/public/images/quote-sign-left.png" class="quote-sign-left" alt="">
              Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam duis minim tempor labore quem eram duis noster aute amet eram fore quis sint minim.
              <img src="/public/images/quote-sign-right.png" class="quote-sign-right" alt="">
            </p>
          </div>

          <div class="testimonial-item">
            <img src="/public/images/testimonial-4.jpg" class="testimonial-img" alt="">
            <h3>Matt Brandon</h3>
            <h4>Freelancer</h4>
            <p>
              <img src="/public/images/quote-sign-left.png" class="quote-sign-left" alt="">
              Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat minim velit minim dolor enim duis veniam ipsum anim magna sunt elit fore quem dolore labore illum veniam.
              <img src="/public/images/quote-sign-right.png" class="quote-sign-right" alt="">
            </p>
          </div>

          <div class="testimonial-item">
            <img src="/public/images/testimonial-5.jpg" class="testimonial-img" alt="">
            <h3>John Larson</h3>
            <h4>Entrepreneur</h4>
            <p>
              <img src="/public/images/quote-sign-left.png" class="quote-sign-left" alt="">
              Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor noster veniam enim culpa labore duis sunt culpa nulla illum cillum fugiat legam esse veniam culpa fore nisi cillum quid.
              <img src="/public/images/quote-sign-right.png" class="quote-sign-right" alt="">
            </p>
          </div>

        </div>

      </div>
    </section>
    -->
    <!-- #testimonials -->

    <!--==========================
      Team Section
    ============================-->
    <!--
    <section id="team">
      <div class="container">
        <div class="section-header wow fadeInUp">
          <h3>팀원소개</h3>
          <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque</p>
        </div>

        <div class="row">

          <div class="col-lg-3 col-md-6 wow fadeInUp">
            <div class="member">
              <img src="/public/images/team-1.jpg" class="img-fluid" alt="">
              <div class="member-info">
                <div class="member-info-content">
                  <h4>Walter White</h4>
                  <span>Chief Executive Officer</span>
                  <div class="social">
                    <a href=""><i class="fa fa-twitter"></i></a>
                    <a href=""><i class="fa fa-facebook"></i></a>
                    <a href=""><i class="fa fa-google-plus"></i></a>
                    <a href=""><i class="fa fa-linkedin"></i></a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
            <div class="member">
              <img src="/public/images/team-2.jpg" class="img-fluid" alt="">
              <div class="member-info">
                <div class="member-info-content">
                  <h4>Sarah Jhonson</h4>
                  <span>Product Manager</span>
                  <div class="social">
                    <a href=""><i class="fa fa-twitter"></i></a>
                    <a href=""><i class="fa fa-facebook"></i></a>
                    <a href=""><i class="fa fa-google-plus"></i></a>
                    <a href=""><i class="fa fa-linkedin"></i></a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
            <div class="member">
              <img src="/public/images/team-3.jpg" class="img-fluid" alt="">
              <div class="member-info">
                <div class="member-info-content">
                  <h4>William Anderson</h4>
                  <span>CTO</span>
                  <div class="social">
                    <a href=""><i class="fa fa-twitter"></i></a>
                    <a href=""><i class="fa fa-facebook"></i></a>
                    <a href=""><i class="fa fa-google-plus"></i></a>
                    <a href=""><i class="fa fa-linkedin"></i></a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
            <div class="member">
              <img src="/public/images/team-4.jpg" class="img-fluid" alt="">
              <div class="member-info">
                <div class="member-info-content">
                  <h4>Amanda Jepson</h4>
                  <span>Accountant</span>
                  <div class="social">
                    <a href=""><i class="fa fa-twitter"></i></a>
                    <a href=""><i class="fa fa-facebook"></i></a>
                    <a href=""><i class="fa fa-google-plus"></i></a>
                    <a href=""><i class="fa fa-linkedin"></i></a>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>
    </section>
    --><!-- #team -->

    <!--==========================
      Contact Section
    ============================-->
    <section id="contact" class="section-bg wow fadeInUp">
      <div class="container">

        <div class="section-header">
          <h3>안부인사방</h3>
          <!--<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque</p>-->
        </div>

        <div class="form">
          <div id="sendmessage">Your message has been sent. Thank you!</div>
          <div id="errormessage"></div>
          <form action="" method="post" role="form" class="contactForm">
            <div class="form-group">
              <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" />
              <div class="validation"></div>
            </div>
            <div class="form-group">
              <textarea class="form-control" name="message" rows="5" data-rule="required" data-msg="Please write something for us" placeholder="Message"></textarea>
              <div class="validation"></div>
            </div>
            <div class="text-center"><button type="submit">Send Message</button></div>
          </form>
        </div>

      </div>
    </section><!-- #contact -->

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
