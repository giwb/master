<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">진행중 산행 예약 관리</h1>
        </div>
      </div>
    </div>

    <div class="sub-contents">
      <h2><b><?=viewStatus($view['status'])?></b> <?=$view['subject']?></h2>
      산행분담금 : <?=number_format($view['cost_total'] == 0 ? $view['cost'] : $view['cost_total'])?>원 (<?=calcTerm($view['startdate'], $view['starttime'], $view['enddate'], $view['schedule'])?>)<br>
      산행일시 : <?=$view['startdate']?> (<?=calcWeek($view['startdate'])?>) <?=$view['starttime']?><br>
      예약인원 : <?=cntRes($view['idx'])?>명<br>

      <div class="area-reservation">
        <div class="area-btn">
          <div class="float-left">
            <a href="<?=base_url()?>admin/main_view_progress/<?=$view['idx']?>"><button type="button" class="btn btn-secondary">예약관리</button></a>
            <a href="<?=base_url()?>admin/main_view_boarding/<?=$view['idx']?>"><button type="button" class="btn btn-primary">승차관리</button></a>
            <a href="<?=base_url()?>admin/main_view_sms/<?=$view['idx']?>"><button type="button" class="btn btn-primary">문자양식</button></a>
            <a href="<?=base_url()?>admin/main_view_adjust/<?=$view['idx']?>"><button type="button" class="btn btn-primary">정산관리</button></a>
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
          foreach ($busType as $key => $value): $bus = $key + 1;
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
                <th colspan="10"><?=$bus?>호차 - <?=$value['bus_name']?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($value['seat'] > 12): ?>
              <tr>
                <th colspan="4" align="left">운전석<?=!empty($value['bus_owner']) ? ' (' . $value['bus_owner'] . ' 기사님)' : ''?></th>
                <th colspan="6" style="text-align: right;">출입구</th>
              </tr>
              <?php endif; ?>
              <?php
                  // 버스 형태 좌석 배치
                  foreach (range(1, $value['seat']) as $seat):
                    $tableMake = getBusTableMake($value['seat'], $seat); // 버스 좌석 테이블 만들기
                    $reserveInfo = getReserveAdmin($reserve, $bus, $seat, $userData, $view['status']); // 예약자 정보
                    $seatNumber = checkDirection($seat, $bus, $view['bustype'], $view['bus']);
              ?>
                <?=$tableMake?>
                <td class="<?=$reserveInfo['class']?>" data-id="<?=$reserveInfo['idx']?>" data-bus="<?=$bus?>" data-seat="<?=$seat?>"><?=$seatNumber?></td>
                <td class="<?=$reserveInfo['class']?>" data-id="<?=$reserveInfo['idx']?>" data-bus="<?=$bus?>" data-seat="<?=$seat?>"><?=$reserveInfo['nickname']?></td>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php endforeach; ?>

        <form id="reserveForm" method="post" action="<?=base_url()?>admin/reserve_complete">
          <div id="addedInfo"></div>
          <input type="hidden" name="idx" value="<?=$view['idx']?>">
          <button type="button" class="btn btn-primary btn-reserve-confirm">예약을 확정합니다</button>
        </form>
      </div>

      <div class="area-wait">
      ■ <strong>대기자 목록</strong><br>
        <table>
          <tr>
            <th>번호</th>
            <th>등록일시</th>
            <th>닉네임</th>
            <th>실명</th>
            <th>성별</th>
            <th>생년월일</th>
            <th>연락처</th>
            <th>&nbsp;</th>
          </tr>
        <?php foreach ($wait as $key => $value): ?>
          <tr>
            <td><?=$key + 1?></td>
            <td><?=date('Y-m-d H:i:s', $value['created_at'])?></td>
            <td><?=$value['nickname']?></td>
            <td><?=$value['realname']?></td>
            <td><?=getGender($value['gender'])?></td>
            <td><?=$value['birthday']?></td>
            <td><?=$value['phone']?></td>
            <td><button class="btn btn-sm btn-primary">삭제</button></td>
          </tr>
        <?php endforeach; ?>
        </table>
      </div>
    </div>
