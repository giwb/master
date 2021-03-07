$(function() {
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
    
    // Toggle the side navigation when window is resized below 480px
    if ($(window).width() < 480 && !$(".sidebar").hasClass("toggled")) {
      $("body").addClass("sidebar-toggled");
      $(".sidebar").addClass("toggled");
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
});

$(function() {
  $('#datePicker').datepicker({
    dateFormat: 'yy-mm-dd',
    prevText: '이전 달',
    nextText: '다음 달',
    monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
    monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
    dayNames: ['일','월','화','수','목','금','토'],
    dayNamesShort: ['일','월','화','수','목','금','토'],
    dayNamesMin: ['일','월','화','수','목','금','토'],
    showMonthAfterYear: true,
    changeMonth: true,
    changeYear: true,
    yearSuffix: '년'
  });
});

$(document).on('click', '.btn-modal-article', function() {
  // 기사 삭제 모달
  var $dom = $('#deleteArticleModal');
  $('input[name=idx]', $dom).val($(this).data('idx'));
  $dom.modal();
}).on('click', '.btn-delete-article', function() {
  // 기사 삭제
  $('#deleteArticle').submit();
}).on('click', '.view-article', function() {
  // 기사 보기
  location.href = ('/desk/article_view/' + $(this).parent().data('idx'));
}).on('click', '.btn-modal-category', function() {
  // 분류 편집 모달 열기
  $('#editCategoryModal').modal();
}).on('click', '.btn-add-category', function() {
  // 분류 항목 추가
  $('.area-category').append('<div class="row p-1"><div class="col-6"><input type="text" name="category_code[]" class="form-control"></div><div class="col-6"><input type="text" name="category_name[]" class="form-control"></div></div>');
}).on('click', '.btn-edit-category', function() {
  // 분류 편집 완료
  var $btn = $(this);
  var formData = new FormData($('#editCategory')[0]);
  $.ajax({
    processData: false,
    contentType: false,
    url: '/desk/category_update',
    data: formData,
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $btn.css('opacity', '0.5').prop('disabled', true);
    },
    success: function(result) {
      $btn.css('opacity', '1').prop('disabled', false);
      if (result.error == 1) {
        $('.error-message').text(result.message);
        setTimeout(function() { $('.error-message').text(''); }, 2000);
      } else {
        $('.article-category').empty().append(result.message);
        $('#editCategoryModal').modal('hide');
        
      }
    }
  });
});