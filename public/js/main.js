(function ($) {
  "use strict";

  // Aside Nav
  $(document).click(function(event) {
    if (!$(event.target).closest($('#nav-aside')).length) {
      if ( $('#nav-aside').hasClass('active') ) {
        $('#nav-aside').removeClass('active');
        $('#nav').removeClass('shadow-active');
      } else {
        if ($(event.target).closest('.aside-btn').length) {
          $('#nav-aside').addClass('active');
          $('#nav').addClass('shadow-active');
        }
      }
    }
  });

  // Preloader
  $(window).on('load', function () {
    if ($('#preloader').length) {
      $('#preloader').delay(100).fadeOut('slow', function () {
        $(this).remove();
      });
    }
  });

  // Back to top button
  $(window).scroll(function() {
    if ($(this).scrollTop() > 100) {
      $('.back-to-top').fadeIn('slow');
    } else {
      $('.back-to-top').fadeOut('slow');
    }
  });
  $('.back-to-top').click(function(){
    $('html, body').animate({scrollTop : 0},1500, 'easeInOutExpo');
    return false;
  });

  // Initiate the wowjs animation library
  new WOW().init();

  // Initiate superfish on nav menu
  $('.nav-menu').superfish({
    animation: {
      opacity: 'show'
    },
    speed: 400
  });

  // Mobile Navigation
  if ($('#nav-menu-container').length) {
    var $mobile_nav = $('#nav-menu-container').clone().prop({
      id: 'mobile-nav'
    });
    $mobile_nav.find('> ul').attr({
      'class': '',
      'id': ''
    });
    $('body').append($mobile_nav);
    $('body').prepend('<button type="button" id="mobile-nav-toggle"><i class="fa fa-bars"></i></button>');
    $('body').append('<div id="mobile-body-overly"></div>');
    $('#mobile-nav').find('.menu-has-children').prepend('<i class="fa fa-chevron-down"></i>');

    $(document).on('click', '.menu-has-children i', function(e) {
      $(this).next().toggleClass('menu-item-active');
      $(this).nextAll('ul').eq(0).slideToggle();
      $(this).toggleClass("fa-chevron-up fa-chevron-down");
    });

    $(document).on('click', '#mobile-nav-toggle', function(e) {
      $('body').toggleClass('mobile-nav-active');
      $('#mobile-nav-toggle i').toggleClass('fa-times fa-bars');
      $('#mobile-body-overly').toggle();
    });

    $(document).click(function(e) {
      var container = $("#mobile-nav, #mobile-nav-toggle");
      if (!container.is(e.target) && container.has(e.target).length === 0) {
        if ($('body').hasClass('mobile-nav-active')) {
          $('body').removeClass('mobile-nav-active');
          $('#mobile-nav-toggle i').toggleClass('fa-times fa-bars');
          $('#mobile-body-overly').fadeOut();
        }
      }
    });
  } else if ($("#mobile-nav, #mobile-nav-toggle").length) {
    $("#mobile-nav, #mobile-nav-toggle").hide();
  }

  // Header scroll class
  $(window).scroll(function() {
    if ($(this).scrollTop() > 100) {
      $('#header').addClass('header-scrolled');
    } else {
      $('#header').removeClass('header-scrolled');
    }
  });

  if ($(window).scrollTop() > 100) {
    $('#header').addClass('header-scrolled');
  }

  // Smooth scroll for the menu and links with .scrollto classes
  $('.nav-menu a, #mobile-nav a, .scrollto').on('click', function() {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
      var target = $(this.hash);
      if (target.length) {
        var top_space = 0;

        if ($('#header').length) {
          top_space = $('#header').outerHeight();

          if (! $('#header').hasClass('header-scrolled')) {
            top_space = top_space - 20;
          }
        }

        $('html, body').animate({
          scrollTop: target.offset().top - top_space
        }, 1500, 'easeInOutExpo');

        if ($(this).parents('.nav-menu').length) {
          $('.nav-menu .menu-active').removeClass('menu-active');
          $(this).closest('li').addClass('menu-active');
        }

        if ($('body').hasClass('mobile-nav-active')) {
          $('body').removeClass('mobile-nav-active');
          $('#mobile-nav-toggle i').toggleClass('fa-times fa-bars');
          $('#mobile-body-overly').fadeOut();
        }
        return false;
      }
    }
  });

  // Navigation active state on scroll
  var nav_sections = $('section');
  var main_nav = $('.nav-menu, #mobile-nav');
  var main_nav_height = $('#header').outerHeight();

  $(window).on('scroll', function () {
    var cur_pos = $(this).scrollTop();
  
    nav_sections.each(function() {
      var top = $(this).offset().top - main_nav_height,
          bottom = top + $(this).outerHeight();
  
      if (cur_pos >= top && cur_pos <= bottom) {
        main_nav.find('li').removeClass('menu-active menu-item-active');
        main_nav.find('a[href="#'+$(this).attr('id')+'"]').parent('li').addClass('menu-active menu-item-active');
      }
    });
  });

  // Intro carousel
  var introCarousel = $(".carousel");
  var introCarouselIndicators = $(".carousel-indicators");
  introCarousel.find(".carousel-inner").children(".carousel-item").each(function(index) {
    (index === 0) ?
    introCarouselIndicators.append("<li data-target='#introCarousel' data-slide-to='" + index + "' class='active'></li>") :
    introCarouselIndicators.append("<li data-target='#introCarousel' data-slide-to='" + index + "'></li>");

    $(this).css("background-image", "url('" + $(this).children('.carousel-background').children('img').attr('src') +"')");
    $(this).children('.carousel-background').remove();
  });

  $(".carousel").swipe({
    swipe: function(event, direction, distance, duration, fingerCount, fingerData) {
      if (direction == 'left') $(this).carousel('next');
      if (direction == 'right') $(this).carousel('prev');
    },
    allowPageScroll:"vertical"
  });

  // Skills section
  $('#skills').waypoint(function() {
    $('.progress .progress-bar').each(function() {
      $(this).css("width", $(this).attr("aria-valuenow") + '%');
    });
  }, { offset: '80%'} );

  // jQuery counterUp (used in Facts section)
  $('[data-toggle="counter-up"]').counterUp({
    delay: 10,
    time: 1000
  });

  // Porfolio isotope and filter
  var portfolioIsotope = $('.portfolio-container').isotope({
    itemSelector: '.portfolio-item',
    layoutMode: 'fitRows'
  });

  $('#portfolio-flters li').on( 'click', function() {
    $("#portfolio-flters li").removeClass('filter-active');
    $(this).addClass('filter-active');

    portfolioIsotope.isotope({ filter: $(this).data('filter') });
  });

  // Clients carousel (uses the Owl Carousel library)
  $(".clients-carousel").owlCarousel({
    autoplay: false,
    dots: true,
    loop: true,
    responsive: { 0: { items: 2 }, 768: { items: 4 }, 900: { items: 6 }
    }
  });

  // Testimonials carousel (uses the Owl Carousel library)
  $(".testimonials-carousel").owlCarousel({
    autoplay: false,
    dots: true,
    loop: true,
    items: 1
  });

  $(document).on('change', '.file', function() {
    // 파일 업로드
    var $dom = $(this);
    var baseUrl = $('input[name=baseUrl]').val();
    var page = $('input[name=page]').val();
    var fileType = $dom.data('type');
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
    formData.append('type', fileType);
    formData.append('file_obj', $dom[0].files[0]);

    $.ajax({
      url: baseUrl + page + '/upload',
      processData: false,
      contentType: false,
      data: formData,
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $('.btn-upload').css('opacity', '0.5').prop('disabled', true).text('업로드중.....');
        $dom.val('');
      },
      success: function(result) {
        $('.btn-upload').css('opacity', '1').prop('disabled', false).text('사진올리기');
        if (result.error == 1) {
          $('.btn-list, .btn-refresh, .btn-delete').hide();
          $('#messageModal .modal-message').text(result.message);
          $('#messageModal').modal('show');
        } else {
          if (page == 'story') {
            // 클럽 스토리
            $('.area-photo').append('<img src="' + result.message + '"><div class="icon-photo-delete" data-filename="' + result.filename + '"></div>');
            $('.btn-photo').hide();
          } else if (page == 'member') {
            // 회원가입
            $('.photo').attr('src', result.message);
            $('input[name=filename]').val(result.filename);
          } else if (page == 'club') {
            // 클럽
            $('.added-files').html('<img src="' + result.message + '" class="btn-photo-modal" data-photo="' + result.filename + '">');
            $('input[name=file]').val(result.filename);
          } else {
            // 그 외
            var $domFiles = $('input[name=file_' + fileType + ']');
            $('.added-files.type' + fileType).append('<img src="' + result.message + '" class="btn-photo-modal" data-photo="' + result.filename + '">');
            if ($domFiles.val() == '') {
              $domFiles.val(result.filename);
            } else {
              $domFiles.val($domFiles.val() + ',' + result.filename);
            }
          }
        }
      }
    });
  }).on('click', '.login-popup', function() {
    // 로그인 모달
    $('#loginModal').modal('show');
  }).on('click', '.btn-login', function(e) {
    // 로그인
    e.preventDefault();
    var $dom = $(this);
    var formData = new FormData($('.loginForm')[0]);
    var baseUrl = $('input[name=baseUrl]').val();
    var clubIdx = $('input[name=clubIdx]').val();
    var redirectUrl = $('input[name=redirectUrl]').val();

    $.ajax({
      url: baseUrl + 'login/' + clubIdx + '?r=' + redirectUrl,
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
          location.replace(result.url)
        }
      }
    });
  }).on('click', '.input-login', function() {
    // 에러 메세지 없애기
    $('.error-message').slideUp();
  }).on('click', '.logout', function() {
    // 로그아웃
    $.ajax({
      url: $('input[name=baseUrl]').val() + 'logout',
      dataType: 'json',
      success: function() {
        location.reload();
      }
    });
  }).on('blur', '.check-userid', function() {
    // 아이디 중복 체크
    var $dom = $(this);
    var userId = $('input[name=userid]').val();
    var clubIdx = $('input[name=clubIdx]').val();

    if (userId != '') {
      $.ajax({
        url: $('input[name=baseUrl]').val() + 'login/check_userid/' + clubIdx,
        data: 'userid=' + userId,
        dataType: 'json',
        type: 'post',
        success: function(result) {
          $('img', $dom).remove();
          $dom.append(result.message);
        }
      });
    } else {
      $('img', $dom).remove();
    }
  }).on('blur', '.check-nickname', function() {
    // 닉네임 중복 체크
    var $dom = $(this);
    var nickname = $('input[name=nickname]').val();
    var clubIdx = $('input[name=clubIdx]').val();

    if (nickname != '') {
      $.ajax({
        url: $('input[name=baseUrl]').val() + 'login/check_nickname/' + clubIdx,
        data: 'nickname=' + nickname,
        dataType: 'json',
        type: 'post',
        success: function(result) {
          $('img', $dom).remove();
          $dom.append(result.message);
        }
      });
    } else {
      $('img', $dom).remove();
    }
  }).on('blur', '.check-password', function() {
    // 비밀번호 확인
    var $dom = $('.check-password-message');
    $('img', $dom).remove();

    if ($('input[name=password]').val() == $('input[name=password_check]').val()) {
      $dom.append('<img class="check-password-complete" src="/public/images/icon_check.png">')
    } else {
      $dom.append('<img src="/public/images/icon_cross.png">')
    }
  }).on('click', '.btn-entry', function() {
    // 회원가입
    if ($('.check-userid img').hasClass('check-userid-complete') == false) {
      $.openMsgModal('아이디를 확인해주세요.');
      return false;
    }
    if ($('.check-nickname img').hasClass('check-nickname-complete') == false) {
      $.openMsgModal('닉네임을 확인해주세요.');
      return false;
    }
    if ($('.check-password img').hasClass('check-password-complete') == false) {
      $.openMsgModal('비밀번호를 확인해주세요.');
      return false;
    }
    if ($('input[name=realname]').val() == '') {
      $.openMsgModal('실명은 꼭 입력해주세요.');
      return false;
    }
    if ($('input:radio[name=gender]').is(':checked') == false) {
      $.openMsgModal('성별은 꼭 선택해주세요.');
      return false;
    }
    if ($('select[name=birthday_year]').val() == '' || $('select[name=birthday_month]').val() == '' || $('select[name=birthday_day]').val() == '') {
      $.openMsgModal('생년월일은 꼭 선택해주세요.');
      return false;
    }
    if ($('input:radio[name=birthday_type]').is(':checked') == false) {
      $.openMsgModal('양력/음력은 꼭 선택해주세요.');
      return false;
    }
    if ($('input[name=phone1]').val() == '' || $('input[name=phone2]').val() == '' || $('input[name=phone3]').val() == '') {
      $.openMsgModal('전화번호는 꼭 입력해주세요.');
      return false;
    }

    var $btn = $(this);
    var formData = new FormData($('#entryForm')[0]);
    var baseUrl = $('input[name=baseUrl]').val();
    var clubIdx = $('input[name=clubIdx]').val();

    $.ajax({
      url: baseUrl + 'login/insert/' + clubIdx,
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
      },
      success: function(result) {
        if (result.error == 1) {
          $btn.css('opacity', '1').prop('disabled', false).text('등록합니다');
          $.openMsgModal(result.message)
        } else {
          $('#messageModal .btn').hide();
          $('#messageModal .btn-top').show();
          $('#messageModal .modal-message').text('회원가입이 성공적으로 완료되었습니다.');
          $('#messageModal').modal({backdrop: 'static', keyboard: false});
        }
      }
    });
  }).on('click', '.btn-upload', function() {
    $(this).prev().click();
  }).on('click', '.search-btn', function() {
  $('#nav-search').toggleClass('active');
  }).on('click', '.search-close', function() {
    $('#nav-search').removeClass('active');
  }).on('click', '.nav-aside-close', function() {
    $('#nav-aside').removeClass('active');
    $('#nav').removeClass('shadow-active');
  }).on('click', '.nav-menu .img-profile', function() {
    // 로그인 아이콘
    var $dom = $('.profile-box');
    if ($dom.css('display') == 'none') {
      $dom.slideDown();
    } else {
      $dom.slideUp();
    }
  }).on('click', '.area-bus-table .seat', function() {
    // 산행 예약/수정 버튼
    var userIdx = $('input[name=userIdx]').val();

    if (userIdx == '') {
      $('input[name=redirectUrl]').val($(location).attr('href'));
      $('#loginModal').modal('show');
      return false;
    }

    var resIdx = $(this).data('id');
    var bus = $(this).data('bus');
    var seat = $(this).data('seat');

    // 좌석 배경색 토글
    if ($(this).hasClass('active')) {
      // 삭제
      $('.seat[data-bus=' + bus + '][data-seat=' + seat + ']').removeClass('active');
      $('#addedInfo .reserve[data-seat=' + seat + ']').remove();

      // 예약 내용이 없으면 예약 확정 버튼 삭제
      if ($('#addedInfo .reserve').length == 0) $('.btn-reserve-confirm').hide();
    } else {
      // 예약 활성화
      $('.seat[data-bus=' + bus + '][data-seat=' + seat + ']').addClass('active');
      $.viewReserveInfo(resIdx, bus, seat); // 예약 정보
    }
  }).on('click', '.btn-reserve-confirm', function() {
    // 좌석 예약
    var $btn = $(this);
    var formCheck = true;
    var formData = new FormData($('#reserveForm')[0]);
    var cnt = 0;

    // 승차위치 선택 확인
    $('.location').each(function() {
      if ($(this).val() == 0) {
        cnt++;
      }
    });
    if (cnt >= 1) {
      $.openMsgModal('승차위치는 꼭 선택해주세요.');
      return false;
    }

    if (formCheck == true) {
      $.ajax({
        url: $('#reserveForm').attr('action'),
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        type: 'post',
        beforeSend: function() {
          $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
        },
        success: function(result) {
          if (result.error == 1) {
            $('#messageModal .btn').hide();
            $('#messageModal .btn-refresh').show();
            $('#messageModal .modal-message').text(result.message);
            $('#messageModal').modal({backdrop: 'static', keyboard: false});
          } else {
            location.replace(result.message);
          }
        }
      });
    }
  }).on('click', '.btn-club-geton', function() {
    // 승차위치 추가
    var $dom = $('.club-geton-text');
    $('.club-geton-added').append('<div class="club-geton-element"><input readonly type="text" name="club_geton[]" class="width-half" value="' + $dom.val() + '"> <button type="button" class="btn btn-primary btn-club-geton-delete">삭제</button></div>');
    $dom.val('');
  }).on('click', '.btn-club-geton-delete', function() {
    // 승차위치 삭제
    $(this).parent().remove();
  }).on('click', '.btn-club-getoff', function() {
    // 하차위치 추가
    var $dom = $('.club-getoff-text');
    $('.club-getoff-added').append('<div class="club-getoff-element"><input readonly type="text" name="club_getoff[]" class="width-half" value="' + $dom.val() + '"> <button type="button" class="btn btn-primary btn-club-getoff-delete">삭제</button></div>');
    $dom.val('');
  }).on('click', '.btn-club-getoff-delete', function() {
    // 하차위치 삭제
    $(this).parent().remove();
  }).on('click', '.btn-mypage-cancel', function() {
    // 예약좌석 취소 모달
    var resIdx = new Array();
    $('.check-reserve:checked').each(function() {
      resIdx.push( $(this).val() );
    });
    if (resIdx.length > 0) {
      $('#reserveCancelModal').modal({backdrop: 'static', keyboard: false});
    } else {
      $.openMsgModal('취소할 예약 내역을 선택해주세요.');
    }
  }).on('change', '.btn-all-check', function() {
    // 체크박스 제어
    var target = $(this).data('id');

    if ($(this).is(':checked') == true) {
      $('.' + target).prop('checked', true)
    } else {
      $('.' + target).prop('checked', false)
    }
  }).on('click', '.btn-refresh', function() {
    location.reload();
  });

  // 예약 정보
  $.viewReserveInfo = function(resIdx, bus, seat) {
    var cnt = 0;
    var selected = '';
    var clubIdx = $('input[name=clubIdx]').val();

    $.ajax({
      url: $('input[name=baseUrl]').val() + 'reserve/information/' + clubIdx,
      data: 'idx=' + $('input[name=noticeIdx]').val() + '&resIdx=' + resIdx,
      dataType: 'json',
      type: 'post',
      success: function(reserveInfo) {
        var header = '<div class="reserve" data-seat="' + seat + '"><input type="hidden" name="resIdx[]" value="' + resIdx + '">';
        var busType = bus + '호차<input type="hidden" name="bus[]" value="' + bus + '"> ';
        var selectSeat = seat + '번<input type="hidden" name="seat[]" value="' + seat + '"> ';
        var location = '<select name="location[]" class="location">'; $.each(reserveInfo.location, function(i, v) { if (v == '') v = '승차위치'; cnt = i + 1; if (reserveInfo.reserve.loc == i) selected = ' selected'; else selected = ''; location += '<option' + selected + ' value="' + i + '">' + v + '</option>'; }); location += '</select> ';
        var memo = '<input type="text" name="memo[]" size="20" placeholder="요청사항" value="' + reserveInfo.reserve.memo + '">';
        var footer = '</div>';
        $('#addedInfo').append(header + busType + selectSeat + location + memo + footer);

        // 예약 확정 버튼
        if ($('.btn-reserve-confirm').is(':visible') == false) $('.btn-reserve-confirm').show();
      }
    });
  }

  // 메세지 모달
  $.openMsgModal = function(msg) {
    $('#messageModal .modal-footer .btn').hide();
    $('#messageModal .modal-footer .btn-close').show();
    $('#messageModal .modal-message').text(msg);
    $('#messageModal').modal('show');
  }

  // 숫자 자릿수 콤마 찍기
  $.setNumberFormat = function(n) {
    return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
  }

})(jQuery);
