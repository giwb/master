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
    $('#messageModal input[name=delete_idx]').val($('input[name=idx]').val());
    $('#messageModal').modal({backdrop: 'static', keyboard: false});
  }).on('click', '.btn-front-delete-modal', function() {
    // 메인 사진 삭제 모달
    $('#messageModal .modal-message').text('정말로 삭제하시겠습니까?');
    $('#messageModal .btn-refresh, #messageModal .close').hide();
    $('#messageModal .btn-delete').show();
    $('#messageModal input[name=action]').val('setup_front_delete');
    $('#messageModal input[name=delete_idx]').val($(this).data('filename'));
    $('#messageModal').modal({backdrop: 'static', keyboard: false});
  }).on('click', '.btn-bustype-delete-modal', function() {
    // 차종 삭제 모달
    $('#messageModal .modal-message').text('정말로 삭제하시겠습니까?');
    $('#messageModal .btn-refresh, #messageModal .close').hide();
    $('#messageModal .btn-delete').show();
    $('#messageModal input[name=action]').val('setup_bustype_delete');
    $('#messageModal input[name=delete_idx]').val($(this).data('idx'));
    $('#messageModal').modal({backdrop: 'static', keyboard: false});
  }).on('click', '.btn-delete', function() {
    // 삭제
    var $dom = $(this);
    $.ajax({
      url: $('input[name=base_url]').val() + 'admin/' + $('#messageModal input[name=action]').val(),
      data: 'idx=' + $('input[name=delete_idx]').val(),
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $dom.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
      },
      success: function() {
        $('#messageModal .btn-delete, #messageModal .btn-close').hide();
        $('#messageModal .btn-list').attr('data-action', $('input[name=back_url').val()).show();
        $('#messageModal .modal-message').text('삭제가 완료되었습니다.');
        $('#messageModal').modal('show');
      }
    });
  }).on('click', '.btn-front-submit', function() {
    // 대문 등록
    if ($('input[name=filename]').val() == '') {
      $('#messageModal .btn-refresh, #messageModal .btn-delete').hide();
      $('#messageModal .modal-message').text('파일을 선택해주세요.');
      $('#messageModal').modal('show');
    } else {
      // 파일 업로드
      var $dom = $('#formFront');
      var $btn = $(this);
      var action = $dom.attr('action');
      var formData = new FormData($dom[0]);
      var maxSize = 20480000;
      var size = $('.file', $dom)[0].files[0].size;

      if (size > maxSize) {
        $('.file', $dom).val('');
        $('#messageModal .btn-refresh, #messageModal .btn-delete').hide();
        $('#messageModal .modal-message').text('파일의 용량은 20MB를 넘을 수 없습니다.');
        $('#messageModal').modal('show');
        return;
      }

      // 사진 형태 추가
      formData.append('file_obj', $('.file', $dom)[0].files[0]);

      $.ajax({
        url: action,
        processData: false,
        contentType: false,
        data: formData,
        dataType: 'json',
        type: 'post',
        beforeSend: function() {
          $btn.css('opacity', '0.5').prop('disabled', true).text('업로드중..');
          $('.file', $dom).val('');
        },
        success: function(result) {
          if (result.error == 1) {
            $('#messageModal .btn-list, #messageModal .btn-refresh, #messageModal .btn-delete').hide();
            $('#messageModal .modal-message').text(result.message);
            $('#messageModal').modal('show');
            $btn.css('opacity', '1').prop('disabled', false).text('등록하기');
          } else {
            location.reload();
          }
        }
      });
    }
  }).on('click', '.btn-front-sort', function() {
    // 대문 정렬
    var $dom = $('#formSort .sort-idx');
    var $btn = $(this);
    var formData = new FormData($('#formSort')[0]);
    var temp = [];
    var flag = false;
    $dom.each(function(i) {
      temp[i] = $(this).val();
    });
    $(temp).each(function(i) {
      var x = 0;
      $dom.each(function() {
        if (temp[i] == $(this).val()) {
          x++;
        }
      });
      if (x > 1) flag = true;
    });
    if (flag == true) {
      $('#messageModal .btn-list, #messageModal .btn-refresh, #messageModal .btn-delete').hide();
      $('#messageModal .modal-message').text('중복된 정렬값이 있습니다.');
      $('#messageModal').modal('show');
    } else {
      $.ajax({
        url: $('#formSort').attr('action'),
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        type: 'post',
        beforeSend: function() {
          $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
        },
        success: function() {
          location.href=($('input[name=base_url]').val() + 'admin/setup_front');
        }
      });
    }
  }).on('click', '.btn-bustype-add', function() {
    var $btn = $(this);
    var formData = new FormData($('#formBustype')[0]);
    var error = 0;
    var message = '';

    if ($('input[name=bus_name]').val() == '') {
      error = 1;
      message = '차량명은 꼭 입력해주세요.';
    }
    if ($('select[name=bus_seat]').val() == '') {
      error = 1;
      message = '인원수는 꼭 선택해주세요.';
    }

    if (error == 1) {
      $('.error-message').text(message).slideDown();
      setTimeout(function() { $('.error-message').slideUp().text(''); }, 3000);
    } else {
      $.ajax({
        url: $('#formBustype').attr('action'),
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        type: 'post',
        beforeSend: function() {
          $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
        },
        success: function() {
          location.href=($('input[name=base_url]').val() + 'admin/setup_bustype');
        }
      });
    }
  }).on('click', '.btn-list', function() {
    // 모달 돌아가기 버튼
    location.replace($('input[name=base_url]').val() + 'admin/' + $(this).data('action'));
  }).on('click', '.btn-member-list', function() {
    // 회원 목록 돌아가기
    location.href=($('input[name=base_url]').val() + 'admin/member_list');
  });

})(jQuery); // End of use strict

