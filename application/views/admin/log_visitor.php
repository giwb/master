<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">방문자 기록</h1>
        </div>
        <form id="formSearch" method="post" action="<?=base_url()?>admin/log_visitor" class="mb-3 pb-3 visitor-header">
          <div class="row align-items-center justify-content-center">
            <div class="pl-3"><a href="<?=base_url()?>admin/log_visitor?<?=$searchPrev?>">◀</a></div>
            <div class="pl-3">
              <select name="y" class="form-control">
                <?php foreach (range($searchYear, 2010) as $value): ?>
                  <option<?=!empty($searchYear) && $searchYear == $value ? ' selected' : ''?> value='<?=$value?>'><?=$value?>년</option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="pl-3">
              <select name="m" class="form-control">
                <?php foreach (range(1, 12) as $value): ?>
                <option<?=!empty($searchMonth) && $searchMonth == $value ? ' selected' : ''?> value='<?=strlen($value) < 2 ? '0' . $value : $value?>'><?=$value?>월</option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="pl-3">
              <select name="d" class="form-control">
                <?php foreach (range(1, 31) as $value): ?>
                <option<?=!empty($searchDay) && $searchDay == $value ? ' selected' : ''?> value='<?=strlen($value) < 2 ? '0' . $value : $value?>'><?=$value?>일</option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="pl-3"><button type="button" class="btn btn-primary btn-visitor-search">검색</button></div>
            <div class="pl-3"><a href="<?=base_url()?>admin/log_visitor?<?=$searchNext?>">▶</a></div>
          </div>
        </div>
      </form>

      <strong>・총 방문횟수 : <?=count($listVisitor)?>회</strong>
      <div class="row align-items-center border-top pt-2 pb-2 bg-primary text-white visitor-row">
        <div class="col-sm-1 pl-4">접속시간</div>
        <div class="col-sm-1">닉네임/IP</div>
        <div class="col-sm-2">링크된곳</div>
        <div class="col-sm-8">브라우저</div>
      </div>
      <?php foreach ($listVisitor as $value): ?>
      <div class="row align-items-center border-top pt-2 pb-2 small visitor-data">
        <div class="col-sm-1 pl-4"><?=calcStoryTime($value['created_at'])?></div>
        <div class="col-sm-1"><?=!empty($value['nickname']) ? '<a href="' . base_url() . 'admin/member_view/' . $value['created_by'] . '">' . $value['nickname'] . '</a>' : $value['ip_address']?></div>
        <div class="col-sm-2"><a target="_blank" href="$value['http_referer']"><?=strlen($value['http_referer']) > 40 ? substr($value['http_referer'], 0, 40) . '...' : $value['http_referer']?></a></div>
        <div class="col-sm-8"><?=$value['user_agent']?></div>
      </div>
      <?php endforeach; ?>
      <div class="mb-5"></div>
    </div>
