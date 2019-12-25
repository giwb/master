<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div id="shop" class="club-main">
        <div class="sub-header">용품판매</div>
        <div class="sub-content mt-4 p-3">
        <?php foreach ($listItem as $key => $value): ?>
          <?php if ($key == 0): ?><div class="row"><?php elseif ($key%2 == 0): ?></div><div class="row"><?php endif; ?>
          <div class="col-sm-6 text-center mb-4">
            <div class="shop-item" data-idx="<?=$value['idx']?>">
              <div class="item-photo"><img src="<?=$value['item_photo']?>"></div>
              <div class="item-category"><?=$value['item_category1']['name']?> > <?=$value['item_category2']['name']?></div>
              <div class="pt-1 pb-1"><?=$value['item_name']?></div>
              <div class="item-cost"><?=number_format($value['item_cost'])?>원</div>
            </div>
          </div>
        <?php endforeach; ?>
        <?php if (!empty($listItem)): ?></div><?php endif; ?>
        </div>
      </div>

      <script type="text/javascript" src="<?=base_url()?>public/js/shop.js"></script>