<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

          <section class="mb-5">
            <div class="row no-gutters">
              <div class="col-5 col-sm-8"><h4 class="font-weight-bold">관리자 페이지</h4></div>
              <div class="col-7 col-sm-4 text-right">
                <form method="post" action="<?=BASE_URL?>/admin/log_reserve">
                  <div class="row no-gutters">
                    <div class="col-9 col-sm-10 pr-2"><input type="text" name="k" value="<?=!empty($keyword) ? $keyword : ''?>" class="form-control form-control-sm"></div>
                    <div class="col-3 col-sm-2"><button class="btn-custom btn-giwb h-100">검색</button></div>
                  </div>
                </form>
              </div>
            </div>
            <hr class="text-default mt-2">

            <div style="min-height: 500px;">
            </div>
          </section>
