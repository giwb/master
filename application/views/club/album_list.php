<div class="row no-gutters">
  <?php foreach ($photos as $key => $value): ?>
  <?php foreach ($value['filename'] as $i => $photo): ?>
  <div class="album-item col-6 col-sm-2 p-1">
    <a class="btn-album" data-source="<?=$value['source'][$i]?>">
      <img class="album-photo w-100" src="<?=$photo?>">
      <div class="caption"><?=$value['subject']?><br><span class="small"><?=calcStoryTime($value['created_at'])?></span></div>
    </a>
  </div>
  <?php endforeach; ?>
  <?php endforeach; ?>
</div>
<?php /* <br><br><span class="small"><?=$value['nickname']?>님 | <?=calcStoryTime($value['created_at'])?><?=$value['created_by'] == $userIdx || !empty($adminCheck) ? ' | <a href="' . BASE_URL . '/album/entry/?n=' . $value['idx'] .'">수정</a>' : ''?></span> */ ?>
