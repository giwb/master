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
        {
          title: 'All Day Event',
          start: '2019-10-01'
        },
        {
          title: 'Long Event',
          start: '2019-10-07',
          end: '2019-10-10'
        },
        {
          id: 999,
          title: 'Repeating Event',
          start: '2019-10-09T16:00:00'
        },
        {
          id: 999,
          title: 'Repeating Event',
          start: '2019-10-16T16:00:00'
        },
        {
          title: 'Conference',
          start: '2019-10-11',
          end: '2019-10-13'
        },
        {
          title: 'Meeting',
          start: '2019-10-12T10:30:00',
          end: '2019-10-12T12:30:00'
        },
        {
          title: 'Lunch',
          start: '2019-10-12T12:00:00'
        },
        {
          title: 'Meeting',
          start: '2019-10-12T14:30:00'
        },
        {
          title: 'Happy Hour',
          start: '2019-10-12T17:30:00'
        },
        {
          title: 'Dinner',
          start: '2019-10-12T20:00:00'
        },
        {
          title: 'Birthday Party',
          start: '2019-10-13T07:00:00'
        },
        {
          title: 'Click for Google',
          url: 'http://google.com/',
          start: '2019-10-28'
        }
      ]
    });

  });
</script>

<section id="club">
  <div class="container">
    <div class="club-left">
<?php if (!empty($view['photo'][0])): ?>
      <!-- 대표 사진 -->
      <img src="<?=base_url()?><?=PHOTO_URL?><?=$view['photo'][0]?>">
<?php endif; ?>
      <h3><?=$view['title']?></h3>
      <?=$view['homepage'] != '' ? '<a target="_blank" href="' . $view['homepage'] . '" class="url">' . $view['homepage'] . '</a>' : ''?>
      <ul class="navi">
        <li><a href="#"><i class="fa fa-home" aria-hidden="true"></i> TOP</a></li>
        <li><a href="#"><i class="fa fa-calendar" aria-hidden="true"></i> 산행일정</a></li>
        <li><a href="#"><i class="fa fa-camera" aria-hidden="true"></i> 사진첩</a></li>
      </ul>
      <div class="desc">
        <?=$view['content']?>
        ・설립년도 : <?=$view['establish']?>년<br>
        ・단체유형 : <?=getClubType($view['club_type'])?><br>
        ・제공사항 : <?=getClubOption($view['club_option'])?> / <?=$view['club_option_text']?><br>
        ・운행주간 : <?=getClubCycle($view['club_cycle'])?><br>
        ・운행시기 : <?=getClubWeek($view['club_week'])?><br>
        ・승차위치 : <?=getClubGetonoff($view['club_geton'])?><br>
        ・하차위치 : <?=getClubGetonoff($view['club_getoff'])?><br>
        ・연락처 : <?=$view['phone']?><br>
      </div>
      <div class="banner">배너광고 #1</div>
      <div class="banner">배너광고 #2</div>
    </div>
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
    <div class="club-right">
      <h3><i class="fa fa-calendar" aria-hidden="true"></i> 이번 주 산행</h3>
      <ul class="club-schedule">
        <li><a href="#"><strong>점봉산 곰배령 야생화</strong></a><br>8월 31일 (토) 6:00 / 32,000원 / 총 19명</li>
        <li><a href="#"><strong>미약골 계곡트레킹 & 효석문화제</strong></a><br>8월 31일 (토) 6:00 / 32,000원 / 총 19명</li>
        <li><a href="#"><strong>추석연휴 자월도 2박3일 섬여행</strong></a><br>8월 31일 (토) 6:00 / 32,000원 / 총 19명</li>
        <li><a href="#"><strong>불갑산 상사화축제</strong></a><br>8월 31일 (토) 6:00 / 32,000원 / 총 19명</li>
        <li><a href="#"><strong>금대봉 & 대덕산 야생화</strong></a><br>8월 31일 (토) 6:00 / 32,000원 / 총 19명</li>
      </ul>
      <h3><i class="fa fa-camera" aria-hidden="true"></i> 사진첩</h3>
      <ul class="club-gallery">
        <li><a href="#"><img src="<?=base_url()?>public/images/sample_photo_2.jpg"></a></li>
        <li><a href="#"><img src="<?=base_url()?>public/images/sample_photo_3.jpg"></a></li>
        <li><a href="#"><img src="<?=base_url()?>public/images/sample_photo_4.jpg"></a></li>
<?php
    //foreach ($view['photo'] as $key => $value):
      //if ($key != 0):
?>
        <!--<li><a href="#"><img src="<?=base_url()?><?=PHOTO_URL?><?=$value?>"></a></li>-->
<?php
      //endif;
    //endforeach;
?>
      </ul>
    </div>
  </div>
</section>

<script type="text/javascript" src="/public/vendors/chart.js/dist/Chart.bundle.min.js"></script>
