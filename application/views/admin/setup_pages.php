<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div id="content" class="mb-5">
          <form id="setupForm" method="post" action="<?=BASE_URL?>/admin/setup_pages_update" enctype="multipart/form-data" class="mb-0">
          <input type="hidden" name="clubIdx" value="<?=$view['idx']?>">
            <div class="area-page">
              <?php foreach ($listClubDetail as $key => $value): ?>
              <div class="item-notice pt-3">
                <div class="row no-gutters align-items-center mb-2">
                  <div class="col-2 col-sm-1 p-0">페이지명</div>
                  <div class="col-8 col-sm-10 p-0 pr-2"><input type="hidden" name="idx[]" value="<?=$value['idx']?>"><input type="text" name="title[]" value="<?=$value['title']?>" class="form-control form-control-sm"></div>
                  <div class="col-2 col-sm-1 p-0 text-right"><button type="button" class="btn-custom btn-giwbred btn-delete-page pl-2 pr-2">삭제</button></div>
                </div>
                <textarea name="content[]" rows="10" cols="100" id="content_<?=$key?>" class="content"><?=$value['content']?></textarea>
              </div>
              <?php endforeach; ?>
              <div class="item-notice pt-3">
                <div class="row no-gutters align-items-center mb-2">
                  <div class="col-2 col-sm-1 p-0">페이지명</div>
                  <div class="col-10 col-sm-11 p-0"><input type="text" name="title[]" class="form-control form-control-sm"></div>
                </div>
                <textarea name="content[]" rows="10" cols="100" class="content"></textarea>
              </div>
            </div>
            <div class="text-center mt-4">
              <button type="button" class="btn-custom btn-info btn-add-page mr-2 pt-2 pb-2 pl-4 pr-4">항목추가</button>
              <button type="submit" class="btn-custom btn-giwb ml-2 mr-4 pt-2 pb-2 pl-4 pr-4">저장하기</button>
            </div>
          </form>
        </div>

        <script src="/public/ckeditor/ckeditor.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript">
          CKEDITOR.replaceAll();
          $('#sortable').disableSelection().sortable();
          $(document).on('click', '.btn-add-page', function() {
            // 항목 추가
            var cnt = 0;
            $('.content').each(function() { cnt++; });
            var content = '<div class="item-notice pt-3"><div class="row no-gutters align-items-center mb-2"><div class="col-2 col-sm-1 p-0">페이지명</div><div class="col-8 col-sm-10 p-0"><input type="text" name="title[]" class="form-control form-control-sm"></div><div class="col-2 col-sm-1 p-0 text-right"><button type="button" class="btn-custom btn-giwbred btn-delete-page pl-2 pr-2">삭제</button></div></div><textarea name="content[]" rows="10" cols="100" id="content_' + cnt + '" class="content"></textarea></div>';
            $('.area-page').append(content);
            CKEDITOR.replace('content_' + cnt);
            //$('html, body').animate({ scrollTop: $(document).height() }, 800);
          }).on('click', '.btn-delete-page', function() {
            // 항목 삭제
            var html = '<div class="w-100 text-center">저장 후 삭제됩니다.</div>';
            $(this).closest('.item-notice').animate({ height: 50 }, 'slow').html(html);
          });
        </script>
