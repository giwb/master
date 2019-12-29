<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <h1 class="h3 mb-0 text-gray-800">신규 산행 <?=$btn?></h1>
    <div class="border-top border-bottom mt-4 mb-4 pt-3 pb-3 row align-items-center">
      <div class="col-sm-9 area-btn">
        <a href="<?=base_url()?>admin/main_entry/<?=$view['idx']?>"><button type="button" class="btn btn-secondary">수정</button></a>
        <a href="<?=base_url()?>admin/main_notice/<?=$view['idx']?>"><button type="button" class="btn btn-primary">공지</button></a>
        <a href="<?=base_url()?>admin/main_view_progress/<?=$view['idx']?>"><button type="button" class="btn btn-primary">예약</button></a>
        <a href="<?=base_url()?>admin/main_view_boarding/<?=$view['idx']?>"><button type="button" class="btn btn-primary">승차</button></a>
        <a href="<?=base_url()?>admin/main_view_sms/<?=$view['idx']?>"><button type="button" class="btn btn-primary">문자</button></a>
        <a href="<?=base_url()?>admin/main_view_adjust/<?=$view['idx']?>"><button type="button" class="btn btn-primary">정산</button></a>
      </div>
      <div class="col-sm-3">
        <?php if (!empty($view['status'])): ?>
        <select name="status" class="form-control form-control-sm change-status">
          <option value="">산행 상태</option>
          <option value="">------------</option>
          <option<?=$view['status'] == STATUS_PLAN ? ' selected' : ''?> value="<?=STATUS_PLAN?>">계획</option>
          <option<?=$view['status'] == STATUS_ABLE ? ' selected' : ''?> value="<?=STATUS_ABLE?>">예정</option>
          <option<?=$view['status'] == STATUS_CONFIRM ? ' selected' : ''?> value="<?=STATUS_CONFIRM?>">확정</option>
          <option<?=$view['status'] == STATUS_CANCEL ? ' selected' : ''?> value="<?=STATUS_CANCEL?>">취소</option>
          <option<?=$view['status'] == STATUS_CLOSED ? ' selected' : ''?> value="<?=STATUS_CLOSED?>">종료</option>
        </select>
        <?php endif;?>
      </div>
    </div>
    <form id="myForm" method="post" action="<?=base_url()?>admin/main_entry_update">
      <input type="hidden" name="idx" value="<?=$view['idx']?>">
      <input type="hidden" name="peak" class="peak" value="<?=$view['peak']?>">
      <input type="hidden" name="winter" class="winter" value="<?=$view['winter']?>">
      <input type="hidden" name="back_url" value="admin/main_list_progress">
      <div class="row align-items-center">
        <div class="col-sm-9">
          <h4>■ 기본정보</h4>
        </div>
        <div class="col-sm-3 pb-2 text-right">
          <select class="form-control form-control-sm search-notice">
            <option value="">▼ 다른 산행 정보 불러오기</option>
            <?php foreach ($listNotice as $value): ?>
            <option value='<?=$value['idx']?>'><?=$value['subject']?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="row align-items-center border-top mt-3 pt-3">
        <div class="col-sm-1 font-weight-bold">출발일시</div>
        <div class="col-sm-11 row align-items-center">
          <div class="col col-sm-2"><input type="text" name="startdate" id="startDatePicker" class="form-control" value="<?=$view['startdate']?>"></div>
          <div class="col col-sm-2">
            <select name="starttime" id="startTime" class="form-control">
            <?php
              $tStart = $tNow = strtotime('06:00');
              $tEnd = strtotime('23:30');
              while ($tNow <= $tEnd): $nowTime = date('H:i', $tNow);
            ?>
              <option<?=$view['starttime'] == $nowTime ? ' selected' : ''?> value="<?=$nowTime?>"><?=$nowTime?></option>
            <?php
                $tNow = strtotime('+30 minutes', $tNow);
              endwhile;
            ?>
            </select>
          </div>
        </div>
      </div>
      <div class="row align-items-center border-top mt-3 pt-3">
        <div class="col-sm-1 font-weight-bold">도착일자</div>
        <div class="col-sm-11 row align-items-center">
          <div class="col col-sm-2"><input readonly type="text" name="enddate" id="endDatePicker" class="form-control" value="<?=$view['enddate']?>"></div>
          <div class="col col-sm-2"><input readonly type="text" id="calcSchedule" class="form-control"></div>
        </div>
      </div>
      <div class="row align-items-center border-top mt-3 pt-3">
        <div class="col-sm-1 font-weight-bold">산 이름</div>
        <div class="col-sm-11"><input type="text" name="mname" class="form-control" value="<?=$view['mname']?>"></div>
      </div>
      <div class="row align-items-center border-top mt-3 pt-3">
        <div class="col-sm-1 font-weight-bold">지역</div>
        <div class="col-sm-11">
          <button type="button" class="btn btn-sm btn-primary btn-add-area mb-2">추가</button><br>
          <?php if (empty($view['sido'])): ?>
          <div class="row mt-1 pl-1 select-area">
            <div class="ml-2">
              <select name="area_sido[]" class="area-sido form-control">
                <option value=''>시/도</option>
                <?php foreach ($area_sido as $value): ?>
                <option<?=$value['idx'] == $view['area_sido'] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="ml-2">
              <select name="area_gugun[]" class="area-gugun form-control">
                <option value=''>시/군/구</option>
                <?php foreach ($area_gugun as $value): ?>
                <option<?=$value['idx'] == $view['area_gugun'] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <?php else: ?>
            <?php foreach ($view['sido'] as $key => $val): ?>
            <div class="row mt-1 pl-1 select-area">
              <div class="ml-2">
                <select name="area_sido[]" class="area-sido form-control">
                  <option value=''>시/도</option>
                  <?php foreach ($list_sido as $value): ?>
                  <option<?=$value['name'] == $val ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="ml-2">
                <select name="area_gugun[]" class="area-gugun form-control">
                  <option value=''>시/군/구</option>
                  <?php foreach ($list_gugun[$key] as $value): ?>
                  <option<?=$value['name'] == $view['gugun'][$key] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                  <?php endforeach; ?>
                </select><div class="mt-1"></div>
              </div>
            </div>
            <?php endforeach; ?>
          <?php endif; ?>
          <div class="added-area">
          </div>
        </div>
      </div>
      <div class="row align-items-center border-top mt-3 pt-3">
        <div class="col-sm-1 font-weight-bold">산행 제목</div>
        <div class="col-sm-11"><input type="text" name="subject" class="form-control subject" value="<?=$view['subject']?>"></div>
      </div>
      <div class="row align-items-center border-top mt-3 pt-3">
        <div class="col-sm-1 font-weight-bold">산행 코스</div>
        <div class="col-sm-11"><textarea name="content" rows="5" class="form-control w-100"><?=$view['content']?></textarea></div>
      </div>
      <div class="row align-items-center border-top mt-3 pt-3">
        <div class="col-sm-1 font-weight-bold">차량</div>
        <div class="col-sm-11">
          <button type="button" class="btn btn-sm btn-primary btn-add-bus">추가</button><br>
          <div class="form-row mt-2">
            <div class="col-md-4">
              <?php if (!$view['idx']): // 등록 ?>
              <div id="area-init-bus">
                <select name="bustype[]" class="form-control mb-2">
                  <option value="">버스 종류를 선택해주세요.</option>
                  <?php foreach ($listBustype as $value): ?>
                  <option value="<?=$value['idx']?>"><?=$value['bus_name']?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <?php
                else:
                  // 수정
                  for ($i=0; $i<count($view['bustype']); $i++):
                    if ($i == 0):
              ?>
              <div id="area-init-bus" class="d-none">
                <select name="bustype[]" class="form-control mb-2">
                  <option value="">버스 종류를 선택해주세요.</option>
                  <?php foreach ($listBustype as $value): ?>
                  <option value="<?=$value['idx']?>"><?=$value['bus_name']?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <?php endif; ?>
              <select name="bustype[]" class="form-control mb-2">
                <option value="">버스 종류를 선택해주세요.</option>
                <?php foreach ($listBustype as $value): ?>
                <option<?=$value['idx'] == $view['bustype'][$i] ? ' selected' : ''?> value="<?=$value['idx']?>"><?=$value['bus_name']?></option>
                <?php endforeach; ?>
              </select>
              <?php
                  endfor;
                endif;
              ?>
              <div id="area-add-bus">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row align-items-center border-top mt-3 pt-3">
        <div class="col-sm-1 font-weight-bold">메모</div>
        <div class="col-sm-11"><textarea name="article" rows="5" cols="100" class="form-control"><?=$view['article']?></textarea></div>
      </div>

      <div class="mt-5">
        <h4>■ 운행거리 및 통행료 산출</h4>
      </div>
      <div class="d-none d-sm-block">
        <div class="row align-items-center font-weight-bold border-top mt-3 pt-3">
          <div class="col-sm-1">번호</div>
          <div class="col-sm-3">운행구간</div>
          <div class="col-sm-3">도착지주소</div>
          <div class="col-sm-2">거리 (km)</div>
          <div class="col-sm-1">소요시간</div>
          <div class="col-sm-2">통행료 (원)</div>
        </div>
      </div>
      <?php foreach ($view['road_course'] as $key => $value): ?>
      <div class="row align-items-center font-weight-bold border-top mt-3 pt-3 row-course">
        <div class="col-sm-1"><?=$key + 1?></div>
        <div class="col-sm-3"><input placeholder="운행구간" class="form-control w2x" type="text" size="20" name="road_course[]" value="<?=!empty($view['road_course'][$key]) ? $view['road_course'][$key] : ''?>"></div>
        <div class="col-sm-3"><input placeholder="도착지주소" class="form-control w2x" type="text" size="20" name="road_address[]" value="<?=!empty($view['road_address'][$key]) ? $view['road_address'][$key] : ''?>"></div>
        <div class="col-sm-2"><input placeholder="거리 (km)" class="form-control road-distance" type="text" size="4" name="road_distance[]" value="<?=!empty($view['road_distance'][$key]) ? $view['road_distance'][$key] : ''?>"></div>
        <div class="col-sm-1"><input placeholder="소요시간" class="form-control road-runtime" type="text" size="4" name="road_runtime[]" value="<?=array_key_exists($key, $view['road_runtime']) ? $view['road_runtime'][$key] : ''?>"></div>
        <div class="col-sm-2"><input placeholder="통행료 (원)" class="form-control road-cost" type="text" size="4" name="road_cost[]" value="<?=array_key_exists($key, $view['road_cost']) ? $view['road_cost'][$key] : ''?>"></div>
      </div>
      <?php endforeach; ?>
      <div class="added-course"></div>
      <div class="row align-items-center font-weight-bold border-top mt-3 pt-3">
        <div class="col-sm-1 mb-3"><button type="button" class="btn btn-sm btn-primary btn-course">추가</button></div>
        <div class="col-sm-5"></div>
        <div class="col-sm-1">합계</div>
        <div class="col-sm-2"><input class="form-control total-distance" readonly type="text" name="distance" size="4" value="0"></div>
        <div class="col-sm-1"></div>
        <div class="col-sm-2"><input class="form-control total-cost" readonly type="text" size="4" value="0"></div>
      </div>

      <div class="mt-5">
        <h4>■ 버스비용 산출</h4>
      </div>
      <div class="row align-items-center font-weight-bold border-top mt-3 pt-3">
        <div class="col-sm-3">기본요금</div>
        <div class="col-sm-3">주유비</div>
        <div class="col-sm-3">운행비</div>
        <div class="col-sm-3">추가비용</div>
      </div>
      <div class="row align-items-center border-top mt-3 pt-3">
        <div class="col-sm-3 row"></div>
        <div class="col-sm-3 row align-items-center">
          <div class="col-sm-6">총주행 (km)</div>
          <div class="col-sm-6"><input class="form-control total-distance" readonly type="text" size="3" name="driving_fuel[]"></div>
        </div>
        <div class="col-sm-3 row align-items-center">
          <div class="col-sm-6">통행료 (원)</div>
          <div class="col-sm-6"><input class="form-control driving-cost total-cost" readonly type="text" size="4" name="driving_cost[]" value="<?=!empty($view['driving_cost'][0]) ? $view['driving_cost'][0] : ''?>"></div>
        </div>
        <div class="col-sm-3 row align-items-center">
          <div class="col-sm-6">일정추가 (원)</div>
          <div class="col-sm-6"><input class="form-control driving-add cost-add-schedule" readonly type="text" size="4" name="driving_add[]" value="<?=!empty($view['driving_add'][0]) ? $view['driving_add'][0] : ''?>"></div>
        </div>
      </div>
      <div class="row align-items-center border-top mt-3 pt-3">
        <div class="col-sm-3 row"></div>
        <div class="col-sm-3 row align-items-center">
          <div class="col-sm-6">연비 (km)</div>
          <div class="col-sm-6"><input class="form-control driving-fuel" readonly type="text" size="3" name="driving_fuel[]"></div>
        </div>
        <div class="col-sm-3 row align-items-center">
          <div class="col-sm-6">주차비 (원)</div>
          <div class="col-sm-6"><input class="form-control driving-cost" type="text" size="4" name="driving_cost[]" value="<?=!empty($view['driving_cost'][1]) ? $view['driving_cost'][1] : '6000'?>"></div>
        </div>
        <div class="col-sm-3 row align-items-center">
          <div class="col-sm-6">성수기 (원)</div>
          <div class="col-sm-6"><input class="form-control driving-add cost-peak" readonly type="text" size="4" name="driving_add[]"></div>
        </div>
      </div>
      <div class="row align-items-center border-top mt-3 pt-3">
        <div class="col-sm-3 row"></div>
        <div class="col-sm-3 row align-items-center">
          <div class="col-sm-6">시세 (원/L)</div>
          <div class="col-sm-6"><input class="form-control cost-gas" type="text" size="3" name="driving_fuel[]" value="<?=!empty($view['driving_fuel'][2]) ? $view['driving_fuel'][2] : $costGas?>"></div>
        </div>
        <div class="col-sm-3 row align-items-center">
          <div class="col-sm-6">식대 (원)</div>
          <div class="col-sm-6"><input class="form-control driving-cost" type="text" size="4" name="driving_cost[]" value="<?=!empty($view['driving_cost'][2]) ? $view['driving_cost'][2] : '10000'?>"></div>
        </div>
        <div class="col-sm-3 row align-items-center">
          <div class="col-sm-6">승객수당 (원)</div>
          <div class="col-sm-6"><input class="form-control driving-add" type="text" size="4" name="driving_add[]" value="<?=!empty($view['cost_driver']) ? $view['cost_driver'] : ''?>"></div>
        </div>
      </div>
      <div class="row align-items-center border-top mt-3 pt-3">
        <div class="col-sm-3 row"></div>
        <div class="col-sm-3 row"></div>
        <div class="col-sm-3 row align-items-center">
          <div class="col-sm-6">숙박 (원)</div>
          <div class="col-sm-6"><input class="form-control driving-cost" type="text" size="4" name="driving_cost[]" value="<?=!empty($view['driving_cost'][3]) ? $view['driving_cost'][3] : ''?>"></div>
        </div>
        <div class="col-sm-3 row"></div>
      </div>
      <div class="row align-items-center border-top mt-3 pt-3">
        <div class="col-sm-3 row align-items-center">
          <div class="col-sm-6">기본요금 (원)</div>
          <div class="col-sm-6"><input class="form-control driving-default" readonly type="text" size="6" name="driving_default"></div>
        </div>
        <div class="col-sm-3 row align-items-center">
          <div class="col-sm-6">합계 (원)</div>
          <div class="col-sm-6"><input class="form-control total-driving-fuel" readonly type="text" size="3"></div>
        </div>
        <div class="col-sm-3 row align-items-center">
          <div class="col-sm-6">합계 (원)</div>
          <div class="col-sm-6"><input class="form-control total-driving-cost" readonly type="text" size="4"></div>
        </div>
        <div class="col-sm-3 row align-items-center">
          <div class="col-sm-6">합계 (원)</div>
          <div class="col-sm-6"><input class="form-control total-driving-add" readonly type="text" size="4"></div>
        </div>
      </div>
      <div class="row align-items-center border-top mt-3 pt-3">
        <div class="col-sm-3 row"></div>
        <div class="col-sm-3 row"></div>
        <div class="col-sm-3 row"></div>
        <div class="col-sm-3 row align-items-center">
          <div class="col-sm-6 font-weight-bold">운행견적총액 (원)</div>
          <div class="col-sm-6"><input class="form-control total-bus-cost" readonly type="text" size="4" name="driving_total" value="<?=$view['driving_total'] != '' ? $view['driving_total'] : ''?>"></div>
        </div>
      </div>

      <div class="mt-5">
        <h4>■ 산행 분담금</h4>
      </div>
      <div class="row align-items-center border-top mt-3 pt-3">
        <div class="col-sm-1 font-weight-bold">기본 비용</div>
        <div class="col-sm-11"><input readonly type="text" name="cost" class="form-control cost-default" value="<?=$view['cost'] != '' ? $view['cost'] : '0'?>"></div>
      </div>
      <div class="row align-items-center border-top mt-3 pt-3">
        <div class="col-sm-1 font-weight-bold">추가 비용</div>
        <div class="col-sm-11"><input type="text" name="cost_added" class="form-control cost-added" value="<?=$view['cost_added'] != '' ? $view['cost_added'] : '0'?>"></div>
      </div>
      <div class="row align-items-center border-top mt-3 pt-3">
        <div class="col-sm-1 font-weight-bold">산행 분담금</div>
        <div class="col-sm-11"><input type="text" name="cost_total" class="form-control cost-total" value="<?=$view['cost_total'] != '' ? $view['cost_total'] : '0'?>"></div>
      </div>
      <div class="row align-items-center border-top mt-3 mb-5 pt-3 pb-5">
        <div class="col-sm-1 font-weight-bold">포함사항</div>
        <div class="col-sm-11"><textarea rows="5" cols="100" name="cost_memo" class="form-control"><?=$view['costmemo']?></textarea></div>
      </div>

      <div class="area-button">
        <button type="button" class="btn btn-primary btn-entry mr-2"><?=$btn?></button>
        <button type="button" class="btn btn-dark btn-list mr-5" data-action="admin/main_list_progress">목록</button>
        <?php if (!empty($view['visible']) && $view['visible'] == VISIBLE_ABLE): ?>
        <button type="button" class="btn btn-secondary btn-change-visible" data-idx="<?=$view['idx']?>" data-visible="<?=VISIBLE_NONE?>">숨김</button>
        <?php else: ?>
        <button type="button" class="btn btn-primary btn-change-visible" data-idx="<?=$view['idx']?>" data-visible="<?=VISIBLE_ABLE?>">공개</button>
        <?php endif; ?>
        <button type="button" class="btn btn-danger btn-notice-delete ml-2" data-idx="<?=$view['idx']?>">삭제</button>
      </div>
    </form>

    <script type="text/javascript">
      $(document).ready(function(){
        var totalDistance = $.calcTotalDistance(); // 총 거리 계산
        $.calcSchedule($('#startDatePicker').val(), $('#startTime').val(), $('#endDatePicker').val()) // 여행기간 계산
        $.calcRoadCost(); // 통행료 계산
        $.calcFuel(); // 연비 계산 (총주행 / 3.5)
        $.calcBusCost(totalDistance); // 버스비용/산행분담 기본비용 계산
        $.calcTotalDriving(); // 운행비 합계
        $.calcTotalFuel(); // 주유비 합계
        $.calcAdd(); // 추가비용 합계
        $.calcTotalBus(); // 추가비용 합계
        $.calcCost(); // 산행 분담금 계산
        <?php if (empty($view['idx']) || $view['status'] == STATUS_PLAN): ?>
        // 통행료 계산
        $('.road-cost').each(function(n) {
          if (n == 0 && $(this).val() == '') {
            $(this).val('0');
          }
        });
        <?php endif; ?>

        // 출발일시
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

        // 도착일
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

        // 신규 등록시, 출발일시를 선택하면 도착일시는 당일로 자동 선택
        $('#startDatePicker').change(function() {
          if ($('#endDatePicker').val() == '') {
            $('#endDatePicker').val($(this).val());
          }
        });
      });

      $(document).on('change', '.area-sido', function() {
        var $dom = $(this);
        var parent = $dom.val();

        $.ajax({
          url: $('input[name=base_url]').val() + 'admin/list_gugun',
          data: 'parent=' + parent,
          dataType: 'json',
          type: 'post',
          success: function(result) {
            var $appendDom = $dom.parent().parent().find('.area-gugun');
            $appendDom.empty().append( $('<option value="">시/군/구</option>') );
            for (var i=0; i<result.length; i++) {
              $appendDom.append( $('<option value="' + result[i].idx + '">' + result[i].name + '</option>') );
            }
          }
        });
      }).on('click', '.btn-add-area', function() {
        var data = $.getAreaTemplate();
        $('.added-area').append(data);
      }).on('change', '.search-notice', function() {
        $.ajax({
          url: $('input[name=base_url]').val() + 'admin/main_entry_notice',
          data: 'idx=' + $(this).val(),
          dataType: 'json',
          type: 'post',
          success: function(result) {
            $('input[name=mname]').val(result.mname);
            $('input[name=subject]').val(result.subject);
            $('textarea[name=content]').val(result.content);
            $('textarea[name=article]').val(result.article);
            $('.select-area').remove();
            $('.added-area').empty();

            if (typeof result.sido != 'undefined') {
              $.each(result.sido, function(i1, v1) {
                $.ajax({
                  url: $('input[name=base_url]').val() + 'admin/main_entry_notice_area',
                  data: 'sido=' + v1,
                  dataType: 'json',
                  type: 'post',
                  success: function(result2) {
                    var data = '<div class="row mt-1 pl-1"><div class="ml-2"><select name="area_sido[]" class="area-sido form-control">';
                    data += '<option value="">시/도</option>';
                    $.each(result2.area_sido, function(i2, v2) {
                      data += '<option';
                      if (v2.idx == v1) data += ' selected';
                      data +=' value="' + v2.idx + '">' + v2.name + '</option>';
                    });
                    data += '</select></div>';
                    data += '<div class="ml-2"><select name="area_gugun[]" class="area-gugun form-control">';
                    data += '<option value="">시/군/구</option>';
                    $.each(result2.area_gugun, function(i3, v3) {
                      data += '<option';
                      if (v3.idx == result.gugun[i1]) data += ' selected';
                      data +=' value="' + v3.idx + '">' + v3.name + '</option>';
                    });
                    data += '</select></div></div>';
                    $('.added-area').append(data);
                  }
                });
              });
            } else {
              var data = $.getAreaTemplate();
              $('.added-area').append(data);
            }
          }
        });
      }).on('click', '.btn-course', function() {
        var cnt = 0;
        $('.row-course').each(function() {
          cnt++;
        });
        var html = '<div class="row align-items-center font-weight-bold border-top mt-3 pt-3 row-course"><div class="col-sm-1">' + (Number(cnt) + 1) + '</div>';
            html += '<div class="col-sm-3"><input placeholder="운행구간" class="form-control w2x" type="text" size="20" name="road_course[]"></div>';
            html += '<div class="col-sm-3"><input placeholder="도착지주소" class="form-control w2x" type="text" size="20" name="road_address[]"></div>';
            html += '<div class="col-sm-2"><input placeholder="거리 (km)" class="form-control road-distance" type="text" size="4" name="road_distance[]"></div>';
            html += '<div class="col-sm-1"><input placeholder="소요시간" class="form-control road-runtime" type="text" size="4" name="road_runtime[]"></div>';
            html += '<div class="col-sm-2"><input placeholder="통행료 (원)" class="form-control road-cost" type="text" size="4" name="road_cost[]"></div></div>';

        $('.added-course').append(html);
      });

      $.getAreaTemplate = function() {
        var data = '<div class="row mt-1 pl-1"><div class="ml-2"><select name="area_sido[]" class="area-sido form-control">';
        data += '<option value="">시/도</option>';
        <?php foreach ($area_sido as $value): ?>
        data += '<option<?=$value['idx'] == $view['area_sido'] ? " selected" : ""?> value="<?=$value['idx']?>""><?=$value['name']?></option>';
        <?php endforeach; ?>
        data += '</select></div>';
        data += '<div class="ml-2"><select name="area_gugun[]" class="area-gugun form-control">';
        data += '<option value="">시/군/구</option>';
        <?php foreach ($area_gugun as $value): ?>
        data += '<option<?=$value['idx'] == $view['area_gugun'] ? " selected" : ""?> value="<?=$value['idx']?>""><?=$value['name']?></option>';
        <?php endforeach; ?>
        data += '</select></div></div>';        
        return data;
      }
    </script>
