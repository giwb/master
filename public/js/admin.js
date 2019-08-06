(function($) {
  "use strict"; // Start of use strict

  // Toggle the side navigation
  $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
    if ($(".sidebar").hasClass("toggled")) {
      $('.sidebar .collapse').collapse('hide');
    };
  });

  // Close any open menu accordions when window is resized below 768px
  $(window).resize(function() {
    if ($(window).width() < 768) {
      $('.sidebar .collapse').collapse('hide');
    };
  });

  // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
  $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
    if ($(window).width() > 768) {
      var e0 = e.originalEvent,
        delta = e0.wheelDelta || -e0.detail;
      this.scrollTop += (delta < 0 ? 1 : -1) * 30;
      e.preventDefault();
    }
  });

  // Scroll to top button appear
  $(document).on('scroll', function() {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }
  });

  // Smooth scrolling using jQuery easing
  $(document).on('click', 'a.scroll-to-top', function(e) {
    var $anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1000, 'easeInOutExpo');
    e.preventDefault();
  });

  $(document).on('click', '.btn-member-update', function() {
    var $dom = $(this);
    var formData = new FormData($('#formMember')[0]);
    $.ajax({
      url: $('#formMember').attr('action'),
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $dom.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
      },
      success: function() {
        $dom.css('opacity', '1').prop('disabled', false).text('수정합니다');
        $('.error-message').text('수정이 완료되었습니다.').slideDown();
        setTimeout(function() {
          $('.error-message').slideUp().text('');
        }, 3000);
      }
    });
  }).on('click', '.btn-member-delete-modal', function() {
    // 회원 삭제 모달
    $('#messageModal .modal-message').text('정말로 이 회원을 삭제하시겠습니까?');
    $('#messageModal .btn-refresh, #messageModal .close').hide();
    $('#messageModal .btn-delete').show();
    $('#messageModal input[name=action]').val('member_delete');
    $('#messageModal').modal({backdrop: 'static', keyboard: false});
  }).on('click', '.btn-delete', function() {
    // 삭제
    var $dom = $(this);
    $.ajax({
      url: $('input[name=base_url]').val() + 'admin/' + $('#messageModal input[name=action]').val(),
      data: 'idx=' + $('input[name=idx]').val(),
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $dom.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
      },
      success: function() {
        $('#messageModal .btn-delete, #messageModal .btn-close').hide();
        $('#messageModal .btn-list').attr('data-action', 'member_list').show();
        $('#messageModal .modal-message').text('삭제가 완료되었습니다.');
      }
    });
  }).on('click', '.btn-list', function() {
    // 모달 돌아가기 버튼
    location.replace($('input[name=base_url]').val() + 'admin/' + $(this).data('action'));
  }).on('click', '.btn-member-list', function() {
    // 회원 목록 돌아가기
    location.href=($('input[name=base_url]').val() + 'admin/member_list');
  });

})(jQuery); // End of use strict

