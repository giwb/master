<?php foreach ($listHistory as $value): ?>
<div class="border-top pt-3 pb-3 pl-2 pr-2">
  <strong><?=$value['header']?></strong> <?=$value['subject']?> - <a target="_blank" href="<?=base_url()?>admin/member_view/<?=$value['userid']?>"><strong><?=$value['nickname']?>님</strong></a>
  <div class="small"><?=calcStoryTime($value['regdate'])?> (<?=date('Y-m-d H:i:s', $value['regdate'])?>)</div>
</div>
<?php endforeach; ?>