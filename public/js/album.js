$(document).on('click', '.btn-album-insert', function() {
  // 등록
  if ($('select[name=noticeIdx]').val() == '') {
    $.openMsgModal('다녀온 여행은 꼭 선택해주세요.');
    return false;
  }
  if ($('input[name=subject]').val() == '') {
    $.openMsgModal('사진 설명은 꼭 입력해주세요.');
    return false;
  }

  var $btn = $(this);
  var $dom = $('.multi');
  var formData = new FormData($('#formPhoto')[0]);

  $.ajax({
    url: '/album/insert',
    processData: false,
    contentType: false,
    data: formData,
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
    },
    success: function(result) {
      location.replace($('input[name=baseUrl]').val() + '/album');
    }
  });
}).on('click', '.btn-album-delete-modal', function() {
  $('#albumDeleteModal').modal({backdrop: 'static', keyboard: false});
}).on('click', '.btn-album-delete', function() {
  // 삭제
  var $btn = $(this);
  $.ajax({
    url: '/album/delete',
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