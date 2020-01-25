<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <h1 class="h3 mb-0 text-gray-800">차종등록</h1>
    <div class="text-right pt-3 pb-3"><a href="/admin/setup_bustype_add"><button class="btn btn-primary">등록하기</button></a></div>
    <input type="hidden" name="back_url" value="setup_bustype">
    <div class="ml-4 mr-4">
      <div class="row align-items-center font-weight-bold border-top mt-3 pt-3">
        <div class="col-6 col-sm-4">차량명</div>
        <div class="col-sm-1 d-none d-sm-block">기사명</div>
        <div class="col-sm-2 d-none d-sm-block">번호판</div>
        <div class="col-sm-1 d-none d-sm-block">색상</div>
        <div class="col-4 col-sm-1">인원수</div>
        <div class="col-sm-1 d-none d-sm-block">등록일</div>
        <div class="col-2 col-sm-2 pl-0 pr-0">편집</div>
      </div>
      <div id="sortable">
        <?php foreach ($listBustype as $key => $value): ?>
        <div class="row align-items-center border-top mt-3 pt-3 bus-list" data-idx="<?=$value['idx']?>">
          <div class="col-6 col-sm-4"><?=$value['bus_name']?></div>
          <div class="col-sm-1 d-none d-sm-block"><?=$value['bus_owner']?></div>
          <div class="col-sm-2 d-none d-sm-block"><?=$value['bus_license']?></div>
          <div class="col-sm-1 d-none d-sm-block"><?=$value['bus_color']?></div>
          <div class="col-4 col-sm-1"><?=$value['bus_seat_name']?></div>
          <div class="col-sm-1 d-none d-sm-block"><?=date('Y-m-d', $value['created_at'])?></div>
          <div class="col-2 col-sm-2 pl-0 pr-0">
            <a href="/admin/setup_bustype_add/<?=$value['idx']?>"><button class="btn btn-sm btn-primary">수정</button></a>
            <button class="btn btn-sm btn-secondary btn-bus-hide" data-idx="<?=$value['idx']?>"><?=$value['visible'] == 'Y' ? '숨김' : '보임'?></button>
            <button class="btn btn-sm btn-danger btn-bustype-delete-modal" data-idx="<?=$value['idx']?>">삭제</button>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="mt-5 mb-5"></div>
    </div>

    <script type="text/javascript">
      $(function() {
        $('#sortable').disableSelection().sortable({
          stop: function(event, ui) {
            var arrSort = new Array();

            $('.bus-list').each(function() {
              arrSort.push($(this).data('idx'));
            })

            $.ajax({
              url: '/admin/setup_bustype_sort',
              data: 'sort=' + arrSort,
              dataType: 'json',
              type: 'post',
              success: function() {}
            });
          }
        });
      });
    </script>