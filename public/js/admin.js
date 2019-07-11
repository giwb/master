(function($) {

  "use strict";

  [].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {
    new SelectFx(el);
  } );

  jQuery('.selectpicker').selectpicker;

  $('#menuToggle').on('click', function(event) {
    $('body').toggleClass('open');
  });

  $('.search-trigger').on('click', function(event) {
    event.preventDefault();
    event.stopPropagation();
    $('.search-trigger').parent('.header-left').addClass('open');
  });

  $('.search-close').on('click', function(event) {
    event.preventDefault();
    event.stopPropagation();
    $('.search-trigger').parent('.header-left').removeClass('open');
  });

});

$(document).on('click', '.btn-get-attendance', function() {
  // 출석체크 최신 데이터 갱신
  var $dom = $(this);
  $.ajax({
    url: $('input[name=base_url]').val() + 'admin/get_attendance',
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $dom.css('opacity', '0.5').prop('disabled', true).text('갱신중...');
    },
    success: function(result) {
      $dom.css('opacity', '1').prop('disabled', false).text('최신 데이터 받기');
      $('#messageModal .modal-message').text(result.message);
      $('#messageModal').modal('show');
    }
  });
}).on('click', '.btn-refresh', function() {
  $(this).css('opacity', '0.5').prop('disabled', true).text('갱신중...');
  location.reload();
});
