<?php foreach ($listItem as $value): ?>
<div class="row align-items-center mb-3" data-idx="<?=$value['idx']?>">
  <div class="col-2 col-sm-2 item-list"><img src="<?=$value['item_photo']?>" class="w-100"></div>
  <div class="col-8 col-sm-9 item-list">
    ・등록 : <?=date('Y-m-d H:i:s', $value['created_at'])?><br>
    ・분류 : <?php foreach ($value['item_category_name'] as $key => $cname): ?><?=$key != 0 ? ' &gt; ' : ''?><?=$cname?><?php endforeach; ?><br>
    ・품명 : <?=$value['item_name']?><br>
    ・가격 : <?=!empty($value['item_price']) ? '<s class="text-danger">' . number_format($value['item_price']) . '원</s> → ' : ''?><?=number_format($value['item_cost'])?>원
    <br>
  </div>
  <div class="col-2 col-sm-1 p-0">
    <?php if ($value['item_visible'] == 'Y'): ?>
    <button type="button" class="btn btn-sm btn-secondary btn-item-visible">숨김</button>
    <?php else: ?>
    <button type="button" class="btn btn-sm btn-default btn-item-visible">공개</button>
    <?php endif; ?>
  </div>
</div>
<?php endforeach; ?>