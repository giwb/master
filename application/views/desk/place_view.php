<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-7"><h1 class="h3 mb-3 text-gray-800">여행정보 확인</h1></div>
                        <div class="col-5 text-right"><a href="/desk/place"><button type="button" class="btn btn-secondary">여행정보 목록</button></a></div>
                    </div>
                    <div class="card shadow pt-3 mb-3">
                        <div class="card-body">
                            <div class="place-data">
                                <h3><?=$view['title']?></h3>
                                <div class="border p-4">
                                    <?=reset_html_escape($view['content'])?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 mb-5">
                        <div class="col-6"><a href="/desk/place_post/<?=$view['idx']?>"><button type="button" class="btn btn-primary mr-4">수정하기</button></a></div>
                        <div class="col-6 text-right"><button type="button" data-idx="<?=$view['idx']?>" class="btn btn-danger btn-modal-place">삭제하기</button></div>
                    </div>
                </div>
