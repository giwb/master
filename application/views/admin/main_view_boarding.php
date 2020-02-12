<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <?=$headerMenuView?>
        <div id="content" class="mb-5">
          <div class="sub-contents">
            <h2 class="m-0 p-0 pb-2"><b><?=viewStatus($view['status'])?></b> <?=$view['subject']?></h2>
            <?php if (!empty($view['type'])): ?><div class="ti"><strong>・유형</strong> : <?=$view['type']?></div><?php endif; ?>
            <div class="ti"><strong>・일시</strong> : <?=$view['startdate']?> (<?=calcWeek($view['startdate'])?>) <?=$view['starttime']?></div>
            <?php $view['cost'] = $view['cost_total'] == 0 ? $view['cost'] : $view['cost_total']; if (!empty($view['cost'])): ?>
            <?php if (!empty($view['sido'])): ?>
            <div class="ti"><strong>・지역</strong> : <?php foreach ($view['sido'] as $key => $value): if ($key != 0): ?>, <?php endif; ?><?=$value?> <?=!empty($view['gugun'][$key]) ? $view['gugun'][$key] : ''?><?php endforeach; ?></div>
            <?php endif; ?>
            <div class="ti"><strong>・요금</strong> : <?=number_format($view['cost_total'] == 0 ? $view['cost'] : $view['cost_total'])?>원 (<?=calcTerm($view['startdate'], $view['starttime'], $view['enddate'], $view['schedule'])?><?=!empty($view['distance']) ? ', ' . calcDistance($view['distance']) : ''?><?=!empty($view['options']) ? ', ' . getOptions($view['options']) : ''?><?=!empty($view['options_etc']) ? ', ' . $view['options_etc'] : ''?><?=!empty($view['options']) || !empty($view['options_etc']) ? ' 제공' : ''?><?=!empty($view['costmemo']) ? ', ' . $view['costmemo'] : ''?>)</div>
            <?php endif; ?>
            <?=!empty($view['content']) ? '<div class="ti"><strong>・코스</strong> : ' . nl2br($view['content']) . '</div>' : ''?>
            <?=!empty($view['kilometer']) ? '<div class="ti"><strong>・거리</strong> : ' . $view['kilometer'] . '</div>' : ''?>
            <div class="ti"><strong>・예약</strong> : <?=cntRes($view['idx'])?>명</div>

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
                    <?php if ($value['seat'] > 15): ?>
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
                          $reserveInfo = getReserveAdmin($reserve, $bus, $seat, $userData); // 예약자 정보
                          $seatNumber = checkDirection($seat, $bus, $view['bustype'], $view['bus']);
                          if (!empty($reserveInfo['idx'])) $maxRes++;
                    ?>
                      <?=$tableMake?>
                      <td class="<?=$reserveInfo['class']?>" data-id="<?=$reserveInfo['idx']?>" <?=!empty($reserveInfo['priority']) ? ' data-priority="' . $reserveInfo['priority'] . '"' : ''?> data-bus="<?=$bus?>" data-seat="<?=$seat?>"><?=$seatNumber?></td>
                      <td class="<?=$reserveInfo['class']?>" data-id="<?=$reserveInfo['idx']?>" <?=!empty($reserveInfo['priority']) ? ' data-priority="' . $reserveInfo['priority'] . '"' : ''?> data-bus="<?=$bus?>" data-seat="<?=$seat?>"><?=$reserveInfo['nickname']?></td>
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
              <div class="area-boarding">
                <div class="mb-2">■ <strong>구매대행</strong> (<?=number_format($maxPurchase['cnt'])?>명)</div>
                <?php foreach ($listPurchase as $purchase): ?>
                <dl>
                  <dt><?=$purchase['nickname']?></dt>
                  <dd>
                  <?php foreach ($purchase['listCart'] as $key => $item): ?>
                    <span class="area-status"><?=getPurchaseStatus($purchase['status'])?></span> <?=$item['name']?><br><small><?=!empty($item['option']) ? $item['option'] . ' - ' : ''?><?=number_format($item['amount'])?>개, <?=number_format($item['cost'] * $item['amount'])?>원</small><br>
                  <?php endforeach; ?>
                  </dd>
                </dl>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
