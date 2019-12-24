<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <link href="<?=base_url()?>public/css/magnific-popup.css" rel="stylesheet">
      <div class="club-main">
        <div class="sub-header">사진첩</div>
        <div class="sub-content">
          <div class="text-right mt-3 mb-3">
            <a href="<?=base_url()?>club/album_upload/<?=$view['idx']?>"><button class="btn btn-sm btn-primary">사진 등록</button></a>
          </div>
          <?php foreach ($listAlbum as $key => $value): ?>
            <?php if ($key == 0): ?><div class="row"><?php elseif ($key%3 == 0): ?></div><div class="row"><?php endif; ?>
            <div class="col-sm-4 text-center mb-2 gallery-item"><a href="javascript:;" class="btn-gallery" data-idx="<?=$value['idx']?>"><img class="gallery-photo border mb-2" src="<?=$value['photo']?>"><br><?=$value['subject']?></a><?=$value['created_by'] == $userIdx || !empty($adminCheck) ? ' | <a href="' . base_url() . 'club/album_upload/' . $view['idx'] . '?n=' . $value['idx'] .'">수정</a>' : ''?></div>
          <?php endforeach; ?>
          </div>
        </div>
      </div>

      <script src="<?=base_url()?>public/js/jquery.magnific-popup.min.js" type="text/javascript"></script>
      <script type="text/javascript">
        $(document).ready(function() {
          $('.btn-gallery').click(function() {
            var $dom = $(this).parent();
            var items = [];
            $.ajax({
              url: $('input[name=baseUrl]').val() + 'club/album_view',
              data: 'idx=' + $(this).data('idx'),
              dataType: 'json',
              type: 'post',
              beforeSend: function() {
                $('.gallery-photo', $dom).css('opacity', '0.5');
                $dom.append('<img class="ajax-loader" src="/public/images/preloader.png">')
              },
              success: function(result) {
                $('.gallery-photo', $dom).css('opacity', '1');
                $('.ajax-loader', $dom).remove();
                $.each(result, function(i, v) {
                  items.push({
                    src: v.src,
                    title: v.title
                  });
                });
              },
              complete: function() {
                $.magnificPopup.open({
                  items: items,
                  gallery: { enabled: true },
                  type: 'image',
                  image: { titleSrc: 'title' }
                });
              }
            });
          });
        });
      </script>