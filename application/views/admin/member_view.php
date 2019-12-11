<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">회원 정보</h1>
        </div>

        <form id="formMember" method="post" action="<?=base_url()?>admin/member_update">
          <input type="hidden" name="idx" value="<?=$view['idx']?>">
          <div class="row align-items-center mb-2">
            <div class="col-sm-1">아이디</div>
            <div class="col-sm-4"><input type="text" readonly name="userid" value="<?=$view['userid']?>" class="form-control"></div>
          </div>
          <div class="row align-items-center mb-2">
            <div class="col-sm-1">닉네임</div>
            <div class="col-sm-4"><input type="text" name="nickname" value="<?=$view['nickname']?>" class="form-control"></div>
          </div>
          <div class="row align-items-center mb-2">
            <div class="col-sm-1">실명</div>
            <div class="col-sm-4"><input type="text" name="realname" value="<?=$view['realname']?>" class="form-control"></div>
          </div>
          <div class="row align-items-center mb-2">
            <div class="col-sm-1">전화번호</div>
            <div class="col-sm-4 row">
              <div class="pl-3"><input type="text" size="4" name="phone1" value="<?=$view['phone'][0]?>" class="form-control"></div>
              <div class="pl-1"><input type="text" size="4" name="phone2" value="<?=$view['phone'][1]?>" class="form-control"></div>
              <div class="pl-1"><input type="text" size="4" name="phone3" value="<?=$view['phone'][2]?>" class="form-control"></div>
            </div>
          </div>
          <div class="row align-items-center mb-2">
            <div class="col-sm-1">생년월일</div>
            <div class="col-sm-4">
              <div class="row align-items-center">
                <div class="pl-3">
                  <select name="birthday_year" class="form-control">
                    <?php foreach (range(1900, date('Y')) as $value): ?>
                    <option<?=$view['birthday'][0] == $value ? ' selected' : ''?> value="<?=$value?>"><?=$value?>년</option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="pl-1">
                  <select name="birthday_month" class="form-control">
                    <?php foreach (range(1, 12) as $value): ?>
                    <option<?=$view['birthday'][1] == $value ? ' selected' : ''?> value="<?=$value?>"><?=$value?>월</option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="pl-1">
                  <select name="birthday_day" class="form-control">
                    <?php foreach (range(1, 31) as $value): ?>
                    <option<?=$view['birthday'][2] == $value ? ' selected' : ''?> value="<?=$value?>"><?=$value?>일</option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="row mt-2">
                <div class="col-sm-3">
                  <label><input type="radio" name="birthday_type" value="1"<?=$view['birthday_type'] == '1' ? ' checked' : ''?>> 양력</label> &nbsp;
                  <label><input type="radio" name="birthday_type" value="2"<?=$view['birthday_type'] == '2' ? ' checked' : ''?>> 음력</label>
                </div>
              </div>
            </div>
          </div>
          <div class="row align-items-center mb-2">
            <div class="col-sm-1">성별</div>
            <div class="col-sm-4">
              <label><input type="radio" name="gender" value="M"<?=$view['gender'] == 'M' ? ' checked' : ''?>> 남성</label> &nbsp;
              <label><input type="radio" name="gender" value="F"<?=$view['gender'] == 'F' ? ' checked' : ''?>> 여성</label>
            </div>
          </div>
          <div class="row align-items-center mb-2">
            <div class="col-sm-1">승차위치</div>
            <div class="col-sm-4">
              <select name="location" class="form-control">
                <?php foreach (arrLocation() as $value): ?>
                <option<?=$view['location'] == $value['no'] ? ' selected' : ''?> value="<?=$value['no']?>"><?=$value['title']?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="row align-items-center mb-2 pt-2 pb-2">
            <div class="col-sm-1">회원 형태</div>
            <div class="col-sm-4 row align-items-center">
              <div class="col-sm-4"><label><input<?=$view['level'] == LEVEL_LIFETIME ? ' checked' : ''?> type="checkbox" name="level" value="<?=LEVEL_LIFETIME?>"> 평생회원 체크</label></div>
              <div class="col-sm-4"><label><input<?=$view['level'] == LEVEL_FREE ? ' checked' : ''?> type="checkbox" name="level" value="<?=LEVEL_FREE?>"> 무료회원 체크</label></div>
              <div class="col-sm-4"><label><input<?=$view['admin'] == 1 ? ' checked' : ''?> type="checkbox" name="admin" value="1"> 관리자 체크</label></div>
            </div>
          </div>
          <div class="row align-items-center mb-2 pt-2 pb-2">
            <div class="col-sm-1">예약횟수</div>
            <div class="col-sm-4"><?=$view['rescount']?></div>
          </div>
          <div class="row align-items-center mb-2 pt-2 pb-2">
            <div class="col-sm-1">페널티</div>
            <div class="col-sm-4"><?=$view['penalty']?></div>
          </div>
          <div class="row align-items-center mb-2 pt-2 pb-2">
            <div class="col-sm-1">등급</div>
            <div class="col-sm-4"><?=$view['memberLevel']['levelName']?></div>
          </div>
          <div class="row align-items-center mb-2 pt-2 pb-2">
            <div class="col-sm-1">포인트</div>
            <div class="col-sm-4"><?=$view['point']?></div>
          </div>
          <div class="row align-items-center mb-2 pt-2 pb-2">
            <div class="col-sm-1">아이콘</div>
            <div class="col-sm-4">
              <?php if (file_exists(PHOTO_PATH . $view['idx'])): ?>
                <img src="<?=base_url()?><?=PHOTO_URL?><?=$view['idx']?>" style="max-width: 100px;">
              <?php else: ?>
                <img src="<?=base_url()?>public/images/user.png">
              <?php endif; ?>
            </div>
          </div>

          <div class="pt-2 pb-5 text-center">
            <div class="error-message"></div>
            <input type="hidden" name="back_url" value="member_list">
            <button type="button" class="btn btn-primary btn-member-update">수정합니다</button>
            <button type="button" class="btn btn-secondary btn-member-delete-modal">삭제합니다</button>
          </div>
        </form>
      </div>
    </div>
