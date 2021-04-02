<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main id="club">
    <div class="container-fluid club-main">
      <div class="row mt-1 mb-5">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">

      <div class="row align-items-center mt-2 mb-2">
        <div class="col-1 col-lg-2"></div>
        <div class="col-lg-8">
          <h2 class="mt-2 text-center">아이디 찾기</h2>
          <hr class="bg-danger mb-5">

          <form id="forgotIdForm" action="/login/search_id">
          <div class="row">
            <div class="col-1"></div>
            <div class="col-lg-10">
              <div class="row align-items-center mb-2">
                <div class="col-sm-3 font-weight-bold">실명</div>
                <div class="col-sm-9"><input type="text" name="realname" maxlenght="20" class="form-control" autocomplete="off"></div>
              </div>
              <div class="row align-items-center mb-2">
                <div class="col-sm-3 font-weight-bold">성별</div>
                <div class="col-sm-9">
                  <label class="mr-3"><input type="radio" name="gender" value="M" checked> 남성</label>
                  <label><input type="radio" name="gender" value="F"> 여성</label>
                </div>
              </div>
              <div class="row align-items-center mb-2">
                <div class="col-sm-3 font-weight-bold">생년월일</div>
                <div class="col-sm-9 row no-gutters">
                  <select name="birthday_year" class="form-control col-3 mr-2 pl-1">
                  <?php foreach (range(date('Y'), 1900) as $value): ?>
                    <option value='<?=$value?>'><?=$value?>년</option>
                  <?php endforeach; ?>
                  </select>
                  <select name="birthday_month" class="form-control col-2 mr-2 pl-1">
                  <?php foreach (range(1, 12) as $value): ?>
                    <option value='<?=$value?>'><?=$value?>월</option>
                  <?php endforeach; ?>
                  </select>
                  <select name="birthday_day" class="form-control col-2 pl-1">
                  <?php foreach (range(1, 31) as $value): ?>
                    <option value='<?=$value?>'><?=$value?>일</option>
                  <?php endforeach; ?>
                  </select>                  
                </div>
              </div>
              <div class="row align-items-center mb-2">
                <div class="col-sm-3 font-weight-bold">전화번호</div>
                <div class="col-sm-9 row no-gutters">
                  <div class="col-2 mr-2 p-0"><input type="text" name="phone1" maxlength="3" class="form-control"></div>
                  <div class="col-3 mr-2 p-0"><input type="text" name="phone2" maxlength="4" class="form-control"></div>
                  <div class="col-3 p-0"><input type="text" name="phone3" maxlength="4" class="form-control"></div>
                </div>
              </div>
            </div>
            <div class="col-1"></div>
          </div>
          <div class="text-center border-top mt-4 mb-5">
            <div class="error-message search-id pt-2 pb-2"></div>
            <button type="button" class="btn btn-danger btn-search-id">아이디 찾기</button>
          </div>
          </form>

          <p class="mt-3"><br></p>

          <h2 class="mt-2 text-center">비밀번호 변경</h2>
          <hr class="bg-danger mb-5">

          <form id="forgotPwForm" action="/login/change_pw">
          <div class="row">
            <div class="col-1"></div>
            <div class="col-lg-10">
              <div class="row align-items-center mb-2">
                <div class="col-sm-3 font-weight-bold">아이디</div>
                <div class="col-sm-9"><input type="text" name="uid" maxlenght="20" class="form-control" autocomplete="off"></div>
              </div>
              <div class="row align-items-center mb-2">
                <div class="col-sm-3 font-weight-bold">실명</div>
                <div class="col-sm-9"><input type="text" name="realname" maxlenght="20" class="form-control" autocomplete="off"></div>
              </div>
              <div class="row align-items-center mb-2">
                <div class="col-sm-3 font-weight-bold">성별</div>
                <div class="col-sm-9">
                  <label class="mr-3"><input type="radio" name="gender" value="M" checked> 남성</label>
                  <label><input type="radio" name="gender" value="F"> 여성</label>
                </div>
              </div>
              <div class="row align-items-center mb-2">
                <div class="col-sm-3 font-weight-bold">생년월일</div>
                <div class="col-sm-9 row no-gutters">
                  <select name="birthday_year" class="form-control col-3 mr-2 pl-1">
                  <?php foreach (range(date('Y'), 1900) as $value): ?>
                    <option value='<?=$value?>'><?=$value?>년</option>
                  <?php endforeach; ?>
                  </select>
                  <select name="birthday_month" class="form-control col-2 mr-2 pl-1">
                  <?php foreach (range(1, 12) as $value): ?>
                    <option value='<?=$value?>'><?=$value?>월</option>
                  <?php endforeach; ?>
                  </select>
                  <select name="birthday_day" class="form-control col-2 pl-1">
                  <?php foreach (range(1, 31) as $value): ?>
                    <option value='<?=$value?>'><?=$value?>일</option>
                  <?php endforeach; ?>
                  </select>                  
                </div>
              </div>
              <div class="row align-items-center mb-2">
                <div class="col-sm-3 font-weight-bold">전화번호</div>
                <div class="col-sm-9 row no-gutters">
                  <div class="col-2 mr-2 p-0"><input type="text" name="phone1" maxlength="3" class="form-control"></div>
                  <div class="col-3 mr-2 p-0"><input type="text" name="phone2" maxlength="4" class="form-control"></div>
                  <div class="col-3 p-0"><input type="text" name="phone3" maxlength="4" class="form-control"></div>
                </div>
              </div>
              <div class="row align-items-center mb-2">
                <div class="col-sm-3 font-weight-bold">신규 비밀번호</div>
                <div class="col-sm-9"><input type="password" name="new_password" maxlenght="20" class="form-control" autocomplete="new-password"></div>
              </div>
            </div>
            <div class="col-1"></div>
          </div>
          <div class="text-center border-top mt-4 mb-5">
            <div class="error-message search-id pt-2 pb-2"></div>
            <button type="button" class="btn btn-danger btn-change-pw">비밀번호 변경</button>
          </div>
          </form>
        </div>
        <div class="col-1 col-lg-2"></div>
      </div>
    </div>
    </form>
    <input type="hidden" name="clubIdx" value="<?=!empty($clubIdx) ? $clubIdx : ''?>">
  </main>

  <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="smallmodalLabel">메시지</h5>
              </div>
              <div class="modal-body text-center">
                  <p class="modal-message mt-3"></p>
              </div>
              <div class="modal-footer">
                  <a href="<?=base_url()?>login"><button type="button" class="btn btn-info">로그인 페이지로</button></a>
                  <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
              </div>
          </div>
      </div>
  </div>

  <script type="text/javascript">
    $(document).on('click', '.btn-search-id', function() {
      var $btn = $(this);
      var $dom = $('#forgotIdForm');
      var formData = new FormData($dom[0]);
      //formData.append('clubIdx', $('input[name=clubIdx]').val());

      if ($('input[name=realname]', $dom).val() == '') {
        $('input[name=realname]', $dom).focus();
        $('.error-message').slideDown().text('실명은 꼭 입력해주세요.');
        setTimeout(function() { $('.error-message').text(''); }, 2000);
        return false;
      }
      if ($('input[name=phone1]', $dom).val() == '' || $('input[name=phone2]', $dom).val() == '' || $('input[name=phone3]', $dom).val() == '') {
        $('input[name=phone1]', $dom).focus();
        $('.error-message').slideDown().text('전화번호는 꼭 입력해주세요.');
        setTimeout(function() { $('.error-message').text(''); }, 2000);
        return false;
      }

      $.ajax({
        url: $dom.attr('action'),
        processData: false,
        contentType: false,
        data: formData,
        dataType: 'json',
        type: 'post',
        beforeSend: function(result) {
          $btn.css('opacity', '0.5').prop('disabled', true).text('검색중..');
        },
        success: function(result) {
          $btn.css('opacity', '1').prop('disabled', false).text('아이디 찾기');
          if (result.error == 1) {
            $('.error-message').slideDown().text(result.message);
            setTimeout(function() { $('.error-message').text(''); }, 2000);
          } else {
            $('#messageModal .btn-info').hide();
            $('#messageModal .btn-close').show();
            $('#messageModal .modal-message').text(result.message);
            $('#messageModal').modal({backdrop: 'static', keyboard: false});
          }
        }
      });
    }).on('click', '.btn-change-pw', function() {
      var $btn = $(this);
      var $dom = $('#forgotPwForm');
      var formData = new FormData($dom[0]);
      //formData.append('clubIdx', $('input[name=clubIdx]').val());

      if ($('input[name=uid]', $dom).val() == '') {
        $('input[name=uid]', $dom).focus();
        $('.error-message').slideDown().text('아이디는 꼭 입력해주세요.');
        setTimeout(function() { $('.error-message').text(''); }, 2000);
        return false;
      }
      if ($('input[name=realname]', $dom).val() == '') {
        $('input[name=realname]', $dom).focus();
        $('.error-message').slideDown().text('실명은 꼭 입력해주세요.');
        setTimeout(function() { $('.error-message').text(''); }, 2000);
        return false;
      }
      if ($('input[name=phone1]', $dom).val() == '' || $('input[name=phone2]', $dom).val() == '' || $('input[name=phone3]', $dom).val() == '') {
        $('input[name=phone1]', $dom).focus();
        $('.error-message').slideDown().text('전화번호는 꼭 입력해주세요.');
        setTimeout(function() { $('.error-message').text(''); }, 2000);
        return false;
      }
      if ($('input[name=new_password]', $dom).val() == '') {
        $('input[name=new_password]', $dom).focus();
        $('.error-message').slideDown().text('신규 비밀번호는 꼭 입력해주세요.');
        setTimeout(function() { $('.error-message').text(''); }, 2000);
        return false;
      }

      $.ajax({
        url: $dom.attr('action'),
        processData: false,
        contentType: false,
        data: formData,
        dataType: 'json',
        type: 'post',
        beforeSend: function(result) {
          $btn.css('opacity', '0.5').prop('disabled', true).text('처리중..');
        },
        success: function(result) {
          $btn.css('opacity', '1').prop('disabled', false).text('비밀번호 변경');
          if (result.error == 1) {
            $('.error-message').slideDown().text(result.message);
            setTimeout(function() { $('.error-message').text(''); }, 2000);
          } else {
            $('#messageModal .btn-info').show();
            $('#messageModal .btn-close').hide();
            $('#messageModal .modal-message').text(result.message);
            $('#messageModal').modal({backdrop: 'static', keyboard: false});
          }
        }
      });
    });
  </script>
