<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="sub-header">사진 등록</div>
        <div class="sub-content">
          <form id="formAlbum" method="post" action="<?=base_url()?>album/update" enctype="multipart/form-data">
            <input type="hidden" name="page" value="album">
            <input type="hidden" name="idx" value="<?=!empty($viewAlbum['idx']) ? $viewAlbum['idx'] : ''?>">
            <input type="hidden" name="clubIdx" value="<?=$view['idx']?>">
            <input type="hidden" name="redirectUrl" value="<?=BASE_URL?>/album">
            <div class="row align-items-center mt-2">
              <div class="col-sm-2 font-weight-bold">사진첩 제목</div>
              <div class="col-sm-10"><input type="text" name="subject" class="form-control" value="<?=!empty($viewAlbum['subject']) ? $viewAlbum['subject'] : ''?>"></div>
            </div>
            <div class="row align-items-center mt-2">
              <div class="col-sm-2 font-weight-bold">내용</div>
              <div class="col-sm-10"><textarea rows="10" name="content" class="form-control"><?=!empty($viewAlbum['content']) ? $viewAlbum['content'] : ''?></textarea></div>
            </div>
            <div class="row align-items-center mt-2">
              <div class="col-sm-2 font-weight-bold">사진</div>
              <div class="col-sm-10">
                <input type="hidden" name="photos" value="<?php foreach ($photos as $value): ?><?=$value['filename']?>,<?php endforeach; ?>"><input type="file" class="photo d-none"><button type="button" class="btn btn-secondary btn-upload-photo">사진 선택</button>
                <div class="added-files"><?php foreach ($photos as $value): ?><img src="<?=base_url()?><?=PHOTO_URL?><?=$value['filename']?>" class="btn-photo-modal" data-photo="<?=$value['filename']?>"><?php endforeach; ?></div>
              </div>
            </div>
            <div class="border-top mt-2 pt-4 text-center">
              <button type="button" class="btn btn-primary btn-album-update"><?=empty($viewAlbum['idx']) ? '등록합니다' : '수정합니다'?></button>
              <?=!empty($viewAlbum['idx']) ? '<button type="button" class="btn btn-danger btn-album-delete-modal ml-3">삭제합니다</button>' : ''?>
            </div>
          </form>
        </div>
        <div class="ad-sp">
          <!-- CENTER -->
          <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-2424708381875991" data-ad-slot="7579588805" data-ad-format="auto" data-full-width-responsive="true"></ins>
          <script> (adsbygoogle = window.adsbygoogle || []).push({}); </script>
        </div>
      </div>
      <script src="<?=base_url()?>public/js/album.js" type="text/javascript"></script>
      <!-- Album Delete Modal -->
      <div class="modal fade" id="albumDeleteModal" tabindex="-1" role="dialog" aria-labelledby="albumDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="smallmodalLabel">메세지</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body text-center">
              <p class="modal-message">정말로 삭제하시겠습니까?</p>
            </div>
            <div class="modal-footer">
              <a href="<?=BASE_URL?>/album"><button type="button" class="btn btn-primary btn-album-list d-none">목록으로</button></a>
              <button type="button" class="btn btn-primary btn-album-delete">삭제합니다</button>
              <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
            </div>
          </div>
        </div>
      </div>
