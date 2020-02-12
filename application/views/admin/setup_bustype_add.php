<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <div id="content" class="mb-5">
          <form id="formBustype" method="post" action="<?=BASE_URL?>/admin/<?=$action?>" class="mt-2">
            <div class="row align-items-center pt-2 pb-2">
              <div class="col-sm-2"><strong>차량명</strong> <span class="required">(*)</span></div>
              <div class="col-sm-10"><input type="text" name="bus_name" class="form-control" value="<?=!empty($viewBustype['bus_name']) ? $viewBustype['bus_name'] : ''?>"></div>
            </div>
            <div class="row align-items-center pt-2 pb-2">
              <div class="col-sm-2"><strong>기사명</strong></div>
              <div class="col-sm-10"><input type="text" name="bus_owner" class="form-control" value="<?=!empty($viewBustype['bus_owner']) ? $viewBustype['bus_owner'] : ''?>"></div>
            </div>
            <div class="row align-items-center pt-2 pb-2">
              <div class="col-sm-2"><strong>번호판</strong></div>
              <div class="col-sm-10"><input type="text" name="bus_license" class="form-control" value="<?=!empty($viewBustype['bus_license']) ? $viewBustype['bus_license'] : ''?>"></div>
            </div>
            <div class="row align-items-center pt-2 pb-2">
              <div class="col-sm-2"><strong>색상</strong></div>
              <div class="col-sm-10"><input type="text" name="bus_color" class="form-control" value="<?=!empty($viewBustype['bus_color']) ? $viewBustype['bus_color'] : ''?>"></div>
            </div>
            <div class="row align-items-center pt-2 pb-2">
              <div class="col-sm-2"><strong>인원수</strong> <span class="required">(*)</span></div>
              <div class="col-sm-10">
                <select name="bus_seat" class="form-control">
                  <option value="">인원수를 선택해주세요.</option>
                  <?php foreach ($listBusdata as $value): ?>
                  <option<?=$value['idx'] == $viewBustype['bus_seat'] ? ' selected': ''?> value="<?=$value['idx']?>"><?=$value['name']?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="row align-items-center pt-2 pb-2">
              <div class="col-sm-2"><strong>메모</strong></div>
              <div class="col-sm-10"><textarea name="memo" rows="10" class="w-100 form-control"><?=!empty($viewBustype['memo']) ? $viewBustype['memo'] : ''?></textarea></div>
            </div>
            <div class="row align-items-center pt-2 pb-2">
              <div class="col-sm-12 text-center">
                <div class="error-message"></div>
                <input type="hidden" name="idx" value="<?=$viewBustype['idx']?>">
                <button type="button" class="btn btn-default btn-bustype-add"><?=$btnName?></button>
              </div>
            </div>
          </form>
        </div>
