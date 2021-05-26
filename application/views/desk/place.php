<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-7"><h1 class="h3 mb-3 text-gray-800">여행정보 관리</h1></div>
                        <div class="col-5 text-right"><a href="/desk/place_post"><button class="btn btn-primary">여행정보 등록</button></a></div>
                    </div>
                    <div class="card shadow mb-4">
                        <form method="get" action="/desk/place">
                        <div class="row no-gutters align-items-center card-header">
                            <div class="col-9"><h6 class="m-0 pt-1 font-weight-bold text-primary">현재 등록된 정보 : <?=$max?>건</h6></div>
                            <div class="col-3 row no-gutters text-right">
                                <div class="col-10"><input type="text" name="keyword" class="form-control" value="<?=!empty($keyword) ? $keyword : ''?>"></div>
                                <div class="col-2"><button class="btn btn-primary">검색</button></div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col-4">
                                    <select class="btn-category form-control">
                                        <option value="">전체보기 (<?=$max?>)</option>
                                        <?php foreach ($category as $value): ?>
                                        <option<?=!empty($cate) && $cate == $value['code'] ? ' selected' : ''?> value="<?=$value['code']?>"><?=$value['name']?> (<?=$value['count']?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-8 text-right">
                                    <label class="mr-4"><input type="radio" name="sort" class="btn-sort" value="idx"<?=empty($sort) || $sort == 'idx' ? ' checked' : ''?>> 등록순으로 정렬</label>
                                    <label><input type="radio" name="sort" class="btn-sort" value="title"<?=!empty($sort) && $sort == 'title' ? ' checked' : ''?>> 가나다순으로 정렬</label>
                                </div>
                            </div>
                            <div class="table-responsive mt-2">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr align="center" bgcolor="#e9e9e9">
                                            <th width="5%">번호</th>
                                            <th width="10%">등록일시</th>
                                            <th width="6%">썸네일</th>
                                            <th width="19%">분류</th>
                                            <th>타이틀</th>
                                            <th width="8%">작성자</th>
                                            <th width="10%">편집</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($list as $key => $value): ?>
                                        <tr data-link="<?=base_url()?>place/view/<?=$value['idx']?>" class="area-link">
                                            <td class="text-center"><?=$max - $key?></td>
                                            <td class="text-center small"><?=date('Y-m-d H:i', $value['created_at'])?></td>
                                            <td class="text-center p-0"><img width="100" src="<?=!empty($value['thumbnail']) ? PHOTO_PLACE_URL . 'thumb_' . $value['thumbnail'] : '/public/images/noimage.png'?>"></td>
                                            <td class="text-center"><?php if (strstr($value['category'], '{')): $category = unserialize($value['category']); foreach ($category as $cate): echo getPlaceCategoryName($cate) . "<br>"; endforeach; endif; ?></td>
                                            <td><?=$value['title']?></td>
                                            <td class="text-center"><?=$value['nickname']?></td>
                                            <td class="text-center">
                                                <button type="button" data-link="<?=base_url()?>desk/place_post/<?=$value['idx']?>" class="btn btn-sm btn-secondary btn-update">수정</button>
                                                <button type="button" data-idx="<?=$value['idx']?>" data-action="place_delete" class="btn btn-sm btn-danger btn-modal-delete">삭제</button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>

                <script type="text/javascript">
                    $(document).on('click', '.btn-sort', function() {
                        location.replace('/desk/place/?sort=' + $(this).val() + '&keyword=<?=!empty($keyword) ? $keyword : ''?>&cate=<?=!empty($cate) ? $cate : ''?>');
                    }).on('change', '.btn-category', function() {
                        location.replace('/desk/place/?sort=' + $(this).val() + '&keyword=<?=!empty($keyword) ? $keyword : ''?>&cate=' + $(this).val());
                    });
                </script>