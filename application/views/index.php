<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <div id="carousel-main" class="carousel slide carousel-fade" data-ride="carousel">
    <ol class="carousel-indicators">
      <?php foreach($listArticleMain as $key => $value): ?>
      <li data-target="#carousel-main" data-slide-to="<?=$key?>"<?=$key == 0 ? ' class="active"' : ''?>></li>
      <?php endforeach; ?>
    </ol>
    <div class="carousel-inner" role="listbox">
      <?php foreach($listArticleMain as $key => $value): ?>
      <div class="carousel-item<?=$key == 0 ? ' active' : ''?>">
        <div class="view h-100 d-flex justify-content-center">
          <img class="d-block h-100 w-lg-100" src="<?=PHOTO_ARTICLE_URL . $value['main_image']?>">
          <div class="mask rgba-black-light">
            <!-- Caption -->
            <div class="full-bg-img flex-center white-text">
              <ul class="list-unstyled animated fadeIn col-10">
                <li>
                  <h1 class="h1-responsive font-weight-bold"><?=$value['title']?></h1>
                </li>
                <li>
                  <p class="carousel-content"><?=articleContent($value['content'])?></p>
                </li>
                <li>
                  <a href="/article/<?=$value['idx']?>" class="btn btn-info" rel="nofollow">더 보기</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
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

  <main>
    <div class="container-fluid">
      <div class="row mt-1">
        <div class="col-xl-8 col-md-12">
          <section class="section extra-margins listing-section">
            <h4 class="font-weight-bold"><strong>최신 기사</strong></h4>
            <hr class="red">
            <div class="row mb-4">
              <?php foreach ($listArticle as $value): ?>
              <div class="col-md-6 my-3">
                <div class="card">
                  <div class="view overlay">
                    <img src="<?=getThumbnail($value['content'])?>" class="card-img-top">
                    <a href="/article/<?=$value['idx']?>"><div class="mask rgba-white-slight"></div></a>
                  </div>
                  <div class="card-body">
                    <h4 class="card-title"><strong><a href="/article/<?=$value['idx']?>"><?=$value['title']?></a></strong></h4><hr>
                    <p class="card-text text-justify"><?=articleContent($value['content'])?></p>
                  </div>
                  <div class="mdb-color lighten-3 text-center">
                    <ul class="list-unstyled list-inline font-small mt-3">
                      <li class="list-inline-item pr-1 white-text"><?=$value['category_name']?></li>
                      <li class="list-inline-item pr-1 white-text"><i class="far fa-clock-o pr-1"></i><?=date('Y-m-d', $value['viewing_at'])?></li>
                      <li class="list-inline-item pr-1"><a href="/article" class="white-text"><i class="far fa-comments pr-1"></i>0</a></li>
                      <li class="list-inline-item pr-1"><a href="/article" class="white-text"><i class="far fa-eye pr-1"></i>24</a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </section>

          <div class="text-center mb-5">
            <a href="javascript:alert('클릭하면 하단에 다음 기사들이 주루룩 나오는 형태입니다');" class="btn btn-info" rel="nofollow">더 보기</a>
          </div>
        </div>
