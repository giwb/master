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
      산행분담금 : <?=number_format($view['cost'])?>원 (<?=calcTerm($view['startdate'], $view['starttime'], $view['enddate'], $view['schedule'])?>)<br>
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
            <button type="button" class="btn btn-secondary">숨김</button>
            <button type="button" class="btn btn-secondary">예정</button>
            <button type="button" class="btn btn-secondary">취소</button>
            <button type="button" class="btn btn-secondary">종료</button>
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
              <tr>
                <th colspan="4" align="left">운전석</th>
                <th colspan="6" style="text-align: right;">출입구</th>
              </tr>
<?php
    // 버스 형태 좌석 배치
    foreach (range(1, $value['seat']) as $seat):
      $tableMake = getBusTableMake($value['seat'], $value['direction'], $seat); // 버스 좌석 테이블 만들기
      $reserveInfo = getReserve($reservation, $bus, $seat); // 예약자 정보
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

        <form id="reserveForm" method="post" action="<?=base_url()?>admin/reserve_complete">
          <div id="addedInfo"></div>
          <input type="hidden" name="idx" value="<?=$view['idx']?>">
          <button type="button" class="btn btn-primary btn-reserve-confirm">예약을 확정합니다</button>
        </form>

      </div>
    </div>
