<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">차종등록</h1>
          <a href="<?=base_url()?>admin/setup_bustype"><button class="btn btn-secondary">목록으로</button></a>
        </div>

        <form id="formBustype" method="post" action="<?=base_url()?>admin/<?=$action?>">
          <table class="table">
            <colgroup>
              <col width="150">
            </colgroup>
            <tbody>
              <tr>
                <th>차량명 <span class="required">(*)</span></th>
                <td><input type="text" name="bus_name" class="form-control" value="<?=$viewBustype['bus_name']?>"></td>
              </tr>
              <tr>
                <th>기사명</th>
                <td><input type="text" name="bus_owner" class="form-control" value="<?=$viewBustype['bus_owner']?>"></td>
              </tr>
              <tr>
                <th>인원수 <span class="required">(*)</span></th>
                <td>
                  <select name="bus_seat" class="form-control">
                    <option value="">인원수를 선택해주세요.</option>
<?php foreach ($listBusdata as $value): ?>
                    <option<?=$value['idx'] == $viewBustype['bus_seat'] ? ' selected': ''?> value="<?=$value['idx']?>"><?=$value['name']?></option>
<?php endforeach; ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td colspan="2" class="text-center">
                  <div class="error-message"></div>
                  <input type="hidden" name="idx" value="<?=$viewBustype['idx']?>">
                  <button type="button" class="btn btn-primary btn-bustype-add"><?=$btnName?></button>
                </td>
              </tr>
            </tbody>
          </table>
        </form>
      </div>
