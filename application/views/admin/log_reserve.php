<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div id="content" class="mb-5">
          <form id="formList" method="post" action="/admin/log_reserve">
            <input type="hidden" name="p" value="1">
            <input type="hidden" name="k" value="<?=$keyword?>">
          </form>
          <?=$listReserve?>
          <div class="area-append"></div>
          <button class="btn btn-page-next">다음 페이지 보기 ▼</button>
        </div>
