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