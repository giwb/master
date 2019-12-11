<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800"><?=$pageTitle?></h1>
        </div>
        <form id="formList" method="post" action="<?=$pageUrl?>">
          <input type="hidden" name="p" value="1">
        </form>
        <?php foreach ($listHistory as $value): ?>
          <div class="border-top pt-3 pb-3 pl-2 pr-2">
            <strong><?=$value['header']?></strong> <?=$value['subject']?> - <a<?=!empty($value['userid']) ? ' target="_blank" href="' . base_url() . 'admin/member_view/' . $value['userid'] . '"' : ' href="javascript:;"'?> class="text-secondary"><strong><?=$value['nickname']?>님</strong></a>
            <div class="small"><?=calcStoryTime($value['regdate'])?> (<?=date('Y-m-d H:i:s', $value['regdate'])?>)</div>
          </div>
        <?php endforeach; ?>
        <div class="area-append">
        </div>
        <button class="btn btn-page-next">다음 페이지 보기 ▼</button>
      </div>
    </div>
