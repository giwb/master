<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main id="club">
    <div class="container-fluid club-main">
      <div class="row">
        <div class="col-xl-8 col-md-12">
          <div class="row mb-4">
            <div class="col-12">
              <h4 class="font-weight-bold">다음 산행 일정</h4>
              <hr class="text-default">
            </div>
            <?php foreach ($listNotice as $value): ?>
            <div class="col-md-6 my-3">
              <div class="card main-card">
                <?php if (!empty($value['subject'])): ?>
                <div class="view overlay">
                  <img src="<?=$value['photo']?>" class="card-img-top">
                  <a href="<?=BASE_URL?>/reserve/list/<?=$value['idx']?>"><div class="mask rgba-white-slight"></div></a>
                </div>
                <div class="card-body">
                  <h4 class="card-title"><div class="card-text mb-1"><?php foreach ($value['sido'] as $key => $area): if ($key != 0): ?>, <?php endif; ?><?=$area?> <?=!empty($area['gugun'][$key]) ? $area['gugun'][$key] : ''?><?php endforeach; ?></div><strong><a><?=$value['subject']?></a></strong></h4><hr>
                  <p class="card-text text-justify">
                    ・일시 : <?=$value['startdate']?> <?=$value['starttime']?><br>
                    ・요금 : <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원<br>
                    ・거리 : <?=$value['kilometer']?>
                  </p>
                </div>
                <div class="mdb-color lighten-3 text-center">
                  <ul class="list-unstyled list-inline font-small mt-3">
                    <li class="list-inline-item pr-1"><a class="white-text"><i class="far fa-eye pr-1"></i>조회 <?=$value['refer']?></a></li>
                    <li class="list-inline-item pr-1"><a class="white-text"><i class="far fa-comments pr-1"></i>댓글 <?=$value['reply_cnt']?></a></li>
                    <li class="list-inline-item pr-1"><a class="white-text"><i class="far fa-calendar-check pr-1"></i>예약 <?=cntRes($value['idx'])?></a></li>
                  </ul>
                </div>
                <?php endif; ?>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
