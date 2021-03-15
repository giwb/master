(function($) {
  "use strict"; // Start of use strict

  // 메세지 모달
  $.openMsgModal = function(msg) {
    $('#messageModal .modal-footer .btn').hide();
    $('#messageModal .modal-footer .btn-close').show();
    $('#messageModal .modal-message').html(msg);
    $('#messageModal').modal('show');
  }

  // Scroll to top button appear
  $(document).on('scroll',function() {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }
  });

  // Smooth scrolling using jQuery easing
  $(document).on('click', 'a.scroll-to-top', function(event) {
    var $anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1000, 'easeInOutExpo');
    event.preventDefault();
  });

})(jQuery); // End of use strict

$(document).on('click', '.login-popup', function() {
  // 로그인 모달
  $('#loginModal').modal('show');
}).on('click', '.btn-login', function(e) {
  // 로그인
  e.preventDefault();
  var $dom = $(this);
  var formData = new FormData($('.loginForm')[0]);
  var redirectUrl = $('input[name=redirectUrl]').val();

  if ($('input[name=login_userid]').val() == '' || $('input[name=login_password]').val() == '') {
    $('.error-message').slideDown().text('아이디와 비밀번호는 꼭 입력해주세요.');
    return false;
  }
  if (typeof redirectUrl == 'undefined') redirectUrl = '';

  $.ajax({
    url: '/login/process/?r=' + redirectUrl,
    data: formData,
    processData: false,
    contentType: false,
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $dom.css('opacity', '0.5').prop('disabled', true).text('로그인중..');
    },
    success: function(result) {
      if (result.error == 1) {
        $dom.css('opacity', '1').prop('disabled', false).text('로그인');
        $('.error-message').slideDown().text(result.message);
      } else {
        location.replace(result.message)
      }
    }
  });
}).on('click', '.logout', function() {
  // 로그아웃
  $.ajax({
    url: '/login/logout',
    dataType: 'json',
    success: function(result) {
      location.reload();
    }
  });
}).on('click', '.area-link', function() {
  location.href = $(this).data('link');
}).on('click', '.btn-more', function() {
  // 더보기
  var $btn = $(this);
  var paging = $('input[name=p]').val();
  var data = '';

  if (typeof paging != 'undefined') {
    $('input[name=p]').val(Number(paging) + 1);
    data = 'p=' + $('input[name=p]').val();

    $.ajax({
      url: '/welcome/article_list',
      data: data,
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true).text('불러오는 중.....');
      },
      success: function(result) {
        if (result == '') {
          $btn.css('opacity', '1').prop('disabled', true).text('마지막 페이지 입니다.');
        } else {
          $btn.css('opacity', '1').prop('disabled', false).text('더 보기');
          if (result != '') $('.article-list').append(result);
        }
      }
    });
  }
}).on('click', '.btn-liked', function() {
  // 좋아요
  var $btn = $(this);

  $.ajax({
    url: '/welcome/liked',
    data: 'idx=' + $btn.data('idx'),
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
          $('.reply-input[data-idx=' + replyIdx + ']').parent().parent().append('<div class="item-reply media mb-4" data-idx="' + result.idx + '"><img class="d-flex rounded-circle avatar z-depth-1-half mr-3" src="' + result.avatar + '"><div class="media-body"><h5 class="mt-0 font-weight-bold">' + nickname + '</h5><p class="dark-grey-text article">' + content + '</p><p class="small text-muted">' + result.date + ' <a class="text-danger ml-2 btn-reply-delete-modal" data-idx="' + result.idx + '">[삭제]</a></p></div></div>');
          $('.reply-input[data-idx=' + replyIdx + ']').remove();
        } else {
          $('.list-reply').append('<div class="item-reply media mb-4" data-idx="' + result.idx + '"><img class="d-flex rounded-circle avatar z-depth-1-half mr-3" src="' + result.avatar + '"><div class="media-body"><h5 class="mt-0 font-weight-bold">' + nickname + '</h5><p class="dark-grey-text article">' + content + '</p><p class="small text-muted">' + result.date + ' <a class="text-info ml-2 btn-reply-thread" data-idx="' + result.idx + '">[댓글]</a> <a class="text-danger ml-2 btn-reply-delete-modal" data-idx="' + result.idx + '">[삭제]</a></p></div></div>');
        }
      }
    }
  });
}).on('click', '.btn-reply-thread', function() {
  // 대댓글 출력
  var $dom = $('.reply-input[data-idx=0]').clone();
  if ($(this).hasClass('active')) {
    return false;
  } else {
    $(this).addClass('active');
    $dom.attr('data-idx', $(this).data('idx'));
    $dom.find('.reply-idx').val($(this).data('idx'));
    $(this).parent().append($dom);
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
        $('.reply-cnt').text(Number($('.reply-cnt').text() - 1));
        $('.item-reply[data-idx=' + idx + ']').remove();
        $dom.modal('hide');
      }
    }
  });
});
