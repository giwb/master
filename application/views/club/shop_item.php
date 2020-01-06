<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div id="shop" class="club-main">
        <div class="sub-header">용품판매 - 상세</div>
        <div class="sub-content">
          <div class="row mt-4">
            <div class="col-sm-5 text-center pb-3">
              <img src="<?=$viewItem['item_photo'][0]?>">
            </div>
            <div class="col-sm-7 text-left">
              <div class="item-category"><?php if (!empty($viewItem['item_category_name'])): foreach ($viewItem['item_category_name'] as $key => $cname): if ($key != 0) { echo ' > '; } ?><?=$cname?><?php endforeach; endif; ?></div>
              <h2 class="item-name"><?=$viewItem['item_name']?></h2>
              <h4 class="mt-4 mb-4">
                <?=!empty($viewItem['item_price']) ? '<s class="text-danger small"><span class="item-price">' . number_format($viewItem['item_price']) . '</span>원</s><br>' : ''?><span class="item-cost"><?=number_format($viewItem['item_cost'])?></span>원<br>
                <select name="item_option" class="form-control item-option mt-3">
                  <option value="">옵션을 선택해주세요</option>
                  <option value="">---------------</option>
                  <?php foreach ($viewItem['item_options'] as $key => $value): ?>
                  <option value="<?=$key?>" data-added-price="<?=$value['added_price']?>" data-added-cost="<?=$value['added_cost']?>"><?=$value['item_option']?></option>
                  <?php endforeach; ?>
                </select>
              </h4>
              <button type="button" class="btn btn-sm btn-cart" data-type="cart" data-idx="<?=$viewItem['idx']?>">장바구니에 담기</button>
              <button type="button" class="btn btn-sm btn-cart btn-buy ml-2" data-type="buy" data-idx="<?=$viewItem['idx']?>">바로 구매하기 &gt;</button>
            </div>
          </div>
          <div class="item-content">
            <h4>상품소개</h4>
            <?=reset_html_escape($viewItem['item_content'])?>
          </div>
        </div>
      </div>
      <script type="text/javascript" src="<?=base_url()?>public/js/shop.js"></script>