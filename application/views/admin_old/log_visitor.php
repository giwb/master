<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">방문자 기록</h1>
        </div>
        <form id="formSearch" method="post" action="/admin_old/log_visitor" class="mb-3 pb-3 visitor-header">
          <input type="hidden" name="keyword" value="<?=!empty($keyword) ? $keyword : ''?>">
          <input type="hidden" name="nowdate" value="<?=!empty($nowdate) ? $nowdate : ''?>">
          <div class="row align-items-center justify-content-center">
            <div class="pl-3"><a href="javascript:;" class="btn-search-visitor-date" data-nowdate="<?=$searchPrev?>">◀</a></div>
            <div class="pl-3">
              <select name="y" class="form-control">
                <?php foreach (range($searchYear, 2010) as $value): ?>
                  <option<?=!empty($searchYear) && $searchYear == $value ? ' selected' : ''?> value='<?=$value?>'><?=$value?>년</option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="pl-3">
              <select name="m" class="form-control">
                <?php foreach (range(1, 12) as $value): ?>
                <option<?=!empty($searchMonth) && $searchMonth == $value ? ' selected' : ''?> value='<?=strlen($value) < 2 ? '0' . $value : $value?>'><?=$value?>월</option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="pl-3">
              <select name="d" class="form-control">
                <?php foreach (range(1, 31) as $value): ?>
                <option<?=!empty($searchDay) && $searchDay == $value ? ' selected' : ''?> value='<?=strlen($value) < 2 ? '0' . $value : $value?>'><?=$value?>일</option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="pl-3"><button type="button" class="btn btn-primary btn-search-visitor">검색</button></div>
            <div class="pl-3"><a href="javascript:;" class="btn-search-visitor-date" data-nowdate="<?=$searchNext?>">▶</a></div>
          </div>
        </div>
      </form>

      <div class="row align-items-center">
        <div class="col-sm-6">
          <strong>・총 방문횟수 : <?=count($listVisitor)?>회</strong>
        </div>
        <div class="col-sm-6 text-right mb-2">
          <?php if (empty($keyword)): ?>
          <button class="btn btn-primary btn-search-visitor-member" data-keyword="created_by">회원만 보기</button>
          <?php else: ?>
          <button class="btn btn-primary btn-search-visitor-member" data-keyword="">모두 보기</button>
          <?php endif; ?>
        </div>
      </div>
      <div class="row align-items-center border-top pt-2 pb-2 bg-primary text-white visitor-row">
        <div class="col-sm-1 pl-4">접속시간</div>
        <div class="col-sm-1">닉네임/IP</div>
        <div class="col-sm-2">링크된곳</div>
        <div class="col-sm-8">브라우저</div>
      </div>
      <?php foreach ($listVisitor as $value): ?>
      <div class="row align-items-center border-top pt-2 pb-2 small visitor-data">
        <div class="col-sm-1 pl-4"><?=calcStoryTime($value['created_at'])?></div>
        <div class="col-sm-1"><?=!empty($value['nickname']) && empty($value['quitdate']) ? '<a href="/admin_old/member_view/' . $value['created_by'] . '">' . $value['nickname'] . '</a>' : $value['ip_address']?></div>
        <div class="col-sm-2"><a target="_blank" href="<?=$value['http_referer']?>"><?=strlen($value['http_referer']) > 40 ? substr($value['http_referer'], 0, 40) . '...' : $value['http_referer']?></a></div>
        <div class="col-sm-8" title="<?=$value['user_agent']?>"><?=getUserAgent($value['user_agent'])?></div>
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