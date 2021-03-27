<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

                <script type="text/javascript" src="/public/se2/js/HuskyEZCreator.js" charset="utf-8"></script>

                <form id="postSchedule" method="post" action="/desk/schedule_update" enctype="multipart/form-data">
                <input type="hidden" name="idx" value="<?=!empty($view['idx']) ? $view['idx'] : ''?>">
                <input type="hidden" name="useridx" value="<?=!empty($userData['idx']) ? $userData['idx'] : ''?>">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-7"><h1 class="h3 mb-3 text-gray-800">여행일정 등록</h1></div>
                        <div class="col-5 text-right"><a href="/desk/schedule"><button type="button" class="btn btn-secondary">여행일정 목록</button></a></div>
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
                                            <th class="text-center">여행사/산악회명</th>
                                            <td><input type="text" name="agency_name" class="form-control" value="<?=!empty($view['agency_name']) ? $view['agency_name'] : ''?>"></td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">여행일정 제목</th>
                                            <td><input type="text" name="title" class="form-control" value="<?=!empty($view['title']) ? $view['title'] : ''?>"></td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">링크 URL</th>
                                            <td><input type="text" name="link" class="form-control" value="<?=!empty($view['link']) ? $view['link'] : ''?>"></td>
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
                                            <th class="text-center">출발일시</th>
                                            <td>
                                                <div class="row">
                                                    <div class="col-6 col-sm-2"><input type="text" name="viewing_date" id="datePicker" class="form-control" autocomplete="off" value="<?=!empty($view['viewing_at']) ? date('Y-m-d', $view['viewing_at']) : date('Y-m-d')?>"></div>
                                                    <div class="col-6 col-sm-2"><input type="text" name="viewing_time" class="form-control" placeholder="00:00" value="<?=!empty($view['viewing_at']) ? date('H:i', $view['viewing_at']) : date('H:i')?>"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">가격</th>
                                            <td>
                                                <div class="row align-items-end">
                                                    <div class="col-sm-1 pr-0"><input type="text" name="cost" maxlength="7" class="form-control" value="<?=!empty($view['cost']) ? $view['cost'] : ''?>"></div>
                                                    <div class="col-sm-1 pl-0 d-none d-sm-block">원</div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">내용</th>
                                            <td><textarea name="content" rows="10" cols="100" id="articleContent"><?=!empty($view['content']) ? $view['content'] : ''?></textarea></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="error-message text-center"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-primary btn-post-schedule">여행일정 <?=!empty($view['idx']) ? '수정' : '등록'?>하기</button>
                    </div>
                </div>
                </form>

                <script type="text/javascript">
                    var oEditors = [];
                    nhn.husky.EZCreator.createInIFrame({
                        oAppRef: oEditors,
                        elPlaceHolder: 'articleContent',
                        sSkinURI: '/public/se2/SmartEditor2Skin.html',
                        fCreator: 'createSEditor2'
                    });
                    $(document).on('click', '.btn-post-schedule', function() {
                      // 기사 등록
                      oEditors.getById['articleContent'].exec('UPDATE_CONTENTS_FIELD', []);
                      var $btn = $(this);
                      var formData = new FormData($('#postArticle')[0]);
                      var content = $('#articleContent').val();
                      formData.append('content', content);

                      if ($('input[name=agency_name]').val() == '') {
                        $('.error-message').text('여행사/산악회명은 꼭 입력해주세요.');
                        setTimeout(function() { $('.error-message').text(''); }, 2000);
                        return false;
                      }
                      if ($('input[name=title]').val() == '') {
                        $('.error-message').text('여행일정 제목은 꼭 입력해주세요.');
                        setTimeout(function() { $('.error-message').text(''); }, 2000);
                        return false;
                      }

                      $.ajax({
                        processData: false,
                        contentType: false,
                        url: '/desk/schedule_update',
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
                            location.replace('/desk/schedule');
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
