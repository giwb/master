<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main id="club">
    <div class="container-fluid club-main">
      <div class="row mt-1 mb-5">
        <div class="col-xl-8 col-md-12 mypage">
          <h4 class="font-weight-bold"><?=$pageTitle?> <small>(<?=$maxVisit['cnt']?>건)</small></h4>
          <hr class="text-default">

          <form id="formList" method="post" action="<?=BASE_URL?>/member/reserve_past">
            <input type="hidden" name="p" value="1">
            <?=$userVisit?>
            <div class="area-append"></div>
            <?php if ($maxVisit['cnt'] > $perPage): ?>
            <button class="btn btn-page-next">다음 페이지 보기 ▼</button>
            <?php endif; ?>
          </form>
        </div>
