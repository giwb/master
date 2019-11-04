<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="club-main">
  <div class="memberForm">
<?php if (!empty($viewMember)): ?>
    <h2>마이페이지</h2>

      <b><?=$viewMember['nickname']?></b>님은 현재 <?=$viewLevel['levelName']?> 이십니다.<br>
      현재 산행 횟수 <?=$viewLevel['cntNotice']?>회, 예약 횟수 <?=$viewLevel['cntReserve']?>회, 취소 페널티 <?=$viewMember['penalty']?>점, 현재 레벨은 <?=$viewLevel['level']?>점 입니다.
<!--
<b></b>님은 현재 <?php //$val = memberLevel($userid); echo $val[1]; ?>이십니다.<br />
<span style="font-size:8pt;">현재 산행 횟수 <?//=$max?>회, <font color=blue>예약 횟수 <?//=$rows[0]["cnt"]?>회</font>, <font color=red>취소 페널티 <?//=$dataInfo[0]["penalty"]?>점</font>. 현재 레벨은 <b><font color=<?//=($level > 0) ? "blue" : "red"?>><?//=$level?>점</font></b> 입니다.</span>
-->
<?php else: ?>
    <h2>로그인</h2>

    <form id="loginForm" method="post" class="mt-5 mr-5 ml-5">
      <dl>
        <dt>아이디</dt>
        <dd><input type="text" name="userid" class="form-control input-login"></dd>
      </dl>
      <dl>
        <dt>비밀번호</dt>
        <dd><input type="password" name="password" class="form-control input-login"></dd>
      </dl>
      <div class="error-message"></div>
      <hr>
      <div class="row mr-3 ml-3">
        <div class="col-sm-8">
          <a href="<?=base_url()?>member/entry/<?=$view['idx']?>"><button type="button" class="btn btn-primary">회원가입</button></a>
          <a href="<?=base_url()?>member/forgot/<?=$view['idx']?>"><button type="button" class="btn btn-secondary">아이디/비밀번호 찾기</button></a>
        </div>
        <div class="col-sm-4 text-right">
          <button type="button" class="btn btn-primary btn-login">로그인</button>
        </div>
      </div>
    </form>
<?php endif; ?>
  </div>
</div>
