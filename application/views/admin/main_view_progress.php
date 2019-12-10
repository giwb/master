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
                    $reserveInfo = getReserveAdmin($reserve, $bus, $seat, $userData); // 예약자 정보
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
          <button type="button" class="btn btn-primary btn-reserve-confirm">확인</button>
        </form>
      </div>

      <div class="area-wait">
      ■ <strong>대기자</strong><br>
        <table class="mt-2">
          <colgroup>
            <col width="7%">
            <col width="12%">
            <col width="15%">
            <col width="11%">
            <col width="10%">
            <col width="37%">
            <col width="8%">
          </colgroup>
          <thead>
            <tr>
              <th>번호</th>
              <th>등록일시</th>
              <th>닉네임</th>
              <th>탑승위치</th>
              <th>성별</th>
              <th>메모</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="2" class="text-center area-userid"></td>
              <td><input type="hidden" name="created_by"><input type="text" name="nickname" class="form-control form-control-sm search-userid"></td>
              <td>
                <select name="location" class="form-control form-control-sm pl-0 pr-0">
                  <?php foreach ($arrLocation as $value): ?>
                  <option value='<?=$value['no']?>'><?=$value['stitle']?></option>
                  <?php endforeach; ?>
                </select>
              </td>
              <td>
                <select name="gender" class="form-control form-control-sm pl-0 pr-0">
                  <option value='M'>남성</option>
                  <option value='F'>여성</option>
                </select>
              </td>
              <td><input type="text" name="memo" class="form-control form-control-sm"></td>
              <td><button type="button" class="btn btn-sm btn-primary btn-wait-insert">등록</button></td>
            </tr>
            <?php foreach ($wait as $key => $value): ?>
            <tr>
              <td><?=$key + 1?></td>
              <td class="small"><?=substr(date('Y-m-d H:i:s', $value['created_at']), 5, 11)?></td>
              <td><?=$value['nickname']?></td>
              <td><?=arrLocation(NULL, $value['location'], 1)?></td>
              <td><?=getGender($value['gender'])?></td>
              <td class="text-left"><?=$value['memo']?></td>
              <td><button type="button" class="btn btn-sm btn-primary btn-wait-delete-modal" data-idx="<?=$value['idx']?>">삭제</button></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Wait Delete Modal -->
    <div class="modal fade" id="waitModal" tabindex="-1" role="dialog" aria-labelledby="waitModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="smallmodalLabel">대기자 삭제</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-center">
            <p class="modal-message">정말로 삭제하시겠습니까?</p>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="waitIdx">
            <button type="button" class="btn btn-primary btn-wait-delete">삭제합니다</button>
            <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
          </div>
        </div>
      </div>
    </div>
