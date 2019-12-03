<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="memberForm">
          <form id="forgotIdForm" action="<?=base_url()?>member/change_password">
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
                  <div class="row">
                    <select name="birthday_year" class="form-control col-sm-5 mr-2">
                    <?php foreach (range(date('Y'), 1900) as $value): ?>
                      <option value='<?=$value?>'><?=$value?>년</option>
                    <?php endforeach; ?>
                    </select>
                    <select name="birthday_month" class="form-control col-sm-3 mr-2">
                    <?php foreach (range(1, 12) as $value): ?>
                      <option value='<?=$value?>'><?=$value?>월</option>
                    <?php endforeach; ?>
                    </select>
                    <select name="birthday_day" class="form-control col-sm-3 mr-2">
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
                  <div class="row">
                    <input type="text" name="phone1" maxlength="3" class="form-control col-sm-2 mr-2">
                    <input type="text" name="phone2" maxlength="4" class="form-control col-sm-3 mr-2">
                    <input type="text" name="phone3" maxlength="4" class="form-control col-sm-3 mr-2">
                  </div>
                </div>
              </dd>
            </dl>
            <div class="area-btn">
              <button type="button" class="btn btn-primary btn-entry">아이디 찾기</button>
            </div>
          </form>

          <p><br></p>

          <form id="forgotPwForm" action="<?=base_url()?>member/change_password">
            <h2>비밀번호 찾기</h2>
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
                  <div class="row">
                    <select name="birthday_year" class="form-control col-sm-5 mr-2">
                    <?php foreach (range(date('Y'), 1900) as $value): ?>
                      <option value='<?=$value?>'><?=$value?>년</option>
                    <?php endforeach; ?>
                    </select>
                    <select name="birthday_month" class="form-control col-sm-3 mr-2">
                    <?php foreach (range(1, 12) as $value): ?>
                      <option value='<?=$value?>'><?=$value?>월</option>
                    <?php endforeach; ?>
                    </select>
                    <select name="birthday_day" class="form-control col-sm-3 mr-2">
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
                  <div class="row">
                    <input type="text" name="phone1" maxlength="3" class="form-control col-sm-2 mr-2">
                    <input type="text" name="phone2" maxlength="4" class="form-control col-sm-3 mr-2">
                    <input type="text" name="phone3" maxlength="4" class="form-control col-sm-3 mr-2">
                  </div>
                </div>
              </dd>
            </dl>
            <dl>
              <dt>새로운 비밀번호</dt>
              <dd><input type="password" name="password" class="form-control" autocomplete="new-password"></dd>
            </dl>
            <div class="area-btn">
              <button type="button" class="btn btn-primary btn-entry">비밀번호 변경</button>
            </div>
          </form>
        </div>
      </div>
