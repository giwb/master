<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div id="content" class="mb-5">
          <div class="sub-contents mt-4">
            <form id="setupForm" method="post" action="<?=BASE_URL?>/admin/setup_information_update" enctype="multipart/form-data">
              <h4>■ 기본정보</h4>
              <div class="row align-items-center mt-2">
                <div class="col-sm-2 font-weight-bold">단체명</div>
                <div class="col-sm-10"><input type="text" name="title" value="<?=$view['title']?>" class="form-control form-control-sm"></div>
              </div>
              <div class="row align-items-center mt-2">
                <div class="col-sm-2 font-weight-bold">지역<div class="mt-2"><button type="button" class="btn btn-sm btn-sm btn-<?=$viewClub['main_color']?> btn-add-area mb-2">추가</button></div></div>
                <div class="col-sm-10 pl-1">
                  <?php if (empty($view['sido'])): ?>
                  <div class="row mt-1">
                    <div class="ml-2">
                      <select name="area_sido[]" class="area-sido form-control form-control-sm">
                        <option value=''>시/도</option>
                        <?php foreach ($area_sido as $value): ?>
                        <option<?=$value['idx'] == $view['area_sido'] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="ml-2">
                      <select name="area_gugun[]" class="area-gugun form-control form-control-sm">
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
                        <select name="area_sido[]" class="area-sido form-control form-control-sm">
                          <option value=''>시/도</option>
                          <?php foreach ($list_sido as $value): ?>
                          <option<?=$value['name'] == $val ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="ml-2">
                        <select name="area_gugun[]" class="area-gugun form-control form-control-sm">
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
                <div class="col-sm-10"><input type="text" name="homepage" value="<?=$view['homepage']?>" class="form-control form-control-sm"></div>
              </div>
              <div class="row align-items-center mt-2">
                <div class="col-sm-2 font-weight-bold">연락처</div>
                <div class="col-sm-10"><input type="text" name="phone" value="<?=$view['phone']?>" class="form-control form-control-sm"></div>
              </div>
              <div class="row align-items-center mt-2">
                <div class="col-sm-2 font-weight-bold">사진</div>
                <div class="col-sm-10">
                  <div class="added-files mt-3">
                    <?php if ($view['photo'][0] != ''): foreach ($view['photo'] as $value): ?>
                      <?php if (file_exists(PHOTO_PATH . $value)): ?><img src="<?=PHOTO_URL?><?=$value?>" class="btn-photo-modal" data-photo="<?=$value?>"><?php endif; ?>
                    <?php endforeach; endif; ?>
                  </div>
                  <input type="hidden" name="file" value="">
                  <input type="file" class="file d-none">
                  <button type="button" class="btn btn-sm btn-sm btn-<?=$viewClub['main_color']?> btn-upload">사진 선택</button>
                </div>
              </div><br>

              <h4>■ 추가정보</h4>
              <div class="row align-items-center mt-2">
                <div class="col-sm-2 font-weight-bold">설립년도</div>
                <div class="col-sm-10">
                  <select name="establish" class="form-control form-control-sm">
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
                    <div class="col-sm-3"><input type="text" name="club_option_text" class="form-control form-control-sm" value="<?=$view['club_option_text']?>"></div>
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
            </div><br>

            <h4>■ 승차위치</h4>
            <div class="row align-items-center border-top border-bottom pt-2 pb-2">
              <div class="col-sm-2 font-weight-bold">승차위치명 <span class="required">(필수)</span></div>
              <div class="col-sm-6 font-weight-bold">승차위치 설명</div>
              <div class="col-sm-2 font-weight-bold">출발 후 도착까지 몇분</div>
              <div class="col-sm-2 font-weight-bold">편집</div>
            </div>
            <div class="row align-items-center border-bottom pt-2 pb-2" data-type="geton">
              <div class="col-sm-2"><input type="text" maxlength="6" class="form-control form-control-sm input-ride-short"></div>
              <div class="col-sm-6"><input type="text" maxlength="50" class="form-control form-control-sm input-ride-title"></div>
              <div class="col-sm-2"><input type="number" maxlength="3" class="form-control form-control-sm input-ride-time"></div>
              <div class="col-sm-2"><input type="hidden" class="ride-idx"><button type="button" class="btn btn-sm btn-<?=$viewClub['main_color']?> btn-ride-add">추가</button></div>
            </div>
            <div class="sortable ride-added">
              <?php foreach ($view['club_geton'] as $key => $value): if ($value != ''): $geton = explode('|', $value); ?>
              <div class="row align-items-center border-bottom elm-ride" data-idx="<?=$key?>" data-type="geton">
                <div class="col-sm-2"><span class="elm-ride-short"><?=!empty($geton[0]) ? $geton[0] : ''?></span><input type="hidden" class="geton-short" value="<?=!empty($geton[0]) ? $geton[0] : ''?>"></div>
                <div class="col-sm-6"><span class="elm-ride-title"><?=!empty($geton[1]) ? $geton[1] : ''?></span><input type="hidden" class="geton-title" value="<?=!empty($geton[1]) ? $geton[1] : ''?>"></div>
                <div class="col-sm-2"><span class="elm-ride-time"><?=!empty($geton[2]) ? $geton[2] : ''?></span><input type="hidden" class="geton-time" value="<?=!empty($geton[2]) ? $geton[2] : ''?>"></div>
                <div class="col-sm-2 pt-2 pb-2"><button type="button" class="btn btn-sm btn-secondary btn-ride-edit">수정</button> <button type="button" class="btn btn-sm btn-danger btn-ride-delete">삭제</button></div>
              </div>
              <?php endif; endforeach; ?>
            </div>
            <div class="text-danger mt-2">
              ※ 마우스로 각 승차위치의 순서를 변경할 수 있습니다.<br>
              ※ 추가 또는 수정 후, 아래의 '확인합니다' 버튼을 눌러야만 저장됩니다.<br>
              ※ 승차위치명은 필수 항목이며, 변경했을 경우 해당 승차위치로 지정된 회원님들의 승차위치가 모두 사라지니 주의해주세요.
            </div><br>

            <h4>■ 하차위치</h4>
            <div class="row align-items-center border-top border-bottom pt-2 pb-2">
              <div class="col-sm-2 font-weight-bold">승차위치명 <span class="required">(필수)</span></div>
              <div class="col-sm-6 font-weight-bold">승차위치 설명</div>
              <div class="col-sm-2 font-weight-bold">출발 후 도착까지 몇분</div>
              <div class="col-sm-2 font-weight-bold">편집</div>
            </div>
            <div class="row align-items-center border-bottom pt-2 pb-2" data-type="getoff">
              <div class="col-sm-2"><input type="text" maxlength="6" class="form-control form-control-sm input-ride-short"></div>
              <div class="col-sm-6"><input type="text" maxlength="50" class="form-control form-control-sm input-ride-title"></div>
              <div class="col-sm-2"><input type="number" maxlength="3" class="form-control form-control-sm input-ride-time"></div>
              <div class="col-sm-2"><input type="hidden" class="ride-idx"><button type="button" class="btn btn-sm btn-<?=$viewClub['main_color']?> btn-ride-add">추가</button></div>
            </div>
            <div class="sortable ride-added">
              <?php foreach ($view['club_getoff'] as $key => $value): if ($value != ''): $getoff = explode('|', $value); ?>
              <div class="row align-items-center border-bottom elm-ride" data-idx="<?=$key?>" data-type="getoff">
                <div class="col-sm-2"><span class="elm-ride-short"><?=!empty($getoff[0]) ? $getoff[0] : ''?></span><input type="hidden" class="getoff-short" value="<?=!empty($getoff[0]) ? $getoff[0] : ''?>"></div>
                <div class="col-sm-6"><span class="elm-ride-title"><?=!empty($getoff[1]) ? $getoff[1] : ''?></span><input type="hidden" class="getoff-title" value="<?=!empty($getoff[1]) ? $getoff[1] : ''?>"></div>
                <div class="col-sm-2"><span class="elm-ride-time"><?=!empty($getoff[2]) ? $getoff[2] : ''?></span><input type="hidden" class="getoff-time" value="<?=!empty($getoff[2]) ? $getoff[2] : ''?>"></div>
                <div class="col-sm-2 pt-2 pb-2"><button type="button" class="btn btn-sm btn-secondary btn-ride-edit">수정</button> <button type="button" class="btn btn-sm btn-danger btn-ride-delete">삭제</button></div>
              </div>
              <?php endif; endforeach; ?>
            </div>
            <div class="text-danger mt-2">
              ※ 마우스로 각 하차위치의 순서를 변경할 수 있습니다.<br>
              ※ 추가 또는 수정 후, 아래의 '확인합니다' 버튼을 눌러야만 저장됩니다.<br>
              ※ 승차위치명은 필수 항목이며, 변경했을 경우 해당 승차위치로 지정된 회원님들의 승차위치가 모두 사라지니 주의해주세요.
            </div><br>

            <div class="area-button">
              <button type="button" class="btn btn-sm btn-<?=$viewClub['main_color']?> btn-setup-submit">확인합니다</button>
            </div>
          </form>
        </div>

        <script type="text/javascript">
          $(function() {
            $('.sortable').disableSelection().sortable();
          });
        </script>
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
            var data = '<div class="row mt-1"><div class="ml-2"><select name="area_sido[]" class="area-sido form-control form-control-sm">';
            data += '<option value="">시/도</option>';
            <?php foreach ($area_sido as $value): ?>
            data += '<option<?=$value['idx'] == $view['area_sido'] ? " selected" : ""?> value="<?=$value['idx']?>""><?=$value['name']?></option>';
            <?php endforeach; ?>
            data += '</select></div>';
            data += '<div class="ml-2"><select name="area_gugun[]" class="area-gugun form-control form-control-sm">';
            data += '<option value="">시/군/구</option>';
            <?php foreach ($area_gugun as $value): ?>
            data += '<option<?=$value['idx'] == $view['area_gugun'] ? " selected" : ""?> value="<?=$value['idx']?>""><?=$value['name']?></option>';
            <?php endforeach; ?>
            data += '</select></div></div>';
            $('.added-area').append(data);
          }).on('click', '.btn-ride-add', function() {
            // 승하차 위치 추가/수정
            var $dom = $(this).parent().parent();
            var idx = $dom.find('.ride-idx').val();
            var title = $dom.find('.input-ride-title').val();
            var short = $dom.find('.input-ride-short').val();
            var time = $dom.find('.input-ride-time').val();
            var type = $dom.data('type');

            // 전체 엘리먼트 개수 확인
            var elmIdx = 0;
            $dom.next().find('.elm-ride').each(function() {
              elmIdx++;
            });

            if (idx == '') {
              // 승하차위치 추가
              $dom.next().append('<div class="row align-items-center border-bottom elm-ride" data-idx="' + elmIdx + '"><div class="col-sm-6"><span class="elm-ride-title">' + title + '</span><input type="hidden" class="' + type + '-title" value="' + title + '"></div><div class="col-sm-2"><span class="elm-ride-short">' + short + '</span><input type="hidden" class="' + type + '-short" value="' + short + '"></div><div class="col-sm-2"><span class="elm-ride-time">' + time + '</span><input type="hidden" class="' + type + '-time" value="' + time  + '"></div><div class="col-sm-2 pt-2 pb-2"><button type="button" class="btn btn-sm btn-secondary btn-ride-edit">수정</button> <button type="button" class="btn btn-sm btn-danger btn-ride-delete">삭제</button></div>');
            } else {
              // 승하차위치 수정
              var $domRide = $dom.next().find('.elm-ride[data-idx=' + idx + ']');
              $('.elm-ride-title', $domRide).text(title); $('.' + type + '-title', $domRide).val(title);
              $('.elm-ride-short', $domRide).text(short); $('.' + type + '-short', $domRide).val(short);
              $('.elm-ride-time', $domRide).text(time); $('.' + type + '-time', $domRide).val(time);
              $('.btn-ride-add').text('추가');
            }
            $dom.find('.input-ride-title').val(''); $dom.find('.input-ride-short').val(''); $dom.find('.input-ride-time').val(''); $dom.find('.ride-idx').val('');
          }).on('click', '.btn-ride-edit', function() {
            // 승하차위치 수정 셋업
            var $dom = $(this).parent().parent();
            var $domPrev = $dom.parent().prev();
            var type = $dom.data('type');
            $domPrev.find('.input-ride-title').val($dom.find('.' + type + '-title').val()).focus();
            $domPrev.find('.input-ride-short').val($dom.find('.' + type + '-short').val());
            $domPrev.find('.input-ride-time').val($dom.find('.' + type + '-time').val());
            $domPrev.find('.ride-idx').val($dom.data('idx'));
            $domPrev.find('.btn-ride-add').text('수정');
          }).on('click', '.btn-ride-delete', function() {
            // 승하차위치 삭제
            $(this).parent().parent().remove();
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
          }).on('click', '.btn-setup-submit', function() {
            var $btn = $(this);
            var formData = new FormData($('#setupForm')[0]);

            $('.geton-title').each(function() { formData.append('geton_title[]', $(this).val()); });
            $('.geton-short').each(function() { formData.append('geton_short[]', $(this).val()); });
            $('.geton-time').each(function() { formData.append('geton_time[]', $(this).val()); });
            $('.getoff-title').each(function() { formData.append('getoff_title[]', $(this).val()); });
            $('.getoff-short').each(function() { formData.append('getoff_short[]', $(this).val()); });
            $('.getoff-time').each(function() { formData.append('getoff_time[]', $(this).val()); });

            $.ajax({
              url: '/admin/setup_information_update',
              processData: false,
              contentType: false,
              data: formData,
              dataType: 'json',
              type: 'post',
              beforeSend: function() {
                $btn.css('opacity', '0.5').prop('disabled', true);
              },
              success: function(result) {
                $btn.css('opacity', '1').prop('disabled', false);
                location.reload();
              }
            });
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
