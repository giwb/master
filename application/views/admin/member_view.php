<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

        <h2 class="sub-header mb-4"><?=$pageTitle?></h2>
        <div id="content" class="mb-5">
          <div class="row border-bottom mb-3 pb-2">
            <div class="col-sm-6">
              <h5>■ 기본 정보</h5>
            </div>
            <div class="col-sm-6 text-right">
              <a href="<?=BASE_URL?>/admin/member_list"><button type="button" class="btn btn-sm btn-secondary">목록으로</button></a>
              <button type="button" class="btn btn-sm btn-danger btn-user-login" data-idx="<?=$viewMember['idx']?>">이 사용자로 로그인</button>
            </div>
          </div>
          <form id="formMember" method="post" action="/admin/member_update">
            <input type="hidden" name="idx" value="<?=$viewMember['idx']?>">
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2">아이디</div>
              <div class="col-sm-10"><input type="text" readonly name="userid" value="<?=$viewMember['userid']?>" class="form-control"></div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2">닉네임</div>
              <div class="col-sm-10"><input type="text" name="nickname" value="<?=$viewMember['nickname']?>" class="form-control"></div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2">실명</div>
              <div class="col-sm-10"><input type="text" name="realname" value="<?=$viewMember['realname']?>" class="form-control"></div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2">전화번호</div>
              <div class="col-sm-10 row">
                <div class="pl-0"><input type="text" size="4" name="phone1" value="<?=!empty($viewMember['phone'][0]) ? $viewMember['phone'][0] : ''?>" class="form-control"></div>
                <div class="pl-2"><input type="text" size="4" name="phone2" value="<?=!empty($viewMember['phone'][1]) ? $viewMember['phone'][1] : ''?>" class="form-control"></div>
                <div class="pl-2"><input type="text" size="4" name="phone3" value="<?=!empty($viewMember['phone'][2]) ? $viewMember['phone'][2] : ''?>" class="form-control"></div>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2">생년월일</div>
              <div class="col-sm-10">
                <div class="row align-items-center">
                  <div class="pl-0">
                    <select name="birthday_year" class="form-control">
                      <?php foreach (range(1900, date('Y')) as $value): ?>
                      <option<?=!empty($viewMember['birthday'][0]) ? $viewMember['birthday'][0] == $value : '' ? ' selected' : ''?> value="<?=$value?>"><?=$value?>년</option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="pl-2">
                    <select name="birthday_month" class="form-control">
                      <?php foreach (range(1, 12) as $value): ?>
                      <option<?=!empty($viewMember['birthday'][1]) ? $viewMember['birthday'][1] == $value : '' ? ' selected' : ''?> value="<?=$value?>"><?=$value?>월</option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="pl-2">
                    <select name="birthday_day" class="form-control">
                      <?php foreach (range(1, 31) as $value): ?>
                      <option<?=!empty($viewMember['birthday'][2]) ? $viewMember['birthday'][2] == $value : '' ? ' selected' : ''?> value="<?=$value?>"><?=$value?>일</option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="row mt-2">
                  <label class="col col-sm-2 m-0 pl-0"><input type="radio" name="birthday_type" value="1"<?=$viewMember['birthday_type'] == '1' ? ' checked' : ''?>> 양력</label>
                  <label class="col col-sm-2 m-0"><input type="radio" name="birthday_type" value="2"<?=$viewMember['birthday_type'] == '2' ? ' checked' : ''?>> 음력</label>
                </div>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2">성별</div>
              <div class="col-sm-10 row align-items-center">
                <label class="col col-sm-2 m-0 pl-0"><input type="radio" name="gender" value="M"<?=$viewMember['gender'] == 'M' ? ' checked' : ''?>> 남성</label>
                <label class="col col-sm-2 m-0"><input type="radio" name="gender" value="F"<?=$viewMember['gender'] == 'F' ? ' checked' : ''?>> 여성</label>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2">승차위치</div>
              <div class="col-sm-10">
                <select name="location" class="form-control">
                  <?php foreach (arrLocation() as $value): ?>
                  <option<?=$viewMember['location'] == $value['no'] ? ' selected' : ''?> value="<?=$value['no']?>"><?=$value['title']?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2">회원 형태</div>
              <div class="col-sm-10">
                <label class="d-block m-0"><input<?=$viewMember['level'] == LEVEL_LIFETIME ? ' checked' : ''?> type="checkbox" name="level" value="<?=LEVEL_LIFETIME?>"> 평생회원</label>
                <label class="d-block m-0"><input<?=$viewMember['level'] == LEVEL_FREE ? ' checked' : ''?> type="checkbox" name="level" value="<?=LEVEL_FREE?>"> 무료회원</label>
                <label class="d-block m-0"><input<?=$viewMember['level'] == LEVEL_DRIVER ? ' checked' : ''?> type="checkbox" name="level" value="<?=LEVEL_DRIVER?>"> 드라이버</label>
                <label class="d-block m-0"><input<?=$viewMember['level'] == LEVEL_DRIVER_ADMIN ? ' checked' : ''?> type="checkbox" name="level" value="<?=LEVEL_DRIVER_ADMIN?>"> 드라이버 관리자</label>
                <label class="d-block m-0"><input<?=$viewMember['level'] == LEVEL_BLACKLIST ? ' checked' : ''?> type="checkbox" name="level" value="<?=LEVEL_BLACKLIST?>"> 블랙리스트</label>
                <label class="d-block m-0"><input<?=$viewMember['admin'] == 1 ? ' checked' : ''?> type="checkbox" name="admin" value="1"> 관리자</label>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2">등급</div>
              <div class="col-sm-10"><?=$viewMember['memberLevel']['levelName']?></div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2">예약횟수</div>
              <div class="col-sm-10"><?=$viewMember['rescount']?></div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2">포인트</div>
              <div class="col-sm-10 row align-items-center">
                <div class="col-sm-4 pl-0"><?=$viewMember['point']?></div>
                <div class="col-sm-5">
                  <input type="text" name="point" class="form-control form-control-sm">
                </div>
                <div class="col-sm-3 p-0">
                  <button type="button" class="btn btn-sm btn-primary btn-point-modal mr-2" data-type="1">추가</button>
                  <button type="button" class="btn btn-sm btn-danger btn-point-modal" data-type="2">감소</button>
                </div>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2">페널티</div>
              <div class="col-sm-10 row align-items-center">
                <div class="col-sm-4 pl-0"><?=$viewMember['penalty']?></div>
                <div class="col-sm-5">
                  <input type="text" name="penalty" class="form-control form-control-sm">
                </div>
                <div class="col-sm-3 p-0">
                  <button type="button" class="btn btn-sm btn-primary btn-point-modal mr-2" data-type="3">추가</button>
                  <button type="button" class="btn btn-sm btn-danger btn-point-modal" data-type="4">감소</button>
                </div>
              </div>
            </div>
            <div class="row align-items-center border-bottom mb-3 pb-3">
              <div class="col-sm-2">아이콘</div>
              <div class="col-sm-10">
                <?php if (file_exists(PHOTO_PATH . $viewMember['idx'])): ?>
                  <img src="<?=PHOTO_URL?><?=$viewMember['idx']?>" style="max-width: 100px;">
                <?php else: ?>
                  <img src="/public/images/user.png">
                <?php endif; ?>
              </div>
            </div>
            <div class="pt-2 pb-5 text-center">
              <div class="error-message"></div>
              <input type="hidden" name="back_url" value="member_list">
              <button type="button" class="btn btn-primary btn-member-update">수정합니다</button>
              <button type="button" class="btn btn-secondary btn-member-delete-modal">삭제합니다</button>
            </div>
          </form>
          <div class="mt-4 mb-3 pb-3">
            <h5>■ 예약 내역</h5>
            <?php foreach ($userReserve as $key => $value): ?>
            <div class="border-top pt-2 pb-2">
              <?=viewStatus($value['notice_status'])?> <a href="<?=BASE_URL?>/reserve/list/<?=$value['resCode']?>"><?=$value['subject']?></a> - <?=checkDirection($value['seat'], $value['bus'], $value['notice_bustype'], $value['notice_bus'])?>번 좌석<br>
              <small>
                일시 : <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / 
                요금 : <?=$value['view_cost']?> /
                <?=!empty($value['status']) && $value['status'] == STATUS_ABLE ? '입금완료' : '입금대기'?>
                <?=!empty($value['depositname']) ? ' / 입금자 : ' . $value['depositname'] : ''?>
              </small>
            </div>
            <?php endforeach; ?>

            <h5 class="mt-4">■ 예약취소 내역</h5>
            <?php foreach ($userReserveCancel as $value): ?>
            <div class="border-top pt-2 pb-2">
              <?=viewStatus($value['notice_status'])?> <a href="/admin/main_view_progress/<?=$value['resCode']?>"><?=$value['subject']?></a><br>
              <small>취소일시 : <?=date('Y-m-d', $value['regdate'])?> (<?=calcWeek(date('Y-m-d', $value['regdate']))?>) <?=date('H:i', $value['regdate'])?></small>
            </div>
            <?php endforeach; ?>

            <h5 class="mt-4">■ 산행 내역</h5>
            <?php foreach ($userVisit as $value): ?>
            <div class="border-top pt-2 pb-2">
              <?=viewStatus($value['notice_status'])?> <a href="/reserve/?n=<?=$value['resCode']?>"><?=$value['subject']?></a> - <?=checkDirection($value['seat'], $value['bus'], $value['notice_bustype'], $value['notice_bus'])?>번 좌석<br>
              <small>
                일시 : <?=$value['startdate']?> (<?=calcWeek($value['startdate'])?>) <?=$value['starttime']?> / 
                요금 : <?=number_format($value['cost_total'] == 0 ? $value['cost'] : $value['cost_total'])?>원
              </small>
            </div>
            <?php endforeach; ?>

            <h5 class="mt-4">■ 포인트 내역 <small>- 잔액 <?=number_format($viewMember['point'])?> 포인트</small></h5>
            <div class="area-list" data-type="point">
              <?php foreach ($userPoint as $value): ?>
              <div class="border-top pt-2 pb-2 row align-items-center">
                <div class="col-10 col-sm-11 p-0">
                  <?php switch ($value['action']): case LOG_POINTUP: ?>
                  <strong class="text-primary"><?=number_format($value['point'])?> 포인트 추가</strong> - <?=$value['subject']?>
                  <?php break; case LOG_POINTDN: ?>
                  <strong class="text-danger"><?=number_format($value['point'])?> 포인트 감소</strong> - <?=$value['subject']?>
                  <?php break; endswitch; ?>
                  <br><small><?=date('Y-m-d, H:i:s', $value['regdate'])?></small>
                </div>
                <div class="col-2 col-sm-1 text-right p-0">
                  <button type="button" data-idx="<?=$value['idx']?>" class="btn btn-sm btn-default btn-history-delete-modal">삭제</button>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
            <div class="border-top text-right small pt-2">
              <a href="javascript:;" class="btn-member-more" data-userid="<?=$viewMember['userid']?>" data-type="point">더보기 ▼</a>
              <input type="hidden" class="page" data-type="point" value="1">
            </div>

            <h5 class="mt-4">■ 페널티 내역</h5>
            <div class="area-list" data-type="penalty">
              <?php foreach ($userPenalty as $value): ?>
              <div class="border-top pt-2 pb-2 row align-items-center">
                <div class="col-10 col-sm-11 p-0">
                  <?php switch ($value['action']): case LOG_PENALTYUP: ?>
                  <strong class="text-primary"><?=number_format($value['point'])?> 페널티 추가</strong> - <?=$value['subject']?>
                  <?php break; case LOG_PENALTYDN: ?>
                  <strong class="text-danger"><?=number_format($value['point'])?> 페널티 감소</strong> - <?=$value['subject']?>
                  <?php break; endswitch; ?>
                  <br><small><?=date('Y-m-d, H:i:s', $value['regdate'])?></small>
                </div>
                <div class="col-2 col-sm-1 text-right p-0">
                  <button type="button" data-idx="<?=$value['idx']?>" class="btn btn-sm btn-default btn-history-delete-modal">삭제</button>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
            <div class="border-top text-right small pt-2">
              <a href="javascript:;" class="btn-member-more" data-userid="<?=$viewMember['userid']?>" data-type="penalty">더보기 ▼</a>
              <input type="hidden" class="page" data-type="penalty" value="1">
            </div>
          </div>
        </div>

        <!-- 페널티/포인트 추가 모달 -->
        <div class="modal fade" id="pointModal" tabindex="-1" role="dialog" aria-labelledby="pointModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="smallmodalLabel">메세지</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body text-center">
                <p class="modal-message"></p>
                <div class="row align-items-center">
                    <div class="col-sm-2 pl-0 pr-0">사유</div>
                    <div class="col-sm-10 pl-0"><input type="text" name="subject" class="form-control"></div>
                  </div>
              </div>
              <div class="modal-footer">
                <input type="hidden" name="action">
                <input type="hidden" name="type">
                <button type="button" class="btn btn-primary btn-member-point-update">승인</button>
                <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
              </div>
            </div>
          </div>
        </div>

        <!-- 포인트/페널티 내역 삭제 모달 -->
        <div class="modal fade" id="historyDeleteModal" tabindex="-1" role="dialog" aria-labelledby="historyDeleteModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="smallmodalLabel">메세지</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body text-center p-5">
                정말로 삭제하시겠습니까?
              </div>
              <div class="modal-footer">
                <input type="hidden" name="idx">
                <button type="button" class="btn btn-default btn-history-delete">삭제합니다</button>
                <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">닫기</button>
              </div>
            </div>
          </div>
        </div>
