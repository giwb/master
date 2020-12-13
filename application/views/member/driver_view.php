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
            기본요금 : <?=number_format($viewNotice['total_driving_cost1'])?>원<?=$viewNotice['cost_standard'] == 0 ? ' <span class="text-danger">☜ 현재예약인원</span>' : ''?><br>
            30명이상 : <?=number_format($viewNotice['total_driving_cost2'])?>원<?=$viewNotice['cost_standard'] == 1 ? ' <span class="text-danger">☜ 현재예약인원</span>' : ''?><br>
            40명이상 : <?=number_format($viewNotice['total_driving_cost3'])?>원<?=$viewNotice['cost_standard'] == 2 ? ' <span class="text-danger">☜ 현재예약인원</span>' : ''?><br>
            만차요금 : <?=number_format($viewNotice['total_driving_cost4'])?>원<?=$viewNotice['cost_standard'] == 3 ? ' <span class="text-danger">☜ 현재예약인원</span>' : ''?>
          </div>

          <h3 class="mb-3">■ 버스비용 산출</h3>
          <strong>・기본요금</strong> : <?=number_format($viewNotice['cost_total'] == 0 ? $viewNotice['cost'] : $viewNotice['cost_total'])?>원 (<?=!empty($viewNotice['distance']) ? calcDistance($viewNotice['distance']) : ''?>)<br>
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

          <div class="mt-5">
            <h4>■ 버스비용 산출</h4>
          </div>
          <div class="row align-items-center border-top mt-2 pt-2">
            <div class="col-sm-1 font-weight-bold">기본요금</div>
            <div class="col-sm-11"><?=number_format($viewNotice['cost_total'] == 0 ? $viewNotice['cost'] : $viewNotice['cost_total'])?>원 (<?=!empty($viewNotice['distance']) ? calcDistance($viewNotice['distance']) : ''?>)</div>
          </div>

          <div class="row align-items-center font-weight-bold border-top mt-2 pt-2">
            <div class="col-sm-4 pr-0">주유비</div>
            <div class="col-sm-4 pr-0">운행비</div>
            <div class="col-sm-4 pr-0">추가비용</div>
          </div>

          <div class="row align-items-center border-top mt-2 pt-2">
            <div class="col-sm-1">총주행</div>
            <div class="col-sm-3"><?=!empty($viewNotice['driving_fuel'][0]) ? number_format($viewNotice['driving_fuel'][0]) : '0'?>km</div>
            <div class="col-sm-1">통행료</div>
            <div class="col-sm-3"><?=!empty($viewNotice['driving_cost'][0]) ? number_format($viewNotice['driving_cost'][0]) : '0'?>원</div>
            <div class="col-sm-1">추가일정</div>
            <div class="col-sm-3"><?=!empty($viewNotice['driving_add'][0]) ? number_format($viewNotice['driving_add'][0]) : '0'?>원 (<?=calcTerm($viewNotice['startdate'], $viewNotice['starttime'], $viewNotice['enddate'], $viewNotice['schedule'])?>)</div>
          </div>
          <div class="row align-items-center border-top mt-2 pt-2">
            <div class="col-sm-1">연비</div>
            <div class="col-sm-3"><?=!empty($viewNotice['driving_fuel'][1]) ? number_format($viewNotice['driving_fuel'][1]) : '0'?>ℓ</div>
            <div class="col-sm-1">주차비</div>
            <div class="col-sm-3"><?=!empty($viewNotice['driving_cost'][1]) ? number_format($viewNotice['driving_cost'][1]) : '0'?>원</div>
            <div class="col-sm-1">여행시기</div>
            <div class="col-sm-3"><?=!empty($viewNotice['driving_add'][1]) ? number_format($viewNotice['driving_add'][1]) . '원 (성수기)' : '0원 (비수기)'?></div>
          </div><br><br>

          <div class="row align-items-center border-top mt-2 pt-2 small">
            <div class="col-sm-3 p-0 row align-items-center">
              <div class="col-sm-6 pr-0">시세 (원/L)</div>
              <div class="col-sm-6 p-0"><input class="form-control form-control-sm cost-gas" type="text" size="3" name="driving_fuel[]" value="<?=!empty($viewNotice['driving_fuel'][2]) ? $viewNotice['driving_fuel'][2] : 0?>"></div>
            </div>
            <div class="col-sm-3 p-0 row align-items-center">
              <div class="col-sm-6 pr-0">식대 (원)</div>
              <div class="col-sm-6 p-0"><input class="form-control form-control-sm driving-cost" type="text" size="4" name="driving_cost[]" value="<?=!empty($viewNotice['driving_cost'][2]) ? $viewNotice['driving_cost'][2] : '10000'?>"></div>
            </div>
            <div class="col-sm-3 p-0 row align-items-center">
              <div class="col-sm-6 pr-0">승객수당</div>
              <div class="col-sm-6 p-0"><input class="form-control form-control-sm driving-add" type="text" size="4" name="driving_add[]" value="<?=!empty($viewNotice['cost_driver']) ? $viewNotice['cost_driver'] : ''?>"></div>
            </div>
          </div>
          <div class="row align-items-center border-top mt-2 pt-2 small">
            <div class="col-sm-3 p-0 row"></div>
            <div class="col-sm-3 p-0 row align-items-center">
              <div class="col-sm-6 pr-0">숙박 (원)</div>
              <div class="col-sm-6 p-0"><input class="form-control form-control-sm driving-cost" type="text" size="4" name="driving_cost[]" value="<?=!empty($viewNotice['driving_cost'][3]) ? $viewNotice['driving_cost'][3] : ''?>"></div>
            </div>
            <div class="col-sm-3 p-0 row"></div>
          </div>
          <div class="row align-items-center border-top mt-2 pt-2 small">
            <div class="col-sm-3 p-0 row align-items-center">
              <div class="col-sm-6 pr-0">합계 (원)</div>
              <div class="col-sm-6 p-0"><input class="form-control form-control-sm total-driving-fuel" readonly type="text" size="3"></div>
            </div>
            <div class="col-sm-3 p-0 row align-items-center">
              <div class="col-sm-6 pr-0">합계 (원)</div>
              <div class="col-sm-6 p-0"><input class="form-control form-control-sm total-driving-cost" readonly type="text" size="4"></div>
            </div>
            <div class="col-sm-3 p-0 row align-items-center">
              <div class="col-sm-6 pr-0">합계 (원)</div>
              <div class="col-sm-6 p-0"><input class="form-control form-control-sm total-driving-add" readonly type="text" size="4"></div>
            </div>
          </div>
          <div class="row align-items-center border-top mt-2 pt-2 small">
            <div class="col-sm-3 p-0 row"></div>
            <div class="col-sm-3 p-0 row"></div>
            <div class="col-sm-2 p-0 row"></div>
            <div class="col-sm-4 p-0 row align-items-center">
              <div class="col-sm-6 p-0 font-weight-bold">운행견적총액 (원)</div>
              <div class="col-sm-6 p-0"><input class="form-control form-control-sm total-bus-cost" readonly type="text" size="4" name="driving_total" value="<?=$viewNotice['driving_total'] != '' ? $viewNotice['driving_total'] : ''?>"></div>
            </div>
          </div>

          <div class="mt-5">
            <h4>■ 운행거리 및 통행료</h4>
          </div>
          <div class="row align-items-center font-weight-bold bg-light border-top pt-2 pb-2">
            <div class="col-3 col-sm-3">운행구간</div>
            <div class="col-3 col-sm-3">거리 (km)</div>
            <div class="col-3 col-sm-3">소요시간</div>
            <div class="col-3 col-sm-3">통행료 (원)</div>
          </div>
          <?php
            $totalDistance = $totalCost = 0;
            foreach ($viewNotice['road_course'] as $key => $value):
          ?>
          <div class="row align-items-center border-top pt-2 pb-2 row-course">
            <div class="col-3 col-sm-3"><?=!empty($viewNotice['road_course'][$key]) ? $viewNotice['road_course'][$key] : ''?></div>
            <div class="col-3 col-sm-3"><?=!empty($viewNotice['road_distance'][$key]) ? $viewNotice['road_distance'][$key] : ''?></div>
            <div class="col-3 col-sm-3"><?=array_key_exists($key, $viewNotice['road_runtime']) ? $viewNotice['road_runtime'][$key] : ''?></div>
            <div class="col-3 col-sm-3"><?=array_key_exists($key, $viewNotice['road_cost']) ? number_format($viewNotice['road_cost'][$key]) : ''?></div>
          </div>
          <?php
              $totalDistance += $viewNotice['road_distance'][$key];
              $totalCost += $viewNotice['road_cost'][$key];
            endforeach;
          ?>
          <div class="added-course"></div>
          <div class="row align-items-center bg-light border-top border-bottom pt-2 pb-2">
            <div class="col-3 col-sm-3 font-weight-bold">합계</div>
            <div class="col-3 col-sm-3"><?=$totalDistance?></div>
            <div class="col-3 col-sm-3"></div>
            <div class="col-3 col-sm-3"><?=number_format($totalCost)?></div>
          </div>

          <div class="mt-5">
            <h4>■ 도착지 주소</h4>
          </div>
          <div class="row align-items-center font-weight-bold bg-light border-top border-bottom pt-2 pb-2">
            <div class="col-4 col-sm-4">도착지명</div>
            <div class="col-8 col-sm-8">주소</div>
          </div>
          <?php
            foreach ($viewNotice['road_course'] as $key => $value):
          ?>
          <div class="row align-items-center border-bottom pt-2 pb-2 row-course">
            <div class="col-4 col-sm-4">
              <?php if ($key == 0): ?>만나는 장소 (<?=date('H:i', strtotime($viewNotice['starttime']) - (60*30))?>)
              <?php elseif (count($viewNotice['road_address']) == ($key+1)): ?>하차지 주소
              <?php else: ?>행선지 <?=$key?><?php endif; ?>
            </div>
            <div class="col-8 col-sm-8"><?=!empty($viewNotice['road_address'][$key]) ? $viewNotice['road_address'][$key] : '-'?></div>
          </div>
          <?php
            endforeach;
          ?>

          <div class="text-center mt-4">
            <a href="<?=BASE_URL?>/member"><button class="btn btn-default">목록으로</button></a>
          </div>
        </div>
      </div>
