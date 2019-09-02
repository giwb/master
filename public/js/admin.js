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
    var $btn = $(this);
    $.ajax({
      url: $('input[name=base_url]').val() + 'admin/' + $('#messageModal input[name=action]').val(),
      data: 'idx=' + $('input[name=delete_idx]').val(),
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
      },
      success: function() {
        $('#messageModal .btn-delete, #messageModal .btn-close').hide();
        $('#messageModal .btn-list').attr('data-action', $('input[name=back_url').val()).show();
        $('#messageModal .modal-message').text('삭제가 완료되었습니다.');
        $('#messageModal').modal('show');
      }
    });
  }).on('click', '.btn-entry', function() {
    // 신규 산행 등록
    var $btn = $(this);
    var btnText = $btn.text();
    var formData = new FormData($('#myForm')[0]);

    if ($('.subject').val() == '') { $.openMsgModal('산행 제목은 꼭 입력해주세요.'); return false; }
    if ($('.startdate').val() == '') { $.openMsgModal('출발일시는 꼭 선택해주세요.'); return false; }
    if ($('.enddate').val() == '') { $.openMsgModal('도착일자는 꼭 선택해주세요.'); return false; }

    $.ajax({
        url: $('#myForm').attr('action'),
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
            $('#messageModal .modal-footer .btn').hide();
            $('#messageModal .modal-footer .btn-close').show();
            $('#messageModal .modal-message').text(result.message);
            $('#messageModal').modal('show');
            $btn.css('opacity', '1').prop('disabled', false).text(btnText);
          } else {
            location.href=($('input[name=base_url]').val() + 'admin/main_list_progress');
          }
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
    // 버스형태 추가
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
  }).on('click', '.btn-add-bus', function() {
    // 신규 산행 등록 차량 추가
    var $dom = $('#area-init-bus').clone();
    $('#area-add-bus').append($dom.removeClass('d-none'));
  }).on('change', '#startDatePicker, #endDatePicker, #startTime', function() {
    // 산행일자 계산
    var startDate = $('#startDatePicker').val();
    var endDate = $('#endDatePicker').val();
    var startTime = $('#startTime').val();

    if (endDate != '' && startDate > endDate) {
      $('#messageModal .modal-footer .btn').hide();
      $('#messageModal .modal-footer .btn-close').show();
      $('#messageModal .modal-message').text('도착일이 출발일보다 빠릅니다.');
      $('#messageModal').modal('show');
      $('#endDatePicker, #calcSchedule').val('');
    } else {
      if (startDate != '' && endDate != '' && startTime != '') {
        $.calcSchedule(startDate, startTime, endDate)
      }
    }
  }).on('change', '.road-cost', function() {
    $.calcRoadCost(); // 통행료 계산
    $.calcTotalBus(); // 운행견적총액
  }).on('change', '.road-distance', function() {
    // 거리/연비/기본비용 계산
    var totalDistance = $.calcTotalDistance(); // 총 거리 계산
    $.calcFuel(); // 연비 계산 (총주행 / 3.5)
    $.calcBusCost(totalDistance); // 버스비용/산행분담 기본비용 계산
    $.calcCost(); // 최종 분담금 계산
    $.calcTotalBus(); // 운행견적총액
  }).on('change', '.cost-gas', function() {
    $.calcTotalFuel(); // 주유비 합계
    $.calcTotalBus(); // 운행견적총액
  }).on('change', '.driving-cost', function() {
    $.calcTotalDriving(); // 운행비 합계
    $.calcTotalBus(); // 운행견적총액
  }).on('change', '.driving-add', function() {
    $.calcAdd(); // 추가비용 합계
    $.calcTotalBus(); // 운행견적총액
  });

  // 여행일자 계산 함수
  $.calcSchedule = function(startDate, startTime, endDate) {
    if (startDate == '' || endDate == '') return false;

    var sDateArr = startDate.split('-');
    var eDateArr = endDate.split('-');
    var sDate = new Date(sDateArr[0], sDateArr[1], sDateArr[2]);
    var eDate = new Date(eDateArr[0], eDateArr[1], eDateArr[2]);
    var resultDay = parseInt((eDate - sDate) / (24 * 60 * 60 * 1000));
    var addDay = (parseInt(resultDay) + 1);
    var startTimeArr = startTime.split(':');
    var sTime = startTimeArr[0] + startTimeArr[1];
    var result = '당일';

    if (resultDay != 0) {
      if (parseInt(sTime) <= '2200') {
        // 22시 이전 출발은 1박 확정
        result = resultDay + '박 ' + addDay + '일';

        $.calcAddSchedule(resultDay, 0); // 일정추가
      } else {
        // 22시 이후 출발은 1일 무박
        result = '1무 ' + (parseInt(resultDay) - 1) + '박 ' + addDay + '일';

        $.calcAddSchedule(resultDay, 1); // 일정추가 (무박)
      }
    }


    // 성수기 계산 (04/01 ~ 06/10, 07/21 ~ 08/20, 09/21 ~ 11/10)
    var sPeak1 = new Date(sDateArr[0], '04', '01')
    var ePeak1 = new Date(sDateArr[0], '06', '10')
    var sPeak2 = new Date(sDateArr[0], '07', '21')
    var ePeak2 = new Date(sDateArr[0], '08', '20')
    var sPeak3 = new Date(sDateArr[0], '09', '21')
    var ePeak3 = new Date(sDateArr[0], '11', '10')
    // 동계예비비 계산 - 12/01 ~ 02/29
    var sPeak4 = new Date(sDateArr[0], '12', '01')
    var ePeak4 = new Date(sDateArr[0], '12', '31')
    var sPeak5 = new Date(sDateArr[0], '01', '01')
    var ePeak5 = new Date(sDateArr[0], '02', '29')

    if ( (sDate >= sPeak1 && eDate <= ePeak1) || (sDate >= sPeak2 && eDate <= ePeak2) || (sDate >= sPeak3 && eDate <= ePeak3) ) {
      $('.cost-peak').val(40000); // 성수기 버스비용 추가
      $('.cost-added').val(2000); // 성수기 분담금 추가
      $('.peak').val('1');
      $('.winter').val('');
      result = result + ' (성수기)';
    } else if ( (sDate >= sPeak4 && eDate <= ePeak4) || (sDate >= sPeak5 && eDate <= ePeak5) ) {
      $('.cost-peak').val(''); // 동계예비비 버스비용 추가는 없음
      $('.cost-added').val(2000); // 동계예비비 분담금 추가
      $('.peak').val('');
      $('.winter').val('1');
      result = result + ' (동계예비비)';
    } else {
      $('.cost-peak').val('');
      if ($('.cost-added').val() >= 2000) {
        $('.cost-added').val(0); // 추가비용 삭제
      }
      $('.peak').val('');
      $('.winter').val('');
    }

    // 최종 분담금 계산
    $('.cost-total').val( Number($('.cost-default').val()) + Number($('.cost-added').val()) );
    $('#calcSchedule').val(result);
  }

  // 총 운행거리 계산 함수
  $.calcTotalDistance = function() {
    var totalDistance = 0;
    $('.road-distance').each(function() {
      totalDistance += Number($(this).val());
    });
    $('.total-distance').val(totalDistance);
    $.calcTotalFuel(); // 주유비 합계

    return totalDistance;
  }

  // 통행료 계산 함수
  $.calcRoadCost = function() {
    var totalCost = 0;
    $('.road-cost').each(function() {
      totalCost += Number($(this).val());
    });
    $('.total-cost').val(totalCost);
    $.calcTotalDriving();
  }

  // 연비 계산
  $.calcFuel = function() {
    $('.driving-fuel').val( (Number( $('.total-distance').val() ) / 3.5).toFixed(2) );
    $.calcTotalFuel(); // 주유비 합계
  }

  // 버스비용/산행분담 기본비용 계산 함수
  $.calcBusCost = function(totalDistance) {
    var cost = 0;
    var cost_bus = 0;
    if      (totalDistance <  200)                          { cost = 22000; cost_bus = 220000; }
    else if (totalDistance >= 200 && totalDistance < 250)   { cost = 23000; cost_bus = 230000; }
    else if (totalDistance >= 250 && totalDistance < 300)   { cost = 24000; cost_bus = 240000; }
    else if (totalDistance >= 300 && totalDistance < 350)   { cost = 25000; cost_bus = 250000; }
    else if (totalDistance >= 350 && totalDistance < 400)   { cost = 26000; cost_bus = 260000; }
    else if (totalDistance >= 400 && totalDistance < 450)   { cost = 27000; cost_bus = 270000; }
    else if (totalDistance >= 450 && totalDistance < 500)   { cost = 28000; cost_bus = 280000; }
    else if (totalDistance >= 500 && totalDistance < 550)   { cost = 29000; cost_bus = 290000; }
    else if (totalDistance >= 550 && totalDistance < 600)   { cost = 30000; cost_bus = 300000; }
    else if (totalDistance >= 600 && totalDistance < 650)   { cost = 31000; cost_bus = 310000; }
    else if (totalDistance >= 650 && totalDistance < 700)   { cost = 32000; cost_bus = 320000; }
    else if (totalDistance >= 700 && totalDistance < 750)   { cost = 33000; cost_bus = 330000; }
    else if (totalDistance >= 750 && totalDistance < 800)   { cost = 34000; cost_bus = 340000; }
    else if (totalDistance >= 800 && totalDistance < 850)   { cost = 35000; cost_bus = 350000; }
    else if (totalDistance >= 850 && totalDistance < 900)   { cost = 36000; cost_bus = 360000; }
    else if (totalDistance >= 900 && totalDistance < 950)   { cost = 37000; cost_bus = 370000; }
    else if (totalDistance >= 950 && totalDistance < 1000)  { cost = 38000; cost_bus = 380000; }
    else if (totalDistance > 1000)                          { cost = 39000; cost_bus = 390000; }
    $('.driving-default').val(cost_bus);
    $('.cost-default').val(cost);
  }

  // 주유비 합계
  $.calcTotalFuel = function() {
    var gas = $('.cost-gas').val();
    var fuel = $('.driving-fuel').val();
    $('.total-driving-fuel').val( Number(gas) + Number(fuel) );
  }

  // 운행비 합계
  $.calcTotalDriving = function() {
    var total = 0;
    $('.driving-cost').each(function() {
      total += Number( $(this).val() );
    });
    $('.total-driving-cost').val(total);
  }

  // 일정추가
  $.calcAddSchedule = function(day, nosleep) {
    // 일정추가 (무박은 15만원, 1박은 25만원)
    $('.cost-add-schedule').val( (nosleep * 150000) + (Number(day) * 250000) );
    $.calcAdd();
  }

  // 추가비용 합계
  $.calcAdd = function() {
    var total = 0;
    $('.driving-add').each(function() {
      total += Number( $(this).val() );
    });
    $('.total-driving-add').val(total);
  }

  // 운행견적총액
  $.calcTotalBus = function() {
    var def = $('.driving-default').val();
    var fuel = $('.total-driving-fuel').val();
    var cost = $('.total-driving-cost').val();
    var add = $('.total-driving-add').val();
    $('.total-bus-cost').val( Number(def) + Number(fuel) + Number(cost) + Number(add) );
  }

  // 최종 분담금 계산
  $.calcCost = function() {
    $('.cost-total').val( Number($('.cost-default').val()) + Number($('.cost-added').val()) );
  }

  // 메세지 모달
  $.openMsgModal = function(msg) {
    $('#messageModal .modal-footer .btn').hide();
    $('#messageModal .modal-footer .btn-close').show();
    $('#messageModal .modal-message').text(msg);
    $('#messageModal').modal('show');
  }

})(jQuery); // End of use strict
