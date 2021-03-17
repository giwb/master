<div class="row no-gutters">
  <?php foreach ($album as $key => $photos): $cnt = 0; ?>
    <div class="col-12 font-weight-bold mt-5 mb-3"><h4 class="font-weight-bold"><?=$photos['title']?></h4></div>
    <?php foreach ($photos as $value): if (!empty($value['filename'])): ?>
      <?php foreach ($value['filename'] as $i => $photo): ?>
      <div class="album-item col-6 col-sm-2 p-1">
        <a class="btn-album" data-index="<?=$cnt?>" data-notice-idx="<?=!empty($value['notice_idx']) ? $value['notice_idx'] : 0?>" data-src="<?=$value['source'][$i]?>" data-width="<?=$value['width'][$i]?>" data-height="<?=$value['height'][$i]?>" data-title="<?=$value['subject']?>">
          <img class="album-photo w-100" src="<?=$photo?>">
          <div class="caption"><?=$value['subject']?><br><span class="small"><?=calcStoryTime($value['created_at'])?></span></div>
        </a>
      </div>
      <?php $cnt++; endforeach; ?>
    <?php endif; endforeach; ?>
  <?php endforeach; ?>
</div>
