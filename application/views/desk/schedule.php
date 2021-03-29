<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-7"><h1 class="h3 mb-3 text-gray-800">여행일정 관리</h1></div>
                        <div class="col-5 text-right"><a href="/desk/schedule_post"><button class="btn btn-primary">여행일정 등록</button></a></div>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">현재 등록된 여행일정 : <?=count($list)?>건</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr align="center" bgcolor="#e9e9e9">
                                            <th width="5%">번호</th>
                                            <th width="10%">등록일시</th>
                                            <th width="15%">여행사명</th>
                                            <th width="19%">지역</th>
                                            <th>타이틀</th>
                                            <th width="10%">편집</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($list as $key => $value): ?>
                                        <tr data-link="<?=base_url()?>desk/schedule_post/<?=$value['idx']?>" class="area-link">
                                            <td class="text-center"><?=$max - $key?></td>
                                            <td class="text-center small"><?=date('Y-m-d H:i', $value['created_at'])?></td>
                                            <td class="text-center"><?=$value['agency_name']?></td>
                                            <td class="text-center"><?=!empty($value['sido'][0]) ? $value['sido'][0] : ''?> <?=!empty($value['gugun'][0]) ? $value['gugun'][0] : ''?><?=count($value['sido']) > 1 ? ' 등' : ''?></td>
                                            <td><?=$value['title']?></td>
                                            <td class="text-center">
                                                <button type="button" data-link="<?=base_url()?>desk/schedule_post/<?=$value['idx']?>" class="btn btn-sm btn-secondary btn-update">수정</button>
                                                <button type="button" data-idx="<?=$value['idx']?>" data-action="schedule_delete" class="btn btn-sm btn-danger btn-modal-delete">삭제</button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
