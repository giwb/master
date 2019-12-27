<?php foreach ($listItem as $value): ?>
<div class="row align-items-center mb-3 item-list" data-idx="<?=$value['idx']?>">
  <div class="col-sm-1"><img src="<?=$value['item_photo']?>" class="w-100"></div>
  <div class="col-sm-11">
    ・등록 : <?=date('Y-m-d H:i:s', $value['created_at'])?><br>
    ・분류 : <?php foreach ($value['item_category_name'] as $key => $cname): if ($key != 0) { echo ' > '; } ?><?=$cname?><? endforeach; ?><br>
    ・품명 : <?=$value['item_name']?><br>
    ・가격<?php if (count($value['item_option_cost']) == 1 && empty($value['item_option'][0])): ?> : <?=number_format($value['item_option_cost'][0])?>원<?php else: ?><br><?php foreach ($value['item_option_cost'] as $key => $cost): ?><div class="pl-3"><?=!empty($value['item_option'][$key]) ? $value['item_option'][$key] . ' - ' : ''?><?=number_format($cost)?>원</div><?php endforeach; endif; ?>
    <br>
  </div>
</div>
<?php endforeach; ?>