<?php foreach ($listPurchase as $value): ?>
<div id="order-<?=$value['idx']?>" class="border bg-white mb-4">
  <div class="bg-light p-2">
    <div class="row align-items-center">
      <div class="col-sm-6 pl-0"><span class="area-status"><?=getPurchaseStatus($value['status'])?></span> <a href="<?=BASE_URL?>/admin/member_view/<?=$value['created_by']?>"><?=$value['nickname']?>님</a> - <?=date('Y-m-d', $value['created_at'])?> (<?=calcWeek(date('Y-m-d', $value['created_at']))?>) <?=date('H:i', $value['created_at'])?></div>
      <div class="col-sm-6 text-right btn-area p-0">
        <?php if ($value['status'] != ORDER_CANCEL): ?>
        <?php if ($value['status'] == ORDER_ON): ?><button type="button" class="btn btn-sm btn-primary btn-order-status-modal" data-idx="<?=$value['idx']?>" data-status="<?=ORDER_PAY?>">입금확인</button><?php endif; ?>
        <?php if ($value['status'] != ORDER_END): ?>
        <button type="button" class="btn btn-sm btn-secondary btn-order-status-modal" data-idx="<?=$value['idx']?>" data-status="<?=ORDER_CANCEL?>">구매취소</button>
        <button type="button" class="btn btn-sm btn-danger btn-order-status-modal" data-idx="<?=$value['idx']?>" data-status="<?=ORDER_END?>">판매완료</button>
        <?php else: ?>
        <button type="button" class="btn btn-sm btn-danger">판매완료</button>
        <?php endif; ?>
        <?php else: ?>
        <button type="button" class="btn btn-sm btn-dark btn-order-delete-modal" data-idx="<?=$value['idx']?>">삭제</button>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="p-3 small">
    ・구매금액 : <?=number_format($value['totalCost'])?>원<br>
    ・사용한 포인트 : <?=number_format($value['point'])?> 포인트<br>
    ・결제잔액 : <?=number_format($value['totalCost'] - $value['point'])?>원<br>
    ・입금자명 : <?=!empty($value['deposit_name']) ? $value['deposit_name'] : '-'?><br>
    ・인수산행 : <?php if (!empty($value['startdate'])): ?><?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['mname']?><?php else: ?>-<?php endif; ?>
    <?php foreach ($value['listCart'] as $key => $item): ?>
    <div class="row align-items-center mt-3">
      <div class="col-3 col-sm-2"><img class="w-100" src="<?=PHOTO_URL . $item['photo']?>"></div>
      <div class="col-9 col-sm-10">
        <?=$item['name']?><br>
        <small><?=!empty($item['option']) ? $item['option'] . ' - ' : ''?><?=number_format($item['amount'])?>개, <?=number_format($item['cost'] * $item['amount'])?>원</small>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endforeach; ?>