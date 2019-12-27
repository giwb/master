<?php foreach ($listOrder as $value): ?>
<div class="border-bottom mb-3 pb-3 order-list" data-idx="<?=$value['idx']?>">
  <?=!empty($value['status']) && $value['status'] == RESERVE_PAY ? '<strong class="text-primary">[입금완료]</strong>' : '<strong class="text-secondary">[입금대기]</strong>'?>
  <a href="<?=base_url()?>admin/member_view/<?=$value['created_by']?>"><?=$value['nickname']?></a>님
  <div class="small">
    ・구매상품 : <?php foreach ($value['order_item'] as $key => $item): if ($key != 0) { ?> / <? } ?><?=$item['item_name']?> (<?=$item['amount']?>개) <?php endforeach; ?><br>
    ・구매일시 : <?=date('Y-m-d', $value['created_at'])?> (<?=calcWeek(date('Y-m-d', $value['created_at']))?>) <?=date('H:i', $value['created_at'])?> / 구매금액 : <?=number_format($value['totalCost'])?>원 / 사용한 포인트 <?=number_format($value['point'])?>원<br>
    ・인수산행 : <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <a href="<?=base_url()?>admin/main_view_progress/<?=$value['notice_idx']?>"><?=$value['mname']?></a>
  </div>
</div>
<?php endforeach; ?>