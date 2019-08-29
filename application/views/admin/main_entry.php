<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">신규 산행 <?=$btn?></h1>
        </div>
      </div>
    </div>

    <div class="sub-contents">
      <form id="myForm" method="post" action="<?=base_url()?>admin/main_entry_update">
      <input type="hidden" name="idx" value="<?=$view['idx']?>">
      <input type="hidden" name="peak" class="peak" value="<?=$view['peak']?>">
      <input type="hidden" name="winter" class="winter" value="<?=$view['winter']?>">
        <h2>■ 기본정보</h2>
        <table class="table">
          <colgroup>
            <col width="150">
          </colgroup>
          <tbody>
            <tr>
              <th>출발일시</th>
              <td>
                <div class="form-row">
                  <div class="col-md-2"><input readonly type="text" name="startdate" id="startDatePicker" class="form-control" value="<?=$view['startdate']?>"></div>
                  <div class="col-md-2">
                    <select name="starttime" id="startTime" class="form-control">
<?php
  $startHour = 5;
  $startMinute = '00';
  for ($i=1; $i<40; $i++):
    if ($i%2 == 0):
      $startHour++;
      $startMinute = '00';
    else:
      $startMinute = '30';
?>
                  <option<?=$view['starttime'] == ($startHour.':'.$startMinute) || (!$view['starttime'] && ($startHour.':'.$startMinute) == '6:00') ? ' selected' : ''?> value="<?=$startHour?>:<?=$startMinute?>"><?=$startHour?>:<?=$startMinute?></option>
<?php
    endif;
  endfor;
?>
                    </select>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <th>도착일자</th>
              <td>
                <div class="form-row">
                  <div class="col-md-2"><input readonly type="text" name="enddate" id="endDatePicker" class="form-control" value="<?=$view['enddate']?>"></div>
                  <div class="col-md-2"><input readonly type="text" id="calcSchedule" class="form-control"></div>
                </div>
              </td>
            </tr>
            <tr>
              <th>산 이름</th>
              <td class="form-row">
                <div class="col-md-4">
                  <input type="text" name="mname" class="form-control" value="<?=$view['mname']?>">
                </div>
              </td>
            </tr>
            <tr>
              <th>산행 제목</th>
              <td class="form-row">
                <div class="col-md-4">
                  <input type="text" name="subject" class="form-control subject" value="<?=$view['subject']?>">
                </div>
              </td>
            </tr>
            <tr>
              <th>산행 코스</th>
              <td class="form-row">
                <div class="col-md-4">
                  <input type="text" name="content" class="form-control" value="<?=$view['content']?>">
                </div>
              </td>
            </tr>
            <tr>
              <th>차량</th>
              <td>
                <button type="button" class="btn btn-primary btn-add-bus">추가</button><br>
                <div class="form-row mt-2">
                  <div class="col-md-4">
<?php
  if (!$view['idx']):
    // 등록
?>
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
              </td>
            </tr>
            <tr>
              <th>메모</th>
              <td><textarea name="article" rows="5" cols="100" class="form-control"><?=$view['article']?></textarea></td>
            </tr>
            <tr>
              <td colspan="2"></td>
            </tr>
          </tbody>
        </table>

        <h2>■ 운행거리 및 통행료 산출</h2>
        <table class="table form-small">
          <thead>
            <tr>
              <th>번호</th>
              <th>운행구간</th>
              <th>거리</th>
              <th>소요시간</th>
              <th>통행료</th>
            </tr>
          </thead>
          <tbody>
<?php foreach (range(1, 10) as $key => $value): ?>
            <tr>
              <td><?=$value?></td>
              <td><input class="form-control" type="text" size="20" name="road_course[]" value="<?=!empty($view['road_course'][$key]) ? $view['road_course'][$key] : ''?>"></td>
              <td><input class="form-control road-distance" type="text" size="4" name="road_distance[]" value="<?=!empty($view['road_distance'][$key]) ? $view['road_distance'][$key] : ''?>">km</td>
              <td><input class="form-control road-runtime" type="text" size="4" name="road_runtime[]" value="<?=!empty($view['road_runtime'][$key]) ? $view['road_runtime'][$key] : ''?>"></td>
              <td><input class="form-control road-cost" type="text" size="4" name="road_cost[]" value="<?=!empty($view['road_cost'][$key]) ? $view['road_cost'][$key] : ''?>">원</td>
            </tr>
