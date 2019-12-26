$(document).ready(function() {
  $('.btn-album').click(function() {
    var $dom = $(this).parent();
    var items = [];
    $.ajax({
      url: $('input[name=baseUrl]').val() + 'club/album_view',
      data: 'idx=' + $(this).data('idx'),
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $('.album-photo', $dom).css('opacity', '0.5');
        $dom.append('<img class="ajax-loader" src="/public/images/preloader.png">')
      },
      success: function(result) {
        $('.album-photo', $dom).css('opacity', '1');
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
}).on('change', '.photo', function() {
  // 사진 업로드
  var $dom = $(this);
  var baseUrl = $('input[name=baseUrl]').val();
  var formData = new FormData($('form')[0]);
  var maxSize = 20480000;
  var size = $dom[0].files[0].size;

  if (size > maxSize) {
    $dom.val('');
    $('#messageModal .modal-message').text('파일의 용량은 20MB를 넘을 수 없습니다.');
    $('#messageModal').modal('show');
    return;
  }

  // 사진 형태 추가
  formData.append('file_obj', $dom[0].files[0]);

  $.ajax({
    url: baseUrl + 'club/upload',
    processData: false,
    contentType: false,
    data: formData,
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $('.btn-upload-photo').css('opacity', '0.5').prop('disabled', true).text('업로드중.....');
      $dom.val('');
    },
    success: function(result) {
      $('.btn-upload-photo').css('opacity', '1').prop('disabled', false).text('사진 선택');
      if (result.error == 1) {
        $.openMsgModel(result.message);
      } else {
        var $domFiles = $('input[name=photos]');
        $('.added-files').append('<img src="' + result.message + '" class="btn-photo-modal" data-photo="' + result.filename + '">');
        if ($domFiles.val() == '') {
          $domFiles.val(result.filename);
        } else {
          $domFiles.val($domFiles.val() + ',' + result.filename);
        }
      }
    }
  });
}).on('click', '.btn-upload-photo', function() {
  // 사진 업로드 클릭
  $(this).prev().click();
}).on('click', '.btn-album-update', function() {
  // 등록/수정
  $(this).css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요......');
  if ($('input[name=subject]').val() == '') {
    $.openMsgModal('제목은 꼭 입력해주세요.');
    return false;
  }
  if ($('textarea[name=content]').val() == '') {
    $.openMsgModal('내용은 꼭 입력해주세요.');
    return false;
  }
  if ($('input[name=photos]').val() == '') {
    $.openMsgModal('사진은 꼭 선택해주세요.');
    return false;
  }
  $('#formAlbum').submit();
}).on('click', '.btn-album-delete-modal', function() {
  $('#albumDeleteModal').modal({backdrop: 'static', keyboard: false});
}).on('click', '.btn-album-delete', function() {
  // 삭제
  var $btn = $(this);
  var baseUrl = $('input[name=baseUrl]').val();
  $.ajax({
    url: baseUrl + 'club/album_delete',
    data: 'idx=' + $('input[name=idx]').val(),
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $btn.css('opacity', '0.5').prop('disabled', true).text('삭제중.......');
    },
    success: function(result) {
      $btn.css('opacity', '1').prop('disabled', false).text('삭제합니다');
      $('#albumDeleteModal .modal-message').text(result.message);
      if (result.error != 1) {
        $('#albumDeleteModal .close').hide();
        $('#albumDeleteModal .btn-album-delete, #albumDeleteModal .btn-close').hide();
        $('#albumDeleteModal .btn-album-list').removeClass('d-none');
      }
    }
  });
});