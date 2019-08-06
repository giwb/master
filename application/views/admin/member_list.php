<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">전체 회원 목록</h1>
        </div>

        <table class="table">
          <tbody>
<?php
  foreach ($list as $value) {
?>
            <tr>
              <td>
                <a href="<?=base_url()?>admin/member_view/<?=$value['idx']?>"><?=$value["realname"]?> / <?=$value["nickname"]?> / <?=$value["userid"]?></a><br>
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
