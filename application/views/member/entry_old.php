<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main id="club">
    <div class="container-fluid club-main">
      <div class="row mt-1 mb-5">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">

          <form id="memberForm" method="post" action="<?=BASE_URL?>/login/entry">
          <input type="hidden" name="club_idx" value="<?=$clubIdx?>">
          <div class="row">
            <div class="col-12"><h2 class="mt-4 mb-4 pb-4 border-bottom text-center"><?=$view['title']?> 이용약관</h2></div>
          </div>
          <div class="border agree-box"><?=!empty($view['agreement']) ? reset_html_escape($view['agreement']) : '&nbsp;'?></div>
          <div class="row">
            <div class="col-12 text-center mt-3 mb-4"><label><input type="checkbox" name="agreement"> 이용약관에 동의합니다.</label></div>
          </div>
          <div class="row">
            <div class="col-12"><h2 class="mt-3 mb-4 pb-4 border-bottom text-center">개인정보 취급방침</h2></div>
          </div>
          <div class="border agree-box"><?=!empty($view['personal']) ? reset_html_escape($view['personal']) : '&nbsp;'?></div>
          <div class="row">
            <div class="col-12 text-center mt-3 mb-4"><label><input type="checkbox" name="personal"> 개인정보 취급방침에 동의합니다.</label></div>
          </div>
          <div class="row">
            <div class="col-12"><h2 class="mt-3 mb-4 pb-4 border-bottom text-center">회원가입 정보</h2></div>
          </div>
          <div class="row">
            <div class="col-1 col-lg-2"></div>
            <div class="col-lg-8 row align-items-center">
              <div class="col-4 col-sm-3">아이디</div>
              <div class="col-8 col-sm-9"><input type="text" name="userid" maxlength="20" class="form-control"></div>
            </div>
            <div class="col-1 col-lg-2"></div>
          </div>
          <div class="row mt-2">
            <div class="col-1 col-lg-2"></div>
            <div class="col-lg-8 row align-items-center">
              <div class="col-4 col-sm-3">비밀번호</div>
              <div class="col-8 col-sm-9"><input type="password" name="password" maxlength="30" class="form-control" autocomplete="new-password"></div>
            </div>
            <div class="col-1 col-lg-2"></div>
          </div>
          <div class="row mt-2">
            <div class="col-1 col-lg-2"></div>
            <div class="col-lg-8 row align-items-center">
              <div class="col-4 col-sm-3">닉네임</div>
              <div class="col-8 col-sm-9"><input type="text" name="nickname" maxlength="10" class="form-control"></div>
            </div>
            <div class="col-1 col-lg-2"></div>
          </div>
          <div class="row mt-2">
            <div class="col-1 col-lg-2"></div>
            <div class="col-lg-8 row align-items-center">
              <div class="col-4 col-sm-3">휴대폰 번호</div>
              <div class="col-8 col-sm-9">
                <div class="row w-100 no-gutters">
                  <div class="col-3 col-sm-2 mr-2 p-0"><input type="text" name="phone1" maxlength="3" class="form-control"></div>
                  <div class="col-4 col-sm-3 mr-2 p-0"><input type="text" name="phone2" maxlength="4" class="form-control"></div>
                  <div class="col-4 col-sm-3 p-0"><input type="text" name="phone3" maxlength="4" class="form-control"></div>
                </div>
              </div>
            </div>
            <div class="col-1 col-lg-2"></div>
          </div>
          <div class="border-top text-center mt-4 pt-2">
            <div class="error-message"></div>
            <button type="button" class="btn btn-default btn-check mt-2">가입 신청</button>
          </div>
          </form>
        </div>
        <div class="col-lg-4"></div>
      </div>
    </div>
    </form>
  </main>

  <script>
    $(document).on('click', '.btn-check', function() {
      var $btn = $(this);
      var $dom = $('#memberForm');
      var formData = new FormData($dom[0]);
      var userid = $('input[name=userid]', $dom).val();
      var password = $('input[name=password]', $dom).val();
      var nickname = $('input[name=nickname]', $dom).val();
      var phone1 = $('input[name=phone1]', $dom).val();
      var phone2 = $('input[name=phone2]', $dom).val();
      var phone3 = $('input[name=phone3]', $dom).val();

      if ($('input:checkbox[name=agreement]').is(':checked') == false) {
        $('.error-message').text('이용약관에 동의해 주십시오.').slideDown();
        setTimeout(function() { $('.error-message').slideUp().text(''); }, 2000);
        return false;
      }

      if ($('input:checkbox[name=personal]').is(':checked') == false) {
        $('.error-message').text('개인정보 이용약관에 동의해 주십시오.').slideDown();
        setTimeout(function() { $('.error-message').slideUp().text(''); }, 2000);
        return false;
      }

      if (userid == '') {
        $('.error-message').text('사용하실 아이디를 입력해주세요.').slideDown();
        setTimeout(function() { $('.error-message').slideUp().text(''); }, 2000);
        return false;
      } else {
        if (userid.length < 4 || userid.length > 10 || userid.search(/\s/) != -1) {
          $('.error-message').text('아이디는 띄어쓰기 없이 4자 ~ 10자 이하만 가능합니다.').slideDown();
          $('#memberForm input[name=userid]').val('');
          setTimeout(function() { $('.error-message').slideUp().text(''); }, 2000);
          return false;
        }
      }

      if (password == '') {
        $('.error-message').text('사용하실 비밀번호를 입력해주세요.').slideDown();
        setTimeout(function() { $('.error-message').slideUp().text(''); }, 2000);
        return false;
      } else {
        if (password.length < 5 || password.length > 20 || password.search(/\s/) != -1) {
          $('.error-message').text('비밀번호는 띄어쓰기 없이 5자 ~ 20자 이하만 가능합니다.').slideDown();
          $('#memberForm input[name=password]').val('');
          setTimeout(function() { $('.error-message').slideUp().text(''); }, 2000);
          return false;
        }
      }

      if (nickname == '') {
        $('.error-message').text('사용하실 닉네임을 입력해주세요.').slideDown();
        setTimeout(function() { $('.error-message').slideUp().text(''); }, 2000);
        return false;
      } else {
        if (nickname.length < 2 || nickname.length > 10 || nickname.search(/\s/) != -1) {
          $('.error-message').text('닉네임은 띄어쓰기 없이 2자 ~ 10자 이하만 가능합니다.').slideDown();
          $('#memberForm input[name=nickname]').val('');
          setTimeout(function() { $('.error-message').slideUp().text(''); }, 2000);
          return false;
        }
      }

      if (phone1 == '' || phone2 == '' || phone3 == '') {
        $('.error-message').text('사용하시는 핸드폰 번호를 입력해주세요.').slideDown();
        setTimeout(function() { $('.error-message').slideUp().text(''); }, 2000);
        return false;
      }

      $.ajax({
        url: '/login/insert',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        type: 'post',
        beforeSend: function() {
          $dom.css('opacity', '0.5').prop('disabled', true);
        },
        success: function(result) {
          $dom.css('opacity', '1').prop('disabled', true);

          if (result.error == 1) {
            $('.error-message').text(result.message).slideDown();
            setTimeout(function() { $('.error-message').slideUp().text(''); }, 2000);
          } else {
            $('#entryModal').modal({backdrop: 'static', keyboard: false});
          }
        }
      });
    });
  </script>

  <div class="modal fade" id="entryModal" tabindex="-1" role="dialog" aria-labelledby="entryModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="smallmodalLabel">메시지</h5>
              </div>
              <div class="modal-body text-center">
                  <p class="modal-message mt-3">회원가입이 완료되었습니다!</p>
              </div>
              <div class="border-top text-center p-3">
                <a href="<?=BASE_URL?>/login"><button type="button" class="btn btn-info">로그인 페이지로</button></a>
              </div>
          </div>
      </div>
  </div>
