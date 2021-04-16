<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main id="club">
    <div class="container-fluid club-main">
      <div class="row mt-1 mb-5">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">

          <div class="row align-items-center mt-2 mb-2">
            <div class="col-1 col-lg-2"></div>
            <div class="col-lg-8">
              <div id="step1">
                <h2 class="mt-2 text-center">회원가입 확인</h2>
                <hr class="bg-danger mb-5">

                <form id="formCheckPhone" method="post">
                  <div class="row no-gutters">
                    <div class="col-4 col-sm-3 pt-2 pl-3">휴대폰 번호</div>
                    <div class="col-8 col-sm-9">
                      <div class="row w-100 no-gutters align-items-center">
                        <div class="col-3 col-sm-2 mr-2 p-0"><input type="text" name="phone1" maxlength="3" class="form-control"></div>
                        <div class="col-4 col-sm-3 mr-2 p-0"><input type="text" name="phone2" maxlength="4" class="form-control"></div>
                        <div class="col-4 col-sm-3 p-0"><input type="text" name="phone3" maxlength="4" class="form-control"></div>
                        <div class="col-sm-3 ml-2 p-0 d-none d-sm-block"><button type="button" class="btn btn-secondary btn-send-auth m-0 pt-2 pb-2 pl-3 pr-3">인증번호 발송</button></div>
                        <div class="col-12 mt-2 p-0 d-block d-sm-none"><button type="button" class="btn btn-secondary btn-send-auth m-0 pt-2 pb-2 pl-5 pr-5">인증번호 발송</button></div>
                      </div>
                    </div>
                  </div>
                  <div class="row no-gutters align-items-center mt-2">
                    <div class="col-4 col-sm-3 pl-3">인증번호 확인</div>
                    <div class="col-8 col-sm-9">
                      <div class="row w-100 no-gutters align-items-center">
                        <div class="col-6 col-sm-4 p-0"><input type="text" name="auth_code" maxlength="6" class="form-control"></div>
                        <div class="col-6 col-sm-8 p-0"><span class="ml-2 auth-time"></span></div>
                      </div>
                    </div>
                  </div>

                  <hr class="bg-danger mt-5">
                  <div class="text-center">
                    <div class="error-message"></div>
                    <button class="btn btn-primary btn-member-check-complete">다음으로 ></button>
                  </div>
                </form>
              </div>
              <div id="step2" style="display: none;">
                <h2 class="mt-2 text-center">비밀번호 변경</h2>
                <hr class="bg-danger mb-5">

                <form id="formChangePassword" method="post">
                  <div class="row no-gutters">
                    <div class="col-5 col-sm-3 pt-2 pl-3">가입된 아이디</div>
                    <div class="col-7 col-sm-9 pr-3">
                      <input readonly type="text" name="userid" class="form-control">
                    </div>
                  </div>
                  <div class="row no-gutters mt-2">
                    <div class="col-5 col-sm-3 pt-2 pl-3">새로운 비밀번호</div>
                    <div class="col-7 col-sm-9 pr-3">
                      <input type="password" name="password" maxlength="30" class="form-control" autocomplete="new-password">
                    </div>
                  </div>
                  <div class="row no-gutters mt-2">
                    <div class="col-5 col-sm-3 pt-2 pl-3">비밀번호 확인</div>
                    <div class="col-7 col-sm-9 pr-3">
                      <input type="password" name="password_check" maxlength="30" class="form-control" autocomplete="new-password">
                    </div>
                  </div>

                  <hr class="bg-danger mt-5">
                  <div class="text-center">
                    <div class="error-message"></div>
                    <input type="hidden" name="phone1">
                    <input type="hidden" name="phone2">
                    <input type="hidden" name="phone3">
                    <input type="hidden" name="auth_code">
                    <button class="btn btn-primary btn-member-change-password">비밀번호 변경 완료</button>
                  </div>
                </form>
              </div>

              <div style="width: 482px; margin: 0 auto;">
                <!-- 애드핏 -->
                <section class="section mt-4 mb-4">
                  <div class="card">
                    <ins class="kakao_ad_area" style="display:none;" data-ad-unit="DAN-nZvV4BAxj0QXpJeQ" data-ad-width="320" data-ad-height="100"></ins>
                    <script type="text/javascript" src="//t1.daumcdn.net/kas/static/ba.min.js" async></script>
                  </div>
                </section>

                <?php if (ENVIRONMENT == 'production'): ?>
                <!-- 구글 광고 -->
                <section class="section">
                  <div class="card text-center">
                    <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-2424708381875991" data-ad-slot="1285643193" data-ad-format="auto" data-full-width-responsive="true"></ins>
                    <script> (adsbygoogle = window.adsbygoogle || []).push({}); </script>
                  </div>
                </section>
                <?php endif; ?>
              </div>

            </div>
            <div class="col-1 col-lg-2"></div>
          </div>

        </div>
        <div class="col-lg-2"></div>
      </div>
    </div>
    <input type="hidden" name="clubIdx" value="<?=!empty($clubIdx) ? $clubIdx : ''?>">
  </main>

  <div class="modal fade" id="forgotModal" tabindex="-1" role="dialog" aria-labelledby="forgotModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="smallmodalLabel">메시지</h5>
              </div>
              <div class="modal-body text-center">
                  <p class="modal-message mt-2">비밀번호 변경이 완료되었습니다.<br>새롭게 로그인 해주세요.</p>
              </div>
              <div class="border-top text-center p-3">
                <a href="<?=BASE_URL?>/login"><button type="button" class="btn btn-info">로그인 페이지로</button></a>
              </div>
          </div>
      </div>
  </div>

  <script>
    $(document).on('click', '.btn-member-check-complete', function() {
      var $btn = $(this);
      var $dom = $('#formCheckPhone');
      var formData = new FormData($dom[0]);
      var phone1 = $('input[name=phone1]', $dom).val();
      var phone2 = $('input[name=phone2]', $dom).val();
      var phone3 = $('input[name=phone3]', $dom).val();
      var auth_code = $('input[name=auth_code]', $dom).val();

      if (phone1 == '' || phone2 == '' || phone3 == '') {
        $('.error-message').text('사용하시는 휴대폰 번호를 입력해주세요.').slideDown();
        setTimeout(function() { $('.error-message').slideUp().text(''); }, 2000);
        return false;
      }
      if (typeof auth_code == 'undefined' || auth_code == '') {
        $('.error-message').text('휴대폰 인증은 필수입니다.').slideDown();
        setTimeout(function() { $('.error-message').slideUp().text(''); }, 2000);
        return false;
      }

      $.ajax({
        url: '/login/forgot_process',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        type: 'post',
        beforeSend: function() {
          $btn.css('opacity', '0.5').prop('disabled', true);
        },
        success: function(result) {
          $btn.css('opacity', '1').prop('disabled', false);

          if (result.error == 1) {
            $('.error-message').text(result.message).slideDown();
            setTimeout(function() { $('.error-message').slideUp().text(''); }, 2000);
          } else {
            $('input[name=userid]').val(result.message.userid);
            $('input[name=phone1]').val(result.message.phone1);
            $('input[name=phone2]').val(result.message.phone2);
            $('input[name=phone3]').val(result.message.phone3);
            $('input[name=auth_code]').val(result.message.auth_code);
            $('#step1').slideUp();
            $('#step2').slideDown();
          }
        }
      });
    }).on('click', '.btn-member-change-password', function() {
      var $btn = $(this);
      var $dom = $('#formChangePassword');
      var formData = new FormData($dom[0]);
      var password = $('input[name=password]', $dom).val();
      var password_check = $('input[name=password_check]', $dom).val();

      if (typeof password == 'undefined' || password == '') {
        $('.error-message').text('새로운 비밀번호를 입력해주세요.').slideDown();
        setTimeout(function() { $('.error-message').slideUp().text(''); }, 2000);
        return false;
      }
      if (password != password_check) {
        $('.error-message').text('입력하신 비밀번호가 일치하지 않습니다.').slideDown();
        setTimeout(function() { $('.error-message').slideUp().text(''); }, 2000);
        return false;
      }

      $.ajax({
        url: '/login/change_pw',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        type: 'post',
        beforeSend: function() {
          $btn.css('opacity', '0.5').prop('disabled', true);
        },
        success: function(result) {
          $btn.css('opacity', '1').prop('disabled', false);

          if (result.error == 1) {
            $('.error-message').text(result.message).slideDown();
            setTimeout(function() { $('.error-message').slideUp().text(''); }, 2000);
          } else {
            $('#forgotModal').modal({backdrop: 'static', keyboard: false});
          }
        }
      });
    }).on('click', '.btn-send-auth', function() {
      var $btn = $(this);
      var $dom = $('#formCheckPhone');
      var phone1 = $('input[name=phone1]', $dom).val();
      var phone2 = $('input[name=phone2]', $dom).val();
      var phone3 = $('input[name=phone3]', $dom).val();

      if (phone1 == '' || phone2 == '' || phone3 == '') {
        $('.error-message').text('사용하시는 휴대폰 번호를 입력해주세요.').slideDown();
        setTimeout(function() { $('.error-message').slideUp().text(''); }, 2000);
        return false;
      }

      $.ajax({
        url: '/login/send_auth',
        data: 'phone1=' + phone1 + '&phone2=' + phone2 + '&phone3=' + phone3 + '&type=forgot',
        dataType: 'json',
        type: 'post',
        beforeSend: function() {
          $btn.css('opacity', '0.5').prop('disabled', true);
        },
        success: function(result) {
          $('.error-message').text(result.message).slideDown();
          setTimeout(function() { $('.error-message').slideUp().text(''); }, 2000);

          if (result.error == 1) {
            $btn.css('opacity', '1').prop('disabled', false);
          } else {
            setTimeout(function() { $btn.css('opacity', '1').prop('disabled', false); }, 20000);
            clearTime();
            setTimer();
          }
        }
      });
    });

    var timer;
    var intervalSecond;
    var clearTime = function() {
      clearTimeout(timer);
      intervalSecond = Number(179); // 3분
    };

    var setTimer = function() {
      $('.auth-time').empty();
      if (intervalSecond > 0) {
        $('.auth-time').append(setMinSec(intervalSecond));
        intervalSecond--;
        timer = setTimeout(setTimer, 1000);
      }
    };

    var setMinSec = function(sec) {
      min = parseInt((sec%3600)/60);
      sec = sec%60;
      return Lpad(min, 2) + ':' + Lpad(sec, 2);
    }

    var Lpad = function(str, len) {
      str = str + '';
      while (str.length < len) {
        str = '0' + str;
      }
      return str;
    }
  </script>
