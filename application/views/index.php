<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <section>
      <div class="row align-items-center mb-3 pl-5 pr-5">
        <?php if (!empty($list)): foreach ($list as $value): ?>
        <div class="col-3 mb-3">
          <a href="/place/view/<?=$value['idx']?>"><img src="<?=$value['photo']?>" class="w-100"></a><h4 class="mt-2 pb-1"><a href="/place/view/<?=$value['idx']?>"><?=$value['title']?></a></h4><small><?=getHeight($value['altitude'])?> / <?=getAreaName($value['area_sido'], $value['area_gugun'], 1)?></small>
        </div>
        <?php endforeach; endif; ?>
      </div>
    </section>
