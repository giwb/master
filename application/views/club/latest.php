<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="sub-header">지난 산행보기</div>
        <div class="sub-content">
          <div class="list-schedule">
<?php foreach ($listLatestNotice as $value): ?>
            <a href="<?=base_url()?>reserve/<?=$value['club_idx']?>?n=<?=$value['idx']?>"><strong><?=$value['subject']?></strong><br><?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / <?=number_format($value['cost'])?>원 / <?=cntRes($value['idx'])?>명</a>
<?php endforeach; ?>
          </div>
        </div>
      </div>
