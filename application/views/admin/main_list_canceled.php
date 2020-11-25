<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div id="content" class="mb-5">
          <div class="w-100 border mt-3 mb-3">
            <form id="formSearch" method="get" action="<?=BASE_URL?>/admin/main_list_canceled">
              <div class="row align-items-center w-100 pt-3 text-center">
                <div class="col-3 col-sm-2">시작일</div>
                <div class="col-9 col-sm-4"><input id="startDatePicker" type="text" name="sdate" class="form-control form-control-sm" value="<?=!empty($search['sdate']) ? $search['sdate'] : ''?>"></div>
                <div class="w-100 d-block d-sm-none pt-2"></div>
                <div class="col-3 col-sm-2">종료일</div>
                <div class="col-9 col-sm-4"><input id="endDatePicker" type="text" name="edate" class="form-control form-control-sm" value="<?=!empty($search['edate']) ? $search['edate'] : ''?>"></div>
              </div>
              <div class="row align-items-center w-100 pt-2 text-center">
                <div class="col-3 col-sm-2">검색어</div>
                <div class="col-9 col-sm-8"><input type="text" name="subject" class="form-control form-control-sm" value="<?=!empty($search['subject']) ? $search['subject'] : ''?>"></div>
                <div class="w-100 d-block d-sm-none pt-2"></div>
                <div class="col-sm-2 text-left"><button class="btn btn-sm btn-default w-100">검색</button></div>
              </div>
            </form>
          </div>
          <div class="row align-items-center border-bottom mb-3 pb-3">
            <div class="col-sm-2 d-none d-sm-block"></div>
            <div class="col-2 col-sm-1 text-right"><a href="<?=BASE_URL?>/admin/main_list_canceled?<?=$search['prev']?>">◀</a></div>
            <div class="col-4 col-sm-3 pr-0">
              <select name="syear" class="form-control form-control-sm">
                <?php foreach (range($search['syear'], 2010) as $value): ?>
                  <option<?=!empty($search['syear']) && $search['syear'] == $value ? ' selected' : ''?> value='<?=$value?>'><?=$value?>년</option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-4 col-sm-3">
              <select name="smonth" class="form-control form-control-sm btn-search-month">
                <?php foreach (range(1, 12) as $value): ?>
                <option<?=!empty($search['smonth']) && $search['smonth'] == $value ? ' selected' : ''?> value='<?=strlen($value) < 2 ? '0' . $value : $value?>'><?=$value?>월</option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-2 col-sm-1"><a href="<?=BASE_URL?>/admin/main_list_canceled?<?=$search['next']?>">▶</a></div>
            <div class="col-sm-2 d-none d-sm-block"></div>
          </div>
          <?php if (empty($listCancel)): ?>
          <div class="border-bottom pt-4 pb-5 text-center">
            등록된 산행이 없습니다.
          </div>
          <?php else: foreach ($listCancel as $value): ?>
          <div class="row mb-3">
            <div class="col-10 pl-0 pr-0">
              <strong><?=viewStatus($value['status'])?></strong> <a href="<?=BASE_URL?>/admin/main_view_progress/<?=$value['idx']?>"><?=$value['subject']?></a>
              <div class="small"><?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원</div>
            </div>
            <div class="col-2 pr-0 pl-0 text-right small">0원<br><?=cntRes($value['idx'])?>명</div>
          </div>
          <?php endforeach; endif; ?>
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
