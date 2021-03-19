<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main id="club">
    <div class="container-fluid club-main">
      <div class="row mt-1 mb-5">
        <div class="col-xl-8 col-md-12">
          <h4 class="font-weight-bold"><?=$viewAbout['title']?></h4>
          <hr class="text-default">

          <div class="d-block d-sm-none">
            <div class="header-menu mt-3 mb-3">
              <?php foreach ($listAbout as $value): ?>
              <div class="header-menu-item<?=$pageIdx == $value['idx'] ? ' active' : ''?>"><a href="<?=BASE_URL?>/club/about/<?=$value['idx']?>"><?=$value['title']?></a></div>
              <?php endforeach; ?>
              <div class="header-menu-item"><a href="<?=BASE_URL?>/club/past">지난산행</a></div>
            </div>
          </div>

          <?php if (empty($viewAbout['idx'])): ?>
          <div class="text-center p-5">해당하는 페이지가 없습니다.</div>
          <?php else: ?>
          <div class="sub-content p-3 mb-5">
          <?=reset_html_escape($viewAbout['content'])?>
          </div>
          <?php endif; ?>
        </div>
