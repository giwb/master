<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="mypage mt-2">
          <h2>드라이버 페이지</h2>

          <h3 class="mb-3">■ 산행개요</h3>
          <div class="ti"><strong>・산행명</strong> : <?=$viewNotice['subject']?></div>
          <div class="ti"><strong>・일시</strong> : <?=$viewNotice['startdate']?> (<?=calcWeek($viewNotice['startdate'])?>) <?=$viewNotice['starttime']?></div>
          <?php $viewNotice['cost'] = $viewNotice['cost_total'] == 0 ? $viewNotice['cost'] : $viewNotice['cost_total']; if (!empty($viewNotice['cost'])): ?>
          <?php if (!empty($viewNotice['sido'])): ?>
          <div class="ti"><strong>・지역</strong> : <?php foreach ($viewNotice['sido'] as $key => $value): if ($key != 0): ?>, <?php endif; ?><?=$value?> <?=!empty($viewNotice['gugun'][$key]) ? $viewNotice['gugun'][$key] : ''?><?php endforeach; ?></div>
          <?php endif; ?>
          <div class="ti"><strong>・분담금</strong> : <?=number_format($viewNotice['cost_total'] == 0 ? $viewNotice['cost'] : $viewNotice['cost_total'])?>원 (<?=calcTerm($viewNotice['startdate'], $viewNotice['starttime'], $viewNotice['enddate'], $viewNotice['schedule'])?><?=!empty($viewNotice['distance']) ? ', ' . calcDistance($viewNotice['distance']) : ''?><?=!empty($viewNotice['costmemo']) ? ', ' . $viewNotice['costmemo'] : ''?>)</div>
            <?php endif; ?>
          <?=!empty($viewNotice['content']) ? '<div class="ti"><strong>・코스</strong> : ' . nl2br($viewNotice['content']) . '</div>' : ''?>
          <div class="ti"><strong>・예약인원</strong> : 최대 <?=$maxSeat?>석중 <?=cntRes($viewNotice['idx'])?>명</div>
          <div class="ti"><strong>・승객수당</strong> : <?=number_format($viewNotice['cost_driver'])?>원</div>

          <h3 class="mb-3">■ 운행거리 및 통행료</h3>
          <?php if (!empty($viewNotice['road_course'])): foreach ($viewNotice['road_course'] as $key => $value): if (!empty($value)): ?>
          <div class="border-bottom pb-2 mb-2">
            <strong>・제<?=$key+1?>운행구간</strong> : <?=$value?><br>
            <strong>・도착지 주소</strong> : <?=!empty($viewNotice['road_address'][$key]) ? $viewNotice['road_address'][$key] : ''?><br>
            <strong>・운행거리</strong> : <?=!empty($viewNotice['road_distance'][$key]) ? $viewNotice['road_distance'][$key] : '0'?>km<br>
            <strong>・소요시간</strong> : <?=!empty($viewNotice['road_runtime'][$key]) ? $viewNotice['road_runtime'][$key] : ''?><br>
            <strong>・통행료</strong> : <?=!empty($viewNotice['road_cost'][$key]) ? number_format($viewNotice['road_cost'][$key]) : '0'?>원<br>
          </div>
          <?php endif; endforeach; endif; ?>

          <h3 class="mb-3">■ 버스비용</h3>
          <strong>・기본요금</strong> : <?=number_format($viewNotice['driving_default'])?>원<br>
          <div class="ti">
            <strong>・주유비</strong><br>
            총주행 : <?=!empty($viewNotice['driving_fuel'][0]) ? number_format($viewNotice['driving_fuel'][0]) : '0'?>km<br>
            연　비 : <?=!empty($viewNotice['driving_fuel'][1]) ? number_format($viewNotice['driving_fuel'][1]) : '0'?>km<br>
            시　세 : <?=!empty($viewNotice['driving_fuel'][2]) ? number_format($viewNotice['driving_fuel'][2]) : '0'?>원/L<br>
            합　계 : <?=!empty($viewNotice['total_fuel']) ? number_format($viewNotice['total_fuel']) : '0'?>원<br>
          </div>
          <div class="ti">
            <strong>・운행비</strong><br>
            통행료 : <?=!empty($viewNotice['driving_cost'][0]) ? number_format($viewNotice['driving_cost'][0]) : '0'?>원<br>
            주차비 : <?=!empty($viewNotice['driving_cost'][1]) ? number_format($viewNotice['driving_cost'][1]) : '0'?>원<br>
            식　대 : <?=!empty($viewNotice['driving_cost'][2]) ? number_format($viewNotice['driving_cost'][2]) : '0'?>원<br>
            숙　박 : <?=!empty($viewNotice['driving_cost'][3]) ? number_format($viewNotice['driving_cost'][3]) : '0'?>원<br>
            합　계 : <?=!empty($viewNotice['total_cost']) ? number_format($viewNotice['total_cost']) : '0'?>원<br>
          </div>
          <div class="ti">
            <strong>・추가비용</strong><br>
            일정추가 : <?=!empty($viewNotice['driving_add'][0]) ? number_format($viewNotice['driving_add'][0]) : '0'?>원<br>
            성 수 기&nbsp; : <?=!empty($viewNotice['driving_add'][1]) ? number_format($viewNotice['driving_add'][1]) : '0'?>원<br>
            승객수당 : <?=number_format($viewNotice['cost_driver'])?>원<br>
            합　　계 : <?=number_format($viewNotice['total_add'])?>원<br>
          </div>
          <strong>・운행견적총액</strong> : <?=number_format($viewNotice['driving_total'])?>원
        </div>
        <div class="text-center border-top pt-4">
          <a href="<?=base_url()?>member"><button class="btn btn-primary">목록으로</button></a>
        </div>
      </div>
