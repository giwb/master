<?php foreach ($listHistory as $value): ?>
<div class="border-top pt-3 pb-3 pl-2 pr-2">
  <strong><?=$value['header']?></strong> <?=$value['subject']?> - <a href="<?=base_url()?>admin/member_view/<?=$value['idx']?>"><strong><?=$value['nickname']?>님</strong></a>
</div>
<?php endforeach; ?>