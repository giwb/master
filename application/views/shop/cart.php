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
              <a href="<?=BASE_URL?>/shop/cart" class="btn-custom btn-giwb btn-cart small">장바구니 보기</a>
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
            <hr>
            <?php if (empty($listCart)): ?>
            <div class="text-center border-bottom mt-5 pb-5">현재 장바구니에 담긴 상품이 없습니다.</div>
            <?php endif; ?>
            <?php foreach ($listCart as $value): ?>
            <div class="row align-items-center pt-3 d-md-none">
              <div class="col"><strong><?=$value['item_name']?></strong></div>
            </div>
            <div class="row align-items-center border-bottom p-3">
              <div class="col-5 col-md-2 p-0 text-center"><img class="w-100" src="<?=$value['item_photo']?>"></div>
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
