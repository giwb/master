<?php foreach ($listItem as $key => $value): ?>
  <?php if ($key == 0): ?><div class="row"><?php elseif ($key%2 == 0): ?></div><div class="row"><?php endif; ?>
  <div class="col-sm-6 text-center mb-4">
    <div class="shop-item" data-idx="<?=$value['idx']?>">
      <div class="item-photo"><img src="<?=$value['item_photo']?>"></div>
      <div class="item-category"><?php if (!empty($value['item_category_name'])): foreach ($value['item_category_name'] as $key => $cname): if ($key != 0) { echo ' > '; } ?><?=$cname?><? endforeach; endif; ?></div>
      <div class="pt-1 pb-1"><?=$value['item_name']?></div>
      <div class="item-cost">
        <?php if (count($value['item_option_cost']) == 1 && empty($value['item_option'][0])): ?><?=number_format($value['item_option_cost'][0])?>원
        <?php else: ?><?=number_format($value['item_option_cost'][0])?>원 ∼<?php endif; ?>
      </div>
    </div>
  </div>
<?php endforeach; ?>
<?php if (!empty($listItem)): ?></div><?php endif; ?>