<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main id="club">
    <div class="container-fluid club-main">
      <div class="row mt-1 mb-5">
        <div class="col-xl-8 col-md-12 mypage">
          <div class="row align-items-end">
            <div class="col-4"><h4 class="font-weight-bold"><?=$pageTitle?></h4></div>
            <div class="col-8 text-right">잔액 <?=number_format($viewMember['penalty'])?> 페널티</small></div>
          </div>
          <hr class="text-default mt-2">

          <ul>
            <form id="formList" method="post" action="<?=BASE_URL?>/member/penalty">
            <input type="hidden" name="p" value="1">
            <?=$userPenalty?>
            <div class="area-append"></div>
            <?php if ($maxPenalty['cnt'] > $perPage): ?>
            <button class="btn btn-page-next">다음 페이지 보기 ▼</button>
            <?php endif; ?>
          </form>
          </ul>
        </div>
