<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="row-category mt-3 mb-3">
          <div class="bg-<?=$view['main_color']?> pt-1"></div>
          <div class="row border-right small text-center m-0 p-0">
            <?php foreach ($listAbout as $value): ?>
            <a href="<?=BASE_URL?>/club/about/<?=$value['idx']?>" class="col-6 border-left border-bottom pt-2 pb-2 pl-0 pr-0<?=$pageIdx == $value['idx'] ? ' active' : ''?>"><?=$value['title']?></a><br>
            <?php endforeach; ?>
            <?php if (!empty($userLevel['levelType']) && $userLevel['levelType'] >= 1): ?>
            <a href="<?=BASE_URL?>/club/past" class="<?=count($listAbout)%2 == 0 ? 'col-12' : 'col-6'?> border-left border-bottom pt-2 pb-2 pl-0 pr-0">지난산행</a><br>
            <?php endif; ?>
          </div>
        </div>
        <h2 class="sub-header"><?=$pageTitle?></h2>
        <div class="sub-content"><br>
          <table class="auth">
            <colgroup>
              <col width="5%">
              <col width="17%">
              <col width="8%">
              <col width="70%">
            </colgroup>
            <tr>
              <th>No.</th>
              <th>닉네임</th>
              <th>횟수</th>
              <th>산행인증내역</th>
            </tr>
            <?php foreach ($auth as $value): ?>
            <tr>
              <td><?=$value['rank']?></td>
              <td><?=$value['nickname']?>님</td>
              <td><?=$value['cnt']?></td>
              <td><?=$value['title']?></td>
            </tr>
            <?php endforeach; ?>
          </table>
        </div><br>
      </div>
