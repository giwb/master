<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <section class="container">
      <div class="row">
        <div class="col-sm-3 pl-0 nav-place d-none d-sm-block">
          <?=$commonMenu?>
        </div>
        <div class="col-12 col-sm-9">
          <?php if (empty($list)): ?>
          <div class="text-center">등록된 데이터가 없습니다.</div>
          <?php else: ?>
            <div class="mt-3 d-none d-sm-block"></div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-8"><h2 class="m-0"><?=$pageTitle?> <small>(가나다순)</small></h2></div>
              <div class="col-4 text-right"><a href="/place/entry"><button type="button" class="btn btn-sm btn-primary">등록</button></a></div>
            </div>
            <?php foreach ($list as $value): ?>
              <div class="row align-items-center mb-3">
                <div class="col-5 col-sm-3"><a href="/place/view/<?=$value['idx']?>"><img src="<?=$value['photo']?>" class="w-100"></a></div>
                <div class="col-7 col-sm-9 pl-0"><h3 class="font-weight-bold"><a href="/place/view/<?=$value['idx']?>"><?=$value['title']?></a></h3><small><?=getHeight($value['altitude'])?><?=getAreaName($value['area_sido'], $value['area_gugun'], 1)?></small></div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </section>
