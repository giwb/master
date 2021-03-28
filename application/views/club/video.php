<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-8 col-md-12 mb-5">
          <div class="row no-gutters align-items-center">
            <div class="col-10"><h4 class="font-weight-bold">여행기 - 동영상</h4></div>
            <?php if (!empty($userData['idx'])): ?>
            <div class="col-2 text-right">
              <a href="<?=BASE_URL?>/club/video_post" class="btn-custom btn-giwbblue">등록</a>
            </div>
            <?php endif; ?>
          </div>
          <hr class="text-default mt-2">

          <div class="d-block d-sm-none">
            <div class="header-menu mt-3">
              <div class="header-menu-item"><a href="<?=BASE_URL?>/album">사진첩</a></div>
              <div class="header-menu-item active"><a href="<?=BASE_URL?>/club/video">동영상</a></div>
              <div class="header-menu-item"><a href="<?=BASE_URL?>/club/search/?code=news">여행정보</a></div>
              <div class="header-menu-item"><a href="<?=BASE_URL?>/club/search/?code=review">여행후기</a></div>
            </div>
          </div>

          <div class="row">
            <?php if (empty($listVideo)): ?>
            <div class="col-12 text-center p-5">
              아직 등록된 동영상이 없습니다.
            </div>
            <?php else: ?>
            <?php foreach ($listVideo as $value): ?>
            <div class="col-md-6 my-3">
              <div class="card">
                <div class="card-body">
                  <h4 class="text-center"><?=$value['subject']?></h4>
                  <?=$value['video_link']?>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
