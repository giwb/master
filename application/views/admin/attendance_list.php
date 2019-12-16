<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">출석체크 보기</h1>
          <button class="btn btn-primary btn-get-attendance">최신 데이터 받기</button>
        </div>

        <div class="table-check">
          <div class="table-head w30">No.</div>
          <div class="table-head w100">닉네임</div>
          <div class="table-head w30">횟수</div>
          <?php if (!empty($viewType)): ?>
          <div class="table-head w88p">산행내역</div>
          <?php else: foreach ($listAttendanceNotice as $value): ?>
          <div class="table-head w30" title="<?=$value['mname']?>"><a href="#"><?=$value['mname']?></a></div>
          <?php endforeach; endif; ?>
        </div>
        <div class="scrolling">
          <?php foreach ($listNickname as $value): ?>
          <div class="table-check">
            <div class="table-body w30"><?=$value['rank']?></div>
            <div class="table-body w100"><a href="#"><?=$value['nickname']?>님</a></div>
            <div class="table-body w30"><?=$value['cnt']?></div>
            <?php foreach ($value['listNotice'] as $notice): ?>
            <div class="table-body w30"><?=!empty($notice['idx']) ? 1 : '&nbsp;'?></div>
            <?php endforeach; ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
