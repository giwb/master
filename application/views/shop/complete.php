<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <script type="text/javascript" src="/public/js/shop.js"></script>
  <main id="shop" class="shop-main">
    <div class="container-fluid">
      <div class="row mt-1 mb-5">
        <div class="col-xl-8 col-md-12">
          <div class="row align-items-center">
            <div class="col-6">
              <table><tr valign="bottom"><td class="m-0 p-0"><h4 class="font-weight-bold"><?=$pageTitle?></h4></td><td valign="top" class="m-0 p-0"><small><a href="javascript:;" class="btn-notice" data-idx="1"><i class="far fa-question-circle mt-0 ml-3"></i></a></small></td></tr></table>
            </div>
            <div class="col-6 text-right">
              <a href="<?=BASE_URL?>/shop/cart" class="btn-custom btn-giwbred btn-cart">장바구니 보기</a>
            </div>
          </div>
          <hr class="text-default mt-2">

          <div class="area-notice text-justify small" data-idx="1">
            <div class="border mb-3 p-4">
              용품샵이란, 온라인 최저가 상품을 박스단위로 구입하여 산행시 필요로 하는 분들께 택배비 부담없이 낱개로 전달해 드리는 상품입니다.<br><br>
              산행 준비를 꼼꼼히 하시는 분들이야 문제 없겠지만, 산행하는 날마다 편의점에서 생수나 여행용티슈를 비싸게 구입하시는  경우도 많다보니, 가장 기본적인 준비물이지만 미리 준비하기 힘드신 분들께 제공해드리는 편의 서비스라고 이해하시면 좋을것 같습니다.<br><br>
              아울러 등산용품 중 추천할만한 용품을 비교적 저렴한 가격으로 손쉽게 구입하실 수 있도록 검증된 제품만 선별해서 올려드릴 예정이니 많은 관심 부탁드립니다.<br><br>
              신청은 해당 산행의 출발 1일전 18시에 마감할 예정이구요.. 기타 문의사항은 댓글이나 카톡을 이용해 주세요~
              <div class="btn-notice text-right mt-2" data-idx="1"><i class="fa fa-chevron-up" aria-hidden="true"></i> 닫기</div>
            </div>
          </div>
          <div class="header-menu">
            <div class="header-menu-item"><a href="<?=BASE_URL?>/shop">인기상품</a></div>
            <?php foreach ($listCategory as $value): ?>
            <div class="header-menu-item<?=!empty($search['item_category1']) && $search['item_category1'] == $value['idx'] ? ' active' : ''?>"><a href="<?=BASE_URL?>/shop/?c=<?=$value['idx']?>"><?=$value['name']?></a></div>
            <?php endforeach; ?>
          </div>

          <div class="sub-content mt-3 p-3">
            <div class="m-3 p-5 text-center">
              <h2 class="text-info">구매해 주셔서<br class="d-block d-sm-none"> 감사합니다!</h2>
            </div>
            <h4 class="border-bottom m-0 pb-3">■ 구매한 상품</h4>
            <?php $totalAmount = 0; $totalCost = 0; foreach ($listCart as $value): $totalAmount += $value['amount']; $totalCost += $value['cost'] * $value['amount']; ?>
            <div class="row align-items-center pt-3 d-md-none">
              <div class="col"><strong><?=$value['name']?></strong></div>
            </div>
            <div class="row align-items-center border-bottom p-3">
              <div class="col-5 col-md-2 p-0 text-center"><img class="w-100" src="<?=PHOTO_URL . $value['photo']?>"></div>
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
              <div class="col-8 col-md-9 p-0 pr-2">
                국민 / 010-7271-3050 / 최병준(경인웰빙투어)<br>
                농협 / 010-7271-3050-09
              </div>
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
