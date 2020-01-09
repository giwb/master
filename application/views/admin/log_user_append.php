<?php foreach ($listHistory as $value): ?>
<div class="row align-items-center border-top pt-3 pb-3 pl-2 pr-2 w-100">
  <div class="col-sm-11">
    <strong><?=$value['header']?></strong> <?=$value['subject']?> - <a <?=!empty($value['userid']) ? 'target="_blank" href="' . base_url() . 'admin/member_view/' . $value['userid'] . '"' : 'href="javascript:;"'?>><strong><?=$value['nickname']?>님</strong></a>
    <div class="small"><?=calcStoryTime($value['regdate'])?> (<?=date('Y-m-d H:i:s', $value['regdate'])?>)</div>
  </div>
  <div class="col-sm-1 text-right"><button class="btn btn-sm btn-primary btn-log-check" data-idx="<?=$value['idx']?>">확인</button></div>
</div>
<?php endforeach; ?>