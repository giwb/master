<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div id="shop" class="club-main">
        <div class="sub-header">용품판매</div>
        <div class="sub-content mt-4 p-3">
          <form id="formList">
            <input type="hidden" name="p" value="1">
            <?=$listItem?>
            <div class="area-append"></div>
            <?php if ($cntItem['cnt'] > $perPage): ?>
            <button type="button" class="btn btn-page-next">다음 페이지 보기 ▼</button>
            <?php endif; ?>
          </form>
        </div>
      </div>
      <script type="text/javascript" src="<?=base_url()?>public/js/shop.js"></script>