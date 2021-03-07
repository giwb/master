(function($) {
  "use strict"; // Start of use strict

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
});