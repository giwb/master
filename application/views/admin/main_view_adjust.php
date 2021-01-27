<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <?=$headerMenuView?>
        <div id="content" class="mb-5">
          <div class="sub-contents">
            <h2 class="m-0 p-0 pb-2"><b><?=viewStatus($view['status'])?></b> <?=$view['subject']?></h2>
            <?php if (!empty($view['type'])): ?><div class="ti"><strong>・유형</strong> : <?=$view['type']?></div><?php endif; ?>
            <div class="ti"><strong>・일시</strong> : <?=$view['startdate']?> (<?=calcWeek($view['startdate'])?>) <?=$view['starttime']?></div>
            <div class="ti"><strong>・노선</strong> : <?php foreach ($location as $key => $value): if ($key > 1): ?> - <?php endif; ?><?=$value['time']?> <?=$value['stitle']?><?php endforeach; ?></div>
            <?php $view['cost'] = $view['cost_total'] == 0 ? $view['cost'] : $view['cost_total']; if (!empty($view['cost'])): ?>
            <?php if (!empty($view['sido'])): ?>
            <div class="ti"><strong>・지역</strong> : <?php foreach ($view['sido'] as $key => $value): if ($key != 0): ?>, <?php endif; ?><?=$value?> <?=!empty($view['gugun'][$key]) ? $view['gugun'][$key] : ''?><?php endforeach; ?></div>
            <?php endif; ?>
            <div class="ti"><strong>・요금</strong> : <?=number_format($view['cost_total'] == 0 ? $view['cost'] : $view['cost_total'])?>원 / 1인우등 <?=number_format($view['cost_total'] == 0 ? $view['cost'] + 10000 : $view['cost_total'] + 10000)?>원 (<?=calcTerm($view['startdate'], $view['starttime'], $view['enddate'], $view['schedule'])?><?=!empty($view['distance']) ? ', ' . calcDistance($view['distance']) : ''?><?=!empty($view['options']) ? ', ' . getOptions($view['options']) : ''?><?=!empty($view['options_etc']) ? ', ' . $view['options_etc'] : ''?><?=!empty($view['options']) || !empty($view['options_etc']) ? ' 제공' : ''?><?=!empty($view['costmemo']) ? ', ' . $view['costmemo'] : ''?>)</div>
            <?php endif; ?>
            <?=!empty($view['content']) ? '<div class="ti"><strong>・코스</strong> : ' . nl2br($view['content']) . '</div>' : ''?>
            <?=!empty($view['kilometer']) ? '<div class="ti"><strong>・거리</strong> : ' . $view['kilometer'] . '</div>' : ''?>
            <div class="ti"><strong>・예약</strong> : <?=$view['cntRes']?>명</div>
          </div>
          <form id="formAdjust" method="post" action="/admin/main_view_adjust_update">
            <input type="hidden" name="rescode" value="<?=$view['idx']?>">
            <div class="row align-items-center mt-4 pt-2 pb-2 border-top border-bottom text-center bg-info text-white font-weight-bold">
              <div class="col-1">&nbsp;</div>
              <div class="col-3">내역</div>
              <div class="col-1 pl-0 pr-0">수량</div>
              <div class="col-2">단가</div>
              <div class="col-2">금액</div>
              <div class="col-3">비고</div>
            </div>
            <!-- 산행비 -->
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title1" class="form-control form-control-sm" value="<?=empty($viewAdjust['title1']) ? '산행예약' : $viewAdjust['title1']?>"></div>
              <div class="col-1 pl-1 pr-1"><input readonly type="text" name="amount1" class="form-control form-control-sm text-right auto-calc" value="<?=$view['cntRes']?>"></div>
              <div class="col-2 pl-1 pr-1"><input readonly type="text" name="cost1" class="form-control form-control-sm text-right auto-calc" value="<?=$view['cost']?>"></div>
              <div class="col-2 pl-1 pr-1"><input readonly type="text" name="total1" class="form-control form-control-sm text-right total-cost" value="<?=empty($viewAdjust['total1']) ? $view['cntRes'] * $view['cost'] : $viewAdjust['total1']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo1" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo1']) ? '' : $viewAdjust['memo1']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1 p-0">산행비</div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title31" class="form-control form-control-sm" value="<?=empty($viewAdjust['title31']) ? '우등요금' : $viewAdjust['title31']?>"></div>
              <div class="col-1 pl-1 pr-1"><input readonly type="text" name="amount31" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount31']) ? ($view['cntHonor']) : $viewAdjust['amount31']?>"></div>
              <div class="col-2 pl-1 pr-1"><input readonly type="text" name="cost31" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost31']) ? '10000' : $viewAdjust['cost31']?>"></div>
              <div class="col-2 pl-1 pr-1"><input readonly type="text" name="total31" class="form-control form-control-sm text-right total-cost" value="<?=empty($viewAdjust['total31']) ? $view['cntHonor'] * 10000 : $viewAdjust['total31']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo31" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo31']) ? '' : $viewAdjust['memo31']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title2" class="form-control form-control-sm" value="<?=empty($viewAdjust['title2']) ? '현지합류' : $viewAdjust['title2']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount2" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount2']) ? '' : $viewAdjust['amount2']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost2" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost2']) ? '' : $viewAdjust['cost2']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total2" class="form-control form-control-sm text-right total-cost" value="<?=empty($viewAdjust['total2']) ? '' : $viewAdjust['total2']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo2" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo2']) ? '' : $viewAdjust['memo2']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title3" class="form-control form-control-sm" value="<?=empty($viewAdjust['title3']) ? '기타수익' : $viewAdjust['title3']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount3" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount3']) ? '' : $viewAdjust['amount3']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost3" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost3']) ? '' : $viewAdjust['cost3']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total3" class="form-control form-control-sm text-right total-cost" value="<?=empty($viewAdjust['total3']) ? '' : $viewAdjust['total3']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo3" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo3']) ? '' : $viewAdjust['memo3']?>"></div>
            </div>
            <!-- 할인 -->
            <div class="row align-items-center mt-1 p-1 pt-2 border-top">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title4" class="form-control form-control-sm" value="<?=empty($viewAdjust['title4']) ? '평생회원' : $viewAdjust['title4']?>"></div>
              <div class="col-1 pl-1 pr-1"><input readonly type="text" name="amount4" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount4']) ? $view['vip']['cnt'] : $viewAdjust['amount4']?>"></div>
              <div class="col-2 pl-1 pr-1"><input readonly type="text" name="cost4" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost4']) ? '5000' : $viewAdjust['cost4']?>"></div>
              <div class="col-2 pl-1 pr-1"><input readonly type="text" name="total4" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total4']) ? $view['vip']['cnt'] * 5000 : $viewAdjust['total4']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo4" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo4']) ? '' : $viewAdjust['memo4']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1 p-0">할인</div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title5" class="form-control form-control-sm" value="<?=empty($viewAdjust['title5']) ? '포인트' : $viewAdjust['title5']?>"></div>
              <div class="col-1 pl-1 pr-1"><input readonly type="text" name="amount5" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount5']) ? $view['point']['cnt'] : $viewAdjust['amount5']?>"></div>
              <div class="col-2 pl-1 pr-1"><input readonly type="text" name="cost5" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost5']) ? '' : $viewAdjust['cost5']?>"></div>
              <div class="col-2 pl-1 pr-1"><input readonly type="text" name="total5" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total5']) ? $view['point_cost']['total'] : $viewAdjust['total5']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo5" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo5']) ? '' : $viewAdjust['memo5']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title6" class="form-control form-control-sm" value="<?=empty($viewAdjust['title6']) ? '공제' : $viewAdjust['title6']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount6" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount6']) ? '' : $viewAdjust['amount6']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost6" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost6']) ? '' : $viewAdjust['cost6']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total6" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total6']) ? '' : $viewAdjust['total6']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo6" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo6']) ? '' : $viewAdjust['memo6']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title7" class="form-control form-control-sm" value="<?=empty($viewAdjust['title7']) ? '기타' : $viewAdjust['title7']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount7" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount7']) ? '' : $viewAdjust['amount7']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost7" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost7']) ? '' : $viewAdjust['cost7']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total7" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total7']) ? '' : $viewAdjust['total7']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo7" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo7']) ? '' : $viewAdjust['memo7']?>"></div>
            </div>
            <!-- 운행비 -->
            <div class="row align-items-center mt-1 p-1 pt-2 border-top">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title8" class="form-control form-control-sm" value="<?=empty($viewAdjust['title8']) ? '관광버스' : $viewAdjust['title8']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount8" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount8']) ? '' : $viewAdjust['amount8']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost8" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost8']) ? '' : $viewAdjust['cost8']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total8" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total8']) ? '' : $viewAdjust['total8']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo8" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo8']) ? '' : $viewAdjust['memo8']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title9" class="form-control form-control-sm" value="<?=empty($viewAdjust['title9']) ? '기사수고비' : $viewAdjust['title9']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount9" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount9']) ? '' : $viewAdjust['amount9']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost9" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost9']) ? '' : $viewAdjust['cost9']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total9" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total9']) ? '' : $viewAdjust['total9']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo9" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo9']) ? '' : $viewAdjust['memo9']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1 p-0">운행비</div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title10" class="form-control form-control-sm" value="<?=empty($viewAdjust['title10']) ? '장소임대' : $viewAdjust['title10']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount10" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount10']) ? '' : $viewAdjust['amount10']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost10" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost10']) ? '' : $viewAdjust['cost10']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total10" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total10']) ? '' : $viewAdjust['total10']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo10" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo10']) ? '' : $viewAdjust['memo10']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title11" class="form-control form-control-sm" value="<?=empty($viewAdjust['title11']) ? '' : $viewAdjust['title11']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount11" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount11']) ? '' : $viewAdjust['amount11']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost11" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost11']) ? '' : $viewAdjust['cost11']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total11" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total11']) ? '' : $viewAdjust['total11']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo11" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo11']) ? '' : $viewAdjust['memo11']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title12" class="form-control form-control-sm" value="<?=empty($viewAdjust['title12']) ? '' : $viewAdjust['title12']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount12" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount12']) ? '' : $viewAdjust['amount12']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost12" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost12']) ? '' : $viewAdjust['cost12']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total12" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total12']) ? '' : $viewAdjust['total12']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo12" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo12']) ? '' : $viewAdjust['memo12']?>"></div>
            </div>
            <!-- 조식 -->
            <div class="row align-items-center mt-1 p-1 pt-2 border-top">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title13" class="form-control form-control-sm" value="<?=empty($viewAdjust['title13']) ? '김밥' : $viewAdjust['title13']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount13" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount13']) ? '' : $viewAdjust['amount13']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost13" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost13']) ? '' : $viewAdjust['cost13']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total13" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total13']) ? '' : $viewAdjust['total13']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo13" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo13']) ? '' : $viewAdjust['memo13']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1 p-0">조식</div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title14" class="form-control form-control-sm" value="<?=empty($viewAdjust['title14']) ? '둥굴레차' : $viewAdjust['title14']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount14" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount14']) ? '' : $viewAdjust['amount14']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost14" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost14']) ? '' : $viewAdjust['cost14']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total14" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total14']) ? '' : $viewAdjust['total14']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo14" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo14']) ? '' : $viewAdjust['memo14']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title15" class="form-control form-control-sm" value="<?=empty($viewAdjust['title15']) ? '커피믹스' : $viewAdjust['title15']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount15" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount15']) ? '' : $viewAdjust['amount15']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost15" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost15']) ? '' : $viewAdjust['cost15']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total15" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total15']) ? '' : $viewAdjust['total15']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo15" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo15']) ? '' : $viewAdjust['memo15']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title16" class="form-control form-control-sm" value="<?=empty($viewAdjust['title16']) ? '종이컵' : $viewAdjust['title16']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount16" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount16']) ? '' : $viewAdjust['amount16']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost16" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost16']) ? '' : $viewAdjust['cost16']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total16" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total16']) ? '' : $viewAdjust['total16']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo16" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo16']) ? '' : $viewAdjust['memo16']?>"></div>
            </div>
            <!-- 하산주 -->
            <div class="row align-items-center mt-1 p-1 pt-2 border-top">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title17" class="form-control form-control-sm" value="<?=empty($viewAdjust['title17']) ? '막걸리' : $viewAdjust['title17']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount17" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount17']) ? '' : $viewAdjust['amount17']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost17" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost17']) ? '' : $viewAdjust['cost17']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total17" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total17']) ? '' : $viewAdjust['total17']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo17" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo17']) ? '' : $viewAdjust['memo17']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title18" class="form-control form-control-sm" value="<?=empty($viewAdjust['title18']) ? '소주' : $viewAdjust['title18']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount18" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount18']) ? '' : $viewAdjust['amount18']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost18" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost18']) ? '' : $viewAdjust['cost18']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total18" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total18']) ? '' : $viewAdjust['total18']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo18" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo18']) ? '' : $viewAdjust['memo18']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title19" class="form-control form-control-sm" value="<?=empty($viewAdjust['title19']) ? '' : $viewAdjust['title19']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount19" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount19']) ? '' : $viewAdjust['amount19']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost19" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost19']) ? '' : $viewAdjust['cost19']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total19" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total19']) ? '' : $viewAdjust['total19']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo19" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo19']) ? '' : $viewAdjust['memo19']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title20" class="form-control form-control-sm" value="<?=empty($viewAdjust['title20']) ? '' : $viewAdjust['title20']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount20" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount20']) ? '' : $viewAdjust['amount20']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost20" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost20']) ? '' : $viewAdjust['cost20']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total20" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total20']) ? '' : $viewAdjust['total20']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo20" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo20']) ? '' : $viewAdjust['memo20']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1 p-0">하산주</div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title21" class="form-control form-control-sm" value="<?=empty($viewAdjust['title21']) ? '' : $viewAdjust['title21']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount21" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount21']) ? '' : $viewAdjust['amount21']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost21" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost21']) ? '' : $viewAdjust['cost21']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total21" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total21']) ? '' : $viewAdjust['total21']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo21" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo21']) ? '' : $viewAdjust['memo21']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title22" class="form-control form-control-sm" value="<?=empty($viewAdjust['title22']) ? '' : $viewAdjust['title22']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount22" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount22']) ? '' : $viewAdjust['amount22']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost22" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost22']) ? '' : $viewAdjust['cost22']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total22" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total22']) ? '' : $viewAdjust['total22']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo22" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo22']) ? '' : $viewAdjust['memo22']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title23" class="form-control form-control-sm" value="<?=empty($viewAdjust['title23']) ? '' : $viewAdjust['title23']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount23" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount23']) ? '' : $viewAdjust['amount23']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost23" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost23']) ? '' : $viewAdjust['cost23']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total23" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total23']) ? '' : $viewAdjust['total23']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo23" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo23']) ? '' : $viewAdjust['memo23']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title24" class="form-control form-control-sm" value="<?=empty($viewAdjust['title24']) ? '' : $viewAdjust['title24']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount24" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount24']) ? '' : $viewAdjust['amount24']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost24" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost24']) ? '' : $viewAdjust['cost24']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total24" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total24']) ? '' : $viewAdjust['total24']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo24" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo24']) ? '' : $viewAdjust['memo24']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title25" class="form-control form-control-sm" value="<?=empty($viewAdjust['title25']) ? '' : $viewAdjust['title25']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount25" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount25']) ? '' : $viewAdjust['amount25']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost25" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost25']) ? '' : $viewAdjust['cost25']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total25" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total25']) ? '' : $viewAdjust['total25']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo25" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo25']) ? '' : $viewAdjust['memo25']?>"></div>
            </div>
            <!-- 운영비 -->
            <div class="row align-items-center mt-1 p-1 pt-2 border-top">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title26" class="form-control form-control-sm" value="<?=empty($viewAdjust['title26']) ? '' : $viewAdjust['title26']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount26" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount26']) ? '' : $viewAdjust['amount26']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost26" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost26']) ? '' : $viewAdjust['cost26']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total26" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total26']) ? '' : $viewAdjust['total26']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo26" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo26']) ? '' : $viewAdjust['memo26']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title27" class="form-control form-control-sm" value="<?=empty($viewAdjust['title27']) ? '' : $viewAdjust['title27']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount27" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount27']) ? '' : $viewAdjust['amount27']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost27" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost27']) ? '' : $viewAdjust['cost27']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total27" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total27']) ? '' : $viewAdjust['total27']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo27" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo27']) ? '' : $viewAdjust['memo27']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1 p-0">운영비</div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title28" class="form-control form-control-sm" value="<?=empty($viewAdjust['title28']) ? '' : $viewAdjust['title28']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount28" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount28']) ? '' : $viewAdjust['amount28']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost28" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost28']) ? '' : $viewAdjust['cost28']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total28" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total28']) ? '' : $viewAdjust['total28']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo28" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo28']) ? '' : $viewAdjust['memo28']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title29" class="form-control form-control-sm" value="<?=empty($viewAdjust['title29']) ? '' : $viewAdjust['title29']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount29" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount29']) ? '' : $viewAdjust['amount29']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost29" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost29']) ? '' : $viewAdjust['cost29']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total29" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total29']) ? '' : $viewAdjust['total29']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo29" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo29']) ? '' : $viewAdjust['memo29']?>"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title30" class="form-control form-control-sm" value="<?=empty($viewAdjust['title30']) ? '' : $viewAdjust['title30']?>"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount30" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['amount30']) ? '' : $viewAdjust['amount30']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost30" class="form-control form-control-sm text-right auto-calc" value="<?=empty($viewAdjust['cost30']) ? '' : $viewAdjust['cost30']?>"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total30" class="form-control form-control-sm text-right total-cost-minus" value="<?=empty($viewAdjust['total30']) ? '' : $viewAdjust['total30']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo30" class="form-control form-control-sm" value="<?=empty($viewAdjust['memo30']) ? '' : $viewAdjust['memo30']?>"></div>
            </div>
            <div class="row align-items-center mt-1 p-1 pt-2 pb-2 border-top border-bottom text-center bg-info text-white font-weight-bold">
              <div class="col-1 p-0">합계</div>
              <div class="col-6"></div>
              <div class="col-2 pl-1 pr-1"><input readonly type="text" name="total" class="form-control form-control-sm text-right" value="<?=empty($viewAdjust['total']) ? $view['total'] : $viewAdjust['total']?>"></div>
              <div class="col-3"></div>
            </div>
            <div class="row align-items-center p-1 text-center">
              <div class="col-1 p-0">산행<br>메모</div>
              <div class="col-11 pl-1 pr-1">
                <textarea name="memo" rows="5" class="form-control form-control-sm"></textarea>
              </div>
            </div>
            <div class="mt-1 pt-3 border-top text-center">
              <div class="error-message"></div>
              <button type="button" class="btn btn-default btn-adjust">정산내역저장</button>
            </div>
          </form>
        </div>

        <script>
          $(document).ready(function() {
            // 항목별 합계 자동 계산 
            $('.auto-calc').change(function() {
              var arr = new Array();
              var $dom = $(this).parent().parent();
              $('.auto-calc', $dom).each(function() {
                arr.push($(this).val());
              });
              var result = arr[0] * arr[1];
              $('.total-cost, .total-cost-minus', $dom).val(result);
              $.totalCost();
            });

            // 전체 합계 자동 계산 
            $('.total-cost, .total-cost-minus').change(function() {
              $.totalCost();
            });

            // 정산내역 저장
            $('.btn-adjust').click(function() {
              var $btn = $(this);
              var formData = new FormData($('#formAdjust')[0]);

              $.ajax({
                url: $('#formAdjust').attr('action'),
                processData: false,
                contentType: false,
                data: formData,
                dataType: 'json',
                type: 'post',
                beforeSend: function() {
                  $btn.css('opacity', '0.5').prop('disabled', true).text('저장하는 중..');
                },
                success: function(result) {
                  $btn.css('opacity', '1').prop('disabled', false).text('정산내역저장');

                  $('.error-message').text(result.message).slideDown();
                  setTimeout(function() { $('.error-message').text('').slideUp(); }, 2000);
                }
              });
            });

            $.totalCost = function() {
              var total = 0;
              var total_minus = 0;
              $('.total-cost').each(function() {
                total += Number($(this).val());
              });
              $('.total-cost-minus').each(function() {
                total_minus += Number($(this).val());
              });
              $('input[name=total]').val(total - total_minus);
            }
          });
        </script>