<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div id="content" class="mb-5">
          <?php foreach ($list as $value): ?>
          <div class="border-bottom mt-1 pt-2 pb-2">
            <b><?=viewStatus($value['status'], $value['visible'])?></b> <a href="/admin/main_view_progress/<?=$value['idx']?>"><?=$value['subject']?></a><br>
            <div class="small">
              <?php if (!empty($value['sido'])): ?>
              <?php foreach ($value['sido'] as $key => $sido): ?><?=$sido?> <?=!empty($value['gugun'][$key]) ? $value['gugun'][$key] : ''?>, <?php endforeach; ?>
              <?php endif; ?>
              <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원 / 예약인원 <?=cntRes($value['idx'])?>명
            </div>
          </div>
          <?php endforeach; ?>
        </div>
