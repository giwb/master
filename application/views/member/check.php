<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div class="club-main memberForm row mt-4 mb-4">
      <div class="col-sm-3 d-none d-sm-block">&nbsp;</div>
      <div class="col-12 col-sm-6">
        <h2 class="border-bottom mb-4 pb-3">가입여부 확인</h2>
        <dl>
          <dt>사용할 닉네임</dt>
          <dd><input type="text" name="nickname" maxlength="10" class="form-control"></dd>
        </dl>
        <dl>
          <dt>휴대폰 번호</dt>
          <dd>
            <div class="row w-100 p-0">
              <div class="col-2 mr-2 p-0"><input type="text" name="phone1" maxlength="3" class="form-control"></div>
              <div class="col-3 mr-2 p-0"><input type="text" name="phone2" maxlength="4" class="form-control"></div>
              <div class="col-3 p-0"><input type="text" name="phone3" maxlength="4" class="form-control"></div>
            </div>
          </dd>
        </dl>
        <div class="border-top text-center mt-4 pt-2">
          <div class="error-message"></div>
          <button type="button" class="btn btn-default btn-check mt-2">다음 단계로 &gt;</button>
        </div>
      </div>
      <div class="col-sm-3 d-none d-sm-block">&nbsp;</div>
    </div>
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
        var nickname = $('.memberForm input[name=nickname]').val();
        var phone1 = $('.memberForm input[name=phone1]').val();
        var phone2 = $('.memberForm input[name=phone2]').val();
        var phone3 = $('.memberForm input[name=phone3]').val();

        if (nickname == '') {
          $('.error-message').text('사용하실 닉네임을 입력해주세요.').slideDown();
          setTimeout(function() { $('.error-message').slideUp().text(''); }, 2000);
          return false;
        } else {
          if (nickname.length < 2 || nickname.length > 10 || nickname.search(/\s/) != -1) {
            $('.error-message').text('닉네임은 띄어쓰기 없이 2자 ~ 10자 이하만 가능합니다.').slideDown();
            $('.memberForm input[name=nickname]').val('');
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