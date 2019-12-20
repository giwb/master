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
          $('.btn-photo-delete').removeClass('d-none');
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
}).on('click', '.btn-photo-delete', function() {
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
  var baseUrl = $('input[name=baseUrl]').val();

  $.ajax({
    url: baseUrl + '/member/photo_delete',
    data: 'userIdx=' + $('input[name=userIdx]').val() + '&filename=' + $('input[name=filename]').val(),
    dataType: 'json',
    type: 'post',
    success: function() {
      $('input[name=filename]').val('');
      $('.photo').attr('src', baseUrl + 'public/images/noimage.png');
      $btn.addClass('d-none');
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
      url: $('input[name=baseUrl]').val() + 'login/check_userid/' + clubIdx,
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
    url: $('input[name=baseUrl]').val() + 'login/check_phone/' + clubIdx,
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
  var baseUrl = $('input[name=baseUrl]').val();
  var clubIdx = $('input[name=clubIdx]').val();
  var userIdx = $('input[name=userIdx]').val();

  $.ajax({
    url: $('input[name=baseUrl]').val() + 'member/quit/' + clubIdx,
    data: 'userIdx=' + userIdx,
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
    $.viewReserveInfo(resIdx, bus, seat); // 예약 정보
  }
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
    setTimeout(function() { $.viewReserveInfo(priorityIdx, bus, prioritySeat, priorityIdx); }, 1000);
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
}).on('click', '.btn-reserve-cancel', function() {
  // 예약 취소 모달
  var $dom;
  var resIdx = new Array();

  if (typeof $('input[name=noticeIdx]').val() != 'undefined') {
    $dom = $('.resIdx'); // 예약페이지
  } else {
    $dom = $('.check-reserve:checked'); // 마이페이지
  }

  $dom.each(function() {
    resIdx.push( $(this).val() );
  });

  if (resIdx.length > 0) {
    $('#reserveCancelModal input[name=resIdx]').val(resIdx);
    $('#reserveCancelModal').modal({backdrop: 'static', keyboard: false});
  } else {
    $.openMsgModal('취소할 예약 내역을 선택해주세요.');
  }
}).on('click', '.btn-reserve-cancel-confirm', function() {
  // 예약좌석 취소 처리
  var $btn = $(this);
  $.ajax({
    url: $('input[name=baseUrl]').val() + 'reserve/cancel/' + $('input[name=clubIdx]').val(),
    data: 'resIdx=' + $('input[name=resIdx]').val(),
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
  location.reload();
});

$(document).ready(function() {
  // 예약 정보
  $.viewReserveInfo = function(resIdx, bus, seat, priorityIdx='') {
    $.ajax({
      url: $('input[name=baseUrl]').val() + 'reserve/information/' + $('input[name=clubIdx]').val(),
      data: 'idx=' + $('input[name=noticeIdx]').val() + '&bus=' + bus + '&seat=' + seat + '&resIdx=' + resIdx,
      dataType: 'json',
      type: 'post',
      beforeSend: function() {
        $('#addedInfo').append('<img src="/public/images/ajax-loader.gif" class="ajax-loader">');
      },
      success: function(reserveInfo) {
        var header = '<div class="reserve" data-seat="' + seat + '"><input type="hidden" name="resIdx[]" value="' + resIdx + '" class="resIdx">';
        var location = '<select name="location[]" class="location">'; $.each(reserveInfo.location, function(i, v) { if (v.stitle == '') v.stitle = '승차위치'; location += '<option'; if ((reserveInfo.reserve.loc == '' && reserveInfo.userLocation == v.no) || (reserveInfo.reserve.loc != '' && reserveInfo.reserve.loc == v.no)) location += ' selected'; location += ' value="' + v.no + '">' + v.stitle + '</option>'; }); location += '</select> ';
        var memo = '<input type="text" name="memo[]" size="20" placeholder="요청사항" value="' + reserveInfo.reserve.memo + '">';
        var footer = '</div>';

        if (resIdx != '') {
          // 수정
          var busType = '';
          if (reserveInfo.busType.length > 1) {
            busType += '<select name="bus[]">'; $.each(reserveInfo.busType, function(i, v) { busType += '<option'; if ((i+1) == bus) busType += ' selected'; busType += ' value="' + (i+1) + '">' + (i+1) + '호차</option>'; }); busType += '</select> ';
          } else {
            busType += '<input type="hidden" name="bus[]" value="' + bus + '">';
          }
          var selectSeat = '<select name="seat[]">'; $.each(reserveInfo.seat, function(i, v) { selectSeat += '<option'; if ((i+1) == seat) selectSeat += ' selected'; selectSeat += ' value="' + (i+1) + '">' + v + '번</option>'; }); selectSeat += '</select> ';

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
        url: $('input[name=baseUrl]').val() + 'login/check_nickname/' + clubIdx,
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
});