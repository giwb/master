<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">진행중 산행 목록</h1>
        </div>

        <?php foreach ($list as $value): ?>
        <div class="border-top pt-2 pb-2">
          <b><?=viewStatus($value['status'], $value['visible'])?></b> <a href="<?=base_url()?>admin/main_view_progress/<?=$value['idx']?>"><?=$value['subject']?></a><br>
          <?php if (!empty($value['sido'])): ?>
          <?php foreach ($value['sido'] as $key => $sido): ?><?=$sido?> <?=!empty($value['gugun'][$key]) ? $value['gugun'][$key] : ''?>, <?php endforeach; ?>
          <?php endif; ?>
          <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원 / 예약인원 <?=cntRes($value['idx'])?>명
        </div>
        <?php endforeach; ?>
      </div>
    </div>
