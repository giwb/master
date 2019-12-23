<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div class="club-main">
      <div class="sub-header">지난 산행보기</div>
      <form id="formSearch" method="get" action="<?=base_url()?>club/past/<?=$view['idx']?>" class="row border no-gutters align-items-center text-center pt-2 pb-2 pr-2 mt-3 mb-3">
        <ul class="box-past-search">
          <li>기간검색</li>
          <li><input type="text" id="startDatePicker" name="sdate" class="form-control form-control-sm" value="<?=!empty($searchData['sdate']) ? $searchData['sdate'] : ''?>"></li>
          <li>～</li>
          <li><input type="text" id="endDatePicker" name="edate" class="form-control form-control-sm" value="<?=!empty($searchData['edate']) ? $searchData['edate'] : ''?>"></li>
          <li>키워드검색</li>
          <li><input type="text" name="keyword" class="form-control form-control-sm" value="<?=!empty($searchData['keyword']) ? $searchData['keyword'] : ''?>"></li>
          <li><button class="btn btn-sm btn-primary">검색</button></li>
        </ul>
      </form>
      <ul class="box-past-title">
        <li><a href="<?=base_url()?>club/past/<?=$view['idx']?>?<?=$searchData['prev']?>">◀</a></li>
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
        <li><a href="<?=base_url()?>club/past/<?=$view['idx']?>?<?=$searchData['next']?>">▶</a></li>
      </ul>
      <div class="list-schedule">
        <?php if (empty($listPastNotice)): ?>
          <div class="text-center m-5">검색된 정보가 없습니다.</div>
        <?php else : ?>
        <?php foreach ($listPastNotice as $value): ?>
        <a href="<?=base_url()?>reserve/<?=$value['club_idx']?>?n=<?=$value['idx']?>"><strong><?=$value['subject']?></strong><br><?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원 / <?=cntRes($value['idx'])?>명</a>
        <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <div class="ad-sp">
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- SP_CENTER -->
        <ins class="adsbygoogle"
          style="display:block"
          data-ad-client="ca-pub-2424708381875991"
          data-ad-slot="4319659782"
          data-ad-format="auto"
          data-full-width-responsive="true"></ins>
        <script>
          (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
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
