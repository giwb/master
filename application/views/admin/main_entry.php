<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">신규 산행 등록</h1>
        </div>
      </div>
    </div>

    <div class="sub-contents">
      <form name="myForm" method="post">
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
                  <div class="col-md-2"><input readonly type="text" name="startdate" id="startDatePicker" class="form-control"></div>
                  <div class="col-md-2">
                    <select name="starttime" class="form-control">
<?php
  $startHour = 5;
  $startMinute = '00';
  for ($i=1; $i<40; $i++):
    if ($i%2 == 0):
      $startHour++;
      $startMinute = '00';
    else:
      $startMinute = '30';
//if ($dataList[0]["starttime"] == ($start_hour.':'.$start_minute) || (!$dataList[0]["starttime"] && ($start_hour.':'.$start_minute) == '6:00')) echo " selected";
?>
                  <option value="<?=$startHour?>:<?=$startMinute?>"><?=$startHour?>:<?=$startMinute?></option>
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
              <th>도착일시</th>
              <td>
                <div class="form-row">
                  <div class="col-md-2"><input readonly type="text" name="startdate" id="endDatePicker" class="form-control"></div>
                  <div class="col-md-2">
                    <select name="starttime" class="form-control">
<?php
  $startHour = 5;
  $startMinute = '00';
  for ($i=1; $i<40; $i++):
    if ($i%2 == 0):
      $startHour++;
      $startMinute = '00';
    else:
      $startMinute = '30';
