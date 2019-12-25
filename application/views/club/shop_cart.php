<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div id="shop" class="club-main">
        <div class="mt-4 d-none d-md-block d-lg-none"></div>
        <div class="sub-header">장바구니</div>
        <div class="sub-content">
          <?php if (empty($listCart)): ?>
          <div class="text-center border-bottom mt-5 pb-5">현재 장바구니에 담긴 상품이 없습니다.</div>
          <?php endif; ?>
          <?php foreach ($listCart as $value): ?>
          <div class="row align-items-center pt-3 d-md-none">
            <div class="col"><strong><?=$value['item_name']?></strong></div>
          </div>
          <div class="row align-items-center border-bottom p-3">
            <div class="col-5 col-md-2 p-0 text-center"><img src="<?=$value['item_photo']?>"></div>
            <div class="col-1 col-md-7 p-0 pl-3"><div class="d-none d-sm-block"><strong><?=$value['item_name']?></strong><br><?=number_format($value['item_cost'])?>원</div></div>
            <div class="col-2 col-md-1 p-0 text-center">
              <select name="amount" class="form-control form-control-sm pl-1 pr-0 cart-amount" data-rowid="<?=$value['rowid']?>">
                <?php foreach (range(1, 10) as $cnt): ?>
                <option<?=$cnt == $value['item_qty'] ? ' selected' : ''?> value='<?=$cnt?>'><?=$cnt?>개</option>
                <?php endforeach; ?>
              </select>
              <button type="button" class="btn btn-sm btn-danger btn-cart-delete mt-1 p-1 w-100" data-rowid="<?=$value['rowid']?>">삭제</button>
            </div>
            <div class="col-3 col-md-2 p-0 text-right item-cost"><?=number_format($value['subtotal'])?>원</div>
          </div>
          <?php endforeach; ?>
          <div class="row border-bottom p-3">
            <div class="col-5 col-md-2 font-weight-bold">합계</div>
            <div class="col-1 col-md-7 p-0"></div>
            <div class="col-2 col-md-1 p-0 text-center"><span class="total-amount"><?=number_format($total_amount)?></span>개</div>
            <div class="col-3 col-md-2 p-0 text-right item-cost"><span class="total-cost"><?=number_format($total_cost)?></span>원</div>
          </div>
          <div class="text-center mt-4">
            <a href="<?=base_url()?>club/shop/<?=$view['idx']?>"><button type="button" class="btn btn-secondary">계속 쇼핑하기</button></a>
            <?php if (!empty($listCart)): ?>
            <button type="button" class="btn btn-primary ml-4">용품 구매하기</button>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <script type="text/javascript" src="<?=base_url()?>public/js/shop.js"></script>