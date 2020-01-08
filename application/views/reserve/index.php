<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

      <script>
        new ClipboardJS('.btn-share-url');
      </script>

      <div class="club-main">
        <?php if (empty($notice['idx'])): ?>
        <div class="text-center mt-5">데이터가 없습니다.</div>
        <?php else: ?>
        <div class="sub-contents">
          <div class="sub-title">
            <div class="area-title"><h2><b><?=viewStatus($notice['status'])?></b> <?=$notice['subject']?></h2></div>
            <div class="area-btn"><a href="<?=base_url()?>reserve/notice/<?=$view['idx']?>?n=<?=$notice['idx']?>"><button type="button" class="btn btn-primary">산행공지</button></a></div>
          </div>
          <strong>・일시</strong> : <?=$notice['startdate']?> (<?=calcWeek($notice['startdate'])?>) <?=$notice['starttime']?><br>
          <?php $notice['cost'] = $notice['cost_total'] == 0 ? $notice['cost'] : $notice['cost_total']; if (!empty($notice['cost'])): ?>
          <?php if (!empty($notice['sido'])): ?>
          <strong>・지역</strong> : <?php foreach ($notice['sido'] as $key => $value): if ($key != 0): ?>, <?php endif; ?><?=$value?> <?=!empty($notice['gugun'][$key]) ? $notice['gugun'][$key] : ''?><?php endforeach; ?><br>
          <?php endif; ?>
          <strong>・분담금</strong> : <?=number_format($notice['cost_total'] == 0 ? $notice['cost'] : $notice['cost_total'])?>원 (<?=calcTerm($notice['startdate'], $notice['starttime'], $notice['enddate'], $notice['schedule'])?><?=!empty($notice['distance']) ? ', ' . calcDistance($notice['distance']) : ''?><?=!empty($notice['costmemo']) ? ', ' . $notice['costmemo'] : ''?>)<br>
          <?php endif; ?>
          <?=!empty($notice['content']) ? "<strong>・코스</strong> : " . nl2br($notice['content']) . "<br>" : ""?>
          <strong>・예약인원</strong> : <?=cntRes($notice['idx'])?>명

          <div class="area-reservation">
            <?php
              // 이번 산행에 등록된 버스 루프
              foreach ($busType as $key => $value):
                $bus = $key + 1;
                $maxRes = cntRes($notice['idx'], $bus);
            ?>
            <div class="area-bus-table">
              <table>
                <colgroup>
                  <col width="4%"></col>
                  <col width="16%"></col>
                  <col width="4%"></col>
                  <col width="16%"></col>
                  <col width="4%"></col>
                  <col width="16%"></col>
                  <col width="4%"></col>
                  <col width="16%"></col>
                  <col width="4%"></col>
                  <col width="16%"></col>
                </colgroup>
                <thead>
                  <tr>
                    <th colspan="10"><?=count($busType) >= 2 ? $bus . '호차 - ' : ''?><?=$value['bus_name']?> <?=!empty($value['bus_license']) ? '(' . $value['bus_license'] . ')' : ''?></td>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($value['seat'] > 13): ?>
                  <tr>
                    <th colspan="4" class="text-left">운전석<?=!empty($value['bus_owner']) ? ' (' . $value['bus_owner'] . ' 기사님)' : ''?></th>
                    <th colspan="6" class="text-right">출입구 (예약 : <?=$maxRes?>명)</th>
                  </tr>
                  <?php endif; ?>
                  <?php
                      // 버스 형태 좌석 배치
                      foreach (range(1, $value['seat']) as $seat):
                        $tableMake = getBusTableMake($value['seat'], $seat); // 버스 좌석 테이블 만들기
                        $reserveInfo = getReserve($reserve, $bus, $seat, $userData, $notice['status']); // 예약자 정보
                        $seatNumber = checkDirection($seat, $bus, $notice['bustype'], $notice['bus']);
                  ?>
                  <?=$tableMake?>
                    <td class="<?=$reserveInfo['class']?>" data-id="<?=$reserveInfo['idx']?>"<?=!empty($reserveInfo['priority']) ? ' data-priority="' . $reserveInfo['priority'] . '"' : ''?> data-bus="<?=$bus?>" data-seat="<?=$seat?>"><?=$seatNumber?></td>
                    <td class="<?=$reserveInfo['class']?>" data-id="<?=$reserveInfo['idx']?>"<?=!empty($reserveInfo['priority']) ? ' data-priority="' . $reserveInfo['priority'] . '"' : ''?> data-bus="<?=$bus?>" data-seat="<?=$seat?>"><?=$reserveInfo['nickname']?></td>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <?php endforeach; ?>
            <?php if ($notice['status'] != STATUS_CLOSED): ?>
            <?php if ($maxRes == $value['seat']): $cntWait = cntWait($view['idx'], $notice['idx']); ?>
            <div class="area-wait text-center mt-3 mb-4">
              현재 예약 대기자로 <span class="cnt-wait"><?=$cntWait?></span>명이 등록되어 있습니다.<br>
              <form id="waitForm" method="post" action="<?=base_url()?>reserve/wait_insert" class="mt-3">
                <div id="addedWait"></div>
                <input type="hidden" name="clubIdx" value="<?=!empty($view['idx']) ? $view['idx'] : ''?>">
                <input type="hidden" name="noticeIdx" value="<?=!empty($notice['idx']) ? $notice['idx'] : ''?>">
                <input type="hidden" name="userIdx" value="<?=!empty($userData['idx']) ? $userData['idx'] : ''?>">
                <input type="hidden" name="userLocation" value="<?=!empty($userData['location']) ? $userData['location'] : ''?>">
                <input type="hidden" name="userGender" value="<?=!empty($userData['gender']) ? $userData['gender'] : ''?>">
                <?php $checkWait = checkWait($view['idx'], $notice['idx'], $userData['idx']); if (empty($checkWait)): ?>
                <button type="button" class="btn btn-primary btn-reserve-wait-add">대기자 등록</button>
                <button type="button" class="btn btn-primary btn-reserve-wait d-none">대기자 등록</button>
                <?php else: ?>
                <button type="button" class="btn btn-secondary btn-reserve-wait">대기자 취소</button>
                <?php endif; ?>
              </form>
            </div>
            <?php endif; ?>
            <form id="reserveForm" method="post" action="<?=base_url()?>reserve/insert">
              <div id="addedInfo"></div>
              <input type="hidden" name="clubIdx" value="<?=!empty($view['idx']) ? $view['idx'] : ''?>">
              <input type="hidden" name="userIdx" value="<?=!empty($userData['idx']) ? $userData['idx'] : ''?>">
              <input type="hidden" name="noticeIdx" value="<?=!empty($notice['idx']) ? $notice['idx'] : ''?>">
              <button type="button" class="btn btn-primary btn-reserve-confirm">예약합니다</button>
              <button type="button" class="btn btn-secondary btn-reserve-cancel d-none">취소합니다</button>
            </form>
            <?php endif; ?>
          </div>
          <div class="story-reaction mt-5">
            <button type="button" data-idx="<?=$notice['idx']?>" data-type="<?=REPLY_TYPE_NOTICE?>"><i class="fa fa-reply" aria-hidden="true"></i> 댓글 <span class="cnt-reply" data-idx="<?=$notice['idx']?>"><?=$notice['reply_cnt']?></span></button>
            <button type="button" class="btn-like<?=!empty($notice['like']) ? ' text-danger' : ''?>" data-idx="<?=$notice['idx']?>" data-type="<?=REACTION_TYPE_NOTICE?>"><i class="fa fa-heart" aria-hidden="true"></i> 좋아요 <span class="cnt-like"><?=$notice['like_cnt']?></span></button>
            <button type="button" class="btn-share" data-idx="<?=$notice['idx']?>"><i class="fa fa-share-alt" aria-hidden="true"></i> 공유하기 <span class="cnt-share"><?=$notice['share_cnt']?></span></button>
            <div class="area-share" data-idx="<?=$notice['idx']?>">
              <ul>
                <li><a href="javascript:;" class="btn-share-sns" data-idx="<?=$notice['idx']?>" data-reaction-type="<?=REACTION_TYPE_NOTICE?>" data-type="<?=SHARE_TYPE_FACEBOOK?>" data-url="https://facebook.com/sharer/sharer.php?u=<?=base_url()?>reserve/<?=$view['idx']?>?n=<?=$notice['idx']?>"><img src="<?=base_url()?>public/images/icon_facebook.png"><br>페이스북</a></li>
                <li><a href="javascript:;" class="btn-share-sns" data-idx="<?=$notice['idx']?>" data-reaction-type="<?=REACTION_TYPE_NOTICE?>" data-type="<?=SHARE_TYPE_TWITTER?>" data-url="https://twitter.com/intent/tweet?url=<?=base_url()?>reserve/<?=$view['idx']?>?n=<?=$notice['idx']?>"><img src="<?=base_url()?>public/images/icon_twitter.png"><br>트위터</a></li>
                <li><a href="javascript:;" class="btn-share-url" data-idx="<?=$notice['idx']?>" data-reaction-type="<?=REACTION_TYPE_NOTICE?>" data-type="<?=SHARE_TYPE_URL?>" data-trigger="click" data-placement="bottom" data-clipboard-text="<?=base_url()?>reserve/<?=$view['idx']?>?n=<?=$notice['idx']?>"><img src="<?=base_url()?>public/images/icon_url.png"><br>URL</a></li>
              </ul>
            </div>
          </div>
          <div class="story-reply mt-3 reply-type-<?=REPLY_TYPE_NOTICE?>" data-idx="<?=$notice['idx']?>">
            <div class="story-reply-content">
              <?php
              foreach ($listReply as $value):
                if (file_exists(PHOTO_PATH . $value['created_by'])) {
                  $value['photo'] = base_url() . PHOTO_URL . $value['created_by'];
                } else {
                  $value['photo'] = base_url() . 'public/images/user.png';
                }
              ?>
              <dl><dt><img class="img-profile" src="<?=$value['photo']?>"></dt><dd><strong><?=$value['nickname']?></strong> · <span class="reply-date">(<?=calcStoryTime($value['created_at'])?>)</span><?=$userData['idx'] == $value['created_by'] || $userData['admin'] == 1 ? ' | <a href="javascript:;" class="btn-post-delete-modal" data-idx="' . $value['idx'] . '" data-action="delete_reply">삭제</a>' : ''?><br><?=$value['content']?></dd></dl>
              <?php endforeach; ?>
            </div>
            <form method="post" action="<?=base_url()?>story/insert_reply/<?=$view['idx']?>" class="story-reply-input" data-idx="<?=$notice['idx']?>">
              <input type="hidden" name="storyIdx" value="<?=$notice['idx']?>">
              <input type="hidden" name="replyType" value="<?=REPLY_TYPE_NOTICE?>">
              <textarea name="content" class="club-story-reply"></textarea>
              <button type="button" class="btn btn-primary btn-post-reply" data-idx="<?=$notice['idx']?>">댓글달기</button>
            </form>
          </div>
        </div>
        <?php endif; ?>

        <div class="area-schedule">
          <h3><i class="fa fa-calendar" aria-hidden="true"></i> 현재 진행중인 산행</h3>
          <div class="list-schedule">
            <?php foreach ($listNotice as $value): ?>
            <a href="<?=base_url()?>reserve/<?=$value['club_idx']?>?n=<?=$value['idx']?>"><?=viewStatus($value['status'])?> <strong><?=$value['subject']?></strong><br><?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원 / <?=cntRes($value['idx'])?>명</a>
            <?php endforeach; ?>
          </div>
          <div class="ad-sp">
            <!-- SP_CENTER -->
            <ins class="adsbygoogle"
              style="display:block"
              data-ad-client="ca-pub-2424708381875991"
              data-ad-slot="4319659782"
              data-ad-format="auto"
              data-full-width-responsive="true"></ins>
            <script>
              (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
          </div>
        </div>
      </div>

      <script type="text/javascript">
        var arrLocation = new Array();
        <?php foreach ($arrLocation as $value): ?>
          arrLocation.push('<?=$value['stitle']?>');
        <?php endforeach; ?>
      </script>
