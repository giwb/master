<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

                <script type="text/javascript" src="/public/se2/js/HuskyEZCreator.js" charset="utf-8"></script>

                <form id="postplace" method="post" action="/desk/place_update" enctype="multipart/form-data">
                <input type="hidden" name="idx" value="<?=!empty($view['idx']) ? $view['idx'] : ''?>">
                <input type="hidden" name="useridx" value="<?=!empty($userData['idx']) ? $userData['idx'] : ''?>">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-7"><h1 class="h3 mb-3 text-gray-800">여행정보 등록</h1></div>
                        <div class="col-5 text-right"><a href="/desk/place"><button type="button" class="btn btn-secondary">여행정보 목록</button></a></div>
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
                                            <th class="text-center">작성자</th>
                                            <td>
                                                <div class="row">
                                                    <div class="col-6"><input type="text" class="form-control" value="<?=!empty($userData['nickname']) ? $userData['nickname'] : ''?>" readonly></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">분류</th>
                                            <td>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <select name="category" class="form-control place-category">
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
                                            <th class="text-center">지역</th>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary btn-add-area mb-2">추가</button>
                                                <div class="row">
                                                    <?php if (empty($view['sido'])): ?>
                                                    <div class="col-3">
                                                        <select name="area_sido[]" class="form-control area-sido">
                                                            <option value=''>시/도</option>
                                                            <?php foreach ($area_sido as $value): ?>
                                                            <option<?=$value['idx'] == $view['area_sido'] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-3">
                                                        <select name="area_gugun[]" class="form-control area-gugun">
                                                            <option value=''>시/군/구</option>
                                                            <?php foreach ($area_gugun as $value): ?>
                                                            <option<?=$value['idx'] == $view['area_gugun'] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <?php else: foreach ($view['sido'] as $key => $val): ?>
                                                    <div class="col-3">
                                                        <select name="area_sido[]" class="form-control area-sido">
                                                            <option value=''>시/도</option>
                                                            <?php foreach ($list_sido as $value): ?>
                                                            <option<?=$value['name'] == $val ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-3">
                                                        <select name="area_gugun[]" class="form-control area-gugun">
                                                            <option value=''>시/군/구</option>
                                                            <?php foreach ($list_gugun[$key] as $value): ?>
                                                            <option<?=$value['name'] == $view['gugun'][$key] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <?php endforeach; endif; ?>
                                                </div>
                                                <div class="added-area"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">타이틀</th>
                                            <td><input type="text" name="title" class="form-control" value="<?=!empty($view['title']) ? $view['title'] : ''?>"></td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">해발</th>
                                            <td>
                                                <div class="row align-items-end">
                                                    <div class="col-1 pr-1"><input type="text" name="altitude" class="form-control" value="<?=!empty($view['altitude']) ? $view['altitude'] : ''?>"></div>
                                                    <div class="col-1 pl-0">m</div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">선정 이유</th>
                                            <td><textarea name="reason" rows="10" cols="100" id="content_1" class="se-content"><?=!empty($view['reason']) ? $view['reason'] : ''?></textarea></div></td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">주변</th>
                                            <td><textarea name="around" rows="10" cols="100" id="content_2" class="se-content"><?=!empty($view['around']) ? $view['around'] : ''?></textarea></td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">코스</th>
                                            <td><textarea name="around" rows="10" cols="100" id="content_3" class="se-content"><?=!empty($view['course']) ? $view['course'] : ''?></textarea></td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">내용</th>
                                            <td><textarea name="content" rows="10" cols="100" id="content_4" class="se-content"><?=!empty($view['content']) ? $view['content'] : ''?></textarea></td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">썸네일</th>
                                            <td>
                                                <input type="file" name="thumbnail">
                                                <?php if (!empty($view['thumbnail'])): ?>
                                                    <div class="pt-2 pb-2"><img src="<?=PHOTO_PLACE_URL . $view['thumbnail']?>"></div>
                                                    <input type="hidden" name="thumbnail_uploaded" value="<?=$view['thumbnail']?>">
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="error-message text-center"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-primary btn-post-place">여행정보 <?=!empty($view['idx']) ? '수정' : '등록'?>하기</button>
                    </div>
                </div>
                </form>

                <!-- 분류 편집 모달 -->
                <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form id="editCategory" method="post">
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
                    var oEditors = new Array(4);

                    function setEditor(n){
                        nhn.husky.EZCreator.createInIFrame({
                            oAppRef: oEditors[n],
                            elPlaceHolder: 'content_' + (n+1),
                            sSkinURI: '/public/se2/SmartEditor2Skin.html',
                            fCreator: 'createSEditor2',
                            htParams: { fOnBeforeUnload: function(){} }
                        });
                    }

                    $(function(){
                        for (var i = 0; i < oEditors.length; i++) {
                            if (oEditors[i] == null) {
                                oEditors[i] = [];
                                setEditor(i);
                            }
                        }
                    });

                    $(document).on('click', '.btn-post-place', function() {
                        // 기사 등록
                        for (var i = 0; i < oEditors.length; i++) {
                            if (oEditors[i] != null) { oEditors[i][0].exec("UPDATE_CONTENTS_FIELD", []); }
                        }

                        var $btn = $(this);
                        var formData = new FormData($('#postplace')[0]);
                        var place = $('#content_1').val();
                        var around = $('#content_2').val();
                        var course = $('#content_3').val();
                        var content = $('#content_4').val();
                        formData.append('place', place);
                        formData.append('around', around);
                        formData.append('course', course);
                        formData.append('content', content);
                        $.ajax({
                            processData: false,
                            contentType: false,
                            url: '/desk/place_update',
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
                                    location.replace('/desk/place');
                                }
                            }
                        });
                    }).on('click', '.btn-add-area', function() {
                        var data = '<div class="row mt-1"><div class="col-3"><select name="area_sido[]" class="form-control area-sido">';
                        data += '<option value="">시/도</option>';
                        <?php foreach ($area_sido as $value): ?>
                        data += '<option<?=$value['idx'] == $view['area_sido'] ? " selected" : ""?> value="<?=$value['idx']?>""><?=$value['name']?></option>';
                        <?php endforeach; ?>
                        data += '</select></div><div class="col-3">';
                        data += '<select name="area_gugun[]" class="form-control area-gugun">';
                        data += '<option value="">시/군/구</option>';
                        <?php foreach ($area_gugun as $value): ?>
                        data += '<option<?=$value['idx'] == $view['area_gugun'] ? " selected" : ""?> value="<?=$value['idx']?>""><?=$value['name']?></option>';
                        <?php endforeach; ?>
                        data += '</select></div></div>';
                        $('.added-area').append(data);
                    }).on('change', '.area-sido', function() {
                        var $dom = $(this);
                        var parent = $dom.val();
                        $.ajax({
                            url: '/place/list_gugun',
                            data: 'parent=' + parent,
                            dataType: 'json',
                            type: 'post',
                            success: function(result) {
                                $dom.parent().parent().find('.area-gugun').empty().append( $('<option value="">시/군/구</option>') );
                                for (var i=0; i<result.length; i++) {
                                    $dom.parent().parent().find('.area-gugun').append( $('<option value="' + result[i].idx + '">' + result[i].name + '</option>') );
                                }
                            }
                        });
                    });
                </script>
