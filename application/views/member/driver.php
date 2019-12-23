<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="mypage mt-2">
          <h2>드라이버 페이지</h2>
          <?php foreach ($listNoticeDriver as $value): ?>
          <div class="border-bottom p-2">
            <a href="<?=base_url()?>member/driver/<?=$value['club_idx']?>?n=<?=$value['idx']?>"><?=viewStatus($value['status'])?> <strong><?=$value['subject']?></strong></a><br><?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원 / <?=cntRes($value['idx'])?>명
          </div>
          <?php endforeach; ?>
        </div>
      </div>
