<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  $uri = $_SERVER['REQUEST_URI'];
?>

          <section class="mb-3">
            <div class="row no-gutters">
              <div class="col-4"><h4 class="font-weight-bold"><?=$pageTitle?></h4></div>
              <div class="col-8 text-right">
                <a href="<?=BASE_URL?>/admin/main_list_progress"><button class="btn-custom btn-blue small">진행</button></a>
                <a href="<?=BASE_URL?>/admin/main_list_closed"><button class="btn-custom btn-gray small">종료</button></a>
                <a href="<?=BASE_URL?>/admin/main_list_canceled"><button class="btn-custom btn-dark small">취소</button></a>
                <a href="<?=BASE_URL?>/admin/main_entry"><button class="btn-custom btn-giwb small">등록</button></a>
              </div>
            </div>
            <hr class="text-default mt-2">
          </section>