<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-8 col-md-12">
          <section class="section extra-margins listing-section">
            <div class="row no-gutters">
              <div class="col-5"><h4 class="font-weight-bold"><strong>여행일정</strong></h4></div>
              <div class="col-7"><form method="get" action="<?=base_url()?>schedule" class="row no-gutters"><div class="col-9 col-sm-11 pr-2"><input type="text" name="keyword" class="form-control form-control-sm" value="<?=$keyword?>"></div><div class="col-3 col-sm-1"><button type="button" class="btn btn-default h-100 pt-1 pb-1 pl-2 pr-2 m-0">검색</button></div></form></div>
            </div><hr class="red mt-2">
            <?php if (!empty($startdate)): ?>
            <h5 class="mt-4"><?=date('Y년 m월 d일', strtotime($startdate))?> 출발 일정</h5>
            <?php endif; ?>
            <div class="row">
              <?php foreach ($listNotice as $value): ?>
              <div class="col-md-12 my-3">
                <div class="card">
                  <div class="card-body row">
                    <?php if (!empty($value['photo'])): ?>
                    <div class="col-sm-3">
                      <a target="_blank" href="<?=$value['url']?>"><img class="w-100" src="<?=$value['photo']?>"></a>
                      <div class="d-block d-sm-none"><div class="mb-3"></div></div>
                    </div>
                    <?php endif; ?>
                    <div class="col-sm-9">
                      <h5><strong><a target="_blank" href="<?=$value['url']?>">[<?=$value['club_name']?>] <?=$value['subject']?></a></strong></h5>
                      <p class="card-text text-justify">
                        ・일시 : <?=$value['startdate']?> <?=$value['starttime']?><br>
                        ・지역 : <?php foreach ($value['sido'] as $key => $area): if ($key != 0): ?>, <?php endif; ?><?=$area?><?=!empty($value['gugun'][$key]) ? ' ' . $value['gugun'][$key] : ''?><?php endforeach; ?><br>
                        ・요금 : <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원<br>
                        ・거리 : <?=$value['kilometer']?>
                      </p>
                    </div>
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
                    <h6><strong><a target="_blank" href="<?=$value['link']?>">[<?=$value['agency_name']?>] <?=$value['title']?></a></strong></h6>
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
