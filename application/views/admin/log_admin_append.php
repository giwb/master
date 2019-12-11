<?php foreach ($listHistory as $value): ?>
<div class="border-top pt-3 pb-3 pl-2 pr-2">
  <strong><?=$value['header']?></strong> <?=$value['subject']?>
  <div class="small"><?=calcStoryTime($value['regdate'])?> (<?=date('Y-m-d H:i:s', $value['regdate'])?>)</div>
</div>
<?php endforeach; ?>