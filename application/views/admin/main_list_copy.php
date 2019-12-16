<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html5>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <title>경인웰빙산악회</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="height=device-height, width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">

  <meta property="og:title" content="경인웰빙" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://giwb.kr/" />
  <meta property="og:image" content="<?=base_url()?>public/images/logo.png" />
  <meta property="og:description" content="인천과 부천 지역을 기반으로 토요산행과 일요산행을 매주 운행하는 안내산악회입니다. 차내 음주가무 없으며 초보자도 함께할 수 있도록 여유롭게 산행을 진행합니다.">
  <meta name="description" content="인천과 부천 지역을 기반으로 토요산행과 일요산행을 매주 운행하는 안내산악회입니다. 차내 음주가무 없으며 초보자도 함께할 수 있도록 여유롭게 산행을 진행합니다.">

  <link rel="icon" type="image/png" href="<?=base_url()?>public/images/favicon.png">
  <link rel="shortcut icon" type="image/png" href="<?=base_url()?>public/images/favicon.png">
  <link rel="apple-touch-icon" sizes="76x76" href="<?=base_url()?>public/images/apple-touch-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="120x120" href="<?=base_url()?>public/images/apple-touch-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="152x152" href="<?=base_url()?>public/images/apple-touch-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="<?=base_url()?>public/images/apple-touch-icon-180x180.png">
  <link rel="apple-touch-icon" sizes="256x256" href="<?=base_url()?>public/images/apple-touch-icon-256x256.png">

  <link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR&display=swap" rel="stylesheet">
  <link href="<?=base_url()?>public/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?=base_url()?>public/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?=base_url()?>public/lib/animate/animate.min.css" rel="stylesheet">
  <link href="<?=base_url()?>public/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="<?=base_url()?>public/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="<?=base_url()?>public/lib/lightbox/css/lightbox.min.css" rel="stylesheet">
  <link href="<?=base_url()?>public/css/fullcalendar.css" rel="stylesheet">
  <link href="<?=base_url()?>public/css/fullcalendar.print.css" rel="stylesheet">
  <link href="<?=base_url()?>public/css/style.css?<?=time()?>" rel="stylesheet">

  <script src="<?=base_url()?>public/js/jquery-2.1.4.min.js" type="text/javascript"></script>
  <script src="<?=base_url()?>public/js/jquery-ui.custom.min.js" type="text/javascript"></script>

  <script src="<?=base_url()?>public/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?=base_url()?>public/lib/superfish/hoverIntent.js"></script>
  <script src="<?=base_url()?>public/lib/superfish/superfish.min.js"></script>
  <script src="<?=base_url()?>public/lib/wow/wow.min.js"></script>
  <script src="<?=base_url()?>public/lib/waypoints/waypoints.min.js"></script>
  <script src="<?=base_url()?>public/lib/counterup/counterup.min.js"></script>
  <script src="<?=base_url()?>public/lib/owlcarousel/owl.carousel.min.js"></script>
  <script src="<?=base_url()?>public/lib/isotope/isotope.pkgd.min.js"></script>
  <script src="<?=base_url()?>public/lib/lightbox/js/lightbox.min.js"></script>
  <script src="<?=base_url()?>public/lib/touchSwipe/jquery.touchSwipe.min.js"></script>
  <script src="<?=base_url()?>public/ckeditor/ckeditor.js" type="text/javascript" charset="utf-8"></script>

  <script src="<?=base_url()?>public/js/fullcalendar.js" type="text/javascript"></script>
  <script src="<?=base_url()?>public/js/main.js?<?=time()?>"></script>

</head>
<body>

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
                $url = base_url() . 'reserve/' . '?n=' . $value['idx'];
              else:
                $url = 'javascript:;';
              endif;
        ?>
        {
          title: '<?=$value['starttime']?>\n<?=$value['mname']?>',
          start: new Date('<?=date('Y', $startDate)?>-<?=date('m', $startDate)?>-<?=date('d', $startDate)?>T00:00:01'),
          end: new Date('<?=date('Y', $endDate)?>-<?=date('m', $endDate)?>-<?=date('d', $endDate)?>T23:59:59'),
          url: '<?=$url?>',
          className: 'notice-status<?=$value['status']?>'
        },
        <?php
            endif;
          endforeach;
        ?>
      ],
      eventClick: function(event) {
        if (event.url) {
          window.open(event.url, '_blank');
          return false;
        }
      }
    });
  });
</script>

<div id="club" class="container m-auto">
  <div id="calendar"></div>

  ■ <strong>현재 예약 진행중인 산행 내역</strong> <small>(<?=date('Y-m-d H:i:s')?> Updated!)</small>
  <div class="list-schedule border-top mt-3 mb-5">
    <?php foreach ($listNotice as $value): ?>
      <a target="_blank" href="<?=base_url()?>reserve/?n=<?=$value['idx']?>"><?=viewStatus($value['status'])?> <strong><?=$value['subject']?></strong><br><?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원</a>
    <?php endforeach; ?>
  </div>
</div>

</body>
</html>
