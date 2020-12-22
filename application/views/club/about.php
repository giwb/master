<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="mt-3 mb-3">
          <?php if ($view['idx'] == 1): ?>
          <div class="row-category">
            <div class="row border-right small text-center m-0 p-0">
              <a href="<?=BASE_URL?>" class="col-6 border-left pt-2 pb-2 pl-0 pr-0">TOP</a>
              <a href="<?=BASE_URL?>/club/about/?p=top" class="col-6 border-left pt-2 pb-2 pl-0 pr-0<?=strstr($_SERVER['REQUEST_URI'], '=top') ? ' active' : ''?>">산악회 소개</a>
              <a href="<?=BASE_URL?>/club/about/?p=guide" class="col-6 border-left pt-2 pb-2 pl-0 pr-0<?=strstr($_SERVER['REQUEST_URI'], '=guide') ? ' active' : ''?>">등산안내인</a>
              <a href="<?=BASE_URL?>/club/past" class="col-6 border-left pt-2 pb-2 pl-0 pr-0<?=strstr($_SERVER['REQUEST_URI'], '/past') ? ' active' : ''?>">지난 산행보기</a>
              <a href="<?=BASE_URL?>/club/about/?p=howto" class="col-6 border-left pt-2 pb-2 pl-0 pr-0<?=strstr($_SERVER['REQUEST_URI'], '=howto') ? ' active' : ''?>">이용안내</a>
              <a href="<?=BASE_URL?>/club/about/?p=mountain" class="col-6 border-left pt-2 pb-2 pl-0 pr-0<?=strstr($_SERVER['REQUEST_URI'], '=mountain') ? ' active' : ''?>">100대명산</a>
              <a href="<?=BASE_URL?>/club/about/?p=place" class="col-6 border-top border-left pt-2 pb-2 pl-0 pr-0<?=strstr($_SERVER['REQUEST_URI'], '=place') ? ' active' : ''?>">100대명소</a>
              <a href="<?=BASE_URL?>/club/auth" class="col-6 border-top border-left pt-2 pb-2 pl-0 pr-0<?=strstr($_SERVER['REQUEST_URI'], '/auth') ? ' active' : ''?>">인증현황</a>
            </div>
          </div>
          <?php else: ?>
          <?php endif; ?>
        </div>
        <h2 class="sub-header"><?=$pageTitle?></h2>
        <div class="sub-content mb-5">
          <?=reset_html_escape($pageContent)?>
        </div>
      </div>
