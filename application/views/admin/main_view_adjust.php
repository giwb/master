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
            <div class="ti"><strong>・요금</strong> : <?=$view['cost']?>원 (<?=calcTerm($view['startdate'], $view['starttime'], $view['enddate'], $view['schedule'])?><?=!empty($view['distance']) ? ', ' . calcDistance($view['distance']) : ''?><?=!empty($view['options']) ? ', ' . getOptions($view['options']) : ''?><?=!empty($view['options_etc']) ? ', ' . $view['options_etc'] : ''?><?=!empty($view['options']) || !empty($view['options_etc']) ? ' 제공' : ''?><?=!empty($view['costmemo']) ? ', ' . $view['costmemo'] : ''?>)</div>
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
              <div class="col-3 pl-1 pr-1"><input type="text" name="title1" value="산행예약" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input readonly type="text" name="amount1" class="form-control form-control-sm text-right" value="<?=$view['cntRes']?>"></div>
              <div class="col-2 pl-1 pr-1"><input readonly type="text" name="cost1" class="form-control form-control-sm text-right" value="<?=$view['cost']?>"></div>
              <div class="col-2 pl-1 pr-1"><input readonly type="text" name="total1" class="form-control form-control-sm text-right" value="<?=$view['cntRes'] * $view['cost']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo1" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1 p-0">산행비</div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title2" value="현지합류" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount2" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost2" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total2" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo2" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title3" value="기타수익" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount3" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost3" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total3" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo3" class="form-control form-control-sm"></div>
            </div>
            <!-- 할인 -->
            <div class="row align-items-center mt-1 p-1 pt-2 border-top">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title4" value="평생회원" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input readonly type="text" name="amount4" class="form-control form-control-sm text-right" value="<?=$view['vip']['cnt']?>"></div>
              <div class="col-2 pl-1 pr-1"><input readonly type="text" name="cost4" class="form-control form-control-sm text-right" value="5000"></div>
              <div class="col-2 pl-1 pr-1"><input readonly type="text" name="total4" class="form-control form-control-sm text-right" value="<?=$view['vip']['cnt'] * 5000?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo4" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1 p-0">할인</div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title5" value="포인트" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input readonly type="text" name="amount5" class="form-control form-control-sm text-right" value="<?=$view['point']['cnt']?>"></div>
              <div class="col-2 pl-1 pr-1"><input readonly type="text" name="cost5" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input readonly type="text" name="total5" class="form-control form-control-sm text-right" value="<?=$view['point_cost']['total']?>"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo5" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title6" value="공제" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount6" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost6" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total6" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo6" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title7" value="기타" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount7" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost7" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total7" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo7" class="form-control form-control-sm"></div>
            </div>
            <!-- 운행비 -->
            <div class="row align-items-center mt-1 p-1 pt-2 border-top">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title8" value="관광버스" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount8" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost8" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total8" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo8" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title9" value="기사수고비" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount9" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost9" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total9" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo9" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1 p-0">운행비</div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title10" value="장소임대" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount10" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost10" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total10" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo10" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title11" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount11" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost11" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total11" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo11" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title12" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount12" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost12" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total12" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo12" class="form-control form-control-sm"></div>
            </div>
            <!-- 조식 -->
            <div class="row align-items-center mt-1 p-1 pt-2 border-top">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title13" value="김밥" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount13" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost13" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total13" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo13" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1 p-0">조식</div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title14" value="둥굴레차" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount14" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost14" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total14" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo14" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title15" value="커피믹스" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount15" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost15" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total15" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo15" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title16" value="종이컵" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount16" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost16" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total16" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo16" class="form-control form-control-sm"></div>
            </div>
            <!-- 하산주 -->
            <div class="row align-items-center mt-1 p-1 pt-2 border-top">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title17" value="막걸리" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount17" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost17" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total17" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo17" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title18" value="소주" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount18" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost18" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total18" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo18" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title19" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount19" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost19" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total19" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo19" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title20" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount20" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost20" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total20" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo20" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1 p-0">하산주</div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title21" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount21" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost21" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total21" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo21" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title22" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount22" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost22" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total22" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo22" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title23" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount23" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost23" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total23" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo23" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title24" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount24" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost24" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total24" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo24" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title25" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount25" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost25" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total25" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo25" class="form-control form-control-sm"></div>
            </div>
            <!-- 운영비 -->
            <div class="row align-items-center mt-1 p-1 pt-2 border-top">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title26" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount26" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost26" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total26" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo26" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title27" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount27" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost27" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total27" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo27" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1 p-0">운영비</div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title28" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount28" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost28" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total28" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo28" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title29" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount29" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost29" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total29" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo29" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center p-1">
              <div class="col-1"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="title30" class="form-control form-control-sm"></div>
              <div class="col-1 pl-1 pr-1"><input type="text" name="amount30" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="cost30" class="form-control form-control-sm text-right"></div>
              <div class="col-2 pl-1 pr-1"><input type="text" name="total30" class="form-control form-control-sm text-right"></div>
              <div class="col-3 pl-1 pr-1"><input type="text" name="memo30" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center mt-1 p-1 pt-2 pb-2 border-top border-bottom text-center bg-info text-white font-weight-bold">
              <div class="col-1 p-0">합계</div>
              <div class="col-6"></div>
              <div class="col-2 pl-1 pr-1"><input readonly type="text" name="total" class="form-control form-control-sm text-right" value="<?=$view['total']?>"></div>
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
          });
        </script>