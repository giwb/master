<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div id="content" class="mb-5">
          <div class="w-100 border mt-3 mb-3 p-3">
            <form id="formSearch" method="get" action="<?=BASE_URL?>/admin/log_visitor" class="m-0">
              <input type="hidden" name="keyword" value="<?=!empty($keyword) ? $keyword : ''?>">
              <input type="hidden" name="nowdate" value="<?=!empty($nowdate) ? $nowdate : ''?>">
              <div class="row align-items-center justify-content-center">
                <div class="col-1 col-sm-2 p-0 pr-1 text-right"><a href="javascript:;" class="btn-search-visitor-date" data-nowdate="<?=$searchPrev?>">◀</a></div>
                <div class="col-4 col-sm-2 p-0 pr-1">
                  <select name="y" class="form-control form-control-sm">
                    <?php foreach (range($searchYear, 2010) as $value): ?>
                      <option<?=!empty($searchYear) && $searchYear == $value ? ' selected' : ''?> value='<?=$value?>'><?=$value?>년</option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-2 col-sm-1 p-0 pr-1">
                  <select name="m" class="form-control form-control-sm p-0">
                    <?php foreach (range(1, 12) as $value): ?>
                    <option<?=!empty($searchMonth) && $searchMonth == $value ? ' selected' : ''?> value='<?=strlen($value) < 2 ? '0' . $value : $value?>'><?=$value?>월</option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-2 col-sm-1 p-0 pr-1">
                  <select name="d" class="form-control form-control-sm p-0">
                    <?php foreach (range(1, 31) as $value): ?>
                    <option<?=!empty($searchDay) && $searchDay == $value ? ' selected' : ''?> value='<?=strlen($value) < 2 ? '0' . $value : $value?>'><?=$value?>일</option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-2 col-sm-1 p-0 pr-1"><button type="button" class="btn btn-sm btn-<?=$viewClub['main_color']?> btn-search-visitor">검색</button></div>
                <div class="col-1 col-sm-2 p-0"><a href="javascript:;" class="btn-search-visitor-date" data-nowdate="<?=$searchNext?>">▶</a></div>
              </div>
            </form>
          </div>
          <div class="row align-items-center">
            <div class="col-sm-6 p-0">
              <strong>・총 방문횟수 : <?=count($listVisitor)?>회</strong>
            </div>
            <div class="col-sm-6 text-right mb-2 p-0">
              <?php if (empty($keyword)): ?>
              <button class="btn btn-sm btn-<?=$viewClub['main_color']?> btn-search-visitor-member" data-keyword="created_by">회원만 보기</button>
              <?php else: ?>
              <button class="btn btn-sm btn-<?=$viewClub['main_color']?> btn-search-visitor-member" data-keyword="">모두 보기</button>
              <?php endif; ?>
            </div>
          </div>
          <div class="row align-items-center pt-2 pb-2 bg-secondary text-white">
            <div class="col-3 col-sm-2 pl-3 pr-0">접속시간</div>
            <div class="col-3 col-sm-2 pl-0 pr-0">닉네임/IP</div>
            <div class="col-3 col-sm-4 pl-0 pr-0 d-none d-sm-block">링크된곳</div>
            <div class="col-6 col-sm-4 pl-0 pr-0">브라우저</div>
          </div>
          <?php foreach ($listVisitor as $value): ?>
          <div class="row align-items-center border-bottom pt-2 pb-2 small visitor-data">
            <div class="col-3 col-sm-2 pl-3 pr-0"><?=calcStoryTime($value['created_at'])?></div>
            <div class="col-3 col-sm-2 pl-0 pr-0"><?=!empty($value['nickname']) && empty($value['quitdate']) ? '<a href="/admin/member_view/' . $value['created_by'] . '">' . $value['nickname'] . '</a>' : $value['ip_address']?></div>
            <div class="col-3 col-sm-4 pl-0 pr-0 d-none d-sm-block"><a target="_blank" href="<?=$value['http_referer']?>"><?=strlen($value['http_referer']) > 35 ? substr($value['http_referer'], 0, 35) . '...' : $value['http_referer']?></a></div>
            <div class="col-6 col-sm-4 pl-0 pr-0" title="<?=$value['user_agent']?>"><?=getUserAgent($value['user_agent'])?></div>
          </div>
          <?php endforeach; ?>
          <div class="mb-5"></div>
        </div>
        <script>
          $(document).ready(function() {
            $('.btn-search-visitor-member').click(function() {
              // 회원만 검색
              var $dom = $('#formSearch');
              var keyword = $(this).data('keyword');
              $('input[name=keyword]', $dom).val(keyword);
              $dom.submit();
            });
            $('.btn-search-visitor').click(function() {
              // 방문자 기록 검색
              var $dom = $('#formSearch');
              var y = $('select[name=y]').val();
              var m = $('select[name=m]').val();
              var d = $('select[name=d]').val();
              $('input[name=nowdate]', $dom).val(y + m + d);
              $dom.submit();
            });
            $('.btn-search-visitor-date').click(function() {
              // 전날, 다음날 방문자 검색
              var $dom = $('#formSearch');
              var nowdate = $(this).data('nowdate');
              $('input[name=nowdate]', $dom).val(nowdate);
              $dom.submit();
            });
          });
        </script>
