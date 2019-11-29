<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="sub-contents">
          <div class="sub-title">
            <div class="area-title"><h2><b>[<?=viewStatus($notice['status'])?>]</b> <?=$notice['subject']?></h2></div>
            <div class="area-btn"><a href="<?=base_url()?>reserve/<?=$view['idx']?>?n=<?=$notice['idx']?>"><button type="button" class="btn btn-primary">좌석보기</button></a></div>
          </div>

          <div class="sub-header">기획의도</div>
          <div class="sub-content"><?=reset_html_escape($notice['plan'])?></div><p><br></p>

          <div class="sub-header">여행개요</div>
          <div class="sub-content"><?=reset_html_escape($notice['point'])?></div><p><br></p>

          <div class="sub-header">산행지소개</div>
          <?php if (!empty($notice['photo'])): ?><div class="sub-photo"><img src="<?=$notice['photo']?>"></div><?php endif; ?>
          <div class="sub-content"><?=reset_html_escape($notice['intro'])?></div><p><br></p>

          <div class="sub-header">일정안내</div>
          <div class="sub-content"><?=reset_html_escape($notice['timetable'])?></div><p><br></p>

          <div class="sub-header">산행안내</div>
          <div class="sub-content"><?=reset_html_escape($notice['information'])?></div><p><br></p>

          <div class="sub-header">코스안내</div>
          <div class="sub-content"><?=reset_html_escape($notice['course'])?></div>

          <?php if (!empty($notice['map'])): ?><div class="sub-photo"><img src="<?=$notice['map']?>"></div><?php endif; ?>
        </div>
      </div>
