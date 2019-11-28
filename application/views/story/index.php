<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <script type="text/javascript" src="<?=base_url()?>public/js/story.js"></script>
      <script type="text/javascript">(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script>

      <div id="fb-root"></div>
      <div class="club-main">
        <div id="calendar"></div>
        <div class="your-story">
          <input type="hidden" name="clubIdx" value="<?=$view['idx']?>">
          <input type="hidden" name="userIdx" value="<?=$userData['idx']?>">
<?php if (empty($viewStory['idx'])): ?>
          <div class="text-center"><hr class="mb-5">등록된 글이 없습니다.<hr class="mt-5"><a href="<?=base_url()?><?=$view['idx']?>"><button type="button" class="btn btn-primary">메인 페이지로</button></a></div>
<?php else: ?>
          <article id="post-<?=$viewStory['idx']?>">
            <div class="story-profile">
              <img class="img-profile" src="<?=base_url()?>public/photos/<?=$viewStory['user_idx']?>"> <strong><?=$viewStory['user_nickname']?></strong><br>
              <?=calcDate($viewStory['created_at'])?><?=!empty($userData['idx']) && ($userData['idx'] == $viewStory['created_by'] || $userData['admin'] == 1) ? ' | <a href="javascript:;" class="btn-post-delete-modal" data-idx="' . $viewStory['idx'] . ' data-action="delete">삭제</a>' : ''?>
            </div>
            <div class="story-content">
              <?php if (!empty($viewStory['filename'])): ?><img src="<?=base_url()?>public/photos/<?=$viewStory['filename']?>"><br><?php endif; ?>
              <?=nl2br($viewStory['content'])?>
            </div>
            <div class="story-reaction">
              <button type="button" class="btn-reply" data-idx="<?=$viewStory['idx']?>"><i class="fa fa-reply" aria-hidden="true"></i> 댓글 <span class="cnt-reply"><?=$viewStory['reply_cnt']?></span></button>
              <button type="button" class="btn-like<?=!empty($viewStory['like']) ? ' text-danger' : ''?>" data-idx="<?=$viewStory['idx']?>"><i class="fa fa-heart" aria-hidden="true"></i> 좋아요 <span class="cnt-like"><?=$viewStory['like_cnt']?></span></button>
              <button type="button" class="btn-share" data-idx="<?=$viewStory['idx']?>"><i class="fa fa-share-alt" aria-hidden="true"></i> 공유하기 <span class="cnt-share"><?=$viewStory['share_cnt']?></span></button>
              <div class="area-share">
                <ul>
                  <li><a href="https://facebook.com/sharer/sharer.php?u=<?=base_url()?><?=$view['idx']?>"><img src="<?=base_url()?>public/images/icon_facebook.png"><br>페이스북</a></li>
                  <li><a href="https://twitter.com/intent/tweet?url=<?=base_url()?><?=$view['idx']?>"><img src="<?=base_url()?>public/images/icon_twitter.png"><br>트위터</a></li>
                  <li><a href="<?=base_url()?>story/view/<?=$view['idx']?>?n=<?=$viewStory['idx']?>"><img src="<?=base_url()?>public/images/icon_url.png"><br>URL</a></li>
                </ul>
              </div>
            </div>
            <div class="story-reply" data-idx="<?=$viewStory['idx']?>">
              <div class="story-reply-content">
              </div>
              <?php if (!empty($userData['idx'])): ?>
              <form method="post" action="<?=base_url()?>story/insert_reply/<?=$view['idx']?>" class="story-reply-input" data-idx="<?=$viewStory['idx']?>">
                <input type="hidden" name="storyIdx" value="<?=$viewStory['idx']?>">
                <textarea name="content" class="club-story-reply"></textarea>
                <button type="button" class="btn btn-primary btn-post-reply" data-idx="<?=$viewStory['idx']?>">댓글달기</button>
              </form>
              <?php endif; ?>
            </div>
          </article>
<?php endif; ?>
        </div>
      </div>
