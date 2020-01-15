<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div id="shop" class="club-main">
        <div class="mb-3"><h3>쇼핑몰</h3></div>
        <div class="row-category row m-0 p-0 border-bottom border-right">
          <a href="<?=base_url()?>club/shop/<?=$clubIdx?>" class="col border-left pt-2 pb-2 pl-0 pr-0 small text-center<?=empty($search['item_category1']) ? ' active' : ''?>">전체</a>
          <?php foreach ($listCategory as $value): ?>
          <a href="<?=base_url()?>club/shop/<?=$clubIdx?>?c=<?=$value['idx']?>" class="col border-left pt-2 pb-2 pl-0 pr-0 small text-center<?=$value['idx'] == $search['item_category1'] ? ' active' : ''?>"><?=$value['name']?></a>
          <?php endforeach; ?>
        </div>
        <div class="sub-content mt-2">
          <div class="row mt-4">
            <div class="col-sm-5 text-center pb-3">
              <img src="<?=$viewItem['item_photo'][0]?>">
            </div>
            <div class="col-sm-7 text-left">
              <div class="item-category"><?php if (!empty($viewItem['item_category_name'])): foreach ($viewItem['item_category_name'] as $key => $cname): if ($key != 0) { echo ' > '; } ?><?=$cname?><?php endforeach; endif; ?></div>
              <h2 class="item-name"><?=$viewItem['item_name']?></h2>
              <h4 class="mt-4 mb-4 area-cost">
                <?=!empty($viewItem['item_price']) ? '<span class="discount"><s class="text-secondary"><span class="item-price">' . number_format($viewItem['item_price']) . '</span>원</s> (' . round(($viewItem['item_price'] - $viewItem['item_cost']) / $viewItem['item_price'] * 100) . '%)</span><br>' : ''?><span class="item-cost"><?=number_format($viewItem['item_cost'])?></span>원<br>
                <?php if (!empty($viewItem['item_options'])): ?>
                <select name="item_option" class="form-control item-option mt-3">
                  <option value="">옵션을 선택해주세요</option>
                  <option value="">---------------</option>
                  <?php foreach ($viewItem['item_options'] as $key => $value): ?>
                  <option value="<?=$key?>" data-added-price="<?=!empty($value['added_price']) ? $value['added_price'] : $viewItem['item_price']?>" data-added-cost="<?=!empty($value['added_cost']) ? $value['added_cost'] : $viewItem['item_cost']?>"><?=$value['item_option']?><?=!empty($value['added_cost']) ? ' : ' . number_format($value['added_cost']) . '원' : ''?></option>
                  <?php endforeach; ?>
                </select>
                <?php endif; ?>
              </h4>
              <div class="row">
                <div class="col-3">
                  <select name="amount" class="form-control form-control-sm pl-1 pr-0">
                    <?php foreach (range(1, 10) as $cnt): ?>
                    <option value='<?=$cnt?>'><?=$cnt?>개</option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-4 pl-0 pr-0"><button type="button" class="btn btn-sm btn-cart" data-type="cart" data-idx="<?=$viewItem['idx']?>">장바구니에 담기</button></div>
                <div class="col-5 pl-0 pr-0"><button type="button" class="btn btn-sm btn-cart btn-buy ml-2" data-type="buy" data-idx="<?=$viewItem['idx']?>">바로 구매하기 &gt;</button></div>
              </div>
            </div>
          </div>
          <div class="item-content">
            <h4>상품소개</h4>
            <?=reset_html_escape($viewItem['item_content'])?>
          </div>
        </div>
      </div>
      <script type="text/javascript" src="<?=base_url()?>public/js/shop.js"></script>