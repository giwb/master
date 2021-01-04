<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="row-category mt-3 mb-3">
          <div class="bg-<?=$view['main_color']?> pt-1"></div>
          <div class="row border-right small text-center m-0 p-0">
            <?php foreach ($listAbout as $value): ?>
            <a href="<?=BASE_URL?>/club/about/<?=$value['idx']?>" class="col-6 border-left border-bottom pt-2 pb-2 pl-0 pr-0<?=$pageIdx == $value['idx'] ? ' active' : ''?>"><?=$value['title']?></a><br>
            <?php endforeach; ?>
            <?php if (!empty($userLevel['levelType']) && $userLevel['levelType'] >= 1): ?>
            <a href="<?=BASE_URL?>/club/past" class="<?=count($listAbout)%2 == 0 ? 'col-12' : 'col-6'?> border-left border-bottom pt-2 pb-2 pl-0 pr-0">지난산행</a><br>
            <?php endif; ?>
          </div>
        </div>
        <?php if (empty($viewAbout['idx'])): ?>
          <div class="text-center p-5">해당하는 페이지가 없습니다.</div>
        <?php else: ?>
        <h2 class="sub-header"><?=$viewAbout['title']?></h2>
        <div class="sub-content mb-5">
          <?=reset_html_escape($viewAbout['content'])?>
        </div>
        <?php endif; ?>
      </div>
