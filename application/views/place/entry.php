<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section id="subpage">
  <div class="sub-header">
    <div class="left">
      <h2>여행정보 등록</h2>
    </div>
    <div class="right">
      <a href="<?=base_url()?>place"><button class="btn-back">목록으로</button></a>
    </div>
  </div>

  <div class="sub-contents">
    <form id="myForm" method="post">
      <input type="hidden" name="addedEditor" value="1">
      <dl>
        <dt>여행지 제목 <span class="require">(*)</span></dt>
        <dd><input type="text" name="title" value="<?=$view['title']?>"></dd>
      </dl>
      <dl>
        <dt>지역</dt>
        <dd>
          <button type="button" class="btn-add-area mb-2">추가</button><br>
<?php if (empty($view['sido'])): ?>
          <select name="area_sido[]" class="area-sido">
            <option value=''>시/도</option>
<?php foreach ($area_sido as $value): ?>
            <option<?=$value['idx'] == $view['area_sido'] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
<?php endforeach; ?>
          </select>
          <select name="area_gugun[]" class="area-gugun">
            <option value=''>시/군/구</option>
<?php foreach ($area_gugun as $value): ?>
            <option<?=$value['idx'] == $view['area_gugun'] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
<?php endforeach; ?>
          </select>
<?php else: ?>
<?php foreach ($view['sido'] as $key => $val): ?>
          <select name="area_sido[]" class="area-sido">
            <option value=''>시/도</option>
<?php foreach ($list_sido as $value): ?>
            <option<?=$value['name'] == $val ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
<?php endforeach; ?>
          </select>
          <select name="area_gugun[]" class="area-gugun">
            <option value=''>시/군/구</option>
<?php foreach ($list_gugun[$key] as $value): ?>
            <option<?=$value['name'] == $view['gugun'][$key] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
<?php endforeach; ?>
          </select><div class="mt-1"></div>
<?php endforeach; ?>
<?php endif; ?>
          <div class="added-area">
          </div>
        </dd>
      </dl>
      <dl>
        <dt>여행 포인트</dt>
        <dd>
          <label><input<?=in_array('point1', $view['point']) ? " checked" : ""?> type="checkbox" name="point[]" value="point1"> 문화재 &nbsp;</label>
          <label><input<?=in_array('point2', $view['point']) ? " checked" : ""?> type="checkbox" name="point[]" value="point2"> 천연기념물 &nbsp;</label>
          <label><input<?=in_array('point3', $view['point']) ? " checked" : ""?> type="checkbox" name="point[]" value="point3"> 보물</label>
        </dd>
      </dl>
      <dl>
        <dt>여행 분류</dt>
        <dd>
          <label><input<?=in_array('type1', $view['type']) ? " checked" : ""?> type="checkbox" name="type[]" value="type1" class="type1"> 산림청 선정 100대 명산 &nbsp;</label>
          <label><input<?=in_array('type2', $view['type']) ? " checked" : ""?> type="checkbox" name="type[]" value="type2"> 블랙야크 명산100 &nbsp;</label>
          <label><input<?=in_array('type3', $view['type']) ? " checked" : ""?> type="checkbox" name="type[]" value="type3"> 죽기전에 꼭 가봐야 할 국내여행 1001 &nbsp;</label>
          <label><input<?=in_array('type4', $view['type']) ? " checked" : ""?> type="checkbox" name="type[]" value="type4"> 백두대간 &nbsp;</label>
          <label><input<?=in_array('type5', $view['type']) ? " checked" : ""?> type="checkbox" name="type[]" value="type5"> 도보트레킹 &nbsp;</label>
          <label><input<?=in_array('type6', $view['type']) ? " checked" : ""?> type="checkbox" name="type[]" value="type6"> 투어 &nbsp;</label>
          <label><input<?=in_array('type7', $view['type']) ? " checked" : ""?> type="checkbox" name="type[]" value="type7"> 섬</label>
        </dd>
      </dl>
      <dl class="reason">
        <dt>선정 사유</dt>
        <dd><textarea name="reason" id="reason" style="height: 100px;"><?=$view['reason']?></textarea></dd>
      </dl>
      <dl>
        <dt>해발</dt>
        <dd><input type="number" name="altitude" value="<?=$view['altitude']?>" class="width-half"> ※ 숫자로만 입력해주세요.</dd>
      </dl>
      <dl>
        <dt>여행지 소개</dt>
        <dd><textarea name="content" id="content" rows="10" cols="100"><?=$view['content']?></textarea></dd>
      </dl>
      <dl>
        <dt>주변 관광지</dt>
        <dd><textarea name="around" id="around" rows="10" cols="100"><?=$view['around']?></textarea></dd>
      </dl>
      <dl>
        <dt>산행 코스</dt>
        <dd><textarea name="course" id="course" rows="10" cols="100" style="height: 100px;"><?=$view['course']?></textarea></dd>
      </dl>
      <dl>
        <dt>대표 사진 <span class="require">(*)</span></dt>
        <dd>
          <input type="hidden" name="file_<?=TYPE_MAIN?>">
          <input type="file" name="file" class="file" data-type="<?=TYPE_MAIN?>">
          <div class="added-files type<?=TYPE_MAIN?>">
<?php
  if ($view['photo'][0] != ''):
    foreach ($view['photo'] as $value):
      if ($value['type'] == 1):
?>
            <img src="<?=base_url()?><?=PHOTO_URL?><?=$value['filename']?>" class="btn-photo-modal" data-photo="<?=$value['filename']?>">
