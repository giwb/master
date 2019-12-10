<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">클럽정보 수정</h1>
        </div>
      </div>
    </div>

    <div class="sub-contents">
      <form id="setupForm" method="post" action="<?=base_url()?>admin/setup_information_update">
        <h2>■ 기본정보</h2>
        <dl class="row align-items-center">
          <dt class="col-sm-1">산악회/단체명</dt>
          <dd class="col-sm-11 row">
            <div class="col-sm-4"><input type="text" name="title" value="<?=$view['title']?>" class="form-control"></div>
          </dd>
        </dl>
        <dl class="row align-items-center">
          <dt class="col-sm-1">지역</dt>
          <dd class="col-sm-11">
            <button type="button" class="btn btn-primary btn-add-area mb-2">추가</button><br>
            <?php if (empty($view['sido'])): ?>
            <div class="row pl-1">
              <div class="ml-2">
                <select name="area_sido[]" class="area-sido form-control">
                  <option value=''>시/도</option>
                  <?php foreach ($area_sido as $value): ?>
                  <option<?=$value['idx'] == $view['area_sido'] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="ml-2">
                <select name="area_gugun[]" class="area-gugun form-control">
                  <option value=''>시/군/구</option>
                  <?php foreach ($area_gugun as $value): ?>
                  <option<?=$value['idx'] == $view['area_gugun'] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <?php else: ?>
              <?php foreach ($view['sido'] as $key => $val): ?>
              <div class="row pl-1">
                <div class="ml-2">
                  <select name="area_sido[]" class="area-sido form-control">
                    <option value=''>시/도</option>
                    <?php foreach ($list_sido as $value): ?>
                    <option<?=$value['name'] == $val ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="ml-2">
                  <select name="area_gugun[]" class="area-gugun form-control">
                    <option value=''>시/군/구</option>
                    <?php foreach ($list_gugun[$key] as $value): ?>
                    <option<?=$value['name'] == $view['gugun'][$key] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                    <?php endforeach; ?>
                  </select><div class="mt-1"></div>
                </div>
              </div>
              <?php endforeach; ?>
            <?php endif; ?>
            <div class="added-area">
            </div>
          </dd>
        </dl>
        <dl class="row align-items-center">
          <dt class="col-sm-1">홈페이지</dt>
          <dd class="col-sm-11 row">
            <div class="col-sm-4"><input type="text" name="homepage" value="<?=$view['homepage']?>" class="form-control"></div>
          </dd>
        </dl>
        <dl class="row align-items-center">
          <dt class="col-sm-1">연락처</dt>
          <dd class="col-sm-11 row">
            <div class="col-sm-4"><input type="text" name="phone" value="<?=$view['phone']?>" class="form-control"></div>
          </dd>
        </dl>
        <dl class="row align-items-center">
          <dt class="col-sm-1">사진</dt>
          <dd class="col-sm-11">
            <input type="file" class="file">
            <input type="hidden" name="file" value="">
            <div class="added-files mt-3">
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
        </dl><br>

        <h2>■ 추가정보</h2>
        <dl class="row align-items-center">
          <dt class="col-sm-1">설립년도</dt>
          <dd class="col-sm-11 row">
            <div class="col-sm-2">
              <select name="establish" class="form-control">
                <option value="">설립년도 선택</option>
                <?php foreach (range(date('Y'), 1900) as $value): ?>
                <option<?=$value == $view['establish'] ? " selected" : ""?> value="<?=$value?>"><?=$value?>년</option>
                <?php endforeach; ?>
              </select>
            </div>
          </dd>
        </dl>
        <dl class="row align-items-center">
          <dt class="col-sm-1">단체유형</dt>
          <dd class="col-sm-11">
            <label class="mr-2"><input<?=in_array(1, $view['club_type']) ? " checked" : ""?> type="checkbox" name="club_type[]" value="1"> 친목</label>
            <label class="mr-2"><input<?=in_array(2, $view['club_type']) ? " checked" : ""?> type="checkbox" name="club_type[]" value="2"> 안내</label>
            <label class="mr-2"><input<?=in_array(3, $view['club_type']) ? " checked" : ""?> type="checkbox" name="club_type[]" value="3"> 동호회</label>
            <label class="mr-2"><input<?=in_array(4, $view['club_type']) ? " checked" : ""?> type="checkbox" name="club_type[]" value="4"> 여행사</label>
          </dd>
        </dl>
        <dl class="row align-items-center">
          <dt class="col-sm-1">제공사항</dt>
          <dd class="col-sm-11">
            <label class="mr-2"><input<?=in_array(1, $view['club_option']) ? " checked" : ""?> type="checkbox" name="club_option[]" value="1"> 조식</label>
            <label class="mr-2"><input<?=in_array(2, $view['club_option']) ? " checked" : ""?> type="checkbox" name="club_option[]" value="2"> 중식</label>
            <label class="mr-2"><input<?=in_array(3, $view['club_option']) ? " checked" : ""?> type="checkbox" name="club_option[]" value="3"> 석식</label>
            <label class="mr-2"><input<?=in_array(4, $view['club_option']) ? " checked" : ""?> type="checkbox" name="club_option[]" value="4"> 하산주</label>
            <label class="mr-2"><input<?=in_array(5, $view['club_option']) ? " checked" : ""?> type="checkbox" name="club_option[]" value="5"> 산행지도</label>
            <label class="mr-2"><input<?=in_array(6, $view['club_option']) ? " checked" : ""?> type="checkbox" name="club_option[]" value="6"> 기념품</label><br>
            <div class="row align-items-center mt-1">
              <div class="pl-2">추가입력</div>
              <div class="col-sm-3"><input type="text" name="club_option_text" class="form-control" value="<?=$view['club_option_text']?>"></div>
            </div>
          </dd>
        </dl>
        <dl class="row align-items-center">
          <dt class="col-sm-1">행사시기</dt>
          <dd class="col-sm-11">
            운행주간 &nbsp;
            <label class="mr-2"><input type="checkbox" class="btn-all-check" data-id="club_cycle"> 매주</label>
            <label class="mr-2"><input<?=in_array(1, $view['club_cycle']) ? " checked" : ""?> type="checkbox" name="club_cycle[]" value="1" class="club_cycle"> 1주</label>
            <label class="mr-2"><input<?=in_array(2, $view['club_cycle']) ? " checked" : ""?> type="checkbox" name="club_cycle[]" value="2" class="club_cycle"> 2주</label>
            <label class="mr-2"><input<?=in_array(3, $view['club_cycle']) ? " checked" : ""?> type="checkbox" name="club_cycle[]" value="3" class="club_cycle"> 3주</label>
            <label class="mr-2"><input<?=in_array(4, $view['club_cycle']) ? " checked" : ""?> type="checkbox" name="club_cycle[]" value="4" class="club_cycle"> 4주</label>
            <label class="mr-2"><input<?=in_array(5, $view['club_cycle']) ? " checked" : ""?> type="checkbox" name="club_cycle[]" value="5" class="club_cycle"> 5주</label><br>
            운행요일 &nbsp;
            <label class="mr-2"><input type="checkbox" class="btn-all-check" data-id="club_week"> 매일</label>
            <label class="mr-2"><input<?=in_array(1, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="1" class="club_week"> 월</label>
            <label class="mr-2"><input<?=in_array(2, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="2" class="club_week"> 화</label>
            <label class="mr-2"><input<?=in_array(3, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="3" class="club_week"> 수</label>
            <label class="mr-2"><input<?=in_array(4, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="4" class="club_week"> 목</label>
            <label class="mr-2"><input<?=in_array(5, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="5" class="club_week"> 금</label>
            <label class="mr-2"><input<?=in_array(6, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="6" class="club_week"> 토</label>
            <label class="mr-2"><input<?=in_array(7, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="7" class="club_week"> 일</label>
            <label class="mr-2"><input<?=in_array(7, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="8" class="club_week"> 공휴일</label>
          </dd>
        </dl>
        <dl class="row align-items-center">
          <dt class="col-sm-1">승차위치</dt>
          <dd class="col-sm-11">
            <div class="club-geton-added">
              <?php foreach ($view['club_geton'] as $value): if ($value != ''): ?>
              <div class="row align-items-center club-geton-element mb-2"><div class="ml-3"><input readonly type="text" name="club_geton[]" class="form-control" value="<?=$value?>"></div><div class="ml-2"><button type="button" class="btn btn-primary btn-club-geton-delete">삭제</button></div></div>
              <?php endif; endforeach; ?>
            </div>
            <div class="row align-items-center">
              <div class="ml-3"><input type="text" name="club_geton[]" class="club-geton-text form-control"></div>
              <div class="ml-2"><button type="button" class="btn btn-primary btn-club-geton">추가</button></div>
            </div>
          </dd>
        </dl>
        <dl class="row align-items-center">
          <dt class="col-sm-1">하차위치</dt>
          <dd class="col-sm-11">
            <div class="club-getoff-added">
              <?php foreach ($view['club_getoff'] as $value): if ($value != ''): ?>
              <div class="row align-items-center club-getoff-element mb-2"><div class="ml-3"><input readonly type="text" name="club_getoff[]" class="form-control" value="<?=$value?>"></div><div class="ml-2"><button type="button" class="btn btn-primary btn-club-getoff-delete">삭제</button></div></div>
              <?php endif; endforeach; ?>
            </div>
            <div class="row align-items-center">
              <div class="ml-3"><input type="text" name="club_getoff[]" class="club-getoff-text form-control"></div>
              <div class="ml-2"><button type="button" class="btn btn-primary btn-club-getoff">추가</button></div>
            </div>
          </dd>
        </dl>
        <div class="area-button">
          <button type="submit" class="btn btn-primary">확인합니다</button>
        </div>
      </form>
    </div>

    <script type="text/javascript">
      $(document).on('change', '.area-sido', function() {
        var $dom = $(this);
        var parent = $dom.val();

        $.ajax({
          url: $('input[name=base_url]').val() + 'club/list_gugun',
          data: 'parent=' + parent,
          dataType: 'json',
          type: 'post',
          success: function(result) {
            var $appendDom = $dom.parent().parent().find('.area-gugun');
            $appendDom.empty().append( $('<option value="">시/군/구</option>') );
            for (var i=0; i<result.length; i++) {
              $appendDom.append( $('<option value="' + result[i].idx + '">' + result[i].name + '</option>') );
            }
          }
        });
      }).on('click', '.btn-add-area', function() {
        var data = '<div class="row mt-1 pl-1"><div class="ml-2"><select name="area_sido[]" class="area-sido form-control">';
        data += '<option value="">시/도</option>';
        <?php foreach ($area_sido as $value): ?>
        data += '<option<?=$value['idx'] == $view['area_sido'] ? " selected" : ""?> value="<?=$value['idx']?>""><?=$value['name']?></option>';
        <?php endforeach; ?>
        data += '</select></div>';
        data += '<div class="ml-2"><select name="area_gugun[]" class="area-gugun form-control">';
        data += '<option value="">시/군/구</option>';
        <?php foreach ($area_gugun as $value): ?>
        data += '<option<?=$value['idx'] == $view['area_gugun'] ? " selected" : ""?> value="<?=$value['idx']?>""><?=$value['name']?></option>';
        <?php endforeach; ?>
        data += '</select></div></div>';
        $('.added-area').append(data);
      }).on('click', '.btn-club-geton', function() {
        // 승차위치 추가
        var $dom = $('.club-geton-text');
        $('.club-geton-added').append('<div class="row align-items-center mb-2 club-geton-element"><div class="ml-3"><input readonly type="text" name="club_geton[]" class="form-control" value="' + $dom.val() + '"></div><div class="ml-2"><button type="button" class="btn btn-primary btn-club-geton-delete">삭제</button></div></div>');
        $dom.val('');
      }).on('click', '.btn-club-geton-delete', function() {
        // 승차위치 삭제
        $(this).parent().parent().remove();
      }).on('click', '.btn-club-getoff', function() {
        // 하차위치 추가
        var $dom = $('.club-getoff-text');
        $('.club-getoff-added').append('<div class="row align-items-center mb-2 club-getoff-element"><div class="ml-3"><input readonly type="text" name="club_getoff[]" class="form-control" value="' + $dom.val() + '"></div><div class="ml-2"><button type="button" class="btn btn-primary btn-club-getoff-delete">삭제</button></div></div>');
        $dom.val('');
      }).on('click', '.btn-club-getoff-delete', function() {
        // 하차위치 삭제
        $(this).parent().remove();
      }).on('change', '.btn-all-check', function() {
        // 체크박스 제어
        var target = $(this).data('id');

        if ($(this).is(':checked') == true) {
          $('.' + target).prop('checked', true)
        } else {
          $('.' + target).prop('checked', false)
        }
      });
    </script>
