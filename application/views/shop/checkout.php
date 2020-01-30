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
          <form id="formCheckout" method="post" action="/shop/insert">
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
              <div class="col-1 col-md-7 p-0 pl-3"><div class="d-none d-sm-block"><strong><?=$value['item_name']?></strong><br><?=!empty($value['item_option']) ? $value['item_option'] . ' - ' : ''?><?=number_format($value['item_cost'])?>원</div></div>
              <div class="col-2 col-md-1 p-0 text-center"><?=number_format($value['item_qty'])?>개</div>
              <div class="col-3 col-md-2 p-0 text-right item-cost">
                <?=!empty($value['subtotal_price']) ? '<s class="text-danger small">' . number_format($value['subtotal_price']) . '원</s><br>' : ''?>
                <?=number_format($value['subtotal_cost'])?>원
              </div>
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
            <div class="text-center mt-4 mb-5">
              <a href="<?=BASE_URL?>/shop"><button type="button" class="btn btn-secondary">계속 쇼핑하기</button></a>
              <button type="button" class="btn btn-danger ml-4 btn-checkout">구매 완료하기</button>
            </div>
          </form>
        </div>
      </div>
      <script type="text/javascript" src="/public/js/shop.js"></script>
