<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">댓글 기록</h1>
        </div>
      </div>

      <form id="formList" method="post" action="/admin_old/log_reply">
        <input type="hidden" name="p" value="1">
      </form>

      <div class="story-reply">
        <?=$listReply?>
        <div class="area-append"></div>
      </div>

      <button class="btn btn-page-next">다음 페이지 보기 ▼</button>
    </div>
