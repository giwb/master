<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-8 col-md-12">
          <section class="section extra-margins listing-section">
            <h4 class="font-weight-bold"><strong>산악회 전체보기</strong></h4>
            <hr class="red">
            <div class="row">
              <?php foreach ($list as $value): ?>
              <div class="col-md-6 my-3">
                <div class="card">
                  <div class="view overlay">
                    <img src="<?=$value['thumbnail']?>" class="card-img-top">
                    <a target="_blank" href="<?=base_url()?><?=$value['url']?>"><div class="mask rgba-white-slight"></div></a>
                  </div>
                  <div class="card-body">
                    <h5><strong><a href="detail.html" style="color: #3e4551;"><img src="/public/images/tripkorea/mountain1.png"> <?=$value['title']?></a></strong></h5>
                    <hr>
                    <p class="card-text text-justify">
                      ・지역 : 인천, 부천<br>
                      ・노선 : 계산 - 작전 - 갈산 - 부평구청 - 삼산 - 소풍 - 복사 - 송내남부
                    </p>
                    <hr>
                    <p class="card-text text-justify">
                      인천과 부천 지역을 기반으로 토요산행과 일요산행을 매주 운행하는 안내산악회입니다. 차내 음주가무 없으며 초보자도 함께할 수 있도록 여유롭게 산행을 진행합니다.
                    </p>
                  </div>
                  <div class="mdb-color lighten-3 text-center">
                    <ul class="list-unstyled list-inline font-small mt-3">
                      <li class="list-inline-item pr-1"><a href="detail.html" class="white-text"><i class="far fa-eye pr-1"></i>회원수 2580명</a></li>
                      <li class="list-inline-item pr-1"><a href="detail.html" class="white-text"><i class="far fa-comments pr-1"></i>산행횟수 5230회</a></li>
                      <li class="list-inline-item pr-1"><a href="detail.html" class="white-text"><i class="far fa-calendar-check pr-1"></i>랭킹 1위</a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </section>
        </div>
