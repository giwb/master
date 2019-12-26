<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div id="shop" class="club-main">
        <div class="sub-header">용품판매 - 상세</div>
        <div class="sub-content">
          <div class="row mt-4">
            <div class="col-sm-5 text-center pb-3">
              <img src="<?=$viewItem['item_photo'][0]?>">
            </div>
            <div class="col-sm-7 text-left">
              <div class="item-category"><?=$viewItem['item_category1']['name']?> > <?=$viewItem['item_category2']['name']?></div>
              <h2 class="item-name"><?=$viewItem['item_name']?></h2>
              <h3 class="item-cost"><?=number_format($viewItem['item_cost'])?>원</h3>
              <button type="button" class="btn btn-cart" data-idx="<?=$viewItem['idx']?>">장바구니에 담기</button>
              <a href="<?=base_url()?>club/shop_cart/<?=$view['idx']?>"><button type="button" class="btn btn-secondary">장바구니 보기</button></a>
            </div>
          </div>
          <div class="item-content">
            <h4>상품소개</h4>
            <?=reset_html_escape($viewItem['item_content'])?>
          </div>
        </div>
      </div>
      <script type="text/javascript" src="<?=base_url()?>public/js/shop.js"></script>