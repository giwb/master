<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div class="container">
      <div class="row memberForm">
        <div class="col-sm-3"></div>
        <form class="col-sm-6" id="entryForm" method="post" action="<?=BASE_URL?>/login/insert">
        <input type="hidden" name="club_idx" value="<?=$view['idx']?>">
        <input type="hidden" name="page" value="member">
          <h2>회원가입</h2>
          <dl>
            <dt>아이디</dt>
            <dd class="check-userid"><input type="text" name="userid" maxlength="20" class="form-control"></dd>
          </dl>
          <dl>
            <dt>닉네임</dt>
            <dd class="check-nickname"><input type="text" name="nickname" maxlength="10" class="form-control"></dd>
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
            <dd><input type="text" name="realname" maxlength="20" class="form-control"></dd>
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
              <div class="container mb-2">
                <div class="row w-100">
                  <select name="birthday_year" class="form-control col-sm-5 mr-2 pl-1">
                    <option value=''>--</option>
                  <?php foreach (range(date('Y'), 1900) as $value): ?>
                    <option value='<?=$value?>'><?=$value?>년</option>
                  <?php endforeach; ?>
                  </select>
                  <select name="birthday_month" class="form-control col-sm-3 mr-2 pl-1">
                    <option value=''>--</option>
                  <?php foreach (range(1, 12) as $value): ?>
                    <option value='<?=$value?>'><?=$value?>월</option>
                  <?php endforeach; ?>
                  </select>
                  <select name="birthday_day" class="form-control col-sm-3 mr-2 pl-1">
                    <option value=''>--</option>
                  <?php foreach (range(1, 31) as $value): ?>
                    <option value='<?=$value?>'><?=$value?>일</option>
                  <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <label><input type="radio" name="birthday_type" value="1"> 양력</label>
              <label><input type="radio" name="birthday_type" value="2"> 음력</label>
            </dd>
          </dl>
          <dl>
            <dt>전화번호</dt>
            <dd>
              <div class="container">
                <div class="row w-100">
                  <div class="col-sm-2 mr-2 p-0"><input type="text" name="phone1" maxlength="3" class="form-control check-phone"></div>
                  <div class="col-sm-3 mr-2 p-0"><input type="text" name="phone2" maxlength="4" class="form-control check-phone"></div>
                  <div class="col-sm-3 p-0 check-phone"><input type="text" name="phone3" maxlength="4" class="form-control check-phone"></div>
                </div>
              </div>
            </dd>
          </dl>
          <dl>
            <dt>승차위치</dt>
            <dd>
              <select name="location" class="form-control">
                <?php foreach (arrLocation() as $value): ?>
                <option value="<?=$value['no']?>"><?=$value['title']?></option>
                <?php endforeach; ?>
              </select>
            </dd>
          </dl>
          <dl>
            <dt>사진</dt>
            <dd><img class="photo" src="/public/images/noimage.png"><input type="file" name="photo" class="file d-none"><button type="button" class="btn btn-sm btn-info btn-upload mt-2 pl-3 pr-3">사진올리기</button><input type="hidden" name="filename"><br><button type="button" class="btn btn-sm btn-danger btn-entry-photo-delete mt-1 pl-3 pr-3 d-none">사진　삭제</button></dd>
          </dl>
          <div class="area-btn">
            <button type="button" class="btn btn-primary btn-entry">등록합니다</button>
          </div>
          <div class="ad-sp">
            <!-- CENTER -->
            <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-2424708381875991" data-ad-slot="7579588805" data-ad-format="auto" data-full-width-responsive="true"></ins>
            <script> (adsbygoogle = window.adsbygoogle || []).push({}); </script>
          </div>
        </form>
        <div class="col-sm-3"></div>
      </div>
    </div>
