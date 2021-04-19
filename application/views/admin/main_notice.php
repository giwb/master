<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <script type="text/javascript" src="/public/se2/js/HuskyEZCreator.js" charset="utf-8"></script>

        <?php if ($view['status'] != STATUS_PLAN) echo $headerMenuView; ?>
        <div id="content" class="mb-5">
          <div class="sub-contents">
            <h4 class="font-weight-bold m-0 p-0 pb-2"><?=viewStatus($view['status'])?> <?=$view['subject']?></h4>
            <?php if (!empty($view['type'])): ?><div class="ti"><strong>・유형</strong> : <?=$view['type']?></div><?php endif; ?>
            <div class="ti"><strong>・일시</strong> : <?=$view['startdate']?> (<?=calcWeek($view['startdate'])?>) <?=$view['starttime']?></div>
            <?php $view['cost'] = $view['cost_total'] == 0 ? $view['cost'] : $view['cost_total']; if (!empty($view['cost'])): ?>
            <?php if (!empty($view['sido'])): ?>
            <div class="ti"><strong>・지역</strong> : <?php foreach ($view['sido'] as $key => $value): if ($key != 0): ?>, <?php endif; ?><?=$value?> <?=!empty($view['gugun'][$key]) ? $view['gugun'][$key] : ''?><?php endforeach; ?></div>
            <?php endif; ?>
            <div class="ti"><strong>・요금</strong> : <?=number_format($view['cost_total'] == 0 ? $view['cost'] : $view['cost_total'])?>원 (<?=calcTerm($view['startdate'], $view['starttime'], $view['enddate'], $view['schedule'])?><?=!empty($view['distance']) ? ', ' . calcDistance($view['distance']) : ''?><?=!empty($view['options']) ? ', ' . getOptions($view['options']) : ''?><?=!empty($view['options_etc']) ? ', ' . $view['options_etc'] : ''?><?=!empty($view['options']) || !empty($view['options_etc']) ? ' 제공' : ''?><?=!empty($view['costmemo']) ? ', ' . $view['costmemo'] : ''?>)</div>
            <?php endif; ?>
            <?=!empty($view['content']) ? '<div class="ti"><strong>・코스</strong> : ' . nl2br($view['content']) . '</div>' : ''?>
            <?=!empty($view['kilometer']) ? '<div class="ti"><strong>・거리</strong> : ' . $view['kilometer'] . '</div>' : ''?>
            <div class="ti"><strong>・예약</strong> : <?=cntRes($view['idx'])?>명</div>
            <form id="formNotice" method="post" action="<?=BASE_URL?>/admin/main_notice_update" enctype="multipart/form-data" class="mb-0">
              <input type="hidden" name="noticeIdx" value="<?=$view['idx']?>">
              <div class="text-right">
                <button type="button" class="btn-custom btn-giwbblue btn-template-modal">기본 템플릿 불러오기</button>
              </div>
              <div class="area-notice">
                <div class="mt-3 border-top border-bottom">
                  <div class="row no-gutters align-items-center mt-3 mb-3">
                    <div class="col-3 col-sm-2 p-0 pl-2">대표 사진<br><small>※ 최적크기 : 500 x 300</small></div>
                    <div class="col-7 col-sm-7 p-0 pr-2">
                      <?php if (!empty($view['photo']) && file_exists(PHOTO_PATH . 'thumb_' . $view['photo'])): ?>
                      <a target="_blank" href="<?=PHOTO_URL . $view['photo']?>"><img src="<?=PHOTO_URL . 'thumb_' . $view['photo']?>"></a>
                      <input type="hidden" name="filename" value="<?=$view['photo']?>">
                      <?php else: ?>
                      <input type="file" name="photo">
                      <?php endif; ?>
                    </div>
                    <div class="col-2 col-sm-3 pr-2 text-right">
                      <?php if (!empty($view['photo'])): ?>
                      <button type="button" class="btn-custom btn-giwbred btn-notice-photo-delete">삭제</button>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <div id="sortable">
                  <?php foreach ($listNoticeDetail as $key => $value): ?>
                  <div class="item-notice pt-3">
                    <div class="row no-gutters align-items-center mb-2">
                      <div class="col-10 col-sm-11"><input type="hidden" name="idx[]" value="<?=$value['idx']?>"><input type="text" name="title[]" value="<?=$value['title']?>" class="form-control form-control-sm"></div>
                      <div class="col-2 col-sm-1 pr-2 text-right"><button type="button" class="btn-custom btn-giwbred btn-delete-notice-modal" data-idx="<?=$value['idx']?>">삭제</button></div>
                    </div>
                    <textarea name="content[]" rows="10" cols="100" id="content_<?=$key?>" class="se-content"><?=$value['content']?></textarea>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
              <div class="text-center mt-4">
                <div class="error-message p-2"></div>
                <button type="button" class="btn-custom btn-info btn-add-notice mr-2 pt-2 pb-2 pl-4 pr-4">항목추가</button>
                <a target="_blank" href="<?=BASE_URL?>/admin/main_notice_view/<?=$view['idx']?>"><button type="button" class="btn-custom btn-gray ml-2 mr-2 pt-2 pb-2 pl-4 pr-4">복사하기</button></a>
                <button type="button" class="btn-custom btn-giwb btn-notice-update ml-2 mr-4 pt-2 pb-2 pl-4 pr-4">저장하기</button>
              </div>
            </form>
          </div>
        </div>

        <div class="modal fade" id="noticeDeleteModal" tabindex="-1" role="dialog" aria-labelledby="noticeDeleteModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="smallmodalLabel">메세지</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body text-center">
                <p class="modal-message">정말로 삭제하시겠습니까?</p>
              </div>
              <div class="modal-footer">
                <input type="hidden" name="idx">
                <button type="button" class="btn btn-danger btn-delete-notice-submit">삭제합니다</button>
                <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
              </div>
            </div>
          </div>
        </div>

        <script type="text/javascript">
          //$('#sortable').disableSelection().sortable();

          var cnt = 0;
          $('.se-content').each(function(n) {
            cnt++;
          });

          var oEditors = new Array(cnt);
          function setEditor(n){
            nhn.husky.EZCreator.createInIFrame({
              oAppRef: oEditors[n],
              elPlaceHolder: 'content_' + n,
              sSkinURI: '/public/se2/SmartEditor2Skin_Club.html',
              fCreator: 'createSEditor2',
              htParams: { fOnBeforeUnload: function(){} }
            });
          }

          $(function(){
            for (var i = 0; i < oEditors.length; i++) {
              if (oEditors[i] == null) {
                oEditors[i] = [];
                setEditor(i);
              }
            }
          });

          $(document).on('click', '.btn-notice-update', function() {
            // 공지사항 등록
            for (var i = 0; i < oEditors.length; i++) {
              if (oEditors[i] != null) { oEditors[i][0].exec("UPDATE_CONTENTS_FIELD", []); }
            }

            var $btn = $(this);
            var $dom = $('#formNotice');
            var formData = new FormData($dom[0]);

            $('.se-content').each(function(n) {
              formData.append('content_' + n, $(this).val());
            });

            $.ajax({
              url: $dom.attr('action'),
              data: formData,
              processData: false,
              contentType: false,
              dataType: 'json',
              type: 'post',
              beforeSend: function() {
                $btn.css('opacity', '0.5').prop('disabled', true);
              },
              success: function(result) {
                if (result.error == 1) {
                  $btn.css('opacity', '1').prop('disabled', false);
                  $('.error-message').text(result.message).slideDown();
                  setTimeout(function() { $('.error-message').text('').slideUp(); }, 2000);
                } else {
                  location.reload();
                }
              }
            });
          }).on('click', '.btn-add-notice', function() {
            // 공지사항 항목 추가
            var cnt = 0;
            $('.se-content').each(function() { cnt++; });
            var content = '<div class="item-notice pt-3"><div class="row no-gutters align-items-center mb-2"><div class="col-10 col-sm-11"><input type="text" name="title[]" class="form-control form-control-sm"></div><div class="col-2 col-sm-1 pr-2 text-right"><button type="button" class="btn-custom btn-giwbred btn-delete-notice-modal">삭제</button></div></div><textarea name="content[]" rows="10" cols="100" id="content_' + cnt + '" class="se-content"></textarea></div>';
            $('.area-notice').append(content);

            oEditors[cnt] = [];
            setEditor(cnt);

            $('html, body').animate({ scrollTop: $(document).height() }, 800);
          }).on('click', '.btn-delete-notice-modal', function() {
            // 공지사항 항목 삭제 모달
            var $dom = $('#noticeDeleteModal');
            var idx = $(this).data('idx');
            $('input[name=idx]', $dom).val(idx);
            $dom.modal('show');
          }).on('click', '.btn-delete-notice-submit', function() {
            // 공지사항 항목 삭제
            var $btn = $(this);
            $.ajax({
              url: '/admin/main_notice_item_delete',
              data: 'noticeIdx=' + $('input[name=noticeIdx]').val() + '&idx=' + $('#noticeDeleteModal input[name=idx]').val(),
              dataType: 'json',
              type: 'post',
              beforeSend: function() {
                $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
              },
              success: function() {
                location.reload();
              }
            });
          }).on('click', '.btn-template-modal', function() {
            // 기본 템플릿 불러오기
            var $dom = $('#sortable');
            $dom.empty();
            $dom.append('<div class="item-notice pt-3"><div class="row no-gutters align-items-center mb-2"><div class="col-10 col-sm-11"><input type="text" name="title[]" value="기획의도" class="form-control form-control-sm"></div><div class="col-2 col-sm-1 pr-2 text-right"></div></div><textarea name="content[]" rows="10" cols="100" id="content_0" class="se-content"><p><strong style="color:#ee2323">☞ 겨울산행의 대명사.. 소백산 칼바람!!!</strong></p><p>겨울산행에 빼 놓을 수 없는 일정중의 하나로 소백산 산행을 꼽습니다.<br><br>선자령의 경우 산행 이정표가 발아래 눈밭에 묻힐 정도의 심설산행의 대표하는 겨울 산행지라면, 소백산은 능선의 바람과 함께 한쪽방향으로 길게 자라는 상고대의 위용을 실감할 수 있는 겨울산행지의 대명사격이라 할 수 있습니다.</p></textarea></div>');
            $dom.append('<div class="item-notice pt-3"><div class="row no-gutters align-items-center mb-2"><div class="col-10 col-sm-11"><input type="text" name="title[]" value="산행개요" class="form-control form-control-sm"></div><div class="col-2 col-sm-1 pr-2 text-right"></div></div><textarea name="content[]" rows="10" cols="100" id="content_1" class="se-content"><p><span style="color:#2980b9"><strong>☞ 경인웰빙 百山百所</strong><br><strong>☞ 산림청 선정 100대 명산</strong><br><strong>☞ 블랙야크 명산100</strong></span><br><span style="color:#d35400"><strong>☞ 죽기 전에 꼭 가봐야 할 국내 여행 1001 - 소백산 국립공원</strong></span></p><p><strong>[100대 명산 선정사유] 소백산 (小白山) 1,439 충북 단양군,경북 영주시</strong><br>국망봉에서 비로봉, 연화봉으로 이어지는 해발 1,300여m의 일대 산군으로 1,000m이상은 고원지대와 같은 초원을 이루고 있으며, 국망천과 낙동강 상류로 들어가는 죽계천이 시작되고 국립공원으로 지정(1987년)된 점 등을 고려하여 선정.</p><p><strong>☞ 산행거리 : 10.0km (약 4~5시간 +α 소요)</strong><br><strong>☞ 표고차 000m (출발지점 해발 000m, 최고점 해발 1,000m)</strong></p></p></textarea></div>');
            $dom.append('<div class="item-notice pt-3"><div class="row no-gutters align-items-center mb-2"><div class="col-10 col-sm-11"><input type="text" name="title[]" value="행선지 안내" class="form-control form-control-sm"></div><div class="col-2 col-sm-1 pr-2 text-right"></div></div><textarea name="content[]" rows="10" cols="100" id="content_2" class="se-content"><strong>○ 소백산 비로봉 1,439m (경북 영동, 충북 단양)</strong><p>겨울에 하얀눈을 머리에 이었다해서 소백산으로 불리며 대설원의 부드러움과 장쾌함이 돋보이는 산으로, 특히 \'소백산 칼바람\'은 대표적인 겨울 눈꽃산행의 대명사라 할 수 있다.</p><p>주봉인 비로봉(1,439.5m)에는 천연기념물인 주목이 군락을 이루고 있으며, 나라가 어려울 때 이 고장 선비들이 한양의 궁궐을 향해 임금과 나라의 태평을 기원하였다는 국망봉(1,421m)과, 소백산천문대가 있는 연화봉(1,394m), 그 옛날 산성의 흔적이 남아 있는 도솔봉(1,315m) 등 많은 산봉우리들이 연이어져 있다.</p><p>소백산 중턱에는 신라 시대 고찰 희방사와 비로사가 있으며, 희방사 입구에는 영남 제일의 희방폭포(28m)가 년중 시원한 물줄기로 피서객들을 즐겁게 맞고 있다.</p><p>특히 해마다 5월이면 철쭉꽃의 장관과 상수리나무 숲 터널은 소백산의 아름다움을 더해주고 있으며, 년중 6개월정도 백설로 뒤덮혀 있는 비로봉은 \'한국의 알프스\'로 불리고 있다.</p></textarea></div>');
            $dom.append('<div class="item-notice pt-3"><div class="row no-gutters align-items-center mb-2"><div class="col-10 col-sm-11"><input type="text" name="title[]" value="일정 안내" class="form-control form-control-sm"></div><div class="col-2 col-sm-1 pr-2 text-right"></div></div><textarea name="content[]" rows="10" cols="100" id="content_3" class="se-content"><p>06:00 계산역 출발<br>06:30 송내남부 출발<br>****************************************<br>10:20 현지도착 (휴게소 1회 정차)<br>10:30 산행출발<br>17:30 산행종료 &amp; 자유시간<br>18:00 현지출발(휴게소 2~3회 정차)<br>22:00 하차시작<br><span style="font-size:11px">※ 상기 일정은 당일 교통상황에 따라 일정이 지연될 수 있습니다.</span></p></textarea></div>');
            $dom.append('<div class="item-notice pt-3"><div class="row no-gutters align-items-center mb-2"><div class="col-10 col-sm-11"><input type="text" name="title[]" value="행사 안내" class="form-control form-control-sm"></div><div class="col-2 col-sm-1 pr-2 text-right"></div></div><textarea name="content[]" rows="10" cols="100" id="content_4" class="se-content"><p><strong>▣ 행 사 유 형</strong> : 산행 / 투어</p><p><strong>▣ 산 행 지</strong> : 팔공산 비로봉 &amp; 갓바위 (대구동구, 경북영천)</p><p><strong>▣ 산 행 일 시</strong> : 2021년 4월 11일(일요당일) 6:00 계산역 출발<br><span style="font-size:11px"><span style="color:#d35400">06:00 계산역 - 06:04 작전역 - 06:08 갈산역 - 06:12 부평구청역 - 06:15 삼산체육관 - 06:20 소풍 - 06:25 복사골 - 06:30 송내남부</span></span></p><p><strong>▣ 분 담 금</strong> : 40,000원 (왕복 700km 미만구간)<br><font color="#d35400"><span style="font-size: 11px;">국민은행 / 010-7271-3050 / 경인웰빙투어(최병준)</span></font></p><p><strong>▣ 기본준비물</strong> : 점심도시락, 식수, 간식(행동식), 방수방풍의<br><strong>▣ 선택준비물</strong> : 여벌옷, 슬리퍼</p><p><strong>▣ 제 공 사 항</strong> : 현행 거리두기 단계에서는 조식과 하산주를 제공하지 않습니다.</p></textarea></div>');
            $dom.append('<div class="item-notice pt-3"><div class="row no-gutters align-items-center mb-2"><div class="col-10 col-sm-11"><input type="text" name="title[]" value="코스 안내" class="form-control form-control-sm"></div><div class="col-2 col-sm-1 pr-2 text-right"></div></div><textarea name="content[]" rows="10" cols="100" id="content_5" class="se-content"><p><strong>☞ A코스 (약 13.5km / 약 6~7시간 소요) </strong><span style="color:#d35400">비로봉 + 갓바위 코스</span><br>수태골[주차] -(2.7)- 철탑[삼] -(0.5)- 서봉[갈] -(0.3)- 동봉[갈] -(0.1)- 비로봉(1,193m) -(back 0.1)- 동봉[갈] -(0.44)- 동봉(1,155m) -(2.7)- 신령재 -(2.8)- 은해봉 -(1.2)- 노적봉 -(0.3)- 선본재 -(0.3)- 관봉(갓바위) -(0.9)- 관암사 -(1.1)- 갓바위[주차]</p><p><strong>☞ A(-)코스 (약 7.2km / 약 4시간 소요) </strong><span style="color:#d35400">정상만 다녀오는 코스</span><br>수태골[주차] -(2.7)- 철탑[삼] -(0.5)- 서봉[갈] -(0.3)- 동봉[갈] -(0.1)- 비로봉(1,193m) -(back 0.1)- 동봉[갈] -(0.23)- 서봉[갈] -(0.04)- 수태골[갈] -(0.5)- 철탑[삼] -(2.7)- 수태골[주차]</p><p><strong>☞ B코스 (약 12.7km / 약 6~7시간 소요) </strong><span style="color:#d35400">편도 케이블카 이용 (비로봉 + 갓바위)</span><br>팔공산대형주차장(420m) -(0.4)- 케이블카승차장(470m) -<span style="color:#d35400">(케이블카 1.2km/7분)</span>- 케이블카하차장(820m) -(0.6)- 낙타봉 -(0.9)- 철탑[삼] -(0.5)- 서봉[갈] -(0.3)- 동봉[갈] -(0.1)- 비로봉(1,193m) -(back 0.1)- 동봉[갈] -(0.44)- 동봉(1,155m) -(2.7)- 신령재 -(2.8)- 은해봉 -(1.2)- 노적봉 -(0.3)- 선본재 -(0.3)- 관봉(갓바위) -(0.9)- 관암사 -(1.1)- 갓바위[주차]<br><br><strong>☞ B(-)코스 (산행 5.6km + 케이블카왕복 / 약 6~7시간 소요)</strong> <span style="color:#d35400">왕복 케이블카 이용 (정상만 다녀오는 코스)</span><br>팔공산대형[주차] -(0.4)- 케이블카하단(470m) -<span style="color:#d35400">(1.2km/7분)</span>- 케이블카상단(820m) -(0.6)- 낙타봉 -(0.9)- 철탑[삼] -(0.5)- 서봉[갈] -(0.3)- 동봉[갈] -(0.1)- 비로봉(1,193m) -(back 0.1)- 동봉[갈] -(0.23)- 서봉[갈] -(0.04)- 수태골[갈] -(0.5)- 철탑[삼] -(0.9)- 낙타봉 -(0.6)- 케이블카상단 -<span style="color:#d35400">(1.2km/7분)</span>- 케이블카하단 -(0.4)- 팔공산대형[주차]</p><p><span style="color:#d35400">※ 케이블카 이용요금 : 왕복 11,000원 / 편도 7,500원</span></p></textarea></div>');
            $dom.append('<div class="item-notice pt-3"><div class="row no-gutters align-items-center mb-2"><div class="col-10 col-sm-11"><input type="text" name="title[]" value="관광버스 및 개인 생활방역 안내" class="form-control form-control-sm"></div><div class="col-2 col-sm-1 pr-2 text-right"></div></div><textarea name="content[]" rows="10" cols="100" id="content_6" class="se-content"><p><strong>☞ 버스소독 및 손소독제 비치</strong><br>버스는 매일 기사님께서 직접 청소소독을 실시하고 있으며,<br>버스 내 손잡이 등 손이 자주 닿는 곳은 휴식시간을 통해 수시로 소독을 하게 되며,<br>손 소독제를 버스에 비치해 두고 있습니다.</p><p><strong>☞ 체온측정 및 발열증상</strong><br>아침 승차시 비접촉식 체온계로 체온을 측정하며, 발열증상이 있을 시 귀가 조치합니다.<br>발열이 있을 경우 당일에라도 여행 참여를 자제해주시기 바랍니다.<br>저희 경인웰빙은 당일 참석 못하신 분들께 산행비 전액을 환불해 드립니다.</p><p><strong>☞ 마스크</strong><br>차량 내에서는 마스크를 꼭 착용하셔야 하며,<br>차량 내에서는 서로를 위해 가급적 대화를 삼가해주시기 바랍니다.</p></textarea></div>');
            $dom.append('<div class="item-notice pt-3"><div class="row no-gutters align-items-center mb-2"><div class="col-10 col-sm-11"><input type="text" name="title[]" value="기타 안내" class="form-control form-control-sm"></div><div class="col-2 col-sm-1 pr-2 text-right"></div></div><textarea name="content[]" rows="10" cols="100" id="content_7" class="se-content"><p><strong>▣ </strong>출발전 3일전까지 <strong>15명 이상이면 출발이 확정</strong>됩니다.<br><strong>▣ 날씨에 관계없이 정상 출발하며, 현지에 비가 올경우 투어 형태의 대체일정으로 진행됩니다. (별도 공지)<br>▣ 출발확정 된 일정이 </strong>날씨 등의 사유로 예약인원이 줄더라도 <strong>최소 10명 이상이면 정상 출발합니다.</strong></p><p><strong>▣ 각주 안내</strong><br>[탐] : 탐방지원센터 / [삼] : 삼거리 / [갈] : 갈림길 / [입] : 입구 / [화] : 화장실 / [휴] : 휴게소 / [대] : 대피소</p></textarea></div>');

            var oEditors = new Array(8);
            function setEditor(n){
              nhn.husky.EZCreator.createInIFrame({
                oAppRef: oEditors[n],
                elPlaceHolder: 'content_' + n,
                sSkinURI: '/public/se2/SmartEditor2Skin_Club.html',
                fCreator: 'createSEditor2',
                htParams: { fOnBeforeUnload: function(){} }
              });
            }

            $(function(){
              for (var i = 0; i < oEditors.length; i++) {
                if (oEditors[i] == null) {
                  oEditors[i] = [];
                  setEditor(i);
                }
              }
            });
          });
        </script>
