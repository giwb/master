<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="sub-contents">
          <div class="sub-title">
            <div class="area-title"><h2><b>[<?=viewStatus($notice['status'])?>]</b> <?=$notice['subject']?></h2></div>
            <div class="area-btn"><a href="<?=base_url()?>reserve/<?=$view['idx']?>?n=<?=$notice['idx']?>"><button type="button" class="btn btn-primary">좌석보기</button></a></div>
          </div>
          <div align="center"><img src="<?=base_url() . '/' . PHOTO_URL . $notice['photo']?>"></div><br>
          기획의도<br>
          <?=reset_html_escape($notice['plan'])?><br><br>
          핵심안내<br>
          <?=reset_html_escape($notice['point'])?><br><br>
          타임테이블<br>
          <?=reset_html_escape($notice['timetable'])?><br><br>
          산행안내<br>
          <?=reset_html_escape($notice['information'])?><br><br>
          산행코스안내<br>
          <?=reset_html_escape($notice['course'])?><br><br>
          산행지소개<br>
          <?=reset_html_escape($notice['intro'])?><br><br>
          <div align="center"><img src="<?=base_url() . '/' . PHOTO_URL . $notice['map']?>"></div>
        </div>
      </div>
