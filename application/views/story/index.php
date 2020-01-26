<?php
  foreach ($listStory as $value):
    if (file_exists(PHOTO_PATH . $value['created_by'])) {
      $size = getImageSize(PHOTO_PATH . $value['created_by']);
      $value['photo'] = PHOTO_URL . $value['created_by'];
      $value['photo_width'] = $size[0];
      $value['photo_height'] = $size[1];
    } else {
      $value['photo'] = '/public/images/user.png';
      $value['photo_width'] = 64;
      $value['photo_height'] = 64;
    }

    if (!empty($value['filename']) && file_exists(PHOTO_PATH . $value['filename'])) {
      $size = getImageSize(PHOTO_PATH . $value['filename']);
      $value['file_width'] = $size[0];
      $value['file_height'] = $size[1];
    }
?>
          <article id="post-<?=$value['idx']?>">
            <div class="story-container">
              <div class="story-profile">
                <img class="img-profile photo-zoom" src="<?=$value['photo']?>" data-filename="<?=$value['photo']?>" data-width="<?=$value['photo_width']?>" data-height="<?=$value['photo_height']?>"> <strong><?=$value['user_nickname']?></strong><br>
                <a href="<?=BASE_URL?>/story/view/?n=<?=$value['idx']?>" class="story-date"><?=calcStoryTime($value['created_at'])?><?=!empty($value['updated_at']) ? ' 작성, ' . calcStoryTime($value['updated_at']) . ' 수정' : ''?></a><?=!empty($userData['idx']) && ($userData['idx'] == $value['created_by'] || $userData['admin'] == 1) ? ' <a href="' . BASE_URL . '/story/edit/?n=' . $value['idx'] . '">[수정]</a> <a href="javascript:;" class="btn-post-delete-modal" data-idx="' . $value['idx'] . '" data-action="delete">[삭제]</a>' : ''?>
              </div>
              <div class="story-content">
                <?php if (!empty($value['filename'])): ?><img class="story-photo" src="<?=PHOTO_URL?>thumb_<?=$value['filename']?>" data-filename="<?=PHOTO_URL?><?=$value['filename']?>" data-width="<?=$value['file_width']?>" data-height="<?=$value['file_height']?>"><br><?php endif; ?>
                <?=nl2br(strip_tags($value['content']))?>
              </div>
              <div class="story-reaction">
                <button type="button" class="btn-reply" data-idx="<?=$value['idx']?>" data-type="<?=REPLY_TYPE_STORY?>"><i class="fa fa-reply" aria-hidden="true"></i> 댓글 <span class="cnt-reply" data-idx="<?=$value['idx']?>"><?=$value['reply_cnt']?></span></button>
                <button type="button" class="btn-like<?=!empty($value['like']) ? ' text-danger' : ''?>" data-idx="<?=$value['idx']?>" data-type="<?=REACTION_TYPE_STORY?>"><i class="fa fa-heart" aria-hidden="true"></i> 좋아요 <span class="cnt-like"><?=$value['like_cnt']?></span></button>
                <button type="button" class="btn-share" data-idx="<?=$value['idx']?>" data-type="<?=REACTION_TYPE_STORY?>"><i class="fa fa-share-alt" aria-hidden="true"></i> 공유 <span class="cnt-share"><?=$value['share_cnt']?></span></button>
                <div class="area-share" data-idx="<?=$value['idx']?>">
                  <ul>
                    <li><a href="javascript:;" class="btn-share-sns" data-idx="<?=$value['idx']?>" data-reaction-type="<?=REACTION_TYPE_STORY?>" data-type="<?=SHARE_TYPE_FACEBOOK?>" data-url="https://facebook.com/sharer/sharer.php?u=<?=BASE_URL?>/story/view/?n=<?=$value['idx']?>"><img src="/public/images/icon_facebook.png"><br>페이스북</a></li>
                    <li><a href="javascript:;" class="btn-share-sns" data-idx="<?=$value['idx']?>" data-reaction-type="<?=REACTION_TYPE_STORY?>" data-type="<?=SHARE_TYPE_TWITTER?>" data-url="https://twitter.com/intent/tweet?url=<?=BASE_URL?>/story/view/?n=<?=$value['idx']?>"><img src="/public/images/icon_twitter.png"><br>트위터</a></li>
                    <li><a href="javascript:;" class="btn-share-url" data-idx="<?=$value['idx']?>" data-reaction-type="<?=REACTION_TYPE_STORY?>" data-type="<?=SHARE_TYPE_URL?>" data-trigger="click" data-placement="bottom" data-clipboard-text="<?=BASE_URL?>/story/view/?n=<?=$value['idx']?>"><img src="/public/images/icon_url.png"><br>URL</a></li>
                  </ul>
                </div>
              </div>
              <div class="story-reply" data-idx="<?=$value['idx']?>">
                <div class="story-reply-content">
                </div>
                <form method="post" action="/story/insert_reply" class="story-reply-input" data-idx="<?=$value['idx']?>">
                  <input type="hidden" name="clubIdx" value="<?=$view['idx']?>">
                  <input type="hidden" name="storyIdx" value="<?=$value['idx']?>">
                  <input type="hidden" name="replyType" value="<?=REPLY_TYPE_STORY?>">
                  <input type="hidden" name="replyIdx" value="">
                  <textarea name="content" class="club-story-reply"></textarea>
                  <button type="button" class="btn btn-primary btn-post-reply" data-idx="<?=$value['idx']?>">댓글달기</button>
                </form>
              </div>
            </div>
          </article>
<?php endforeach; ?>
