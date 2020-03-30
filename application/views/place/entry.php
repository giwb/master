<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <section class="container">
      <div class="row">
        <div class="col-sm-3 pl-0 nav-place d-none d-sm-block">
          <?=$commonMenu?>
        </div>
        <div class="col-12 col-sm-9">
          <div class="mt-3 d-none d-sm-block"></div>
          <div class="row align-items-center border-bottom mb-3 pb-3">
            <div class="col-8"><h2 class="m-0">여행정보 <?=empty($view['idx']) ? '등록' : '수정'?></h2></div>
            <div class="col-4 text-right"><a href="/place"><button type="button" class="btn btn-sm btn-secondary btn-back">목록으로</button></a></div>
          </div>
          <form id="myForm" method="post" action="/place/<?=empty($view['idx']) ? 'insert' : 'update'?>">
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2 font-weight-bold">여행지 제목 <span class="text-require">(*)</span></div>
              <div class="col-sm-10"><input type="text" name="title" value="<?=$view['title']?>" class="form-control"></div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2 font-weight-bold">지역 <span class="text-require">(*)</span></div>
              <div class="col-sm-10">
                <button type="button" class="btn btn-sm btn-primary btn-add-area mb-2">추가</button><br>
                <?php if (empty($view['sido'])): ?>
                <div class="row">
                  <div class="col p-0 pr-3">
                    <select name="area_sido[]" class="form-control area-sido">
                      <option value=''>시/도</option>
                      <?php foreach ($area_sido as $value): ?>
                      <option<?=$value['idx'] == $view['area_sido'] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col p-0">
                    <select name="area_gugun[]" class="form-control area-gugun">
                      <option value=''>시/군/구</option>
                      <?php foreach ($area_gugun as $value): ?>
                      <option<?=$value['idx'] == $view['area_gugun'] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <?php else: foreach ($view['sido'] as $key => $val): ?>
                <div class="row">
                  <div class="col p-0 pr-3">
                    <select name="area_sido[]" class="form-control area-sido">
                      <option value=''>시/도</option>
                      <?php foreach ($list_sido as $value): ?>
                      <option<?=$value['name'] == $val ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col p-0">
                    <select name="area_gugun[]" class="form-control area-gugun">
                      <option value=''>시/군/구</option>
                      <?php foreach ($list_gugun[$key] as $value): ?>
                      <option<?=$value['name'] == $view['gugun'][$key] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <?php endforeach; endif; ?>
                <div class="added-area">
                </div>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2 font-weight-bold">여행 포인트</div>
              <div class="col-sm-10">
                <label class="m-0 mr-3"><input<?=in_array('point1', $view['point']) ? " checked" : ""?> type="checkbox" name="point[]" value="point1"> 문화재</label>
                <label class="m-0 mr-3"><input<?=in_array('point2', $view['point']) ? " checked" : ""?> type="checkbox" name="point[]" value="point2"> 천연기념물</label>
                <label class="m-0 mr-3"><input<?=in_array('point3', $view['point']) ? " checked" : ""?> type="checkbox" name="point[]" value="point3"> 보물</label>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2 font-weight-bold">여행 분류</div>
              <div class="col-sm-10">
                <label class="m-0 mr-3"><input<?=in_array('type1', $view['type']) ? " checked" : ""?> type="checkbox" name="type[]" value="type1" class="type1"> 산림청 선정 100대 명산</label>
                <label class="m-0 mr-3"><input<?=in_array('type2', $view['type']) ? " checked" : ""?> type="checkbox" name="type[]" value="type2"> 블랙야크 명산100</label>
                <label class="m-0 mr-3"><input<?=in_array('type3', $view['type']) ? " checked" : ""?> type="checkbox" name="type[]" value="type3"> 죽기전에 꼭 가봐야 할 국내여행 1001</label>
                <label class="m-0 mr-3"><input<?=in_array('type4', $view['type']) ? " checked" : ""?> type="checkbox" name="type[]" value="type4"> 백두대간</label>
                <label class="m-0 mr-3"><input<?=in_array('type5', $view['type']) ? " checked" : ""?> type="checkbox" name="type[]" value="type5"> 도보트레킹</label>
                <label class="m-0 mr-3"><input<?=in_array('type6', $view['type']) ? " checked" : ""?> type="checkbox" name="type[]" value="type6"> 투어</label>
                <label class="m-0 mr-3"><input<?=in_array('type7', $view['type']) ? " checked" : ""?> type="checkbox" name="type[]" value="type7"> 섬</label>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2 font-weight-bold">선정 사유</div>
              <div class="col-sm-10"><textarea name="reason" id="reason"><?=$view['reason']?></textarea></div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2 font-weight-bold">해발</div>
              <div class="col-sm-10 row align-items-center">
                <div class="col-sm-6"><input type="number" name="altitude" value="<?=$view['altitude']?>" class="form-control"></div>
                <div class="col-sm-6">※ 숫자로만 입력해주세요.</div>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2 font-weight-bold">여행지 소개</div>
              <div class="col-sm-10"><textarea name="content" id="content"><?=$view['content']?></textarea></div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2 font-weight-bold">주변 관광지</div>
              <div class="col-sm-10"><textarea name="around" id="around"><?=$view['around']?></textarea></div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2 font-weight-bold">산행 코스</div>
              <div class="col-sm-10"><textarea name="course" id="course"><?=$view['course']?></textarea></div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2 font-weight-bold">대표 사진 <span class="require">(*)</span></div>
              <div class="col-sm-10">
                <input type="hidden" name="file_<?=TYPE_MAIN?>">
                <input type="file" name="file" class="file" data-type="<?=TYPE_MAIN?>">
                <div class="added-files type<?=TYPE_MAIN?>">
                  <?php
                    if ($view['photo'][0] != ''):
                      foreach ($view['photo'] as $value):
                        if ($value['type'] == 1):
                  ?>
                  <img src="<?=PHOTO_URL . $value['filename']?>" class="w-100 btn-photo-modal" data-photo="<?=$value['filename']?>">
                  <?php
                        endif;
                      endforeach;
                    endif;
                  ?>
                </div>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2 font-weight-bold">추가 사진 <span class="require">(*)</span></div>
              <div class="col-sm-10">
                <input type="hidden" name="file_<?=TYPE_ADDED?>">
                <input type="file" name="file" class="file" data-type="<?=TYPE_ADDED?>">
                <div class="added-files type<?=TYPE_ADDED?>">
                  <?php
                    if ($view['photo'][0] != ''):
                      foreach ($view['photo'] as $value):
                        if ($value['type'] == 2):
                  ?>
                  <img src="<?=PHOTO_URL . $value['filename']?>" class="w-100 btn-photo-modal" data-photo="<?=$value['filename']?>">
                  <?php
                        endif;
                      endforeach;
                    endif;
                  ?>
                </div>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2 font-weight-bold">지도</div>
              <div class="col-sm-10">
                <input type="hidden" name="file_<?=TYPE_MAP?>">
                <input type="file" name="file" class="file" data-type="<?=TYPE_MAP?>">
                <div class="added-files type<?=TYPE_MAP?>">
                  <?php
                    if ($view['photo'][0] != ''):
                      foreach ($view['photo'] as $value):
                        if ($value['type'] == 3):
                  ?>
                  <img src="<?=PHOTO_URL . $value['filename']?>" class="w-100 btn-photo-modal" data-photo="<?=$value['filename']?>">
                  <?php
                        endif;
                      endforeach;
                    endif;
                  ?>
                </div>
              </div>
            </div>
            <div class="text-center">
              <input type="hidden" name="idx" value="<?=$view['idx']?>">
              <input type="hidden" name="page" value="place">
              <button type="submit" class="btn btn-primary"><?=$view['idx'] == "" ? '등록합니다' : '수정합니다'?></button>
            </div>
          </form>
        </div>
      </div>
    </section>

    <script language='javascript'>
      CKEDITOR.replace('reason');
      CKEDITOR.replace('content');
      CKEDITOR.replace('around');
      CKEDITOR.replace('course');

      function goPopup() {
        var pop = window.open('/public/area_popup.php', 'pop', 'width=570, height=420, scrollbars=yes, resizable=yes'); 
      }

      function jusoCallBack(roadFullAddr,roadAddrPart1,addrDetail,roadAddrPart2,engAddr, jibunAddr, zipNo, admCd, rnMgtSn, bdMgtSn, detBdNmList, bdNm, bdKdcd, siNm, sggNm, emdNm, liNm, rn, udrtYn, buldMnnm, buldSlno, mtYn, lnbrMnnm, lnbrSlno, emdNo) {
        document.myForm.area_sido.value = siNm;
        document.myForm.area_gugun.value = sggNm;
      }

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
