<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">회원 정보</h1>
        </div>

        <form id="formMember" method="post" action="<?=base_url()?>admin/update_member">
        <input type="hidden" name="idx" value="<?=$view['idx']?>">
        <table class="table">
          <colgroup>
            <col width="150">
          </colgroup>
          <tbody>
            <tr>
              <th>아이디</th>
              <td><?=$view['userid']?></td>
            </tr>
            <tr>
              <th>닉네임</th>
              <td><input type="text" name="nickname" value="<?=$view['nickname']?>"></td>
            </tr>
            <tr>
              <th>실명</th>
              <td><input type="text" name="realname" value="<?=$view['realname']?>"></td>
            </tr>
            <tr>
              <th>성별</th>
              <td>
                <label><input type="radio" name="gender" value="M"<?=$view['gender'] == 'M' ? ' checked' : ''?>> 남성</label> &nbsp;
                <label><input type="radio" name="gender" value="F"<?=$view['gender'] == 'F' ? ' checked' : ''?>> 여성</label>
              </td>
            </tr>
            <tr>
              <th>생년월일</th>
              <td>
                <select name="birthday_year">
<?php foreach (range(1900, date('Y')) as $value): ?>
                  <option<?=$view['birthday'][0] == $value ? ' selected' : ''?> value="<?=$value?>"><?=$value?>년</option>
<?php endforeach; ?>
                </select>
                <select name="birthday_month">
<?php foreach (range(1, 12) as $value): ?>
                  <option<?=$view['birthday'][1] == $value ? ' selected' : ''?> value="<?=$value?>"><?=$value?>월</option>
<?php endforeach; ?>
                </select>
                <select name="birthday_day">
<?php foreach (range(1, 31) as $value): ?>
                  <option<?=$view['birthday'][2] == $value ? ' selected' : ''?> value="<?=$value?>"><?=$value?>일</option>
<?php endforeach; ?>
                </select> &nbsp;
                <label><input type="radio" name="birthday_type" value="1"<?=$view['birthday_type'] == '1' ? ' checked' : ''?>> 양력</label> &nbsp;
                <label><input type="radio" name="birthday_type" value="2"<?=$view['birthday_type'] == '2' ? ' checked' : ''?>> 음력</label>
              </td>
            </tr>
            <tr>
              <th>전화번호</th>
              <td>
                <input type="text" size="4" name="phone1" value="<?=$view['phone'][0]?>">-<input type="text" size="4" name="phone2" value="<?=$view['phone'][1]?>">-<input type="text" size="4" name="phone3" value="<?=$view['phone'][2]?>">
              </td>
            </tr>
            <tr>
              <th>주 승차위치</th>
              <td>
                <select name="location">
<?php foreach (arrLocation() as $key => $value): ?>
                  <option<?=$view['location'] == $key ? ' selected' : ''?> value="<?=$key?>"><?=$value?></option>
<?php endforeach; ?>
                </select>
              </td>
            </tr>
            <tr>
              <th>주 메뉴선택</th>
              <td>
                <select name="breakfast">
<?php foreach (arrBreakfast() as $key => $value): ?>
                  <option<?=$view['breakfast'] == $key ? ' selected' : ''?> value="<?=$key?>"><?=$value?></option>
<?php endforeach; ?>
                </select>
              </td>
            </tr>
            <tr>
              <th>주 메뉴선택</th>
              <td><input<?=$view['level'] == 1 ? ' checked' : ''?> type="checkbox" name="level" value="1"> 평생회원 체크</td>
            </tr>
            <tr>
              <th>레벨</th>
              <td><?=$view['memberLevel']?></td>
            </tr>
            <tr>
              <th>산행횟수</th>
              <td><?=$view['cntPersonalReservation']?></td>
            </tr>
            <tr>
              <th>예약횟수</th>
              <td><?=$view['cntTotalReservation']?></td>
            </tr>
            <tr>
              <th>페널티</th>
              <td><?=$view['penalty']?></td>
            </tr>
            <tr>
              <th>현재 포인트</th>
              <td><?=$view['point']?></td>
            </tr>
            <tr>
              <td colspan="2" class="text-center">
                <div class="error-message"></div>
                <button type="button" class="btn btn-primary btn-member-update">수정합니다</button>
                <button type="button" class="btn btn-primary btn-member-delete ml10 mr10">삭제합니다</button>
                <button type="button" class="btn btn-primary btn-member-list">목록으로</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
