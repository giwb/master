<?php
  foreach ($listReply as $value):
    if ($value['reply_type'] == REPLY_TYPE_STORY):  $url = BASE_URL . '/story/view/' . $value['story_idx']; endif;
    if ($value['reply_type'] == REPLY_TYPE_NOTICE): $url = BASE_URL . '/admin/main_view_progress/' . $value['story_idx']; endif;
    if ($value['reply_type'] == REPLY_TYPE_SHOP):   $url = BASE_URL . '/shop/item/' . $value['story_idx']; endif;
    if (file_exists(PHOTO_PATH . $value['created_by'])) $value['photo'] = PHOTO_URL . $value['created_by'];
    else $value['photo'] = '/public/images/user.png';
?>
<dl>
  <dt><img class="img-profile" src="<?=$value['photo']?>"></dt>
  <dd>
    <strong><?=$value['nickname']?></strong> · <span class="reply-date"><?=calcStoryTime($value['created_at'])?></span><br>
    <?=$value['content']?><br>
    <a href="<?=$url?>" target="_blank">원글보기</a> | 
    <a href="javascript:;" class="btn-reply-delete" data-idx="<?=$value['idx']?>" data-action="delete_reply">삭제</a>
  </dd>
</dl>
<?php endforeach; ?>