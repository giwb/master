<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if ($_SERVER['SERVER_PORT'] == '80') $header = 'http://'; else $header = 'https://';
$URL = $header . $_SERVER['HTTP_HOST'];
?>
<!DOCTYPE html5>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>경인웰빙산악회</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <meta property="og:title" content="경인웰빙" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="<?=$URL?>" />
  <meta property="og:image" content="<?=$URL?>/public/images/logo.png" />
  <meta property="og:description" content="인천과 부천 지역을 기반으로 토요산행과 일요산행을 매주 운행하는 안내산악회입니다. 차내 음주가무 없으며 초보자도 함께할 수 있도록 여유롭게 산행을 진행합니다.">
  <meta name="description" content="인천과 부천 지역을 기반으로 토요산행과 일요산행을 매주 운행하는 안내산악회입니다. 차내 음주가무 없으며 초보자도 함께할 수 있도록 여유롭게 산행을 진행합니다.">

  <link rel="icon" type="image/png" href="<?=$URL?>/public/images/favicon.png">
  <link rel="shortcut icon" type="image/png" href="<?=$URL?>/public/images/favicon.png">
  <link rel="apple-touch-icon" sizes="76x76" href="<?=$URL?>/public/images/apple-touch-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="120x120" href="<?=$URL?>/public/images/apple-touch-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="152x152" href="<?=$URL?>/public/images/apple-touch-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="<?=$URL?>/public/images/apple-touch-icon-180x180.png">
  <link rel="apple-touch-icon" sizes="256x256" href="<?=$URL?>/public/images/apple-touch-icon-256x256.png">

  <link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR&display=swap" rel="stylesheet">
  <link href="<?=$URL?>/public/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?=$URL?>/public/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?=$URL?>/public/lib/animate/animate.min.css" rel="stylesheet">
  <link href="<?=$URL?>/public/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="<?=$URL?>/public/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="<?=$URL?>/public/lib/lightbox/css/lightbox.min.css" rel="stylesheet">
  <link href="<?=$URL?>/public/css/fullcalendar.css" rel="stylesheet">
  
  <script src="<?=$URL?>/public/js/jquery-2.1.4.min.js" type="text/javascript"></script>
  <script src="<?=$URL?>/public/js/jquery-ui.custom.min.js" type="text/javascript"></script>

  <script src="<?=$URL?>/public/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?=$URL?>/public/lib/superfish/hoverIntent.js"></script>
  <script src="<?=$URL?>/public/lib/superfish/superfish.min.js"></script>
  <script src="<?=$URL?>/public/lib/wow/wow.min.js"></script>
  <script src="<?=$URL?>/public/lib/waypoints/waypoints.min.js"></script>
  <script src="<?=$URL?>/public/lib/counterup/counterup.min.js"></script>
  <script src="<?=$URL?>/public/lib/owlcarousel/owl.carousel.min.js"></script>
  <script src="<?=$URL?>/public/lib/isotope/isotope.pkgd.min.js"></script>
  <script src="<?=$URL?>/public/lib/lightbox/js/lightbox.min.js"></script>
  <script src="<?=$URL?>/public/lib/touchSwipe/jquery.touchSwipe.min.js"></script>
  <script src="<?=$URL?>/public/ckeditor/ckeditor.js" type="text/javascript" charset="utf-8"></script>

  <script src="<?=$URL?>/public/js/fullcalendar.js" type="text/javascript"></script>
  <script src="<?=$URL?>/public/js/main.js?<?=time()?>"></script>

  <style>
    body { font-size: 14px; font-family: "맑은 고딕"; }
    .fc-header-title { font-family: "맑은 고딕"; }
    #club a:link, #club a:active, #club a:visited { color: #FF6C00; }
    #club a:hover { color: #0AB031; }
    .fc-event { background: #fff; color: #000; }
    .status-wait { color: #999; }
    #calendar { margin: 30px 0; }
    #calendar .fc-event { padding: 5px 5px 4px 5px; }
    #calendar .fc-event.notice-status1 { color: #000; background-color: #FEECBF; }
    #calendar .fc-event.notice-status2 { color: #000; background-color: #F6C23E; }
    #calendar .fc-event.notice-status8 { background-color: #EFEFEF; }
    #calendar .fc-event.notice-status8 .fc-event-title { color: #999; text-decoration: line-through; }
    #calendar .fc-event.notice-status9 { background-color: #EFEFEF; }
    #calendar .fc-event.holiday { color: #fff; background-color: #dc3545; }
    #calendar .fc-event.dayname { color: #fff; background-color: #999; }
    #calendar .fc-view { border-bottom: 1px solid #d9d9d9; }
    #calendar .fc-week .fc-day > div .fc-day-number { padding: 1px; }
    #calendar .fc-border-separate tr.fc-last th { border: 1px solid #cdcdcd; border-width: 1px 0; font-size: 14px; padding: 0px; line-height: 1.8rem; }
  </style>

</head>
<body>

<script type="text/javascript">
  $(document).ready(function() {
    $('#calendar').fullCalendar({
      header: {
        left: '',
        center: 'title',
        right: ''
      },
      titleFormat: {
        month: 'yyyy년 MMMM',
        week: "yyyy년 MMMM",
        day: 'yyyy년 MMMM'
      },
      events: [
        <?php
          foreach ($listCalendar as $value):
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
                $url = $URL . '/reserve/index/' . $value['idx'];
              else:
                $url = 'javascript:;';
              endif;
        ?>
        {
          title: '<?=$value['starttime']?>\n<?=$value['mname']?>',
          start: new Date('<?=date('Y', $startDate)?>-<?=date('m', $startDate)?>-<?=date('d', $startDate)?>T00:00:01'),
          end: new Date('<?=date('Y', $endDate)?>-<?=date('m', $endDate)?>-<?=date('d', $endDate)?>T23:59:59'),
          url: 'javascript:;',
          className: 'notice-status<?=$value['status']?>'
        },
        <?php
            endif;
          endforeach;
        ?>
      ]
    });
    $(".fc-state-highlight").removeClass("fc-state-highlight");
  });
</script>

<div class="m-auto" style="width: 750px;">
  <div id="calendar"></div>
</div>
<div id="club" class="m-auto" style="width: 750px;">
  <div class="text-left">
    ■ <strong>현재 예약 진행중인 산행 내역</strong> <small>(<?=date('Y-m-d H:i:s')?> Updated!)</small>
    <hr style="margin: 10px 0;">
  </div>
  <?php foreach ($listNotice as $value): if ($value['status'] >= STATUS_ABLE && $value['status'] <= STATUS_CONFIRM): ?>
  <div style="font-size: 14px;">
    <strong><?=viewStatus($value['status'])?></strong> <a target="_blank" href="<?=$URL?>/reserve/index/<?=$value['idx']?>"><strong><?=$value['subject']?></strong></a><br>
    <span class="small">일시 : <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / 참가비용 : <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원 / 예약인원 : <?=cntRes($value['idx'])?>명</span><hr style="margin: 9px 0 10px 0;">
  </div>
  <?php endif; endforeach; ?>
</div><br><br>

</body>
</html>
