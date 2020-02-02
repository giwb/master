<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div id="content" class="mb-5">
          <div class="row align-items-center border-bottom text-center mb-4 pt-3 pb-3">
            <div class="col"><a href="<?=BASE_URL?>/admin/main_entry/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-default w-100">수정</button></a></div>
            <div class="col"><a href="<?=BASE_URL?>/admin/main_notice/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-default w-100">공지</button></a></div>
            <div class="col"><a href="<?=BASE_URL?>/admin/main_view_progress/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-default w-100">예약</button></a></div>
            <div class="col"><a href="<?=BASE_URL?>/admin/main_view_boarding/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-secondary w-100">승차</button></a></div>
            <div class="col"><a href="<?=BASE_URL?>/admin/main_view_sms/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-default w-100">문자</button></a></div>
            <div class="col"><a href="<?=BASE_URL?>/admin/main_view_adjust/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-default w-100">정산</button></a></div>
          </div>
          <div class="sub-contents">
            <h2><b><?=viewStatus($view['status'])?></b> <?=$view['subject']?></h2>
            산행일시 : <?=$view['startdate']?> (<?=calcWeek($view['startdate'])?>) <?=$view['starttime']?><br>
            참가비용 : <?=number_format($view['cost_total'] == 0 ? $view['cost'] : $view['cost_total'])?>원 (<?=calcTerm($view['startdate'], $view['starttime'], $view['enddate'], $view['schedule'])?>)<br>
            예약인원 : <?=cntRes($view['idx'])?>명<br>

            <div class="col-sm-3 mt-3 pl-0">
              <select name="status" class="form-control form-control-sm change-status-modal">
                <option value="">산행 상태</option>
                <option value="">------------</option>
                <option<?=$view['status'] == STATUS_PLAN ? ' selected' : ''?> value="<?=STATUS_PLAN?>">계획</option>
                <option<?=$view['status'] == STATUS_ABLE ? ' selected' : ''?> value="<?=STATUS_ABLE?>">예정</option>
                <option<?=$view['status'] == STATUS_CONFIRM ? ' selected' : ''?> value="<?=STATUS_CONFIRM?>">확정</option>
                <option<?=$view['status'] == STATUS_CANCEL ? ' selected' : ''?> value="<?=STATUS_CANCEL?>">취소</option>
                <option<?=$view['status'] == STATUS_CLOSED ? ' selected' : ''?> value="<?=STATUS_CLOSED?>">종료</option>
              </select>
            </div>

            <div class="area-reservation">
              <?php foreach ($busType as $key => $value): $bus = $key + 1; // 이번 산행에 등록된 버스 루프 ?>
              <div class="admin-bus-table">
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
                      <th colspan="10"><?=$bus?>호차 - <?=$value['bus_name']?> <?=!empty($value['bus_license']) ? '(' . $value['bus_license'] . ')' : ''?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($value['seat'] > 12): ?>
                    <tr>
                      <th colspan="4" class="text-left">운전석<?=!empty($value['bus_owner']) ? ' (' . $value['bus_owner'] . ' 기사님)' : ''?></th>
                      <th colspan="6" class="text-right">출입구 (예약 : <?=cntRes($view['idx'], $bus)?>명)</th>
                    </tr>
                    <?php endif; ?>
                    <?php
                        // 버스 형태 좌석 배치
                        $maxRes = 0;
                        foreach (range(1, $value['seat']) as $seat):
                          $tableMake = getBusTableMake($value['seat'], $seat); // 버스 좌석 테이블 만들기
                          $reserveInfo = getReserveAdmin($reserve, $bus, $seat, $userData, $view['status'], 1); // 예약자 정보
                          $seatNumber = checkDirection($seat, $bus, $view['bustype'], $view['bus']);
                          if (!empty($reserveInfo['idx'])) $maxRes++;
                    ?>
                      <?=$tableMake?>
                      <td class="<?=$reserveInfo['class']?>" data-id="<?=$reserveInfo['idx']?>" data-bus="<?=$bus?>" data-seat="<?=$seat?>"><?=$seatNumber?></td>
                      <td class="<?=$reserveInfo['class']?>" data-id="<?=$reserveInfo['idx']?>" data-bus="<?=$bus?>" data-seat="<?=$seat?>"><?=$reserveInfo['nickname'] != '예약가능' ? $reserveInfo['nickname'] : ''?></td>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <div class="area-boarding">
                <div class="mb-2">■ <strong>승차위치</strong> (<?=number_format($maxRes)?>명)</div>
                <?php
                  foreach ($value['listLocation'] as $cnt => $location):
                    if ($cnt == 0): $lastData = $location;
                    else:
                ?>
                <dl>
                  <dt><?=$location['time']?> <?=$location['stitle']?> (<?=!empty($location['nickname']) ? count($location['nickname']) : 0?>명)</dt>
                  <dd><?php if (!empty($location['nickname'])): foreach ($location['nickname'] as $n => $nickname): if ($n != 0): ?> / <?php endif; ?><?=$nickname?><?php endforeach; endif; ?></dd>
                </dl>
                <?php
                    endif;
                  endforeach;
                ?>
                <dl>
                  <dt>미지정 (<?=!empty($lastData['nickname']) ? count($lastData['nickname']) : 0?>명)</dt>
                  <dd><?php if (!empty($lastData['nickname'])): foreach ($lastData['nickname'] as $n => $nickname): if ($n != 0): ?> / <?php endif; ?><?=$nickname?><?php endforeach; endif; ?></dd>
                </dl>
              </div>
              <div class="area-boarding">
                <div class="mb-2">■ <strong>포인트 결제</strong> (<?=number_format($value['maxPoint'])?>명)</div>
                <?php foreach ($value['listPoint'] as $point): ?>
                <dl>
                  <dt><?=checkDirection($point['seat'], $bus, $view['bustype'], $view['bus'])?>. <?=$point['nickname']?></dt>
                  <dd><?=$point['point']?></dd>
                </dl>
                <?php endforeach; ?>
              </div>
              <div class="area-boarding">
                <div class="mb-2">■ <strong>요청사항</strong> (<?=number_format($value['maxMemo'])?>명)</div>
                <?php foreach ($value['listMemo'] as $memo): ?>
                <dl>
                  <dt><?=checkDirection($memo['seat'], $bus, $view['bustype'], $view['bus'])?>. <?=$memo['nickname']?></dt>
                  <dd><?=$memo['memo']?></dd>
                </dl>
                <?php endforeach; ?>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
