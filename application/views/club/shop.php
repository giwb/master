<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div id="shop" class="club-main">
        <div class="mb-3"><h3>구매대행 상품</h3></div>
        <div class="row-category row m-0 p-0 border-bottom border-right">
          <a href="<?=base_url()?>club/shop/<?=$clubIdx?>" class="col border-left pt-2 pb-2 pl-0 pr-0 small text-center<?=empty($search['item_category1']) ? ' active' : ''?>">인기상품</a>
          <?php foreach ($listCategory as $value): ?>
          <a href="<?=base_url()?>club/shop/<?=$clubIdx?>?c=<?=$value['idx']?>" class="col border-left pt-2 pb-2 pl-0 pr-0 small text-center<?=$value['idx'] == $search['item_category1'] ? ' active' : ''?>"><?=$value['name']?></a>
          <?php endforeach; ?>
        </div>
        <div class="sub-content mt-4 p-3">
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