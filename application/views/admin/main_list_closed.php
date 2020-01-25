<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">다녀온 산행 목록</h1>
        </div>
        <div class="w-100 border mt-2 mb-3 p-3">
          <form id="formSearch" method="get" action="/admin/main_list_closed" class="row align-items-center text-center">
            <div class="col-sm-1 pl-0 pr-0">검색 시작일</div>
            <div class="col-sm-2 pl-0 pr-0"><input id="startDatePicker" type="text" name="sdate" class="form-control" value="<?=!empty($search['sdate']) ? $search['sdate'] : ''?>"></div>
            <div class="col-sm-1 pl-0 pr-0">검색 종료일</div>
            <div class="col-sm-2 pl-0 pr-0"><input id="endDatePicker" type="text" name="edate" class="form-control" value="<?=!empty($search['edate']) ? $search['edate'] : ''?>"></div>
            <div class="col-sm-1 pl-0 pr-0">키워드 검색</div>
            <div class="col-sm-2 pl-0 pr-0"><input type="text" name="subject" class="form-control" value="<?=!empty($search['subject']) ? $search['subject'] : ''?>"></div>
            <div class="col-sm-3 text-left"><button class="btn btn-primary">검색</button></div>
          </form>
        </div>
        <div class="row align-items-center border-bottom mb-3 pb-3">
          <div class="col-lg-4"></div>
          <div class="col-lg-1 text-right"><a href="/admin/main_list_closed?<?=$search['prev']?>">◀</a></div>
          <div class="col-lg-1">
            <select name="syear" class="form-control">
              <?php foreach (range($search['syear'], 2010) as $value): ?>
                <option<?=!empty($search['syear']) && $search['syear'] == $value ? ' selected' : ''?> value='<?=$value?>'><?=$value?>년</option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-lg-1">
            <select name="smonth" class="form-control btn-search-month">
              <?php foreach (range(1, 12) as $value): ?>
              <option<?=!empty($search['smonth']) && $search['smonth'] == $value ? ' selected' : ''?> value='<?=strlen($value) < 2 ? '0' . $value : $value?>'><?=$value?>월</option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-lg-1"><a href="/admin/main_list_closed?<?=$search['next']?>">▶</a></div>
          <div class="col-lg-4"></div>
        </div>
<?php
  foreach ($listClosed as $value) {
?>
        <div class="row mb-3">
          <div class="col-lg-11"><b><?=viewStatus($value['status'])?></b> <a target="_blank" href="/admin/main_view_progress/<?=$value['idx']?>"><?=$value['subject']?></a><br>산행일시 : <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / 참가비용 : <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원</div>
          <div class="col-lg-1 text-right">0원<br><?=cntRes($value['idx'])?>명</div>
        </div>
<?php
  }
?>
      </div>
    </div>

    <link href="/public/css/jquery-ui.css" rel="stylesheet">
    <script src="/public/js/jquery-ui.min.js" type="text/javascript"></script>
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
