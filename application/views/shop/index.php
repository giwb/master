<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <script type="text/javascript" src="/public/js/shop.js"></script>
  <main id="shop" class="shop-main">
    <div class="container-fluid club-main">
      <div class="row mt-1 mb-5">
        <div class="col-xl-8 col-md-12">

          <h4 class="font-weight-bold"><?=$pageTitle?></h4>
          <hr class="text-default">

          <div class="row align-items-center">
            <div class="col-9 mt-2 mb-2"><a href="javascript:;" class="btn-notice" data-idx="1"><strong><i class="fa fa-question-circle-o" aria-hidden="true"></i> 용품샵이란..?</strong></a></div>
            <div class="col-3 text-right d-none d-sm-block"><a href="<?=BASE_URL?>/shop/cart"><button type="button" class="btn btn-sm btn-cart">장바구니 보기</button></a></div>
          </div>
          <div class="area-notice text-justify small" data-idx="1">
            <div class="border mb-3 p-4">
              용품샵이란, 온라인 최저가 상품을 박스단위로 구입하여 산행시 필요로 하는 분들께 택배비 부담없이 낱개로 전달해 드리는 상품입니다.<br><br>
              산행 준비를 꼼꼼히 하시는 분들이야 문제 없겠지만, 산행하는 날마다 편의점에서 생수나 여행용티슈를 비싸게 구입하시는  경우도 많다보니, 가장 기본적인 준비물이지만 미리 준비하기 힘드신 분들께 제공해드리는 편의 서비스라고 이해하시면 좋을것 같습니다.<br><br>
              아울러 등산용품 중 추천할만한 용품을 비교적 저렴한 가격으로 손쉽게 구입하실 수 있도록 검증된 제품만 선별해서 올려드릴 예정이니 많은 관심 부탁드립니다.<br><br>
              신청은 해당 산행의 출발 1일전 18시에 마감할 예정이구요.. 기타 문의사항은 댓글이나 카톡을 이용해 주세요~
              <div class="btn-notice text-right mt-2" data-idx="1"><i class="fa fa-chevron-up" aria-hidden="true"></i> 닫기</div>
            </div>
          </div>
          <div class="shop-menu">
            <div class="shop-menu-item<?=empty($search['item_category1']) ? ' active' : ''?>"><a href="<?=BASE_URL?>/shop">인기상품</a></div>
            <?php foreach ($listCategory as $value): ?>
            <div class="shop-menu-item<?=!empty($search['item_category1']) && $search['item_category1'] == $value['idx'] ? ' active' : ''?>"><a href="<?=BASE_URL?>/shop/?c=<?=$value['idx']?>"><?=$value['name']?></a></div>
            <?php endforeach; ?>
          </div>
          <div class="sub-content mt-3 p-3">
            <form id="formList">
              <input type="hidden" name="p" value="1">
              <?php if (empty($cntItem['cnt'])): ?>
                <div class="text-center">등록된 상품이 없습니다.</div>
              <?php else: ?>
              <?=$listItem?>
              <?php endif; ?>
              <div class="area-append"></div>
              <?php if ($cntItem['cnt'] > $perPage): ?>
              <button type="button" class="btn btn-page-next">다음 페이지 보기 ▼</button>
              <?php endif; ?>
            </form>
          </div>
        </div>


