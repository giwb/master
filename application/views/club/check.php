<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="sub-contents">
          <h2><b>[<?=viewStatus($notice['status'])?>]</b> <?=$notice['subject']?></h2>
          산행분담금 : <?=number_format($notice['cost'])?>원 (<?=calcTerm($notice['startdate'], $notice['starttime'], $notice['enddate'], $notice['schedule'])?>)<br>
          산행일시 : <?=$notice['startdate']?> (<?=calcWeek($notice['startdate'])?>) <?=$notice['starttime']?><br>
          예약인원 : <?=cntRes($notice['idx'])?>명<br>

          <div class="area-reservation">
<?php
  // 이번 산행에 등록된 버스 루프
  $bus = 0;
  foreach ($busType as $value): $bus++;
?>
            <div class="area-bus-table">
              <table>
                <colgroup>
                  <col width="4%"></col>
                  <col width="16%"></col>
                  <col width="4%"></col>
                  <col width="16%"></col>
                  <col width="4%"></col>
                  <col width="16%"></col>
                  <col width="4%"></col>
                  <col width="16%"></col>
                  <col width="4%"></col>
                  <col width="16%"></col>
                </colgroup>
                <thead>
                  <tr>
                    <th colspan="10"><?=$bus?>호차 - <?=$value['bus_name']?> (<?=$value['bus_owner']?> 기사님)</td>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th colspan="4" align="left">운전석</th>
                    <th colspan="6" style="text-align: right;">출입구</th>
                  </tr>
<?php
    // 버스 형태 좌석 배치
    foreach (range(1, $value['seat']) as $seat):
      $tableMake = getBusTableMake($value['seat'], $value['direction'], $seat); // 버스 좌석 테이블 만들기
      $reserveInfo = getCheck($reserve, $bus, $seat); // 예약자 정보 확인
?>
                    <?=$tableMake?>
                    <td><?=$seat?></td>
                    <td><?=$reserveInfo['nickname']?></td>
<?php
    endforeach;
?>
                  </tbody>
                </table>
              </div>
<?php
  endforeach;
?>

<div class="div_width">
  <div style="text-align:left; padding-bottom:5px; border:0px solid #d9d9d9; border-bottom-width:1px;">
    <strong><LI>예약정보</strong><br />
  </div>
</div>
<div class="div_width" style="background-color:#d9d9d9;">
<table border="0" width="100%" cellspacing="1" cellpadding="3" class="table">
<colgroup>
  <col width="8%"></col>
  <col width="35%"></col>
  <col width="15%"></col>
  <col width=""></col>
</colgroup>
<tr>
  <th bgcolor="#e9e9e9" rowspan="2">번</td>
  <td bgcolor="#ffffff"></td>
  <td bgcolor="#ffffff"></td>
  <td bgcolor="#ffffff"></td>
</tr>
<tr>
  <td colspan="3" bgcolor="#ffffff"></td>
</tr>
</table>
</div><br />

<div class="div_width">
  <div style="text-align:left; padding-bottom:5px; border:0px solid #d9d9d9; border-bottom-width:1px;">
    <strong><LI>결제정보</strong><br />
  </div>
</div>
<div class="div_width" style="background-color:#d9d9d9;">
<table border="0" width="100%" cellspacing="1" cellpadding="3" class="table">
<tr>
  <th width="14%" bgcolor="#e9e9e9">합계금액</td>
  <td colspan="2" bgcolor="#ffffff">원</td>
</tr>
<tr>
  <th bgcolor="#e9e9e9">결제금액</td>
  <td colspan="2" bgcolor="#ffffff" style="color:red;"><big><strong><span class='totalCost'></span>원</strong></big></td>
</tr>
<tr>
  <th bgcolor="#e9e9e9">포인트 사용</th>
  <td bgcolor="#ffffff">총 0 포인트 중 <input type="text" size="5" value="0" class="pointUse" value="0" /> 포인트 사용</td>
  <td bgcolor="#ffffff"><label class="pointAll"><input type="checkbox" name="pointAll" /> 포인트 전액 사용</label></td>
</tr>
<tr>
  <th bgcolor="#e9e9e9">입금은행</td>
  <td colspan="2" bgcolor="#ffffff">국민은행 / 288001-04-154630 / 경인웰빙산악회 (김영미)</td>
</tr>
<tr>
  <th bgcolor="#e9e9e9">입금자명</td>
  <td colspan="2" bgcolor="#ffffff"><input type="text" size="20" class="depositName" /></td>
</tr>
</table>
</div><br />

<input type="button" value="결제정보등록" class="payment" style="font-size:10pt; padding:5px;" />
<input type="button" value="좌석배치보기" style="font-size:10pt; padding:5px;" />

            </div>
          </div>
        </div>
