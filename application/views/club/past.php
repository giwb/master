<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div class="club-main">
      <div class="mt-3 mb-3">
        <?php if ($view['idx'] == 1): ?>
        <div class="row-category">
          <div class="row border-right small text-center m-0 p-0">
            <a href="<?=BASE_URL?>" class="col-6 border-left pt-2 pb-2 pl-0 pr-0">TOP</a>
            <a href="<?=BASE_URL?>/club/about/?p=top" class="col-6 border-left pt-2 pb-2 pl-0 pr-0<?=strstr($_SERVER['REQUEST_URI'], '=top') ? ' active' : ''?>">산악회 소개</a>
            <a href="<?=BASE_URL?>/club/about/?p=guide" class="col-6 border-left pt-2 pb-2 pl-0 pr-0<?=strstr($_SERVER['REQUEST_URI'], '=guide') ? ' active' : ''?>">등산안내인</a>
            <a href="<?=BASE_URL?>/club/past" class="col-6 border-left pt-2 pb-2 pl-0 pr-0<?=strstr($_SERVER['REQUEST_URI'], '/past') ? ' active' : ''?>">지난 산행보기</a>
            <a href="<?=BASE_URL?>/club/about/?p=howto" class="col-6 border-left pt-2 pb-2 pl-0 pr-0<?=strstr($_SERVER['REQUEST_URI'], '=howto') ? ' active' : ''?>">이용안내</a>
            <a href="<?=BASE_URL?>/club/about/?p=mountain" class="col-6 border-left pt-2 pb-2 pl-0 pr-0<?=strstr($_SERVER['REQUEST_URI'], '=mountain') ? ' active' : ''?>">100대명산</a>
            <a href="<?=BASE_URL?>/club/about/?p=place" class="col-6 border-top border-left pt-2 pb-2 pl-0 pr-0<?=strstr($_SERVER['REQUEST_URI'], '=place') ? ' active' : ''?>">100대명소</a>
            <a href="<?=BASE_URL?>/club/auth" class="col-6 border-top border-left pt-2 pb-2 pl-0 pr-0<?=strstr($_SERVER['REQUEST_URI'], '/auth') ? ' active' : ''?>">인증현황</a>
          </div>
        </div>
        <?php else: ?>
        <?php endif; ?>
      </div>
      <h2 class="sub-header"><?=$pageTitle?></h2>
      <form id="formSearch" method="get" action="<?=BASE_URL?>/club/past" class="row border no-gutters align-items-center text-center pt-2 pb-2 pr-2 mt-3 mb-3">
        <ul class="box-past-search">
          <li>기간검색</li>
          <li><input type="text" id="startDatePicker" name="sdate" class="form-control form-control-sm" value="<?=!empty($searchData['sdate']) ? $searchData['sdate'] : ''?>"></li>
          <li>～</li>
          <li><input type="text" id="endDatePicker" name="edate" class="form-control form-control-sm" value="<?=!empty($searchData['edate']) ? $searchData['edate'] : ''?>"></li>
          <li>키워드검색</li>
          <li><input type="text" name="keyword" class="form-control form-control-sm" value="<?=!empty($searchData['keyword']) ? $searchData['keyword'] : ''?>"></li>
          <li><button class="btn btn-sm btn-<?=$view['main_color']?>">검색</button></li>
        </ul>
      </form>
      <ul class="box-past-title">
        <li><a href="<?=BASE_URL?>/club/past/?<?=$searchData['prev']?>">◀</a></li>
        <li>
          <select name="syear" class="form-control">
            <?php foreach (range($searchData['syear'], 2010) as $value): ?>
              <option<?=!empty($searchData['syear']) && $searchData['syear'] == $value ? ' selected' : ''?> value='<?=$value?>'><?=$value?>년</option>
            <?php endforeach; ?>
          </select>
        </li>
        <li>
          <select name="smonth" class="form-control btn-search-month">
            <?php foreach (range(1, 12) as $value): ?>
            <option<?=!empty($searchData['smonth']) && $searchData['smonth'] == $value ? ' selected' : ''?> value='<?=strlen($value) < 2 ? '0' . $value : $value?>'><?=$value?>월</option>
            <?php endforeach; ?>
          </select>
        </li>
        <li><a href="<?=BASE_URL?>/club/past/?<?=$searchData['next']?>">▶</a></li>
      </ul>
      <div class="list-schedule">
        <?php if (empty($listPastNotice)): ?>
          <div class="text-center m-5">검색된 정보가 없습니다.</div>
        <?php else : ?>
        <?php foreach ($listPastNotice as $value): ?>
        <a href="<?=BASE_URL?>/reserve/list/<?=$value['idx']?>"><strong><?=$value['subject']?></strong><br><?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원 / <?=cntRes($value['idx'])?>명</a>
        <?php endforeach; ?>
        <?php endif; ?>
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
