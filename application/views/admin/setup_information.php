<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div id="content" class="mb-5">
          <div class="sub-contents mt-4">
            <form id="setupForm" method="post" action="<?=BASE_URL?>/admin/setup_information_update" enctype="multipart/form-data">
              <h4>■ 기본정보</h4>
              <div class="row align-items-center mt-2">
                <div class="col-sm-2 font-weight-bold">단체명</div>
                <div class="col-sm-10"><input type="text" name="title" value="<?=$view['title']?>" class="form-control"></div>
              </div>
              <div class="row align-items-center mt-2">
                <div class="col-sm-2 font-weight-bold">지역<div class="mt-2"><button type="button" class="btn btn-sm btn-<?=$viewClub['main_color']?> btn-add-area mb-2">추가</button></div></div>
                <div class="col-sm-10 pl-1">
                  <?php if (empty($view['sido'])): ?>
                  <div class="row mt-1">
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
                    <div class="row mt-1">
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
                        </select>
                      </div>
                    </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
                  <div class="added-area"></div>
                </div>
              </div>
              <div class="row align-items-center mt-2">
                <div class="col-sm-2 font-weight-bold">홈페이지</div>
                <div class="col-sm-10"><input type="text" name="homepage" value="<?=$view['homepage']?>" class="form-control"></div>
              </div>
              <div class="row align-items-center mt-2">
                <div class="col-sm-2 font-weight-bold">연락처</div>
                <div class="col-sm-10"><input type="text" name="phone" value="<?=$view['phone']?>" class="form-control"></div>
              </div>
              <div class="row align-items-center mt-2">
                <div class="col-sm-2 font-weight-bold">사진</div>
                <div class="col-sm-10">
                  <div class="added-files mt-3">
                    <?php if ($view['photo'][0] != ''): foreach ($view['photo'] as $value): ?>
                    <img src="<?=PHOTO_URL?><?=$value?>" class="btn-photo-modal" data-photo="<?=$value?>">
                    <?php endforeach; endif; ?>
                  </div>
                  <input type="hidden" name="file" value="">
                  <input type="file" class="file d-none">
                  <button type="button" class="btn btn-sm btn-<?=$viewClub['main_color']?> btn-upload">사진 선택</button>
                </div>
              </div><br>

              <h4>■ 추가정보</h4>
              <div class="row align-items-center mt-2">
                <div class="col-sm-2 font-weight-bold">설립년도</div>
                <div class="col-sm-10">
                  <select name="establish" class="form-control">
                    <option value="">설립년도 선택</option>
                    <?php foreach (range(date('Y'), 1900) as $value): ?>
                    <option<?=$value == $view['establish'] ? " selected" : ""?> value="<?=$value?>"><?=$value?>년</option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="row align-items-center mt-2">
                <div class="col-sm-2 font-weight-bold">단체유형</div>
                <div class="col-sm-10">
                  <label class="mr-2"><input<?=in_array(1, $view['club_type']) ? " checked" : ""?> type="checkbox" name="club_type[]" value="1"> 친목</label>
                  <label class="mr-2"><input<?=in_array(2, $view['club_type']) ? " checked" : ""?> type="checkbox" name="club_type[]" value="2"> 안내</label>
                  <label class="mr-2"><input<?=in_array(3, $view['club_type']) ? " checked" : ""?> type="checkbox" name="club_type[]" value="3"> 동호회</label>
                  <label class="mr-2"><input<?=in_array(4, $view['club_type']) ? " checked" : ""?> type="checkbox" name="club_type[]" value="4"> 여행사</label>
                </div>
              </div>
              <div class="row align-items-center mt-2">
                <div class="col-sm-2 font-weight-bold">제공사항</div>
                <div class="col-sm-10">
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
                </div>
              </div>
              <div class="row align-items-center mt-2">
                <div class="col-sm-2 font-weight-bold">행사시기</div>
                <div class="col-sm-10">
                  운행주간 &nbsp;
                  <label class="mr-1"><input type="checkbox" class="btn-all-check" data-id="club_cycle"> 매주</label>
                  <label class="mr-1"><input<?=in_array(1, $view['club_cycle']) ? " checked" : ""?> type="checkbox" name="club_cycle[]" value="1" class="club_cycle"> 1주</label>
                  <label class="mr-1"><input<?=in_array(2, $view['club_cycle']) ? " checked" : ""?> type="checkbox" name="club_cycle[]" value="2" class="club_cycle"> 2주</label>
                  <label class="mr-1"><input<?=in_array(3, $view['club_cycle']) ? " checked" : ""?> type="checkbox" name="club_cycle[]" value="3" class="club_cycle"> 3주</label>
                  <label class="mr-1"><input<?=in_array(4, $view['club_cycle']) ? " checked" : ""?> type="checkbox" name="club_cycle[]" value="4" class="club_cycle"> 4주</label>
                  <label class="mr-1"><input<?=in_array(5, $view['club_cycle']) ? " checked" : ""?> type="checkbox" name="club_cycle[]" value="5" class="club_cycle"> 5주</label><br>
                  운행요일 &nbsp;
                  <label class="mr-1"><input type="checkbox" class="btn-all-check" data-id="club_week"> 매일</label>
                  <label class="mr-1"><input<?=in_array(1, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="1" class="club_week"> 월</label>
                  <label class="mr-1"><input<?=in_array(2, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="2" class="club_week"> 화</label>
                  <label class="mr-1"><input<?=in_array(3, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="3" class="club_week"> 수</label>
                  <label class="mr-1"><input<?=in_array(4, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="4" class="club_week"> 목</label>
                  <label class="mr-1"><input<?=in_array(5, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="5" class="club_week"> 금</label>
                  <label class="mr-1"><input<?=in_array(6, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="6" class="club_week"> 토</label>
                  <label class="mr-1"><input<?=in_array(7, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="7" class="club_week"> 일</label>
                  <label class="mr-1"><input<?=in_array(7, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="8" class="club_week"> 공휴일</label>
                </div>
              </div>
              <div class="row align-items-center mt-2">
                <div class="col-sm-2 font-weight-bold">승차위치</div>
                <div class="col-sm-10">
                  <div class="club-geton-added">
                    <?php foreach ($view['club_geton'] as $value): if ($value != ''): ?>
                    <div class="row align-items-center club-geton-element mb-2"><div class="ml-3"><input readonly type="text" name="club_geton[]" class="form-control" value="<?=$value?>"></div><div class="ml-2"><button type="button" class="btn btn-danger btn-club-geton-delete">삭제</button></div></div>
                    <?php endif; endforeach; ?>
                  </div>
                  <div class="row align-items-center mt-2">
                    <div class="ml-3"><input type="text" name="club_geton[]" class="club-geton-text form-control"></div>
                    <div class="ml-2"><button type="button" class="btn btn-<?=$viewClub['main_color']?> btn-club-geton">추가</button></div>
                  </div>
                </div>
              </div>
              <div class="row align-items-center mt-2">
                <div class="col-sm-2 font-weight-bold">하차위치</div>
                <div class="col-sm-10">
                  <div class="club-getoff-added">
                    <?php foreach ($view['club_getoff'] as $value): if ($value != ''): ?>
                    <div class="row align-items-center club-getoff-element mb-2"><div class="ml-3"><input readonly type="text" name="club_getoff[]" class="form-control" value="<?=$value?>"></div><div class="ml-2"><button type="button" class="btn btn-danger btn-club-getoff-delete">삭제</button></div></div>
                    <?php endif; endforeach; ?>
                  </div>
                  <div class="row align-items-center mt-2">
                    <div class="ml-3"><input type="text" name="club_getoff[]" class="club-getoff-text form-control"></div>
                    <div class="ml-2"><button type="button" class="btn btn-<?=$viewClub['main_color']?> btn-club-getoff">추가</button></div>
                  </div>
                </div>
              </div>
              <div class="area-button">
                <button type="submit" class="btn btn-<?=$viewClub['main_color']?>">확인합니다</button>
              </div>
            </form>
          </div>
        </div>

        <script type="text/javascript">
          $(document).on('change', '.area-sido', function() {
            var $dom = $(this);
            var parent = $dom.val();

            $.ajax({
              url: '/club/list_gugun',
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
            var data = '<div class="row mt-1"><div class="ml-2"><select name="area_sido[]" class="area-sido form-control">';
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
            $('.club-geton-added').append('<div class="row align-items-center mb-2 club-geton-element"><div class="ml-3"><input readonly type="text" name="club_geton[]" class="form-control" value="' + $dom.val() + '"></div><div class="ml-2"><button type="button" class="btn btn-danger btn-club-geton-delete">삭제</button></div></div>');
            $dom.val('');
          }).on('click', '.btn-club-geton-delete', function() {
            // 승차위치 삭제
            $(this).parent().parent().remove();
          }).on('click', '.btn-club-getoff', function() {
            // 하차위치 추가
            var $dom = $('.club-getoff-text');
            $('.club-getoff-added').append('<div class="row align-items-center mb-2 club-getoff-element"><div class="ml-3"><input readonly type="text" name="club_getoff[]" class="form-control" value="' + $dom.val() + '"></div><div class="ml-2"><button type="button" class="btn btn-danger btn-club-getoff-delete">삭제</button></div></div>');
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
          }).on('click', '.btn-upload', function() {
            $(this).prev().click();
          }).on('change', '.file', function() {
            // 파일 업로드
            var $btn = $('.btn-upload');
            var $dom = $(this);
            var formData = new FormData($('form')[0]);
            var maxSize = 20480000;
            var size = $dom[0].files[0].size;

            if (size > maxSize) {
              $dom.val('');
              $.openMsgModal('파일의 용량은 20MB를 넘을 수 없습니다.');
              return;
            }

            // 사진 형태 추가
            formData.append('file_obj', $dom[0].files[0]);

            $.ajax({
              url: '/file/upload',
              processData: false,
              contentType: false,
              data: formData,
              dataType: 'json',
              type: 'post',
              beforeSend: function() {
                $btn.css('opacity', '0.5').prop('disabled', true).text('업로드중.....');
                $dom.val('');
              },
              success: function(result) {
                $btn.css('opacity', '1').prop('disabled', false).text('사진 선택');
                $dom.val('');
                if (result.error == 1) {
                  $.openMsgModal(result.message);
                } else {
                  var $domFiles = $('input[name=file]');
                  $('.added-files').empty().append('<img src="' + result.message + '" class="btn-photo-modal" data-photo="' + result.filename + '">');
                  $('input[name=file]').val(result.filename);
                }
              }
            });
          });
        </script>
