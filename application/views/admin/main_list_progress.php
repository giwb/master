<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div id="content" class="mb-5">
          <?php if (empty($list)): ?>
          <div class="border-bottom pt-5 pb-5 text-center">
            등록된 산행이 없습니다.
          </div>
          <?php else: foreach ($list as $value): ?>
          <div class="row align-items-center border-bottom mt-1 pt-2 pb-2">
            <div class="col-9 col-sm-10">
              <b><?=viewStatus($value['status'], $value['visible'])?></b> <a href="<?=BASE_URL?>/admin/main_view_progress/<?=$value['idx']?>"><?=$value['subject']?></a><br>
              <div class="small">
                <?php if (!empty($value['sido'])): ?>
                <?php foreach ($value['sido'] as $key => $sido): ?><?=$sido?> <?=!empty($value['gugun'][$key]) ? $value['gugun'][$key] : ''?>, <?php endforeach; ?>
                <?php endif; ?>
                <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원 / 예약인원 <?=cntRes($value['idx'])?>명
              </div>
            </div>
            <div class="col-3 col-sm-2 text-right">
              <?php if ($value['visible'] == VISIBLE_ABLE): ?>
              <button type="button" class="btn btn-sm btn-secondary btn-change-visible" data-idx="<?=$value['idx']?>" data-visible="<?=VISIBLE_NONE?>">숨김</button>
              <?php else: ?>
              <button type="button" class="btn btn-sm btn-default btn-change-visible" data-idx="<?=$value['idx']?>" data-visible="<?=VISIBLE_ABLE?>">공개</button>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; endif; ?>
        </div>
