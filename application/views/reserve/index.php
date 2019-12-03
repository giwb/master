<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
<?php if (empty($notice['idx'])): ?>
        <div class="text-center mt-5">데이터가 없습니다.</div>
<?php else: ?>
        <div class="sub-contents">
          <div class="sub-title">
            <div class="area-title"><h2><b>[<?=viewStatus($notice['status'])?>]</b> <?=$notice['subject']?></h2></div>
            <div class="area-btn"><a href="<?=base_url()?>reserve/notice/<?=$view['idx']?>?n=<?=$notice['idx']?>"><button type="button" class="btn btn-primary">산행공지</button></a></div>
          </div>
          <strong>・산행일시</strong> : <?=$notice['startdate']?> (<?=calcWeek($notice['startdate'])?>) <?=$notice['starttime']?><br>
          <?php if (!empty($notice['cost'])): ?>
          <strong>・산행분담금</strong> : <?=number_format($notice['cost'])?>원 (<?=calcTerm($notice['startdate'], $notice['starttime'], $notice['enddate'], $notice['schedule'])?><?=!empty($notice['distance']) ? ', ' . calcDistance($notice['distance']) : ''?><?=!empty($notice['costmemo']) ? ', ' . $notice['costmemo'] : ''?>)<br>
          <?php endif; ?>
          <?=!empty($notice['content']) ? "<strong>・산행코스</strong> : " . $notice['content'] : ""?>

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
                    <th colspan="5" style="border-right: 0px;"><?=count($busType) >= 2 ? $bus . '호차 - ' : ''?><?=$value['bus_name']?> (<?=$value['bus_owner']?> 기사님)</td>
                    <th colspan="5" style="border-left: 0px;" class="text-right">예약 : <?=cntRes($notice['idx'], $bus)?>명</th>
                  </tr>
                </thead>
                <tbody>
<?php if ($value['seat'] > 13): ?>
              <tr>
                <th colspan="4" align="left">운전석</th>
                <th colspan="6" style="text-align: right;">출입구</th>
              </tr>
<?php endif; ?>
<?php
    // 버스 형태 좌석 배치
    foreach (range(1, $value['seat']) as $seat):
      $tableMake = getBusTableMake($value['seat'], $seat); // 버스 좌석 테이블 만들기
      $reserveInfo = getReserve($reserve, $bus, $seat, $userData, $notice['status']); // 예약자 정보

      if ($value['direction'] == 1) {
        // 역방향 (좌석 번호는 그대로 놔둔 상태에서 표시만 역방향으로 한다)
        if ($seat%4 == 1) $seatNumber = $seat + 3;
        elseif ($seat%4 == 2) $seatNumber = $seat + 1;
        elseif ($seat%4 == 3) $seatNumber = $seat - 1;
        elseif ($seat%4 == 0) $seatNumber = $seat - 3;
      } else {
        $seatNumber = $seat;
      }
?>
                <?=$tableMake?>
                <td><?=$seatNumber?></td>
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
                <input type="hidden" name="clubIdx" value="<?=!empty($view['idx']) ? $view['idx'] : ''?>">
                <input type="hidden" name="userIdx" value="<?=!empty($userData['idx']) ? $userData['idx'] : ''?>">
                <input type="hidden" name="noticeIdx" value="<?=!empty($notice['idx']) ? $notice['idx'] : ''?>">
                <button type="button" class="btn btn-primary btn-reserve-confirm">예약합니다</button>
              </form>
            </div>
          </div>
<?php endif; ?>
        </div>
