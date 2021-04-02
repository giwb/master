<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

                <script type="text/javascript" src="/public/se2/js/HuskyEZCreator.js" charset="utf-8"></script>

                <form id="postArticle" method="post" action="/desk/article_update" enctype="multipart/form-data">
                <input type="hidden" name="idx" value="<?=!empty($view['idx']) ? $view['idx'] : ''?>">
                <input type="hidden" name="useridx" value="<?=!empty($userData['idx']) ? $userData['idx'] : ''?>">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-7"><h1 class="h3 mb-3 text-gray-800">기사 등록</h1></div>
                        <div class="col-5 text-right"><a href="/desk/article"><button type="button" class="btn btn-secondary">기사 목록</button></a></div>
                    </div>
                    <div class="card shadow pt-3 mb-3">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <colgroup>
                                        <col width="10%">
                                        <col width="90%">
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <th class="text-center">분류</th>
                                            <td>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <select name="category" class="form-control">
                                                            <option value="">분류를 선택해주세요</option>
                                                            <?php foreach ($category as $value): ?>
                                                            <option<?=!empty($view['category']) && $view['category'] == $value['code'] ? ' selected' : ''?> value="<?=$value['code']?>"><?=$value['name']?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-6 pl-0">
                                                        <button type="button" class="btn btn-primary btn-modal-category">분류 편집</button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">타이틀</th>
                                            <td><input type="text" name="title" class="form-control" value="<?=!empty($view['title']) ? $view['title'] : ''?>"></td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">내용</th>
                                            <td><textarea name="content" rows="10" cols="100" id="articleContent"><?=!empty($view['content']) ? $view['content'] : ''?></textarea></td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">메인 사진</th>
                                            <td>
                                                <input type="file" name="main_image">
                                                <div class="pt-2 pb-2 text-danger">※ 메인 페이지에 올리는 사진은 1500 x 450 사이즈에 최적화되어 있습니다.</div>
                                                <?php if (!empty($view['main_image'])): ?>
                                                    <div class="pt-2 pb-2"><img src="<?=PHOTO_ARTICLE_URL . $view['main_image']?>" style="max-width: 600px;"></div>
                                                    <input type="hidden" name="main_image_uploaded" value="<?=$view['main_image']?>">
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">공개일시</th>
                                            <td>
                                                <div class="row">
                                                    <div class="col-6"><input type="text" name="viewing_date" id="datePicker" class="form-control" autocomplete="off" value="<?=!empty($view['viewing_at']) ? date('Y-m-d', $view['viewing_at']) : date('Y-m-d')?>"></div>
                                                    <div class="col-6"><input type="text" name="viewing_time" class="form-control" placeholder="00:00" value="<?=!empty($view['viewing_at']) ? date('H:i', $view['viewing_at']) : date('H:i')?>"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="error-message text-center"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-primary btn-post-article">기사 <?=!empty($view['idx']) ? '수정' : '등록'?>하기</button>
                    </div>
                </div>
                </form>

                <!-- 분류 편집 모달 -->
                <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form id="editCategory" method="post" action="/desk/article_category_update">
                            <div class="modal-header">
                                <h5 class="modal-title" id="smallmodalLabel">분류 편집</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <div class="row p-2">
                                    <div class="col-6 p-2 bg-secondary text-white small">카테고리 코드</div>
                                    <div class="col-6 p-2 bg-secondary text-white small">카테고리 이름</div>
                                </div>
                                <div class="area-category">
                                    <?php if (empty($category)): ?>
                                    <div class="row p-1"><div class="col-6"><input type="text" name="category_code[]" class="form-control"></div><div class="col-6"><input type="text" name="category_name[]" class="form-control"></div></div>
                                    <?php else: ?>
                                    <?php foreach ($category as $value): ?>
                                    <div class="row p-1"><div class="col-6"><input type="text" name="category_code[]" class="form-control" value="<?=$value['code']?>"></div><div class="col-6"><input type="text" name="category_name[]" class="form-control" value="<?=$value['name']?>"></div></div>
                                    <?php endforeach; endif; ?>
                                </div>
                                <div class="error-message"></div>
                            </div>
                            <div class="border-top p-3">
                                <div class="row">
                                    <div class="col-6 text-left"><button type="button" class="btn btn-danger btn-add-category">분류 추가</button></div>
                                    <div class="col-6 text-right"><button type="button" class="btn btn-primary btn-edit-category">편집 완료</button></div>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script type="text/javascript">
                    var oEditors = [];
                    nhn.husky.EZCreator.createInIFrame({
                        oAppRef: oEditors,
                        elPlaceHolder: 'articleContent',
                        sSkinURI: '/public/se2/SmartEditor2Skin.html',
                        fCreator: 'createSEditor2'
                    });
                    $(document).on('click', '.btn-post-article', function() {
                      // 기사 등록
                      oEditors.getById['articleContent'].exec('UPDATE_CONTENTS_FIELD', []);
                      var $btn = $(this);
                      var formData = new FormData($('#postArticle')[0]);
                      var content = $('#articleContent').val();
                      formData.append('content', content);
                      $.ajax({
                        processData: false,
                        contentType: false,
                        url: '/desk/article_update',
                        data: formData,
                        dataType: 'json',
                        type: 'post',
                        beforeSend: function() {
                          $btn.css('opacity', '0.5').prop('disabled', true);
                        },
                        success: function(result) {
                          $btn.css('opacity', '1').prop('disabled', false);
                          if (result.error == 1) {
                            $('.error-message').text(result.message);
                            setTimeout(function() { $('.error-message').text(''); }, 2000);
                          } else {
                            location.replace('/desk/article');
                          }
                        }
                      });
                    });
                </script>
