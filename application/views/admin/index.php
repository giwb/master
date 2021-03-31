<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

          <section id="bookmark" class="mb-5">
            <div class="row no-gutters">
              <div class="col-5 col-sm-8"><h4 class="font-weight-bold">관리자 페이지</h4></div>
              <div class="col-7 col-sm-4 text-right">
                <form method="post" action="<?=BASE_URL?>/admin/log_reserve">
                  <div class="row no-gutters">
                    <div class="col-9 col-sm-10 pr-2"><input type="text" name="k" value="<?=!empty($keyword) ? $keyword : ''?>" class="form-control form-control-sm"></div>
                    <div class="col-3 col-sm-2"><button type="button" class="btn-custom btn-giwb h-100">검색</button></div>
                  </div>
                </form>
              </div>
            </div>
            <hr class="text-default mt-2">

            <div class="area-memo"><?=!empty($viewBookmark['memo']) ? $viewBookmark['memo'] : ''?></div>

            <div class="area-edit-memo mt-3 mb-3">
              <a class="btn-edit-memo" data-idx="<?=!empty($viewBookmark['idx']) ? $viewBookmark['idx'] : ''?>"><i class="far fa-plus-square"></i> 메모장 편집</a>
            </div>

            <div class="row no-gutters align-items-top area-bookmark">
              <?php if (!empty($listBookmark)): foreach ($listBookmark as $value): ?>
              <div class="col-sm-3 p-1 pb-5 text-center">
                <div class="bk-header"><a class="btn-delete-bookmark" data-idx="<?=$value['idx']?>"><i class="far fa-minus-square"></i></a> <?=$value['title']?></div>
                <div class="bk-body text-left p-2">
                  <?php if (!empty($value['bookmark'])): foreach ($value['bookmark'] as $bookmark): ?>
                    <a class="btn-delete-bookmark" data-idx="<?=$bookmark['idx']?>"><i class="far fa-minus-square"></i></a> <a target="_blank" href="<?=$bookmark['link']?>"><?=$bookmark['title']?></a><br>
                  <?php endforeach; endif; ?>
                  <a class="btn-add-bookmark" data-idx="<?=$value['idx']?>"><i class="far fa-plus-square"></i> 북마크 추가</a>
                </div>
              </div>
              <?php endforeach; endif; ?>
              <div class="col-sm-3 text-center area-add-category">
                <a class="btn-add-category"><i class="far fa-plus-square"></i> 카테고리 추가</a>
              </div>
            </div>
          </section>

          <div class="modal fade" id="bookmarkDeleteModal" tabindex="-1" role="dialog" aria-labelledby="bookmarkDeleteModalLabel" aria-hidden="true">
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
                  <input type="hidden" name="idx" value="">
                  <button type="button" class="btn btn-danger btn-delete-bookmark-submit">삭제합니다</button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
                </div>
              </div>
            </div>
          </div>

<script>
  $(document).on('click', '.btn-add-category', function() {
    $('.area-add-category').remove();
    $('.area-bookmark').append('<div class="col-sm-3 p-1 pb-5 text-center bk-editing"><div class="row no-gutters align-items-center bk-header"><div class="col-9"><input type="text" name="title" class="form-control form-control-sm" placeholder="카테고리명 입력"></div><div class="col-3"><button type="button" class="btn-custom btn-giwb btn-add-category-submit">등록</button></div></div></div>');
    $('input[name=title]').focus();
  }).on('click', '.btn-add-category-submit', function() {
    var $btn = $(this);
    var title = $('input[name=title]').val();
    if (title == '') return false;
    $.ajax({
      url: '/admin/bookmark_update',
      data: 'title=' + title,
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true);
      },
      success: function(result) {
        $btn.css('opacity', '1').prop('disabled', false);
        $('.bk-editing').remove();
        $('.area-bookmark').append('<div class="col-sm-3 p-1 pb-5 text-center"><div class="bk-header"><a class="btn-delete-bookmark" data-idx="' + result.message + '"><i class="far fa-minus-square"></i></a> ' + title + '</div><div class="bk-body text-left p-2"><a class="btn-add-bookmark" data-idx="' + result.message + '"><i class="far fa-plus-square"></i> 북마크 추가</a></div></div><div class="col-sm-3 text-center area-add-category"><a class="btn-add-category"><i class="far fa-plus-square"></i> 카테고리 추가</a></div>');
      }
    });
  }).on('click', '.btn-add-bookmark', function() {
    $(this).parent().append('<div class="bk-editing"><div class="mt-1 mb-1"><input type="text" name="title" class="form-control form-control-sm" placeholder="북마크명"></div><div class="mb-1"><input type="text" name="link" class="form-control form-control-sm" placeholder="링크URL"></div><input type="hidden" name="parent_idx" value="' + $(this).data('idx') + '"><button type="button" class="btn-custom btn-giwb btn-add-bookmark-submit">등록</button></div>');
    $(this).remove();
  }).on('click', '.btn-add-bookmark-submit', function() {
    var $btn = $(this);
    var $dom = $btn.parent().parent();
    var parent_idx = $dom.find('input[name=parent_idx]').val();
    var title = $dom.find('input[name=title]').val();
    var link = $dom.find('input[name=link]').val();
    if (title == '' || link == '') return false;
    $.ajax({
      url: '/admin/bookmark_update',
      data: 'title=' + title + '&link=' + link + '&parent_idx=' + parent_idx,
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true);
      },
      success: function(result) {
        $btn.css('opacity', '1').prop('disabled', false);
        $('.bk-editing').remove();
        $dom.append('<a class="btn-delete-bookmark" data-idx="' + result.message + '"><i class="far fa-minus-square"></i></a> <a target="_blank" href="' + link + '">' + title + '</a><br><a class="btn-add-bookmark" data-idx="' + parent_idx + '"><i class="far fa-plus-square"></i> 북마크 추가</a>');
      }
    });
  }).on('click', '.btn-edit-memo', function() {
    var text = $('.area-memo').text();
    $('.area-edit-memo').hide();
    $('.area-memo').empty().append('<div class="row no-gutters bk-editing"><div class="col-12"><textarea name="memo" rows="10" class="form-control">' + text + '</textarea></div><div class="col-12 mt-2 mb-5 text-right"><input type="hidden" name="idx" value="' + $(this).data('idx') + '"><button type="button" class="btn-custom btn-giwb btn-edit-memo-submit">메모장 수정</button></div>')
  }).on('click', '.btn-edit-memo-submit', function() {
    var $btn = $(this);
    var memo = $('textarea[name=memo]').val();
    if (memo == '') return false;
    $.ajax({
      url: '/admin/bookmark_update',
      data: 'memo=' + memo,
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true);
      },
      success: function(result) {
        $btn.css('opacity', '1').prop('disabled', false);
        $('.bk-editing').remove();
        $('.area-memo').append(memo);
        $('.area-edit-memo').show();
      }
    });
  }).on('click', '.btn-delete-bookmark', function() {
    var $dom = $('#bookmarkDeleteModal');
    $('input[name=idx]', $dom).val($(this).data('idx'));
    $dom.modal('show');
  }).on('click', '.btn-delete-bookmark-submit', function() {
    var $btn = $(this);
    var $dom = $('#bookmarkDeleteModal');
    var idx = $('input[name=idx]', $dom).val();
    if (idx == '') return false;
    $.ajax({
      url: '/admin/bookmark_delete',
      data: 'idx=' + idx,
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