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

          <div class="story-reaction">
            <button type="button" data-idx="<?=$notice['idx']?>" data-type="<?=REPLY_TYPE_STORY?>"><i class="fa fa-reply" aria-hidden="true"></i> 댓글 <span class="cnt-reply" data-idx="<?=$notice['idx']?>"><?=$notice['reply_cnt']?></span></button>
            <button type="button" class="btn-like<?=!empty($notice['like']) ? ' text-danger' : ''?>" data-idx="<?=$notice['idx']?>" data-type="<?=REACTION_TYPE_NOTICE?>"><i class="fa fa-heart" aria-hidden="true"></i> 좋아요 <span class="cnt-like"><?=$notice['like_cnt']?></span></button>
            <button type="button" class="btn-share" data-idx="<?=$notice['idx']?>"><i class="fa fa-share-alt" aria-hidden="true"></i> 공유하기 <span class="cnt-share"><?=$notice['share_cnt']?></span></button>
            <div class="area-share">
              <ul>
                <li><a href="javascript:;" class="btn-share-sns" data-idx="<?=$notice['idx']?>" data-reaction-type="<?=REACTION_TYPE_NOTICE?>" data-type="<?=SHARE_TYPE_FACEBOOK?>" data-url="https://facebook.com/sharer/sharer.php?u=<?=base_url()?>story/view/<?=$view['idx']?>?n=<?=$notice['idx']?>"><img src="<?=base_url()?>public/images/icon_facebook.png"><br>페이스북</a></li>
                <li><a href="javascript:;" class="btn-share-sns" data-idx="<?=$notice['idx']?>" data-reaction-type="<?=REACTION_TYPE_NOTICE?>" data-type="<?=SHARE_TYPE_TWITTER?>" data-url="https://twitter.com/intent/tweet?url=<?=base_url()?>story/view/<?=$view['idx']?>?n=<?=$notice['idx']?>"><img src="<?=base_url()?>public/images/icon_twitter.png"><br>트위터</a></li>
                <li><a href="javascript:;" class="btn-share-url" data-idx="<?=$notice['idx']?>" data-reaction-type="<?=REACTION_TYPE_NOTICE?>" data-type="<?=SHARE_TYPE_URL?>" data-trigger="click" data-placement="bottom" data-clipboard-text="<?=base_url()?>story/view/<?=$view['idx']?>?n=<?=$notice['idx']?>"><img src="<?=base_url()?>public/images/icon_url.png"><br>URL</a></li>
              </ul>
            </div>
          </div>
          <div class="story-reply reply-type-<?=REPLY_TYPE_NOTICE?>" data-idx="<?=$notice['idx']?>">
            <div class="story-reply-content">
<?php foreach ($listReply as $value): ?>
              <dl><dt><img class="img-profile" src="/public/photos/<?=$value['created_by']?>"> <?=$value['nickname']?></dt><dd><?=$value['content']?> <span class="date">(<?=$value['created_at']?>)</span><?=$userData['idx'] == $value['created_by'] || $userData['admin'] == 1 ? ' | <a href="javascript:;" class="btn-post-delete-modal" data-idx="' . $value['idx'] . '" data-action="delete_reply">삭제</a>' : ''?></dd></dl>
<?php endforeach; ?>
            </div>
            <form method="post" action="<?=base_url()?>story/insert_reply/<?=$view['idx']?>" class="story-reply-input" data-idx="<?=$notice['idx']?>">
              <input type="hidden" name="storyIdx" value="<?=$notice['idx']?>">
              <input type="hidden" name="replyType" value="<?=REPLY_TYPE_NOTICE?>">
              <textarea name="content" class="club-story-reply"></textarea>
              <button type="button" class="btn btn-primary btn-post-reply" data-idx="<?=$notice['idx']?>">댓글달기</button>
            </form>
          </div>
        </div>
      </div>
