<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main id="club">
    <div class="container-fluid club-main">
      <div class="row mt-1 mb-5">
        <div class="col-xl-8 col-md-12">
          <h4 class="font-weight-bold"><?=$pageTitle?></h4>
          <hr class="text-default">

          <div class="mypage mt-2">
            <form id="formSearch" method="get" action="/member/driver" class="row border no-gutters align-items-center text-center pt-2 pb-2 pr-2 mt-3 mb-3">
              <ul class="box-past-search">
                <li>기간검색</li>
                <li><input type="text" id="startDatePicker" name="sdate" class="form-control form-control-sm" value="<?=!empty($searchData['sdate']) ? $searchData['sdate'] : ''?>"></li>
                <li>～</li>
                <li><input type="text" id="endDatePicker" name="edate" class="form-control form-control-sm" value="<?=!empty($searchData['edate']) ? $searchData['edate'] : ''?>"></li>
                <li>산행명 검색</li>
                <li><input type="text" name="keyword" class="form-control form-control-sm" value="<?=!empty($searchData['keyword']) ? $searchData['keyword'] : ''?>"></li>
                <li><button class="btn-sm btn-custom btn-giwbred">검색</button></li>
              </ul>
            </form>
            <ul class="box-past-title">
              <li><a href="<?=BASE_URL?>/member/driver/?<?=$searchData['prev']?>">◀</a></li>
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
              <li><a href="<?=BASE_URL?>/member/driver/?<?=$searchData['next']?>">▶</a></li>
            </ul>
            <?php foreach ($listNoticeDriver as $value): $busType = getBusType($value['bustype'], $value['bus']); ?>
            <div class="border-top m-0 pt-2 pb-2">
              <div class="">
                <a href="<?=BASE_URL?>/member/driver_view/?n=<?=$value['idx']?>"><?=viewStatus($value['status'])?> <strong><?=$value['subject']?></strong></a><br><?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원 / <?=cntRes($value['idx'])?>명
              </div>
              <div class="">
              <?php if ($userData['level'] == LEVEL_DRIVER_ADMIN): ?>
                <?php foreach ($busType as $key => $bus): $busNo = $key + 1; ?>
                <select class="form-control form-control-sm selectBus" data-idx="<?=$value['idx']?>" data-bus="<?=$busNo?>"<?=$value['status'] >= STATUS_CANCEL ? ' disabled' : ''?>>
                  <?php foreach ($listBustype as $busInfo): ?>
                  <option<?=$busInfo['idx'] == $bus['idx'] ? ' selected' : ''?> value="<?=$busInfo['idx']?>"><?=$busInfo['bus_name']?> <?=$busInfo['bus_owner']?></option>
                  <?php endforeach; ?>
                </select>
                <?php endforeach; ?>
              <?php else: ?>
                <?php foreach ($busType as $key => $bus): $busNo = $key + 1; ?>
                  <?=$busNo?>호차 : <?=$bus['bus_name']?> <?=$bus['bus_owner']?><br>
                <?php endforeach; ?>
              <?php endif; ?>
              </div>
            </div>
            <?php endforeach; ?>
            <div class="border-top p-2"></div>
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
            $('.selectBus').change(function() {
              $.ajax({
                url: '/member/driver_change',
                data: 'noticeIdx=' + $(this).data('idx') + '&bus=' + $(this).data('bus') + '&busType=' + $(this).val(),
                dataType: 'json',
                type: 'post',
                success: function(result) {
                  $.openMsgModal(result.message);
                }
              });
            });
          });
        </script>
