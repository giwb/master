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
      $('.scroll-to-top').fadeIn('slow');
    } else {
      $('.scroll-to-top').fadeOut('slow');
    }
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
      url: '/file/upload',
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
            $('.btn-entry-photo-delete').removeClass('d-none');
            $('.btn-modify-photo-delete').removeClass('d-none');
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
  }).on('click', '.btn-entry-photo-delete', function() {
    // 회원가입 사진 삭제
    var $btn = $(this);
    var baseUrl = $('input[name=baseUrl]').val();

    $.ajax({
      url: baseUrl + '/login/photo_delete',
      data: 'filename=' + $('input[name=filename]').val(),
      dataType: 'json',
      type: 'post',
      success: function() {
        $('input[name=filename]').val('');
        $('.photo').attr('src', baseUrl + 'public/images/noimage.png');
        $btn.addClass('d-none');
      }
    });
  }).on('click', '.btn-modify-photo-delete', function() {
    // 개인정보수정 사진 삭제
    var $btn = $(this);
    $.ajax({
      url: '/member/photo_delete',
      data: 'userIdx=' + $('input[name=userIdx]').val() + '&filename=' + $('input[name=filename]').val(),
      dataType: 'json',
      type: 'post',
      success: function() {
        $('input[name=filename]').val('');
        $('.photo').attr('src', '/public/images/noimage.png');
        $btn.addClass('d-none');
      }
    });
  }).on('click', '.btn-photo-delete', function() {
    // 팝업창 사진 삭제
    var $btn = $(this);
    var baseUrl = $('input[name=baseUrl]').val();
    var photoName = $('#photoModal input[name=photo_name]').val();
    $.ajax({
      url: baseUrl + '/club/photo_delete',
      data: 'filename=' + photoName,
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true).text('삭제중.....');
      },
      success: function(result) {
        $btn.css('opacity', '1').prop('disabled', false).text('삭제합니다');
        if (result.error != 1) {
          // 사진 삭제
          $('.btn-photo-modal[data-photo="' + photoName + '"]').remove();
          var files = $('input[name=photos]').val();
          var newFiles = '';
          var file = files.split(',');
          for (var i in file) {
            if (photoName != file[i] && file[i] != '') newFiles += file[i] + ',';
          }
          $('input[name=photos]').val(newFiles);
          $('#photoModal').modal('hide');
        }
      }
    });
  }).on('click', '.btn-photo-modal', function() {
    // 사진 모달
    $('#photoModal .btn-list, #photoModal .btn-refresh').hide();
    $('#photoModal .modal-message').empty().append('<img class="w-100" src="' + $(this).attr('src') + '">');
    $('#photoModal input[name=photo_name]').val($(this).data('photo'))
    $('#photoModal').modal('show');
  }).on('click', '.login-popup', function() {
    // 로그인 모달
    $('#loginModal').modal('show');
  }).on('click', '.btn-login', function(e) {
    // 로그인
    e.preventDefault();
    var $dom = $(this);
    var formData = new FormData($('.loginForm')[0]);
    var redirectUrl = $('input[name=redirectUrl]').val();

    if ($('input[name=userid]').val() == '' || $('input[name=password]').val() == '') {
      $('.error-message').slideDown().text('아이디와 비밀번호는 꼭 입력해주세요.');
      return false;
    }
    if (typeof redirectUrl == 'undefined') redirectUrl = '';

    $.ajax({
      url: '/login/?r=' + redirectUrl,
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
  }).on('click', '.input-login', function() {
    // 에러 메세지 없애기
    $('.error-message').slideUp();
  }).on('click', '.logout', function() {
    // 로그아웃
    $.ajax({
      url: '/login/logout',
      dataType: 'json',
      success: function() {
        location.replace($('input[name=baseUrl]').val());
      }
    });
  }).on('blur', '.check-userid', function() {
    // 아이디 중복 체크
    var $dom = $(this);
    var userid = $('input[name=userid]').val();
    var clubIdx = $('input[name=clubIdx]').val();
    var pattern = /^[a-z0-9]{3,20}$/;

    if (userid != '') {
      if (userid.length < 3 || userid.length > 20 || !pattern.test(userid) || userid.search(/\s/) != -1) {
        $.openMsgModal('아이디는 띄어쓰기 없이<br>3자 ~ 20자 이하의 영어 소문자만 가능합니다.');
        $('input[name=userid]').val('');
        return false;
      }
      $.ajax({
        url: '/login/check_userid',
        data: 'userid=' + userid,
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
    $.checkNickname();
  }).on('blur', '.check-password', function() {
    // 비밀번호 확인
    var password = $('input[name=password]').val();
    var $dom = $('.check-password-message');
    $('img', $dom).remove();

    if (password != '') {
      if (password.length < 6 || password.length > 20 || password.search(/\s/) != -1) {
        $.openMsgModal('비밀번호는 띄어쓰기 없이<br>6자 ~ 20자 이하만 가능합니다.');
        $('input[name=password]').val('');
        $('input[name=password_check]').val('');
        return false;
      }
      if (password == $('input[name=password_check]').val()) {
        $dom.append('<img class="check-password-complete" src="/public/images/icon_check.png">')
      } else {
        $dom.append('<img src="/public/images/icon_cross.png">')
      }
    }
  }).on('blur', '.check-phone', function() {
    var $dom = $('.check-phone');
    var clubIdx = $('input[name=clubIdx]').val();
    var phone = $('input[name=phone1]').val() + '-' + $('input[name=phone2]').val() + '-' + $('input[name=phone3]').val();
    $.ajax({
      url: $('input[name=baseUrl]').val() + '/login/check_phone',
      data: 'phone=' + phone,
      dataType: 'json',
      type: 'post',
      success: function(result) {
        if (result.error == 1) {
          $.openMsgModal(result.message);
          $dom.append('<img class="check-phone-error" src="/public/images/icon_cross.png">')
        } else {
          $('img', $dom).remove();
        }
      }
    });
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
    if ($('.check-phone img').hasClass('check-phone-error') == true) {
      $.openMsgModal('전화번호를 확인해주세요.');
      return false;
    }
    if ($('input[name=phone1]').val() == '' || $('input[name=phone2]').val() == '' || $('input[name=phone3]').val() == '') {
      $.openMsgModal('전화번호는 꼭 입력해주세요.');
      return false;
    }
    if ($('select[name=location]').val() == '0') {
      $.openMsgModal('승차위치는 꼭 선택해주세요.');
      return false;
    }

    var $btn = $(this);
    var formData = new FormData($('#entryForm')[0]);

    $.ajax({
      url: '/login/insert',
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
  }).on('click', '.btn-member-update', function() {
    // 개인정보수정
    if ($('.check-nickname img').hasClass('check-nickname-complete') == false) {
      $.openMsgModal('닉네임을 확인해주세요.');
      return false;
    }
    if ($('input[name=password]').val() != '' && $('.check-password img').hasClass('check-password-complete') == false) {
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
    var $dom = $('#entryForm');
    var formData = new FormData($dom[0]);

    $.ajax({
      url: $dom.attr('action'),
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
      },
      success: function(result) {
        $btn.css('opacity', '1').prop('disabled', false).text('수정합니다');
        $.openMsgModal(result.message)
      }
    });
  }).on('click', '.btn-quit', function() {
    // 회원 탈퇴
    $.ajax({
      url: '/member/quit',
      data: 'userIdx=' + $('input[name=userIdx]').val(),
      dataType: 'json',
      type: 'post',
      success: function(result) {
        $('#quitModal .btn').hide();
        $('#quitModal .btn-top').removeClass('d-none').show();
        $('#quitModal .modal-message').html('회원 탈퇴가 완료되었습니다.<br>이용해 주셔서 감사합니다.')
      }
    });
  }).on('click', '.btn-upload', function() {
    $(this).prev().click();
  }).on('click', '.search-btn', function() {
    //$('#nav-search').toggleClass('active');
  }).on('click', '.search-close', function() {
    $('#nav-search').removeClass('active');
  }).on('click', '.nav-aside-close', function() {
    $('#nav-aside').removeClass('active');
    $('#nav').removeClass('shadow-active');
  }).on('click', '.btn-list', function() {
    // 모달 돌아가기 버튼
    location.replace($('input[name=baseUrl]').val() + $(this).data('action'));
  }).on('click', '.scroll-to-top', function() {
    // 상단 스크롤
    $('html, body').animate({scrollTop : 0}, 1000, 'easeInOutExpo');
  }).on('click', '.photo-zoom', function() {
    // 사진 확대
    var $dom = $(this);
    var filename = $dom.data('filename');
    var width = $dom.data('width');
    var height = $dom.data('height');
    var pswpElement = document.querySelectorAll('.pswp')[0];
    var items = [{ src: filename, w: width, h: height }];
    var options = {
      index: 0,
      bgOpacity: 0.8,
      showHideOpacity: true,
      getThumbBoundsFn: function(index) {
        var thumbnail = $dom[0],
        pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
        rect = thumbnail.getBoundingClientRect(); 
        return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
      }
    };
    var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
    gallery.init();
  }).on('click', '.btn-page-next', function() {
    // 페이징
    var $btn = $(this);
    var $dom = $('#formList');
    var formData = new FormData($dom[0]);
    $.ajax({
      url: $dom.attr('action'),
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true).text('불러오는 중.....');
      },
      success: function(result) {
        if (result.html == '') {
          $btn.css('opacity', '1').prop('disabled', true).text('마지막 페이지 입니다.');
        } else {
          $btn.css('opacity', '1').prop('disabled', false).text('다음 페이지 보기 ▼');
          $('input[name=p]').val(result.page);
          if (result != '') $('.area-append').append(result.html);
        }
      }
    });
  }).on('click', '.nav-menu .img-profile', function() {
    // 로그인 아이콘
    var $dom = $('.profile-box');
    if ($dom.css('display') == 'none') {
      $dom.slideDown();
    } else {
      $dom.slideUp();
    }
  }).on('change', '.btn-search-month', function() {
    // 검색
    var syear = $('select[name=syear]').val();
    var smonth = $('select[name=smonth]').val();
    var lastDay = ( new Date( syear, smonth, 0) ).getDate();
    location.href = ($('#formSearch').attr('action') + '?sdate=' + syear + '-' + smonth + '-01' + '&edate=' + syear + '-' + smonth + '-' + lastDay);
  }).on('click', '.btn-reserve-wait-add', function() {
    // 대기자 등록 (추가 버튼)
    var userIdx = $('input[name=userIdx]').val();
    if (userIdx == '') {
      $('#loginModal').modal('show'); // 로그인
      return false;
    }
    $(this).removeClass('btn-primary').addClass('btn-secondary').text('일행 추가');
    $('.btn-reserve-wait').removeClass('d-none');
    var header = '<div class="reserve">';
    var location = '<select name="location[]" class="location">'; $.each(arrLocation, function(i, v) { if (v == '') v = '승차위치'; location += '<option'; if ($('input[name=userLocation]').val() == i) location += ' selected'; location += ' value="' + i + '">' + v + '</option>'; }); location += '</select> ';
    var gender = '<select name="gender[]" class="location"><option'; if ($('input[name=userGender]').val() == 'M') gender += ' selected'; gender += ' value="M">남성</option><option'; if ($('input[name=userGender]').val() == 'F') gender += ' selected'; gender += ' value="F">여성</option></select> ';
    var memo = '<input type="text" name="memo[]" size="20" placeholder="요청사항" value="">';
    var footer = '</div>';
    $('#addedWait').append(header + location + gender + memo + footer);
  }).on('click', '.btn-reserve-wait', function() {
    // 대기자 등록
    var $btn = $(this);
    var formData = new FormData($('#waitForm')[0]);
    $.ajax({
      url: $('#waitForm').attr('action'),
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $('btn-reserve-wait-add').hide();
        $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
      },
      success: function(result) {
        $('#messageModal .btn').hide();
        $('#messageModal .btn-refresh').show();
        $('#messageModal .modal-message').text(result.message);
        $('#messageModal').modal({backdrop: 'static', keyboard: false});
      }
    });
  }).on('click', '.area-bus-table .seat', function() {
    // 산행 예약/수정 버튼
    var userIdx = $('input[name=userIdx]').val();

    if (userIdx == '') {
      // 로그인
      $('input[name=redirectUrl]').val($(location).attr('href'));
      $('#loginModal').modal('show');
      return false;
    }

    var resIdx = $(this).data('id');
    var bus = $(this).data('bus');
    var seat = $(this).data('seat');
    var chk = false;

    // 예약/수정 중에는 대기자 예약을 숨긴다
    $('.area-wait').hide();

    // 좌석 토글
    if ($(this).hasClass('active')) {
      // 비활성화
      $('.seat[data-bus=' + bus + '][data-seat=' + seat + ']').removeClass('active');
      $('#addedInfo .reserve[data-seat=' + seat + ']').remove();

      // 예약 내용이 없으면 버튼 삭제
      if ($('#addedInfo .reserve').length == 0) {
        $('.btn-reserve-confirm').text('예약합니다').hide();
        $('.btn-reserve-cancel').addClass('d-none'); // 취소버튼 숨기기
      }
    } else {
      // 활성화
      $('.resIdx').each(function(n) {
        if ($(this).val() != '') chk = true;
      });
      if ($(this).hasClass('reserved')) {
        if (typeof $('.resIdx').css('display') != 'undefined' && chk == false) {
          $.openMsgModal('예약중에는 수정을 할 수 없습니다.');
          return false;
        }
        $('.btn-reserve-confirm').text('수정합니다');
      } else {
        if (chk == true) {
          $.openMsgModal('수정중에는 예약을 할 수 없습니다.');
          return false;
        }
      }
      $('.seat[data-bus=' + bus + '][data-seat=' + seat + ']').addClass('active');
      $('html, body').animate( { scrollTop : $('#reserveForm').offset().top - 100 }, 1000 ); // 하단으로 스크롤
      $.viewReserveInfo(resIdx, bus, seat, 0); // 예약 정보
    }
  }).on('change', '.reserve .busSelect', function() {
    // 버스 선택시 해당 버스의 좌석으로 변경
    var $dom = $(this);
    var $domSeat = $dom.parent().find('.busSeat');
    var seat = $domSeat.val();
    var bus = $dom.val();
    var selectSeat = '';
    var selected = '';
    $.ajax({
      url: '/reserve/information_bus',
      data: 'idx=' + $('input[name=noticeIdx]').val(),
      dataType: 'json',
      type: 'post',
      success: function(result) {
        console.log(result);
        $.each(result.seat[bus], function(i, v) { if ((i+1) == seat) selected = ' selected'; else selected = ''; selectSeat += '<option' + selected + ' value="' + (i+1) + '">' + v + '번</option>'; });
        $domSeat.empty().append(selectSeat);
      }
    });
  }).on('click', '.area-bus-table .priority', function() {
    // 2인우선 예약 버튼
    var userIdx = $('input[name=userIdx]').val();

    if (userIdx == '') {
      // 로그인
      $('input[name=redirectUrl]').val($(location).attr('href'));
      $('#loginModal').modal('show');
      return false;
    }

    var resIdx = $(this).data('id');
    var priorityIdx = $(this).data('priority');
    var bus = $(this).data('bus');
    var seat = $(this).data('seat');
    var chk = false;

    // 우선석 정보가 있는지 확인하고, 없으면 종료
    if (typeof priorityIdx == 'undefined') {
      return false;
    }
    var prioritySeat = $('.priority[data-bus=' + bus + '][data-id=' + priorityIdx + ']').attr('data-seat');

    // 예약/수정 중에는 대기자 예약을 숨긴다
    $('.area-wait').hide();

    // 좌석 토글
    if ($(this).hasClass('active')) {
      // 비활성화
      $('.priority[data-bus=' + bus + '][data-seat=' + seat + ']').removeClass('active');
      $('.priority[data-bus=' + bus + '][data-id=' + priorityIdx + ']').removeClass('active');
      $('#addedInfo .reserve[data-seat=' + seat + ']').remove();
      $('#addedInfo .reserve[data-seat=' + prioritySeat + ']').remove();

      // 예약 내용이 없으면 버튼 삭제
      if ($('#addedInfo .reserve').length == 0) {
        $('.btn-reserve-confirm').text('예약합니다').hide();
        $('.btn-reserve-cancel').addClass('d-none'); // 취소버튼 숨기기
      }
    } else {
      // 활성화
      $('.resIdx').each(function(n) {
        if ($(this).val() != '') chk = true;
      });
      if (chk == true || typeof $('.resIdx').css('display') != 'undefined') {
        $.openMsgModal('일반예약과 2인예약을 동시에 할 수 없습니다.');
        return false;
      }
      $('.priority[data-bus=' + bus + '][data-seat=' + seat + ']').addClass('active');
      $('.priority[data-bus=' + bus + '][data-id=' + priorityIdx + ']').addClass('active');
      $('html, body').animate( { scrollTop : $('#reserveForm').offset().top - 100 }, 1000 ); // 하단으로 스크롤
      $.viewReserveInfo(resIdx, bus, seat, priorityIdx);
      $.viewReserveInfo(priorityIdx, bus, prioritySeat, priorityIdx);
    }
  }).on('click', '.btn-reserve-confirm', function() {
    // 좌석 예약
    var $btn = $(this);
    var baseUrl = $('input[name=baseUrl]').val();
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
            location.replace(baseUrl + result.message);
          }
        }
      });
    }
  }).on('click', '.btn-reserve-cancel', function() {
    // 예약 취소 모달
    var $dom;
    var resIdx = new Array();
    var penalty = 0;
    var msg_penalty = '';

    if (typeof $('input[name=noticeIdx]').val() != 'undefined') {
      $dom = $('.resIdx'); // 예약페이지
    } else {
      $dom = $('.check-reserve:checked'); // 마이페이지
    }

    $dom.each(function() {
      resIdx.push( $(this).val() );
      if ($(this).data('penalty')) {
        penalty += Number($(this).data('penalty'));
      }
    });

    if (penalty > 0) {
      msg_penalty = '<br>' + penalty + '점의 페널티가 발생합니다.';
    }

    if (resIdx.length > 0) {
      $('#reserveCancelModal input[name=resIdx]').val(resIdx);
      $('#reserveCancelModal input[name=resType]').val(1); // 결제 형식은 예약
      $('#reserveCancelModal .modal-message').html('정말로 취소하시겠습니까?' + msg_penalty);
      $('#reserveCancelModal').modal({backdrop: 'static', keyboard: false});
    } else {
      $.openMsgModal('취소할 예약 내역을 선택해주세요.');
    }
  }).on('click', '.btn-reserve-cancel-confirm', function() {
    // 취소 처리
    var $btn = $(this);
    var resType = $('#reserveCancelModal input[name=resType]').val();
    var action = '';
    if (resType == 1) {
      action = '/reserve/cancel';
    } else {
      action = '/shop/cancel';
    }
    $.ajax({
      url: action,
      data: 'clubIdx=' + $('input[name=clubIdx]').val() + '&resIdx=' + $('input[name=resIdx]').val(),
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
      },
      success: function(result) {
        if (result.error == 1) {
          $btn.css('opacity', '1').prop('disabled', false).text('승인');
          $('.modal-message').text(result.message);
        } else {
          location.reload();
        }
      }
    });
  }).on('click', '.btn-refresh', function() {
    // 새로고침
    location.reload();
  });

  // 예약 정보
  $.viewReserveInfo = function(resIdx, bus, seat, priorityIdx) {
    $.ajax({
      url: '/reserve/information',
      data: 'clubIdx=' + $('input[name=clubIdx]').val() + '&idx=' + $('input[name=noticeIdx]').val() + '&bus=' + bus + '&seat=' + seat + '&resIdx=' + resIdx,
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $('#addedInfo').append('<img src="/public/images/ajax-loader.gif" class="ajax-loader">');
      },
      success: function(reserveInfo) {
        var header = '<div class="reserve" data-seat="' + seat + '"><input type="hidden" name="resIdx[]" value="' + resIdx + '" class="resIdx" data-penalty="' + reserveInfo.penalty + '">';
        var location = '<select name="location[]" class="location">'; $.each(reserveInfo.location, function(i, v) { if (v.stitle == '') v.stitle = '승차위치'; location += '<option'; if ((reserveInfo.reserve.loc == '' && reserveInfo.userLocation == v.no) || (reserveInfo.reserve.loc != '' && reserveInfo.reserve.loc == v.no)) location += ' selected'; location += ' value="' + v.no + '">' + v.stitle + '</option>'; }); location += '</select> ';
        var memo = '<input type="text" name="memo[]" size="20" placeholder="요청사항" value="' + reserveInfo.reserve.memo + '">';
        //var footer = ' <select><option>할인선택</option><option>-------</option><option>초등생 할인</option><option>중고생 할인</option></select> </div>';
        var footer = '</div>';

        if (resIdx != '') {
          // 수정
          var busType = '';
          if (reserveInfo.busType.length > 1) {
            busType += '<select name="bus[]" class="busSelect">'; $.each(reserveInfo.busType, function(i, v) { busType += '<option'; if ((i+1) == bus) busType += ' selected'; busType += ' value="' + (i+1) + '">' + (i+1) + '호차</option>'; }); busType += '</select> ';
          } else {
            busType += '<input type="hidden" name="bus[]" value="' + bus + '">';
          }
          var selectSeat = '<select name="seat[]" class="busSeat">'; $.each(reserveInfo.seat[bus], function(i, v) { selectSeat += '<option'; if ((i+1) == seat) selectSeat += ' selected'; selectSeat += ' value="' + (i+1) + '">' + v + '번</option>'; }); selectSeat += '</select> ';

          if (reserveInfo.reserve.priority == 0 && reserveInfo.reserve.nickname != '2인우선') {
            $('.btn-reserve-cancel').removeClass('d-none').show();
          }
        } else {
          // 등록
          var busNumber = '';
          if (reserveInfo.busType.length > 1) {
            busNumber = bus + '호차';
          }
          var busType = busNumber + '<input type="hidden" name="bus[]" value="' + bus + '"> ';
          var selectSeat = reserveInfo.nowSeat + '번<input type="hidden" name="seat[]" value="' + seat + '"> ';
        }

        $('.ajax-loader').remove();
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
    $('#messageModal .modal-message').html(msg);
    $('#messageModal').modal('show');
  }

  // 숫자 자릿수 콤마 찍기
  $.setNumberFormat = function(n) {
    return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
  }

  // 닉네임 확인
  $.checkNickname = function() {
    var clubIdx = $('input[name=clubIdx]').val();
    var userid = $('input[name=userid]').val();
    var nickname = $('input[name=nickname]').val();

    if (nickname != '') {
      if (nickname.length < 2 || nickname.length > 10 || nickname.search(/\s/) != -1) {
        $.openMsgModal('닉네임은 띄어쓰기 없이<br>2자 ~ 10자 이하만 가능합니다.');
        $('input[name=nickname]').val('');
        return false;
      }
      $.ajax({
        url: '/login/check_nickname',
        data: 'userid=' + userid + '&nickname=' + nickname,
        dataType: 'json',
        type: 'post',
        success: function(result) {
          $('.check-nickname img').remove();
          $('.check-nickname').append(result.message);
        }
      });
    } else {
      $('.check-nickname img').remove();
    }
  }
})(jQuery);

