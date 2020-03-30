<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div id="shop" class="club-main">
        <h2 class="sub-header"><?=$pageTitle?></h2>
        <div class="sub-content">
          <div class="m-3 p-5 text-center">
            <h2 class="text-info">구매해 주셔서<br class="d-block d-sm-none"> 감사합니다!</h2>
          </div>
          <h4 class="border-bottom m-0 pb-3">■ 구매한 상품</h4>
          <?php $totalAmount = 0; $totalCost = 0; foreach ($listCart as $value): $totalAmount += $value['amount']; $totalCost += $value['cost'] * $value['amount']; ?>
          <div class="row align-items-center pt-3 d-md-none">
            <div class="col"><strong><?=$value['name']?></strong></div>
          </div>
          <div class="row align-items-center border-bottom p-3">
            <div class="col-5 col-md-2 p-0 text-center"><img src="<?=PHOTO_URL . $value['photo']?>"></div>
            <div class="col-1 col-md-7 p-0 pl-3"><div class="d-none d-sm-block"><strong><?=$value['name']?></strong><br><?=!empty($value['option']) ? $value['option'] . ' - ' : ''?><?=number_format($value['cost'])?>원</div></div>
            <div class="col-2 col-md-1 p-0 text-center"><?=number_format($value['amount'])?>개</div>
            <div class="col-3 col-md-2 p-0 text-right item-cost"><?=number_format($value['cost'] * $value['amount'])?>원</div>
          </div>
          <?php endforeach; ?>
          <div class="row border-bottom p-3">
            <div class="col-5 col-md-2 font-weight-bold">합계</div>
            <div class="col-1 col-md-7 p-0"></div>
            <div class="col-2 col-md-1 p-0 text-center"><?=number_format($totalAmount)?>개</div>
            <div class="col-3 col-md-2 p-0 text-right item-cost"><span class="totalCost"><?=number_format($totalCost)?></span>원</div>
          </div>
          <div class="row p-3">
            <div class="col-5 col-md-2 font-weight-bold">결제금액</div>
            <div class="col-6 col-md-10 p-0 text-right"><h3 class="item-cost"><span class="paymentCost"><?=number_format($totalCost - $viewPurchase['point'])?></span>원</h3></div>
          </div>
          <div class="border-bottom font-weight-bold mt-1 pb-1">■ 결제방법</div>
          <?php if (!empty($viewPurchase['point'])): ?>
          <div class="row align-items-center border-bottom pt-2 pb-2 pl-3 pr-3">
            <div class="col-4 col-md-3 font-weight-bold">포인트 결제</div>
            <div class="col-8 col-md-9 text-right p-0 item-cost"><?=number_format($viewPurchase['point'])?>원</div>
          </div>
          <?php elseif (!empty($viewPurchase['depositName'])): ?>
          <div class="row align-items-center border-bottom pt-2 pb-2 pl-3 pr-3">
            <div class="col-4 col-md-3 font-weight-bold">은행 입금</div>
            <div class="col-5 col-md-4 p-0 pr-2">입금자명 입력</div>
            <div class="col-3 col-md-5 text-right p-0"><input type="text" name="depositName" class="form-control pl-1 pr-1"></div>
          </div>
          <div class="row align-items-center border-bottom pt-2 pb-2 pl-3 pr-3">
            <div class="col-4 col-md-3 font-weight-bold">은행 계좌</div>
            <div class="col-8 col-md-9 p-0 pr-2">국민은행 / 288001-04-154630<br>경인웰빙산악회 (김영미)</div>
          </div>
          <?php else: ?>
          <div class="border-bottom pt-2 pb-2 pl-3 pr-3 text-center">
            현재 결제방법이 정해지지 않았습니다. 마이페이지에서 결제하실 수 있습니다.
          </div>
          <?php endif; ?>
          <div class="border-bottom font-weight-bold mt-4 pb-1">■ 인수방법</div>
          <?php if (!empty($viewNotice)): ?>
          <div class="row align-items-center border-bottom pt-2 pb-2 pl-3 pr-3">
            <div class="col-4 col-md-3 font-weight-bold">차량 인수</div>
            <div class="col-8 col-md-9 text-right"><?=$viewNotice['startdate']?> (<?=calcWeek($viewNotice['startdate'])?>) <?=$viewNotice['mname']?></div>
          </div>
          <?php else: ?>
          <div class="border-bottom pt-2 pb-2 pl-3 pr-3 text-center">
            현재 인수방법이 정해지지 않았습니다. 마이페이지에서 선택하실 수 있습니다.
          </div>
          <?php endif; ?>
          <div class="text-center mb-5 pt-4">
            <a href="<?=BASE_URL?>"><button type="button" class="btn btn-default">메인 화면으로</button></a>
            <a href="<?=BASE_URL?>/shop"><button type="button" class="btn btn-secondary ml-3">계속 쇼핑하기</button></a>
          </div>
        </div>
      </div>
      <script type="text/javascript" src="/public/js/shop.js"></script>