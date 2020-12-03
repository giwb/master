<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <?php if ($view['status'] != STATUS_PLAN) echo $headerMenuView; ?>
        <div id="content" class="mb-5">
          <div class="sub-contents">
              <h2 class="m-0 p-0 pb-2"><b><?=viewStatus($view['status'])?></b> <?=$view['subject']?></h2>
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
            <form id="myForm" method="post" action="<?=BASE_URL?>/admin/main_notice_update" enctype="multipart/form-data" class="mb-0">
              <input type="hidden" name="noticeIdx" value="<?=$view['idx']?>">
              <div class="row align-items-center">
                <div class="col-sm-3 pb-2 text-right">
                  <!--
                  <select class="form-control form-control-sm search-notice">
                    <option value="">▼ 다른 산행 공지 불러오기</option>
                    <?php foreach ($listNotice as $value): ?>
                    <option value='<?=$value['idx']?>'><?=$value['subject']?></option>
                    <?php endforeach; ?>
                  </select>
                  -->
                </div>
              </div>
              <div class="area-notice">
                <div class="mt-3 border-top border-bottom">
                  <div class="row align-items-center mt-3 mb-3">
                    <div class="col-3 col-sm-2 p-0 pl-2">대표 사진<br><small>※ 최적크기 : 135 x 76</small></div>
                    <div class="col-7 col-sm-7 p-0 pr-2">
                      <?php if (!empty($view['photo']) && file_exists(PHOTO_PATH . 'thumb_' . $view['photo'])): ?>
                      <a target="_blank" href="<?=PHOTO_URL . $view['photo']?>"><img src="<?=PHOTO_URL . 'thumb_' . $view['photo']?>"></a>
                      <input type="hidden" name="filename" value="<?=$view['photo']?>">
                      <?php else: ?>
                      <input type="file" name="photo">
                      <?php endif; ?>
                    </div>
                    <div class="col-2 col-sm-3 p-0 text-right">
                      <?php if (!empty($view['photo'])): ?>
                      <button type="button" class="btn btn-sm btn-danger btn-notice-photo-delete pl-2 pr-2">삭제</button>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <?php foreach ($listNoticeDetail as $key => $value): ?>
                <div class="item-notice pt-3">
                  <div class="row align-items-center mb-2">
                    <div class="col-10 col-sm-11 p-0 pr-2"><input type="hidden" name="idx[]" value="<?=$value['idx']?>"><input type="text" name="title[]" value="<?=$value['title']?>" class="form-control form-control-sm"></div>
                    <div class="col-2 col-sm-1 p-0 text-right"><button type="button" class="btn btn-sm btn-danger btn-delete-notice pl-2 pr-2">삭제</button></div>
                  </div>
                  <textarea name="content[]" rows="10" cols="100" id="content_<?=$key?>" class="content"><?=$value['content']?></textarea>
                </div>
                <?php endforeach; ?>
              </div>
              <div class="area-button">
                <button type="button" class="btn btn-sm btn-info btn-add-notice mr-2">항목추가</button>
                <a target="_blank" href="<?=BASE_URL?>/admin/main_notice_view/<?=$view['idx']?>"><button type="button" class="btn btn-sm btn-secondary ml-2 mr-2">복사하기</button></a>
                <button type="submit" class="btn btn-sm btn-default ml-2 mr-4">저장하기</button>
              </div>
            </form>
          </div>
        </div>

        <script type="text/javascript">
          CKEDITOR.replaceAll();
          $('#sortable').disableSelection().sortable();
          $(document).on('change', '.search-notice', function() {
            $.ajax({
              url: '/admin/main_entry_notice',
              data: 'idx=' + $(this).val(),
              dataType: 'json',
              type: 'post',
              success: function(result) {
                CKEDITOR.instances.plan.setData(result.plan);
                CKEDITOR.instances.point.setData(result.point);
                CKEDITOR.instances.intro.setData(result.intro);
                CKEDITOR.instances.timetable.setData(result.timetable);
                CKEDITOR.instances.information.setData(result.information);
                CKEDITOR.instances.course.setData(result.course);
              }
            });
          }).on('click', '.btn-add-notice', function() {
            // 항목 추가
            var cnt = 0;
            $('.content').each(function() { cnt++; });
            var content = '<div class="item-notice pt-3"><div class="row align-items-center mb-2"><div class="col-10 col-sm-11 p-0 pr-2"><input type="text" name="title[]" class="form-control form-control-sm"></div><div class="col-2 col-sm-1 p-0 text-right"><button type="button" class="btn btn-sm btn-danger btn-delete-notice pl-2 pr-2">삭제</button></div></div><textarea name="content[]" rows="10" cols="100" id="content_' + cnt + '" class="content"></textarea></div>';
            $('.area-notice').append(content);
            CKEDITOR.replace('content_' + cnt);
            $('html, body').animate({ scrollTop: $(document).height() }, 800);
          }).on('click', '.btn-delete-notice', function() {
            // 항목 삭제
            var html = '<div class="w-100 text-center">저장 후 삭제됩니다.</div>';
            $(this).closest('.item-notice').animate({ height: 50 }, 'slow').html(html);
          });
        </script>
