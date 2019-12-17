<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">산행지로 보기</h1>
          <button class="btn btn-primary btn-get-attendance">최신 데이터 받기</button>
        </div>
        <div class="row align-items-center border bg-primary text-white small p-2">
          <div class="col-sm-1">No.</div>
          <div class="col-sm-1">닉네임</div>
          <div class="col-sm-1">횟수</div>
          <div class="col-sm-9">산행내역</div>
        </div>
        <?php foreach ($listNickname as $key => $value): ?>
        <div class="row align-items-center border small p-2">
          <div class="col-sm-1"><?=$key+1?></div>
          <div class="col-sm-1"><?=$value['nickname']?></div>
          <div class="col-sm-1"><?=$value['cnt']?></div>
          <div class="col-sm-9"><?=$value['mname']?></div>
        </div>
        <?php endforeach; ?>
        <div class="mb-5"></div>
      </div>
    </div>
