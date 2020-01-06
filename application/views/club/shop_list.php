<?php foreach ($listItem as $key => $value): ?>
  <?php if ($key == 0): ?><div class="row d-flex justify-content-between"><?php elseif ($key%3 == 0): ?></div><div class="row d-flex justify-content-between"><?php endif; ?>
  <div class="col-sm-4 text-center mb-4 pl-2 pr-2">
    <div class="shop-item p-3 h-100" data-idx="<?=$value['idx']?>">
      <div class="item-photo"><img src="<?=$value['item_photo']?>"></div>
      <div class="item-category"><?php if (!empty($value['item_category_name'])): foreach ($value['item_category_name'] as $cnt => $cname): ?><?=$cnt != 0 ? ' &gt; ' : ''?><?=$cname?><?php endforeach; endif; ?></div>
      <div class="pt-1 pb-1"><?=$value['item_name']?></div>
      <?=!empty($value['item_price']) ? '<s class="text-danger">' . number_format($value['item_price']) . '원</s> → ' : ''?><?=number_format($value['item_cost'])?>원
    </div>
  </div>
<?php endforeach; ?>
<?php if (!empty($listItem)): ?></div><?php endif; ?>
