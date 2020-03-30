<?php foreach ($listPurchase as $value): ?>
<dl>
  <dd>
    <div class="border">
      <div class="bg-light p-2"><?php if ($value['status'] != ORDER_END): ?><input type="checkbox" id="cp<?=$value['idx']?>" name="checkPurchase[]" class="check-purchase" value="<?=$value['idx']?>" data-reserve-cost="<?=$value['cost_total']?>" data-payment-cost="<?=$value['cost_total']?>" data-using-point="<?=$value['point']?>" data-status="<?=$value['status']?>" data-deposit-name="<?=$value['deposit_name']?>" data-notice-idx="<?=$value['noticeIdx']?>"><label for="cp<?=$value['idx']?>"></label><?php endif; ?><?=getPurchaseStatus($value['status'])?> 구매일 <?=date('Y-m-d', $value['created_at'])?> (<?=calcWeek(date('Y-m-d', $value['created_at']))?>) <?=date('H:i', $value['created_at'])?></div>
      <div class="pt-2 pb-2 pl-3 pr-3 font-weight-normal">
        ・구매금액 : <?=number_format($value['cost_total'])?>원<?=!empty($value['point']) ? ' / 사용한 포인트 : ' . number_format($value['point']) . '원' : ''?><?=!empty($value['deposit_name']) ? ' / 입금자명 : ' . $value['deposit_name'] : ''?><br>
        ・인수산행 : <?php if (!empty($value['startdate'])): ?><a href="<?=BASE_URL?>/reserve/list/<?=$value['noticeIdx']?>"><?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['mname']?></a><?php else: ?>미지정<?php endif; ?>
        <?php foreach ($value['listCart'] as $item): ?>
        <div class="row align-items-center mt-2">
          <div class="col-3 col-sm-2 pr-1"><img class="w-100" src="<?=PHOTO_URL . $item['photo']?>"></div>
          <div class="col-9 col-sm-10 pl-1"><a href="<?=BASE_URL?>/shop/item/?n=<?=$item['idx']?>"><?=$item['name']?></a><br><small><?=!empty($item['option']) ? $item['option'] . ' - ' : ''?><?=number_format($item['amount'])?>개, <?=number_format($item['cost'] * $item['amount'])?>원</small></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </dd>
</dl>
<?php endforeach; ?>