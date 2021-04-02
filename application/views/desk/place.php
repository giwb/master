<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-7"><h1 class="h3 mb-3 text-gray-800">여행정보 관리</h1></div>
                        <div class="col-5 text-right"><a href="/desk/place_post"><button class="btn btn-primary">여행정보 등록</button></a></div>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">현재 등록된 정보 : <?=count($list)?>건</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
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
                                            <td class="text-center"><?=$value['category_name']?></td>
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
                    </div>
                </div>
