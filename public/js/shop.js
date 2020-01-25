$(document).on('click', '.shop-item', function() {
  // 용품 상세 페이지
  location.href = ( $('input[name=baseUrl]').val() + '/shop/item/?n=' + $(this).data('idx') );
}).on('click', '.btn-cart-insert', function() {
  // 장바구니에 담기
  var $btn = $(this);
  var $dom = $('#shopForm');
  var formData = new FormData($dom[0]);
  var baseUrl = $('input[name=baseUrl]').val();
  var amount = $('.area-option .item-amount').val();
  var type = $(this).data('type');

  if (typeof amount == 'undefined') {
    $.openMsgModal('옵션은 꼭 선택해주세요.');
    return false;
  }

  formData.append('type', type);

  $.ajax({
    url: $dom.attr('action'),
    processData: false,
    contentType: false,
    data: formData,
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $btn.css('opacity', '0.5').prop('disabled', true);
    },
    success: function(result) {
      if (result.error == 1) {
        $btn.css('opacity', '1').prop('disabled', false);
        $.openMsgModal(result.message);
      } else {
        if (type == 'buy') {
          location.href = (baseUrl + '/shop/checkout');
        } else {
          location.href = (baseUrl + '/shop/cart');
        }
      }
    }
  });
}).on('change', '.item-option', function() {
  // 옵션 선택
  var $dom = $(this);
  var option = '<div class="row align-items-center border ml-0 mr-0 mb-2 pt-2 pb-2"><div class="col-3"><select name="amount[]" class="item-amount form-control form-control-sm pl-1 pr-0"><option value="1">1개</option><option value="2">2개</option><option value="3">3개</option><option value="4">4개</option><option value="5">5개</option><option value="6">6개</option><option value="7">7개</option><option value="8">8개</option><option value="9">9개</option><option value="10">10개</option></select></div><div class="col-9">';
  option += $('option:selected', $dom).text() + '<input type="hidden" name="option[]" value="' + $('option:selected', $dom).val() + '"></div></div>';
  $('.area-option').append(option);
  $('option:selected', $dom).remove();
  $dom.val('');
}).on('change', '.cart-amount', function() {
  // 수량 변경
  var $dom = $(this);
  var rowid = $(this).data('rowid');
  var amount = $(this).val();
  $.ajax({
    url: '/shop/cart_update',
    data: 'rowid=' + rowid + '&amount=' + amount,
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $dom.css('opacity', '0.5').prop('disabled', true);
    },
    success: function(result) {
      if (result.error == 1) {
        $dom.css('opacity', '1').prop('disabled', false);
        $.openMsgModal(result.message);
      } else {
        location.reload();
      }
    }
  });
}).on('click', '.btn-cart-delete', function() {
  // 장바구니 삭제
  var $btn = $(this);
  var rowid = $(this).data('rowid');
  var amount = 0;
  $.ajax({
    url: '/shop/cart_update',
    data: 'rowid=' + rowid + '&amount=' + amount,
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $btn.css('opacity', '0.5').prop('disabled', true);
    },
    success: function(result) {
      if (result.error == 1) {
        $btn.css('opacity', '1').prop('disabled', false);
        $.openMsgModal(result.message);
      } else {
        location.reload();
      }
    }
  });
}).on('blur', '.using-point', function() {
  // 포인트 사용
  var result = 0;
  var usingPoint = Number($(this).val());
  var userPoint = Number($('input[name=userPoint]').val());
  var totalCost = Number($('input[name=totalCost]').val());
  var paymentCost = Number($('input[name=paymentCost]').val());

  if (usingPoint > userPoint) {
    $.openMsgModal('보유한 포인트만 사용할 수 있습니다.');
    $(this).val('');
    $('input[name=paymentCost]').val(totalCost);
    $('.userPoint').text($.setNumberFormat(userPoint));
    $('.paymentCost').text($.setNumberFormat(totalCost));
    return false;
  }
  if (usingPoint < 0) {
    usingPoint = '';
    $('input[name=usingPoint]').val(usingPoint);
    return false;
  }
  if (usingPoint > totalCost) {
    usingPoint = totalCost;
    $('input[name=usingPoint]').val(usingPoint);
  }

  paymentCost = totalCost - usingPoint;
  userPoint = userPoint - usingPoint;

  $('input[name=paymentCost]').val(paymentCost);
  $('.userPoint').text($.setNumberFormat(userPoint));
  $('.paymentCost').text($.setNumberFormat(paymentCost));
}).on('click', '.using-point-all', function() {
  // 포인트 전액 사용
  var result = 0;
  var usingPoint = Number($('.using-point').val()); // 사용한 포인트
  var userPoint = Number($('input[name=userPoint]').val()); // 사용자 보유 총 포인트
  var totalCost = Number($('input[name=totalCost]').val()); // 합계금액
  var paymentCost = Number($('input[name=paymentCost]').val()); // 결제금액

  if (userPoint > usingPoint) {
    if (paymentCost > userPoint) {
      // 결제금액이 사용자 보유 총 포인트보다 높을때는 남은 포인트만큼만 결제
      usingPoint = userPoint;
      paymentCost = totalCost - userPoint;
      userPoint = 0;
    } else {
      // 결제금액이 총 포인트보다 낮을때는 결제금액 전액 결제
      usingPoint = totalCost;
      userPoint = userPoint - usingPoint;
      paymentCost = 0;
    }

    $('input[name=usingPoint]').val(usingPoint);
    $('input[name=paymentCost]').val(paymentCost);
    $('.userPoint').text($.setNumberFormat(userPoint));
    $('.paymentCost').text($.setNumberFormat(paymentCost));
  }
}).on('click', '.btn-checkout', function() {
  // 구매 완료하기
  var $btn = $(this);
  var $dom = $('#formCheckout');
  var baseUrl = $('input[name=baseUrl]').val();
  var formData = new FormData($dom[0]);
  $.ajax({
    url: $dom.attr('action'),
    processData: false,
    contentType: false,
    data: formData,
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
    },
    success: function(result) {
      if (result.error == 1) {
        $btn.css('opacity', '1').prop('disabled', false).text('구매 완료하기');
        $.openMsgModal(result.message);
      } else {
        location.href = (baseUrl + '/shop/complete/?n=' + result.message);
      }
    }
  });
});