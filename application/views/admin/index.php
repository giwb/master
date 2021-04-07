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

            <div class="text-right">
              <button type="button" class="btn-custom btn-giwb btn-bookmark-update">북마크 수정</button>
            </div>

            <div class="area-memo d-none"><?=!empty($viewBookmark['memo']) ? nl2br($viewBookmark['memo']) : ''?></div>

            <div class="area-edit-memo mt-3 mb-3">
              <a class="btn-bookmark btn-edit-memo" data-idx="<?=!empty($viewBookmark['idx']) ? $viewBookmark['idx'] : ''?>"><i class="far fa-plus-square"></i> 메모장 편집</a>
            </div>

            <div id="sortable" class="row no-gutters align-items-top area-bookmark">
              <?php if (!empty($listBookmark)): foreach ($listBookmark as $value): ?>
              <div class="col-6 col-sm-3 p-1 pb-5 text-center">
                <div class="bk-header" data-idx="<?=$value['idx']?>" style="background-color: <?=$value['bgcolor']?>"><a class="btn-bookmark btn-delete-bookmark-modal"><i class="fas fa-minus-square"></i></a> <a class="btn-bookmark btn-edit-category" data-idx="<?=$value['idx']?>" data-bgcolor="<?=$value['bgcolor']?>"><i class="fas fa-pen-square"></i></a> <span class="category"><?=$value['title']?></span></div>
                <div class="bk-body text-left p-2">
                  <?php if (!empty($value['bookmark'])): foreach ($value['bookmark'] as $bookmark): ?>
                    <div class="bk-item" data-idx="<?=$bookmark['idx']?>"><a class="btn-bookmark btn-delete-bookmark-modal"><i class="far fa-minus-square"></i></a> <a class="btn-bookmark btn-edit-bookmark"><i class="far fa-edit"></i></a> <?php if (!empty($bookmark['link']) && !empty($bookmark['title'])): ?><a target="_blank" href="<?=$bookmark['link']?>" data-title="<?=$bookmark['title']?>" data-memo="<?=!empty($bookmark['memo']) ? $bookmark['memo'] : ''?>" class="link-bookmark"><?=$bookmark['title']?><?php else: ?><?=!empty($bookmark['memo']) ? '<a data-memo="' . $bookmark['memo'] . '" class="link-bookmark">' . $bookmark['memo'] : ''?><?php endif; ?></a></div>
                  <?php endforeach; endif; ?>
                  <a class="btn-bookmark btn-add-bookmark" data-idx="<?=$value['idx']?>"><i class="far fa-plus-square"></i> 북마크 추가</a>
                </div>
              </div>
              <?php endforeach; endif; ?>
              <div class="col-sm-3 text-center area-add-category btn-bookmark">
                <a class="btn-add-category"><i class="far fa-plus-square"></i> 카테고리 추가</a>
              </div>
            </div>
            <div class="text-right mt-5">
              <button type="button" class="btn-custom btn-giwb btn-bookmark-update">북마크 수정</button>
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
  $(document).on('click', '.btn-bookmark-update', function() {
    // 북마크 수정 토글
    $('.btn-bookmark').toggle();
    if ($('.btn-bookmark').is(':visible') == false) {
      $('#sortable').sortable('disable');
    } else {
      $('#sortable').sortable('enable');
    }
  }).on('click', '.btn-add-category', function() {
    // 북마크 카테고리 추가 입력폼 표시
    $('.area-add-category').remove();
    $('.area-bookmark').append('<div class="col-sm-3 p-1 pb-5 text-center bk-editing"><div class="row no-gutters align-items-center bk-header" style="background-color: #929fba;"><div class="col-7"><input type="text" name="title" class="form-control form-control-sm" placeholder="카테고리명 입력"></div><div class="col-2"><input type="text" name="bgcolor" class="form-control form-control-sm" placeholder="색"></div><div class="col-3"><button type="button" class="btn-custom btn-giwb btn-add-category-submit">등록</button></div></div></div>');
    $('input[name=title]').focus();
  }).on('click', '.btn-edit-category', function() {
    // 북마크 카테고리 수정 입력폼 표시
    var $dom = $(this).parent();
    var $domParent = $(this).parent().parent();
    var idx = $(this).data('idx');
    var bgcolor = $(this).data('bgcolor');
    var title = $dom.find('.category').text();
    $dom.remove();
    $domParent.prepend('<div class="bk-editing"><div class="row no-gutters align-items-center bk-header" style="background-color: #929fba;"><div class="col-7"><input type="text" name="title" class="form-control form-control-sm" value="' + title + '"></div><div class="col-2"><input type="text" name="bgcolor" class="form-control form-control-sm" value="' + bgcolor + '"></div><div class="col-3"><input type="hidden" name="idx" value="' + idx + '"><button type="button" class="btn-custom btn-giwb btn-add-category-submit">수정</button></div></div></div>');
    $('input[name=title]').focus();
  }).on('click', '.btn-add-category-submit', function() {
    // 북마크 카테고리 추가/수정 처리
    var $btn = $(this);
    var idx = $('input[name=idx]').val();
    var title = $('input[name=title]').val();
    var bgcolor = $('input[name=bgcolor]').val();
    if (typeof idx == 'undefined' || idx == '') idx = '';
    if (title == '') return false;
    $.ajax({
      url: '/admin/bookmark_update',
      data: 'clubIdx=' + $('input[name=clubIdx]').val() + '&idx=' + idx + '&title=' + title + '&bgcolor=' + bgcolor,
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true);
      },
      success: function(result) {
        $btn.css('opacity', '1').prop('disabled', false);
        var $dom = $('.bk-editing').parent();
        $('.bk-editing').remove();
        if (idx == '') {
          // 등록
          $('.area-bookmark').append('<div class="col-sm-3 p-1 pb-5 text-center"><div class="bk-header" style="background-color: ' + bgcolor + '"><a class="btn-bookmark btn-delete-bookmark-modal"><i class="fas fa-minus-square"></i></a> <a class="btn-bookmark btn-edit-category" data-idx="' + result.message + '"><i class="fas fa-pen-square"></i></a> ' + title + '</div><div class="bk-body text-left p-2"><a class="btn-bookmark btn-add-bookmark" data-idx="' + result.message + '"><i class="far fa-plus-square"></i> 북마크 추가</a></div></div><div class="col-sm-3 text-center area-add-category"><a class="btn-add-category"><i class="far fa-plus-square"></i> 카테고리 추가</a></div>');
        } else {
          // 수정
          $dom.prepend('<div class="bk-header" style="background-color: ' + bgcolor + '"><a class="btn-bookmark btn-delete-bookmark-modal"><i class="fas fa-minus-square"></i></a> <a class="btn-bookmark btn-edit-category" data-idx="' + result.message + '"><i class="fas fa-pen-square"></i></a> <span class="category">' + title + '</span></div>');
        }
        $('.btn-bookmark').show();
      }
    });
  }).on('click', '.btn-add-bookmark', function() {
    // 북마크 추가 입력폼 표시
    $(this).parent().append('<div class="bk-editing"><div class="mt-1 mb-1"><input type="text" name="title" class="form-control form-control-sm" placeholder="북마크명"></div><div class="mb-1"><input type="text" name="link" class="form-control form-control-sm" placeholder="링크URL"></div><div class="mb-1"><input type="text" name="memo" class="form-control form-control-sm" placeholder="설명"></div><input type="hidden" name="parent_idx" value="' + $(this).data('idx') + '"><button type="button" class="btn-custom btn-giwb btn-add-bookmark-submit">등록</button></div>');
    $(this).remove();
  }).on('click', '.btn-edit-bookmark', function() {
    // 북마크 수정 입력폼 표시
    var $dom = $(this).parent();
    var idx = $dom.data('idx');
    var link = $dom.find('.link-bookmark').attr('href');
    var title = $dom.find('.link-bookmark').data('title');
    var memo = $dom.find('.link-bookmark').data('memo');
    var parent_idx = $dom.parent().parent().find('.bk-header').data('idx');

    if (typeof title == 'undefined') title = '';
    if (typeof link == 'undefined') link = '';

    $dom.empty().append('<div class="bk-editing"><div class="mt-1 mb-1"><input type="text" name="title" class="form-control form-control-sm" value="' + title + '" placeholder="북마크명"></div><div class="mb-1"><input type="text" name="link" class="form-control form-control-sm" value="' + link + '" placeholder="링크URL"></div><div class="mb-1"><input type="text" name="memo" class="form-control form-control-sm" value="' + memo + '" placeholder="설명"></div><input type="hidden" name="parent_idx" value="' + parent_idx + '"><input type="hidden" name="idx" value="' + idx + '"><button type="button" class="btn-custom btn-giwb btn-add-bookmark-submit">수정</button></div>');
  }).on('click', '.btn-add-bookmark-submit', function() {
    // 북마크 추가/수정 처리
    var $btn = $(this);
    var $dom = $btn.parent().parent();
    var idx = $dom.find('input[name=idx]').val();
    var parent_idx = $dom.find('input[name=parent_idx]').val();
    var title = $dom.find('input[name=title]').val();
    var link = $dom.find('input[name=link]').val();
    var memo = $dom.find('input[name=memo]').val();
    if (typeof idx == 'undefined' || idx == '') idx = '';
    $.ajax({
      url: '/admin/bookmark_update',
      data: 'clubIdx=' + $('input[name=clubIdx]').val() + '&title=' + title + '&link=' + link + '&memo=' + memo + '&parent_idx=' + parent_idx + '&idx=' + idx,
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true);
      },
      success: function(result) {
        $btn.css('opacity', '1').prop('disabled', false);
        $('.bk-editing').remove();
        if (link == '' && title == '' && memo != '') {
          $dom.append('<a class="btn-bookmark btn-delete-bookmark-modal"><i class="far fa-minus-square"></i></a> <a class="btn-bookmark btn-edit-bookmark"><i class="far fa-edit"></i></a> <a data-memo="' + memo + '" class="link-bookmark">' + memo + '</a>');
        } else {
          $dom.append('<a class="btn-bookmark btn-delete-bookmark-modal"><i class="far fa-minus-square"></i></a> <a class="btn-bookmark btn-edit-bookmark"><i class="far fa-edit"></i></a> <a target="_blank" href="' + link + '" data-title="' + title + '" data-memo="' + memo + '" class="link-bookmark">' + title + '</a>');
        }
        if (idx == '') {
          $dom.append('<br><a class="btn-bookmark btn-add-bookmark" data-idx="' + parent_idx + '"><i class="far fa-plus-square"></i> 북마크 추가</a>');
        }
        $('.btn-bookmark').show();
      }
    });
  }).on('click', '.btn-edit-memo', function() {
    // 메모 수정 입력폼 표시
    var text = $('.area-memo').text();
    $('.area-edit-memo').hide();
    $('.area-memo').empty().append('<div class="row no-gutters bk-editing"><div class="col-12"><textarea name="memo" rows="10" class="form-control">' + text + '</textarea></div><div class="col-12 mt-2 mb-5 text-right"><input type="hidden" name="idx" value="' + $(this).data('idx') + '"><button type="button" class="btn-custom btn-giwb btn-edit-memo-submit">메모장 수정</button></div>')
  }).on('click', '.btn-edit-memo-submit', function() {
    // 메모 수정 처리
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
  }).on('click', '.btn-delete-bookmark-modal', function() {
    // 북마크 삭제 모달
    var $dom = $('#bookmarkDeleteModal');
    $('input[name=idx]', $dom).val($(this).parent().data('idx'));
    $dom.modal('show');
  }).on('click', '.btn-delete-bookmark-submit', function() {
    // 북마크 삭제 처리
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
        $btn.css('opacity', '0.5').prop('disabled', true);
      },
      success: function() {
        $btn.css('opacity', '1').prop('disabled', false);
        $('.bk-header[data-idx=' + idx + ']').parent().remove();
        $('.bk-item[data-idx=' + idx + ']').remove();
        $dom.modal('hide');
      }
    });
  });

  $('#sortable').disableSelection().sortable({
    // 정렬
    disabled: true,
    stop: function(event, ui) {
      var arrSort = new Array();

      $('.bk-header').each(function() {
        arrSort.push($(this).data('idx'));
      })

      $.ajax({
        url: '/admin/bookmark_sort',
        data: 'idx=' + arrSort,
        dataType: 'json',
        type: 'post',
        success: function() {}
      });
    }
  });

  $(document).ready(function() {
    // 북마크 설명 보여주기
    $('.link-bookmark').hover(function(){
      var memo = $(this).data('memo');
      if (typeof memo != 'undefined' && memo != '') {
        $(this).append('<div class="layer-memo">' + memo + '</div>')
      }
    }, function() {
      $('.layer-memo').remove();
    });
  });
</script>