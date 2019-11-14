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
            <div class="post-profile">
              <img class="img-profile" src="<?=base_url()?>public/images/profile.png"> <a href="#"><?=$value['user_nickname']?></a><br><?=calcDate($value['created_at'])?>
            </div>
            <div class="post-content">
  <?php if (!empty($value['filename'])): ?><img src="<?=base_url()?>public/photos/<?=$value['filename']?>"><br><?php endif; ?>
              <?=nl2br($value['content'])?>
            </div>
            <div class="post-reaction">
              <button><i class="fa fa-reply" aria-hidden="true"></i> 댓글 <?=$value['reply_cnt']?></button>
              <button><i class="fa fa-heart" aria-hidden="true"></i> 좋아요 <?=$value['like_cnt']?></button>
              <button><i class="fa fa-share-alt" aria-hidden="true"></i> 공유하기 <?=$value['share_cnt']?></button>
            </div>
          </article>
  <?php endforeach; ?>
        </div>
      </div>
