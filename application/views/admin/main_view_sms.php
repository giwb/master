<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">진행중 산행 문자 양식</h1>
        </div>
      </div>
    </div>

    <div class="sub-contents">
      <h2><b><?=viewStatus($view['status'])?></b> <?=$view['subject']?></h2>
      산행분담금 : <?=number_format($view['cost_total'] == 0 ? $view['cost'] : $view['cost_total'])?>원 (<?=calcTerm($view['startdate'], $view['starttime'], $view['enddate'], $view['schedule'])?>)<br>
      산행일시 : <?=$view['startdate']?> (<?=calcWeek($view['startdate'])?>) <?=$view['starttime']?><br>
      예약인원 : <?=cntRes($view['idx'])?>명<br>

      <div class="area-reservation">
        <div class="area-btn">
          <div class="float-left">
            <a href="<?=base_url()?>admin/main_view_progress/<?=$view['idx']?>"><button type="button" class="btn btn-primary">예약관리</button></a>
            <a href="<?=base_url()?>admin/main_view_boarding/<?=$view['idx']?>"><button type="button" class="btn btn-primary">승차관리</button></a>
            <a href="<?=base_url()?>admin/main_view_sms/<?=$view['idx']?>"><button type="button" class="btn btn-secondary">문자양식</button></a>
            <a href="<?=base_url()?>admin/main_view_adjust/<?=$view['idx']?>"><button type="button" class="btn btn-primary">정산관리</button></a>
          </div>
        </div><br>

        <?php foreach ($list as $value): ?>
          <?=$value['date']?> (<?=$value['week']?>요<?=$value['dist']?>)<br>
          <?=$value['subject']?><br>
          <?=$value['nickname']?>님<br>
          <?php if ($view['busTotal'] > 1): ?><?=$value['bus']?>번차<?php endif; ?>
          <?=$value['seat']?>번 좌석<br>
          <?=$value['time']?> <?=$value['title']?><br>
          경인웰빙산악회<br><br>
        <?php endforeach; ?>
      </div>
    </div>
