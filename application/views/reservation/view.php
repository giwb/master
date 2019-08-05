<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

  <section id="subpage">
    <h2 class="reservation-title">
      <b>[<?=viewStatus($viewNotice['status'])?>]</b> <?=$viewNotice['subject']?>
      <div class="description">산행분담금 : <?=number_format($viewNotice['cost'])?>원 (<?=$viewNotice['schedule']?><?=$viewNotice['distance'] != '' ? ',' . $viewNotice['distance'] : ''?>)</div>
    </h2>
  </section>
