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
                <div class="post-article">
                  <h2>선정사유</h2>
                  <div class="post-content"><?=nl2br(reset_html_escape($view['reason']))?></div>

                  <h2>내용</h2>
                  <div class="post-content"><?=nl2br(reset_html_escape($view['content']))?></div>

                  <h2>주변</h2>
                  <div class="post-content"><?=nl2br(reset_html_escape($view['around']))?></div>

                  <h2>코스</h2>
                  <div class="post-content"><?=nl2br(reset_html_escape($view['course']))?></div>
                </div><!--
                <hr>
                <div class="row">
                  <div class="col-md-6 mt-4">
                    <h5 class="font-weight-bold dark-grey-text">
                      <i class="far fa-lg fa-newspaper mr-3 dark-grey-text"></i>
                      <strong>147</strong> Views
                    </h5>
                  </div>
                  <div class="col-md-6 mt-2 d-flex justify-content-end">
                    <a type="button" class="btn-floating btn-small btn-fb waves-effect waves-light">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a type="button" class="btn-floating btn-small btn-tw waves-effect waves-light">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a type="button" class="btn-floating btn-small btn-gplus waves-effect waves-light">
                        <i class="fab fa-google-plus-g"></i>
                    </a>
                    <a type="button" class="btn-floating btn-small btn-ins waves-effect waves-light">
                        <i class="fab fa-instagram"></i>
                    </a>
                  </div>
                </div>-->
              </div>
            </div>

<!--
            <section class="text-left mb-3">
              <div class="card card-body">
                <div class="row">
                  <div class="col-12 col-sm-2 mb-md-0 mb-3 section-avatar">
                    <img src="img/can.jpg" class="img-fluid rounded-circle icon-avatar" alt="">
                  </div>
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
            -->
          </div>
        </div>
