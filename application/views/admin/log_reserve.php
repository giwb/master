<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">예약기록<?=!empty($keyword) ? ' - ' . $keyword . '님' : ''?></h1>
        </div>
        <form id="formList" method="post" action="<?=base_url()?>admin/log_reserve" class="row align-items-center text-center">
          <input type="hidden" name="p" value="1">
          <input type="hidden" name="k" value="<?=$keyword?>">
        </form>
        <?php foreach ($searchReserve as $value): ?>
          <div class="border-top pt-2 pb-2">
            <?=viewStatus($value['notice_status'])?> <a href="<?=base_url()?>admin/main_view_progress/<?=$value['rescode']?>"><?=$value['subject']?></a> - <?=checkDirection($value['seat'], $value['bus'], $value['notice_bustype'], $value['notice_bus'])?>번 좌석<br>
            <small>
              일시 : <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / 
              분담금 : <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원 /
              <?=!empty($value['status']) && $value['status'] == STATUS_ABLE ? '입금완료' : '입금대기'?>
              <?=!empty($value['depositname']) ? ' / 입금자 : ' . $value['depositname'] : ''?>
            </small>
          </div>
        <?php endforeach; ?>
        <div class="area-append">
        </div>
        <?php if (count($searchReserve) > 1): ?>
        <button class="btn btn-page-next">다음 페이지 보기 ▼</button>
        <?php endif; ?>
      </div>
    </div>
