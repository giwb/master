<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <form id="setupForm" method="post" action="<?=base_url()?>club/setup_update">
        <input type="hidden" name="clubIdx" value="<?=$view['idx']?>">
        <input type="hidden" name="page" value="club">
          <div class="setup-header" data-idx="1"><strong>기본정보</strong></div>
          <div class="setup-content" data-idx="1">
            <dl>
              <dt>산악회/단체명 <span class="require">(*)</span></dt>
              <dd><input type="text" name="title" value="<?=$view['title']?>"></dd>
            </dl>
            <dl>
              <dt>지역 <span class="require">(*)</span></dt>
              <dd>
                <button type="button" class="btn btn-primary btn-add-area mb-2">추가</button><br>
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
              <dt>홈페이지</dt>
              <dd><input type="text" name="homepage" value="<?=$view['homepage']?>"></dd>
            </dl>
            <dl>
              <dt>연락처</dt>
              <dd><input type="text" name="phone" value="<?=$view['phone']?>"></dd>
            </dl>
            <dl>
              <dt>사진</dt>
              <dd>
                <input type="file" class="file">
                <input type="hidden" name="file" value="">
                <div class="added-files">
                <?php
                  if ($view['photo'][0] != ''):
                    foreach ($view['photo'] as $value):
                ?>
                  <img src="<?=base_url()?><?=PHOTO_URL?><?=$value?>" class="btn-photo-modal" data-photo="<?=$value?>">
                <?php
                    endforeach;
                  endif;
                ?>
                </div>
              </dd>
            </dl>
            <div class="area-btn">
              <button type="button" class="btn btn-primary btn-setup">수정합니다</button>
            </div>
          </div>

          <div class="setup-header" data-idx="2"><strong>추가정보</strong></div>
          <div class="setup-content" data-idx="2">
            <dl>
              <dt>설립년도</dt>
              <dd>
                <select name="establish">
                  <option value="">설립년도 선택</option>
                  <?php foreach (range(date('Y'), 1900) as $value): ?>
                  <option<?=$value == $view['establish'] ? " selected" : ""?> value="<?=$value?>"><?=$value?>년</option>
                  <?php endforeach; ?>
                </select>
              </dd>
            </dl>
            <dl>
              <dt>단체 유형</dt>
              <dd>
                <label><input<?=in_array(1, $view['club_type']) ? " checked" : ""?> type="checkbox" name="club_type[]" value="1"> 친목</label>
                <label><input<?=in_array(2, $view['club_type']) ? " checked" : ""?> type="checkbox" name="club_type[]" value="2"> 안내</label>
                <label><input<?=in_array(3, $view['club_type']) ? " checked" : ""?> type="checkbox" name="club_type[]" value="3"> 동호회</label>
                <label><input<?=in_array(4, $view['club_type']) ? " checked" : ""?> type="checkbox" name="club_type[]" value="4"> 여행사</label>
              </dd>
            </dl>
            <dl>
              <dt>제공사항</dt>
              <dd>
                <label><input<?=in_array(1, $view['club_option']) ? " checked" : ""?> type="checkbox" name="club_option[]" value="1"> 조식</label>
                <label><input<?=in_array(2, $view['club_option']) ? " checked" : ""?> type="checkbox" name="club_option[]" value="2"> 중식</label>
                <label><input<?=in_array(3, $view['club_option']) ? " checked" : ""?> type="checkbox" name="club_option[]" value="3"> 석식</label>
                <label><input<?=in_array(4, $view['club_option']) ? " checked" : ""?> type="checkbox" name="club_option[]" value="4"> 하산주</label>
                <label><input<?=in_array(5, $view['club_option']) ? " checked" : ""?> type="checkbox" name="club_option[]" value="5"> 산행지도</label>
                <label><input<?=in_array(6, $view['club_option']) ? " checked" : ""?> type="checkbox" name="club_option[]" value="6"> 기념품</label><br>
                추가입력 <input type="text" name="club_option_text" class="width-half" value="<?=$view['club_option_text']?>">
              </dd>
            </dl>
            <dl>
              <dt>행사시기</dt>
              <dd>
                운행주간 &nbsp;
                <label><input type="checkbox" class="btn-all-check" data-id="club_cycle"> 매주</label>
                <label><input<?=in_array(1, $view['club_cycle']) ? " checked" : ""?> type="checkbox" name="club_cycle[]" value="1" class="club_cycle"> 1주</label>
                <label><input<?=in_array(2, $view['club_cycle']) ? " checked" : ""?> type="checkbox" name="club_cycle[]" value="2" class="club_cycle"> 2주</label>
                <label><input<?=in_array(3, $view['club_cycle']) ? " checked" : ""?> type="checkbox" name="club_cycle[]" value="3" class="club_cycle"> 3주</label>
                <label><input<?=in_array(4, $view['club_cycle']) ? " checked" : ""?> type="checkbox" name="club_cycle[]" value="4" class="club_cycle"> 4주</label>
                <label><input<?=in_array(5, $view['club_cycle']) ? " checked" : ""?> type="checkbox" name="club_cycle[]" value="5" class="club_cycle"> 5주</label><br>
                운행요일 &nbsp;
                <label><input type="checkbox" class="btn-all-check" data-id="club_week"> 매일</label>
                <label><input<?=in_array(1, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="1" class="club_week"> 월</label>
                <label><input<?=in_array(2, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="2" class="club_week"> 화</label>
                <label><input<?=in_array(3, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="3" class="club_week"> 수</label>
                <label><input<?=in_array(4, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="4" class="club_week"> 목</label>
                <label><input<?=in_array(5, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="5" class="club_week"> 금</label>
                <label><input<?=in_array(6, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="6" class="club_week"> 토</label>
                <label><input<?=in_array(7, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="7" class="club_week"> 일</label>
                <label><input<?=in_array(7, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="8" class="club_week"> 공휴일</label><br>
              </dd>
            </dl>
            <dl>
              <dt>승차위치</dt>
              <dd>
                <div class="club-geton-added">
                  <?php foreach ($view['club_geton'] as $value): if ($value != ''): ?>
                  <div class="club-geton-element"><input readonly type="text" name="club_geton[]" class="width-half" value="<?=$value?>"> <button type="button" class="btn btn-primary btn-club-geton-delete">삭제</button></div>
                  <?php endif; endforeach; ?>
                </div>
                <input type="text" name="club_geton[]" class="club-geton-text width-half"> <button type="button" class="btn btn-primary btn-club-geton">추가</button>
              </dd>
            </dl>
            <dl>
              <dt>하차위치</dt>
              <dd>
                <div class="club-getoff-added">
                  <?php foreach ($view['club_getoff'] as $value): if ($value != ''): ?>
                  <div class="club-getoff-element"><input readonly type="text" name="club_getoff[]" class="width-half" value="<?=$value?>"> <button type="button" class="btn btn-primary btn-club-getoff-delete">삭제</button></div>
                  <?php endif; endforeach; ?>
                </div>
                <input type="text" name="club_getoff[]" class="club-getoff-text width-half"> <button type="button" class="btn btn-primary btn-club-getoff">추가</button>
              </dd>
            </dl>
            <div class="area-btn">
              <button type="button" class="btn btn-primary btn-setup">수정합니다</button>
            </div>
          </div>
          <div class="setup-header" data-idx="3"><strong>산악회 소개</strong></div>
          <div class="setup-content" data-idx="3">
            <textarea name="about" id="about" rows="10" cols="100"><?=!empty($view['about']) ? reset_html_escape($view['about']) : ''?></textarea>
            <div class="area-btn">
              <button type="button" class="btn btn-primary btn-setup">수정합니다</button>
            </div>
          </div>
          <div class="setup-header" data-idx="4"><strong>등산 안내인 소개</strong></div>
          <div class="setup-content" data-idx="4">
            <textarea name="guide" id="guide" rows="10" cols="100"><?=!empty($view['guide']) ? reset_html_escape($view['guide']) : ''?></textarea>
            <div class="area-btn">
              <button type="button" class="btn btn-primary btn-setup">수정합니다</button>
            </div>
          </div>
          <div class="setup-header" data-idx="5"><strong>이용안내</strong></div>
          <div class="setup-content" data-idx="5">
            <textarea name="howto" id="howto" rows="10" cols="100"><?=!empty($view['howto']) ? reset_html_escape($view['howto']) : ''?></textarea>
            <div class="area-btn">
              <button type="button" class="btn btn-primary btn-setup">수정합니다</button>
            </div>
          </div>
          <div class="setup-header" data-idx="6"><strong>백산백소 소개</strong></div>
          <div class="setup-content" data-idx="6">
            <textarea name="hundred" id="hundred" rows="10" cols="100"><?=!empty($view['hundred']) ? reset_html_escape($view['hundred']) : ''?></textarea>
            <div class="area-btn">
              <button type="button" class="btn btn-primary btn-setup">수정합니다</button>
            </div>
          </div>
        </form>
      </div>

      <script src="/public/editor/js/service/HuskyEZCreator.js" type="text/javascript" charset="utf-8"></script>
      <script language="javascript">
        var oEditors1 = [];
        nhn.husky.EZCreator.createInIFrame({
          oAppRef: oEditors1,
          elPlaceHolder: 'about',
          sSkinURI: '/public/editor/SmartEditor2Skin.html',
          fCreator: 'createSEditor2',
          tParams: { fOnBeforeUnload : function(){}}
        });
        var oEditors2 = [];
        nhn.husky.EZCreator.createInIFrame({
          oAppRef: oEditors2,
          elPlaceHolder: 'guide',
          sSkinURI: '/public/editor/SmartEditor2Skin.html',
          fCreator: 'createSEditor2',
          tParams: { fOnBeforeUnload : function(){}}
        });
        var oEditors3 = [];
        nhn.husky.EZCreator.createInIFrame({
          oAppRef: oEditors3,
          elPlaceHolder: 'howto',
          sSkinURI: '/public/editor/SmartEditor2Skin.html',
          fCreator: 'createSEditor2',
          tParams: { fOnBeforeUnload : function(){}}
        });
        var oEditors4 = [];
        nhn.husky.EZCreator.createInIFrame({
          oAppRef: oEditors4,
          elPlaceHolder: 'hundred',
          sSkinURI: '/public/editor/SmartEditor2Skin.html',
          fCreator: 'createSEditor2',
          tParams: { fOnBeforeUnload : function(){}}
        });

        $(document).on('change', '.area-sido', function() {
          var $dom = $(this);
          var parent = $dom.val();

          $.ajax({
            url: $('input[name=base_url]').val() + 'club/list_gugun',
            data: 'parent=' + parent,
            dataType: 'json',
            type: 'post',
            success: function(result) {
              $dom.next().empty().append( $('<option value="">시/군/구</option>') );
              for (var i=0; i<result.length; i++) {
                $dom.next().append( $('<option value="' + result[i].idx + '">' + result[i].name + '</option>') );
              }
            }
          });
        }).on('click', '.btn-add-area', function() {
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
        }).on('click', '.setup-header', function() {
          var $dom = $('.setup-content[data-idx=' + $(this).data('idx') + ']')
          if ($dom.css('display') == 'none') {
            $dom.slideDown();
          } else {
            $dom.slideUp();
          }
        });
      </script>
