<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div class="club-main memberForm row mt-4 mb-4">
      <div class="col-sm-3 d-none d-sm-block">&nbsp;</div>
      <div class="col-12 col-sm-6">
        <form id="entryForm" method="post" action="<?=BASE_URL?>/login/insert">
        <input type="hidden" name="club_idx" value="<?=$view['idx']?>">
        <input type="hidden" name="page" value="member">
          <h2 class="border-bottom mb-4 pb-3">회원가입</h2>
          <dl>
            <dt>닉네임</dt>
            <dd><input readonly type="text" class="form-control" value="<?=$nickname?>"><input type="hidden" name="nickname" value="<?=$nickname?>"></dd>
          </dl>
          <dl>
            <dt>휴대폰</dt>
            <dd>
              <div class="row w-100">
                <div class="col-sm-2 mr-2 p-0"><input readonly type="text" class="form-control" value="<?=$phone1?>"><input type="hidden" name="phone1" value="<?=$phone1?>"></div>
                <div class="col-sm-3 mr-2 p-0"><input readonly type="text" class="form-control" value="<?=$phone2?>"><input type="hidden" name="phone2" value="<?=$phone2?>"></div>
                <div class="col-sm-3 p-0"><input readonly type="text" class="form-control" value="<?=$phone3?>"><input type="hidden" name="phone3" value="<?=$phone3?>"></div>
              </div>
            </dd>
          </dl>
          <dl>
            <dt>사용할 아이디</dt>
            <dd class="check-userid"><input type="text" name="userid" maxlength="20" class="form-control"></dd>
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
              <label><input type="radio" name="gender" value="M" checked> 남성</label>
              <label><input type="radio" name="gender" value="F"> 여성</label>
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
              <label><input type="radio" name="birthday_type" value="1" checked> 양력</label>
              <label><input type="radio" name="birthday_type" value="2"> 음력</label>
            </dd>
          </dl>
          <dl>
            <dt>승차위치</dt>
            <dd>
              <select name="location" class="form-control">
                <?php foreach (arrLocation($view['club_geton'], NULL, NULL, NULL, 1) as $value): ?>
                <option value="<?=$value['short']?>"><?=$value['title']?></option>
                <?php endforeach; ?>
              </select>
            </dd>
          </dl>
          <dl>
            <dt>사진</dt>
            <dd><img class="photo" src="/public/images/noimage.png"><input type="file" name="photo" class="file d-none"><button type="button" class="btn btn-sm btn-info btn-upload mt-2 pl-3 pr-3">사진올리기</button><input type="hidden" name="filename"><br><button type="button" class="btn btn-sm btn-danger btn-entry-photo-delete mt-1 pl-3 pr-3 d-none">사진　삭제</button></dd>
          </dl>
          <div class="border-top text-center mt-4 pt-2">
            <button type="button" class="btn btn-default btn-entry mt-2 mr-2">등록합니다</button>
            <button type="button" class="btn btn-secondary mt-2 ml-2" onClick="javascript:history.back();">이전 화면으로</button>
          </div>
        </form>
      </div>
      <div class="col-sm-3 d-none d-sm-block">&nbsp;</div>
    </div>
