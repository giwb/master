<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div id="shop" class="club-main">
        <h2 class="sub-header"><?=$pageTitle?></h2>
        <div class="row align-items-center">
          <div class="col-9 mb-2"><a href="javascript:;" class="btn-notice" data-idx="1"><strong><i class="fa fa-question-circle-o" aria-hidden="true"></i> 구매대행 상품이란..?</strong></a></div>
          <div class="col-3 text-right d-none d-sm-block"><a href="<?=BASE_URL?>/shop/cart"><button type="button" class="btn btn-sm btn-cart">장바구니 보기</button></a></div>
        </div>
        <div class="area-notice text-justify small" data-idx="1">
          <div class="border mb-3 p-4">
            본 상품은 경인웰빙이 유통하는 상품이 아니고, 소비자 입장에서 박스로 구입하여 낱개로 전달해 드리는 상품입니다.<br><br>
            박스단위로 택배비 부담이 없이 비교적 저렴하게 구입하여 회원분들께 낱개 단위로 저렴하게 전해 드리는 상품으로 산행시 꼭 필요로 하는 품목을 산행 신청하신 분들을 대상으로 버스에서 전달해 드리는 서비스로 이해해 주시면 감사하겠습니다. <br><br>
            산행 준비를 꼼꼼히 하시는 분들이야 문제 없겠지만, 산행 하는 날마다 편의점에서 비싼 생수를 구입하고, 현지에서 막걸리를 사거나 그마저도 구입할 수 없는 상황에 발을 동동 구르시는 상황을 많이 보아왔던 입장인지라, 가장 기본적인 준비물이지만 미리 준비하기 힘드신 분들을 위해 제가 대신 준비해 드리겠다는 차원입니다.<br><br>
            제공되는 가격은 마트나 온라인 쇼핑몰의 최저가 가격에 10~20% 정도를 붙이거나 낱개 구입 시 발생하는 택배비 정도를 심부름값 정도의 비용을 얹어서 책정되었다는 점도 참고 삼아 미리 말씀 드립니다.<br><br>
            홈페이지 신청은 산행출발 시간 기준 24시간 전까지만 신청이 가능하시구요.. 그 이후로 주문을 원하시는 경우 카톡으로 직접 주문해 주시기 바랍니다. 상황에 따라 미리 준비되지 않은 품목은 전달해 드리지 못할 수 있다는 점도 사전 양해 부탁드립니다.
            <div class="btn-notice text-right mt-2" data-idx="1"><i class="fa fa-chevron-up" aria-hidden="true"></i> 닫기</div>
          </div>
        </div>
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
            <div class="col-1 col-md-7 p-0 pl-3"><div class="d-none d-sm-block"><strong><?=$value['item_name']?></strong><br><?=!empty($value['item_option']) ? $value['item_option'] . ' - ' : ''?><?=number_format($value['item_cost'])?>원</div></div>
            <div class="col-2 col-md-1 p-0 text-center">
              <select name="amount" class="form-control form-control-sm pl-1 pr-0 cart-amount" data-rowid="<?=$value['rowid']?>">
                <?php foreach (range(1, 10) as $cnt): ?>
                <option<?=$cnt == $value['item_qty'] ? ' selected' : ''?> value='<?=$cnt?>'><?=$cnt?>개</option>
                <?php endforeach; ?>
              </select>
              <button type="button" class="btn btn-sm btn-danger btn-cart-delete mt-1 p-1 w-100" data-rowid="<?=$value['rowid']?>">삭제</button>
            </div>
            <div class="col-3 col-md-2 p-0 text-right item-cost">
              <?=!empty($value['subtotal_price']) ? '<s class="text-danger small">' . number_format($value['subtotal_price']) . '원</s><br>' : ''?>
                <?=number_format($value['subtotal_cost'])?>원
            </div>
          </div>
          <?php endforeach; ?>
          <div class="row border-bottom p-3">
            <div class="col-5 col-md-2 font-weight-bold">합계</div>
            <div class="col-1 col-md-7 p-0"></div>
            <div class="col-2 col-md-1 p-0 text-center"><span class="total-amount"><?=number_format($total_amount)?></span>개</div>
            <div class="col-3 col-md-2 p-0 text-right item-cost"><span class="total-cost"><?=number_format($total_cost)?></span>원</div>
          </div>
          <div class="text-center mt-4 mb-5">
            <a href="<?=BASE_URL?>/shop"><button type="button" class="btn btn-secondary">계속 쇼핑하기</button></a>
            <?php if (!empty($listCart)): ?>
            <a href="<?=BASE_URL?>/shop/checkout"><button type="button" class="btn btn-default ml-4">용품 구매진행</button></a>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <script type="text/javascript" src="/public/js/shop.js"></script>