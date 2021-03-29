<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-8 col-md-12">
          <section class="section extra-margins listing-section">
            <div class="row">
              <div class="col-7"><h4 class="font-weight-bold"><strong>여행일정</strong></h4></div>
              <div class="col-5"><!--<div class="row"><div class="col-10"><input type="text" class="form-control form-control-sm"></div><div class="col-2 pl-0 pr-0"><button type="button" class="btn btn-sm btn-default pt-2 pb-2 pl-3 pr-3 m-0">검색</button></div></div>--></div>
            </div><hr class="red mt-2">
            <div class="row">
              <?php foreach ($listNotice as $value): ?>
              <div class="col-md-12 my-3">
                <div class="card">
                  <div class="card-body">
                    <a target="_blank" href="<?=$value['url']?>"><img width="250" class="mr-3" align="left" src="<?=$value['photo']?>"></a>
                    <h5><strong><a target="_blank" href="<?=$value['url']?>">[<?=$value['club_name']?>] <?=$value['subject']?></a></strong></h5>
                    <p class="card-text text-justify">
                      ・일시 : <?=$value['startdate']?> <?=$value['starttime']?><br>
                      ・지역 : <?php foreach ($value['sido'] as $key => $area): if ($key != 0): ?>, <?php endif; ?><?=$area?><?=!empty($value['gugun'][$key]) ? ' ' . $value['gugun'][$key] : ''?><?php endforeach; ?><br>
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
              <?php foreach ($listSchedule as $value): ?>
              <div class="col-md-12 my-3">
                <div class="card">
                  <div class="card-body">
                    <h5><strong><a target="_blank" href="<?=$value['link']?>">[<?=$value['agency_name']?>] <?=$value['title']?></a></strong></h5>
                    <p class="card-text text-justify">
                      ・일시 : <?=$value['startdate']?> <?=$value['starttime']?><br>
                      <?php if (!empty($value['sido'])): ?>
                      ・지역 : <?php foreach ($value['sido'] as $key => $area): if ($key != 0): ?>, <?php endif; ?><?=$area?><?=!empty($value['gugun'][$key]) ? ' ' . $value['gugun'][$key] : ''?><?php endforeach; ?><br>
                      <?php endif; ?>
                      <?=!empty($value['cost']) ? '・요금 : ' . number_format($value['cost']) . '원<br>' : ''?>
                      <?=!empty($value['distance']) ? '・거리 : ' . $value['distance'] : ''?>
                    </p>
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
