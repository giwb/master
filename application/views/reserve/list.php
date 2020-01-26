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
        });
      </script>

      <div class="club-main">
        <div id="calendar"></div>

        <div class="ad-sp">
          <!-- CENTER -->
          <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-2424708381875991" data-ad-slot="7579588805" data-ad-format="auto" data-full-width-responsive="true"></ins>
          <script> (adsbygoogle = window.adsbygoogle || []).push({}); </script>
        </div>

        <h3><i class="fa fa-calendar" aria-hidden="true"></i> 현재 진행중인 산행</h3>
        <div class="list-schedule">
          <?php foreach ($listNotice as $value): ?>
          <a href="<?=BASE_URL?>/reserve/?n=<?=$value['idx']?>"><?=viewStatus($value['status'])?> <strong><?=$value['subject']?></strong><br><?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원 / <?=cntRes($value['idx'])?>명</a>
          <?php endforeach; ?>
        </div>
      </div>