<?php
      endif;
    endforeach;
  endif;
?>
          </div>
        </dd>
      </dl>
      <dl>
        <dt>추가 사진</dt>
        <dd>
          <input type="hidden" name="file_<?=TYPE_ADDED?>">
          <input type="file" name="file" class="file" data-type="<?=TYPE_ADDED?>">
          <div class="added-files type<?=TYPE_ADDED?>">
<?php
  if ($view['photo'][0] != ''):
    foreach ($view['photo'] as $value):
      if ($value['type'] == 2):
?>
            <img src="<?=base_url()?><?=PHOTO_URL?><?=$value['filename']?>" class="btn-photo-modal" data-photo="<?=$value['filename']?>">
<?php
      endif;
    endforeach;
  endif;
?>
          </div>
        </dd>
      </dl>
      <dl>
        <dt>지도</dt>
        <dd>
          <input type="hidden" name="file_<?=TYPE_MAP?>">
          <input type="file" name="file" class="file" data-type="<?=TYPE_MAP?>">
          <div class="added-files type<?=TYPE_MAP?>">
<?php
  if ($view['photo'][0] != ''):
    foreach ($view['photo'] as $value):
      if ($value['type'] == 3):
?>
            <img src="<?=base_url()?><?=PHOTO_URL?><?=$value['filename']?>" class="btn-photo-modal" data-photo="<?=$value['filename']?>">
<?php
      endif;
    endforeach;
  endif;
?>
          </div>
        </dd>
      </dl>
      <div class="text-center">
        <hr>
        <input type="hidden" name="idx" value="<?=$view['idx']?>">
        <input type="hidden" name="page" value="place">
        <button type="button" class="btn-submit"><?=$view['idx'] == "" ? '등록합니다' : '수정합니다'?></button>
      </div>
    </form>
  </div>
</section>

<script language='javascript'>
function goPopup() {
  var pop = window.open('/public/area_popup.php', 'pop', 'width=570, height=420, scrollbars=yes, resizable=yes'); 
}

function jusoCallBack(roadFullAddr,roadAddrPart1,addrDetail,roadAddrPart2,engAddr, jibunAddr, zipNo, admCd, rnMgtSn, bdMgtSn, detBdNmList, bdNm, bdKdcd, siNm, sggNm, emdNm, liNm, rn, udrtYn, buldMnnm, buldSlno, mtYn, lnbrMnnm, lnbrSlno, emdNo) {
  document.myForm.area_sido.value = siNm;
  document.myForm.area_gugun.value = sggNm;
}

var oEditors = [];
nhn.husky.EZCreator.createInIFrame({
  oAppRef: oEditors,
  elPlaceHolder: 'content',
  sSkinURI: '/public/editor/SmartEditor2Skin.html',
  fCreator: 'createSEditor2',
  tParams: { fOnBeforeUnload : function(){}}
});

var oEditors2 = [];
nhn.husky.EZCreator.createInIFrame({
  oAppRef: oEditors2,
  elPlaceHolder: 'around',
  sSkinURI: '/public/editor/SmartEditor2Skin.html',
  fCreator: 'createSEditor2',
  tParams: { fOnBeforeUnload : function(){}}
});

var oEditors3 = [];
nhn.husky.EZCreator.createInIFrame({
  oAppRef: oEditors3,
  elPlaceHolder: 'reason',
  sSkinURI: '/public/editor/SmartEditor2Skin.html',
  fCreator: 'createSEditor2',
  tParams: { fOnBeforeUnload : function(){}}
});

var oEditors4 = [];
nhn.husky.EZCreator.createInIFrame({
  oAppRef: oEditors4,
  elPlaceHolder: 'course',
  sSkinURI: '/public/editor/SmartEditor2Skin.html',
  fCreator: 'createSEditor2',
  tParams: { fOnBeforeUnload : function(){}}
});

$(document).ready(function() {
  if ($('.type1').is(':checked') == false) {
    $('.reason').show();
  }

  $('.type1').click(function() {
    if ($(this).is(':checked') == true) {
      $('.reason').show();
    } else {
      $('.reason').hide();
    }
  });
});

$(document).on('click', '.btn-add-area', function() {
  var data = '<div class="mt-1"><select name="area_sido[]" class="area-sido">';
  data += '<option value="">시/도</option>';
<?php foreach ($area_sido as $value): ?>
  data += '<option<?=$value['idx'] == $view['area_sido'] ? " selected" : ""?> value="<?=$value['idx']?>""><?=$value['name']?></option>';
<?php endforeach; ?>
  data += '</select> ';
  data += '<select name="area_gugun[]" class="area-gugun">';
  data += '<option value="">시/군/구</option>';
<?php foreach ($area_gugun as $value): ?>
  data += '<option<?=$value['idx'] == $view['area_gugun'] ? " selected" : ""?> value="<?=$value['idx']?>""><?=$value['name']?></option>';
<?php endforeach; ?>
  data += '</select></div>';
  $('.added-area').append(data);
}).on('change', '.area-sido', function() {
  var $dom = $(this);
  var parent = $dom.val();

  $.ajax({
    url: $('input[name=base_url]').val() + 'place/list_gugun',
    data: 'parent=' + parent,
    dataType: 'json',
    type: 'post',
    success: function(result) {
      console.log(result);
      $dom.next().empty().append( $('<option value="">시/군/구</option>') );
      for (var i=0; i<result.length; i++) {
        $dom.next().append( $('<option value="' + result[i].idx + '">' + result[i].name + '</option>') );
      }
    }
  });
});
</script>
