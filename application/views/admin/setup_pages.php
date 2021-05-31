<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <script type="text/javascript" src="/public/se2/js/HuskyEZCreator.js" charset="utf-8"></script>

        <div id="content" class="mb-5">
          <form id="formSetupPage" method="post" action="<?=BASE_URL?>/admin/setup_pages_update" enctype="multipart/form-data" class="mb-0">
          <input type="hidden" name="clubIdx" value="<?=$view['idx']?>">
            <div id="sortable" class="area-page">
              <?php foreach ($listClubDetail as $key => $value): ?>
              <div class="item-notice pt-3">
                <div class="row no-gutters align-items-center mb-2">
                  <div class="col-2 col-sm-1">페이지명</div>
                  <div class="col-8 col-sm-10"><input type="hidden" name="idx[]" value="<?=$value['idx']?>"><input type="text" name="title[]" value="<?=$value['title']?>" class="form-control form-control-sm"></div>
                  <div class="col-2 col-sm-1 pr-2 text-right"><button type="button" class="btn-custom btn-giwbred btn-delete-page-modal" data-idx="<?=$value['idx']?>">삭제</button></div>
                </div>
                <textarea name="content[]" rows="10" cols="100" id="content_<?=$key?>" class="se-content editor-count"><?=$value['content']?></textarea>
              </div>
              <?php endforeach; ?>
            </div>

            <?php if ($view['idx'] == 1): // 경인웰빙 전용 ?>
            <div class="pt-3">
              <div class="row no-gutters align-items-center mb-2">
                <div class="col-2 col-sm-1">페이지명</div>
                <div class="col-8 col-sm-10"><input readonly type="text" value="경인웰빙 100대명산" class="form-control form-control-sm"></div>
              </div>
              <textarea name="content-mountain" rows="10" cols="100" id="content_mountain" class="se-content"><?=$view['mountain']?></textarea>
            </div>
            <div class="pt-3">
              <div class="row no-gutters align-items-center mb-2">
                <div class="col-2 col-sm-1">페이지명</div>
                <div class="col-8 col-sm-10"><input readonly type="text" value="경인웰빙 100대명소" class="form-control form-control-sm"></div>
              </div>
              <textarea name="content-place" rows="10" cols="100" id="content_place" class="se-content"><?=$view['place']?></textarea>
            </div>
            <?php endif; ?>

            <div class="text-center mt-4">
              <button type="button" class="btn-custom btn-info btn-add-page mr-2 pt-2 pb-2 pl-4 pr-4">항목추가</button>
              <button type="button" class="btn-custom btn-giwb btn-page-update ml-2 mr-4 pt-2 pb-2 pl-4 pr-4">저장하기</button>
            </div>
          </form>
        </div>

        <div class="modal fade" id="pageDeleteModal" tabindex="-1" role="dialog" aria-labelledby="pageDeleteModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="smallmodalLabel">메세지</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body text-center">
                <p class="modal-message">정말로 삭제하시겠습니까?</p>
              </div>
              <div class="modal-footer">
                <input type="hidden" name="idx">
                <button type="button" class="btn btn-danger btn-delete-page-submit">삭제합니다</button>
                <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
              </div>
            </div>
          </div>
        </div>

        <script src="/public/ckeditor/ckeditor.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript">
          //$('#sortable').disableSelection().sortable();

          var cnt = 0;
          $('.editor-count').each(function(n) {
            cnt++;
          });

          var oEditors = new Array(cnt);
          function setEditor(n){
            nhn.husky.EZCreator.createInIFrame({
              oAppRef: oEditors[n],
              elPlaceHolder: 'content_' + n,
              sSkinURI: '/public/se2/SmartEditor2Skin_Club.html',
              fCreator: 'createSEditor2',
              htParams: { fOnBeforeUnload: function(){} }
            });
          }

          $(function(){
            for (var i = 0; i < oEditors.length; i++) {
              if (oEditors[i] == null) {
                oEditors[i] = [];
                setEditor(i);
              }
            }
          });

          <?php if ($view['idx'] == 1): // 경인웰빙 전용 ?>
          var oEditor1 = []
          nhn.husky.EZCreator.createInIFrame({
            oAppRef: oEditor1,
            elPlaceHolder: 'content_mountain',
            sSkinURI: '/public/se2/SmartEditor2Skin_Club.html',
            fCreator: 'createSEditor2',
            htParams: { fOnBeforeUnload: function(){} }
          });
          var oEditor2 = []
          nhn.husky.EZCreator.createInIFrame({
            oAppRef: oEditor2,
            elPlaceHolder: 'content_place',
            sSkinURI: '/public/se2/SmartEditor2Skin_Club.html',
            fCreator: 'createSEditor2',
            htParams: { fOnBeforeUnload: function(){} }
          });
          <?php endif; ?>

          $(document).on('click', '.btn-page-update', function() {
            // 페이지 등록
            for (var i = 0; i < oEditors.length; i++) {
              if (oEditors[i] != null) { oEditors[i][0].exec("UPDATE_CONTENTS_FIELD", []); }
            }

            var $btn = $(this);
            var $dom = $('#formSetupPage');
            var formData = new FormData($dom[0]);

            $('.editor-count').each(function(n) {
              formData.append('content_' + n, $(this).val());
            });

            <?php if ($view['idx'] == 1): // 경인웰빙 전용 ?>
              oEditor1[0].exec("UPDATE_CONTENTS_FIELD", []);
              oEditor2[0].exec("UPDATE_CONTENTS_FIELD", []);
              formData.append('content_mountain', $('#content_mountain').val());
              formData.append('content_place', $('#content_place').val());
            <?php endif; ?>

            $.ajax({
              url: $dom.attr('action'),
              data: formData,
              processData: false,
              contentType: false,
              dataType: 'json',
              type: 'post',
              beforeSend: function() {
                $btn.css('opacity', '0.5').prop('disabled', true);
              },
              success: function() {
                location.reload();
              }
            });
          }).on('click', '.btn-add-page', function() {
            // 항목 추가
            var cnt = 0;
            $('.editor-count').each(function() { cnt++; });
            var content = '<div class="item-notice pt-3"><div class="row no-gutters align-items-center mb-2"><div class="col-2 col-sm-1">페이지명</div><div class="col-8 col-sm-10"><input type="text" name="title[]" class="form-control form-control-sm"></div><div class="col-2 col-sm-1 pr-2 text-right"><button type="button" class="btn-custom btn-giwbred btn-delete-page-modal">삭제</button></div></div><textarea name="content[]" rows="10" cols="100" id="content_' + cnt + '" class="se-content editor-count"></textarea></div>';
            $('.area-page').append(content);

            oEditors[cnt] = [];
            setEditor(cnt);
          }).on('click', '.btn-delete-page-modal', function() {
            // 항목 삭제 모달
            var $dom = $('#pageDeleteModal');
            var idx = $(this).data('idx');
            $('input[name=idx]', $dom).val(idx);
            $dom.modal('show');
          }).on('click', '.btn-delete-page-submit', function() {
            // 항목 삭제
            var $btn = $(this);
            $.ajax({
              url: '/admin/setup_pages_delete',
              data: 'clubIdx=' + $('input[name=clubIdx]').val() + '&idx=' + $('#pageDeleteModal input[name=idx]').val(),
              dataType: 'json',
              type: 'post',
              beforeSend: function() {
                $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
              },
              success: function() {
                location.reload();
              }
            });
          });
        </script>
