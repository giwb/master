<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="sub-contents">
          <div class="sub-title">
            <div class="area-title"><h2><b>[<?=viewStatus($notice['status'])?>]</b> <?=$notice['subject']?></h2></div>
            <div class="area-btn"><a href="<?=base_url()?>reserve/notice/<?=$view['idx']?>?n=<?=$notice['idx']?>"><button type="button" class="btn btn-primary">산행공지</button></a></div>
          </div>
          산행일시 : <?=$notice['startdate']?> (<?=calcWeek($notice['startdate'])?>) <?=$notice['starttime']?><br>
          산행분담금 : <?=number_format($notice['cost'])?>원 (<?=calcTerm($notice['startdate'], $notice['starttime'], $notice['enddate'], $notice['schedule'])?>, <?=calcDistance($notice['distance'])?><?=!empty($notice['costmemo']) ? ', ' . $notice['costmemo'] : ''?>)<br>
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
                    <th colspan="7" style="border-right: 0px;"><?=count($busType) >= 2 ? $bus . '호차 - ' : ''?><?=$value['bus_name']?> (<?=$value['bus_owner']?> 기사님)</td>
                    <th colspan="3" style="border-left: 0px;" class="text-right">예약 : <?=cntRes($notice['idx'], $bus)?>명</th>
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
      $reserveInfo = getReserve($reserve, $bus, $seat, $userData); // 예약자 정보
?>
                    <?=$tableMake?>
                    <td><?=$seat?></td>
                    <td class="<?=$reserveInfo['class']?>" data-id="<?=$reserveInfo['idx']?>" data-bus="<?=$bus?>" data-seat="<?=$seat?>"><?=$reserveInfo['nickname']?></td>
<?php
    endforeach;
?>
                  </tbody>
                </table>
              </div>
<?php
  endforeach;
?>
              <form id="reserveForm" method="post" action="<?=base_url()?>reserve/insert">
                <div id="addedInfo"></div>
                <input type="hidden" name="club_idx" value="<?=$view['idx']?>">
                <input type="hidden" name="notice_idx" value="<?=$notice['idx']?>">
                <button type="button" class="btn btn-primary btn-reserve-confirm">예약합니다</button>
              </form>
            </div>
          </div>
        </div>
