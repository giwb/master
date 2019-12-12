<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">댓글 기록</h1>
        </div>
      </div>

      <div class="story-reply">
        <?php
          foreach ($listReply as $value):
            if ($value['reply_type'] == REPLY_TYPE_STORY):  $url = 'story/view/' . $value['club_idx'] . '?n=' . $value['idx']; endif;
            if ($value['reply_type'] == REPLY_TYPE_NOTICE): $url = 'reserve/notice/' . $value['club_idx'] . '?n=' . $value['idx']; endif;
            if (file_exists(PHOTO_PATH . $value['created_by'])) $value['photo'] = base_url() . 'public/photos/' . $value['created_by'];
            else $value['photo'] = base_url() . 'public/images/user.png';
        ?>
        <dl>
          <dt><img class="img-profile" src="<?=$value['photo']?>"></dt>
          <dd>
            <strong><?=$value['nickname']?></strong> · <span class="reply-date"><?=calcStoryTime($value['created_at'])?></span><br>
            <?=$value['content']?><br>
            <a href="<?=base_url() . $url?>" target="_blank">원글보기</a> | 
            <a href="javascript:;" class="btn-reply-delete" data-idx="<?=$value['idx']?>" data-action="delete_reply">삭제</a>
          </dd>
        </dl>
        <?php endforeach; ?>
      </div>
    </div>
