<div class="">
  <?php foreach ($album as $key => $photos): $cnt = 0; ?>
    <div class="font-weight-bold mt-5 mb-3"><h4 class="font-weight-bold"><?=$photos['title']?></h4></div>
    <div class="grid">
    <?php foreach ($photos as $value): if (!empty($value['filename'])): ?>
      <?php foreach ($value['filename'] as $i => $photo): ?>
      <div class="album-item" style="height: <?=$value['thumb_height'][$i]?>px">
        <a class="btn-album-view" data-index="<?=$cnt?>" data-notice-idx="<?=!empty($value['notice_idx']) ? $value['notice_idx'] : 0?>" data-src="<?=$value['source'][$i]?>" data-width="<?=$value['width'][$i]?>" data-height="<?=$value['height'][$i]?>" data-title="<?=$value['subject']?>">
          <img class="album-photo" style="width: <?=$value['thumb_width'][$i]?>px;" src="<?=$photo?>">
          <div class="caption"><table width="100%"><tr><td><?=$value['subject']?><br><span class="small"><?=calcStoryTime($value['created_at'])?></span></td></tr></table></div>
        </a>
      </div>
      <?php $cnt++; endforeach; ?>
    <?php endif; endforeach; ?>
    </div>
  <?php endforeach; ?>
</div>

<script type="text/javascript">
  $('.grid').masonry({
    itemSelector: '.album-item',
  });
</script>