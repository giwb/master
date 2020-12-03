<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <section class="container">
      <?php if (empty($view['idx'])): ?>
      등록된 데이터가 없습니다.
      <?php else: ?>
      <div class="row align-items-center border-bottom mb-3 pb-3">
        <div class="col-sm-8"><h2 class="m-0"><?=$view['title']?></h2></div>
        <div class="col-sm-4 text-right">
          <input type="hidden" name="page" value="place">
          <a href="/place/entry/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-primary">수정</button></a>
          <button class="btn btn-sm btn-danger btn-delete-modal" data-idx='<?=$view['idx']?>'>삭제</button>
          <a href="/place"><button type="button" class="btn btn-sm btn-secondary btn-back">목록으로</button></a>
        </div>
      </div>
      <div class="m-3">
        <?php if (!empty($view['photo_' . TYPE_MAIN])): foreach ($view['photo_' . TYPE_MAIN] as $value): ?>
        <img src="<?=PHOTO_URL?><?=$value?>" class="w-100">
        <?php endforeach; endif; ?>
        <?php if (!empty($view['photo_' . TYPE_ADDED])): foreach ($view['photo_' . TYPE_ADDED] as $value): ?>
        <img src="<?=PHOTO_URL?><?=$value?>" class="w-100">
        <?php endforeach; endif; ?>
        <?php if (!empty($view['photo_' . TYPE_MAP])): foreach ($view['photo_' . TYPE_MAP] as $value): ?>
        <img src="<?=PHOTO_URL?><?=$value?>" class="w-100">
        <?php endforeach; endif; ?>

        <div class="pt-3 text-justify">
          <?=getAreaName($view['area_sido'], $view['area_gugun'])?><br>
          <?=getHeight($view['altitude'])?>
          <?=getPoint($view['point'])?>

          <p><?=$view['reason']?></p>
          <p><?=$view['content']?></p>
          <p><?=$view['around']?></p>
          <p><?=$view['course']?></p>
        </div>
      </div>
      <?php endif; ?>
    </section>
