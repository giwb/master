<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <section class="container">
      <div class="row">
        <div class="col-sm-3 pl-0 nav-place d-none d-sm-block">
          <a href="/club" class="d-block">전체보기</a>
          <a href="/club/?s=area_sido&k=11000" class="d-block">서울특별시</a>
          <a href="/club/?s=area_sido&k=12000" class="d-block">부산광역시</a>
          <a href="/club/?s=area_sido&k=13000" class="d-block">대구광역시</a>
          <a href="/club/?s=area_sido&k=14000" class="d-block">인천광역시</a>
          <a href="/club/?s=area_sido&k=15000" class="d-block">광주광역시</a>
          <a href="/club/?s=area_sido&k=16000" class="d-block">대전광역시</a>
          <a href="/club/?s=area_sido&k=17000" class="d-block">울산광역시</a>
          <a href="/club/?s=area_sido&k=18000" class="d-block">세종특별자치시</a>
          <a href="/club/?s=area_sido&k=19000" class="d-block">경기도</a>
          <a href="/club/?s=area_sido&k=20000" class="d-block">강원도</a>
          <a href="/club/?s=area_sido&k=21000" class="d-block">충청북도</a>
          <a href="/club/?s=area_sido&k=22000" class="d-block">충청남도</a>
          <a href="/club/?s=area_sido&k=23000" class="d-block">전라북도</a>
          <a href="/club/?s=area_sido&k=24000" class="d-block">전라남도</a>
          <a href="/club/?s=area_sido&k=25000" class="d-block">경상북도</a>
          <a href="/club/?s=area_sido&k=26000" class="d-block">경상남도</a>
          <a href="/club/?s=area_sido&k=27000" class="d-block">제주도</a>
        </div>
        <div class="col-12 col-sm-9">
          <div class="mt-3 d-none d-sm-block"></div>
          <div class="row align-items-center border-bottom mb-3 pb-3">
            <div class="col-sm-7 pl-0">
              <h2 class="m-0">산악회 등록</h2>
            </div>
            <div class="col-sm-5 text-right pr-0">
              <a href="/club"><button class="btn btn-sm btn-secondary btn-back">목록으로</button></a>
            </div>
          </div>
          <form id="myForm" method="post" action="/club/insert" enctype="multipart/form-data">
            <h3 class="font-weight-bold border-bottom mt-4 mb-3 pb-3">■ 기본정보</h3>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-3 font-weight-bold">산악회/단체명 <span class="text-require">(*)</span></div>
              <div class="col-sm-9"><input type="text" name="title" value="<?=$view['title']?>" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-3 font-weight-bold">지역 <span class="text-require">(*)</span></div>
              <div class="col-sm-9">
                <button type="button" class="btn btn-sm btn-info btn-add-area">추가</button><br>
                <?php if (empty($view['sido'])): ?>
                <div class="row mt-2">
                  <div class="col pl-0 pr-1">
                    <select name="area_sido[]" class="form-control form-control-sm area-sido">
                      <option value=''>시/도</option>
                      <?php foreach ($area_sido as $value): ?>
                      <option<?=$value['idx'] == $view['area_sido'] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col pl-1 pr-0">
                    <select name="area_gugun[]" class="form-control form-control-sm area-gugun">
                      <option value=''>시/군/구</option>
                      <?php foreach ($area_gugun as $value): ?>
                      <option<?=$value['idx'] == $view['area_gugun'] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <?php else: foreach ($view['sido'] as $key => $val): ?>
                <div class="row mt-2">
                  <div class="col pl-0 pr-1">
                    <select name="area_sido[]" class="form-control form-control-sm area-sido">
                      <option value=''>시/도</option>
                      <?php foreach ($list_sido as $value): ?>
                      <option<?=$value['name'] == $val ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col pl-1 pr-0">
                    <select name="area_gugun[]" class="form-control form-control-sm area-gugun">
                      <option value=''>시/군/구</option>
                      <?php foreach ($list_gugun[$key] as $value): ?>
                      <option<?=$value['name'] == $view['gugun'][$key] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <?php endforeach; endif; ?>
                <div class="added-area"></div>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-3 font-weight-bold">사용할 도메인 <span class="text-require">(*)</span></div>
              <div class="col-sm-9">
                <div class="row align-items-center">
                  <div class="col-6 col-sm-3 p-1 text-right"><?=base_url()?></div>
                  <div class="col-6 col-sm-9 p-0 check-domain"><input type="text" name="domain" value="<?=$view['domain']?>" class="form-control form-control-sm"></div>
                </div>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-3 font-weight-bold">홈페이지 링크</div>
              <div class="col-sm-9"><input type="text" name="homepage" value="<?=$view['homepage']?>" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-3 font-weight-bold">연락처</div>
              <div class="col-sm-9"><input type="text" name="phone" value="<?=$view['phone']?>" class="form-control form-control-sm"></div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-12 font-weight-bold">산악회 소개</div>
              <div class="col-sm-12 mt-3">
                <textarea name="about" id="about"><?=!empty($view['about']) ? $view['about'] : ''?></textarea>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-3 font-weight-bold">디자인 <span class="text-require">(*)</span></div>
              <div class="col-sm-9">
                <div class="row">
                  <div class="col-2 pl-0 pr-5 text-center"><label><input type="radio" name="main_design" value="1" checked> 1번</label></div>
                  <div class="col-2 pl-0 pr-5 text-center"><label><input type="radio" name="main_design" value="2"> 2번</label></div>
                </div>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-3 font-weight-bold">기본 색상 <span class="text-require">(*)</span></div>
              <div class="col-sm-9">
                <div class="row">
                  <div class="col-2 pl-0 pr-5"><label class="border p-3 bg-primary"><input type="radio" name="main_color" value="primary" checked></label></div>
                  <div class="col-2 pl-0 pr-5"><label class="border p-3 bg-danger"><input type="radio" name="main_color" value="danger"></label></div>
                  <div class="col-2 pl-0 pr-5"><label class="border p-3 bg-warning"><input type="radio" name="main_color" value="warning"></label></div>
                  <div class="col-2 pl-0 pr-5"><label class="border p-3 bg-info"><input type="radio" name="main_color" value="info"></label></div>
                  <div class="col-2 pl-0 pr-5"><label class="border p-3 bg-success"><input type="radio" name="main_color" value="success"></label></div>
                  <div class="col-2 pl-0 pr-5"><label class="border p-3 bg-secondary"><input type="radio" name="main_color" value="default"></label></div>
                </div>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-3 font-weight-bold">사진</div>
              <div class="col-sm-9">
                <input type="hidden" name="file_<?=TYPE_MAIN?>" value="">
                <input type="file" name="file" class="file d-none" data-type="<?=TYPE_MAIN?>">
                <button type="button" class="btn btn-sm btn-primary btn-upload">사진 선택</button>
                <div class="added-files type<?=TYPE_MAIN?>">
                  <?php if ($view['photo'][0] != ''): foreach ($view['photo'] as $value): ?>
                  <img src="<?=PHOTO_URL . $value?>" class="btn-photo-modal" data-photo="<?=$value?>">
                  <?php endforeach; endif; ?>
                </div>
              </div>
            </div>
            <h3 class="font-weight-bold border-bottom mt-4 mb-3 pb-3">■ 추가정보</h3>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-3 font-weight-bold">설립년도</div>
              <div class="col-sm-9">
                <select name="establish" class="form-control form-control-sm">
                  <option value="">설립년도 선택</option>
                  <?php foreach (range(date('Y'), 1900) as $value): ?>
                  <option<?=$value == $view['establish'] ? " selected" : ""?> value="<?=$value?>"><?=$value?>년</option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-3 font-weight-bold">단체 유형</div>
              <div class="col-sm-9">
                <label class="m-0 mr-3"><input<?=in_array(1, $view['club_type']) ? " checked" : ""?> type="checkbox" name="club_type[]" value="1"> 친목</label>
                <label class="m-0 mr-3"><input<?=in_array(2, $view['club_type']) ? " checked" : ""?> type="checkbox" name="club_type[]" value="2"> 안내</label>
                <label class="m-0 mr-3"><input<?=in_array(3, $view['club_type']) ? " checked" : ""?> type="checkbox" name="club_type[]" value="3"> 동호회</label>
                <label class="m-0 mr-3"><input<?=in_array(4, $view['club_type']) ? " checked" : ""?> type="checkbox" name="club_type[]" value="4"> 여행사</label>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-3 font-weight-bold">제공사항</div>
              <div class="col-sm-9">
                <label class="m-0 mr-3"><input<?=in_array(1, $view['club_option']) ? " checked" : ""?> type="checkbox" name="club_option[]" value="1"> 조식</label>
                <label class="m-0 mr-3"><input<?=in_array(2, $view['club_option']) ? " checked" : ""?> type="checkbox" name="club_option[]" value="2"> 중식</label>
                <label class="m-0 mr-3"><input<?=in_array(3, $view['club_option']) ? " checked" : ""?> type="checkbox" name="club_option[]" value="3"> 석식</label>
                <label class="m-0 mr-3"><input<?=in_array(4, $view['club_option']) ? " checked" : ""?> type="checkbox" name="club_option[]" value="4"> 하산주</label>
                <label class="m-0 mr-3"><input<?=in_array(5, $view['club_option']) ? " checked" : ""?> type="checkbox" name="club_option[]" value="5"> 산행지도</label>
                <label class="m-0 mr-3"><input<?=in_array(6, $view['club_option']) ? " checked" : ""?> type="checkbox" name="club_option[]" value="6"> 기념품</label><br>
                <div class="row align-items-center mt-3">
                  <div class="col-sm-2 p-0">추가사항입력</div>
                  <div class="col-sm-10 p-0"><input type="text" name="club_option_text" class="form-control form-control-sm" value="<?=$view['club_option_text']?>"></div>
                </div>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-3 font-weight-bold">행사시기</div>
              <div class="col-sm-9">
                <div class="mt-1 mb-1">
                  <label class="m-0 mr-2">운행주간</label>
                  <label class="m-0 mr-2"><input type="checkbox" class="btn-all-check" data-id="club_cycle"> 매주</label>
                  <label class="m-0 mr-2"><input<?=in_array(1, $view['club_cycle']) ? " checked" : ""?> type="checkbox" name="club_cycle[]" value="1" class="club_cycle"> 1주</label>
                  <label class="m-0 mr-2"><input<?=in_array(2, $view['club_cycle']) ? " checked" : ""?> type="checkbox" name="club_cycle[]" value="2" class="club_cycle"> 2주</label>
                  <label class="m-0 mr-2"><input<?=in_array(3, $view['club_cycle']) ? " checked" : ""?> type="checkbox" name="club_cycle[]" value="3" class="club_cycle"> 3주</label>
                  <label class="m-0 mr-2"><input<?=in_array(4, $view['club_cycle']) ? " checked" : ""?> type="checkbox" name="club_cycle[]" value="4" class="club_cycle"> 4주</label>
                  <label class="m-0 mr-2"><input<?=in_array(5, $view['club_cycle']) ? " checked" : ""?> type="checkbox" name="club_cycle[]" value="5" class="club_cycle"> 5주</label>
                </div>
                <div class="mt-1 mb-1">
                  <label class="m-0 mr-2">운행요일</label>
                  <label class="m-0 mr-2"><input type="checkbox" class="btn-all-check" data-id="club_week"> 매일</label>
                  <label class="m-0 mr-2"><input<?=in_array(1, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="1" class="club_week"> 월</label>
                  <label class="m-0 mr-2"><input<?=in_array(2, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="2" class="club_week"> 화</label>
                  <label class="m-0 mr-2"><input<?=in_array(3, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="3" class="club_week"> 수</label>
                  <label class="m-0 mr-2"><input<?=in_array(4, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="4" class="club_week"> 목</label>
                  <label class="m-0 mr-2"><input<?=in_array(5, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="5" class="club_week"> 금</label>
                  <label class="m-0 mr-2"><input<?=in_array(6, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="6" class="club_week"> 토</label>
                  <label class="m-0 mr-2"><input<?=in_array(7, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="7" class="club_week"> 일</label>
                  <label class="m-0 mr-2"><input<?=in_array(7, $view['club_week']) ? " checked" : ""?> type="checkbox" name="club_week[]" value="8" class="club_week"> 공휴일</label><br>
                </div>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-3 font-weight-bold">승차위치</div>
              <div class="col-sm-9">
                <?php foreach ($view['club_geton'] as $value): if ($value != ''): ?>
                <div class="row align-items-center">
                  <div class="col-10 col-sm-11 pl-0"><input readonly type="text" name="club_geton[]" class="form-control form-control-sm" value="<?=$value?>"></div>
                  <div class="col-2 col-sm-1 p-0"><button type="button" class="btn-club-geton-delete">삭제</button></div>
                </div>
                <?php endif; endforeach; ?>
                <div class="row align-items-center">
                  <div class="col-10 col-sm-11 pl-0"><input type="text" name="club_geton[]" class="form-control form-control-sm club-geton-text"></div>
                  <div class="col-2 col-sm-1 p-0"><button type="button" class="btn btn-sm btn-info btn-club-geton">추가</button></div>
                </div>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-3 font-weight-bold">하차위치</div>
              <div class="col-sm-9">
                <?php foreach ($view['club_getoff'] as $value): if ($value != ''): ?>
                <div class="row align-items-center">
                  <div class="col-10 col-sm-11 pl-0"><input readonly type="text" name="club_getoff[]" class="form-control form-control-sm" value="<?=$value?>"></div>
                  <div class="col-2 col-sm-1 p-0"><button type="button" class="btn-club-getoff-delete">삭제</button></div>
                </div>
                <?php endif; endforeach; ?>
                <div class="row align-items-center">
                  <div class="col-10 col-sm-11 pl-0"><input type="text" name="club_getoff[]" class="form-control form-control-sm club-getoff-text"></div>
                  <div class="col-2 col-sm-1 p-0"><button type="button" class="btn btn-sm btn-info btn-club-getoff">추가</button></div>
                </div>
              </div>
            </div>
            <div class="text-center">
              <input type="hidden" name="idx" value="<?=$view['idx']?>">
              <input type="hidden" name="page" value="club">
              <button type="button" class="btn btn-primary btn-submit"><?=$view['idx'] == "" ? '등록합니다' : '수정합니다'?></button>
            </div>
          </form>
        </div>
      </section>

      <script language="javascript">
      CKEDITOR.replace('about');
      function goPopup() {
        var pop = window.open("/public/area_popup.php", "pop", "width=570, height=420, scrollbars=yes, resizable=yes"); 
      }
      function jusoCallBack(roadFullAddr,roadAddrPart1,addrDetail,roadAddrPart2,engAddr, jibunAddr, zipNo, admCd, rnMgtSn, bdMgtSn, detBdNmList, bdNm, bdKdcd, siNm, sggNm, emdNm, liNm, rn, udrtYn, buldMnnm, buldSlno, mtYn, lnbrMnnm, lnbrSlno, emdNo) {
        document.myForm.area_sido.value = siNm;
        document.myForm.area_gugun.value = sggNm;
        document.myForm.area_dong.value = emdNm;
      }
      $(document).on('click', '.btn-add-area', function() {
        var data = '<div class="row mt-2"><div class="col pl-0 pr-1"><select name="area_sido[]" class="form-control area-sido">';
        data += '<option value="">시/도</option>';
      <?php foreach ($area_sido as $value): ?>
        data += '<option<?=$value['idx'] == $view['area_sido'] ? " selected" : ""?> value="<?=$value['idx']?>""><?=$value['name']?></option>';
      <?php endforeach; ?>
        data += '</select></div><div class="col pl-1 pr-0">';
        data += '<select name="area_gugun[]" class="form-control area-gugun">';
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
          url: '/place/list_gugun',
          data: 'parent=' + parent,
          dataType: 'json',
          type: 'post',
          success: function(result) {
            $dom.parent().parent().find('.area-gugun').empty().append( $('<option value="">시/군/구</option>') );
            for (var i=0; i<result.length; i++) {
              $dom.parent().parent().find('.area-gugun').append( $('<option value="' + result[i].idx + '">' + result[i].name + '</option>') );
            }
          }
        });
      }).on('click', '.btn-submit', function() {
        if ($('input[name=title]').val() == '') {
          $.openMsgModal('산악회/단체명은 꼭 입력해주세요.');
          return false;
        }
        if ($('.area-sido').val() == '' || $('.area-gugun').val() == '') {
          $.openMsgModal('지역은 꼭 선택해주세요.');
          return false;
        }
        if ($('input[name=domain]').val() == '') {
          $.openMsgModal('사용할 도메인은 꼭 입력해주세요.');
          return false;
        }
        if ($('.check-domain img').hasClass('check-domain-complete') == false) {
          $.openMsgModal('도메인을 확인해주세요.');
          return false;
        }
        $(this).css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요...');
        $('#myForm').submit();
      }).on('blur', '.check-domain', function() {
        // 도메인 중복 체크
        var $dom = $(this);
        var domain = $('input[name=domain]').val();
        var pattern = /^[a-z0-9]{3,20}$/;

        if (domain != '') {
          if (domain.length < 3 || domain.length > 20 || !pattern.test(domain) || domain.search(/\s/) != -1) {
            $.openMsgModal('도메인은 띄어쓰기 없이<br>3자 ~ 20자 이하의 영어 소문자만 가능합니다.');
            $(this).val('');
            return false;
          }
          $.ajax({
            url: '/club/check_domain',
            data: 'domain=' + domain,
            dataType: 'json',
            type: 'post',
            success: function(result) {
              $('img', $dom).remove();
              $dom.append(result.message);
            }
          });
        } else {
          $('img', $dom).remove();
        }
      });
      </script>
