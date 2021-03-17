<div class="row no-gutters">
  <?php foreach ($album as $key => $photos): ?>
    <div class="col-12 font-weight-bold mt-5 mb-3"><h4 class="font-weight-bold"><?=$photos['title']?></h4></div>
    <?php foreach ($photos as $value): if (!empty($value['filename'])): ?>
      <?php foreach ($value['filename'] as $i => $photo): ?>
      <div class="album-item col-6 col-sm-2 p-1">
        <a class="btn-album" data-source="<?=$value['source'][$i]?>">
          <img class="album-photo w-100" src="<?=$photo?>">
          <div class="caption"><?=$value['subject']?><br><span class="small"><?=calcStoryTime($value['created_at'])?></span></div>
        </a>
      </div>
      <?php endforeach; ?>
    <?php endif; endforeach; ?>
  <?php endforeach; ?>
</div>
<?php /* <br><br><span class="small"><?=$value['nickname']?>님 | <?=calcStoryTime($value['created_at'])?><?=$value['created_by'] == $userIdx || !empty($adminCheck) ? ' | <a href="' . BASE_URL . '/album/entry/?n=' . $value['idx'] .'">수정</a>' : ''?></span> */ ?>
