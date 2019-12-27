<?php foreach ($listOrder as $value): ?>
<div class="border bg-white">
  <div class="bg-light p-2"><?=!empty($value['status']) && $value['status'] == RESERVE_PAY ? '<strong class="text-primary">[입금완료]</strong>' : '<strong class="text-secondary">[입금대기]</strong>'?> <a href="<?=base_url()?>admin/member_view/<?=$value['created_by']?>"><?=$value['nickname']?>님</a> - <?=date('Y-m-d', $value['created_at'])?> (<?=calcWeek(date('Y-m-d', $value['created_at']))?>) <?=date('H:i', $value['created_at'])?></div>
  <div class="p-3">
    ・구매금액 : <?=number_format($value['totalCost'])?>원 / 사용한 포인트 : <?=number_format($value['point'])?>원<br>
    ・인수산행 : <?php if (!empty($value['startdate'])): ?><?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['mname']?><? else: ?>미지정<?php endif; ?>
    <?php foreach ($value['listCart'] as $key => $item): ?>
    <div class="row align-items-center mt-3">
      <div class="col-sm-1"><img class="w-100" src="<?=base_url() . PHOTO_URL . $item['photo']?>"></div>
      <div class="col-sm-11"><?=$item['name']?><br><small><?=number_format($item['amount'])?>개, <?=number_format($item['cost'] * $item['amount'])?>원</small></div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endforeach; ?>