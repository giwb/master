<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="sub-contents">
          <div class="sub-title">
            <div class="area-title"><h2><b>[<?=viewStatus($notice['status'])?>]</b> <?=$notice['subject']?></h2></div>
            <div class="area-btn"><a href="<?=base_url()?>reserve/<?=$view['idx']?>?n=<?=$notice['idx']?>"><button type="button" class="btn btn-primary">좌석보기</button></a></div>
          </div>
          <?php if (!empty($notice['photo'])): ?><div class="sub-photo"><img src="<?=$notice['photo']?>"></div><?php endif; ?>

          <div class="sub-header">기획의도</div>
          <div class="sub-content"><?=reset_html_escape($notice['plan'])?></div>

          <div class="sub-header">핵심안내</div>
          <div class="sub-content"><?=reset_html_escape($notice['point'])?></div>

          <div class="sub-header">타임테이블</div>
          <div class="sub-content"><?=reset_html_escape($notice['timetable'])?></div>

          <div class="sub-header">산행안내</div>
          <div class="sub-content"><?=reset_html_escape($notice['information'])?></div>

          <div class="sub-header">산행코스안내</div>
          <div class="sub-content"><?=reset_html_escape($notice['course'])?></div>

          <div class="sub-header">산행지소개</div>
          <div class="sub-content"><?=reset_html_escape($notice['intro'])?></div>

          <?php if (!empty($notice['map'])): ?><div class="sub-photo"><img src="<?=$notice['map']?>"></div><?php endif; ?>
        </div>
      </div>
