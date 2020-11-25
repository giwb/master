<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">진행중 산행 좌석 변경</h1>
        </div>
      </div>
    </div>

    <div class="sub-contents">
      <h2><b><?=viewStatus($view['status'])?></b> <?=$view['subject']?></h2>
      산행일시 : <?=$view['startdate']?> (<?=calcWeek($view['startdate'])?>) <?=$view['starttime']?><br>
      참가비용 : <?=number_format($view['cost_total'] == 0 ? $view['cost'] : $view['cost_total'])?>원 (<?=calcTerm($view['startdate'], $view['starttime'], $view['enddate'], $view['schedule'])?>)<br>
      예약인원 : <?=cntRes($view['idx'])?>명<br>

      <div class="area-reservation">
        <div class="area-btn">
          <div class="float-left">
            <a href="/admin/main_view_progress/<?=$view['idx']?>"><button type="button" class="btn btn-secondary">예약</button></a>
            <a href="/admin/main_view_boarding/<?=$view['idx']?>"><button type="button" class="btn btn-secondary">승차</button></a>
            <a href="/admin/main_view_adjust/<?=$view['idx']?>"><button type="button" class="btn btn-secondary">정산</button></a>
            <a href="/admin/main_view_sms/<?=$view['idx']?>"><button type="button" class="btn btn-secondary">문자</button></a>
          </div>
          <div class="float-right">
          </div>
        </div>
        <form id="changeSeatForm" method="post" action="/admin/reserve_change_seat">
<?php
  // 이번 산행에 등록된 버스 루프
  $bus = 0;
  $cnt = 0;
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
                <th colspan="10"><?=$bus?>호차 - <?=$value['bus_name']?> (<?=$value['bus_owner']?>)</td>
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
      $tableMake = getBusTableMake($value['seat'], $seat); // 버스 좌석 테이블 만들기
      $reserveInfo = getReserveAdmin($reserve, $bus, $seat, $userData, $view['status']); // 예약자 정보
      $seatNumber = checkDirection($seat, $bus, $view['bustype'], $view['bus']);
?>
                <?=$tableMake?>
                <?php if (!empty($reserveInfo['idx'])): ?>
                <td><input type="text" name="reserve[<?=$cnt?>][seat]" value="<?=$seatNumber?>" class="seat"><input type="hidden" name="reserve[<?=$cnt?>][origin_seat]" value="<?=$seatNumber?>"><input type="hidden" name="reserve[<?=$cnt?>][idx]" value="<?=$reserveInfo['idx']?>"></td>
                <?php $cnt++; else: ?>
                <td><?=$seatNumber?></td>
                <?php endif; ?>
                <td class="<?=$reserveInfo['class']?>" data-id="<?=$reserveInfo['idx']?>" data-bus="<?=$bus?>" data-seat="<?=$seat?>"><?=$reserveInfo['nickname']?></td>
<?php
    endforeach;
?>
            </tbody>
          </table>
        </div><br>
<?php
  endforeach;
?>


        <input type="hidden" name="idx" value="<?=$view['idx']?>">
        <button type="button" class="btn btn-primary btn-change-seat">좌석 변경 완료</button>
        </form>
      </div>
    </div>
