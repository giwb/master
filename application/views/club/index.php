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
                      $url = base_url() . 'reserve/' . $view['idx'] . '?n=' . $value['idx'];
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
        <div id="calendar"></div>
        <div class="ad-sp">
          <!-- SP_CENTER -->
          <ins class="adsbygoogle_sp" style="display:block" data-ad-client="ca-pub-2424708381875991" data-ad-slot="4319659782" data-ad-format="auto" data-full-width-responsive="true"></ins>
        </div>
        <script> (adsbygoogle_sp = window.adsbygoogle_sp || []).push({}); </script>
        <div class="your-story">
          <form id="your-story-form" method="post" action="<?=base_url()?>club/upload">
            <textarea id="club-story-content" placeholder="회원들에게 안부를 남겨주세요~"></textarea>
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
          <?=$listStory?>
          <div class="story-article">
          </div>
          <input type="hidden" name="p" value="1">
          <input type="hidden" name="n" value="<?=!empty($storyIdx) ? $storyIdx : ''?>">
        </div>
      </div>
