<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="mypage mt-2">
          <h2>드라이버 페이지</h2>

          <h3 class="mb-3">■ 산행개요</h3>
          <div class="ti"><strong>・제목</strong> : <?=$viewNotice['subject']?></div>
          <div class="ti"><strong>・일시</strong> : <?=$viewNotice['startdate']?> (<?=calcWeek($viewNotice['startdate'])?>) <?=$viewNotice['starttime']?></div>
          <div class="ti"><strong>・운행견적총액</strong> : <?=number_format($viewNotice['driving_total'])?>원</div>
          <?php foreach ($viewNotice['road_address'] as $key => $value): ?>
          <div class="ti"><strong>・도착지 주소</strong> : <?=$value?></div>
          <?php endforeach; ?>

          <h3 class="mb-3">■ 버스비용</h3>
          <strong>・기본요금</strong> : <?=number_format($viewNotice['driving_default'])?>원 (<?=!empty($viewNotice['distance']) ? calcDistance($viewNotice['distance']) : ''?>)<br>
          <div class="ti mt-2">
            <strong>・주유비</strong> : <?=!empty($viewNotice['total_fuel']) ? number_format($viewNotice['total_fuel']) : '0'?>원<br>
            총주행 : <?=!empty($viewNotice['driving_fuel'][0]) ? number_format($viewNotice['driving_fuel'][0]) : '0'?>km<br>
            연　비 : <?=!empty($viewNotice['driving_fuel'][1]) ? number_format($viewNotice['driving_fuel'][1]) : '0'?>km<br>
            시　세 : <?=!empty($viewNotice['driving_fuel'][2]) ? number_format($viewNotice['driving_fuel'][2]) : '0'?>원/L<br>
          </div>
          <div class="ti mt-2">
            <strong>・운행비</strong> : <?=!empty($viewNotice['total_cost']) ? number_format($viewNotice['total_cost']) : '0'?>원<br>
            통행료 : <?=!empty($viewNotice['driving_cost'][0]) ? number_format($viewNotice['driving_cost'][0]) : '0'?>원<br>
            주차비 : <?=!empty($viewNotice['driving_cost'][1]) ? number_format($viewNotice['driving_cost'][1]) : '0'?>원<br>
            식　대 : <?=!empty($viewNotice['driving_cost'][2]) ? number_format($viewNotice['driving_cost'][2]) : '0'?>원<br>
            숙　박 : <?=!empty($viewNotice['driving_cost'][3]) ? number_format($viewNotice['driving_cost'][3]) : '0'?>원<br>
          </div>
          <div class="ti mt-2">
            <strong>・추가비용</strong> : <?=number_format($viewNotice['total_add'])?>원<br>
            추가일정 : <?=!empty($viewNotice['driving_add'][0]) ? number_format($viewNotice['driving_add'][0]) : '0'?>원 (<?=calcTerm($viewNotice['startdate'], $viewNotice['starttime'], $viewNotice['enddate'], $viewNotice['schedule'])?>)<br>
            여행시기 : <?=!empty($viewNotice['driving_add'][1]) ? number_format($viewNotice['driving_add'][1]) . '원 (성수기)' : '0원 (비수기)'?><br>
            승객수당 : <?=number_format($viewNotice['cost_driver'])?>원 (예약인원 <?=cntRes($viewNotice['idx'])?>명)<br>
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
        </div>
        <div class="text-center border-top pt-4">
          <a href="<?=base_url()?>member"><button class="btn btn-primary">목록으로</button></a>
        </div>
        <div class="ad-sp">
          <!-- SP_CENTER -->
          <ins class="adsbygoogle"
            style="display:block"
            data-ad-client="ca-pub-2424708381875991"
            data-ad-slot="4319659782"
            data-ad-format="auto"
            data-full-width-responsive="true"></ins>
          <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
          </script>
        </div>
      </div>