// 스토리 관련
$(document).on('click', '.btn-reply', function() {
  // 댓글 열기
  var storyIdx = $(this).data('idx');
  var replyType = $(this).data('type');
  var $dom = $('.story-reply[data-idx=' + storyIdx + ']');
  var html = '';

  $('.story-reply-content', $dom).empty();

  if ($dom.css('display') == 'none') {
    $.ajax({
      url: '/story/reply',
      data: 'storyIdx=' + storyIdx + '&replyType=' + replyType,
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
}).on('click', '.btn-share', function() {
  // 공유하기
  var $dom = $('.area-share[data-idx="' + $(this).data('idx') + '"]');
  var userIdx = $('input[name=userIdx]').val();

  if (userIdx == '') {
    $('#loginModal').modal('show'); // 로그인
    return false;
  }

  if ($dom.css('display') == 'none') {
    $dom.show();
  } else {
    $dom.hide();
  }
}).on('click', '.btn-share-sns', function() {
  // 공유창 열기
  var $dom = $(this);
  var url = $dom.data('url');
  var storyIdx = $dom.data('idx');
  var reactionType = $dom.data('reaction-type');
  var shareType = $dom.data('type');
  window.open(url, 'share-window', 'width=626, height=436');
  $.ajax({
    url: '/story/share',
    data: 'clubIdx=' + $('input[name=clubIdx]').val() + '&storyIdx=' + storyIdx + '&reactionType=' + reactionType + '&shareType=' + shareType,
    dataType: 'json',
    type: 'post',
    success: function(result) {
      if (result.error == 1) {
        if (result.message != '') $.openMsgModal(result.message);
      } else {
        var $dom = $('.btn-share[data-idx=' + storyIdx + ']');
        $dom.find('.cnt-share').text(result.count);
        if (result.type == 1) $dom.addClass('text-danger'); else $dom.removeClass('text-danger');
      }
    }
  });
}).on('click', '.btn-share-url', function() {
  // URL 복사 툴팁
  var $dom = $(this);
  var storyIdx = $dom.data('idx');
  var reactionType = $dom.data('reaction-type');
  var shareType = $dom.data('type');
  $dom.tooltip('hide').attr('data-original-title', '복사했습니다!').tooltip('show');
  setTimeout(function() { $dom.tooltip('hide'); }, 2000);
  $.ajax({
    url: '/story/share',
    data: 'clubIdx=' + $('input[name=clubIdx]').val() + '&storyIdx=' + storyIdx + '&reactionType=' + reactionType + '&shareType=' + shareType,
    dataType: 'json',
    type: 'post',
    success: function(result) {
      if (result.error == 1) {
        if (result.message != '') $.openMsgModal(result.message);
      } else {
        var $dom = $('.btn-share[data-idx=' + storyIdx + ']');
        $dom.find('.cnt-share').text(result.count);
        if (result.type == 1) $dom.addClass('text-danger'); else $dom.removeClass('text-danger');
      }
    }
  });
}).on('click', '.club-story-reply', function() {
  // 스토리 댓글 로그인 체크
  var userIdx = $('input[name=userIdx]').val();
  if (userIdx == '') {
    $('#loginModal').modal('show'); // 로그인
    return false;
  }
}).on('click', '.btn-post-reply', function() {
  // 댓글 달기
  var $btn = $(this);
  var userIdx = $('input[name=userIdx]').val();
  var storyIdx = $btn.data('idx');
  var $form = $('.story-reply-input[data-idx=' + storyIdx + ']');
  var formData = new FormData($form[0]);

  if (userIdx == '') {
    $('#loginModal').modal('show'); // 로그인
    return false;
  }

  if ($btn.prev().val() == '') {
    return false;
  }

  $.ajax({
    url: $form.attr('action'),
    data: formData,
    processData: false,
    contentType: false,
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $btn.css('opacity', '0.5').prop('disabled', true).text('처리중..');
    },
    success: function(result) {
      if (result.error == 1) {
        $.openMsgModal('댓글 등록에 실패했습니다. 다시 시도해주세요.');
      } else {
        var replyIdx = $('input[name=replyIdx]').val();
        if (typeof replyIdx == 'undefined' || replyIdx == '') {
          // 댓글 등록
          $('.story-reply[data-idx=' + storyIdx + '] .story-reply-content').append(result.message);
          $('.cnt-reply[data-idx=' + storyIdx + ']').text(result.reply_cnt);
        } else {
          // 댓글 수정
          $('.reply-content[data-idx=' + replyIdx + ']').text($('.club-story-reply', $form).val());
          $('input[name=replyIdx]').val('');
        }
        $('.club-story-reply').val('');
      }
      $btn.css('opacity', '1').prop('disabled', false).text('댓글등록');
    }
  });
}).on('click', '.btn-like', function() {
  // 좋아요
  var $dom = $(this);
  var userIdx = $('input[name=userIdx]').val();

  if (userIdx == '') {
    $('#loginModal').modal('show'); // 로그인
    return false;
  }
  
  $.ajax({
    url: '/story/like',
    data: 'clubIdx=' + $('input[name=clubIdx]').val() + '&storyIdx=' + $(this).data('idx') + '&reactionType=' + $(this).data('type'),
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
}).on('click', '.btn-post-delete-modal', function() {
  // 삭제하기 모달
  $('#messageModal .btn').hide();
  $('#messageModal .btn-delete, #messageModal .btn-close').show();
  $('#messageModal .modal-message').text('정말로 삭제하시겠습니까?');
  $('#messageModal input[name=action]').val($(this).data('action'));
  $('#messageModal input[name=deleteIdx]').val($(this).data('idx'));
  $('#messageModal').modal();
}).on('click', '.btn-delete', function() {
  // 삭제하기
  var $btn = $(this);
  $.ajax({
    url: '/story/' + $('#messageModal input[name=action]').val(),
    data: 'idx=' + $('input[name=deleteIdx]').val(),
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
        if (result.message == 'delete_reply') {
          // 댓글 삭제시에는 해당 댓글만 사라지게
          $btn.css('opacity', '1').prop('disabled', false).text('삭제합니다');
          $('.story-reply-item[data-idx=' + $('input[name=deleteIdx]').val() + ']').remove();
          $('.cnt-reply[data-idx=' + result.story_idx + ']').text(result.reply_cnt);
          $('#messageModal input[name=action]').val('');
          $('#messageModal input[name=deleteIdx]').val('');
          $('#messageModal').modal('hide');
        } else if (result.message == 'reload') {
          location.reload();
        } else {
          location.replace($('input[name=baseUrl]').val());
        }
      }
    }
  });
}).on('click', '.btn-post-modal', function() {
  $('#storyModal').modal('show');
}).on('click', '.btn-post', function() {
  // 스토리 작성
  var $dom = $(this);
  var baseUrl = $('input[name=baseUrl]').val();
  var content = $('#club-story-content').val();
  var photo = $('.icon-photo-delete').data('filename');
  var page = $('input[name=page]').val();
  var userIdx = $('input[name=userIdx]').val();
  var idx = $('#your-story-form input[name=idx]').val();

  if (userIdx == '') {
    $('#loginModal').modal('show'); // 로그인
    return false;
  }

  if (content == '') { return false; }
  if (typeof(photo) == 'undefined') { photo = ''; }

  if (typeof(idx) != 'undefined') {
    var data = 'clubIdx=' + $('input[name=clubIdx]').val() + '&page=' + $('input[name=page]').val() + '&idx=' + idx + '&photo=' + photo + '&content=' + encodeURIComponent(content)
  } else {
    var data = 'clubIdx=' + $('input[name=clubIdx]').val() + '&page=' + $('input[name=page]').val() + '&photo=' + photo + '&content=' + encodeURIComponent(content)
  }

  $.ajax({
    url: '/story/insert',
    data: data,
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $dom.css('opacity', '0.5').prop('disabled', true).text('전송중..');
      $('#club-story-content').prop('disabled', true);
    },
    success: function(result) {
      if (result.error == 1) {
        $dom.css('opacity', '1').prop('disabled', false).text('전송');
        $('#club-story-content').prop('disabled', false).val('');
        $('#messageModal .btn').hide();
        $('#messageModal .btn-refresh, #messageModal .btn-close').show();
        $('#messageModal .modal-message').text(result.message);
        $('#messageModal').modal();
      } else {
        if (typeof(idx) != 'undefined') {
          location.replace(baseUrl + '/story/view/?n=' + idx);
        } else {
          location.replace(baseUrl);
        }
      }
    }
  });
}).on('click', '.btn-reply-update', function() {
  // 댓글 수정
  var replyIdx = $(this).data('idx');
  var $dom = $('.reply-content[data-idx=' + replyIdx + ']');
  var content = $dom.text();
  $('input[name=replyIdx]').val(replyIdx);
  $('.club-story-reply').val(content);
  $('.btn-post-reply').text('댓글수정');
}).on('click', '#club-story-content', function() {
  // 스토리 작성 텍스트 박스 클릭
  if ($('input[name=userIdx]').val() == '') {
    $('#loginModal').modal('show'); // 로그인
    return false;
  }
}).on('click', '.btn-photo', function() {
  // 사진 선택
  if ($('input[name=userIdx]').val() == '') {
    $('#loginModal').modal('show'); // 로그인
    return false;
  } else {
    $(this).prev().click();
  }
}).on('click', '.icon-photo-delete', function() {
  // 사진 삭제
  var page = $('input[name=page]').val();

  $.ajax({
    url: $('input[name=baseUrl]').val() + '/story/delete_photo',
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
}).on('click', '.story-photo', function() {
  // 스토리 사진 확대
  var $dom = $(this);
  var filename = $dom.data('filename');
  var width = $dom.data('width');
  var height = $dom.data('height');
  var pswpElement = document.querySelectorAll('.pswp')[0];
  var items = [
    {
      src: filename,
      w: width,
      h: height
    }
  ];
  var options = {
    index: 0,
    bgOpacity: 0.8,
    showHideOpacity: true,
    getThumbBoundsFn: function(index) {
      var thumbnail = $dom[0],
      pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
      rect = thumbnail.getBoundingClientRect(); 
      return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
    }
  };
  var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
  gallery.init();
}).on('click', '.btn-story-more', function() {
  // 더보기
  var $btn = $(this);
  var storyIdx = $('input[name=n]').val();
  var paging = $('input[name=p]').val();

  if (typeof storyIdx != 'undefined' && typeof paging != 'undefined') {
    $('input[name=p]').val(Number(paging) + 1);
    if (storyIdx != '') {
      var data = 'n=' + storyIdx;
    } else {
      var data = 'p=' + $('input[name=p]').val();
    }
    $.ajax({
      url: $('input[name=baseUrl]').val() + '/story/index',
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
          $btn.css('opacity', '1').prop('disabled', false).text('더 보기 ▼');
          if (result != '') $('.club-story-article').append(result);
        }
      }
    });
  }
});
