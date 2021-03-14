<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main id="club">
    <div class="container-fluid club-main">
      <div class="row mt-1 mb-5">
        <div class="col-xl-8 col-md-12">

          <div class="row align-items-center">
            <div class="col-8 col-sm-9"><h4 class="font-weight-bold"><?=$viewAbout['title']?></h4></div>
            <div class="col-4 col-sm-3 text-right">
              <select class="form-control form-control-sm" onChange="location.href=this.value">
                <?php foreach ($listAbout as $value): ?>
                <option<?=$pageIdx == $value['idx'] ? ' selected' : ''?> value="<?=BASE_URL?>/club/about/<?=$value['idx']?>"><?=$value['title']?></option>
                <?php endforeach; ?>
                <option value="<?=BASE_URL?>/club/past">지난산행</option>
              </select>
            </div>
          </div>
          <hr class="text-default mt-2">

          <?php if (empty($viewAbout['idx'])): ?>
          <div class="text-center p-5">해당하는 페이지가 없습니다.</div>
          <?php else: ?>
          <div class="sub-content p-3 mb-5">
          <?=reset_html_escape($viewAbout['content'])?>
          </div>
          <?php endif; ?>
        </div>
