<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <script type="text/javascript" src="/public/js/clipboard.min.js"></script>
      <script type="text/javascript" src="/public/js/story.js"></script>
      <script type="text/javascript">(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script>

      <div id="fb-root"></div>
      <div class="club-main">
        <div class="your-story">
          <h3>안부글 수정하기</h3>
          <form id="your-story-form" method="post">
            <textarea id="club-story-content" class="edit"><?=$listStory[0]['content']?></textarea>
            <div class="area-photo">
              <?php if (!empty($listStory[0]['filename'])): ?>
              <img src="<?=PHOTO_URL?>/thumb_<?=$listStory[0]['filename']?>"><div class="icon-photo-delete" data-filename="<?=$listStory[0]['filename']?>"></div>
              <?php endif; ?>
            </div>
            <div class="area-btn">
              <input type="file" class="file">
              <button type="button" class="btn btn-photo"><i class="fa fa-camera" aria-hidden="true"></i> 사진추가</button>
              <button type="button" class="btn btn-post">수정합니다</button>
              <input type="hidden" name="clubIdx" value="<?=$view['idx']?>">
              <input type="hidden" name="userIdx" value="<?=$userData['idx']?>">
              <input type="hidden" name="page" value="story">
              <input type="hidden" name="idx" value="<?=$storyIdx?>">
            </div>
          </form>
        </div>
      </div>

      <script type="text/javascript">
        $(document).ready(function() {
          var storyIdx = '<?=$storyIdx?>';
          var replyType = '<?=REPLY_TYPE_STORY?>';
          var $dom = $('.story-reply[data-idx=' + storyIdx + ']');
          $.ajax({
            url: '/story/reply',
            data: 'clubIdx=<?=$view['idx']?>&storyIdx=' + storyIdx + '&replyType=' + replyType,
            dataType: 'json',
            type: 'post',
            success: function(result) {
              $('.story-reply-content', $dom).append(result.message);
            }
          });
          <?=!empty($listStory[0]['filename']) ? '$(".btn-photo").hide();' : ''?>
        });
      </script>
