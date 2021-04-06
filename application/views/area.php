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
                  <div class="card-body">
                    <h5 class="row no-gutters align-items-center font-weight-bold area-link" data-link="<?=!empty($value['domain']) ? 'http://' . $value['domain'] : !empty($value['url']) ? base_url() . $value['url'] : ''?>" data-target="new">
                      <div class="col-2 col-sm-1"><img class="w-100" src="<?=$value['thumbnail']?>"></div>
                      <div class="col-10 col-sm-11 pl-2"><?=$value['title']?></div>
                    </h5>
                    <hr>
                    <p class="card-text text-justify">
                      <?php if (!empty($value['establish'])): ?>・설립년도 : <?=$value['establish']?>년<br><?php endif; ?>
                      <?php if (!empty($value['sido'])): ?>・활동지역 : <?php foreach ($value['sido'] as $key => $area): if ($key != 0): ?>, <?php endif; ?><?=$area?> <?=!empty($value['gugun'][$key]) ? $value['gugun'][$key] : ''?><?php endforeach; ?><br><?php endif; ?>
                      <?php if (!empty($value['phone'])): ?>・연락처 : <?=$value['phone']?><?php endif; ?>
                    </p>
                    <hr>
                    <p class="card-text text-justify">
                      인천과 부천 지역을 기반으로 토요산행과 일요산행을 매주 운행하는 안내산악회입니다. 차내 음주가무 없으며 초보자도 함께할 수 있도록 여유롭게 산행을 진행합니다.
                    </p>
                  </div>
                  <div class="mdb-color lighten-3 text-center">
                    <ul class="list-unstyled list-inline font-small mt-3">
                      <li class="list-inline-item pr-1"><a href="detail.html" class="white-text"><i class="fas fa-users pr-1"></i> 회원수 <?=number_format($value['cntMember'])?>명</a></li>
                      <li class="list-inline-item pr-1"><a href="detail.html" class="white-text"><i class="fas fa-map-marker-alt"></i> 산행횟수 <?=number_format($value['cntNotice'])?>회</a></li>
                      <!--<li class="list-inline-item pr-1"><a href="detail.html" class="white-text"><i class="far fa-calendar-check pr-1"></i>랭킹 1위</a></li>-->
                    </ul>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </section>
        </div>
