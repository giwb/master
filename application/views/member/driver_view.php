<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $maxRes = cntRes($viewNotice['idx']); ?>

      <div class="club-main">
        <div class="mypage mt-2">
          <h2>드라이버 페이지</h2>

          <h3 class="mb-3">■ 행사개요</h3>
          <div class="ti mb-2"><strong>・제목</strong> : <?=$viewNotice['subject']?></div>
          <div class="ti mb-2"><strong>・일시</strong> : <?=$viewNotice['startdate']?> (<?=calcWeek($viewNotice['startdate'])?>) <?=$viewNotice['starttime']?></div>
          <div class="ti mb-2">
            <strong>・<span class="text-danger">운행요금</span></strong> : <span class="text-danger"><?=number_format($viewNotice['total_driving_cost'])?>원</span> (예약인원 <?=$maxRes?>명 기준)<br>
            기본요금 : <?=number_format($viewNotice['total_driving_cost1'])?>원<?=$viewNotice['total_driving_cost'] == $viewNotice['total_driving_cost1'] ? ' <span class="text-danger">☜ 현재예약인원</span>' : ''?><br>
            30명이상 : <?=number_format($viewNotice['total_driving_cost2'])?>원<?=$viewNotice['total_driving_cost'] == $viewNotice['total_driving_cost2'] ? ' <span class="text-danger">☜ 현재예약인원</span>' : ''?><br>
            40명이상 : <?=number_format($viewNotice['total_driving_cost3'])?>원<?=$viewNotice['total_driving_cost'] == $viewNotice['total_driving_cost3'] ? ' <span class="text-danger">☜ 현재예약인원</span>' : ''?><br>
            만차요금 : <?=number_format($viewNotice['total_driving_cost4'])?>원<?=$viewNotice['total_driving_cost'] == $viewNotice['total_driving_cost4'] ? ' <span class="text-danger">☜ 현재예약인원</span>' : ''?>
          </div>
          <?php foreach ($viewNotice['road_address'] as $key => $value): if ($key == 0) $title = '만나는 장소 (' . date('H:i', strtotime($viewNotice['starttime']) - (60*30)) . ')'; elseif (count($viewNotice['road_address']) == ($key +1)) $title = '하차지 주소'; else $title = '행선지 ' . $key; ?>
          <div class="ti mb-2">・<strong><?=$title?></strong><br><?=$value?></div>
          <?php endforeach; ?>

          <h3 class="mb-3">■ 버스비용 산출</h3>
          <strong>・기본요금</strong> : <?=number_format($viewNotice['driving_default'])?>원 (<?=!empty($viewNotice['distance']) ? calcDistance($viewNotice['distance']) : ''?>)<br>
          <div class="ti mt-2">
            <strong>・주유비</strong> : <?=!empty($viewNotice['total_fuel']) ? number_format($viewNotice['total_fuel']) : '0'?>원<br>
            총주행 : <?=!empty($viewNotice['driving_fuel'][0]) ? number_format($viewNotice['driving_fuel'][0]) : '0'?>km<br>
            연　비 : <?=!empty($viewNotice['driving_fuel'][1]) ? number_format($viewNotice['driving_fuel'][1]) : '0'?>ℓ<br>
            시　세 : <?=!empty($viewNotice['driving_fuel'][2]) ? number_format($viewNotice['driving_fuel'][2]) : '0'?>원/ℓ<br>
          </div>
          <div class="ti mt-2">
            <strong>・운행비</strong> : <?=number_format($viewNotice['total_cost'])?>원<br>
            통행료 : <?=!empty($viewNotice['driving_cost'][0]) ? number_format($viewNotice['driving_cost'][0]) : '0'?>원<br>
            주차비 : <?=!empty($viewNotice['driving_cost'][1]) ? number_format($viewNotice['driving_cost'][1]) : '0'?>원<br>
            식　대 : <?=!empty($viewNotice['driving_cost'][2]) ? number_format($viewNotice['driving_cost'][2]) : '0'?>원<br>
            숙　박 : <?=!empty($viewNotice['driving_cost'][3]) ? number_format($viewNotice['driving_cost'][3]) : '0'?>원<br>
          </div>
          <div class="ti mt-2">
            <strong>・추가비용</strong> : <?=number_format($viewNotice['total_add'])?>원<br>
            추가일정 : <?=!empty($viewNotice['driving_add'][0]) ? number_format($viewNotice['driving_add'][0]) : '0'?>원 (<?=calcTerm($viewNotice['startdate'], $viewNotice['starttime'], $viewNotice['enddate'], $viewNotice['schedule'])?>)<br>
            여행시기 : <?=!empty($viewNotice['driving_add'][1]) ? number_format($viewNotice['driving_add'][1]) . '원 (성수기)' : '0원 (비수기)'?><br>
            승객수당 : <?=number_format($viewNotice['cost_driver'])?>원 (예약인원 <?=$maxRes?>명)<br>
          </div>
          <div class="mt-2"><strong>・운행견적총액</strong> : <?=number_format($viewNotice['driving_total'])?>원</div>

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
          <div class="text-center mt-4">
            <a href="<?=BASE_URL?>/member"><button class="btn btn-primary">목록으로</button></a>
          </div>
        </div>
      </div>
