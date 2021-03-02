<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="memberForm">
          <form id="entryForm" method="post" action="/member/update">
          <input type="hidden" name="page" value="member">
            <h2>개인정보수정</h2>
            <dl class="pt-2 pb-2">
              <dt>아이디</dt>
              <dd><?=$viewMember['userid']?><input type="hidden" name="userid" value="<?=$viewMember['userid']?>"></dd>
            </dl>
            <dl>
              <dt>닉네임</dt>
              <dd class="check-nickname"><input type="text" name="nickname" maxlength="20" class="form-control" value="<?=$viewMember['nickname']?>"></dd>
            </dl>
            <dl>
              <dt>비밀번호</dt>
              <dd class="check-password"><input type="password" name="password" class="form-control" autocomplete="new-password"></dd>
            </dl>
            <dl>
              <dt>비밀번호 확인</dt>
              <dd class="check-password check-password-message"><input type="password" name="password_check" class="form-control"></dd>
            </dl>
            <dl>
              <dt>실명</dt>
              <dd><input type="text" name="realname" maxlength="20" class="form-control" value="<?=$viewMember['realname']?>"></dd>
            </dl>
            <dl class="pt-2 pb-2">
              <dt>성별</dt>
              <dd>
                <label><input type="radio" name="gender" value="M"<?=$viewMember['gender'] == 'M' ? ' checked' : ''?>> 남성</label>
                <label><input type="radio" name="gender" value="F"<?=$viewMember['gender'] == 'F' ? ' checked' : ''?>> 여성</label>
              </dd>
            </dl>
            <dl>
              <dt>생년월일</dt>
              <dd>
                <div class="container mb-2">
                  <div class="row w-100">
                    <select name="birthday_year" class="form-control col-sm-4 mr-2 pl-1">
                      <option value=''>--</option>
                    <?php foreach (range(date('Y'), 1900) as $value): ?>
                      <option<?=$viewMember['birthday_year'] == $value ? ' selected' : ''?> value='<?=$value?>'><?=$value?>년</option>
                    <?php endforeach; ?>
                    </select>
                    <select name="birthday_month" class="form-control col-sm-3 mr-2 pl-1">
                      <option value=''>--</option>
                    <?php foreach (range(1, 12) as $value): ?>
                      <option<?=$viewMember['birthday_month'] == $value ? ' selected' : ''?> value='<?=$value?>'><?=$value?>월</option>
                    <?php endforeach; ?>
                    </select>
                    <select name="birthday_day" class="form-control col-sm-3 pl-1">
                      <option value=''>--</option>
                    <?php foreach (range(1, 31) as $value): ?>
                      <option<?=$viewMember['birthday_day'] == $value ? ' selected' : ''?> value='<?=$value?>'><?=$value?>일</option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <label><input type="radio" name="birthday_type" value="1"<?=$viewMember['birthday_type'] == '1' ? ' checked' : ''?>> 양력</label>
                <label><input type="radio" name="birthday_type" value="2"<?=$viewMember['birthday_type'] == '2' ? ' checked' : ''?>> 음력</label>
              </dd>
            </dl>
            <dl>
              <dt>전화번호</dt>
              <dd>
                <div class="container">
                  <div class="row w-100">
                    <div class="col-sm-2 mr-2 p-0"><input type="text" name="phone1" maxlength="3" class="form-control" value="<?=$viewMember['phone1']?>"></div>
                    <div class="col-sm-3 mr-2 p-0"><input type="text" name="phone2" maxlength="4" class="form-control" value="<?=$viewMember['phone2']?>"></div>
                    <div class="col-sm-3 p-0"><input type="text" name="phone3" maxlength="4" class="form-control" value="<?=$viewMember['phone3']?>"></div>
                  </div>
                </div>
              </dd>
            </dl>
            <dl>
              <dt>주 승차위치</dt>
              <dd>
                <select name="location" class="form-control">
                  <?php foreach (arrLocation($view['club_geton']) as $value): ?>
                  <option<?=$viewMember['location'] == $value['short'] ? ' selected' : ''?> value='<?=$value['short']?>'><?=$value['title']?></option>
                  <?php endforeach; ?>
                </select>
              </dd>
            </dl>
            <dl>
              <dt>사진</dt>
              <dd><img class="photo" src="<?=$viewMember['photo']?>"><input type="file" name="photo" class="file d-none"><button type="button" class="btn btn-sm btn-info btn-upload mt-2 pl-3 pr-3">사진올리기</button><input type="hidden" name="filename"><br><button type="button" class="btn btn-sm btn-danger btn-modify-photo-delete mt-1 pl-3 pr-3">사진　삭제</button></dd>
            </dl>
            <div class="area-btn">
              <button type="button" class="btn btn-primary btn-member-update">수정합니다</button>
              <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#quitModal" data-backdrop="static" data-keyboard="false">탈퇴합니다</button>
            </div>
          </form>
        </div>
      </div>

      <div class="modal fade" id="quitModal" tabindex="-1" role="dialog" aria-labelledby="quitModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="smallmodalLabel">회원 탈퇴</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body text-center">
              <p class="modal-message">회원에서 탈퇴하시면 적립된 포인트가 모두 사라집니다.<br>정말로 탈퇴하시겠습니까?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary btn-quit">탈퇴합니다</button>
              <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
              <a href="<?=BASE_URL?>"><button type="button" class="btn btn-primary btn-top d-none">메인 화면으로</button></a>
            </div>
          </div>
        </div>
      </div>

      <script>
        $(document).ready(function() {
          $.checkNickname();
        });
      </script>