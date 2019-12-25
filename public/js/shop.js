$(document).on('click', '.shop-item', function() {
  // 용품 상세 페이지
  location.href = ( $('input[name=baseUrl]').val() + 'club/shop_item/' + $('input[name=clubIdx]').val() + '?n=' + $(this).data('idx') );
}).on('click', '.btn-cart', function() {
  // 장바구니에 담기
  var $btn = $(this);
  var baseUrl = $('input[name=baseUrl]').val();
  var clubIdx = $('input[name=clubIdx]').val();
  var idx = $(this).data('idx');
  $.ajax({
    url: baseUrl + 'club/shop_cart_insert/' + clubIdx,
    data: 'idx=' + idx,
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
    },
    success: function(result) {
      if (result.error == 1) {
        $btn.css('opacity', '1').prop('disabled', false).text('장바구니에 담기');
        $.openMsgModal(result.message);
      } else {
        location.href = (result.message);
      }
    }
  })
}).on('change', '.cart-amount', function() {
  // 장바구니에 담기
  var $dom = $(this);
  var baseUrl = $('input[name=baseUrl]').val();
  var clubIdx = $('input[name=clubIdx]').val();
  var rowid = $(this).data('rowid');
  var amount = $(this).val();
  $.ajax({
    url: baseUrl + 'club/shop_cart_update/' + clubIdx,
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
        location.href = (result.message);
      }
    }
  })
}).on('click', '.btn-cart-delete', function() {
  // 장바구니 삭제
  var $btn = $(this);
  var baseUrl = $('input[name=baseUrl]').val();
  var clubIdx = $('input[name=clubIdx]').val();
  var rowid = $(this).data('rowid');
  var amount = 0;
  $.ajax({
    url: baseUrl + 'club/shop_cart_update/' + clubIdx,
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
        location.href = (result.message);
      }
    }
  })
});