<?php endforeach; ?>
            <tr>
              <th>합계</th>
              <td>&nbsp;</td>
              <td><input class="form-control total-distance" readonly type="text" name="distance" size="4" value="0">km</td>
              <td>&nbsp;</td>
              <td><input class="form-control total-cost" readonly type="text" size="4" value="0">원</td>
            </tr>
            <tr><td colspan="5"></td></tr>
          </tbody>
        </table>

        <h2>■ 버스비용 산출</h2>
        <table class="table form-small">
          <thead>
            <tr>
              <th colspan="2">기본요금</th>
              <th colspan="2">주유비</th>
              <th colspan="2">운행비</th>
              <th colspan="2">추가비용</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td rowspan="4" colspan="2">&nbsp;</td>
              <td width="9%">총주행</td>
              <td width="18%"><input class="form-control total-distance" readonly type="text" size="3" name="driving_fuel[]">km</td>
              <td width="9%">통행료</td>
              <td width="16%"><input class="form-control driving-cost" type="text" size="4" name="driving_cost[]" value="<?=!empty($view['driving_cost'][0]) ? $view['driving_cost'][0] : ''?>">원</td>
              <td width="12%">일정추가</td>
              <td width="16%"><input class="form-control driving-add" type="text" size="4" name="driving_add[]" value="<?=!empty($view['driving_add'][0]) ? $view['driving_add'][0] : ''?>">원</td>
            </tr>
            <tr>
              <td>연비</td>
              <td><input class="form-control driving-fuel" readonly type="text" size="3" name="driving_fuel[]">km</td>
              <td>주차비</td>
              <td><input class="form-control driving-cost" type="text" size="4" name="driving_cost[]" value="<?=!empty($view['driving_cost'][1]) ? $view['driving_cost'][1] : '6000'?>">원</td>
              <td>성수기</td>
              <td><input class="form-control driving-add cost-peak" readonly type="text" size="4" name="driving_add[]">원</td>
            </tr>
            <tr>
              <td>시세</td>
              <td><input class="form-control cost-gas" type="text" size="3" name="driving_fuel[]" value="<?=!empty($view['driving_fuel'][2]) ? $view['driving_fuel'][2] : ''?>">원/L</td>
              <td>식대</td>
              <td><input class="form-control driving-cost" type="text" size="4" name="driving_cost[]" value="<?=!empty($view['driving_cost'][2]) ? $view['driving_cost'][2] : '10000'?>">원</td>
              <td>승객수당</td>
              <td><input class="form-control driving-add" type="text" size="4" name="driving_add[]" value="<?=!empty($view['driving_add'][2]) ? $view['driving_add'][2] : ''?>">원</td>
            </tr>
            <tr>
              <td colspan="2">&nbsp;</td>
              <td>숙박</td>
              <td><input class="form-control driving-cost" type="text" size="4" name="driving_cost[]" value="<?=!empty($view['driving_cost'][3]) ? $view['driving_cost'][3] : '16000'?>">원</td>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2"><input class="form-control driving-default" readonly type="text" size="6" name="driving_default">원</td>
              <td>합계</td>
              <td><input class="form-control total-driving-fuel" readonly type="text" size="3">원</td>
              <td>합계</td>
              <td><input class="form-control total-driving-cost" readonly type="text" size="4">원</td>
              <td>합계</td>
              <td><input class="form-control total-driving-add" readonly type="text" size="4">원</td>
            </tr>
            <tr>
              <th colspan="2">운행견적총액</th>
              <td colspan="5">&nbsp;</td>
              <td colspan="2"><input class="form-control total-bus-cost" readonly type="text" size="4" name="driving_total" value="<?=$view['driving_total'] != '' ? $view['driving_total'] : ''?>">원</td>
            </tr>
            <tr><td colspan="9"></td></tr>
          </tbody>
        </table>

        <h2>■ 산행 분담금</h2>
        <table class="table">
          <colgroup>
            <col width="150">
          </colgroup>
          <tbody>
            <tr>
              <th>기본 비용</th>
              <td class="form-row">
                <div class="col-md-4"><input readonly type="text" name="cost" class="form-control cost-default" value="<?=$view['cost'] != '' ? $view['cost'] : '0'?>"></div>
              </td>
            </tr>
            <tr>
              <th>추가 비용</th>
              <td class="form-row">
                <div class="col-md-4"><input type="text" name="cost_added" class="form-control cost-added" value="<?=$view['cost_added'] != '' ? $view['cost_added'] : '0'?>"></div>
              </td>
            </tr>
            <tr>
              <th>최종 분담금</th>
              <td class="form-row">
                <div class="col-md-4"><input type="text" name="cost_total" class="form-control cost-total" value="<?=$view['cost_total'] != '' ? $view['cost_total'] : '0'?>"></div>
              </td>
            </tr>
            <tr>
              <th>포함사항</th>
              <td><textarea rows="5" cols="100" name="cost_memo" class="form-control"><?=$view['costmemo']?></textarea></td>
            </tr>
            <tr><td colspan="2"></td></tr>
          </tbody>
        </table>

        <div class="text-center mb-5">
          <button type="button" class="btn btn-primary btn-entry"><?=$btn?>합니다</button>
        </div>
      </form>
    </div>

    <link rel="stylesheet" href="<?=base_url()?>public/css/jquery-ui.css">
    <script src="<?=base_url()?>public/js/jquery-ui.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        var totalDistance = $.calcTotalDistance(); // 총 거리 계산
        $.calcSchedule($('#startDatePicker').val(), $('#startTime').val(), $('#endDatePicker').val()) // 여행기간 계산
        $.calcRoadCost(); // 통행료 계산
        $.calcFuel(); // 연비 계산 (총주행 / 3.5)
        $.calcBusCost(totalDistance); // 버스비용/산행분담 기본비용 계산
        $.calcTotalFuel(); // 주유비 합계
        $.calcTotalDriving(); // 운행비 합계
        $.calcAdd(); // 추가비용 합계
        $.calcTotalBus(); // 추가비용 합계
        $.calcCost(); // 최종 분담금 계산

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
      });
    </script>
