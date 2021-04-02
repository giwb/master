<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div class="club-main memberForm row mt-4 mb-4">
      <div class="col-sm-3 d-none d-sm-block">&nbsp;</div>
      <div class="col-12 col-sm-6">
        <form id="entryForm" method="post">
        <input type="hidden" name="club_idx" value="<?=$view['idx']?>">
        <input type="hidden" name="idx" value="<?=$viewMember['idx']?>">
        <input type="hidden" name="page" value="member">
          <h2 class="border-bottom mb-3 pb-3">회원가입</h2>
          <div class="border-bottom text-center mb-4 pb-3 text-danger">
            ※ 관리자에 의해 가등록된 정보입니다.
          </div>
          <dl>
            <dt>닉네임</dt>
            <dd><input readonly type="text" class="form-control" value="<?=$viewMember['nickname']?>"><input type="hidden" name="nickname" value="<?=$viewMember['nickname']?>"></dd>
          </dl>
          <dl>
            <dt>전화번호</dt>
            <dd>
              <div class="row w-100">
                <div class="col-sm-2 mr-2 p-0"><input readonly type="text" class="form-control" value="<?=$viewMember['phone1']?>"><input type="hidden" name="phone1" value="<?=$viewMember['phone1']?>"></div>
                <div class="col-sm-3 mr-2 p-0"><input readonly type="text" class="form-control" value="<?=$viewMember['phone2']?>"><input type="hidden" name="phone2" value="<?=$viewMember['phone2']?>"></div>
                <div class="col-sm-3 p-0"><input readonly type="text" class="form-control" value="<?=$viewMember['phone3']?>"><input type="hidden" name="phone3" value="<?=$viewMember['phone3']?>"></div>
              </div>
            </dd>
          </dl>
          <dl>
            <dt>사용할 아이디</dt>
            <dd class="check-userid"><input type="text" name="userid" maxlength="20" class="form-control" value="<?=!empty($viewMember['userid']) ? $viewMember['userid'] : ''?>"></dd>
          </dl>
          <dl>
            <dt>비밀번호</dt>
            <dd class="check-password"><input type="password" name="password" class="form-control" autocomplete="new-password"></dd>
          </dl>
          <dl>
            <dt>비밀번호확인</dt>
            <dd class="check-password check-password-message"><input type="password" name="password_check" class="form-control"></dd>
          </dl>
          <dl>
            <dt>실명</dt>
            <dd><input type="text" name="realname" maxlength="20" class="form-control" value="<?=!empty($viewMember['realname']) ? $viewMember['realname'] : ''?>"></dd>
          </dl>
          <dl>
            <dt>성별</dt>
            <dd>
              <label><input type="radio" name="gender" value="M"<?=empty($viewMember['gender']) || (!empty($viewMember['gender']) && $viewMember['gender'] == 'M') ? ' checked' : ''?>> 남성</label>
              <label><input type="radio" name="gender" value="F"<?=!empty($viewMember['gender']) && $viewMember['gender'] == 'F' ? ' checked' : ''?>> 여성</label>
            </dd>
          </dl>
          <dl>
            <dt>생년월일</dt>
            <dd>
              <div class="mb-2">
                <div class="row w-100">
                  <select name="birthday_year" class="form-control col-sm-5 mr-2 pl-1">
                    <option value=''>--</option>
                    <?php foreach (range(date('Y'), 1900) as $value): ?>
                    <option<?=!empty($viewMember['birthday_year']) && $viewMember['birthday_year'] == $value ? ' selected' : ''?> value='<?=$value?>'><?=$value?>년</option>
                    <?php endforeach; ?>
                  </select>
                  <select name="birthday_month" class="form-control col-sm-3 mr-2 pl-1">
                    <option value=''>--</option>
                    <?php foreach (range(1, 12) as $value): ?>
                    <option<?=!empty($viewMember['birthday_month']) && $viewMember['birthday_month'] == $value ? ' selected' : ''?> value='<?=$value?>'><?=$value?>월</option>
                    <?php endforeach; ?>
                  </select>
                  <select name="birthday_day" class="form-control col-sm-3 mr-2 pl-1">
                    <option value=''>--</option>
                    <?php foreach (range(1, 31) as $value): ?>
                    <option<?=!empty($viewMember['birthday_day']) && $viewMember['birthday_day'] == $value ? ' selected' : ''?> value='<?=$value?>'><?=$value?>일</option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <label><input type="radio" name="birthday_type" value="1"<?=empty($viewMember['birthday_type']) || (!empty($viewMember['birthday_type']) && $viewMember['birthday_type'] == '1') ? ' checked' : ''?>> 양력</label>
              <label><input type="radio" name="birthday_type" value="2"<?=!empty($viewMember['birthday_type']) && $viewMember['birthday_type'] == '2' ? ' checked' : ''?>> 음력</label>
            </dd>
          </dl>
          <dl>
            <dt>승차위치</dt>
            <dd>
              <select name="location" class="form-control">
                <?php foreach (arrLocation($view['club_geton'], NULL, NULL, NULL, 1) as $value): ?>
                <option<?=!empty($viewMember['location']) && $viewMember['location'] == $value['no'] ? ' selected' : ''?> value="<?=$value['no']?>"><?=$value['title']?></option>
                <?php endforeach; ?>
              </select>
            </dd>
          </dl>
          <dl>
            <dt>사진</dt>
            <dd><img class="photo" src="/public/images/noimage.png"><input type="file" name="photo" class="file d-none"><button type="button" class="btn btn-sm btn-info btn-upload mt-2 pl-3 pr-3">사진올리기</button><input type="hidden" name="filename"><br><button type="button" class="btn btn-sm btn-danger btn-entry-photo-delete mt-1 pl-3 pr-3 d-none">사진　삭제</button></dd>
          </dl>
          <div class="border-top text-center mt-4 pt-2">
            <button type="button" class="btn btn-default btn-update mt-2 mr-2">등록합니다</button>
          </div>
        </form>
      </div>
      <div class="col-sm-3 d-none d-sm-block">&nbsp;</div>
    </div>

    <script>
      $(document).on('click', '.btn-update', function() {
        if ($('.check-userid img').hasClass('check-userid-complete') == false) {
          $.openMsgModal('아이디를 확인해주세요.');
          return false;
        }
        if ($('input[name=nickname]').val() == '') {
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
        if ($('input[name=phone1]').val() == '' || $('input[name=phone2]').val() == '' || $('input[name=phone3]').val() == '') {
          $.openMsgModal('전화번호를 확인해주세요.');
          return false;
        }
        if ($('select[name=location]').val() == '0') {
          $.openMsgModal('승차위치는 꼭 선택해주세요.');
          return false;
        }

        var $btn = $(this);
        var formData = new FormData($('#entryForm')[0]);

        $.ajax({
          url: '/login/update',
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
      });
    </script>
