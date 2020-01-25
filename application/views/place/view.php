<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section id="subpage">
  <div class="sub-header">
    <div class="left">
      <h2>여행정보 보기</h2>
    </div>
    <div class="right">
      <input type="hidden" name="page" value="place">
      <a href="<?=base_url()?>place/entry/<?=$view['idx']?>"><button type="button">수정</button></a>
      <button class="btn-delete-modal" data-idx='<?=$view['idx']?>'>삭제</button>
      <a href="<?=base_url()?>place"><button type="button" class="btn-back">목록으로</button></a>
    </div>
  </div>

  <div class="sub-contents">
<?php if (!empty($view['idx'])): ?>
    <h3><?=$view['title']?></h3>
    <div class="point">
      <?=getAreaName($view['area_sido'], $view['area_gugun'])?><br>
      <?=getHeight($view['altitude'])?>
      <?=getPoint($view['point'])?>
    </div>

    <div id="photoCarousel" class="carousel slide carousel-fade" data-ride="carousel">
      <ol class="carousel-indicators">
<?php
  $cnt = 0;
  if (!empty($view['photo_' . TYPE_MAIN])):
    foreach ($view['photo_' . TYPE_MAIN] as $value):
?>
        <li data-target="#photoCarousel" data-slide-to="<?=$cnt?>"<?=$cnt == 0 ? " class='active'" : ""?>><img src="<?=base_url()?><?=PHOTO_URL?>thumb_<?=$value?>"></li>
<?php
      $cnt++;
    endforeach;
  endif;

  if (!empty($view['photo_' . TYPE_ADDED])):
    foreach ($view['photo_' . TYPE_ADDED] as $value):
?>
        <li data-target="#photoCarousel" data-slide-to="<?=$cnt?>"<?=$cnt == 0 ? " class='active'" : ""?>><img src="<?=base_url()?><?=PHOTO_URL?>thumb_<?=$value?>"></li>
<?php 
      $cnt++;
    endforeach;
  endif;
?>
      </ol>
      <div class="carousel-inner">
<?php
  $cnt = 0;
  if (!empty($view['photo_' . TYPE_MAIN])):
    foreach ($view['photo_' . TYPE_MAIN] as $value):
?>
        <div class="carousel-item<?=$cnt == 0 ? " active" : ""?>">
          <img src="<?=base_url()?><?=PHOTO_URL?><?=$value?>">
        </div>
<?php
      $cnt++;
    endforeach;
  endif;

  if (!empty($view['photo_' . TYPE_ADDED])):
    foreach ($view['photo_' . TYPE_ADDED] as $value):
?>
        <div class="carousel-item">
          <img src="<?=base_url()?><?=PHOTO_URL?><?=$value?>">
        </div>
<?php
    endforeach;
  endif;
?>
      </div>
      <a class="carousel-control-prev" href="#photoCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#photoCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>

<?php if ($view['reason'] != ''): ?>
    <div class="content"><?=$view['reason']?></div>
<?php endif; ?>
    <div class="content"><?=$view['content']?></div>
    <div class="content"><?=$view['around']?></div>
    <div class="content"><?=$view['course']?></div>
    <br>

<?php
  if (!empty($view['photo_' . TYPE_MAP])):
    if (count($view['photo_' . TYPE_MAP]) >= 2):
?>
    <div id="mapCarousel" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
<?php
    $cnt = 0;
    foreach ($view['photo_' . TYPE_MAP] as $value):
?>
        <li data-target="#mapCarousel" data-slide-to="<?=$cnt?>"<?=$cnt == 0 ? " class='active'" : ""?>><img src="<?=base_url()?><?=PHOTO_URL?>thumb_<?=$value?>"></li>
<?php
      $cnt++;
    endforeach;
?>
      </ol>
      <div class="carousel-inner">
<?php
    $cnt = 0;
    foreach ($view['photo_' . TYPE_MAP] as $value):
?>
        <div class="carousel-item<?=$cnt == 0 ? " active" : ""?>">
          <img src="<?=base_url()?><?=PHOTO_URL?><?=$value?>">
        </div>
<?php
      $cnt++;
    endforeach;
?>
      </div>
      <a class="carousel-control-prev" href="#mapCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#mapCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
<?php else: ?>
    <div class="photo-map">
<?php foreach ($view['photo_' . TYPE_MAP] as $value): ?>
      <img src="<?=base_url()?><?=PHOTO_URL?><?=$value?>">
<?php endforeach; ?>
    </div>
<?php
    endif;
  endif;
?>

<?php else: ?>
    <div class="text-center">
      데이터가 없습니다.
    </div>
<?php endif; ?>
  </div>

  <div class="sub-footer">
<?php if (!empty($view['idx'])): ?>
    <div class="right">
      <a href="<?=base_url()?>place/entry/<?=$view['idx']?>"><button type="button">수정</button></a>
      <button class="btn-delete-modal" data-idx='<?=$view['idx']?>'>삭제</button>
      <a href="<?=base_url()?>place"><button type="button" class="btn-back">목록으로</button></a>
    </div>
<?php endif; ?>
  </div>
</section>
