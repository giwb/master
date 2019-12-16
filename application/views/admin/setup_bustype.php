<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">차종등록</h1>
          <a href="<?=base_url()?>admin/setup_bustype_add"><button class="btn btn-primary">등록하기</button></a>
        </div>

        <input type="hidden" name="back_url" value="setup_bustype">
        <table class="table">
          <colgroup>
            <col width="10%">
            <col width="40%">
            <col width="10%">
            <col width="15%">
            <col width="10%">
            <col width="15%">
          </colgroup>
          <thead>
            <tr>
              <th>번호</th>
              <th>차량명</th>
              <th>기사명</th>
              <th>인원수</th>
              <th>등록일</th>
              <th>편집</th>
            </tr>
          </thead>
          <tbody>
<?php foreach ($listBustype as $key => $value): ?>
            <tr>
              <td><?=$key+1?></td>
              <td><?=$value['bus_name']?></td>
              <td><?=$value['bus_owner']?></td>
              <td><?=$value['bus_seat_name']?></td>
              <td><?=date('Y-m-d', $value['created_at'])?></td>
              <td>
                <a href="<?=base_url()?>admin/setup_bustype_add/<?=$value['idx']?>"><button class="btn btn-primary">수정</button></a>
                <button class="btn btn-secondary btn-bustype-delete-modal" data-idx="<?=$value['idx']?>">삭제</button>
              </td>
            </tr>
<?php endforeach; ?>
            <tr><td colspan="6"></td></tr>
          </tbody>
        </table>
      </div>
