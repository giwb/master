<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <section class="container">
      <div class="row align-items-center border-bottom mb-3 pb-3">
        <div class="col-sm-7">
          <h2><?=$searchTitle['name']?></h2>
        </div>
        <div class="col-sm-5 text-right p-0">
          <form method="get" action="/club" class="row align-items-center m-0">
            <div class="col-8 p-1"><input type="hidden" name="s" value="title"><input type="text" size="10" name="k" value="<?=$search == 'title' ? $keyword : ''?>" class="form-control form-control-sm"></div>
            <div class="col-2 p-1"><button type="submit" class="btn btn-sm btn-primary w-100">검색</button></div>
            <div class="col-2 p-1"><a href="/club/entry"><button type="button" class="btn btn-sm btn-primary w-100">등록</button></a></div>
          </form>
        </div>
      </div>
      <ul class="m-0 p-0">
      <?php if (empty($list)): ?>
      </ul>
      <div class="text-center">등록된 데이터가 없습니다.</div>
      <?php else: foreach ($list as $value): ?>
        <li class="row align-items-top border-bottom mb-3 pr-3 pb-3">
          <div class="col-4 col-sm-2 pr-0"><a target="_blank" href="<?=goHome($value)?>"><img src="<?=PHOTO_URL?><?=$value['photo']?>" class="w-100"></a></div>
          <div class="col-8 col-sm-10 text-justify"><a target="_blank" href="<?=goHome($value)?>"><h3 class="font-weight-bold"><?=$value['title']?></h3></a><?=ksubstr(strip_tags(reset_html_escape($value['about'])), 200)?></div>
        </li>
      <?php endforeach; endif; ?>
      </ul>
    </section>
