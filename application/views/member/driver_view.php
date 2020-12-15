<?php
  defined('BASEPATH') OR exit('No direct script access allowed');

  $maxRes = cntRes($viewNotice['idx']);

  if (!empty($viewNotice['driving_add'][0])) {
    $driving_add1 = $viewNotice['driving_add'][0];
  } else {
    $driving_add1 = 0;
  }
  if (!empty($viewNotice['driving_add'][1])) {
    $driving_add2 = $viewNotice['driving_add'][1];
  } else {
    $driving_add2 = 0;
  }
  if (!empty($viewNotice['cost_driver'])) {
    $driving_add3 = $viewNotice['cost_driver'];
  } else {
    $driving_add3 = 0;
  }
  $total_add = $driving_add1 + $driving_add2 + $driving_add3;
?>

      <div class="club-main">
        <div class="mypage mt-2">
          <h2>드라이버 페이지</h2>

          <div class="border-bottom pb-2">
            <div class="notice-title"><b><?=viewStatus($viewNotice['status'])?></b> <?=$viewNotice['subject']?></div>
          </div>
          <div class="row align-items-center border-bottom">
            <div class="col-3 col-sm-1 font-weight-bold bg-light pt-2 pb-2 pr-0">운행요금</div>
            <div class="col-9 col-sm-11 pt-2 pb-2"><span class="text-danger"><?=number_format($viewNotice['total_driving_cost'])?>원</span> (예약인원 <?=$maxRes?>명 기준)</div>
          </div>
          <div class="row align-items-center border-bottom">
            <div class="col-3 col-sm-1 font-weight-bold bg-light pt-2 pb-2 pr-0">기본요금</div>
            <div class="col-9 col-sm-11 pt-2 pb-2"><?=number_format($viewNotice['total_driving_cost1'])?>원<?=$viewNotice['cost_standard'] == 0 ? ' <span class="text-danger">☜ 현재예약인원</span>' : ''?></div>
          </div>
          <div class="row align-items-center border-bottom">
            <div class="col-3 col-sm-1 font-weight-bold bg-light pt-2 pb-2 pr-0">30명이상</div>
            <div class="col-9 col-sm-11 pt-2 pb-2"><?=number_format($viewNotice['total_driving_cost2'])?>원<?=$viewNotice['cost_standard'] == 1 ? ' <span class="text-danger">☜ 현재예약인원</span>' : ''?></div>
          </div>
          <div class="row align-items-center border-bottom">
            <div class="col-3 col-sm-1 font-weight-bold bg-light pt-2 pb-2 pr-0">40명이상</div>
            <div class="col-9 col-sm-11 pt-2 pb-2"><?=number_format($viewNotice['total_driving_cost3'])?>원<?=$viewNotice['cost_standard'] == 2 ? ' <span class="text-danger">☜ 현재예약인원</span>' : ''?></div>
          </div>
          <div class="row align-items-center border-bottom">
            <div class="col-3 col-sm-1 font-weight-bold bg-light pt-2 pb-2 pr-0">만차요금</div>
            <div class="col-9 col-sm-11 pt-2 pb-2"><?=number_format($viewNotice['total_driving_cost4'])?>원<?=$viewNotice['cost_standard'] == 3 ? ' <span class="text-danger">☜ 현재예약인원</span>' : ''?></div>
          </div>

          <div class="mt-4">
            <h4>■ 버스비용 산출</h4>
          </div>
          <div class="row align-items-center border-top">
            <div class="col-3 col-sm-1 font-weight-bold bg-light pt-2 pb-2 pr-0">기본요금</div>
            <div class="col-9 col-sm-11 pt-2 pb-2"><?=!empty($viewNotice['driving_default']) ? number_format($viewNotice['driving_default']) : '0'?>원 (<?=!empty($viewNotice['distance']) ? calcDistance($viewNotice['distance']) : ''?>)</div>
          </div>
          <div class="row align-items-top border-top">
            <div class="col-sm-4 p-0">
              <div class="row border-bottom pt-2 pb-2 bg-light">
                <div class="col-sm-12 font-weight-bold pr-0">주유비</div>
              </div>
              <div class="row border-bottom">
                <div class="col-3 pt-2 pb-2 pr-0 bg-light">총주행</div>
                <div class="col-9 pt-2 pb-2"><?=!empty($viewNotice['driving_fuel'][0]) ? number_format($viewNotice['driving_fuel'][0]) : '0'?>km</div>
              </div>
              <div class="row border-bottom">
                <div class="col-3 pt-2 pb-2 pr-0 bg-light">연비</div>
                <div class="col-9 pt-2 pb-2"><?=!empty($viewNotice['driving_fuel'][1]) ? number_format($viewNotice['driving_fuel'][1]) : '0'?>ℓ</div>
              </div>
              <div class="row border-bottom">
                <div class="col-3 pt-2 pb-2 pr-0 bg-light">시세</div>
                <div class="col-9 pt-2 pb-2"><?=!empty($viewNotice['driving_fuel'][2]) ? $viewNotice['driving_fuel'][2] : 0?>원/ℓ</div>
              </div>
              <div class="row border-bottom d-none d-sm-block">
                <div class="col-3 pt-2 pb-2 bg-light">&nbsp;</div>
                <div class="col-9"></div>
              </div>
              <div class="row border-bottom">
                <div class="col-3 font-weight-bold bg-light pt-2 pb-2 pr-0">합계</div>
                <div class="col-9 pt-2 pb-2"><?=!empty($viewNotice['total_fuel']) ? number_format($viewNotice['total_fuel']) : '0'?>원</div>
              </div>
            </div>
            <div class="col-sm-4 p-0">
              <div class="row border-bottom pt-2 pb-2 bg-light">
                <div class="col-sm-12 font-weight-bold pr-0">운행비</div>
              </div>
              <div class="row border-bottom">
                <div class="col-3 pt-2 pb-2 pr-0 bg-light">통행료</div>
                <div class="col-9 pt-2 pb-2"><?=!empty($viewNotice['driving_cost'][0]) ? number_format($viewNotice['driving_cost'][0]) : '0'?>원</div>
              </div>
              <div class="row border-bottom">
                <div class="col-3 pt-2 pb-2 pr-0 bg-light">주차비</div>
                <div class="col-9 pt-2 pb-2"><?=!empty($viewNotice['driving_cost'][1]) ? number_format($viewNotice['driving_cost'][1]) : '0'?>원</div>
              </div>
              <div class="row border-bottom">
                <div class="col-3 pt-2 pb-2 pr-0 bg-light">식대</div>
                <div class="col-9 pt-2 pb-2"><?=!empty($viewNotice['driving_cost'][2]) ? number_format($viewNotice['driving_cost'][2]) : '10,000'?>원</div>
              </div>
              <div class="row border-bottom">
                <div class="col-3 pt-2 pb-2 pr-0 bg-light">숙박</div>
                <div class="col-9 pt-2 pb-2"><?=!empty($viewNotice['driving_cost'][3]) ? number_format($viewNotice['driving_cost'][3]) : '0'?>원</div>
              </div>
              <div class="row border-bottom">
                <div class="col-3 font-weight-bold bg-light pt-2 pb-2 pr-0">합계</div>
                <div class="col-9 pt-2 pb-2"><?=!empty($viewNotice['total_cost']) ? number_format($viewNotice['total_cost']) : '0'?>원</div>
              </div>
            </div>
            <div class="col-sm-4 p-0">
              <div class="row border-bottom pt-2 pb-2 bg-light">
                <div class="col-sm-12 font-weight-bold pr-0">추가비용</div>
              </div>
              <div class="row border-bottom">
                <div class="col-3 pt-2 pb-2 pr-0 bg-light">추가일정</div>
                <div class="col-9 pt-2 pb-2"><?=number_format($driving_add1)?>원 (<?=calcTerm($viewNotice['startdate'], $viewNotice['starttime'], $viewNotice['enddate'], $viewNotice['schedule'])?>)</div>
              </div>
              <div class="row border-bottom">
                <div class="col-3 pt-2 pb-2 pr-0 bg-light">여행시기</div>
                <div class="col-9 pt-2 pb-2"><?=!empty($driving_add2) ? number_format($driving_add2) . '원 (성수기)' : '0원 (비수기)'?></div>
              </div>
              <div class="row border-bottom">
                <div class="col-3 pt-2 pb-2 pr-0 bg-light">승객수당</div>
                <div class="col-9 pt-2 pb-2"><?=number_format($driving_add3)?>원 (<?=$maxRes?>명)</div>
              </div>
              <div class="row border-bottom d-none d-sm-block">
                <div class="col-3 pt-2 pb-2 bg-light">&nbsp;</div>
                <div class="col-9"></div>
              </div>
              <div class="row border-bottom">
                <div class="col-3 font-weight-bold bg-light pt-2 pb-2 pr-0">합계</div>
                <div class="col-9 pt-2 pb-2"><?=number_format($total_add)?>원</div>
              </div>
            </div>
          </div>
          <div class="row align-items-center border-bottom">
            <div class="col-3 col-sm-1 font-weight-bold bg-light pt-2 pb-2 pr-0">견적총액</div>
            <div class="col-9 col-sm-11 pt-2 pb-2"><?=number_format($viewNotice['driving_total'])?>원</div>
          </div>

          <div class="mt-4">
            <h4>■ 운행거리 및 통행료</h4>
          </div>
          <div class="row align-items-center font-weight-bold bg-light border-top pt-2 pb-2">
            <div class="col-3 col-sm-3 pr-0">운행구간</div>
            <div class="col-3 col-sm-3">거리 (km)</div>
            <div class="col-3 col-sm-3">소요시간</div>
            <div class="col-3 col-sm-3">통행료 (원)</div>
          </div>
          <?php
            $totalDistance = $totalCost = 0;
            foreach ($viewNotice['road_course'] as $key => $value):
          ?>
          <div class="row align-items-center border-top pt-2 pb-2 row-course">
            <div class="col-3 col-sm-3 pr-0"><?=!empty($viewNotice['road_course'][$key]) ? $viewNotice['road_course'][$key] : ''?></div>
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
            <div class="col-3 col-sm-3 pr-0 font-weight-bold">합계</div>
            <div class="col-3 col-sm-3"><?=$totalDistance?></div>
            <div class="col-3 col-sm-3"></div>
            <div class="col-3 col-sm-3"><?=number_format($totalCost)?></div>
          </div>

          <div class="mt-4">
            <h4>■ 도착지 주소</h4>
          </div>
          <div class="row align-items-center font-weight-bold bg-light border-top border-bottom pt-2 pb-2">
            <div class="col-4 col-sm-4 pr-0">도착지명</div>
            <div class="col-8 col-sm-8">주소</div>
          </div>
          <?php foreach ($viewNotice['road_course'] as $key => $value): ?>
          <div class="row align-items-center border-bottom pt-2 pb-2 row-course">
            <div class="col-4 col-sm-4 pr-0">
              <?php if ($key == 0): ?>만나는 장소 (<?=date('H:i', strtotime($viewNotice['starttime']) - (60*30))?>)
              <?php elseif (count($viewNotice['road_address']) == ($key+1)): ?>하차지 주소
              <?php else: ?>행선지 <?=$key?><?php endif; ?>
            </div>
            <div class="col-8 col-sm-8"><?=!empty($viewNotice['road_address'][$key]) ? $viewNotice['road_address'][$key] : '-'?></div>
          </div>
          <?php endforeach; ?>

          <div class="mt-4">
            <h4>■ 승차 위치 <small>(총 <?=$maxRes?>명)</small></h4>
          </div>
          <?php foreach ($busType as $key => $value): $bus = $key + 1;  ?>
          <div class="row align-items-center border-top border-bottom mt-3 bg-light">
            <div class="col-12 font-weight-bold pt-2 pb-2"><?=$bus?>호차 (<?=$value['total']?>명)</div>
          </div>
          <?php foreach ($value['listLocation'] as $cnt => $location): if ($cnt == 0): $lastData = $location; else: ?>
          <div class="row align-items-center border-bottom">
            <div class="col-12 col-sm-2 font-weight-bold bg-light pt-2 pb-2"><?=$location['time']?> <?=$location['stitle']?> (<?=!empty($location['nickname']) ? count($location['nickname']) : 0?>명)</div>
            <div class="col-12 col-sm-10 bg-white pt-2 pb-2"><?php if (!empty($location['nickname'])): foreach ($location['nickname'] as $n => $nickname): if ($n != 0): ?> / <?php endif; ?><?=$nickname?><?php endforeach; else: echo "&nbsp;"; endif; ?></div>
          </div>
          <?php endif; endforeach; ?>
          <div class="row align-items-center border-bottom">
            <div class="col-12 col-sm-2 font-weight-bold bg-light pt-2 pb-2">미지정 (<?=!empty($lastData['nickname']) ? count($lastData['nickname']) : 0?>명)</div>
            <div class="col-12 col-sm-10 bg-white pt-2 pb-2"><?php if (!empty($lastData['nickname'])): foreach ($lastData['nickname'] as $n => $nickname): if ($n != 0): ?> / <?php endif; ?><?=$nickname?><?php endforeach; else: echo "&nbsp;"; endif; ?></div>
          </div>
          <?php endforeach; ?>

          <div class="text-center mt-4">
            <a href="<?=BASE_URL?>/member/driver"><button class="btn btn-default">목록으로</button></a>
          </div>
        </div>
      </div>
