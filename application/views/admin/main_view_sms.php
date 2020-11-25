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

            <div class="border-top mt-4 pt-4">
              <?php if (!empty($list)): foreach ($list as $value): ?>
                <?=$value['date']?> (<?=$value['week']?>요<?=$value['dist']?>)<br>
                <?=$value['subject']?><br>
                <?=$value['nickname']?>님<br>
                <?php if ($view['busTotal'] > 1): ?><?=$value['bus']?>호차<?php endif; ?>
                <?=$value['time']?> <?=$value['title']?><br>
                <?=!empty($value['bus_name']) ? $value['bus_name'] : ''?><br>
                <?=$value['seat']?>번 좌석<br>
                경인웰빙산악회<br>
                <br>
              <?php endforeach; endif; ?>
            </div>
          </div>
        </div>
