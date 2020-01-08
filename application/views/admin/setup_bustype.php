<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <h1 class="h3 mb-0 text-gray-800">차종등록</h1>
    <div class="text-right pt-3 pb-3"><a href="<?=base_url()?>admin/setup_bustype_add"><button class="btn btn-primary">등록하기</button></a></div>
    <input type="hidden" name="back_url" value="setup_bustype">
    <div class="row align-items-center font-weight-bold border-top mt-3 pt-3">
      <div class="col-sm-1">번호</div>
      <div class="col-sm-3">차량명</div>
      <div class="col-sm-1">기사명</div>
      <div class="col-sm-2">번호판</div>
      <div class="col-sm-1">색상</div>
      <div class="col-sm-1">인원수</div>
      <div class="col-sm-1">등록일</div>
      <div class="col-sm-2">편집</div>
    </div>
    <?php foreach ($listBustype as $key => $value): ?>
    <div class="row align-items-center border-top mt-3 pt-3">
      <div class="col-sm-1"><?=$key+1?></div>
      <div class="col-sm-3"><?=$value['bus_name']?></div>
      <div class="col-sm-1"><?=$value['bus_owner']?></div>
      <div class="col-sm-2"><?=$value['bus_license']?></div>
      <div class="col-sm-1"><?=$value['bus_color']?></div>
      <div class="col-sm-1"><?=$value['bus_seat_name']?></div>
      <div class="col-sm-1"><?=date('Y-m-d', $value['created_at'])?></div>
      <div class="col-sm-2">
        <a href="<?=base_url()?>admin/setup_bustype_add/<?=$value['idx']?>"><button class="btn btn-sm btn-primary">수정</button></a>
        <button class="btn btn-sm btn-secondary btn-bus-hide" data-idx="<?=$value['idx']?>"><?=$value['visible'] == 'Y' ? '숨김' : '보임'?></button>
        <button class="btn btn-sm btn-danger btn-bustype-delete-modal" data-idx="<?=$value['idx']?>">삭제</button>
      </div>
    </div>
    <?php endforeach; ?>
    <div class="mt-5 mb-5"></div>

