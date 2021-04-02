<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

                <script type="text/javascript" src="/public/se2/js/HuskyEZCreator.js" charset="utf-8"></script>

                <form id="postClub" method="post" action="/desk/club_update" enctype="multipart/form-data">
                <input type="hidden" name="idx" value="<?=!empty($view['idx']) ? $view['idx'] : ''?>">
                <input type="hidden" name="useridx" value="<?=!empty($userData['idx']) ? $userData['idx'] : ''?>">
                <input type="hidden" name="main_design" value="2">
                <input type="hidden" name="main_color" value="default">
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
                                                <button type="button" class="btn btn-sm btn-primary btn-add-area mb-1">추가</button>
                                                <?php if (empty($view['sido'])): ?>
                                                <div class="row mt-1">
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
                                                </div>
                                                <?php else: foreach ($view['sido'] as $key => $val): ?>
                                                <div class="row mt-1">
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
                                                            <option<?=!empty($view['gugun'][$key]) && $value['name'] == $view['gugun'][$key] ? ' selected' : ''?> value='<?=$value['idx']?>'><?=$value['name']?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <?php endforeach; endif; ?>
                                                <div class="added-area"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">페이지 주소</th>
                                            <td>
                                                <div class="row align-items-center">
                                                    <div class="col-sm-2 pr-0"><?=base_url()?></div>
                                                    <div class="col-sm-3 check-url"><input type="text" name="url" value="<?=!empty($view['url']) ? $view['url'] : ''?>" class="form-control"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">홈페이지 링크</th>
                                            <td><input type="text" name="homepage" class="form-control" value="<?=!empty($view['homepage']) ? $view['homepage'] : ''?>"></td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">연락처</th>
                                            <td><input type="text" name="phone" class="form-control" value="<?=!empty($view['phone']) ? $view['phone'] : ''?>"></td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">설립년도</th>
                                            <td>
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col-2 col-sm-1"><input type="text" name="establish" class="form-control" value="<?=!empty($view['establish']) ? $view['establish'] : ''?>"></div>
                                                    <div class="col-1 col-sm-1">년</div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">산악회 소개</th>
                                            <td><textarea name="about" rows="10" cols="100" id="clubContent" class="se-content"><?=!empty($view['about']) ? $view['about'] : ''?></textarea></td>
                                        </tr>
                                        <!--
                                        <tr>
                                            <th class="text-center">디자인</th>
                                            <td class="pt-4">
                                                <label class="mr-5"><input type="radio" name="main_design" value="1"> 1번</label>
                                                <label><input type="radio" name="main_design" value="2" checked> 2번</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">색상</th>
                                            <td class="pt-4">
                                                <div class="row align-items-center">
                                                    <div class="col"><label class="border p-3 bg-primary"><input type="radio" name="main_color" value="primary" checked></label></div>
                                                    <div class="col"><label class="border p-3 bg-danger"><input type="radio" name="main_color" value="danger"></label></div>
                                                    <div class="col"><label class="border p-3 bg-warning"><input type="radio" name="main_color" value="warning"></label></div>
                                                    <div class="col"><label class="border p-3 bg-info"><input type="radio" name="main_color" value="info"></label></div>
                                                    <div class="col"><label class="border p-3 bg-success"><input type="radio" name="main_color" value="success"></label></div>
                                                    <div class="col"><label class="border p-3 bg-secondary"><input type="radio" name="main_color" value="default"></label></div>
                                                </div>
                                            </td>
                                        </tr>
                                        -->
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
                        formData.append('about', content);
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
                                    location.replace('/desk/club');
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
