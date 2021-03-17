<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <script type="text/javascript" src="/public/js/jquery.MultiFile.min.js"></script>
  <script type="text/javascript" src="/public/js/album.js"></script>
  <main id="club">
    <div class="container-fluid club-main">
      <div class="row mt-1 mb-5">
        <div class="col-md-2"></div>
        <div class="col-md-8">

          <div class="row align-items-center">
            <div class="col-6"><h4 class="font-weight-bold"><?=$pageTitle?></h4></div>
            <div class="col-6 text-right"><a href="<?=BASE_URL?>/album" class="btn-custom btn-gray">목록</a></div>
          </div>
          <hr class="text-default">

          <form id="formPhoto" method="post" action="<?=BASE_URL?>/album/update" enctype="multipart/form-data">
            <input type="hidden" name="clubIdx" value="<?=$view['idx']?>">
            <div class="row align-items-center mt-4">
              <div class="col-sm-2 font-weight-bold">다녀온 여행</div>
              <div class="col-sm-10">
                <select name="noticeIdx" class="form-control">
                  <option value="">사진을 등록할 여행을 선택해주세요.</option>
                  <?php foreach ($listNotice as $value): ?>
                  <option value="<?=$value['idx']?>"><?=$value['startdate']?> / <?=$value['subject']?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="row align-items-center mt-2">
              <div class="col-sm-2 font-weight-bold">사진 설명</div>
              <div class="col-sm-10"><input type="text" name="subject" maxlength="200" class="form-control" value="<?=!empty($viewAlbum['subject']) ? $viewAlbum['subject'] : ''?>"></div>
            </div>
            <div class="row align-items-center mt-2">
              <div class="col-sm-2 font-weight-bold">사진 올리기</div>
              <div class="col-sm-10 pt-1 pb-1">
                <input type="file" name="files[]" multiple="multiple" class="multi" accept="gif|jpg|png|jpeg">
              </div>
            </div>
            <div class="border-top mt-4 pt-4 text-center">
              <button type="button" class="btn btn-default btn-album-insert">등록합니다</button>
            </div>
          </form>
        </div>
        <div class="col-md-2"></div>
      </div>
