<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-8 col-md-12">
          <section class="section extra-margins listing-section">
            <div class="row">
              <div class="col-7"><h4 class="font-weight-bold"><strong>최신 산행</strong></h4></div>
              <div class="col-5"><!--<div class="row"><div class="col-10"><input type="text" class="form-control form-control-sm"></div><div class="col-2 pl-0 pr-0"><button type="button" class="btn btn-sm btn-default pt-2 pb-2 pl-3 pr-3 m-0">검색</button></div></div>--></div>
            </div><hr class="red mt-2">
            <div class="row">
              <?php foreach ($listNotice as $value): ?>
              <div class="col-md-12 my-3">
                <div class="card">
                  <div class="card-body">
                    <img width="250" class="mr-3" align="left" src="<?=PHOTO_URL . $value['photo']?>">
                    <h5><strong><a href="detail.html">[<?=$value['club_name']?>] <?=$value['subject']?></a></strong></h5>
                    <p class="card-text text-justify">
                      ・일시 : <?=$value['startdate']?> <?=$value['starttime']?><br>
                      ・지역 : <?php foreach ($value['sido'] as $key => $area): if ($key != 0): ?>, <?php endif; ?><?=$area?><?=!empty($area['gugun'][$key]) ? $area['gugun'][$key] : ''?><?php endforeach; ?><br>
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
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </section>
          <!--
          <div class="text-center mb-5">
            <a href="javascript:;" class="btn btn-info" rel="nofollow">더 보기</a>
          </div>-->
        </div>
