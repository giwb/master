<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">진행중 산행 정산 관리</h1>
        </div>
      </div>
    </div>

    <div class="sub-contents">
      <h2><b><?=viewStatus($view['status'])?></b> <?=$view['subject']?></h2>
      산행일시 : <?=$view['startdate']?> (<?=calcWeek($view['startdate'])?>) <?=$view['starttime']?><br>
      참가비용 : <?=number_format($view['cost_total'] == 0 ? $view['cost'] : $view['cost_total'])?>원 (<?=calcTerm($view['startdate'], $view['starttime'], $view['enddate'], $view['schedule'])?>)<br>
      예약인원 : <?=cntRes($view['idx'])?>명<br>

      <div class="area-reservation">
        <div class="border-top border-bottom mt-4 pt-3 pb-3 row align-items-center">
          <div class="col-sm-9 area-btn">
            <a href="/admin_old/main_entry/<?=$view['idx']?>"><button type="button" class="btn btn-primary">수정</button></a>
            <a href="/admin_old/main_notice/<?=$view['idx']?>"><button type="button" class="btn btn-primary">공지</button></a>
            <a href="/admin_old/main_view_progress/<?=$view['idx']?>"><button type="button" class="btn btn-primary">예약</button></a>
            <a href="/admin_old/main_view_boarding/<?=$view['idx']?>"><button type="button" class="btn btn-primary">승차</button></a>
            <a href="/admin_old/main_view_sms/<?=$view['idx']?>"><button type="button" class="btn btn-primary">문자</button></a>
            <a href="/admin_old/main_view_adjust/<?=$view['idx']?>"><button type="button" class="btn btn-secondary">정산</button></a>
          </div>
          <div class="col-sm-3">
            <select name="status" class="form-control change-status">
              <option value="">산행 상태</option>
              <option value="">------------</option>
              <option<?=$view['status'] == STATUS_PLAN ? ' selected' : ''?> value="<?=STATUS_PLAN?>">계획</option>
              <option<?=$view['status'] == STATUS_ABLE ? ' selected' : ''?> value="<?=STATUS_ABLE?>">예정</option>
              <option<?=$view['status'] == STATUS_CONFIRM ? ' selected' : ''?> value="<?=STATUS_CONFIRM?>">확정</option>
              <option<?=$view['status'] == STATUS_CANCEL ? ' selected' : ''?> value="<?=STATUS_CANCEL?>">취소</option>
              <option<?=$view['status'] == STATUS_CLOSED ? ' selected' : ''?> value="<?=STATUS_CLOSED?>">종료</option>
            </select>
          </div>
        </div>
      </div>
    </div>
