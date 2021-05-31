<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main id="club">
    <div class="container-fluid club-main">
      <div class="row mt-1 mb-5">
        <div class="col-xl-8 col-md-12">
          <h4 class="font-weight-bold"><?=$pageTitle?></h4>
          <hr class="text-default">

          <div class="d-block d-sm-none">
            <div class="header-menu mt-3 mb-3">
              <div class="header-menu-item"><a href="<?=BASE_URL?>/club/auth">인증현황</a></div>
              <div class="header-menu-item<?=$type == 'mountain' ? ' active' : ''?>"><a href="<?=BASE_URL?>/club/page/?type=mountain">100대명산</a></div>
              <div class="header-menu-item<?=$type == 'place' ? ' active' : ''?>"><a href="<?=BASE_URL?>/club/page/?type=place">100대명소</a></div>
            </div>
          </div>

          <div class="sub-content p-3 mb-5">
            <?=$type == 'mountain' ? reset_html_escape($view['mountain']) : reset_html_escape($view['place'])?>
          </div>
        </div>
