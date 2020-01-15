<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div id="shop" class="club-main">
        <div class="mb-3"><h3>구매대행 상품</h3></div>
        <div class="row-category row m-0 p-0 border-bottom border-right">
          <a href="<?=base_url()?>club/shop/<?=$clubIdx?>" class="col border-left pt-2 pb-2 pl-0 pr-0 small text-center<?=empty($search['item_category1']) ? ' active' : ''?>">인기상품</a>
          <?php foreach ($listCategory as $value): ?>
          <a href="<?=base_url()?>club/shop/<?=$clubIdx?>?c=<?=$value['idx']?>" class="col border-left pt-2 pb-2 pl-0 pr-0 small text-center<?=$value['idx'] == $search['item_category1'] ? ' active' : ''?>"><?=$value['name']?></a>
          <?php endforeach; ?>
        </div>
        <div class="border mt-4 p-3 small">
          <a href="javascript:;" class="btn-notice" data-idx="1">구매대행 상품이란..?</a>
          <div class="notice" style="display:none;" data-idx="1">본 상품은 경인웰빙이 유통하는 상품이 아니고, 소비자 입장에서 박스로 구입하여 낱개로 전달해 드리는 상품입니다.<br>
            박스단위로 택배비 부담이 없이 비교적 저렴하게 구입하여 회원분들께 낱개 단위로 저렴하게 전해 드리는 상품으로 산행시 꼭 필요로 하는 품목을 산행 신청하신 분들을 대상으로 버스에서 전달해 드리는 서비스로 이해해 주시면 감사하겠습니다. <br>
            산행 준비를 꼼꼼히 하시는 분들이야 문제 없겠지만, 산행 하는 날마다 편의점에서 비싼 생수를 구입하고, 현지에서 막걸리를 사거나 그마저도 구입할 수 없는 상황에 발을 동동 구르시는 상황을 많이 보아왔던 입장인지라, 가장 기본적인 준비물이지만 미리 준비하기 힘드신 분들을 위해 제가 대신 준비해 드리겠다는 차원입니다.<br>
            제공되는 가격은 마트나 온라인 쇼핑몰의 최저가 가격에 10~20% 정도를 붙이거나 낱개 구입 시 발생하는 택배비 정도를 심부름값 정도의 비용을 얹어서 책정되었다는 점도 참고 삼아 미리 말씀 드립니다.<br>
            홈페이지 신청은 산행출발 시간 기준 24시간 전까지만 신청이 가능하시구요.. 그 이후로 주문을 원하시는 경우 카톡으로 직접 주문해 주시기 바랍니다. 상황에 따라 미리 준비되지 않은 품목은 전달해 드리지 못할 수 있다는 점도 사전 양해 부탁드립니다.
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

      <script type="text/javascript" src="<?=base_url()?>public/js/shop.js"></script>
      <script type="text/javascript">
        $('.btn-notice').click(function() {
          var $dom = $('.notice[data-idx=' + $(this).data('idx') + ']');
          if ($dom.css('display') == 'none') {
            $dom.slideDown();
          } else {
            $dom.slideUp();
          }
        });
      </script>