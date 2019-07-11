<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <div id="right-panel" class="right-panel">
    <div class="breadcrumbs">
      <div class="col-sm-4">
        <div class="page-header float-left">
          <div class="page-title">
              <h1>전체 회원 목록</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <table class="table">
          <tbody>
<?php
  foreach ($list as $value) {
?>
            <tr>
              <td>
                <a href="#"><?=$value["realname"]?> / <?=$value["nickname"]?> / <?=$value["userid"]?></a><br>
                <?=$value["phone"]?>, <?=$value["birthday"]?> <?=$value["birthday_type"] == "1" ? "(양력)" : "(음력)" ?>, <?=$value["location"]?><br />
                등록일 : <?=date("Y-m-d, H:i:s", $value["regdate"])?>, <?php if ($value["lastdate"] != NULL) { echo "최종접속일 : " . date("Y-m-d, H:i:s", $value["lastdate"]) . ", "; } ?>접속횟수 : <?=$value["connect"]?>
              </td>
            </tr>
<?php
  }
?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
