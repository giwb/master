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
          <!--/Section: Magazine posts-->

          <!--Pagination dark-->
          <div class="text-center mb-5">
            <a href="javascript:alert('클릭하면 하단에 다음 기사들이 주루룩 나오는 형태입니다');" class="btn btn-info" rel="nofollow">더 보기</a>
          </div>
          <!--/Pagination dark grey-->

        </div>
        <!--/ Main news -->

        <!-- Sidebar -->
        <div class="col-xl-4 col-md-12 widget-column mt-0">

          <!-- Section: Categories -->
          <section class="section mb-5">

            <h4 class="font-weight-bold"><strong>분류별 기사</strong></h4>
            <hr class="red">

            <ul class="list-group z-depth-1 mt-4">
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <a>뉴스</a>
                <span class="badge badge-danger badge-pill">4</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <a>특집기사</a>
                <span class="badge badge-danger badge-pill">2</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <a>산행 정보</a>
                <span class="badge badge-danger badge-pill">1</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <a>산행 리뷰</a>
                <span class="badge badge-danger badge-pill">2</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <a>상품</a>
                <span class="badge badge-danger badge-pill">5</span>
              </li>
            </ul>
          </section>
          <!-- Section: Categories -->

          <section class="section mb-3">
            <h4 class="row font-weight-bold">
              <div class="col-6"><strong>월간 산악회 랭킹</strong></div>
              <div class="col-6 text-right"><a href="javascript:;" class="btn btn-default pt-2 pb-2 pl-4 pr-4 m-0">더 보기</a></div>
            </h4>
            <div class="card pb-3">
              <div class="row pl-3 pr-3 pt-3">
                <div class="col-4"><img src="/public/images/tripkorea/medal1.png" align="left"> 경인웰빙</div>
                <div class="col-8 pl-0"><img width="68%" height="10" src="/public/images/tripkorea/dot.png"> 192점</div>
              </div>
              <div class="row pl-3 pr-3 pt-2">
                <div class="col-4"><img src="/public/images/tripkorea/medal2.png" align="left"> 한국여행</div>
                <div class="col-8 pl-0"><img width="60%" height="10" src="/public/images/tripkorea/dot.png"> 154점</div>
              </div>
              <div class="row pl-3 pr-3 pt-2">
                <div class="col-4"><img src="/public/images/tripkorea/medal3.png" align="left"> 좋은사람들</div>
                <div class="col-8 pl-0"><img width="55%" height="10" src="/public/images/tripkorea/dot.png"> 148점</div>
              </div>
              <div class="row pl-3 pr-3 pt-2">
                <div class="col-4"><img src="/public/images/tripkorea/medal4.png" align="left"> 나쁜사람들</div>
                <div class="col-8 pl-0"><img width="32%" height="10" src="/public/images/tripkorea/dot.png"> 58점</div>
              </div>
              <div class="row pl-3 pr-3 pt-2">
                <div class="col-4"><img src="/public/images/tripkorea/medal5.png" align="left"> 보통사람들</div>
                <div class="col-8 pl-0"><img width="10%" height="10" src="/public/images/tripkorea/dot.png"> 27점</div>
              </div>
            </div>
          </section>

          <!-- Section: Advertisment -->
          <section class="section mt-5">

            <!--Jumbotron-->
            <div class="jumbotron text-center">

              <!--Title-->
              <h1 class="card-title h2-responsive mt-2"><strong>이곳에는 광고가 들어갑니다</strong></h1>
              <!--Subtitle-->
              <p class="blue-text mb-4 mt-5 font-weight-bold">여기는 광고 섹션입니다.</p>

              <!--Text-->
              <div class="d-flex justify-content-center">
                <p class="card-text mb-3" style="max-width: 43rem;">여기는 광고 섹션입니다.여기는 광고 섹션입니다.여기는 광고 섹션입니다.여기는 광고 섹션입니다.여기는 광고 섹션입니다.여기는 광고 섹션입니다.여기는 광고 섹션입니다.여기는 광고 섹션입니다.여기는 광고 섹션입니다.여기는 광고 섹션입니다.여기는 광고 섹션입니다.여기는 광고 섹션입니다.여기는 광고 섹션입니다.여기는 광고 섹션입니다.여기는 광고 섹션입니다.
                </p>
              </div>

              <hr class="my-4">

              <button type="button" class="btn btn-primary btn-sm waves-effect">Buy now <span class="far fa-gem ml-1"></span></button>
              <button type="button" class="btn btn-outline-primary btn-sm waves-effect">Download <i class="fas fa-download ml-1"></i></button>

            </div>
            <!--Jumbotron-->

          </section>
          <!--/ Section: Advertisment -->

          <!-- Section: Featured posts -->
          <section class="section widget-content mt-5">

            <h4 class="row font-weight-bold">
              <div class="col-6"><strong>산행 정보</strong></div>
              <div class="col-6 text-right"><a href="list.html" class="btn btn-default pt-2 pb-2 pl-4 pr-4 m-0">더 보기</a></div>
            </h4>
            <hr class="red mb-4">

            <div class="card card-body pb-5">
              <div class="single-post pb-4 mb-4">
                <h6 class="mt-0 mb-3"><a href="/article"><strong>[경인웰빙] 태백산 장군봉 눈꽃</strong></a></h6>
                <div class="row">
                  <div class="col-4">
                    <div class="view overlay">
                      <img width="126" height="84" src="https://giwb.kr/public/uploads/editor/161248371254205.jpg">
                      <a href="/article">
                        <div class="mask waves-light"></div>
                      </a>
                    </div>
                  </div>
                  <div class="col-8 pl-0 pr-0">
                    <div class="post-data">
                      <p class="font-small mb-0">
                        ・일시 : 2021-02-07 (일) 06:00<br>
                        ・요금 : 31,000원 / 1인우등 41,000원<br>
                        ・거리 : 산행 8.4km + 도로 1.0km / 약 5시간<br>
                        ・예약 : 32명<br>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="single-post pb-4 mb-4">
                <h6 class="mt-0 mb-3"><a href="/article"><strong>[한국여행] 오대산 비로봉 & 두로령 옛길</strong></a></h6>
                <div class="row">
                  <div class="col-4">
                    <div class="view overlay">
                      <img width="126" height="84" src="https://giwb.kr/public/uploads/editor/161248267682782.jpg">
                      <a href="/article">
                        <div class="mask waves-light"></div>
                      </a>
                    </div>
                  </div>
                  <div class="col-8 pl-0 pr-0">
                    <div class="post-data">
                      <p class="font-small mb-0">
                        ・일시 : 2021-02-06 (토) 06:30<br>
                        ・요금 : 31,000원 / 1인우등 41,000원<br>
                        ・거리 : 임도포함 13.8km / 약 5~6시간 소요<br>
                        ・예약 : 29명<br>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="single-post pb-4 mb-4">
                <h6 class="mt-0 mb-3"><a href="/article"><strong>덕유산 향적봉 눈꽃산행</strong></a></h6>
                <div class="row">
                  <div class="col-4">
                    <div class="view overlay">
                      <img width="126" height="84" src="https://giwb.kr/public/uploads/editor/161050582585413.jpg">
                      <a href="/article">
                        <div class="mask waves-light"></div>
                      </a>
                    </div>
                  </div>
                  <div class="col-8 pl-0 pr-0">
                    <div class="post-data">
                      <p class="font-small mb-0">
                        ・일시 : 2021-01-31 (일) 06:00<br>
                        ・요금 : 32,000원 / 1인우등 42,000원<br>
                        ・거리 : 산행 9.1km + 곤돌라 2.7km / 약 5~6시간<br>
                        ・예약 : 53명<br>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="single-post pb-4 mb-4">
                <h6 class="mt-0 mb-3"><a href="/article"><strong>소백산 비로봉 칼바람 눈꽃산행</strong></a></h6>
                <div class="row">
                  <div class="col-4">
                    <div class="view overlay">
                      <img width="126" height="84" src="https://giwb.kr/public/uploads/editor/161049859367645.jpg">
                      <a href="/article">
                        <div class="mask waves-light"></div>
                      </a>
                    </div>
                  </div>
                  <div class="col-8 pl-0 pr-0">
                    <div class="post-data">
                      <p class="font-small mb-0">
                        ・일시 : 2021-01-30 (토) 06:30<br>
                        ・요금 : 29,000원 / 1인우등 39,000원<br>
                        ・거리 : 12.5km / 약 6시간 소요<br>
                        ・예약 : 55명<br>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="single-post">
                <h6 class="mt-0 mb-3"><a href="/article"><strong>계방산 눈꽃산행</strong></a></h6>
                <div class="row">
                  <div class="col-4">
                    <div class="view overlay">
                      <img width="126" height="84" src="https://giwb.kr/public/uploads/editor/161049470999541.jpg">
                      <a href="/article">
                        <div class="mask waves-light"></div>
                      </a>
                    </div>
                  </div>
                  <div class="col-8 pl-0 pr-0">
                    <div class="post-data">
                      <p class="font-small mb-0">
                        ・일시 : 2021-01-23 (토) 06:30<br>
                        ・요금 : 29,000원 / 1인우등 39,000원<br>
                        ・거리 : 11.7km / 약 5시간<br>
                        ・예약 : 33명<br>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <!--/ Section: Featured posts -->
        </div>
        <!--/ Sidebar -->
      </div>
      <!--/ Magazine -->
    </div>
  </main>
  <!--/ Main layout -->
