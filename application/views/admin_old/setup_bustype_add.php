<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">차종등록</h1>
        </div>

        <form id="formBustype" method="post" action="/admin_old/<?=$action?>">
          <div class="row align-items-center pt-2 pb-2">
            <div class="col-sm-1"><strong>차량명</strong> <span class="required">(*)</span></div>
            <div class="col-sm-11"><input type="text" name="bus_name" class="form-control" value="<?=$viewBustype['bus_name']?>"></div>
          </div>
          <div class="row align-items-center pt-2 pb-2">
            <div class="col-sm-1"><strong>기사명</strong></div>
            <div class="col-sm-11"><input type="text" name="bus_owner" class="form-control" value="<?=$viewBustype['bus_owner']?>"></div>
          </div>
          <div class="row align-items-center pt-2 pb-2">
            <div class="col-sm-1"><strong>번호판</strong></div>
            <div class="col-sm-11"><input type="text" name="bus_license" class="form-control" value="<?=$viewBustype['bus_license']?>"></div>
          </div>
          <div class="row align-items-center pt-2 pb-2">
            <div class="col-sm-1"><strong>색상</strong></div>
            <div class="col-sm-11"><input type="text" name="bus_color" class="form-control" value="<?=$viewBustype['bus_color']?>"></div>
          </div>
          <div class="row align-items-center pt-2 pb-2">
            <div class="col-sm-1"><strong>인원수</strong> <span class="required">(*)</span></div>
            <div class="col-sm-11">
              <select name="bus_seat" class="form-control">
                <option value="">인원수를 선택해주세요.</option>
                <?php foreach ($listBusdata as $value): ?>
                <option<?=$value['idx'] == $viewBustype['bus_seat'] ? ' selected': ''?> value="<?=$value['idx']?>"><?=$value['name']?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="row align-items-center pt-2 pb-2">
            <div class="col-sm-1"><strong>메모</strong></div>
            <div class="col-sm-11"><textarea name="memo" rows="10" class="w-100 form-control"><?=$viewBustype['memo']?></textarea></div>
          </div>
          <div class="row align-items-center pt-2 pb-2">
            <div class="col-sm-12 text-center">
              <div class="error-message"></div>
              <input type="hidden" name="idx" value="<?=$viewBustype['idx']?>">
              <button type="button" class="btn btn-primary btn-bustype-add"><?=$btnName?></button>
            </div>
          </div>
        </form>
      </div>
