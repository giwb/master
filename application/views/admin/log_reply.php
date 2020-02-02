<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div id="content" class="mb-5">
          <form id="formList" method="post" action="<?=BASE_URL?>/admin/log_reply">
            <input type="hidden" name="p" value="1">
          </form>
          <div class="admin-reply story-reply">
            <?=$listReply?>
            <div class="area-append"></div>
          </div>
          <?php if ($cntReply['cnt'] > 0): ?>
          <button class="btn btn-page-next">다음 페이지 보기 ▼</button>
          <?php endif; ?>
        </div>
