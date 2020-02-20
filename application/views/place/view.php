<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <section class="container">
      <div class="row">
        <div class="col-sm-3 nav-place">
          <a href="/place/?search=type&keyword=type1" class="d-block">전체보기</a>
          <a href="/place/?search=type&keyword=type2" class="d-block">산림청 선정 100대 명산</a>
          <a href="/place/?search=type&keyword=type3" class="d-block">블랙야크 명산100</a>
          <a href="/place/?search=type&keyword=type4" class="d-block">죽기전에 꼭 가봐야 할 국내여행 1001</a>
          <a href="/place/?search=type&keyword=type5" class="d-block">백두대간</a>
          <a href="/place/?search=type&keyword=type6" class="d-block">도보트레킹</a>
          <a href="/place/?search=type&keyword=type7" class="d-block">투어</a>
          <a href="/place/?search=type&keyword=type8" class="d-block">섬</a>
        </div>
        <div class="col-sm-9 p-3">
          <?php if (empty($view['idx'])): ?>
          등록된 데이터가 없습니다.
          <?php else: ?>
            <div class="row align-items-center border-bottom mt-1 mb-3 pb-3">
              <div class="col-6"><h2><?=$view['title']?></h2></div>
              <div class="col-6 text-right">
                <div class="pr-3">
                  <input type="hidden" name="page" value="place">
                  <a href="<?=base_url()?>place/entry/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-primary">수정</button></a>
                  <button class="btn btn-sm btn-danger btn-delete-modal" data-idx='<?=$view['idx']?>'>삭제</button>
                  <a href="<?=base_url()?>place"><button type="button" class="btn btn-sm btn-secondary btn-back">목록으로</button></a>
                </div>
              </div>
            </div>
            <div class="pr-3">
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
        </div>
      </div>
    </section>
