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
      scrollTop: 0
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
  var formData = new FormData($('#formLogin')[0]);
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
}).on('click', '.btn-logout', function() {
  // 로그아웃
  $.ajax({
    url: '/login/logout',
    dataType: 'json',
    success: function(result) {
      location.reload();
    }
  });
}).on('click', '.area-link', function() {
  var link = $(this).data('link');
  var target = $(this).data('target');
  if (typeof link == 'undefined' || link == '') return false;
  else {
    if (target == 'new') {
      window.open(link);
    } else {
      location.href = link;
    }
  }
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
});
