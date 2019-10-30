<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="sub-contents">
          <h2><b>[<?=viewStatus($notice['status'])?>]</b> <?=$notice['subject']?></h2>
          산행일시 : <?=$notice['startdate']?> (<?=calcWeek($notice['startdate'])?>) <?=$notice['starttime']?><br>
          산행분담금 : <?=number_format($notice['cost'])?>원 (<?=calcTerm($notice['startdate'], $notice['starttime'], $notice['enddate'], $notice['schedule'])?>)<br>
          <?=!empty($notice['content']) ? "산행코스 : " . $notice['content'] : ""?>

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
                    <th colspan="7" style="border-right: 0px;"><?=$bus?>호차 - <?=$value['bus_name']?> (<?=$value['bus_owner']?> 기사님)</td>
                    <th colspan="3" style="border-left: 0px;" class="text-right">예약인원 : <?=cntRes($notice['idx'])?>명</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th colspan="4" class="text-left">운전석</th>
                    <th colspan="6" class="text-right">출입구</th>
                  </tr>
<?php
    // 버스 형태 좌석 배치
    foreach (range(1, $value['seat']) as $seat):
      $tableMake = getBusTableMake($value['seat'], $value['direction'], $seat); // 버스 좌석 테이블 만들기
      $reserveInfo = getReserve($reserve, $bus, $seat); // 예약자 정보
?>
                    <?=$tableMake?>
                    <td><?=$seat?></td>
                    <td class="seat<?=$reserveInfo['class']?>" data-id="<?=$reserveInfo['idx']?>" data-bus="<?=$bus?>" data-seat="<?=$seat?>"><?=$reserveInfo['nickname']?></td>
<?php
    endforeach;
?>
                  </tbody>
                </table>
              </div>
<?php
  endforeach;
?>

              <form id="reserveForm" method="post" action="<?=base_url()?>club/reserve_insert">
                <div id="addedInfo"></div>
                <input type="hidden" name="idx" value="<?=$notice['idx']?>">
                <button type="button" class="btn btn-primary btn-reserve-confirm">예약을 확정합니다</button>
              </form>

            </div>
          </div>
        </div>
