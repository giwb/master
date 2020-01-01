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
                <?php if (count($viewItem['item_option_cost']) == 1 && empty($viewItem['item_option'][0])): ?>
                <span class="item-cost"><?=number_format($viewItem['item_option_cost'][0])?>원</span>
                <input type="hidden" value="0" class="item-option" data-cost="<?=$viewItem['item_option_cost'][0]?>">
                <?php else: ?>
                <select name="item_option" class="form-control item-option">
                  <option value="">옵션을 선택해주세요</option>
                  <?php foreach ($viewItem['item_option_cost'] as $key => $cost): ?>
                  <option value="<?=$key?>" data-cost="<?=$cost?>"><?=!empty($viewItem['item_option'][$key]) ? $viewItem['item_option'][$key] . ' - ' : ''?><?=number_format($cost)?>원</option>
                  <?php endforeach; ?>
                </select>
                <?php endif; ?>
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