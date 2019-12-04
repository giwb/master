<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">진행중 산행 예약 보기</h1>
        </div>
      </div>
    </div>

    <div class="sub-contents">
      <h2><b>[<?=viewStatus($view['status'])?>]</b> <?=$view['subject']?></h2>
      산행분담금 : <?=number_format($view['cost_total'] == 0 ? $view['cost'] : $view['cost_total'])?>원 (<?=calcTerm($view['startdate'], $view['starttime'], $view['enddate'], $view['schedule'])?>)<br>
      산행일시 : <?=$view['startdate']?> (<?=calcWeek($view['startdate'])?>) <?=$view['starttime']?><br>
      예약인원 : <?=cntRes($view['idx'])?>명<br>

      <div class="area-reservation">
        <div class="area-btn">
          <div class="float-left">
            <button type="button" class="btn btn-secondary">승차</button>
            <a href="<?=base_url()?>admin/main_change_seat/<?=$view['idx']?>"><button type="button" class="btn btn-secondary">좌석</button></a>
            <button type="button" class="btn btn-secondary">정산</button>
            <button type="button" class="btn btn-secondary">문자</button>
          </div>
          <div class="float-right">
            <select name="status" class="form-control change-status">
              <option value="">산행 상태</option>
              <option value="">------------</option>
              <option<?=$view['status'] == STATUS_PLAN ? ' selected' : ''?> value="<?=STATUS_PLAN?>">계획</option>
              <option<?=$view['status'] == STATUS_ABLE ? ' selected' : ''?> value="<?=STATUS_ABLE?>">예정</option>
              <option<?=$view['status'] == STATUS_CONFIRM ? ' selected' : ''?> value="<?=STATUS_CONFIRM?>">확정</option>
              <option<?=$view['status'] == STATUS_CANCEL ? ' selected' : ''?> value="<?=STATUS_CANCEL?>">취소</option>
              <option<?=$view['status'] == STATUS_CLOSED ? ' selected' : ''?> value="<?=STATUS_CLOSED?>">종료</option>
            </select>
          </div>
        </div>

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
                <th colspan="10"><?=$bus?>호차 - <?=$value['bus_name']?> (<?=$value['bus_owner']?>)</td>
              </tr>
            </thead>
            <tbody>
<?php if ($value['seat'] > 12): ?>
              <tr>
                <th colspan="4" align="left">운전석</th>
                <th colspan="6" style="text-align: right;">출입구</th>
              </tr>
<?php endif; ?>
<?php
    // 버스 형태 좌석 배치
    foreach (range(1, $value['seat']) as $seat):
      $tableMake = getBusTableMake($value['seat'], $seat); // 버스 좌석 테이블 만들기
      $reserveInfo = getAdminReserve($reservation, $bus, $seat); // 예약자 정보

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

        <form id="reserveForm" method="post" action="<?=base_url()?>admin/reserve_complete">
          <div id="addedInfo"></div>
          <input type="hidden" name="idx" value="<?=$view['idx']?>">
          <button type="button" class="btn btn-primary btn-reserve-confirm">예약을 확정합니다</button>
        </form>

      </div>
    </div>
