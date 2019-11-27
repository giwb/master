<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <script>
        $(document).on('click', '.btn-reply', function() {
          // 댓글 열기
          var storyIdx = $(this).data('idx');
          var $dom = $('.story-reply[data-idx=' + storyIdx + ']');
          var html = '';

          if ($dom.css('display') == 'none') {
            $.ajax({
              url: $('input[name=base_url]').val() + 'story/reply/' + $('input[name=club_idx]').val(),
              data: 'storyIdx=' + storyIdx + '&userIdx=<?=$userData['idx']?>',
              dataType: 'json',
              type: 'post',
              success: function(result) {
                if (result.error == 1) {
                  $.openMsgModal(result.message);
                } else {
                  $('.story-reply-content', $dom).append(result.message);
                  $dom.slideDown();
                }
              }
            });
          } else {
            $dom.slideUp();
          }
        }).on('click', '.btn-post-reply', function() {
          // 댓글 달기
          var $btn = $(this);
          var storyIdx = $btn.data('idx');
          var $form = $('.story-reply-input[data-idx=' + storyIdx + ']');
          var formData = new FormData($form[0]);

          $.ajax({
            url: $form.attr('action'),
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            type: 'post',
            beforeSend: function() {
              $btn.css('opacity', '0.5').prop('disabled', true).text('등록중..');
            },
            success: function(result) {
              $btn.css('opacity', '1').prop('disabled', false).text('댓글달기');
              if (result.error == 1) {
                $.openMsgModal('댓글 등록에 실패했습니다. 다시 시도해주세요.');
              } else {
                var html = '<dl><dt><img class="img-profile" src="/public/photos/<?=$userData['idx']?>"> <?=$userData['nickname']?></dt><dd>' + result.content + ' <span class="date">(' + result.created_at + ')</span></dd></dl>'
                $('.story-reply[data-idx=' + storyIdx + '] .story-reply-content').append(html);
                $('.club-story-reply').val('');
                $('#post-' + storyIdx + ' .cnt-reply').text(result.reply_cnt);
              }
            }
          });
        }).on('click', '.btn-like', function() {
          // 좋아요
          <?php if (empty($userData['idx'])): ?>
          $.openMsgModal('로그인을 해주세요.');
          <?php else: ?>
          var $dom = $(this);
          $.ajax({
            url: $('input[name=base_url]').val() + 'story/like/' + $('input[name=club_idx]').val(),
            data: 'storyIdx=' + $(this).data('idx') + '&userIdx=<?=$userData['idx']?>',
            dataType: 'json',
            type: 'post',
            success: function(result) {
              $dom.find('.cnt-like').text(result.count);
              if (result.type == 1) $dom.addClass('text-danger'); else $dom.removeClass('text-danger');
            }
          });
          <?php endif; ?>
        }).on('click', '.btn-share', function() {
          // 공유하기
          var $dom = $('.area-share');
          if ($dom.css('display') == 'none') {
            $dom.show();
          } else {
            $dom.hide();
          }
        }).on('click', '.btn-post-delete-modal', function() {
          // 삭제하기 모달
          $('#messageModal .btn').hide();
          $('#messageModal .btn-delete, #messageModal .btn-close').show();
          $('#messageModal .modal-message').text('정말로 삭제하시겠습니까?');
          $('#messageModal input[name=action]').val($(this).data('action'));
          $('#messageModal input[name=delete_idx]').val($(this).data('idx'));
          $('#messageModal').modal();
        }).on('click', '.btn-delete', function() {
          // 삭제하기
          var $btn = $(this);
          $.ajax({
            url: $('input[name=base_url]').val() + 'story/' + $('#messageModal input[name=action]').val() + '/' + $('input[name=club_idx]').val(),
            data: 'idx=' + $('input[name=delete_idx]').val(),
            dataType: 'json',
            type: 'post',
            beforeSend: function() {
              $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
            },
            success: function(result) {
              if (result.error == 1) {
                $btn.css('opacity', '1').prop('disabled', false).text('삭제합니다');
                $('#messageModal .btn').hide();
                $('#messageModal .btn-refresh, #messageModal .btn-close').show();
                $('#messageModal .modal-message').text(result.message);
                $('#messageModal').modal();
              } else {
                location.reload();
              }
            }
          });
        }).on('click', '.btn-post', function() {
          <?php if (empty($userData['idx'])): ?>
          $.openMsgModal('로그인을 해주세요.');
          <?php else: ?>
          // 스토리 작성
          var $dom = $(this);
          var content = $('#club-story-content').val();
          var photo = $('.icon-photo-delete').data('filename');
          var page = $('input[name=page]').val();

          if (content == '') { return false; }
          if (typeof(photo) == 'undefined') { photo = ''; }

          $.ajax({
            url: $('input[name=base_url]').val() + 'story/insert/' + $('input[name=club_idx]').val(),
            data: 'page=' + $('input[name=page]').val() + '&photo=' + photo + '&content=' + content,
            dataType: 'json',
            type: 'post',
            beforeSend: function() {
              $dom.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
              $('#club-story-content').prop('disabled', true);
            },
            success: function(result) {
              if (result.error == 1) {
                $dom.css('opacity', '1').prop('disabled', false).text('등록합니다');
                $('#club-story-content').prop('disabled', false).val('');
                $('#messageModal .btn').hide();
                $('#messageModal .btn-refresh, #messageModal .btn-close').show();
                $('#messageModal .modal-message').text(result.message);
                $('#messageModal').modal();
              } else {
                /*$('#club-story-content').prop('disabled', false).val('');
                $dom.css('opacity', '1').prop('disabled', false).text('등록합니다');*/
                location.reload();
              }
            }
          });
          <?php endif; ?>
        }).on('click', '.btn-photo', function() {
          // 사진 선택
          $(this).prev().click();
        }).on('click', '.icon-photo-delete', function() {
          // 사진 삭제
          var page = $('input[name=page]').val();

          $.ajax({
            url: $('input[name=base_url]').val() + 'story/delete_photo/' + $('input[name=club_idx]').val(),
            data: 'page=' + $('input[name=page]').val() + '&photo=' + $(this).data('filename'),
            dataType: 'json',
            type: 'post',
            success: function(result) {
              if (result.error == 0) {
                $('.area-photo').empty();
                $('.btn-photo').show();
              }
            }
          });
        });
      </script>
      <script>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script>

      <div id="fb-root"></div>
      <div class="club-main">
        <div id="calendar"></div>
        <div class="your-story">
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
                <input type="hidden" name="userIdx" value="<?=$userData['idx']?>">
                <textarea name="content" class="club-story-reply"></textarea>
                <button type="button" class="btn btn-primary btn-post-reply" data-idx="<?=$viewStory['idx']?>">댓글달기</button>
              </form>
              <?php endif; ?>
            </div>
          </article>
<?php endif; ?>
        </div>
      </div>
