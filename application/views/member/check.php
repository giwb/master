<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main>
    <form id="memberForm">
    <div class="container-fluid">
      <div class="row">
        <div class="col-1 col-lg-2"></div>
        <div class="col-lg-8">
          <div class="row">
            <div class="col-12">
              <h2 class="mt-4 mb-4 pb-4 border-bottom text-center"><?=$view['title']?> 이용약관</h2>
            </div>
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
            <div class="col-12"><h2 class="mt-3 mb-4 pb-4 border-bottom text-center">가입여부 확인</h2></div>
          </div>
          <div class="row">
            <div class="col-1 col-lg-2"></div>
            <div class="col-lg-8 row align-items-center">
              <div class="col-3">사용할 닉네임</div>
              <div class="col-9"><input type="text" name="nickname" maxlength="10" class="form-control"></div>
            </div>
            <div class="col-1 col-lg-2"></div>
          </div>
          <div class="row mt-2">
            <div class="col-1 col-lg-2"></div>
            <div class="col-lg-8 row align-items-center">
              <div class="col-3">휴대폰 번호</div>
              <div class="col-9">
                <div class="row w-100 no-gutters">
                  <div class="col-2 mr-2 p-0"><input type="text" name="phone1" maxlength="3" class="form-control"></div>
                  <div class="col-3 mr-2 p-0"><input type="text" name="phone2" maxlength="4" class="form-control"></div>
                  <div class="col-3 p-0"><input type="text" name="phone3" maxlength="4" class="form-control"></div>
                </div>
              </div>
            </div>
            <div class="col-1 col-lg-2"></div>
          </div>
          <div class="border-top text-center mt-4 pt-2">
            <div class="error-message"></div>
            <button type="button" class="btn btn-default btn-check mt-2">다음 단계로 &gt;</button>
          </div>
        </div>
        <div class="col-1 col-lg-2"></div>
      </div>
    </div>
    </form>
  </main>

  <form id="memberCheckComplete" method="post" action="<?=BASE_URL?>/login/entry">
    <input type="hidden" name="nickname">
    <input type="hidden" name="phone1">
    <input type="hidden" name="phone2">
    <input type="hidden" name="phone3">
    <input type="hidden" name="userIdx">
  </form>

  <script>
    $(document).on('click', '.btn-check', function() {
      var $dom = $(this);
      var nickname = $('#memberForm input[name=nickname]').val();
      var phone1 = $('#memberForm input[name=phone1]').val();
      var phone2 = $('#memberForm input[name=phone2]').val();
      var phone3 = $('#memberForm input[name=phone3]').val();

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
        url: '/login/check_member',
        data: 'club_idx=<?=$view['idx']?>&nickname=' + nickname + '&phone=' + phone1 + '-' + phone2 + '-' + phone3,
        dataType: 'json',
        type: 'post',
        beforeSend: function() {
          $dom.css('opacity', '0.5').prop('disabled', true);
        },
        success: function(result) {
          var $domForm = $('#memberCheckComplete');
          $('input[name=nickname]', $domForm).val(nickname);
          $('input[name=phone1]', $domForm).val(phone1);
          $('input[name=phone2]', $domForm).val(phone2);
          $('input[name=phone3]', $domForm).val(phone3);

          if (result.error == 1) {
            $dom.css('opacity', '1').prop('disabled', false);
            $.openMsgModal(result.message);
          } else if (result.error == 2) {
            $('input[name=userIdx]', $domForm).val(result.message);
            $domForm.attr('action', '<?=BASE_URL?>/login/entry_update');
            $domForm.submit();
          } else {
            $domForm.submit();
          }
        }
      });
    });
  </script>