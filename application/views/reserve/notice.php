<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <script>
        new ClipboardJS('.btn-share-url');
      </script>

      <div class="club-main">
        <div class="sub-contents">
          <div class="sub-title">
            <div class="area-title"><h2><b><?=viewStatus($notice['status'])?></b> <?=$notice['subject']?></h2></div>
            <div class="area-btn"><a href="<?=base_url()?>reserve/<?=$view['idx']?>?n=<?=$notice['idx']?>"><button type="button" class="btn btn-primary">좌석보기</button></a></div>
          </div>

          <?php if (empty($notice['plan']) && empty($notice['point']) && empty($notice['intro']) && empty($notice['timetable']) && empty($notice['information']) && empty($notice['course'])): ?>
          <div class="mt-5 mb-5 text-center">준비중</div>
          <?php endif; ?>

          <?php if (!empty($notice['plan'])): ?>
          <div class="sub-header">기획의도</div>
          <div class="sub-content"><?=$notice['plan']?></div><br>
          <?php endif; ?>
          <?php if (!empty($notice['point'])): ?>
          <div class="sub-header">산행개요</div>
          <div class="sub-content"><?=$notice['point']?></div><br>
          <?php endif; ?>
          <?php if (!empty($notice['intro'])): ?>
          <div class="sub-header">산행지소개</div>
          <div class="sub-content"><?=$notice['intro']?></div><br>
          <?php endif; ?>
          <?php if (!empty($notice['timetable'])): ?>
          <div class="sub-header">일정안내</div>
          <div class="sub-content"><?=$notice['timetable']?></div><br>
          <?php endif; ?>
          <?php if (!empty($notice['information'])): ?>
          <div class="sub-header">산행안내</div>
          <div class="sub-content"><?=$notice['information']?></div><br>
          <?php endif; ?>
          <?php if (!empty($notice['course'])): ?>
          <div class="sub-header">코스안내</div>
          <div class="sub-content"><?=$notice['course']?></div>
          <?php endif; ?>

          <div class="story-reaction">
            <button type="button" data-idx="<?=$notice['idx']?>" data-type="<?=REPLY_TYPE_NOTICE?>"><i class="fa fa-reply" aria-hidden="true"></i> 댓글 <span class="cnt-reply" data-idx="<?=$notice['idx']?>"><?=$notice['reply_cnt']?></span></button>
            <button type="button" class="btn-like<?=!empty($notice['like']) ? ' text-danger' : ''?>" data-idx="<?=$notice['idx']?>" data-type="<?=REACTION_TYPE_NOTICE?>"><i class="fa fa-heart" aria-hidden="true"></i> 좋아요 <span class="cnt-like"><?=$notice['like_cnt']?></span></button>
            <button type="button" class="btn-share" data-idx="<?=$notice['idx']?>"><i class="fa fa-share-alt" aria-hidden="true"></i> 공유하기 <span class="cnt-share"><?=$notice['share_cnt']?></span></button>
            <div class="area-share" data-idx="<?=$notice['idx']?>">
              <ul>
                <li><a href="javascript:;" class="btn-share-sns" data-idx="<?=$notice['idx']?>" data-reaction-type="<?=REACTION_TYPE_NOTICE?>" data-type="<?=SHARE_TYPE_FACEBOOK?>" data-url="https://facebook.com/sharer/sharer.php?u=<?=base_url()?>reserve/notice/<?=$view['idx']?>?n=<?=$notice['idx']?>"><img src="<?=base_url()?>public/images/icon_facebook.png"><br>페이스북</a></li>
                <li><a href="javascript:;" class="btn-share-sns" data-idx="<?=$notice['idx']?>" data-reaction-type="<?=REACTION_TYPE_NOTICE?>" data-type="<?=SHARE_TYPE_TWITTER?>" data-url="https://twitter.com/intent/tweet?url=<?=base_url()?>reserve/notice/<?=$view['idx']?>?n=<?=$notice['idx']?>"><img src="<?=base_url()?>public/images/icon_twitter.png"><br>트위터</a></li>
                <li><a href="javascript:;" class="btn-share-url" data-idx="<?=$notice['idx']?>" data-reaction-type="<?=REACTION_TYPE_NOTICE?>" data-type="<?=SHARE_TYPE_URL?>" data-trigger="click" data-placement="bottom" data-clipboard-text="<?=base_url()?>reserve/notice/<?=$view['idx']?>?n=<?=$notice['idx']?>"><img src="<?=base_url()?>public/images/icon_url.png"><br>URL</a></li>
              </ul>
            </div>
          </div>
          <div class="story-reply mt-4 reply-type-<?=REPLY_TYPE_NOTICE?>" data-idx="<?=$notice['idx']?>">
            <div class="story-reply-content">
              <?=$listReply?>
            </div>
            <form method="post" action="<?=base_url()?>story/insert_reply/<?=$view['idx']?>" class="story-reply-input" data-idx="<?=$notice['idx']?>">
              <input type="hidden" name="storyIdx" value="<?=$notice['idx']?>">
              <input type="hidden" name="replyType" value="<?=REPLY_TYPE_NOTICE?>">
              <input type="hidden" name="replyIdx" value="">
              <textarea name="content" class="club-story-reply"></textarea>
              <button type="button" class="btn btn-primary btn-post-reply" data-idx="<?=$notice['idx']?>">댓글달기</button>
            </form>
          </div>
        </div>
        <div class="ad-sp mt-5">
          <!-- SP_CENTER -->
          <ins class="adsbygoogle"
            style="display:block"
            data-ad-client="ca-pub-2424708381875991"
            data-ad-slot="4319659782"
            data-ad-format="auto"
            data-full-width-responsive="true"></ins>
          <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
          </script>
        </div>
      </div>

      <script type="text/javascript">
        $(document).ready(function() {
          $('.sub-content img').click(function() {
            var $dom = $(this);
            var src = $(this).attr('src');
            var width = $(this).data('width');
            var height = $(this).data('height');
            var pswpElement = document.querySelectorAll('.pswp')[0];
            var items = [{ src: src, w: width, h: height }];

            $('.sub-content img').each(function() {
              var pushSrc = $(this).attr('src');
              if (pushSrc != src) {
                items.push({ src: pushSrc, w: $(this).data('width'), h: $(this).data('height') });
              }
            });

            var items = items;
            var options = {
              index: 0,
              bgOpacity: 0.8,
              showHideOpacity: true,
              getThumbBoundsFn: function(index) {
                var thumbnail = $dom[0],
                pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                rect = thumbnail.getBoundingClientRect(); 
                return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
              }
            };
            var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
            gallery.init();
          });
        });
      </script>
