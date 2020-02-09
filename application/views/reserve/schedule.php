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
                      $url = BASE_URL . '/reserve/list/' . $value['idx'];
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
      </div>
