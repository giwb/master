<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div class="club-main">
      <div class="sub-header">지난 산행보기</div>
      <form method="get" action="<?=base_url()?>club/past/<?=$view['idx']?>" class="row border no-gutters align-items-center text-center pt-2 pb-2 pr-2 mt-3 mb-3">
        <div class="col-sm-2">기간 검색</div>
        <div class="col-sm-2"><input type="text" id="startDatePicker" name="sdate" class="form-control form-control-sm" value="<?=!empty($searchData['sdate']) ? $searchData['sdate'] : ''?>"></div>
        <div class="col-sm-1">～</div>
        <div class="col-sm-2"><input type="text" id="endDatePicker" name="edate" class="form-control form-control-sm" value="<?=!empty($searchData['edate']) ? $searchData['edate'] : ''?>"></div>
        <div class="col-sm-2">키워드 검색</div>
        <div class="col-sm-2"><input type="text" name="keyword" class="form-control form-control-sm" value="<?=!empty($searchData['keyword']) ? $searchData['keyword'] : ''?>"></div>
        <div class="col-sm-1"><button class="btn btn-sm btn-primary">검색</button></div>
      </form>
      <div class="text-center border-bottom pb-3">
        <div class="row justify-content-center align-items-center">
          <div class="col-sm-1">◀</div>
          <div class="col-sm-3"><select class="form-control"><option>2019년</option></select></div>
          <div class="col-sm-2"><select class="form-control"><option>12월</option></select></div>
          <div class="col-sm-1">▶</div>
        </div>
      </div>
      <div class="list-schedule">
<?php foreach ($listPastNotice as $value): ?>
        <a href="<?=base_url()?>reserve/<?=$value['club_idx']?>?n=<?=$value['idx']?>"><strong><?=$value['subject']?></strong><br><?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / <?=number_format($value['cost'])?>원 / <?=cntRes($value['idx'])?>명</a>
<?php endforeach; ?>
      </div>
    </div>

    <link href="<?=base_url()?>public/css/jquery-ui.css" rel="stylesheet">
    <script src="<?=base_url()?>public/js/jquery-ui.min.js" type="text/javascript"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        $('#startDatePicker').datepicker({
          dateFormat: 'yy-mm-dd',
          prevText: '이전 달',
          nextText: '다음 달',
          monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
          monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
          dayNames: ['일','월','화','수','목','금','토'],
          dayNamesShort: ['일','월','화','수','목','금','토'],
          dayNamesMin: ['일','월','화','수','목','금','토'],
          showMonthAfterYear: true,
          changeMonth: true,
          changeYear: true,
          yearSuffix: '년'
        });
        $('#endDatePicker').datepicker({
          dateFormat: 'yy-mm-dd',
          prevText: '이전 달',
          nextText: '다음 달',
          monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
          monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
          dayNames: ['일','월','화','수','목','금','토'],
          dayNamesShort: ['일','월','화','수','목','금','토'],
          dayNamesMin: ['일','월','화','수','목','금','토'],
          showMonthAfterYear: true,
          changeMonth: true,
          changeYear: true,
          yearSuffix: '년'
        });
      });
    </script>
