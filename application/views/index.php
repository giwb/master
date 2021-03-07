<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <div id="carousel-example-1z" class="carousel slide carousel-fade" data-ride="carousel">
    <ol class="carousel-indicators">
      <li data-target="#carousel-example-1z" data-slide-to="0" class="active"></li>
      <li data-target="#carousel-example-1z" data-slide-to="1"></li>
      <li data-target="#carousel-example-1z" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner" role="listbox">
      <div class="carousel-item active">
        <div class="view h-100 d-flex justify-content-center">
          <img class="d-block h-100 w-lg-100" src="/public/images/tripkorea/1.jpg" alt="First slide">
          <div class="mask rgba-black-light">
            <!-- Caption -->
            <div class="full-bg-img flex-center white-text">
              <ul class="list-unstyled animated fadeIn col-10">
                <li>
                  <h1 class="h1-responsive font-weight-bold">계룡산 국립공원의 비경</h1>
                </li>
                <li>
                  <p>동학사와 갑사로 유명한, 계룡산 하늘공원에 다녀오다</p>
                </li>
                <li>
                  <a href="/article" class="btn btn-info" rel="nofollow">더 보기</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="carousel-item h-100">
        <div class="view h-100 d-flex justify-content-center">
          <img class="d-block h-100 w-lg-100" src="/public/images/tripkorea/2.jpg" alt="Second slide">
          <div class="mask rgba-stylish-strong">
            <!-- Caption -->
            <div class="full-bg-img flex-center white-text">
              <ul class="list-unstyled animated fadeIn col-10">
                <li>
                  <h1 class="h1-responsive font-weight-bold">겨울왕국으로 변한 계방산의 아름다움</h1>
                </li>
                <li>
                  <p>해발 1,577m의 계방산은 태백산맥의 한줄기로 동쪽으로 오대산을 바라보고 우뚝 서 있으며<br>한라, 지리, 설악, 덕유산에 이은 남한 제 5위봉이다.</p>
                </li>
                <li>
                  <a href="/article" class="btn btn-info" rel="nofollow">더 보기</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <div class="view h-100 d-flex justify-content-center">
          <img class="d-block h-100 w-lg-100" src="/public/images/tripkorea/3.jpg" alt="Third slide">
          <div class="mask rgba-black-light">
            <div class="full-bg-img flex-center white-text">
              <ul class="list-unstyled animated fadeIn col-md-12">
                <li>
                  <h1 class="h1-responsive font-weight-bold">지리산.. 어머니 품처럼 넓고 포근한 영산</h1>
                </li>
                <li>
                  <p>대한민국 화첩산행 100의 네번째 산은 지리산이다.<br>지금부터 성삼재에서 출발하는 32.5km 종주코스를 소개한다.</p>
                </li>
                <li>
                  <a href="/article" class="btn btn-default" rel="nofollow">더 보기</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <a class="carousel-control-prev" href="#carousel-example-1z" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carousel-example-1z" role="button" data-slide="next">
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
