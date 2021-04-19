$(document).on('click', '.btn-liked', function() {
  // 좋아요
  var $btn = $(this);
  var idx = $btn.data('idx');
  var typeService = $btn.data('type-service');
  var typeReaction = $btn.data('type-reaction');
  var clubIdx = $('input[name=clubIdx]').val();

  if (typeof typeService == 'undefined' || typeService == '') typeService = '';
  if (typeof typeReaction == 'undefined' || typeReaction == '') typeReaction = '';
  if (typeof clubIdx == 'undefined' || clubIdx == '') clubIdx = 0;

  $.ajax({
    url: '/welcome/liked',
    data: 'idx=' + idx + '&club_idx=' + clubIdx + '&type_service=' + typeService + '&type_reaction=' + typeReaction,
    dataType: 'json',
    type: 'post',
    success: function(result) {
      if (result.error == 0) {
        $('.cnt-liked').text(result.liked);
        if (result.message == 1) {
          $('.fa-heart').addClass('text-danger');
        } else {
          $('.fa-heart').removeClass('text-danger');
        }
      }
    }
  });
}).on('click', '.btn-reply', function() {
  // 댓글
  var $btn = $(this);
  var replyIdx = $btn.parent().parent().find('.reply-idx').val();
  var nickname = $btn.parent().parent().find('.reply-nickname').val();
  var content = $btn.parent().parent().find('.reply-content').val();

  if (typeof content == 'undefined' || content == '') {
    return false;
  }

  $.ajax({
    url: '/welcome/reply_insert',
    data: 'articleIdx=' + $btn.data('article-idx') + '&replyIdx=' + replyIdx + '&nickname=' + nickname + '&content=' + content,
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $btn.css('opacity', '0.5').prop('disabled', true);
    },
    success: function(result) {
      $btn.css('opacity', '1').prop('disabled', false);
      if (result.error == 0) {
        $('.reply-cnt').text(result.message);
        $('.reply-content').val('');
        if (replyIdx != 0) {
          $('.btn-reply-thread').removeClass('active');
          $('.reply-input[data-idx=' + replyIdx + ']').parent().parent().parent().append('<div class="item-reply media" data-idx="' + result.idx + '"><img class="d-flex rounded-circle avatar z-depth-1-half mr-3" src="' + result.avatar + '"><div class="media-body"><h6 class="mt-0 font-weight-bold">' + nickname + '<span class="small text-muted ml-2">' + result.date + '<a class="text-danger ml-2 btn-reply-delete-modal" data-idx="' + result.idx + '">[삭제]</a></span></h6><p class="dark-grey-text article">' + content + '</p></div></div>');
          $('.reply-input[data-idx=' + replyIdx + ']').remove();
        } else {
          $('.list-reply').append('<div class="item-reply media" data-idx="' + result.idx + '"><img class="d-flex rounded-circle avatar z-depth-1-half mr-3" src="' + result.avatar + '"><div class="media-body"><h6 class="mt-0 font-weight-bold">' + nickname + '<span class="small text-muted ml-2">' + result.date + '<a class="text-info ml-2 btn-reply-thread" data-idx="' + result.idx + '">[댓글]</a><a class="text-danger ml-2 btn-reply-delete-modal" data-idx="' + result.idx + '">[삭제]</a></span></h6><p class="dark-grey-text article">' + content + '</p></div></div>');
        }
      }
      $('.reply-input[data-idx=0]').show();
    }
  });
}).on('click', '.btn-reply-thread', function() {
  // 대댓글 출력
  var idx = $(this).data('idx');
  if ($(this).hasClass('active')) {
    $('.reply-input[data-idx=' + idx + ']').remove();
    $(this).removeClass('active');
    $('.reply-input[data-idx=0]').show();
  } else {
    var $dom = $('.reply-input[data-idx=0]').clone();
    $(this).addClass('active');
    $dom.attr('data-idx', idx);
    $dom.find('.reply-idx').val(idx);
    $(this).parent().append($dom);
    $('.reply-input[data-idx=0]').hide();
  }
}).on('click', '.btn-reply-delete-modal', function() {
  // 댓글 삭제 모달
  var $dom = $('#replyDeleteModal');
  $('input[name=idx]', $dom).val($(this).data('idx'));
  $dom.modal('show');
}).on('click', '.btn-reply-delete-submit', function() {
  // 댓글 삭제
  var $btn = $(this);
  var $dom = $('#replyDeleteModal');
  var idx = $('input[name=idx]', $dom).val();
  var idx_article = $('input[name=idx_article]', $dom).val();

  $.ajax({
    url: '/welcome/reply_delete',
    data: 'idx=' + idx + '&idx_article=' + idx_article,
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $btn.css('opacity', '0.5').prop('disabled', true);
    },
    success: function(result) {
      if (result.error == 0) {
        $btn.css('opacity', '1').prop('disabled', false);
        $('.reply-cnt').text(result.message);
        $('.item-reply[data-idx=' + idx + ']').remove();
        $dom.modal('hide');
      }
    }
  });
}).on('click', '.btn-article-delete-modal', function() {
  // 기사 삭제 모달
  $('#articleDeleteModal').modal('show');
}).on('click', '.btn-article-delete-submit', function() {
  // 기사 삭제
  var $btn = $(this);
  var $dom = $('#articleDeleteModal');
  var idx = $('input[name=idx]', $dom).val();
  var code = $('input[name=code]', $dom).val();

  $.ajax({
    url: '/welcome/article_delete',
    data: 'idx=' + idx,
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $btn.css('opacity', '0.5').prop('disabled', true);
    },
    success: function(result) {
      if (result.error == 0) {
        location.replace($('input[name=baseUrl]').val() + '/club/search/?code=' + code);
      }
    }
  });
});
