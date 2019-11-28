$(document).on('click', '.btn-reply', function() {
  // 댓글 열기
  var storyIdx = $(this).data('idx');
  var $dom = $('.story-reply[data-idx=' + storyIdx + ']');
  var html = '';

  $('.story-reply-content', $dom).empty();

  if ($dom.css('display') == 'none') {
    $.ajax({
      url: $('input[name=base_url]').val() + 'story/reply/' + $('input[name=clubIdx]').val(),
      data: 'storyIdx=' + storyIdx,
      dataType: 'json',
      type: 'post',
      success: function(result) {
        if (result.error == 1) {
          $.openMsgModal(result.message);
        } else {
          $('.story-reply-content', $dom).append(result.message);
          $dom.slideDown();
        }
      }
    });
  } else {
    $dom.slideUp();
  }
}).on('click', '.btn-post-reply', function() {
  // 댓글 달기
  var $btn = $(this);
  var storyIdx = $btn.data('idx');
  var $form = $('.story-reply-input[data-idx=' + storyIdx + ']');
  var formData = new FormData($form[0]);

  $.ajax({
    url: $form.attr('action'),
    data: formData,
    processData: false,
    contentType: false,
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $btn.css('opacity', '0.5').prop('disabled', true).text('등록중..');
    },
    success: function(result) {
      $btn.css('opacity', '1').prop('disabled', false).text('댓글달기');
      if (result.error == 1) {
        $.openMsgModal('댓글 등록에 실패했습니다. 다시 시도해주세요.');
      } else {
        $('.club-story-reply').val('');
        $('.story-reply[data-idx=' + storyIdx + '] .story-reply-content').append(result.message);
        $('#post-' + storyIdx + ' .cnt-reply').text(result.reply_cnt);
      }
    }
  });
}).on('click', '.btn-like', function() {
  // 좋아요
  var $dom = $(this);
  var userIdx = $('input[name=userIdx]').val();

  if (userIdx == '') {
    $.openMsgModal('로그인을 해주세요.');
    return false;
  }
  
  $.ajax({
    url: $('input[name=base_url]').val() + 'story/like/' + $('input[name=clubIdx]').val(),
    data: 'storyIdx=' + $(this).data('idx'),
    dataType: 'json',
    type: 'post',
    success: function(result) {
      if (result.error == 1) {
        $.openMsgModal(result.message);
      } else {
        $dom.find('.cnt-like').text(result.count);
        if (result.type == 1) $dom.addClass('text-danger'); else $dom.removeClass('text-danger');
      }
    }
  });
}).on('click', '.btn-share', function() {
  // 공유하기
  var $dom = $('.area-share');
  if ($dom.css('display') == 'none') {
    $dom.show();
  } else {
    $dom.hide();
  }
}).on('click', '.btn-post-delete-modal', function() {
  // 삭제하기 모달
  $('#messageModal .btn').hide();
  $('#messageModal .btn-delete, #messageModal .btn-close').show();
  $('#messageModal .modal-message').text('정말로 삭제하시겠습니까?');
  $('#messageModal input[name=action]').val($(this).data('action'));
  $('#messageModal input[name=delete_idx]').val($(this).data('idx'));
  $('#messageModal').modal();
}).on('click', '.btn-delete', function() {
  // 삭제하기
  var $btn = $(this);
  $.ajax({
    url: $('input[name=base_url]').val() + 'story/' + $('#messageModal input[name=action]').val() + '/' + $('input[name=clubIdx]').val(),
    data: 'idx=' + $('input[name=delete_idx]').val(),
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
    },
    success: function(result) {
      if (result.error == 1) {
        $btn.css('opacity', '1').prop('disabled', false).text('삭제합니다');
        $('#messageModal .btn').hide();
        $('#messageModal .btn-refresh, #messageModal .btn-close').show();
        $('#messageModal .modal-message').text(result.message);
        $('#messageModal').modal();
      } else {
        location.reload();
      }
    }
  });
}).on('click', '.btn-post', function() {
  // 스토리 작성
  var $dom = $(this);
  var content = $('#club-story-content').val();
  var photo = $('.icon-photo-delete').data('filename');
  var page = $('input[name=page]').val();
  var userIdx = $('input[name=userIdx]').val();

  if (userIdx == '') {
    $.openMsgModal('로그인을 해주세요.');
    return false;
  }

  if (content == '') { return false; }
  if (typeof(photo) == 'undefined') { photo = ''; }

  $.ajax({
    url: $('input[name=base_url]').val() + 'story/insert/' + $('input[name=clubIdx]').val(),
    data: 'page=' + $('input[name=page]').val() + '&photo=' + photo + '&content=' + content,
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $dom.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
      $('#club-story-content').prop('disabled', true);
    },
    success: function(result) {
      if (result.error == 1) {
        $dom.css('opacity', '1').prop('disabled', false).text('등록합니다');
        $('#club-story-content').prop('disabled', false).val('');
        $('#messageModal .btn').hide();
        $('#messageModal .btn-refresh, #messageModal .btn-close').show();
        $('#messageModal .modal-message').text(result.message);
        $('#messageModal').modal();
      } else {
        /*$('#club-story-content').prop('disabled', false).val('');
        $dom.css('opacity', '1').prop('disabled', false).text('등록합니다');*/
        location.reload();
      }
    }
  });
}).on('click', '.btn-photo', function() {
  // 사진 선택
  $(this).prev().click();
}).on('click', '.icon-photo-delete', function() {
  // 사진 삭제
  var page = $('input[name=page]').val();

  $.ajax({
    url: $('input[name=base_url]').val() + 'story/delete_photo/' + $('input[name=clubIdx]').val(),
    data: 'page=' + $('input[name=page]').val() + '&photo=' + $(this).data('filename'),
    dataType: 'json',
    type: 'post',
    success: function(result) {
      if (result.error == 0) {
        $('.area-photo').empty();
        $('.btn-photo').show();
      }
    }
  });
});