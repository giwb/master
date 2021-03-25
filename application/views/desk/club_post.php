<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

                <script type="text/javascript" src="/public/se2/js/HuskyEZCreator.js" charset="utf-8"></script>

                <form id="postClub" method="post" action="/desk/club_update" enctype="multipart/form-data">
                <input type="hidden" name="idx" value="<?=!empty($view['idx']) ? $view['idx'] : ''?>">
                <input type="hidden" name="useridx" value="<?=!empty($userData['idx']) ? $userData['idx'] : ''?>">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-7"><h1 class="h3 mb-3 text-gray-800">산악회 등록</h1></div>
                        <div class="col-5 text-right"><a href="/desk/club"><button type="button" class="btn btn-secondary">산악회 목록</button></a></div>
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
                                            <th class="text-center">산악회/단체명</th>
                                            <td><input type="text" name="title" class="form-control" value="<?=!empty($view['title']) ? $view['title'] : ''?>"></td>
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
                                                            <option value='<?=$value['idx']?>'><?=$value['name']?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-3">
                                                        <select name="area_gugun[]" class="form-control area-gugun">
                                                            <option value=''>시/군/구</option>
                                                            <?php foreach ($area_gugun as $value): ?>
                                                            <option value='<?=$value['idx']?>'><?=$value['name']?></option>
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
                                            <th class="text-center">해발</th>
                                            <td>
                                                <div class="row align-items-end">
                                                    <div class="col-1 pr-1"><input type="text" name="altitude" class="form-control" value="<?=!empty($view['altitude']) ? $view['altitude'] : ''?>"></div>
                                                    <div class="col-1 pl-0">m</div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">산악회 소개</th>
                                            <td><textarea name="content" rows="10" cols="100" id="clubContent" class="se-content"><?=!empty($view['content']) ? $view['content'] : ''?></textarea></td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">썸네일</th>
                                            <td>
                                                <div class="pt-2 pb-2 text-danger">※ 썸네일은 500 x 250 사이즈에 최적화되어 있습니다.</div>
                                                <input type="file" name="thumbnail">
                                                <?php if (!empty($view['thumbnail'])): ?>
                                                    <div class="pt-2 pb-2"><img src="<?=PHOTO_CLUB_URL . $view['thumbnail']?>"></div>
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
                        <button type="button" class="btn btn-primary btn-post-club">산악회 <?=!empty($view['idx']) ? '수정' : '등록'?>하기</button>
                    </div>
                </div>
                </form>

                <script type="text/javascript">
                    var oEditors = [];
                    nhn.husky.EZCreator.createInIFrame({
                        oAppRef: oEditors,
                        elPlaceHolder: 'clubContent',
                        sSkinURI: '/public/se2/SmartEditor2Skin.html',
                        fCreator: 'createSEditor2'
                    });
                    $(document).on('click', '.btn-post-club', function() {
                        oEditors.getById['clubContent'].exec('UPDATE_CONTENTS_FIELD', []);
                        var $btn = $(this);
                        var formData = new FormData($('#postClub')[0]);
                        var content = $('#clubContent').val();
                        formData.append('content', content);
                        $.ajax({
                            processData: false,
                            contentType: false,
                            url: '/desk/club_update',
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
                                    location.reclub('/desk/club');
                                }
                            }
                        });
                    }).on('click', '.btn-add-area', function() {
                        var data = '<div class="row mt-1"><div class="col-3"><select name="area_sido[]" class="form-control area-sido">';
                        data += '<option value="">시/도</option>';
                        <?php foreach ($area_sido as $value): ?>
                        data += '<option<?=!empty($view['area_sido']) && $view['area_sido'] == $value['idx'] ? " selected" : ""?> value="<?=$value['idx']?>""><?=$value['name']?></option>';
                        <?php endforeach; ?>
                        data += '</select></div><div class="col-3">';
                        data += '<select name="area_gugun[]" class="form-control area-gugun">';
                        data += '<option value="">시/군/구</option>';
                        <?php foreach ($area_gugun as $value): ?>
                        data += '<option<?=!empty($view['area_gugun']) && $view['area_gugun'] == $value['idx'] ? " selected" : ""?> value="<?=$value['idx']?>""><?=$value['name']?></option>';
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
