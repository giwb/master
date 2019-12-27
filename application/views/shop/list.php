<?php foreach ($listItem as $value): ?>
<div class="row align-items-center mb-3 item-list" data-idx="<?=$value['idx']?>">
  <div class="col-sm-1"><img src="<?=$value['item_photo']?>" class="w-100"></div>
  <div class="col-sm-11">
    ・번호 : <?=$value['idx']?><br>
    ・품명 : <?=$value['item_name']?><br>
    ・가격 : <?=$value['item_cost']?><br>
    ・등록 : <?=date('Y-m-d H:i:s', $value['created_at'])?><br>
  </div>
</div>
<?php endforeach; ?>