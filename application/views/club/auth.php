<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <div class="club-main">
        <div class="sub-header">백산백소 인증현황</div>
        <div class="sub-content">

          <br>
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

        </div>
      </div>
