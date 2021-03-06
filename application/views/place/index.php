<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-8 col-md-12">
          <section class="section extra-margins listing-section">
            <div class="row align-items-center">
              <div class="col-6"><h4 class="font-weight-bold"><strong><?=$pageTitle?></strong></h4></div>
              <div class="col-6 list-view text-right"><h4><a href="<?=base_url()?>place?view=list&code=<?=$code?>"><i class="fas fa-th-list<?=empty($viewType) || (!empty($viewType) && $viewType == 'list') ? ' active' : ''?>" title="목록"></i></a> <a href="<?=base_url()?>place?view=photo&code=<?=$code?>"><i class="fas fa-th-large ml-2<?=!empty($viewType) && $viewType == 'photo' ? ' active' : ''?>" title="사진"></i></a></h4></div>
            </div>
            <hr class="red mt-2">
            <div class="row mb-4">
              <?php foreach($listPlace as $value): ?>
              <?php if (!empty($viewType) && $viewType == 'photo'): ?>
              <div class="col-md-6 my-3">
                <div class="card">
                  <div class="view overlay">
                    <img src="<?=!empty($value['thumbnail']) ? PHOTO_PLACE_URL . 'thumb_' . $value['thumbnail'] : '/public/images/nophoto_big.png'?>" class="card-img-top">
                    <a href="/place/view/<?=$value['idx']?>"><div class="mask rgba-white-slight"></div></a>
                  </div>
                  <div class="card-body">
                    <h4 class="card-title"><strong><a href="/place/view/<?=$value['idx']?>"><i class="fas fa-crown pr-1" style="color:#CE7430;"></i><?=$value['title']?><br><small class="grey-text"><?php foreach ($value['sido'] as $key => $area): if ($key != 0): ?>, <?php endif; ?><?=$area?> <?=!empty($value['gugun'][$key]) ? $value['gugun'][$key] : ''?><?php endforeach; ?><?=!empty($value['altitude']) ? ' / ' . number_format($value['altitude']) . 'm' : ''?></small></a></strong></h4><hr>
                    <p class="card-text text-justify"><?=articleContent($value['content'])?></p>
                  </div>
              <?php else: ?>
              <div class="col-md-12 my-3">
                <div class="card area-link" data-link="/place/view/<?=$value['idx']?>">
                  <div class="card-body row no-gutters">
                    <div class="col-3 col-sm-3" style="background: url(<?=!empty($value['thumbnail']) ? PHOTO_PLACE_URL . 'thumb_' . $value['thumbnail'] : '/public/images/nophoto_mid.png'?>) no-repeat center center; background-size: 130%;">
                    </div>
                    <div class="col-9 col-sm-9 pl-3">
                      <h4 class="card-title font-weight-bold"><?=$value['title']?><br><small class="grey-text"><?php foreach ($value['sido'] as $key => $area): if ($key != 0): ?>, <?php endif; ?><?=$area?> <?=!empty($value['gugun'][$key]) ? $value['gugun'][$key] : ''?><?php endforeach; ?><?=!empty($value['altitude']) ? ' / ' . number_format($value['altitude']) . 'm' : ''?></small></h4>
                      <div class="d-none d-sm-block"><hr>
                        <p class="card-text text-justify"><?=articleContent($value['content'], 180)?></p>
                      </div>
                    </div>
                  </div>
              <?php endif; ?>
                  <div class="mdb-color lighten-3 text-center">
                    <ul class="list-unstyled list-inline font-small text-white mt-3">
                      <li class="list-inline-item pr-2"><i class="far fa-eye pr-1"></i>조회 <?=$value['refer']?></a></li>
                      <li class="list-inline-item pr-2"><i class="far fa-comments pr-1"></i>댓글 0</a></li>
                      <li class="list-inline-item"><i class="fas fa-crown pr-1"></i>랭킹 0위</a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </section>
        </div>
