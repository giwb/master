<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">대문관리</h1>
        </div>
      </div>

      <form id="formFront" method="post" action="/admin_old/setup_front_insert" enctype="multipart/form-data">
        <table class="table">
          <tbody>
            <tr>
              <th>
                <input type="file" name="filename" class="file">
                <button type="button" class="btn btn-primary btn-front-submit">등록하기</button>
              </th>
            </tr>
          </tbody>
        </table>
      </form>

      <form id="formSort" method="post" action="/admin_old/setup_front_sort">
        <table class="table">
          <colgroup>
            <col width="90">
          </colgroup>
          <thead>
            <tr>
              <th>정렬순서</th>
              <th>사진</th>
            </tr>
          </thead>
          <tbody>
<?php foreach ($listFront as $value): ?>
            <tr>
              <td><input type="text" size="5" name="sort_idx[]" value="<?=$value['sort_idx']?>" class="sort-idx"><br><button type="button" class="btn btn-secondary btn-front-delete-modal" data-filename="<?=$value['filename']?>">삭제</button></td>
              <td><img width="200" src="<?=URL_FRONT?><?=$value['filename']?>"></td>
            </tr>
<?php endforeach; ?>
            <tr><td colspan="2" style="padding: 0px;"></td></tr>
          </tbody>
        </table>
        <button type="button" class="btn btn-primary btn-front-sort">정렬순서수정</button>
        <input type="hidden" name="back_url" value="setup_front">
      </form><br><br>