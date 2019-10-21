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
    var baseUrl = $('input[name=base_url]').val();
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
        $('.btn-upload').css('opacity', '0.5').prop('disabled', true).text('업로드중..');
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
    var formData = new FormData($('#loginForm')[0]);
    $.ajax({
      url: $('input[name=base_url]').val() + 'member/login',
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
          location.reload();
        }
      }
    });
  }).on('click', '.input-login', function() {
    // 에러 메세지 없애기
    $('.error-message').slideUp();
  }).on('click', '.logout', function() {
    // 로그아웃
    $.ajax({
      url: $('input[name=base_url]').val() + 'member/logout',
      dataType: 'json',
      success: function() {
        location.reload();
      }
    });
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
  }).on('click', '.btn-post', function() {
    // 클럽 스토리 작성
    var $dom = $(this);
    var content = $('#club-story-content').val();
    var photo = $('.icon-photo-delete').data('filename');
    var page = $('input[name=page]').val();

    if (content == '') { return false; }
    if (typeof(photo) == 'undefined') { photo = ''; }

    $.ajax({
      url: $('input[name=base_url]').val() + page + '/story_insert',
      data: 'club_idx=' + $('input[name=club_idx]').val() + '&page=' + page + '&photo=' + photo + '&content=' + content,
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $('#club-story-content').prop('disabled', true);
        $dom.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
      },
      success: function(result) {
        /*$('#club-story-content').prop('disabled', false).val('');
        $dom.css('opacity', '1').prop('disabled', false).text('등록합니다');*/
        location.reload();
      }
    });
  }).on('click', '.btn-photo', function() {
    // 사진 선택 클릭
    $(this).prev().click();
  }).on('click', '.icon-photo-delete', function() {
    // 클럽 스토리 사진 삭제
    var page = $('input[name=page]').val();

    $.ajax({
      url: $('input[name=base_url]').val() + page + '/delete_photo',
      data: 'photo=' + $(this).data('filename'),
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

})(jQuery);
