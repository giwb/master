<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main id="club">
    <div class="container-fluid club-main">
      <div class="row mt-1">
        <div class="col-xl-8 col-md-12">
          <section class="section extra-margins listing-section">
            <h4 class="row font-weight-bold">
              <div class="col-6"><strong>다음 산행</strong></div>
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
         </div>