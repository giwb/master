<?php foreach ($listItem as $value): ?>
<div class="row align-items-center mb-3 item-list" data-idx="<?=$value['idx']?>">
  <div class="col-sm-1"><img src="<?=$value['item_photo']?>" class="w-100"></div>
  <div class="col-sm-11">
    ・등록 : <?=date('Y-m-d H:i:s', $value['created_at'])?><br>
    ・분류 : <?php foreach ($value['item_category_name'] as $key => $cname): ?><?=$key != 0 ? ' &gt; ' : ''?><?=$cname?><?php endforeach; ?><br>
    ・품명 : <?=$value['item_name']?><br>
    ・가격 : <?=!empty($value['item_price']) ? '<s class="text-danger">' . number_format($value['item_price']) . '원</s> → ' : ''?><?=number_format($value['item_cost'])?>원
    <br>
  </div>
</div>
<?php endforeach; ?>