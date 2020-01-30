<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <script type="text/javascript">
        $(document).ready(function() {
          $('#calendar').fullCalendar({
            header: {
              left: 'prev',
              center: 'title',
              right: 'next'
            },
            titleFormat: {
              month: 'yyyy년 MMMM',
              week: "yyyy년 MMMM",
              day: 'yyyy년 MMMM'
            },
            events: [
              <?php
                foreach ($listNoticeCalendar as $value):
                  $startDate = strtotime($value['startdate']);
                  if (!empty($value['enddate'])): $endDate = calcEndDate($value['startdate'], $value['enddate']);
                  else: $endDate = calcEndDate($value['startdate'], $value['schedule']);
                  endif;
                  if ($value['status'] == 'schedule'):
              ?>
              {
                title: '<?=$value['mname']?>',
                start: new Date('<?=date('Y', $startDate)?>-<?=date('m', $startDate)?>-<?=date('d', $startDate)?>T00:00:00'),
                end: new Date('<?=date('Y', $endDate)?>-<?=date('m', $endDate)?>-<?=date('d', $endDate)?>T23:59:59'),
                url: 'javascript:;',
                className: '<?=$value['class']?>'
              },
              <?php
                  else:
                    if ($value['status'] >= 1):
                      $url = BASE_URL . '/reserve/?n=' . $value['idx'];
                    else:
                      $url = 'javascript:;';
                    endif;
              ?>
              {
                title: '<?=$value['status'] != STATUS_PLAN ? $value['starttime'] . "\\n" : "[계획]\\n"?><?=$value['mname']?>',
                start: new Date('<?=date('Y', $startDate)?>-<?=date('m', $startDate)?>-<?=date('d', $startDate)?>T00:00:01'),
                end: new Date('<?=date('Y', $endDate)?>-<?=date('m', $endDate)?>-<?=date('d', $endDate)?>T23:59:59'),
                url: '<?=$url?>',
                className: 'notice-status<?=$value['status']?>'
              },
              <?php
                  endif;
                endforeach;
              ?>
            ]
          });

          new ClipboardJS('.btn-share-url');
        });
      </script>
      <script type="text/javascript">(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script>

      <div id="fb-root"></div>
      <div class="club-main">
        <?php if (!empty($view['main_photo'])): ?>
        <!-- 모바일 대표 사진 -->
        <div class="d-block d-sm-none">
          <a href="javascript:;" class="photo-zoom" data-filename="<?=$view['main_photo']?>" data-width="<?=$view['main_photo_width']?>" data-height="<?=$view['main_photo_height']?>"><img src="<?=$view['main_photo']?>" class="main-image"></a>
          <?php endif; ?>
        </div>
        <div id="calendar"></div>
        <div class="your-story">
          <div class="club-story-article">
            <?=$listStory?>
          </div>
          <?php if ($maxStory['cnt'] > $perPage): ?>
          <button class="btn btn-story-more">더 보기 ▼</button>
          <?php endif; ?>
          <input type="hidden" name="p" value="1">
          <input type="hidden" name="n" value="<?=!empty($storyIdx) ? $storyIdx : ''?>">
        </div>
      </div>
