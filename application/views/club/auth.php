<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <main id="club">
    <div class="container-fluid club-main">
      <div class="row mt-1 mb-5">
        <div class="col-xl-8 col-md-12">

          <h4 class="font-weight-bold"><?=$pageTitle?></h4>
          <hr class="text-default">

          <div class="sub-content"><br>
            <table width="100%" class="auth">
              <colgroup>
                <col width="5%">
                <col width="17%">
                <col width="78%">
              </colgroup>
              <tr>
                <th>No.</th>
                <th>닉네임</th>
                <th>횟수</th>
              </tr>
              <?php foreach ($auth as $key => $value): ?>
              <tr>
                <td align="center">
                  <?php if ($value['rank'] <= 5): ?><img src="/public/images/<?=$value['rank']?>">
                  <?php else: ?><?=$value['rank']?><?php endif; ?>
                </td>
                <td><?=$value['nickname']?>님</td>
                <td>
                  <div class="auth-progress-bar"><div id="medal<?=$key?>" class="auth-gauge" cnt="<?=$value['cnt']?>"><?=$value['cnt']?></div></div>
                </td>
              </tr>
              <?php endforeach; ?>
            </table>
          </div>
        </div>
