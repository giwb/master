<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main id="club">
    <div class="container-fluid club-main">
      <div class="row mt-1 mb-5">
        <div class="col-xl-8 col-md-12">

          <div class="sub-contents">
          <div class="row align-items-center">
            <div class="col-12 col-sm-9">
              <h4 class="font-weight-bold"><b><?=viewStatus($notice['status'])?></b> <?=$notice['subject']?></h4>
            </div>
            <div class="d-none d-sm-block col-sm-3 text-right">
              <?=!empty($notice['weather']) ? '<a target="_blank" href="' . $notice['weather'] . '" class="btn-custom btn-giwbblue">날씨</button></a>' : ''?>
              <a href="<?=BASE_URL?>/reserve/list/<?=$notice['idx']?>" class="btn-custom btn-giwbred btn-notice">좌석</button></a>
            </div>
          </div>
          <hr class="text-default mt-2">

          <div class="header-menu d-block-inline d-sm-none">
            <div class="header-menu-item"><a href="<?=BASE_URL?>/reserve/list/<?=$notice['idx']?>">좌석</a></div>
            <?=!empty($notice['weather']) ? '<div class="header-menu-item"><a target="_blank" href="' . $notice['weather'] . '">날씨</a></div>' : ''?>
            <div class="header-menu-item active"><a href="<?=BASE_URL?>/reserve/notice/<?=$notice['idx']?>">공지</a></div>
          </div>

          <div class="mt-4"></div>
          <div class="pt-2"></div>

          <?php if (empty($listNoticeDetail)): ?>
          <div class="sub-notice-header">산행안내</div>
          <div class="sub-content pt-4 pb-4">▶ 산행 공지를 준비중에 있습니다.</div>
          <?php else: ?>
          <?php foreach ($listNoticeDetail as $value): ?>
          <div class="sub-notice-header"><?=$value['title']?></div>
          <div class="sub-content pt-3"><?=$value['content']?></div><br>
          <?php endforeach; ?>
          <?php endif; ?>

          <div class="story-reaction">
            <button type="button" data-idx="<?=$notice['idx']?>" data-type="<?=REPLY_TYPE_NOTICE?>"><i class="fa fa-reply" aria-hidden="true"></i> 댓글 <span class="cnt-reply" data-idx="<?=$notice['idx']?>"><?=$notice['reply_cnt']?></span></button>
            <button type="button" class="btn-like<?=!empty($notice['like']) ? ' text-danger' : ''?>" data-idx="<?=$notice['idx']?>" data-type="<?=REACTION_TYPE_NOTICE?>"><i class="fa fa-heart" aria-hidden="true"></i> 좋아요 <span class="cnt-like"><?=$notice['like_cnt']?></span></button>
            <button type="button" class="btn-share" data-idx="<?=$notice['idx']?>"><i class="fa fa-share-alt" aria-hidden="true"></i> 공유하기 <span class="cnt-share"><?=$notice['share_cnt']?></span></button>
            <div class="area-share" data-idx="<?=$notice['idx']?>">
              <ul>
                <li><a href="javascript:;" id="kakao-link-btn"><img width="32" height="32" src="/public/images/icon_kakao.png"><br>카카오톡</a></li>
                <li><a href="javascript:;" class="btn-share-sns" data-idx="<?=$notice['idx']?>" data-reaction-type="<?=REACTION_TYPE_NOTICE?>" data-type="<?=SHARE_TYPE_FACEBOOK?>" data-url="https://facebook.com/sharer/sharer.php?u=<?=BASE_URL?>/reserve/notice/<?=$notice['idx']?>"><img src="/public/images/icon_facebook.png"><br>페이스북</a></li>
                <li><a href="javascript:;" class="btn-share-sns" data-idx="<?=$notice['idx']?>" data-reaction-type="<?=REACTION_TYPE_NOTICE?>" data-type="<?=SHARE_TYPE_TWITTER?>" data-url="https://twitter.com/intent/tweet?url=<?=BASE_URL?>/reserve/notice/<?=$notice['idx']?>"><img src="/public/images/icon_twitter.png"><br>트위터</a></li>
                <li><a href="javascript:;" class="btn-share-url" data-idx="<?=$notice['idx']?>" data-reaction-type="<?=REACTION_TYPE_NOTICE?>" data-type="<?=SHARE_TYPE_URL?>" data-trigger="click" data-placement="bottom" data-clipboard-text="<?=BASE_URL?>/reserve/notice/<?=$notice['idx']?>"><img src="/public/images/icon_url.png"><br>URL</a></li>
              </ul>
            </div>
          </div>
          <div class="story-reply mt-4 reply-type-<?=REPLY_TYPE_NOTICE?>" data-idx="<?=$notice['idx']?>">
            <div class="story-reply-content">
              <?=$listReply?>
            </div>
            <form method="post" action="/story/insert_reply" class="story-reply-input" data-idx="<?=$notice['idx']?>">
              <input type="hidden" name="clubIdx" value="<?=$view['idx']?>">
              <input type="hidden" name="storyIdx" value="<?=$notice['idx']?>">
              <input type="hidden" name="replyType" value="<?=REPLY_TYPE_NOTICE?>">
              <input type="hidden" name="replyIdx" value="">
              <textarea name="content" class="club-story-reply"></textarea>
              <button type="button" class="btn btn-default btn-post-reply" data-idx="<?=$notice['idx']?>">댓글달기</button>
            </form>
          </div>
        </div>
      </div>

      <script type="text/javascript">
        new ClipboardJS('.btn-share-url');
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

        // 카카오톡 공유
        Kakao.init('<?=API_KAKAO_JS?>');
        Kakao.Link.createDefaultButton({
          container: '#kakao-link-btn',
          objectType: 'feed',
          content: {
            title: '<?=$view['title']?> 산행 공유',
            description: '<?=htmlspecialchars_decode($notice['subject'])?>',
            <?php if (!empty($notice['photo'])): ?>imageUrl: '<?=BASE_URL . PHOTO_URL . 'thumb_' . $notice['photo']?>',<?php endif; ?>

            link: {
              webUrl: '<?=BASE_URL?>/reserve/notice/<?=$notice['idx']?>',
              mobileWebUrl: '<?=BASE_URL?>/reserve/notice/<?=$notice['idx']?>',
            },
          },
          buttons: [
            {
              title: '공지 페이지로 이동',
              link: {
                webUrl: '<?=BASE_URL?>/reserve/notice/<?=$notice['idx']?>',
                mobileWebUrl: '<?=BASE_URL?>/reserve/notice/<?=$notice['idx']?>',
              },
            },
          ]
        });
      </script>
