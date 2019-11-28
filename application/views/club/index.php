<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <script type="text/javascript">
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
                foreach ($listNoticeCalendar as $value) {
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
      </script>
      <script type="text/javascript" src="<?=base_url()?>public/js/story.js"></script>
      <script type="text/javascript">(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script>

      <div id="fb-root"></div>
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
              <input type="hidden" name="clubIdx" value="<?=$view['idx']?>">
              <input type="hidden" name="userIdx" value="<?=$userData['idx']?>">
              <input type="hidden" name="page" value="story">
            </div>
          </form>
  <?php foreach ($listStory as $value): ?>
          <article id="post-<?=$value['idx']?>">
            <div class="story-profile">
              <img class="img-profile" src="<?=base_url()?>public/photos/<?=$value['user_idx']?>"> <strong><?=$value['user_nickname']?></strong><br>
              <?=calcDate($value['created_at'])?><?=!empty($userData['idx']) && ($userData['idx'] == $value['created_by'] || $userData['admin'] == 1) ? ' | <a href="javascript:;" class="btn-post-delete-modal" data-idx="' . $value['idx'] . ' data-action="delete">삭제</a>' : ''?>
            </div>
            <div class="story-content">
  <?php if (!empty($value['filename'])): ?><img src="<?=base_url()?>public/photos/<?=$value['filename']?>"><br><?php endif; ?>
              <?=nl2br($value['content'])?>
            </div>
            <div class="story-reaction">
              <button type="button" class="btn-reply" data-idx="<?=$value['idx']?>"><i class="fa fa-reply" aria-hidden="true"></i> 댓글 <span class="cnt-reply"><?=$value['reply_cnt']?></span></button>
              <button type="button" class="btn-like<?=!empty($value['like']) ? ' text-danger' : ''?>" data-idx="<?=$value['idx']?>"><i class="fa fa-heart" aria-hidden="true"></i> 좋아요 <span class="cnt-like"><?=$value['like_cnt']?></span></button>
              <button type="button" class="btn-share" data-idx="<?=$value['idx']?>"><i class="fa fa-share-alt" aria-hidden="true"></i> 공유하기 <span class="cnt-share"><?=$value['share_cnt']?></span></button>
              <div class="area-share">
                <ul>
                  <li><a href="https://facebook.com/sharer/sharer.php?u=<?=base_url()?><?=$view['idx']?>"><img src="<?=base_url()?>public/images/icon_facebook.png"><br>페이스북</a></li>
                  <li><a href="https://twitter.com/intent/tweet?url=<?=base_url()?><?=$view['idx']?>"><img src="<?=base_url()?>public/images/icon_twitter.png"><br>트위터</a></li>
                  <li><a href="<?=base_url()?>story/view/<?=$view['idx']?>?n=<?=$value['idx']?>"><img src="<?=base_url()?>public/images/icon_url.png"><br>URL</a></li>
                </ul>
              </div>
            </div>
            <div class="story-reply" data-idx="<?=$value['idx']?>">
              <div class="story-reply-content">
              </div>
              <?php if (!empty($userData['idx'])): ?>
              <form method="post" action="<?=base_url()?>story/insert_reply/<?=$view['idx']?>" class="story-reply-input" data-idx="<?=$value['idx']?>">
                <input type="hidden" name="storyIdx" value="<?=$value['idx']?>">
                <textarea name="content" class="club-story-reply"></textarea>
                <button type="button" class="btn btn-primary btn-post-reply" data-idx="<?=$value['idx']?>">댓글달기</button>
              </form>
              <?php endif; ?>
            </div>
          </article>
  <?php endforeach; ?>
        </div>
      </div>
