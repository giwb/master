<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div id="shop" class="club-main">
        <h2 class="sub-header"><?=$pageTitle?></h2>
        <div class="row align-items-center">
          <div class="col-9 mt-2 mb-2"><a href="javascript:;" class="btn-notice" data-idx="1"><strong><i class="fa fa-question-circle-o" aria-hidden="true"></i> 구매대행 상품이란..?</strong></a></div>
          <div class="col-3 text-right d-none d-sm-block"><a href="<?=BASE_URL?>/shop/cart"><button type="button" class="btn btn-sm btn-cart">장바구니 보기</button></a></div>
        </div>
        <div class="area-notice text-justify small" data-idx="1">
          <div class="border mb-3 p-4">
            구매대행 상품이란, 온라인 최저가 상품을 박스단위로 구입하여 산행시 필요로 하는 분들께 택배비 부담없이 낱개로 전달해 드리는 상품입니다.<br><br>
            산행 준비를 꼼꼼히 하시는 분들이야 문제 없겠지만, 산행 하는 날마다 편의점에서 비싼 여행용티슈나 생수를 구입하시는  경우도 많다보니, 가장 기본적인 준비물이지만 미리 준비하기 힘드신 분들께 제공해드리는 편의 서비스입니다.<br><br>
            아울러 아웃도어 용품 중 추천할만한 용품을 비교적 저렴한 가격으로 손쉽게 구입하실 수 있도록 검증된 제품만 선별해서 올려드릴 예정입니다.<br><br>
            신청은 해당 산행의 출발 1일전 18시에 마감할 예정이구요.. 기타 문의사항은 댓글이나 카톡을 이용해 주세요~
            <div class="btn-notice text-right mt-2" data-idx="1"><i class="fa fa-chevron-up" aria-hidden="true"></i> 닫기</div>
          </div>
        </div>
        <div class="d-none d-sm-block">
          <div class="row-category row m-0 p-0 border-bottom border-right">
            <a href="<?=BASE_URL?>/shop" class="col border-left pt-2 pb-2 pl-0 pr-0 small text-center<?=empty($search['item_category1']) ? ' active' : ''?>">인기상품</a>
            <?php foreach ($listCategory as $value): ?>
            <a href="<?=BASE_URL?>/shop/?c=<?=$value['idx']?>" class="col border-left pt-2 pb-2 pl-0 pr-0 small text-center<?=$value['idx'] == $search['item_category1'] ? ' active' : ''?>"><?=$value['name']?></a>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="d-sm-none">
          <div class="row-category">
            <div class="row m-0 p-0 border-right">
              <?php foreach ($listCategory as $key => $value): ?>
              <?php if ($key == 0): ?><a href="<?=BASE_URL?>/shop" class="col border-left pt-2 pb-2 pl-0 pr-0 small text-center<?=empty($search['item_category1']) ? ' active' : ''?>">인기상품</a>
              <?php elseif ($key == 1): ?></div><div class='row m-0 p-0 border-right'>
              <?php elseif ($key >= 2 && $key%2 == 1): ?></div><div class='row m-0 p-0 border-right'>
              <?php endif; ?>
              <a href="<?=BASE_URL?>/shop/?c=<?=$value['idx']?>" class="col border-left pt-2 pb-2 pl-0 pr-0 small text-center<?=$value['idx'] == $search['item_category1'] ? ' active' : ''?>"><?=$value['name']?></a>
              <?php endforeach; ?>
            </div>
          </div>
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

      <script type="text/javascript" src="/public/js/shop.js"></script>
