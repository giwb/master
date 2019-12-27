<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div id="shop" class="club-main">
        <div class="mt-4 d-none d-md-block d-lg-none"></div>
        <div class="sub-header">용품판매 - 구매진행</div>
        <div class="sub-content">
          <form id="formCheckout" method="post" action="<?=base_url()?>club/shop_insert">
            <input type="hidden" name="userPoint" value="<?=!empty($userData['point']) ? $userData['point'] : 0?>">
            <input type="hidden" name="totalCost" value="<?=!empty($total_cost) ? $total_cost : 0?>">
            <input type="hidden" name="paymentCost" value="<?=!empty($total_cost) ? $total_cost : 0?>">
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
              <div class="col-2 col-md-1 p-0 text-center"><?=number_format($value['item_qty'])?>개</div>
              <div class="col-3 col-md-2 p-0 text-right item-cost"><?=number_format($value['subtotal'])?>원</div>
            </div>
            <?php endforeach; ?>
            <div class="row border-bottom p-3">
              <div class="col-5 col-md-2 font-weight-bold">합계</div>
              <div class="col-1 col-md-7 p-0"></div>
              <div class="col-2 col-md-1 p-0 text-center"><?=number_format($total_amount)?>개</div>
              <div class="col-3 col-md-2 p-0 text-right item-cost"><span class="totalCost"><?=number_format($total_cost)?></span>원</div>
            </div>
            <div class="row p-3">
              <div class="col-5 col-md-2 font-weight-bold">결제금액</div>
              <div class="col-6 col-md-10 p-0 text-right"><h3 class="item-cost"><span class="paymentCost"><?=number_format($total_cost)?></span>원</h3></div>
            </div>
            <div class="border-bottom font-weight-bold mt-3 pb-1">■ 결제방법</div>
            <div class="row align-items-center border-bottom pt-2 pb-2 pl-3 pr-3">
              <div class="col-4 col-md-3 font-weight-bold">포인트 결제</div>
              <div class="col-6 col-md-4 p-0 pr-2">
                총 <span class="userPoint"><?=number_format($userData['point'])?></span> 포인트 중<br>
                <button type="button" class="btn btn-sm btn-info p-0 pl-3 pr-3 using-point-all">포인트 전액 사용</button>
              </div>
              <div class="col-2 col-md-5 text-right p-0"><input type="text" name="usingPoint" class="form-control pl-1 pr-1 using-point"></div>
            </div>
            <div class="row align-items-center border-bottom pt-2 pb-2 pl-3 pr-3">
              <div class="col-4 col-md-3 font-weight-bold">은행 입금</div>
              <div class="col-5 col-md-4 p-0 pr-2">입금자명 입력</div>
              <div class="col-3 col-md-5 text-right p-0"><input type="text" name="depositName" class="form-control pl-1 pr-1"></div>
            </div>
            <div class="row align-items-center border-bottom pt-2 pb-2 pl-3 pr-3">
              <div class="col-4 col-md-3 font-weight-bold">은행 계좌</div>
              <div class="col-8 col-md-9 p-0 pr-2">국민은행 / 288001-04-154630<br>경인웰빙산악회 (김영미)</div>
            </div>
            <div class="border-bottom font-weight-bold mt-5 pb-1">■ 인수방법</div>
            <div class="row align-items-center border-bottom pt-2 pb-2 pl-3 pr-3">
              <div class="col-4 col-md-3 font-weight-bold">차량 인수</div>
              <div class="col-8 col-md-9">
                <select name="reserveIdx" class="form-control">
                  <option value=''>예약된 산행 보기</option>
                  <option value=''>-------------</option>
                  <?php foreach ($listMemberReserve as $value): ?>
                  <option value='<?=$value['idx']?>'><?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['mname']?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="pt-2 pb-2 pl-3 pr-3 text-danger small">
              ※ 결제방법, 인수방법은 구입 후 마이페이지에서도 지정할 수 있습니다.<br>
              ※ 구입한 용품을 인수하실 예약된 산행을 선택해주세요.<br>
              ※ 현재는 차량 인수만 가능합니다.<br>
            </div>
            <div class="text-center mt-4">
              <a href="<?=base_url()?>club/shop/<?=$view['idx']?>"><button type="button" class="btn btn-secondary">계속 쇼핑하기</button></a>
              <button type="button" class="btn btn-danger ml-4 btn-checkout">구매 완료하기</button>
            </div>
          </form>
        </div>
      </div>
      <script type="text/javascript" src="<?=base_url()?>public/js/shop.js"></script>