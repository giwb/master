<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main class="memberForm">
    <form id="entryForm" method="post" action="<?=BASE_URL?>/login/insert">
    <input type="hidden" name="club_idx" value="<?=$view['idx']?>">
    <input type="hidden" name="page" value="member">
    <div class="container-fluid">
      <div class="row align-items-center mt-2 mb-2">
        <div class="col-1 col-lg-2"></div>
        <div class="col-lg-8">
          <h2 class="mt-2 text-center"><?=$view['title']?> 회원가입</h2>
          <hr class="bg-danger">

          <div class="pl-4 pr-4">
            <div class="row align-items-center mt-2 mb-2">
              <div class="col-sm-2">닉네임</div>
              <div class="col-sm-10"><input readonly type="text" class="form-control" value="<?=$nickname?>"><input type="hidden" name="nickname" value="<?=$nickname?>"></div>
            </div>
            <div class="row align-items-center mt-2 mb-2">
              <div class="col-sm-2">휴대폰</div>
              <div class="col-sm-10">
                <div class="row no-gutters">
                  <div class="col-2 col-sm-1 mr-2 p-0"><input readonly type="text" class="form-control" value="<?=$phone1?>"><input type="hidden" name="phone1" value="<?=$phone1?>"></div>
                  <div class="col-3 col-sm-2 mr-2 p-0"><input readonly type="text" class="form-control" value="<?=$phone2?>"><input type="hidden" name="phone2" value="<?=$phone2?>"></div>
                  <div class="col-3 col-sm-2 p-0"><input readonly type="text" class="form-control" value="<?=$phone3?>"><input type="hidden" name="phone3" value="<?=$phone3?>"></div>
                </div>
              </div>
            </div>
            <div class="row align-items-center mt-2 mb-2">
              <div class="col-sm-2">사용할 아이디</div>
              <div class="col-sm-10 check-userid"><input type="text" name="userid" maxlength="20" class="form-control"></div>
            </div>
            <div class="row align-items-center mt-2 mb-2">
              <div class="col-sm-2">비밀번호</div>
              <div class="col-sm-10 check-password"><input type="password" name="password" class="form-control" autocomplete="new-password"></div>
            </div>
            <div class="row align-items-center mt-2 mb-2">
              <div class="col-sm-2">비밀번호확인</div>
              <div class="col-sm-10 check-password check-password-message"><input type="password" name="password_check" class="form-control"></div>
            </div>
            <div class="row align-items-center mt-2 mb-2">
              <div class="col-sm-2">실명</div>
              <div class="col-sm-10"><input type="text" name="realname" maxlength="20" class="form-control"></div>
            </div>
            <div class="row align-items-center mt-2 mb-2">
              <div class="col-sm-2">성별</div>
              <div class="col-sm-10">
                <label class="mr-3"><input type="radio" name="gender" value="M" checked> 남성</label>
                <label><input type="radio" name="gender" value="F"> 여성</label>
              </div>
            </div>
            <div class="row align-items-center mt-2 mb-2">
              <div class="col-sm-2">생년월일</div>
              <div class="col-sm-10">
                <div class="mb-2">
                  <div class="row no-gutters">
                    <select name="birthday_year" class="form-control col-4 col-sm-2 mr-2 pl-1">
                      <option value=''>--</option>
                    <?php foreach (range(date('Y'), 1900) as $value): ?>
                      <option value='<?=$value?>'><?=$value?>년</option>
                    <?php endforeach; ?>
                    </select>
                    <select name="birthday_month" class="form-control col-3 col-sm-1 mr-2 pl-1">
                      <option value=''>--</option>
                    <?php foreach (range(1, 12) as $value): ?>
                      <option value='<?=$value?>'><?=$value?>월</option>
                    <?php endforeach; ?>
                    </select>
                    <select name="birthday_day" class="form-control col-3 col-sm-1 mr-2 pl-1">
                      <option value=''>--</option>
                    <?php foreach (range(1, 31) as $value): ?>
                      <option value='<?=$value?>'><?=$value?>일</option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <label><input type="radio" name="birthday_type" value="1" checked> 양력</label>
                <label><input type="radio" name="birthday_type" value="2"> 음력</label>
              </div>
            </div>
            <div class="row align-items-center mt-2 mb-2">
              <div class="col-sm-2">승차위치</div>
              <div class="col-sm-10">
                <select name="location" class="form-control">
                  <?php foreach (arrLocation($view['club_geton'], NULL, NULL, NULL, 1) as $value): ?>
                  <option value="<?=$value['short']?>"><?=$value['title']?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="row align-items-center mt-2 mb-2">
              <div class="col-sm-2">사진</div>
              <div class="col-sm-10"><img class="photo" src="/public/images/noimage.png"><input type="file" name="photo" class="file d-none"><button type="button" class="btn btn-sm btn-info btn-upload mt-2 pl-3 pr-3">사진올리기</button><input type="hidden" name="filename"><br><button type="button" class="btn btn-sm btn-danger btn-entry-photo-delete mt-1 pl-3 pr-3 d-none">사진　삭제</button></div>
            </div>
            <div class="border-top text-center mt-4 pt-2">
              <button type="button" class="btn btn-default btn-entry mt-2 mr-2">등록합니다</button>
              <button type="button" class="btn btn-secondary mt-2 ml-2" onClick="javascript:history.back();">이전 화면으로</button>
            </div>
          </div>
        </div>
        <div class="col-1 col-lg-2"></div>
      </div>
    </div>
    </form>
  </main>
