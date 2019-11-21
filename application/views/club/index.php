<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <script>
        $(document).ready(function() {
          var date = new Date();
          var d = date.getDate();
          var m = date.getMonth();
          var y = date.getFullYear();

          /* initialize the calendar
          -----------------------------------------------------------------*/
          $('#calendar').fullCalendar({
            header: {
              left: 'prev',
              center: 'title',
              right: 'next'
            },
            selectable: true,
            selectHelper: true,
            select: function(start, end, allDay) {
              var title = prompt('Event Title:');
              var eventData;
              if (title) {
                eventData = {
                  title: title,
                  start: start,
                  end: end,
                  allDay: allDay
                };
                $('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
              }
              $('#calendar').fullCalendar('unselect');
            },
            editable: false,
            eventLimit: true, // allow "more" link when too many events
            columnFormat: {
                      month: 'ddd',
                      week: 'ddd d',
                      day: 'dddd M/d',
                      agendaDay: 'dddd d'
                  },
                  titleFormat: {
                      month: 'yyyy년 MMMM',
                      week: "yyyy년 MMMM",
                      day: 'yyyy년 MMMM'
                  },
            events: [
              <?php
                foreach ($listNotice as $value) {
                  $startDate = strtotime($value['startdate']);
                  $endDate = calcEndDate($value['startdate'], $value['schedule']);
                  $viewNoticeStatus = viewNoticeStatus($value['status']);
              ?>
              {
                title: '<?=$viewNoticeStatus?><?=$value['mname']?>',
                start: new Date('<?=date('Y', $startDate)?>-<?=date('m', $startDate)?>-<?=date('d', $startDate)?>T00:00:00'),
                end: new Date('<?=date('Y', $endDate)?>-<?=date('m', $endDate)?>-<?=date('d', $endDate)?>T23:59:59'),
                url: '<?=base_url()?>reserve/<?=$view['idx']?>?n=<?=$value['idx']?>',
                className: 'notice-status<?=$value['status']?>'
              },
              <?php
                }
              ?>
            ]
          });
        });

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
                $.each(result, function(e, v) {
                  html += '<dl><dt><img class="img-profile" src="/public/photos/' + v.member_idx + '"> ' + v.nickname + '</dt><dd>' + v.content + ' <span class="date">(' + v.created_at + ')</span></dd></dl>'
                });
                $('.story-reply-content', $dom).append(html);
                $dom.slideDown();
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
        }).on('click', '.btn-post-delete-modal', function() {
          // 삭제하기 모달
          $('#messageModal .btn').hide();
          $('#messageModal .btn-delete, #messageModal .btn-close').show();
          $('#messageModal .modal-message').text('정말로 삭제하시겠습니까?');
          $('#messageModal input[name=action]').val('delete');
          $('#messageModal input[name=delete_idx]').val($(this).data('idx'));
          $('#messageModal').modal();
        }).on('click', '.btn-delete', function() {
          // 삭제하기
          var $btn = $(this);
          $.ajax({
            url: $('input[name=base_url]').val() + 'story/' + $('#messageModal input[name=action]').val() + '/' + $('input[name=club_idx]').val(),
            data: 'idx=' + $('input[name=delete_idx]').val() + '&user_idx=' + $('input[name=user_idx]').val(),
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
            data: 'user_idx=<?=$userData['idx']?>&page=' + $('input[name=page]').val() + '&photo=' + photo + '&content=' + content,
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

      <div class="club-main">
        <div id="calendar"></div>
        <div class="your-story">
          <form id="your-story-form" method="post" action="<?=base_url()?>club/upload">
            <textarea id="club-story-content" placeholder="당신의 이야기를 들려주세요!"></textarea>
            <div class="area-photo"></div>
            <div class="area-btn">
              <input type="file" class="file">
              <button type="button" class="btn btn-photo"><i class="fa fa-camera" aria-hidden="true"></i> 사진추가</button>
              <button type="button" class="btn btn-post">등록합니다</button>
              <input type="hidden" name="club_idx" value="<?=$view['idx']?>">
              <input type="hidden" name="page" value="story">
            </div>
          </form>
  <?php foreach ($listStory as $value): ?>
          <article id="post-<?=$value['idx']?>">
            <div class="story-profile">
              <img class="img-profile" src="<?=base_url()?>public/photos/<?=$value['user_idx']?>"> <strong><?=$value['user_nickname']?></strong><br>
              <?=calcDate($value['created_at'])?><?=!empty($userData['idx']) && ($userData['idx'] == $value['created_by'] || $userData['admin'] == 1) ? ' | <a href="javascript:;" class="btn-post-delete-modal" data-idx="' . $value['idx'] . '">삭제</a>' : ''?>
            </div>
            <div class="story-content">
  <?php if (!empty($value['filename'])): ?><img src="<?=base_url()?>public/photos/<?=$value['filename']?>"><br><?php endif; ?>
              <?=nl2br($value['content'])?>
            </div>
            <div class="story-reaction">
              <button type="button" class="btn-reply" data-idx="<?=$value['idx']?>"><i class="fa fa-reply" aria-hidden="true"></i> 댓글 <span class="cnt-reply"><?=$value['reply_cnt']?></span></button>
              <button type="button" class="btn-like<?=!empty($value['like']) ? ' text-danger' : ''?>" data-idx="<?=$value['idx']?>"><i class="fa fa-heart" aria-hidden="true"></i> 좋아요 <span class="cnt-like"><?=$value['like_cnt']?></span></button>
              <button type="button" class="btn-share" data-idx="<?=$value['idx']?>"><i class="fa fa-share-alt" aria-hidden="true"></i> 공유하기 <span class="cnt-share"><?=$value['share_cnt']?></span></button>
            </div>
            <div class="story-reply" data-idx="<?=$value['idx']?>">
              <div class="story-reply-content">
              </div>
              <?php if (!empty($userData['idx'])): ?>
              <form method="post" action="<?=base_url()?>story/insert_reply/<?=$view['idx']?>" class="story-reply-input" data-idx="<?=$value['idx']?>">
                <input type="hidden" name="storyIdx" value="<?=$value['idx']?>">
                <input type="hidden" name="userIdx" value="<?=$userData['idx']?>">
                <textarea name="content" class="club-story-reply"></textarea>
                <button type="button" class="btn btn-primary btn-post-reply" data-idx="<?=$value['idx']?>">댓글달기</button>
              </form>
              <?php endif; ?>
            </div>
          </article>
  <?php endforeach; ?>
        </div>
      </div>
