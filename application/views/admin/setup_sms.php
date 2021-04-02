<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div id="content" class="mb-5">
          <div class="p-4">
            ** 경인웰빙 산행안내 **<br><br>
<?php
  $str = '';
  foreach ($list as $value):
    $str .= date('m/d', strtotime($value['startdate']));
    $str .= '(' . calcWeek($value['startdate']) . '요';
    $str .= calcSchedule($value['schedule']);
    $str .= '/' . $value['starttime'] . ')
';
    $str .= $value['subject'] . '
';
    $str .= number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total']) . '원

';
  endforeach;

  $str .= '***************************
';
  $str .= '[버스 승차 시간 / 6:30 기준]
';
  $location = arrLocation($viewClub['club_geton'], '06:30');
  foreach ($location as $key => $value):
    if ($key != 9):
      $str .= $value['time'] . ' ';
      $str .= $value['title'] . '
';
    endif;
  endforeach;

  $str .= '***************************
';
  $str .= '경인웰빙산악회
';
  $str .= '캔총무 010-7271-3050

';

  echo nl2br($str);
?>
            <button class="btn btn-secondary btn-copy mb-5" data-trigger="click" data-placement="top" data-clipboard-text="<?=$str?>">복사하기</button>
          </div>
        </div>

        <script src="/public/js/clipboard.min.js" type="text/javascript"></script>
        <script type="text/javascript">
          $(document).ready(function() {
            new ClipboardJS('.btn-copy');

            $('.btn-copy').click(function() {
              var $dom = $(this);
              $dom.css('color', '#000').css('background', '#fff').text('복사했습니다!');
              setTimeout(function() { $dom.css('color', '#fff').css('background', '#717384').text('복사하기'); }, 2000);
            });
          });
        </script>
