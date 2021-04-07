<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <script type="text/javascript" src="/public/se2/js/HuskyEZCreator.js" charset="utf-8"></script>

        <?php if ($view['status'] != STATUS_PLAN) echo $headerMenuView; ?>
        <div id="content" class="mb-5">
          <div class="sub-contents">
            <h4 class="font-weight-bold m-0 p-0 pb-2"><?=viewStatus($view['status'])?> <?=$view['subject']?></h4>
            <?php if (!empty($view['type'])): ?><div class="ti"><strong>・유형</strong> : <?=$view['type']?></div><?php endif; ?>
            <div class="ti"><strong>・일시</strong> : <?=$view['startdate']?> (<?=calcWeek($view['startdate'])?>) <?=$view['starttime']?></div>
            <?php $view['cost'] = $view['cost_total'] == 0 ? $view['cost'] : $view['cost_total']; if (!empty($view['cost'])): ?>
            <?php if (!empty($view['sido'])): ?>
            <div class="ti"><strong>・지역</strong> : <?php foreach ($view['sido'] as $key => $value): if ($key != 0): ?>, <?php endif; ?><?=$value?> <?=!empty($view['gugun'][$key]) ? $view['gugun'][$key] : ''?><?php endforeach; ?></div>
            <?php endif; ?>
            <div class="ti"><strong>・요금</strong> : <?=number_format($view['cost_total'] == 0 ? $view['cost'] : $view['cost_total'])?>원 (<?=calcTerm($view['startdate'], $view['starttime'], $view['enddate'], $view['schedule'])?><?=!empty($view['distance']) ? ', ' . calcDistance($view['distance']) : ''?><?=!empty($view['options']) ? ', ' . getOptions($view['options']) : ''?><?=!empty($view['options_etc']) ? ', ' . $view['options_etc'] : ''?><?=!empty($view['options']) || !empty($view['options_etc']) ? ' 제공' : ''?><?=!empty($view['costmemo']) ? ', ' . $view['costmemo'] : ''?>)</div>
            <?php endif; ?>
            <?=!empty($view['content']) ? '<div class="ti"><strong>・코스</strong> : ' . nl2br($view['content']) . '</div>' : ''?>
            <?=!empty($view['kilometer']) ? '<div class="ti"><strong>・거리</strong> : ' . $view['kilometer'] . '</div>' : ''?>
            <div class="ti"><strong>・예약</strong> : <?=cntRes($view['idx'])?>명</div>
            <form id="formNotice" method="post" action="<?=BASE_URL?>/admin/main_notice_update" enctype="multipart/form-data" class="mb-0">
              <input type="hidden" name="noticeIdx" value="<?=$view['idx']?>">
              <div class="text-right">
                <button type="button" class="btn-custom btn-giwbblue btn-template-modal">기본 템플릿 불러오기</button>
              </div>
              <div class="area-notice">
                <div class="mt-3 border-top border-bottom">
                  <div class="row no-gutters align-items-center mt-3 mb-3">
                    <div class="col-3 col-sm-2 p-0 pl-2">대표 사진<br><small>※ 최적크기 : 500 x 300</small></div>
                    <div class="col-7 col-sm-7 p-0 pr-2">
                      <?php if (!empty($view['photo']) && file_exists(PHOTO_PATH . 'thumb_' . $view['photo'])): ?>
                      <a target="_blank" href="<?=PHOTO_URL . $view['photo']?>"><img src="<?=PHOTO_URL . 'thumb_' . $view['photo']?>"></a>
                      <input type="hidden" name="filename" value="<?=$view['photo']?>">
                      <?php else: ?>
                      <input type="file" name="photo">
                      <?php endif; ?>
                    </div>
                    <div class="col-2 col-sm-3 pr-2 text-right">
                      <?php if (!empty($view['photo'])): ?>
                      <button type="button" class="btn-custom btn-giwbred btn-notice-photo-delete pl-2 pr-2">삭제</button>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <div id="sortable">
                  <?php foreach ($listNoticeDetail as $key => $value): ?>
                  <div class="item-notice pt-3">
                    <div class="row no-gutters align-items-center mb-2">
                      <div class="col-10 col-sm-11"><input type="hidden" name="idx[]" value="<?=$value['idx']?>"><input type="text" name="title[]" value="<?=$value['title']?>" class="form-control form-control-sm"></div>
                      <div class="col-2 col-sm-1 pr-2 text-right"><button type="button" class="btn-custom btn-giwbred btn-delete-notice-modal pl-2 pr-2" data-idx="<?=$value['idx']?>">삭제</button></div>
                    </div>
                    <textarea name="content[]" rows="10" cols="100" id="content_<?=$key?>" class="se-content"><?=$value['content']?></textarea>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
              <div class="text-center mt-4">
                <div class="error-message p-2"></div>
                <button type="button" class="btn-custom btn-info btn-add-notice mr-2 pt-2 pb-2 pl-4 pr-4">항목추가</button>
                <a target="_blank" href="<?=BASE_URL?>/admin/main_notice_view/<?=$view['idx']?>"><button type="button" class="btn-custom btn-gray ml-2 mr-2 pt-2 pb-2 pl-4 pr-4">복사하기</button></a>
                <button type="button" class="btn-custom btn-giwb btn-notice-update ml-2 mr-4 pt-2 pb-2 pl-4 pr-4">저장하기</button>
              </div>
            </form>
          </div>
        </div>

        <div class="modal fade" id="noticeDeleteModal" tabindex="-1" role="dialog" aria-labelledby="noticeDeleteModalLabel" aria-hidden="true">
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
                <button type="button" class="btn btn-danger btn-delete-notice-submit">삭제합니다</button>
                <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
              </div>
            </div>
          </div>
        </div>

        <script type="text/javascript">
          $('#sortable').disableSelection().sortable();

          var cnt = 0;
          $('.se-content').each(function(n) {
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

          $(document).on('click', '.btn-notice-update', function() {
            // 공지사항 등록
            for (var i = 0; i < oEditors.length; i++) {
              if (oEditors[i] != null) { oEditors[i][0].exec("UPDATE_CONTENTS_FIELD", []); }
            }

            var $btn = $(this);
            var $dom = $('#formNotice');
            var formData = new FormData($dom[0]);

            $('.se-content').each(function(n) {
              formData.append('content_' + n, $(this).val());
            });

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
              success: function(result) {
                if (result.error == 1) {
                  $btn.css('opacity', '1').prop('disabled', false);
                  $('.error-message').text(result.message).slideDown();
                  setTimeout(function() { $('.error-message').text('').slideUp(); }, 2000);
                } else {
                  location.reload();
                }
              }
            });
          }).on('click', '.btn-add-notice', function() {
            // 공지사항 항목 추가
            var cnt = 0;
            $('.se-content').each(function() { cnt++; });
            var content = '<div class="item-notice pt-3"><div class="row no-gutters align-items-center mb-2"><div class="col-10 col-sm-11"><input type="text" name="title[]" class="form-control form-control-sm"></div><div class="col-2 col-sm-1 pr-2 text-right"><button type="button" class="btn-custom btn-giwbred btn-delete-notice-modal pl-2 pr-2">삭제</button></div></div><textarea name="content[]" rows="10" cols="100" id="content_' + cnt + '" class="se-content"></textarea></div>';
            $('.area-notice').append(content);

            oEditors[cnt] = [];
            setEditor(cnt);

            $('html, body').animate({ scrollTop: $(document).height() }, 800);
          }).on('click', '.btn-delete-notice-modal', function() {
            // 공지사항 항목 삭제 모달
            var $dom = $('#noticeDeleteModal');
            var idx = $(this).data('idx');
            $('input[name=idx]', $dom).val(idx);
            $dom.modal('show');
          }).on('click', '.btn-delete-notice-submit', function() {
            // 공지사항 항목 삭제
            var $btn = $(this);
            $.ajax({
              url: '/admin/main_notice_item_delete',
              data: 'noticeIdx=' + $('input[name=noticeIdx]').val() + '&idx=' + $('#noticeDeleteModal input[name=idx]').val(),
              dataType: 'json',
              type: 'post',
              beforeSend: function() {
                $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
              },
              success: function() {
                location.reload();
              }
            });
          }).on('click', '.btn-template-modal', function() {
            // 기본 템플릿 불러오기
            var $dom = $('#sortable');
            $dom.empty();
            $dom.append('<div class="item-notice pt-3"><div class="row no-gutters align-items-center mb-2"><div class="col-10 col-sm-11"><input type="text" name="title[]" value="기획의도" class="form-control form-control-sm"></div><div class="col-2 col-sm-1 pr-2 text-right"></div></div><textarea name="content[]" rows="10" cols="100" id="content_0" class="se-content"></textarea></div>');
            $dom.append('<div class="item-notice pt-3"><div class="row no-gutters align-items-center mb-2"><div class="col-10 col-sm-11"><input type="text" name="title[]" value="산행개요" class="form-control form-control-sm"></div><div class="col-2 col-sm-1 pr-2 text-right"></div></div><textarea name="content[]" rows="10" cols="100" id="content_1" class="se-content"></textarea></div>');
            $dom.append('<div class="item-notice pt-3"><div class="row no-gutters align-items-center mb-2"><div class="col-10 col-sm-11"><input type="text" name="title[]" value="행선지 소개" class="form-control form-control-sm"></div><div class="col-2 col-sm-1 pr-2 text-right"></div></div><textarea name="content[]" rows="10" cols="100" id="content_2" class="se-content"></textarea></div>');
            $dom.append('<div class="item-notice pt-3"><div class="row no-gutters align-items-center mb-2"><div class="col-10 col-sm-11"><input type="text" name="title[]" value="일정안내" class="form-control form-control-sm"></div><div class="col-2 col-sm-1 pr-2 text-right"></div></div><textarea name="content[]" rows="10" cols="100" id="content_3" class="se-content"></textarea></div>');
            $dom.append('<div class="item-notice pt-3"><div class="row no-gutters align-items-center mb-2"><div class="col-10 col-sm-11"><input type="text" name="title[]" value="행사안내" class="form-control form-control-sm"></div><div class="col-2 col-sm-1 pr-2 text-right"></div></div><textarea name="content[]" rows="10" cols="100" id="content_4" class="se-content"></textarea></div>');
            $dom.append('<div class="item-notice pt-3"><div class="row no-gutters align-items-center mb-2"><div class="col-10 col-sm-11"><input type="text" name="title[]" value="코스안내" class="form-control form-control-sm"></div><div class="col-2 col-sm-1 pr-2 text-right"></div></div><textarea name="content[]" rows="10" cols="100" id="content_5" class="se-content"></textarea></div>');
            $dom.append('<div class="item-notice pt-3"><div class="row no-gutters align-items-center mb-2"><div class="col-10 col-sm-11"><input type="text" name="title[]" value="관광버스 및 개인 생활방역 안내" class="form-control form-control-sm"></div><div class="col-2 col-sm-1 pr-2 text-right"></div></div><textarea name="content[]" rows="10" cols="100" id="content_6" class="se-content"></textarea></div>');
            $dom.append('<div class="item-notice pt-3"><div class="row no-gutters align-items-center mb-2"><div class="col-10 col-sm-11"><input type="text" name="title[]" value="기타안내" class="form-control form-control-sm"></div><div class="col-2 col-sm-1 pr-2 text-right"></div></div><textarea name="content[]" rows="10" cols="100" id="content_7" class="se-content"></textarea></div>');

            var oEditors = new Array(8);
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
          });
        </script>
