<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="mypage">
          <h2>드라이버 페이지</h2>
          <?php foreach ($listNoticeDriver as $value): ?>
          <div class="border-bottom p-2">
            <strong><?=viewStatus($value['status'])?> <a href="<?=base_url()?>reserve/<?=$value['club_idx']?>?n=<?=$value['idx']?>"><?=$value['subject']?></a></strong><br>
            ・일시 : <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?><br>
            ・승객 : <?=$value['count']?>명<br>
            ・거리 : <?=$value['driving_fuel']?>km<br>
            ・통행료 : <?=number_format($value['driving_cost'])?>원<br>
            ・승객수당 : <?=number_format($value['cost_driver'])?>원
          </div>
          <?php endforeach; ?>
        </div>
      </div>
