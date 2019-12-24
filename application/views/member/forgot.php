<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="memberForm">
          <form id="forgotIdForm" action="<?=base_url()?>login/search_id">
            <h2>아이디 찾기</h2>
            <dl>
              <dt>실명</dt>
              <dd><input type="text" name="realname" class="form-control"></dd>
            </dl>
            <dl>
              <dt>성별</dt>
              <dd>
                <label><input type="radio" name="gender" value="M"> 남성</label>
                <label><input type="radio" name="gender" value="F"> 여성</label>
              </dd>
            </dl>
            <dl>
              <dt>생년월일</dt>
              <dd>
                <div class="container">
                  <div class="row w-100">
                    <select name="birthday_year" class="form-control col-sm-5 mr-2 pl-1">
                    <?php foreach (range(date('Y'), 1900) as $value): ?>
                      <option value='<?=$value?>'><?=$value?>년</option>
                    <?php endforeach; ?>
                    </select>
                    <select name="birthday_month" class="form-control col-sm-3 mr-2 pl-1">
                    <?php foreach (range(1, 12) as $value): ?>
                      <option value='<?=$value?>'><?=$value?>월</option>
                    <?php endforeach; ?>
                    </select>
                    <select name="birthday_day" class="form-control col-sm-3 mr-2 pl-1">
                    <?php foreach (range(1, 31) as $value): ?>
                      <option value='<?=$value?>'><?=$value?>일</option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </dd>
            </dl>
            <dl>
              <dt>전화번호</dt>
              <dd>
                <div class="container">
                  <div class="row w-100">
                    <div class="col-sm-2 mr-2 p-0"><input type="text" name="phone1" maxlength="3" class="form-control"></div>
                    <div class="col-sm-3 mr-2 p-0"><input type="text" name="phone2" maxlength="4" class="form-control"></div>
                    <div class="col-sm-3 p-0"><input type="text" name="phone3" maxlength="4" class="form-control"></div>
                  </div>
                </div>
              </dd>
            </dl>
            <div class="area-btn">
              <button type="button" class="btn btn-primary btn-search-id">아이디 찾기</button>
            </div>
          </form>

          <p><br></p>

          <form id="forgotPwForm" action="<?=base_url()?>login/change_pw">
            <h2>비밀번호 변경</h2>
            <dl>
              <dt>아이디</dt>
              <dd><input type="text" name="userid" class="form-control"></dd>
            </dl>
            <dl>
              <dt>실명</dt>
              <dd><input type="text" name="realname" class="form-control"></dd>
            </dl>
            <dl>
              <dt>성별</dt>
              <dd>
                <label><input type="radio" name="gender" value="M"> 남성</label>
                <label><input type="radio" name="gender" value="F"> 여성</label>
              </dd>
            </dl>
            <dl>
              <dt>생년월일</dt>
              <dd>
                <div class="container">
                  <div class="row w-100">
                    <select name="birthday_year" class="form-control col-sm-5 mr-2 pl-1">
                    <?php foreach (range(date('Y'), 1900) as $value): ?>
                      <option value='<?=$value?>'><?=$value?>년</option>
                    <?php endforeach; ?>
                    </select>
                    <select name="birthday_month" class="form-control col-sm-3 mr-2 pl-1">
                    <?php foreach (range(1, 12) as $value): ?>
                      <option value='<?=$value?>'><?=$value?>월</option>
                    <?php endforeach; ?>
                    </select>
                    <select name="birthday_day" class="form-control col-sm-3 mr-2 pl-1">
                    <?php foreach (range(1, 31) as $value): ?>
                      <option value='<?=$value?>'><?=$value?>일</option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </dd>
            </dl>
            <dl>
              <dt>전화번호</dt>
              <dd>
                <div class="container">
                  <div class="row w-100">
                    <div class="col-sm-2 mr-2 p-0"><input type="text" name="phone1" maxlength="3" class="form-control"></div>
                    <div class="col-sm-3 mr-2 p-0"><input type="text" name="phone2" maxlength="4" class="form-control"></div>
                    <div class="col-sm-3 p-0"><input type="text" name="phone3" maxlength="4" class="form-control"></div>
                  </div>
                </div>
              </dd>
            </dl>
            <dl>
              <dt>새로운 비밀번호</dt>
              <dd><input type="password" name="password" class="form-control" autocomplete="new-password"></dd>
            </dl>
            <div class="area-btn">
              <button type="button" class="btn btn-primary btn-change-password">비밀번호 변경</button>
            </div>
          </form>
        </div>
        <div class="ad-sp">
          <!-- SP_CENTER -->
          <ins class="adsbygoogle"
            style="display:block"
            data-ad-client="ca-pub-2424708381875991"
            data-ad-slot="4319659782"
            data-ad-format="auto"
            data-full-width-responsive="true"></ins>
          <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
          </script>
        </div>
      </div>

      <script type="text/javascript">
        $(document).on('click', '.btn-search-id', function() {
          var $btn = $(this);
          var $dom = $('#forgotIdForm');
          var formData = new FormData($dom[0]);
          formData.append('clubIdx', $('input[name=clubIdx]').val());

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
              $.openMsgModal(result.message);
            }
          });
        }).on('click', '.btn-change-password', function() {
          var $btn = $(this);
          var $dom = $('#forgotPwForm');
          var formData = new FormData($dom[0]);
          formData.append('clubIdx', $('input[name=clubIdx]').val());

          if ($('input[name=password]').val() == '') {
            $.openMsgModal('새로운 비밀번호를 입력해주세요.');
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
              $.openMsgModal(result.message);
            }
          });
        });
      </script>
