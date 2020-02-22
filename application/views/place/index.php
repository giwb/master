<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <section class="container">
      <div class="row">
        <div class="col-sm-3 nav-place d-none d-sm-block">
          <a href="/place/?search=type&keyword=type1" class="d-block<?=empty($keyword) || $keyword == 'type1' ? ' active' : ''?>">전체보기</a>
          <a href="/place/?search=type&keyword=type2" class="d-block<?=$keyword == 'type2' ? ' active' : ''?>">산림청 선정 100대 명산</a>
          <a href="/place/?search=type&keyword=type3" class="d-block<?=$keyword == 'type3' ? ' active' : ''?>">블랙야크 명산100</a>
          <a href="/place/?search=type&keyword=type4" class="d-block<?=$keyword == 'type4' ? ' active' : ''?>">죽기전에 꼭 가봐야 할 국내여행 1001</a>
          <a href="/place/?search=type&keyword=type5" class="d-block<?=$keyword == 'type5' ? ' active' : ''?>">백두대간</a>
          <a href="/place/?search=type&keyword=type6" class="d-block<?=$keyword == 'type6' ? ' active' : ''?>">도보트레킹</a>
          <a href="/place/?search=type&keyword=type7" class="d-block<?=$keyword == 'type7' ? ' active' : ''?>">투어</a>
          <a href="/place/?search=type&keyword=type8" class="d-block<?=$keyword == 'type8' ? ' active' : ''?>">섬</a>
        </div>
        <div class="col col-sm-9">
          <?php if (empty($list)): ?>
          <div class="text-center">등록된 데이터가 없습니다.</div>
          <?php else: ?>
            <div class="row align-items-center border-bottom mt-3 mb-3 pb-3">
              <div class="col-8"><h2 class="m-0"><?=$pageTitle?> <small>(가나다순)</small></h2></div>
              <div class="col-4 text-right">
                <div class="pr-3">
                  <a href="/place/entry"><button type="button" class="btn btn-sm btn-primary">등록</button></a>
                </div>
              </div>
            </div>
            <?php foreach ($list as $value): ?>
              <div class="row align-items-center mb-3">
                <div class="col-sm-3"><a href="/place/view/<?=$value['idx']?>"><img src="<?=$value['photo']?>" class="w-100"></a></div>
                <div class="col-sm-9 pl-0"><h3 class="font-weight-bold"><a href="/place/view/<?=$value['idx']?>"><?=$value['title']?></a></h3><small><?=getHeight($value['altitude'])?><?=getAreaName($value['area_sido'], $value['area_gugun'], 1)?></small></div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </section>
