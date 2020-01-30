<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <h2 class="d-none d-sm-block border-bottom mb-2 pb-3"><?=$pageTitle?></h2>
        <div class="container">
          <div class="row align-items-center text-center header-menu">
            <div class="col-2 active">진행</div>
            <div class="col-2">종료</div>
            <div class="col-2">취소</div>
            <div class="col-2">신규</div>
            <div class="col-2">계획</div>
            <div class="col-2">복사</div>
          </div>
        </div>
        <?php foreach ($list as $value): ?>
        <div class="border-bottom mt-1 pt-2 pb-2">
          <b><?=viewStatus($value['status'], $value['visible'])?></b> <a href="/admin/main_view_progress/<?=$value['idx']?>"><?=$value['subject']?></a><br>
          <?php if (!empty($value['sido'])): ?>
          <?php foreach ($value['sido'] as $key => $sido): ?><?=$sido?> <?=!empty($value['gugun'][$key]) ? $value['gugun'][$key] : ''?>, <?php endforeach; ?>
          <?php endif; ?>
          <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원 / 예약인원 <?=cntRes($value['idx'])?>명
        </div>
        <?php endforeach; ?>
      </div>
    </div>