//if ($dataList[0]["starttime"] == ($start_hour.':'.$start_minute) || (!$dataList[0]["starttime"] && ($start_hour.':'.$start_minute) == '6:00')) echo " selected";
?>
                  <option value="<?=$startHour?>:<?=$startMinute?>"><?=$startHour?>:<?=$startMinute?></option>
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
              <th>산 이름</th>
              <td class="form-row">
                <div class="col-md-4">
                  <input type="text" name="mname" class="form-control">
                </div>
              </td>
            </tr>
            <tr>
              <th>산행 제목</th>
              <td class="form-row">
                <div class="col-md-4">
                  <input type="text" name="subject" class="form-control">
                </div>
              </td>
            </tr>
            <tr>
              <th>산행 설명</th>
              <td class="form-row">
                <div class="col-md-4">
                  <input type="text" name="content" class="form-control">
                </div>
              </td>
            </tr>
            <tr>
              <th>산행 분담금</th>
              <td>
                <div class="form-row mb-2">
                  <div class="col-md-1"><input readonly type="text" class="form-control-plaintext" value="구간"></div>
                  <div class="col-md-3"><input type="text" name="distance" class="form-control"></div>
                </div>
                <div class="form-row mb-2">
                  <div class="col-md-1"><input readonly type="text" class="form-control-plaintext" value="일정"></div>
                  <div class="col-md-3"><input type="text" name="schedule" class="form-control"></div>
                </div>
                <div class="form-row mb-2">
                  <div class="col-md-1"><input readonly type="text" class="form-control-plaintext" value="가격"></div>
                  <div class="col-md-3"><input type="text" name="cost" class="form-control"></div>
                </div>
                <div class="form-row">
                  <div class="col-md-1"><input readonly type="text" class="form-control-plaintext" value="메모"></div>
                  <div class="col-md-3"><input type="text" name="costmemo" class="form-control"></div>
                </div>
              </td>
            </tr>
            <tr>
              <th>차량</th>
              <td>
                <button type="button" class="btn btn-primary btn-add-bus">추가</button><br>
                <div class="form-row mt-2">
                  <div class="col-md-4">
                    <select name="bustype[]" class="form-control">
                      <option value="">버스 종류를 선택해주세요.</option>
                    </select>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <th>내용</th>
              <td><textarea name="article" id="article" rows="10" cols="100"></textarea></td>
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
            <tr>
              <td>1</td>
              <td><input class="form-control" type="text" size="20" name="road_course[]" value=""></td>
              <td><input class="form-control" type="text" size="4" name="road_distance[]" class="road_distance" value="" onBlur="calcDistance();">km</td>
              <td><input class="form-control" type="text" size="4" name="road_runtime[]" class="road_runtime" value=""></td>
              <td><input class="form-control" type="text" size="4" name="road_cost[]" class="road_cost" value="" onBlur="calcCost();">원</td>
            </tr>
            <tr>
              <td>2</td>
              <td><input class="form-control" type="text" size="20" name="road_course[]" value=""></td>
              <td><input class="form-control" type="text" size="4" name="road_distance[]" class="road_distance" value="" onBlur="calcDistance();">km</td>
              <td><input class="form-control" type="text" size="4" name="road_runtime[]" class="road_runtime" value=""></td>
              <td><input class="form-control" type="text" size="4" name="road_cost[]" class="road_cost" value="" onBlur="calcCost();">원</td>
            </tr>
            <tr>
              <td>3</td>
              <td><input class="form-control" type="text" size="20" name="road_course[]" value=""></td>
              <td><input class="form-control" type="text" size="4" name="road_distance[]" class="road_distance" value="" onBlur="calcDistance();">km</td>
              <td><input class="form-control" type="text" size="4" name="road_runtime[]" class="road_runtime" value=""></td>
              <td><input class="form-control" type="text" size="4" name="road_cost[]" class="road_cost" value="" onBlur="calcCost();">원</td>
            </tr>
            <tr>
              <td>4</td>
              <td><input class="form-control" type="text" size="20" name="road_course[]" value=""></td>
              <td><input class="form-control" type="text" size="4" name="road_distance[]" class="road_distance" value="" onBlur="calcDistance();">km</td>
              <td><input class="form-control" type="text" size="4" name="road_runtime[]" class="road_runtime" value=""></td>
              <td><input class="form-control" type="text" size="4" name="road_cost[]" class="road_cost" value="" onBlur="calcCost();">원</td>
            </tr>
            <tr>
              <td>5</td>
              <td><input class="form-control" type="text" size="20" name="road_course[]" value=""></td>
              <td><input class="form-control" type="text" size="4" name="road_distance[]" class="road_distance" value="" onBlur="calcDistance();">km</td>
              <td><input class="form-control" type="text" size="4" name="road_runtime[]" class="road_runtime" value=""></td>
              <td><input class="form-control" type="text" size="4" name="road_cost[]" class="road_cost" value="" onBlur="calcCost();">원</td>
            </tr>
            <tr>
              <td>6</td>
              <td><input class="form-control" type="text" size="20" name="road_course[]" value=""></td>
              <td><input class="form-control" type="text" size="4" name="road_distance[]" class="road_distance" value="" onBlur="calcDistance();">km</td>
              <td><input class="form-control" type="text" size="4" name="road_runtime[]" class="road_runtime" value=""></td>
              <td><input class="form-control" type="text" size="4" name="road_cost[]" class="road_cost" value="" onBlur="calcCost();">원</td>
            </tr>
            <tr>
              <td>7</td>
              <td><input class="form-control" type="text" size="20" name="road_course[]" value=""></td>
              <td><input class="form-control" type="text" size="4" name="road_distance[]" class="road_distance" value="" onBlur="calcDistance();">km</td>
              <td><input class="form-control" type="text" size="4" name="road_runtime[]" class="road_runtime" value=""></td>
              <td><input class="form-control" type="text" size="4" name="road_cost[]" class="road_cost" value="" onBlur="calcCost();">원</td>
            </tr>
            <tr>
              <th>합계</th>
              <td>&nbsp;</td>
              <td><input class="form-control" readonly type="text" size="4" class="totalDistance" value="0">km</td>
              <td>&nbsp;</td>
              <td><input class="form-control" readonly type="text" size="4" class="totalCost" value="0">원</td>
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
              <td width="18%"><input class="form-control" readonly type="text" size="3" class="totalDistance" name="drivingFuel[]" value="0">km</td>
              <td width="9%">통행료</td>
              <td width="16%"><input class="form-control" type="text" size="4" class="drivingCost" name="drivingCost[]" value="0">원</td>
              <td width="12%">일정추가</td>
              <td width="16%"><input class="form-control" type="text" size="4" class="drivingAdd" name="drivingAdd[]" value="">원</td>
            </tr>
            <tr>
              <td>연비</td>
              <td><input class="form-control" readonly type="text" size="3" class="fuelCost" name="drivingFuel[]" value="0">km</td>
              <td>주차비</td>
              <td><input class="form-control" type="text" size="4" class="drivingCost" name="drivingCost[]" value="6000">원</td>
              <td>성수기</td>
              <td><input class="form-control" type="text" size="4" class="drivingAdd drivingPeak" name="drivingAdd[]" value="">원</td>
            </tr>
            <tr>
              <td>시세</td>
              <td><input class="form-control" type="text" size="3" class="fuelPrice" name="drivingFuel[]" value="" onBlur="calcGasCost();">원/L</td>
              <td>식대</td>
              <td><input class="form-control" type="text" size="4" class="drivingCost" name="drivingCost[]" value="10000">원</td>
              <td>승객수당</td>
              <td><input class="form-control" type="text" size="4" class="drivingAdd" name="drivingAdd[]" value=""/>원</td>
            </tr>
            <tr>
              <td colspan="2">&nbsp;</td>
              <td>숙박</td>
              <td><input class="form-control" type="text" size="4" class="drivingCost" name="drivingCost[]" value="">원</td>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2"><input class="form-control" readonly type="text" size="6" id="defaultBusCost" name="drivingDefault" value="">원</td>
              <td>합계</td>
              <td><input class="form-control" readonly type="text" size="3" id="drivingFuelTotal" name="drivingFuel[]" value="0">원</td>
              <td>합계</td>
              <td><input class="form-control" readonly type="text" size="4" id="drivingCostTotal" name="drivingCost[]" value="16000">원</td>
              <td>합계</td>
              <td><input class="form-control" readonly type="text" size="4" id="drivingAddTotal" name="drivingAdd[]" value="0">원</td>
            </tr>
            <tr>
              <th colspan="2">운행견적총액</th>
              <td colspan="5">&nbsp;</td>
              <td colspan="2"><input class="form-control" readonly type="text" size="4" id="totalBusCost" name="drivingTotal" value="16000">원</td>
            </tr>
            <tr><td colspan="9"></td></tr>
          </tbody>
        </table>

        <h2>■ 산행 공지</h2>
        <table class="table form-small">
          <colgroup>
            <col width="150">
          </colgroup>
          <tbody>
            <tr>
              <th>기획의도</th>
              <td><textarea name="plan" id="plan" rows="10" cols="100"></textarea></td>
            </tr>
            <tr>
              <th>핵심안내</th>
              <td><textarea name="point" id="point" rows="10" cols="100"></textarea></td>
            </tr>
            <tr>
              <th>타임테이블</th>
              <td><textarea name="timetable" id="timetable" rows="10" cols="100"></textarea></td>
            </tr>
            <tr>
              <th>산행안내</th>
              <td><textarea name="information" id="information" rows="10" cols="100"></textarea></td>
            </tr>
            <tr>
              <th>산행코스안내</th>
              <td><textarea name="course" id="course" rows="10" cols="100"></textarea></td>
            </tr>
            <tr>
              <th>산행지 소개</th>
              <td><textarea name="intro" id="intro" rows="10" cols="100"></textarea></td>
            </tr>
            <tr>
              <th>산행지 사진</th>
              <td><input type="file" name="file"></td>
            </tr>
            <tr>
              <th>산행 지도 사진</th>
              <td><input type="file" name="map"></td>
            </tr>
            <tr><td colspan="2"></td></tr>
          </tbody>
        </table>
        <div class="text-center mb-5">
          <button type="button" class="btn btn-primary btn-entry">등록합니다</button>
        </div>
      </form>
    </div>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script>
    $(document).ready(function(){
      $("#startDatePicker").datepicker({
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

      $("#endDatePicker").datepicker({
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

    var oEditors = [];
    nhn.husky.EZCreator.createInIFrame({
      oAppRef: oEditors,
      elPlaceHolder: 'article',
      sSkinURI: '/public/editor/SmartEditor2Skin.html',
      fCreator: 'createSEditor2'
    });
    var oEditors2 = [];
    nhn.husky.EZCreator.createInIFrame({
      oAppRef: oEditors2,
      elPlaceHolder: 'plan',
      sSkinURI: '/public/editor/SmartEditor2Skin.html',
      fCreator: 'createSEditor2'
    });
    var oEditors3 = [];
    nhn.husky.EZCreator.createInIFrame({
      oAppRef: oEditors3,
      elPlaceHolder: 'point',
      sSkinURI: '/public/editor/SmartEditor2Skin.html',
      fCreator: 'createSEditor2'
    });
    var oEditors4 = [];
    nhn.husky.EZCreator.createInIFrame({
      oAppRef: oEditors4,
      elPlaceHolder: 'timetable',
      sSkinURI: '/public/editor/SmartEditor2Skin.html',
      fCreator: 'createSEditor2'
    });
    var oEditors5 = [];
    nhn.husky.EZCreator.createInIFrame({
      oAppRef: oEditors5,
      elPlaceHolder: 'information',
      sSkinURI: '/public/editor/SmartEditor2Skin.html',
      fCreator: 'createSEditor2'
    });
    var oEditors6 = [];
    nhn.husky.EZCreator.createInIFrame({
      oAppRef: oEditors6,
      elPlaceHolder: 'course',
      sSkinURI: '/public/editor/SmartEditor2Skin.html',
      fCreator: 'createSEditor2'
    });
    var oEditors7 = [];
    nhn.husky.EZCreator.createInIFrame({
      oAppRef: oEditors7,
      elPlaceHolder: 'intro',
      sSkinURI: '/public/editor/SmartEditor2Skin.html',
      fCreator: 'createSEditor2'
    });
    </script>
