<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <script type="text/javascript">
    $(document).ready(function() {   
      // Collapse Navbar
      var navbarCollapse = function() {
        if ($("#mainNav").offset().top < 650) {
          $("#mainNav").addClass("navbar-scrolled");
        } else {
          $("#mainNav").removeClass("navbar-scrolled");
        }
      };
      // Collapse now if page is not at top
      navbarCollapse();
      // Collapse the navbar when page is scrolled
      $(window).scroll(navbarCollapse);
    });
  </script>

  <div id="fb-root"></div>
  <div id="carousel-main" class="carousel slide carousel-fade" data-ride="carousel">
    <ol class="carousel-indicators">
      <li data-target="#carousel-main" data-slide-to="0" class="active"></li>
      <li data-target="#carousel-main" data-slide-to="1" class="active"></li>
      <li data-target="#carousel-main" data-slide-to="2" class="active"></li>
    </ol>
    <div class="carousel-inner" role="listbox">
      <div class="carousel-item active">
        <div class="view h-100 d-flex justify-content-center">
          <img class="d-block h-100 w-lg-100" src="/public/images/top1.jpg">
          <div class="mask rgba-black-light">
            <div class="full-bg-img flex-center white-text">
              <ul class="list-unstyled animated fadeIn col-10">
                <li>
                  <h1 class="h1-responsive font-weight-bold"><?=$view['title']?></h1>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <div class="view h-100 d-flex justify-content-center">
          <img class="d-block h-100 w-lg-100" src="/public/images/top2.jpg">
          <div class="mask rgba-black-light">
            <div class="full-bg-img flex-center white-text">
              <ul class="list-unstyled animated fadeIn col-10">
                <li>
                  <h1 class="h1-responsive font-weight-bold"><?=$view['title']?></h1>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <div class="view h-100 d-flex justify-content-center">
          <img class="d-block h-100 w-lg-100" src="/public/images/top3.jpg">
          <div class="mask rgba-black-light">
            <div class="full-bg-img flex-center white-text">
              <ul class="list-unstyled animated fadeIn col-10">
                <li>
                  <h1 class="h1-responsive font-weight-bold"><?=$view['title']?></h1>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <a class="carousel-control-prev" href="#carousel-main" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carousel-main" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>

  <main id="mainpage">
    <div class="container-fluid mt-4">
      <div class="row mt-1">
        <div class="col-xl-8 col-md-12">
          <section class="section extra-margins listing-section">
            <h4 class="row font-weight-bold">
              <div class="col-6"><strong>다음 여행</strong></div>
              <div class="col-6 text-right"><!--<a href="javascript:;" class="btn btn-default pt-2 pb-2 pl-4 pr-4 m-0">더 보기</a>--></div>
            </h4>
            <hr class="text-default">
            <div class="row">
              <div class="col-md-6 my-3">
                <div class="card main-card">
                  <div class="view overlay">
                    <img src="<?=PHOTO_URL . $listNotice[0]['photo']?>" class="card-img-top">
                    <a href="<?=BASE_URL?>/reserve/list/<?=$listNotice[0]['idx']?>"><div class="mask rgba-white-slight"></div></a>
                  </div>
                  <div class="card-body">
                    <h4 class="card-title"><div class="card-text mb-1"><?php foreach ($listNotice[0]['sido'] as $key => $value): if ($key != 0): ?>, <?php endif; ?><?=$value?> <?=!empty($listNotice[0]['gugun'][$key]) ? $listNotice[0]['gugun'][$key] : ''?><?php endforeach; ?></div><strong><a><?=$listNotice[0]['subject']?></a></strong></h4><hr>
                    <p class="card-text text-justify">
                      ・일시 : <?=$listNotice[0]['startdate']?> <?=$listNotice[0]['starttime']?><br>
                      ・요금 : <?=number_format($listNotice[0]['cost_total'] == 0 ? $listNotice[0]['cost'] : $listNotice[0]['cost_total'])?>원<br>
                      ・거리 : <?=$listNotice[0]['kilometer']?>
                    </p>
                  </div>
                  <div class="mdb-color lighten-3 text-center">
                    <ul class="list-unstyled list-inline font-small mt-3">
                      <li class="list-inline-item pr-1"><a class="white-text"><i class="far fa-eye pr-1"></i>조회 <?=$listNotice[0]['refer']?></a></li>
                      <li class="list-inline-item pr-1"><a class="white-text"><i class="far fa-comments pr-1"></i>댓글 <?=$listNotice[0]['reply_cnt']?></a></li>
                      <li class="list-inline-item pr-1"><a class="white-text"><i class="far fa-calendar-check pr-1"></i>예약 <?=cntRes($listNotice[0]['idx'])?></a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-md-6 my-3">
                <div class="card main-card">
                  <div class="view overlay">
                    <img src="<?=PHOTO_URL . $listNotice[1]['photo']?>" class="card-img-top">
                    <a href="<?=BASE_URL?>/reserve/list/<?=$listNotice[1]['idx']?>"><div class="mask rgba-white-slight"></div></a>
                  </div>
                  <div class="card-body">
                    <h4 class="card-title"><div class="card-text mb-1"><?php foreach ($listNotice[1]['sido'] as $key => $value): if ($key != 0): ?>, <?php endif; ?><?=$value?> <?=!empty($listNotice[1]['gugun'][$key]) ? $listNotice[1]['gugun'][$key] : ''?><?php endforeach; ?></div><strong><a><?=$listNotice[1]['subject']?></a></strong></h4>
                    <hr>
                    <p class="card-text text-justify">
                      ・일시 : <?=$listNotice[1]['startdate']?> <?=$listNotice[1]['starttime']?><br>
                      ・요금 : <?=number_format($listNotice[1]['cost_total'] == 0 ? $listNotice[1]['cost'] : $listNotice[1]['cost_total'])?>원<br>
                      ・거리 : <?=$listNotice[1]['kilometer']?>
                    </p>
                  </div>
                  <div class="mdb-color lighten-3 text-center">
                    <ul class="list-unstyled list-inline font-small mt-3">
                      <li class="list-inline-item pr-1"><a class="white-text"><i class="far fa-eye pr-1"></i>조회 <?=$listNotice[1]['refer']?></a></li>
                      <li class="list-inline-item pr-1"><a class="white-text"><i class="far fa-comments pr-1"></i>댓글 <?=$listNotice[1]['reply_cnt']?></a></li>
                      <li class="list-inline-item pr-1"><a class="white-text"><i class="far fa-calendar-check pr-1"></i>예약 <?=cntRes($listNotice[1]['idx'])?></a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <div class="row">
            <section class="col-md-6 section extra-margins listing-section mt-3">
                <h4 class="row font-weight-bold">
                  <div class="col-6"><strong>여행 소식</strong></div>
                  <div class="col-6 text-right"><a href="<?=BASE_URL?>/search/?code=news" class="btn btn-default pt-2 pb-2 pl-4 pr-4 m-0">더 보기</a></div>
                </h4>
              <hr class="text-default">
              <div class="text-left mt-3 mb-4">
                <div class="card main-card">
                  <div class="view overlay">
                    <img src="<?=getThumbnail($viewNews[0]['content'])?>" class="card-img-top">
                    <a href="<?=BASE_URL?>/article/<?=$viewNews[0]['idx']?>"><div class="mask rgba-white-slight"></div></a>
                  </div>
                  <div class="card-body">
                    <h4 class="card-title"><strong><a href="<?=BASE_URL?>/article/<?=$viewNews[0]['idx']?>"><?=$viewNews[0]['title']?></a></strong></h4><hr>
                    <p class="card-text text-justify">
                      <?=articleContent($viewNews[0]['content'])?>
                    </p>
                  </div>
                  <div class="mdb-color lighten-3 text-center">
                    <ul class="list-unstyled list-inline font-small mt-3">
                      <li class="list-inline-item pr-1 white-text"><?=$viewNews[0]['category_name']?></li>
                      <li class="list-inline-item pr-1 white-text"><i class="far fa-clock-o pr-1"></i><?=date('Y-m-d', $viewNews[0]['viewing_at'])?></li>
                      <li class="list-inline-item pr-1 white-text"><i class="far fa-eye pr-1"></i><?=$viewNews[0]['cntRefer']?></li>
                      <li class="list-inline-item pr-1 white-text"><i class="far fa-heart pr-1"></i><?=$viewNews[0]['cntLiked']?></li>
                      <li class="list-inline-item pr-1 white-text"><i class="far fa-comments pr-1"></i><?=$viewNews[0]['cntReply']?></li>
                    </ul>
                  </div>
                </div>
              </div>
            </section>
            <section class="col-md-6 section extra-margins listing-section mt-3">
              <h4 class="row font-weight-bold">
                <div class="col-6"><strong>여행 후기</strong></div>
                <div class="col-6 text-right"><a href="<?=BASE_URL?>/search/?code=review" class="btn btn-default pt-2 pb-2 pl-4 pr-4 m-0">더 보기</a></div>
              </h4>
              <hr class="text-default">
              <div class="text-left mt-3 mb-4">
                <div class="card main-card">
                  <div class="view overlay">
                    <img src="<?=getThumbnail($viewLogs[0]['content'])?>" class="card-img-top">
                    <a href="<?=BASE_URL?>/article/<?=$viewLogs[0]['idx']?>"><div class="mask rgba-white-slight"></div></a>
                  </div>
                  <div class="card-body">
                    <h4 class="card-title"><strong><a href="<?=BASE_URL?>/article/<?=$viewLogs[0]['idx']?>"><?=$viewLogs[0]['title']?></a></strong></h4><hr>
                    <p class="card-text text-justify">
                      <?=articleContent($viewLogs[0]['content'])?>
                    </p>
                  </div>
                  <div class="mdb-color lighten-3 text-center">
                    <ul class="list-unstyled list-inline font-small mt-3">
                      <li class="list-inline-item pr-1 white-text"><?=$viewLogs[0]['category_name']?></li>
                      <li class="list-inline-item pr-1 white-text"><i class="far fa-clock-o pr-1"></i><?=date('Y-m-d', $viewLogs[0]['viewing_at'])?></li>
                      <li class="list-inline-item pr-1 white-text"><i class="far fa-eye pr-1"></i><?=$viewLogs[0]['cntRefer']?></li>
                      <li class="list-inline-item pr-1 white-text"><i class="far fa-heart pr-1"></i><?=$viewLogs[0]['cntLiked']?></li>
                      <li class="list-inline-item pr-1 white-text"><i class="far fa-comments pr-1"></i><?=$viewLogs[0]['cntReply']?></li>
                    </ul>
                  </div>
                </div>
              </div>
            </section>
          </div>

          <section id="album" class="section extra-margins mt-3">
            <h4 class="row font-weight-bold">
              <div class="col-6"><strong>산행 앨범</strong></div>
              <div class="col-6 text-right"><a href="<?=BASE_URL?>/album" class="btn btn-default pt-2 pb-2 pl-4 pr-4 m-0">더 보기</a></div>
            </h4>
            <hr class="text-default mb-4">
            <div class="row mb-4">
              <?php foreach ($listAlbum as $value): ?>
              <div class="col-6 col-md-3"><a href="<?=BASE_URL?>/album"><img src="<?=$value['photo']?>" class="album-photo border mb-3"></a></div>
              <?php endforeach; ?>
            </div>
          </section>
        </div>
