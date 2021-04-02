<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <h2 class="sub-header mb-4">신규 회원등록 (관리자용)</h2>
        <div id="content" class="mb-5">
          <div class="border-bottom mt-3 mb-3 pb-2">
            <div class="text-right"><a href="<?=BASE_URL?>/admin/member_list"><button type="button" class="btn btn-sm btn-secondary">목록으로</button></a></div>
          </div>
          <form>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2 font-weight-bold">닉네임 <span class="required">(*)</span></div>
              <div class="col-sm-10"><input type="text" name="nickname" class="form-control check-nickname"><div class="check-message"></div></div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2 font-weight-bold">전화번호 <span class="required">(*)</span></div>
              <div class="col-sm-10">
                <div class="row">
                  <div class="pl-0"><input type="text" size="4" maxlength="4" name="phone1" class="form-control check-phone"></div>
                  <div class="pl-2"><input type="text" size="4" maxlength="4" name="phone2" class="form-control check-phone"></div>
                  <div class="pl-2"><input type="text" size="4" maxlength="4" name="phone3" class="form-control check-phone"></div>
                </div>
                <div class="check-message text-phone"></div>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2 font-weight-bold">실명</div>
              <div class="col-sm-10"><input type="text" name="realname" class="form-control"></div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2 font-weight-bold">생년월일</div>
              <div class="col-sm-10">
                <div class="row align-items-center">
                  <div class="pl-0">
                    <select name="birthday_year" class="form-control">
                      <option value=""></option>
                      <?php foreach (range(1900, date('Y')) as $value): ?>
                      <option value="<?=$value?>"><?=$value?>년</option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="pl-2">
                    <select name="birthday_month" class="form-control">
                      <option value=""></option>
                      <?php foreach (range(1, 12) as $value): ?>
                      <option value="<?=$value?>"><?=$value?>월</option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="pl-2">
                    <select name="birthday_day" class="form-control">
                      <option value=""></option>
                      <?php foreach (range(1, 31) as $value): ?>
                      <option value="<?=$value?>"><?=$value?>일</option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="row mt-2">
                  <label class="col col-sm-2 m-0 pl-0"><input type="radio" name="birthday_type" value="1" checked> 양력</label>
                  <label class="col col-sm-2 m-0"><input type="radio" name="birthday_type" value="2"> 음력</label>
                </div>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2 font-weight-bold">성별</div>
              <div class="col-sm-10 row align-items-center">
                <label class="col col-sm-2 m-0 pl-0"><input type="radio" name="gender" value="M" checked> 남성</label>
                <label class="col col-sm-2 m-0"><input type="radio" name="gender" value="F"> 여성</label>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2 font-weight-bold">승차위치1</div>
              <div class="col-sm-10">
                <select name="location" class="form-control">
                  <?php foreach (arrLocation($viewClub['club_geton']) as $value): ?>
                  <option value="<?=$value['no']?>"><?=$value['title']?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2 font-weight-bold">회원 형태</div>
              <div class="col-sm-10">
                <label class="d-block m-0"><input type="checkbox" name="level" value="<?=LEVEL_LIFETIME?>"> 평생회원</label>
                <label class="d-block m-0"><input type="checkbox" name="level" value="<?=LEVEL_FREE?>"> 무료회원</label>
                <label class="d-block m-0"><input type="checkbox" name="level" value="<?=LEVEL_DRIVER?>"> 드라이버</label>
                <label class="d-block m-0"><input type="checkbox" name="level" value="<?=LEVEL_DRIVER_ADMIN?>"> 드라이버 관리자</label>
                <label class="d-block m-0"><input type="checkbox" name="level" value="<?=LEVEL_BLACKLIST?>"> 블랙리스트</label>
                <label class="d-block m-0"><input type="checkbox" name="admin" value="1"> 관리자</label>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2 font-weight-bold">접속횟수</div>
              <div class="col-sm-10"><input type="text" name="connect" class="form-control" value="0"></div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2 font-weight-bold">예약횟수</div>
              <div class="col-sm-10"><input type="text" name="rescount" class="form-control" value="0"></div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2 font-weight-bold">포인트</div>
              <div class="col-sm-10"><input type="text" name="point" class="form-control" value="0"></div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2 font-weight-bold">페널티</div>
              <div class="col-sm-10"><input type="text" name="penalty" class="form-control" value="0"></div>
            </div>
            <div class="pt-2 pb-5 text-center">
              <div class="error-message"></div>
              <input type="hidden" name="clubIdx" value="<?=$clubIdx?>">
              <input type="hidden" name="baseUrl" value="<?=BASE_URL?>">
              <button type="button" class="btn btn-<?=$viewClub['main_color']?> btn-member-insert">등록합니다</button>
            </div>
          </form>
        </div>

        <script language="javascript">
        $(document).on('blur', '.check-userid', function() {
          // 아이디 확인
          var $dom = $(this);
          var userid = $(this).val();
          var pattern = /^[a-z0-9]{3,20}$/;
          $dom.next().text('');
          if (typeof(userid) != 'undefined' && userid != '') {
            if (userid.length < 3 || userid.length > 20 || !pattern.test(userid) || userid.search(/\s/) != -1) {
              $dom.next().text('아이디는 띄어쓰기 없이 3자 ~ 20자 이하의 영어 소문자만 가능합니다.');
              $dom.val('');
              return false;
            }
            $.ajax({
              url: '/login/check_userid',
              data: 'userid=' + userid,
              dataType: 'json',
              type: 'post',
              success: function(result) {
                if (result.error == 1) {
                  $dom.next().text('이미 사용중인 아이디 입니다.');
                  $dom.val('');
                } else {
                  $dom.next().text('');
                }
              }
            });
          }
        }).on('blur', '.check-password', function() {
          // 비밀번호 확인
          var $dom = $(this);
          var password = $(this).val();
          $dom.next().text('');
          if (typeof(password) != 'undefined' && password != '') {
            if (password.length < 6 || password.length > 20 || password.search(/\s/) != -1) {
              $dom.next().text('비밀번호는 띄어쓰기 없이 6자 ~ 20자 이하만 가능합니다.');
              $dom.val('');
              return false;
            }
          }
        }).on('blur', '.check-nickname', function() {
          // 닉네임 확인
          var $dom = $(this);
          var userid = $('.check-userid').val();
          var nickname = $(this).val();
          $dom.next().text('');
          if (typeof(userid) != 'undefined' && userid == '') {
            $dom.next().text('아이디를 먼저 입력해주세요.');
            return false;
          }
          if (typeof(nickname) != 'undefined' && nickname != '') {
            if (nickname.length < 2 || nickname.length > 10 || nickname.search(/\s/) != -1) {
              $dom.next().text('닉네임은 띄어쓰기 없이 2자 ~ 10자 이하만 가능합니다.');
              $dom.val('');
              return false;
            }
            $.ajax({
              url: '/login/check_nickname',
              data: 'userid=' + userid + '&nickname=' + nickname,
              dataType: 'json',
              type: 'post',
              success: function(result) {
                if (result.error == 1) {
                  $dom.next().text('이미 사용중인 닉네임 입니다.');
                  $dom.val('');
                } else {
                  $dom.next().text('');
                }
              }
            });
          }
        }).on('blur', '.check-phone', function() {
          var phone1 = $('input[name=phone1]').val();
          var phone2 = $('input[name=phone2]').val();
          var phone3 = $('input[name=phone3]').val();
          if (phone1 != '' && phone2 != '' && phone3 != '') {
            $.ajax({
              url: '/login/check_phone',
              data: 'phone=' + phone1 + '-' + phone2 + '-' + phone3,
              dataType: 'json',
              type: 'post',
              success: function(result) {
                if (result.error == 1) {
                  $('.text-phone').text('이미 사용중인 전화번호 입니다.');
                  $('input[name=phone1]').val('');
                  $('input[name=phone2]').val('');
                  $('input[name=phone3]').val('');
                } else {
                  $('.text-phone').text('');
                }
              }
            });
          }
        }).on('click', '.btn-member-insert', function() {
          var $btn = $(this);
          var baseUrl = $('input[name=baseUrl]').val();
          var formData = new FormData($('form')[0]);

          if ($('input[name=userid]').val() == '') {
            $.openMsgModal('아이디는 꼭 입력해주세요.');
            return false;
          }
          if ($('input[name=password]').val() == '') {
            $.openMsgModal('비밀번호는 꼭 입력해주세요.');
            return false;
          }
          if ($('input[name=nickname]').val() == '') {
            $.openMsgModal('닉네임은 꼭 입력해주세요.');
            return false;
          }
          if ($('input[name=phone1]').val() == '' || $('input[name=phone2]').val() == '' || $('input[name=phone3]').val() == '') {
            $.openMsgModal('전화번호는 꼭 입력해주세요.');
            return false;
          }

          $.ajax({
            url: '/admin/member_insert',
            processData: false,
            contentType: false,
            data: formData,
            dataType: 'json',
            type: 'post',
            beforeSend: function() {
              $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
            },
            success: function(result) {
              if (result == 1) {
                $btn.css('opacity', '1').prop('disabled', false).text('등록합니다');
                $.openMsgModal(result.message);
              } else {
                location.href = (baseUrl + '/admin/member_list');
              }
            }
          });
        });
        </script>
