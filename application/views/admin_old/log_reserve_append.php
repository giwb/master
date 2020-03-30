<?php foreach ($listReserve as $value): ?>
  <div class="border-top pt-2 pb-2">
    <strong><?=viewStatus($value['notice_status'])?></strong> <a href="/admin_old/main_view_progress/<?=$value['rescode']?>"><?=$value['subject']?></a> - <a href="<?=!empty($value['member_idx']) ? '/admin_old/member_view/' . $value['member_idx'] : 'javascript:;'?>"><strong class="text-secondary"><?=$value['nickname']?>님</strong></a><br>
    <small>
      일시 : <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / 
      요금 : <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원 /
      <?=checkDirection($value['seat'], $value['bus'], $value['notice_bustype'], $value['notice_bus'])?>번 좌석 / 
      <?=!empty($value['status']) && $value['status'] == STATUS_ABLE ? '입금완료' : '입금대기'?>
      <?=!empty($value['depositname']) ? ' / 입금자 : ' . $value['depositname'] : ''?>
    </small>
  </div>
<?php endforeach; ?>