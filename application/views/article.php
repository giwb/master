<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-8 col-md-12">
          <div class="row mt-3 mb-5 pb-3 mx-2">
            <div class="card card-body mb-4">
              <div class="post-data mb-4">
                <h2 class="font-weight-bold mt-3 pl-3 pr-3"><strong><?=$view['title']?></strong></h2>
                <hr class="red title-hr">
                <div class="pt-3 pb-3">
                  <?=nl2br(reset_html_escape($view['content']))?>
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-6 mt-4">
                    <h5 class="font-weight-bold dark-grey-text">
                      <i class="far fa-lg fa-newspaper mr-3 dark-grey-text"></i>
                      <strong>147</strong> Views
                    </h5>
                  </div>
                  <div class="col-md-6 mt-2 d-flex justify-content-end">
                    <!--Facebook-->
                    <a type="button" class="btn-floating btn-small btn-fb waves-effect waves-light">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <!--Twitter-->
                    <a type="button" class="btn-floating btn-small btn-tw waves-effect waves-light">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <!--Google +-->
                    <a type="button" class="btn-floating btn-small btn-gplus waves-effect waves-light">
                        <i class="fab fa-google-plus-g"></i>
                    </a>
                    <!--Instagram-->
                    <a type="button" class="btn-floating btn-small btn-ins waves-effect waves-light">
                        <i class="fab fa-instagram"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <section class="text-left mb-3">
              <div class="card card-body">
                <div class="row">
                  <!--Avatar-->
                  <div class="col-12 col-sm-2 mb-md-0 mb-3 section-avatar">
                    <img src="img/can.jpg" class="img-fluid rounded-circle icon-avatar" alt="">
                  </div>
                  <!--Author Data-->
                  <div class="col-12 col-sm-10 section-avatar">
                      <p>
                          <strong>필자 : 경인웰빙산악회 - 캔총무</strong>
                      </p>
                      <div class="personal-sm">
                          <a class="pr-2 fb-ic">
                              <i class="fab fa-facebook-f"> </i>
                          </a>
                          <a class="p-2 tw-ic">
                              <i class="fab fa-twitter"> </i>
                          </a>
                          <a class="p-2 gplus-ic">
                              <i class="fab fa-google-plus-g"> </i>
                          </a>
                          <a class="p-2 li-ic">
                              <i class="fab fa-linkedin-in"> </i>
                          </a>
                      </div>
                      <p class="dark-grey-text article">인천과 부천 지역을 기반으로 토요산행과 일요산행을 매주 운행하는 안내산악회입니다. 차내 음주가무 없으며 초보자도 함께할 수 있도록 여유롭게 산행을 진행합니다.</p>
                  </div>
                </div>
              </div>
            </section>

            <h5 class="font-weight-bold mt-3">
              <strong>관련 굿즈</strong>
            </h5><hr class="red title-hr">

            <div class="row single-post mb-4">
              <div class="col-md-4 text-left mt-3">
                <div class="card">
                  <div class="view overlay">
                    <img src="img/goods1.jpg" class="card-img-top" alt="Sample image">
                    <a>
                        <div class="mask rgba-white-slight waves-effect waves-light"></div>
                    </a>
                  </div>
                  <div class="card-body">
                    <h4 class="card-title">
                        <strong>동계용 비닐쉘터</strong>
                    </h4><hr>
                    <p class="card-text mb-3">응용용품 > 비닐쉘터</p>
                  </div>
                </div>
              </div>
              <div class="col-md-4 text-left mt-3">
                <div class="card">
                  <div class="view overlay">
                    <img src="img/goods2.jpg" class="card-img-top" alt="Sample image">
                    <a>
                        <div class="mask rgba-white-slight waves-effect waves-light"></div>
                    </a>
                  </div>
                  <div class="card-body">
                    <h4 class="card-title">
                        <strong>에그 트레이 2구</strong>
                    </h4><hr>
                    <p class="card-text mb-3">응용용품 > 트레이</p>
                  </div>
                </div>
              </div>
              <div class="col-md-4 text-left mt-3">
                <div class="card">
                  <div class="view overlay">
                    <img src="img/goods3.jpg" class="card-img-top" alt="Sample image">
                    <a>
                        <div class="mask rgba-white-slight waves-effect waves-light"></div>
                    </a>
                  </div>
                  <div class="card-body">
                    <h4 class="card-title">
                        <strong>자연수 물티슈 (20매)</strong>
                    </h4><hr>
                    <p class="card-text mb-3">소모용품 > 화장지</p>
                  </div>
                </div>
              </div>
            </div>

            <h5 class="font-weight-bold mt-3">
              <strong>댓글</strong> <span class="badge indigo">2</span>
            </h5><hr class="red title-hr">

            <section>
              <div class="comments-list text-left">
                <div class="form-group basic-textarea rounded-corners"><textarea class="form-control z-depth-1" id="exampleFormControlTextarea3" rows="5" placeholder="댓글을 입력해주세요."></textarea></div>

                <div class="media">
                  <img class="d-flex rounded-circle avatar z-depth-1-half mr-3" src="https://giwb.kr/public/photos/2" alt="Avatar">
                  <div class="media-body">
                    <h5 class="mt-0 font-weight-bold">Jun</h5>
                    <p class="dark-grey-text article">아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! </p>
                  </div>
                </div>
                <div class="media mt-4">
                  <img class="d-flex rounded-circle avatar z-depth-1-half mr-3" src="https://giwb.kr/public/photos/1" alt="Avatar">
                  <div class="media-body">
                    <h5 class="mt-0 font-weight-bold">캔총무</h5>
                    <p class="dark-grey-text article">아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! 아주 좋은 산행기네요! </p>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
        <!--/ Main news -->

        <!-- Sidebar -->
        <div class="col-xl-4 col-md-12 widget-column mt-0">

          <!-- Section: Categories -->
          <section class="section mt-3 mb-5">

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
          <section class="section widget-content mt-5 listing-section">

            <!-- Heading -->
            <h4 class="font-weight-bold"><strong>관련 기사</strong></h4>
            <hr class="red mb-4">

            <div class="single-post">
              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="view overlay">
                      <img src="img/article8.jpg" class="card-img-top">
                      <a href="detail.html"><div class="mask rgba-white-slight"></div></a>
                    </div>
                    <div class="card-body">
                      <h4 class="card-title"><strong><a href="detail.html">[김 셰프의 미식산행] 푸근한 산 아래 충실히 재현된 남도 고향의 맛</a></strong></h4><hr>
                      <p class="card-text">북한산을 바라보는 해발 209m의 높지 않은 산이 있다. 봉산烽山이라는 이 산은 서울시 은평구 구산동과 경기도 고양시 덕양구의 경계를 이루는 산이다. 서울의 북서쪽 마을을 병풍처럼 둘러싸고...</p>
                    </div>
                    <div class="mdb-color lighten-3 text-center">
                      <ul class="list-unstyled list-inline font-small mt-3">
                        <li class="list-inline-item pr-1 white-text">특집기사</li>
                        <li class="list-inline-item pr-1 white-text"><i class="far fa-clock-o pr-1"></i>2021-02-15</li>
                        <li class="list-inline-item pr-1"><a href="detail.html" class="white-text"><i class="far fa-comments pr-1"></i>0</a></li>
                        <li class="list-inline-item pr-1"><a href="detail.html" class="white-text"><i class="far fa-eye pr-1"></i>70</a></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div><br>
            <div class="single-post">
              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="view overlay">
                      <img src="img/article5.jpg" class="card-img-top">
                      <a href="detail.html"><div class="mask rgba-white-slight"></div></a>
                    </div>
                    <div class="card-body">
                      <h4 class="card-title"><strong><a href="detail.html">'준비없는 산행 화 부른다'..올들어 충북 산악사고 15건·14명 구조</a></strong></h4><hr>
                      <p class="card-text text-justify">3일 충북소방본부에 따르면 1월부터 지난달 말까지 도내에서 일어난 산악사고는 15건이다. 구조 인원만 14명에 이른다. 지난해에도 소방헬기 출동 구조 190건 중 43건(22.6%)이 산악구조였다...</p>
                    </div>
                    <div class="mdb-color lighten-3 text-center">
                      <ul class="list-unstyled list-inline font-small mt-3">
                        <li class="list-inline-item pr-1 white-text">뉴스</li>
                        <li class="list-inline-item pr-1 white-text"><i class="far fa-clock-o pr-1"></i>2021-02-19</li>
                        <li class="list-inline-item pr-1"><a href="detail.html" class="white-text"><i class="far fa-comments pr-1"></i>0</a></li>
                        <li class="list-inline-item pr-1"><a href="detail.html" class="white-text"><i class="far fa-eye pr-1"></i>38</a></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <!--/ Section: Featured posts -->

          <!-- Section: Featured posts -->
          <section class="section widget-content mt-5">

            <h4 class="font-weight-bold"><strong>산행 정보</strong></h4>
            <hr class="red mb-4">

            <div class="card card-body pb-5">
              <div class="single-post pb-4 mb-4">
                <h6 class="mt-0 mb-3"><a href="detail.html"><strong>태백산 장군봉 눈꽃</strong></a></h6>
                <div class="row">
                  <div class="col-4">
                    <div class="view overlay">
                      <img width="126" height="84" src="https://giwb.kr/public/uploads/editor/161248371254205.jpg">
                      <a href="detail.html">
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
                <h6 class="mt-0 mb-3"><a href="detail.html"><strong>오대산 비로봉 & 두로령 옛길</strong></a></h6>
                <div class="row">
                  <div class="col-4">
                    <div class="view overlay">
                      <img width="126" height="84" src="https://giwb.kr/public/uploads/editor/161248267682782.jpg">
                      <a href="detail.html">
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
                <h6 class="mt-0 mb-3"><a href="detail.html"><strong>덕유산 향적봉 눈꽃산행</strong></a></h6>
                <div class="row">
                  <div class="col-4">
                    <div class="view overlay">
                      <img width="126" height="84" src="https://giwb.kr/public/uploads/editor/161050582585413.jpg">
                      <a href="detail.html">
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
                <h6 class="mt-0 mb-3"><a href="detail.html"><strong>소백산 비로봉 칼바람 눈꽃산행</strong></a></h6>
                <div class="row">
                  <div class="col-4">
                    <div class="view overlay">
                      <img width="126" height="84" src="https://giwb.kr/public/uploads/editor/161049859367645.jpg">
                      <a href="detail.html">
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
                <h6 class="mt-0 mb-3"><a href="detail.html"><strong>계방산 눈꽃산행</strong></a></h6>
                <div class="row">
                  <div class="col-4">
                    <div class="view overlay">
                      <img width="126" height="84" src="https://giwb.kr/public/uploads/editor/161049470999541.jpg">
                      <a href="detail.html">
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
