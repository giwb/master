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

  $(document).on('click', '.btn-member-update', function() {
    // 회원정보 수정
    var $btn = $(this);
    var formData = new FormData($('#formMember')[0]);
    $.ajax({
      url: $('#formMember').attr('action'),
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
      },
      success: function() {
        $btn.css('opacity', '1').prop('disabled', false).text('수정합니다');
        $('.error-message').text('수정이 완료되었습니다.').slideDown();
        setTimeout(function() {
          $('.error-message').slideUp().text('');
        }, 3000);
      }
    });
  }).on('click', '.scroll-to-top', function() {
    // 상단 스크롤
    $('html, body').animate({scrollTop : 0}, 1000, 'easeInOutExpo');
  }).on('click', '.img-profile', function() {
    // 로그인 아이콘
    var $dom = $('.profile-box');
    if ($dom.css('display') == 'none') {
      $dom.slideDown();
    } else {
      $dom.slideUp();
    }
  }).on('click', '.btn-mypage', function() {
    var $dom = $('.nav-sp-mypage');
    if ($dom.css('display') == 'none') {
      $('.nav-sp-mypage').slideDown();
    } else {
      $('.nav-sp-mypage').slideUp();
    }
  }).on('click', '.btn-member-point-update', function() {
    // 포인트/페널티 추가/감소
    var $btn = $(this);
    $.ajax({
      url: '/admin/member_update_point/' + $('input[name=idx]').val(),
      data: 'action=' + $('input[name=action]').val() + '&type=' + $('input[name=type]').val() + '&point=' + $('input[name=point]').val() + '&penalty=' + $('input[name=penalty]').val() + '&subject=' + $('input[name=subject]').val(),
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
      },
      success: function() {
        location.reload();
      }
    });
  }).on('click', '.btn-member-modal', function() {
    // 회원정보 모달
    var $btn = $(this);
    $.ajax({
      url: $('input[name=baseUrl]').val() + '/admin/member_view_modal',
      data: 'userid=' + $(this).data('userid'),
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true);
      },
      success: function(result) {
        $btn.css('opacity', '1').prop('disabled', false);
        $.openMsgModal(result.message);
      }
    });
  }).on('click', '.btn-point-modal', function() {
    // 회원 페널티 모달
    var type = $(this).data('type');
    var action;
    var msg;
    if (type == 1) { msg = '포인트를 추가'; action = 'member_update_point'; }
    if (type == 2) { msg = '포인트를 감소'; action = 'member_update_point'; }
    if (type == 3) { msg = '페널티를 추가'; action = 'member_update_penalty'; }
    if (type == 4) { msg = '페널티를 감소'; action = 'member_update_penalty'; }
    $('#pointModal .modal-message').text('정말로 ' + msg + '하시겠습니까?');
    $('#pointModal input[name=action]').val(action);
    $('#pointModal input[name=type]').val(type);
    $('#pointModal').modal({backdrop: 'static', keyboard: false});
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
  }).on('click', '.btn-notice-delete', function() {
    // 산행 삭제 모달
    $('#messageModal .modal-message').text('정말로 삭제하시겠습니까?');
    $('#messageModal .btn-refresh, #messageModal .close').hide();
    $('#messageModal .btn-delete').show();
    $('#messageModal input[name=action]').val('main_notice_delete');
    $('#messageModal input[name=delete_idx]').val($(this).data('idx'));
    $('#messageModal').modal({backdrop: 'static', keyboard: false});
  }).on('click', '.btn-reply-delete', function() {
    // 클럽 댓글 삭제
    $('#messageModal .modal-message').text('정말로 삭제하시겠습니까?');
    $('#messageModal .btn-refresh, #messageModal .close').hide();
    $('#messageModal .btn-delete').show();
    $('#messageModal input[name=action]').val('log_reply_delete');
    $('#messageModal input[name=delete_idx]').val($(this).data('idx'));
    $('#messageModal').modal({backdrop: 'static', keyboard: false});
  }).on('click', '.btn-delete', function() {
    // 삭제
    var $btn = $(this);
    $.ajax({
      url: '/admin/' + $('#messageModal input[name=action]').val(),
      data: 'idx=' + $('#messageModal input[name=delete_idx]').val(),
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
      },
      success: function(result) {
        if (result.reload == true) {
          location.reload();
        } else {
          $('#messageModal .btn-delete, #messageModal .btn-close').hide();
          $('#messageModal .btn-list').attr('data-action', $('input[name=back_url').val()).show();
          $('#messageModal .modal-message').text('삭제가 완료되었습니다.');
          $('#messageModal').modal('show');
        }
      }
    });
  }).on('click', '.btn-entry', function() {
    // 신규 산행 등록
    var $btn = $(this);
    var btnText = $btn.text();
    var formData = new FormData($('#myForm')[0]);
    if ($('input[name=subject]').val() == '') {
      $.openMsgModal('산행 제목은 꼭 입력해주세요.');
      return false;
    }
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
        $btn.css('opacity', '1').prop('disabled', false).text(btnText);
        $('#messageModal .btn-delete, #messageModal .btn-refresh').hide();
        $('#messageModal .btn-list').attr('data-action', $('input[name=back_url]').val()).show();
        $('#messageModal .modal-message').text(result.message);
        $('#messageModal').modal('show');
      }
    });
  }).on('click', '.btn-log-check', function() {
    // 활동관리 확인 체크
    var $btn = $(this);
    var idx = $(this).data('idx');
    $.ajax({
      url: '/admin/log_check',
      data: 'idx=' + idx + '&status=' + status,
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true).text('확인중..');
      },
      success: function(result) {
        if (result.error == 1) {
          $btn.css('opacity', '1').prop('disabled', false).text('확인');
          $.openMsgModal(result.message);
        } else {
          $btn.css('opacity', '1').prop('disabled', false);
          if (result.status == 1) {
            $btn.removeClass('btn-default').addClass('btn-secondary').attr('data-status', 0).text('복원');
          } else {
            $btn.removeClass('btn-secondary').addClass('btn-default').attr('data-status', 1).text('확인');
          }
        }
      }
    });
  }).on('click', '.btn-reply-check', function() {
    // 댓글 확인 체크
    var $btn = $(this);
    $.ajax({
      url: '/admin/reply_check',
      data: 'idx=' + $(this).data('idx'),
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true);
      },
      success: function(result) {
        if (result.error == 1) {
          $btn.css('opacity', '1').prop('disabled', false).text('확인');
          $.openMsgModal(result.message);
        } else {
          $btn.css('opacity', '1').prop('disabled', false);
          if (result.message == 'N') {
            $btn.removeClass('btn-default').addClass('btn-secondary').text('복원');
          } else {
            $btn.removeClass('btn-secondary').addClass('btn-default').text('확인');
          }
        }
      }
    });
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
  }).on('keypress', '.form-search', function(e) {
    // 검색폼 엔터 처리
    if (e.keyCode == 13) {
      $('input[name=p]').val('');
      $('#formList').submit();
    }
  }).on('click', '.btn-member-search', function() {
    // 회원 검색
    $('input[name=p]').val('');
    $('#formList').submit();
  }).on('change', '.select-level-type', function() {
    $('input[name=p]').val('');
    $('input[name=keyword]').val('');
    $('#formList').submit();
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
          location.href=($('input[name=baseUrl]').val() +  '/admin/setup_front');
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
          location.href=($('input[name=baseUrl]').val() + '/admin/setup_bustype');
        }
      });
    }
  }).on('change', '.change-status-modal', function() {
    var msg;
    var status = $(this).val();
    if (status == 8) {
      msg = '취소하시면 입금된 회원들에게 포인트를 환불합니다.<br>정말로 취소하시겠습니까?';
    } else if (status == 9) {
      msg = '종료하시면 회원들에게 포인트를 지급합니다.<br>정말로 종료하시겠습니까?';
    } else {
      msg = '정말로 상태를 변경하시겠습니까?';
    }
    $('#statusModal input[name=selectStatus]').val(status);
    $('#statusModal .modal-message').html(msg);
    $('#statusModal').modal({backdrop: 'static', keyboard: false});
  }).on('click', '.btn-change-status', function() {
    // 상태 변경
    var status = $('#statusModal input[name=selectStatus]').val();
    $.ajax({
      url: '/admin/change_status',
      data: 'idx=' + $('input[name=idx]').val() + '&status=' + status,
      dataType: 'json',
      type: 'post',
      success: function(result) {
        if (result.error == 1) {
          $.openMsgModal(result.message);
        } else {
          location.reload();
        }
      }
    });
  }).on('change', '.btn-search-month', function() {
    // 월별 검색
    var syear = $('select[name=syear]').val();
    var smonth = $('select[name=smonth]').val();
    var lastDay = ( new Date( syear, smonth, 0) ).getDate();
    location.href = ($('#formSearch').attr('action') + '?sdate=' + syear + '-' + smonth + '-01' + '&edate=' + syear + '-' + smonth + '-' + lastDay);
  }).on('click', '.btn-change-visible', function() {
    // 숨김/공개
    $.ajax({
      url: '/admin/change_visible',
      data: 'idx=' + $(this).data('idx') + '&visible=' + $(this).data('visible'),
      dataType: 'json',
      type: 'post',
      success: function(result) {
        if (result.error == 1) {
          $.openMsgModal(result.message);
        } else {
          location.reload();
        }
      }
    });
  }).on('click', '.btn-wait-delete-modal', function() {
    // 대기자 삭제 모달
    $('#waitModal input[name=waitIdx]').val($(this).data('idx'));
    $('#waitModal').modal({backdrop: 'static', keyboard: false});
  }).on('click', '.btn-wait-delete', function() {
    // 대기자 삭제
    var $btn = $(this);
    $.ajax({
      url: '/admin/main_wait_delete',
      data: 'idx=' + $('input[name=waitIdx]').val(),
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
      },
      success: function(result) {
        if (result.error == 1) {
          $.openMsgModal(result.message);
        } else {
          location.reload();
        }
      }
    });
  }).on('blur', '.search-userid', function() {
    // 닉네임으로 아이디 검색
    var $dom = $(this);
    var nickname = $(this).val();

    if (nickname == '') {
      return false;
    }

    $.ajax({
      url: '/admin/search_by_nickname',
      data: 'nickname=' + nickname,
      dataType: 'json',
      type: 'post',
      success: function(result) {
        if (result.error != 1) {
          if (typeof $('.search-userid-result').css('display') == 'undefined') {
            $dom.next().val(result.message.userid);
            $dom.parent().parent().find('.gender').val(result.message.gender);
            $dom.parent().parent().find('.location').val(result.message.location);
            $dom.tooltip('hide').attr('data-original-title', '회원입니다!').tooltip('show');
            setTimeout(function() { $dom.attr('data-original-title', '').tooltip('hide'); }, 2000);
          } else {
            $('.search-userid-result').val(result.message.userid);
          }
        } else {
          $dom.next().val('');
        }
      }
    });
  }).on('click', '.btn-wait-insert', function() {
    // 대기자 추가
    var $btn = $(this);
    var nickname = $('input[name=nickname]').val();
    var location = $('select[name=location]').val();
    var data = 'idx=' + $('input[name=idx]').val() + '&userid=' + $('input[name=userid]').val() + '&nickname=' + nickname + '&location=' + location + '&gender=' + $('select[name=gender]').val() + '&memo=' + $('input[name=memo]').val();
    if (nickname == '') return false;

    $.ajax({
      url: '/admin/main_wait_insert',
      data: data,
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true);
      },
      success: function(result) {
        if (result.error == 1) {
          $.openMsgModal(result.message);
        } else {
          window.location.reload(true);
        }
      }
    });
  }).on('click', '.btn-refresh', function() {
    location.reload();
  }).on('click', '.btn-list', function() {
    // 모달 돌아가기 버튼
    location.replace($('input[name=baseUrl]').val() + '/' + $(this).data('action'));
  }).on('click', '.btn-member-list', function() {
    // 회원 목록 돌아가기
    location.href=($('input[name=baseUrl]').val() + '/admin/member_list');
  }).on('click', '.logout', function() {
    // 로그아웃
    var baseUrl = $('input[name=baseUrl]').val();
    $.ajax({
      url: '/login/logout',
      dataType: 'json',
      success: function(result) {
        location.reload();
      }
    });
  }).on('click', '.btn-user-login', function() {
    var $btn = $(this);
    var baseUrl = $('input[name=baseUrl]').val();
    $.ajax({
      url: '/admin/user_login',
      data: 'idx=' + $(this).data('idx'),
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
      },
      success: function(result) {
        $btn.css('opacity', '1').prop('disabled', false).text('이 사용자로 로그인');
        window.open(baseUrl);
      }
    });
  }).on('click', '.btn-get-attendance', function() {
    // 출석체크 최신 데이터 받기
    var $btn = $(this);
    $.ajax({
      url: '/admin/attendance_make',
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
      },
      success: function(result) {
        $btn.css('opacity', '1').prop('disabled', false).text('최신 데이터 받기');
        $('#messageModal .modal-message').text('최신 데이터를 모두 받았습니다.');
        $('#messageModal .btn-delete, #messageModal .close').hide();
        $('#messageModal .btn-refresh').show();
        $('#messageModal').modal({backdrop: 'static', keyboard: false});
      }
    });
  }).on('click', '.btn-auth', function() {
    // 백산백소 인증현황 등록
    var $btn = $(this);
    var $dom = $('#formAuth');
    var formData = new FormData($dom[0]);

    if ($('select[name=rescode] option:selected').val() == '') {
      $.openMsgModal('산행은 꼭 선택해주세요.');
      return false;
    }
    if ($('input[name=title]').val() == '') {
      $.openMsgModal('산행명은 꼭 입력해주세요.');
      return false;
    }

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
        $btn.css('opacity', '1').prop('disabled', false).text('인증 사진을 등록합니다');
        if (result.error != 1) {
          $dom.each(function() {
            this.reset();
          });
        }
        $.openMsgModal(result.message);
      }
    });
  }).on('click', '.btn-add-bus', function() {
    // 신규 산행 등록 차량 추가
    var $dom = $('#area-init-bus').clone();
    $('#area-add-bus').append($dom.removeClass('d-none'));
  }).on('click', '.btn-manager', function() {
    // 운영진우선
    var $dom = $(this).parent().parent().parent().find('.nickname');
    var checked = $(this).is(':checked');
    if (checked == true) {
      $dom.val('운영진우선');
    } else {
      $dom.val('');
    }
  }).on('click', '#addedInfo .priority', function() {
    // 2인우선
    var $dom = $(this).parent().parent().parent().find('.nickname');
    var checked = $(this).is(':checked');
    if (checked == true) {
      $dom.val('2인우선');
    } else {
      $dom.val('');
    }
  }).on('click', '#addedInfo .honor', function() {
    // 1인우등
    var $dom = $(this).parent().parent().parent().find('.nickname');
    var checked = $(this).is(':checked');
    if (checked == true) {
      $dom.val('1인우등');
    } else {
      $dom.val('');
    }
  }).on('click', '.admin-bus-table .seat', function() {
    // 산행 예약/수정 버튼
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
  }).on('change', '.reserve .busSelect', function() {
    // 버스 선택시 해당 버스의 좌석으로 변경
    var $dom = $(this);
    var $domSeat = $dom.parent().find('.busSeat');
    var seat = $domSeat.val();
    var bus = $dom.val();
    var selectSeat = '';
    var selected = '';
    $.ajax({
      url: '/admin/reserve_information_bus',
      data: 'idx=' + $('input[name=idx]').val(),
      dataType: 'json',
      type: 'post',
      success: function(result) {
        $.each(result.seat[bus], function(i, v) { if ((i+1) == seat) selected = ' selected'; else selected = ''; selectSeat += '<option' + selected + ' value="' + (i+1) + '">' + v + '번</option>'; });
        $domSeat.empty().append(selectSeat);
      }
    });
  }).on('click', '.admin-bus-table .priority', function() {
    // 2인우선 예약 버튼
    var resIdx = $(this).data('id');
    var priorityIdx = $(this).data('priority');
    var bus = $(this).data('bus');
    var seat = $(this).data('seat');

    // 우선석 정보가 있는지 확인하고, 없으면 종료
    if (typeof priorityIdx == 'undefined') {
      return false;
    }
    var prioritySeat = $('.priority[data-bus=' + bus + '][data-id=' + priorityIdx + ']').attr('data-seat');

    // 좌석 배경색 토글
    if ($(this).hasClass('active')) {
      // 삭제
      $('.priority[data-bus=' + bus + '][data-seat=' + seat + ']').removeClass('active');
      $('.priority[data-bus=' + bus + '][data-id=' + priorityIdx + ']').removeClass('active');
      $('#addedInfo .reserve[data-seat=' + seat + ']').remove();
      $('#addedInfo .reserve[data-seat=' + prioritySeat + ']').remove();

      // 예약 내용이 없으면 예약 확정 버튼 삭제
      if ($('#addedInfo .reserve').length == 0) $('.btn-reserve-confirm').hide();
    } else {
      // 예약 활성화
      $('.priority[data-bus=' + bus + '][data-seat=' + seat + ']').addClass('active');
      $.viewReserveInfo(resIdx, bus, seat); // 1인석 예약
      if (typeof $('.priority[data-bus=' + bus + '][data-id=' + priorityIdx + ']').css('display') != 'undefined') {
        $('.priority[data-bus=' + bus + '][data-id=' + priorityIdx + ']').addClass('active');
        $.viewReserveInfo(priorityIdx, bus, prioritySeat); // 2인석 예약
      }
    }
  }).on('click', '.admin-bus-table .honor', function() {
    // 1인우등 예약 버튼
    var resIdx = $(this).data('id');
    var honorIdx = $(this).data('honor');
    var bus = $(this).data('bus');
    var seat = $(this).data('seat');

    // 1인우등 정보가 있는지 확인하고, 없으면 종료
    if (typeof honorIdx == 'undefined') {
      return false;
    }
    var honorSeat = $('.honor[data-bus=' + bus + '][data-id=' + honorIdx + ']').attr('data-seat');

    // 좌석 배경색 토글
    if ($(this).hasClass('active')) {
      // 삭제
      $('.honor[data-bus=' + bus + '][data-seat=' + seat + ']').removeClass('active');
      $('.honor[data-bus=' + bus + '][data-id=' + honorIdx + ']').removeClass('active');
      $('#addedInfo .reserve[data-seat=' + seat + ']').remove();
      $('#addedInfo .reserve[data-seat=' + honorSeat + ']').remove();

      // 예약 내용이 없으면 예약 확정 버튼 삭제
      if ($('#addedInfo .reserve').length == 0) $('.btn-reserve-confirm').hide();
    } else {
      // 예약 활성화
      $('.honor[data-bus=' + bus + '][data-seat=' + seat + ']').addClass('active');
      $.viewReserveInfo(resIdx, bus, seat); // 1번째 자리 예약
      if (typeof $('.honor[data-bus=' + bus + '][data-id=' + honorIdx + ']').css('display') != 'undefined') {
        $('.honor[data-bus=' + bus + '][data-id=' + honorIdx + ']').addClass('active');
        $.viewReserveInfo(honorIdx, bus, honorSeat); // 2번째 자리 예약
      }
    }
  }).on('click', '.btn-reserve-confirm', function() {
    // 예약 확정
    var $btn = $(this);
    var formCheck = true;
    var formData = new FormData($('#reserveForm')[0]);

    // 체크박스는 formData에 들어가지 않으니 수동으로 확인
    formData.delete('vip[]');
    formData.delete('manager[]');
    formData.delete('priority[]');
    formData.delete('honor[]');
    var checkbox = $("#reserveForm").find("input[type=checkbox]");
    $.each(checkbox, function(i, v) {
      formData.append($(v).attr('name'), $(this).is(':checked'))
    });

    // 예약시 닉네임은 필수
    $('.nickname').each(function() {
      if ($(this).val() == '') {
        $('#messageModal .modal-footer .btn').hide();
        $('#messageModal .modal-footer .btn-close').show();
        $('#messageModal .modal-message').text('닉네임은 꼭 모두 입력해주세요.');
        $('#messageModal').modal('show');
        formCheck = false;
        return false;
      }
    });

    // 2인우선석이 2개가 선택되었는지 확인
    var cnt = 0;
    $('#addedInfo .priority').each(function() {
      if ($(this).is(':checked') == true) {
        cnt++;
      }
    });
    if (cnt > 0 && cnt != 2) {
      $.openMsgModal('2인우선석은 2개 자리만 지정해주세요.');
      return false;
    }

    // 1인우등석이 2개가 선택되었는지 확인
    var cnt = 0;
    $('#addedInfo .honor').each(function() {
      if ($(this).is(':checked') == true) {
        cnt++;
      }
    });
    if (cnt > 0 && cnt != 2) {
      $.openMsgModal('1인우등석은 2개 자리만 지정해주세요.');
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
            $btn.css('opacity', '1').prop('disabled', false).text('확인');
            $.openMsgModal(result.message);
          } else {
            location.reload();
          }
        }
      });
    }
  }).on('click', '.btn-reserve-deposit', function() {
    // 입금 확인
    $('#messageModal .modal-footer .btn-refresh, #messageModal .modal-footer .btn-list').hide();
    $('#messageModal .modal-footer .btn-delete').text('완료합니다').show();
    $('#messageModal .modal-footer input[name=action]').val('reserve_deposit');
    $('#messageModal .modal-footer input[name=delete_idx]').val($(this).data('idx'));
    if ($(this).data('status') == 1) {
      $('#messageModal .modal-message').text('입금 확인을 취소하시겠습니까?');
    } else {
      $('#messageModal .modal-message').text('입금 확인을 완료하시겠습니까?');
    }
    $('#messageModal').modal('show');
  }).on('click', '.btn-reserve-cancel', function() {
    // 예약 취소 모달
    $('#cancelModal .modal-footer input[name=delete_idx]').val($(this).data('idx'));
    $('#cancelModal').modal('show');
  }).on('click', '.btn-reserve-cancel-complete', function() {
    // 예약 취소
    var $btn = $(this);
    $.ajax({
      url: '/admin/reserve_cancel',
      data: 'idx=' + $('#cancelModal input[name=delete_idx]').val() + '&subject=' + $('#cancelModal input[name=subject]').val(),
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
      },
      success: function() {
        location.reload();
      }
    });
  }).on('click', '.btn-change-seat', function() {
    // 좌석 변경
    var $btn = $(this);
    var formData = new FormData($('#changeSeatForm')[0]);
    $.ajax({
      url: $('#changeSeatForm').attr('action'),
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
      },
      success: function(result) {
        $btn.css('opacity', '1').prop('disabled', false).text('좌석 변경 완료');
        if (result.error == 1) {
          $('#messageModal .modal-footer .btn').hide();
          $('#messageModal .modal-footer .btn-close').show();
          $('#messageModal .modal-message').text(result.message);
          $('#messageModal').modal('show');
        } else {
          location.replace($('input[name=baseUrl]').val() +  '/admin/main_view_progress/' + $('input[name=idx]').val());
        }
      }
    });
  }).on('click', '.btn-bus-hide', function() {
    // 버스 숨기기
    var $btn = $(this);
    $.ajax({
      url: '/admin/setup_bustype_hide',
      data: 'idx=' + $(this).data('idx'),
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $btn.css('opacity', '0.5').prop('disabled', true);
      },
      success: function(result) {
        $btn.css('opacity', '1').prop('disabled', false);
        if (result.error == 1) {
          $.openMsgModal(result.message);
        } else {
          if (result.message == 'Y') var msg = '숨김'; else msg = '보임';
          $btn.text(msg);
        }
      }
    });
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
    $.calcCost(); // 최종 요금 계산
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
  }).on('change', '.cost-added', function() {
    $.calcCost();
  });

  // 여행일자 계산 함수
  $.calcSchedule = function(startDate, startTime, endDate) {
    if (startDate == '' || endDate == '') return false;

    var sDateArr = startDate.split('-');
    var eDateArr = endDate.split('-');
    var sDate = new Date(sDateArr[0], sDateArr[1], sDateArr[2]);
    var eDate = new Date(eDateArr[0], eDateArr[1], eDateArr[2]);
    var lastDay = new Date(sDateArr[0], sDateArr[1], 0).getDate();
    var addedTime = 0;
    if (eDateArr[1] > sDateArr[1]) addedTime = 1;
    var resultDay = parseInt((eDate - sDate) / (24 * 60 * 60 * 1000) - addedTime);
    var addDay = (parseInt(resultDay) + 1);
    var startTimeArr = startTime.split(':');
    var sTime = startTimeArr[0] + startTimeArr[1];
    var result = '당일';

    if (resultDay != 0) {
      if (parseInt(sTime) < '2200') {
        // 22시 이전 출발은 1박 확정
        result = resultDay + '박 ' + addDay + '일';
        $.calcAddSchedule(resultDay, 0); // 일정추가
      } else {
        // 22시 이후 출발은 1일 무박
        resultDay = parseInt(resultDay) - 1;
        result = '1무 ' + resultDay + '박 ' + addDay + '일';
        $.calcAddSchedule(resultDay, 1); // 일정추가 (무박)
      }
    } else {
      // 당일은 15만원
      $('.cost-add-schedule').val(0);
    }

    // 성수기 계산 (04/01 ~ 06/10, 07/21 ~ 08/20, 09/21 ~ 11/10)
    var sPeak1 = new Date(sDateArr[0], '04', '01')
    var ePeak1 = new Date(sDateArr[0], '06', '10')
    var sPeak2 = new Date(sDateArr[0], '07', '21')
    var ePeak2 = new Date(sDateArr[0], '08', '20')
    var sPeak3 = new Date(sDateArr[0], '09', '21')
    var ePeak3 = new Date(sDateArr[0], '11', '10')
    // 동계예비비 계산 - 12/01 ~ 02/29
    //var sPeak4 = new Date(sDateArr[0], '12', '01')
    //var ePeak4 = new Date(sDateArr[0], '12', '31')
    // 동계예비비 계산 - 01/01 ~ 02/29
    var sPeak5 = new Date(sDateArr[0], '01', '01')
    var ePeak5 = new Date(sDateArr[0], '02', '29')

    if ( (sDate >= sPeak1 && eDate <= ePeak1) || (sDate >= sPeak2 && eDate <= ePeak2) || (sDate >= sPeak3 && eDate <= ePeak3) ) {
      $('.cost-peak').val(40000); // 성수기 버스비용 추가
      if ($('.cost-added').val() == '0') {
        $('.cost-added').val(Number($('.cost-added').val()) + 2000); // 성수기 요금 추가
      }
      $('.peak').val('1');
      $('.winter').val('');
      result = result + ' (성수기)';
    //} else if ( (sDate >= sPeak4 && eDate <= ePeak4) || (sDate >= sPeak5 && eDate <= ePeak5) ) {
    } else if ( (sDate >= sPeak5 && eDate <= ePeak5) ) {
      $('.cost-peak').val('0'); // 동계예비비 버스비용 추가는 없음
      if ($('.cost-added').val() == '0') {
        $('.cost-added').val(Number($('.cost-added').val()) + 2000); // 동계예비비 요금 추가
      }
      $('.peak').val('');
      $('.winter').val('1');
      result = result + ' (동계예비비)';
    } else {
      $('.cost-peak').val('0');
      if ( $('.peak').val() == '1' || $('.winter').val() == '1' ) {
        $('.cost-added').val(Number($('.cost-added').val()) - 2000); // 추가비용 삭제
      }
      $('.peak').val('');
      $('.winter').val('');
    }

    // 최종 요금 계산
    if ($('.cost-added').val() == '0') {
      $('.cost-total').val(Number($('.cost-default').val()) + Number($('.cost-added').val()));
    }
    $('#calcSchedule').val(result);
  }

  // 총 운행거리 계산 함수
  $.calcTotalDistance = function() {
    var totalDistance = 0;
    $('.road-distance').each(function() {
      totalDistance += Number($(this).val());
    });
    $('.total-distance').val(totalDistance.toFixed(2));
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
    $('.driving-fuel').val( (Number($('.total-distance').val()) / 3.5).toFixed(2));
    $.calcTotalFuel(); // 주유비 합계
  }

  // 버스비용/산행분담 기본비용 계산 함수
  $.calcBusCost = function(totalDistance) {
    var cost = 0;
    var cost_bus = 0;
    /*
    if      (totalDistance <  200)                          { cost = 23000; cost_bus = 220000; }
    else if (totalDistance >= 200 && totalDistance < 250)   { cost = 24000; cost_bus = 230000; }
    else if (totalDistance >= 250 && totalDistance < 300)   { cost = 25000; cost_bus = 240000; }
    else if (totalDistance >= 300 && totalDistance < 350)   { cost = 26000; cost_bus = 250000; }
    else if (totalDistance >= 350 && totalDistance < 400)   { cost = 27000; cost_bus = 260000; }
    else if (totalDistance >= 400 && totalDistance < 450)   { cost = 28000; cost_bus = 270000; }
    else if (totalDistance >= 450 && totalDistance < 500)   { cost = 29000; cost_bus = 280000; }
    else if (totalDistance >= 500 && totalDistance < 550)   { cost = 30000; cost_bus = 290000; }
    else if (totalDistance >= 550 && totalDistance < 600)   { cost = 31000; cost_bus = 300000; }
    else if (totalDistance >= 600 && totalDistance < 650)   { cost = 32000; cost_bus = 310000; }
    else if (totalDistance >= 650 && totalDistance < 700)   { cost = 33000; cost_bus = 320000; }
    else if (totalDistance >= 700 && totalDistance < 750)   { cost = 34000; cost_bus = 330000; }
    else if (totalDistance >= 750 && totalDistance < 800)   { cost = 35000; cost_bus = 340000; }
    else if (totalDistance >= 800 && totalDistance < 850)   { cost = 36000; cost_bus = 350000; }
    else if (totalDistance >= 850 && totalDistance < 900)   { cost = 37000; cost_bus = 360000; }
    else if (totalDistance >= 900 && totalDistance < 950)   { cost = 38000; cost_bus = 370000; }
    else if (totalDistance >= 950 && totalDistance < 1000)  { cost = 39000; cost_bus = 380000; }
    else if (totalDistance > 1000)                          { cost = 40000; cost_bus = 390000; }
    */
    if      (totalDistance <  200)                          { cost = 24000; cost_bus = 220000; }
    else if (totalDistance >= 200 && totalDistance < 250)   { cost = 25000; cost_bus = 230000; }
    else if (totalDistance >= 250 && totalDistance < 300)   { cost = 26000; cost_bus = 240000; }
    else if (totalDistance >= 300 && totalDistance < 350)   { cost = 27000; cost_bus = 250000; }
    else if (totalDistance >= 350 && totalDistance < 400)   { cost = 28000; cost_bus = 260000; }
    else if (totalDistance >= 400 && totalDistance < 450)   { cost = 29000; cost_bus = 270000; }
    else if (totalDistance >= 450 && totalDistance < 500)   { cost = 30000; cost_bus = 280000; }
    else if (totalDistance >= 500 && totalDistance < 550)   { cost = 31000; cost_bus = 290000; }
    else if (totalDistance >= 550 && totalDistance < 600)   { cost = 32000; cost_bus = 300000; }
    else if (totalDistance >= 600 && totalDistance < 650)   { cost = 33000; cost_bus = 310000; }
    else if (totalDistance >= 650 && totalDistance < 700)   { cost = 34000; cost_bus = 320000; }
    else if (totalDistance >= 700 && totalDistance < 750)   { cost = 35000; cost_bus = 330000; }
    else if (totalDistance >= 750 && totalDistance < 800)   { cost = 36000; cost_bus = 340000; }
    else if (totalDistance >= 800 && totalDistance < 850)   { cost = 37000; cost_bus = 350000; }
    else if (totalDistance >= 850 && totalDistance < 900)   { cost = 38000; cost_bus = 360000; }
    else if (totalDistance >= 900 && totalDistance < 950)   { cost = 39000; cost_bus = 370000; }
    else if (totalDistance >= 950 && totalDistance < 1000)  { cost = 40000; cost_bus = 380000; }
    else if (totalDistance > 1000)                          { cost = 41000; cost_bus = 390000; }
    $('.driving-default').val(cost_bus);
    $('.cost-default').val(cost);
  }

  // 주유비 합계
  $.calcTotalFuel = function() {
    var gas = $('.cost-gas').val();
    var fuel = $('.driving-fuel').val();
    $('.total-driving-fuel').val( parseInt(Number(gas) * Number(fuel)) );
  }

  // 운행비 합계
  $.calcTotalDriving = function() {
    var total = 0;
    $('.driving-cost').each(function() {
      total += Number($(this).val());
    });
    $('.total-driving-cost').val(total);
  }

  // 일정추가
  $.calcAddSchedule = function(day, nosleep) {
    // 일정추가 (무박은 15만원, 1박은 25만원)
    if (nosleep == 1) {
      $('.cost-add-schedule').val(150000 + (Number(day) * 250000));
      $('.cost-default').val(Number($('.cost-default').val()) + 5000 + (Number(day) * 15000)); // 무박은 5000원, 1박당 15000원 추가
    } else {
      $('.cost-add-schedule').val(Number(day) * 250000);
      $('.cost-default').val(Number($('.cost-default').val()) + (Number(day) * 15000)); // 1박당 15000원 추가
    }
    $.calcAdd();
  }

  // 추가비용 합계
  $.calcAdd = function() {
    var total = 0;
    $('.driving-add').each(function() {
      total += Number($(this).val());
    });
    $('.total-driving-add').val(total);
  }

  // 운행견적총액
  $.calcTotalBus = function() {
    var def = $('.driving-default').val();
    var fuel = $('.total-driving-fuel').val();
    var cost = $('.total-driving-cost').val();
    var add = $('.total-driving-add').val();
    $('.total-bus-cost').val(parseInt(Number(def) + Number(fuel) + Number(cost) + Number(add)));
  }

  // 최종 요금 계산
  $.calcCost = function() {
    $('.cost-total').val(Number($('.cost-default').val()) + Number($('.cost-added').val()));
  }

  // 예약 정보
  $.viewReserveInfo = function(resIdx, bus, seat) {
    var cnt = 0;
    var selected = '';
    $.ajax({
      url: '/admin/reserve_information',
      data: 'idx=' + $('input[name=idx]').val() + '&resIdx=' + resIdx,
      dataType: 'json',
      type: 'post',
      success: function(reserveInfo) {
        var header = '<div class="reserve" data-seat="' + seat + '"><input type="hidden" name="resIdx[]" value="' + resIdx + '">';
        var nickname = '<input type="text" name="nickname[]" size="20" placeholder="닉네임" class="nickname search-userid" value="' + reserveInfo.reserve.nickname + '"><input type="hidden" name="userid[]" class="userid" value="' + reserveInfo.reserve.userid + '"> ';
        var gender = '<select name="gender[]" class="gender"><option'; if (reserveInfo.reserve.gender == 'M') gender += ' selected'; gender += ' value="M">남</option><option '; if (reserveInfo.reserve.gender == 'F') gender += ' selected'; gender +=' value="F">여</option></select> ';
        var busType = '<select name="bus[]" class="busSelect">'; $.each(reserveInfo.busType, function(i, v) { if (v.idx == '') v.idx = '버스'; cnt = i + 1; if (cnt == bus) selected = ' selected'; else selected = ''; busType += '<option' + selected + ' value="' + cnt + '">' + cnt + '호차</option>'; }); busType += '</select> ';
        var selectSeat = '<select name="seat[]" class="busSeat">'; $.each(reserveInfo.seat[bus], function(i, v) { if ((i+1) == seat) selected = ' selected'; else selected = ''; selectSeat += '<option' + selected + ' value="' + (i+1) + '">' + v + '번</option>'; }); selectSeat += '</select> ';
        var location = '<select name="location[]" class="location">'; $.each(reserveInfo.location, function(i, v) { if (v.stitle == '') v.stitle = '승차위치'; cnt = i + 1; if (reserveInfo.reserve.loc == v.no) selected = ' selected'; else selected = ''; location += '<option' + selected + ' value="' + v.no + '">' + v.stitle + '</option>'; }); location += '</select> ';
        var depositname = '<input type="text" name="depositname[]" size="20" placeholder="입금자명" value="' + reserveInfo.reserve.depositname + '">';
        var memo = '<div class="mt-1"><input type="text" name="memo[]" size="30" placeholder="메모" value="' + reserveInfo.reserve.memo + '"> ';
        var options = '<label><input'; if (reserveInfo.reserve.vip == 1) options += ' checked'; options += ' type="checkbox" name="vip[]">평생</label> <label><input'; if (reserveInfo.reserve.manager == 1) options += ' checked'; options += ' type="checkbox" name="manager[]" class="btn-manager">운영진</label> <label><input'; if (reserveInfo.reserve.honor == 1) options += ' checked'; options += ' type="checkbox" name="honor[]" class="honor">1인</label> <label><input'; if (reserveInfo.reserve.priority == 1) options += ' checked'; options += ' type="checkbox" name="priority[]" class="priority">2인</label>';
        if (resIdx != '') {
          var button = '<button type="button" class="btn btn-secondary btn-reserve-deposit" data-idx="' + resIdx + '" data-status="' + reserveInfo.reserve.status + '">';
          if (reserveInfo.reserve.status == 1) button += '입금취소'; else button += '입금확인';
          button += '</button> ';
          button += '<button type="button" class="btn btn-secondary btn-reserve-cancel" data-idx="' + resIdx + '">예약취소</button> ';
          if (reserveInfo.reserve.userid != '') {
            button += '<button type="button" class="btn btn-info btn-member-modal" data-userid="' + reserveInfo.reserve.userid + '">회원정보</button> ';
          }
        } else {
          var button = '';
        }
        var footer = '</div></div>';
        $('#addedInfo').append(header + busType + selectSeat + nickname + gender + location + depositname + memo + options + button + footer);

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

})(jQuery); // End of use strict
