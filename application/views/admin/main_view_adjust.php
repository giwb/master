<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div id="content" class="mb-5">
          <div class="row align-items-center border-bottom text-center mb-4 pt-3 pb-3">
            <div class="col pt-0 pr-1 pb-0 pl-1"><a href="<?=BASE_URL?>/admin/main_entry/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-default w-100">수정</button></a></div>
            <div class="col pt-0 pr-1 pb-0 pl-1"><a href="<?=BASE_URL?>/admin/main_notice/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-default w-100">공지</button></a></div>
            <div class="col pt-0 pr-1 pb-0 pl-1"><a href="<?=BASE_URL?>/admin/main_view_progress/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-default w-100">예약</button></a></div>
            <div class="col pt-0 pr-1 pb-0 pl-1"><a href="<?=BASE_URL?>/admin/main_view_boarding/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-default w-100">승차</button></a></div>
            <div class="col pt-0 pr-1 pb-0 pl-1"><a href="<?=BASE_URL?>/admin/main_view_sms/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-default w-100">문자</button></a></div>
            <div class="col pt-0 pr-1 pb-0 pl-1"><a href="<?=BASE_URL?>/admin/main_view_adjust/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-secondary w-100">정산</button></a></div>
          </div>
          <div class="sub-contents">
            <h2><b><?=viewStatus($view['status'])?></b> <?=$view['subject']?></h2>
            산행일시 : <?=$view['startdate']?> (<?=calcWeek($view['startdate'])?>) <?=$view['starttime']?><br>
            참가비용 : <?=number_format($view['cost_total'] == 0 ? $view['cost'] : $view['cost_total'])?>원 (<?=calcTerm($view['startdate'], $view['starttime'], $view['enddate'], $view['schedule'])?>)<br>
            예약인원 : <?=cntRes($view['idx'])?>명<br>
          </div>
        </div>
