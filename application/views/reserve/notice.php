<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="sub-contents">
          <div class="sub-title">
            <div class="area-title"><h2><b>[<?=viewStatus($notice['status'])?>]</b> <?=$notice['subject']?></h2></div>
            <div class="area-btn"><a href="<?=base_url()?>reserve/<?=$view['idx']?>?n=<?=$notice['idx']?>"><button type="button" class="btn btn-primary">좌석보기</button></a></div>
          </div>

          <div class="sub-header">기획의도</div>
          <div class="sub-content"><?=reset_html_escape($notice['plan'])?></div><br>

          <div class="sub-header">산행개요</div>
          <div class="sub-content"><?=reset_html_escape($notice['point'])?></div><br>

          <div class="sub-header">산행지소개</div>
          <div class="sub-content"><?=reset_html_escape($notice['intro'])?></div><br>

          <div class="sub-header">일정안내</div>
          <div class="sub-content"><?=reset_html_escape($notice['timetable'])?></div><br>

          <div class="sub-header">산행안내</div>
          <div class="sub-content"><?=reset_html_escape($notice['information'])?></div><br>

          <div class="sub-header">코스안내</div>
          <div class="sub-content"><?=reset_html_escape($notice['course'])?></div>

          <div class="area-reply" data-idx="<?=$notice['idx']?>">
            <form method="post" action="<?=base_url()?>story/insert_reply/<?=$view['idx']?>" class="story-reply-input" data-idx="<?=$value['idx']?>">
              <input type="hidden" name="storyIdx" value="<?=$value['idx']?>">
              <textarea name="content" class="club-story-reply"></textarea>
              <button type="button" class="btn btn-primary btn-post-reply" data-idx="<?=$value['idx']?>">댓글달기</button>
            </form>
            <div class="story-reply-content">
            </div>
          </div>
        </div>
      </div>
