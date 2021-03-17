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
                <col width="8%">
                <col width="70%">
              </colgroup>
              <tr>
                <th>No.</th>
                <th>닉네임</th>
                <th>횟수</th>
                <th>산행인증내역</th>
              </tr>
              <?php foreach ($auth as $value): if ($value['nickname'] != '아띠'): ?>
              <tr>
                <td><?=$value['rank']?></td>
                <td><?=$value['nickname']?>님</td>
                <td><?=$value['cnt']?></td>
                <td><?=$value['title']?></td>
              </tr>
              <?php endif; endforeach; ?>
            </table>
          </div>
        </div>
