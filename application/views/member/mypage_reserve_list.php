<?php foreach ($userReserve as $value): ?>
<dl>
  <dt><input type="checkbox" id="cr<?=$value['idx']?>" name="checkReserve[]" class="check-reserve" value="<?=$value['idx']?>" data-reserve-cost="<?=$value['cost_total']?>" data-payment-cost="<?=$value['real_cost']?>" data-status="<?=$value['status']?>" data-penalty="<?=$value['penalty']?>"><label for="cr<?=$value['idx']?>"></label></dt>
  <dd>
    <?=viewStatus($value['notice_status'])?> <a href="<?=base_url()?>reserve/<?=$view['idx']?>?n=<?=$value['resCode']?>"><?=$value['subject']?></a> - <?=checkDirection($value['seat'], $value['bus'], $value['notice_bustype'], $value['notice_bus'])?>번 좌석<br>
    <small>
      일시 : <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / 
      분담금 : <?=$value['view_cost']?> /
      <?=!empty($value['status']) && $value['status'] == STATUS_ABLE ? '입금완료' : '입금대기'?>
      <?=!empty($value['depositname']) ? ' / 입금자 : ' . $value['depositname'] : ''?>
    </small>
  </dd>
</dl>
<?php endforeach; ?>