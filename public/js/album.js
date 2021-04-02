$(document).on('click', '.btn-album-insert', function() {
  // 등록
  if ($('select[name=noticeIdx]').val() == '') {
    $.openMsgModal('다녀온 여행은 꼭 선택해주세요.');
    return false;
  }
  if ($('input[name=subject]').val() == '') {
    $.openMsgModal('사진 설명은 꼭 입력해주세요.');
    return false;
  }

  var $btn = $(this);
  var $dom = $('.multi');
  var formData = new FormData($('#formPhoto')[0]);

  $.ajax({
    url: '/album/insert',
    processData: false,
    contentType: false,
    data: formData,
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $btn.css('opacity', '0.5').prop('disabled', true).text('잠시만 기다리세요..');
    },
    success: function(result) {
      location.replace($('input[name=baseUrl]').val() + '/album');
    }
  });
}).on('click', '.btn-album-view', function() {
  // 사진 확대하여 보여주기 (PhotoSwipe)
  var $dom = $(this).parent();
  var index = $(this).data('index');
  var items = [];

  $('.btn-album-view[data-notice-idx=' + $(this).data('notice-idx') + ']').each(function(i, v) {
    items.push({
      src: $('input[name=photoUrl]').val() + $(this).data('src'),
      w: $(this).data('width'),
      h: $(this).data('height'),
      title: $(this).data('title')
    });
  });

  var pswpElement = document.querySelectorAll('.pswp')[0];
  var items = items;
  var options = {
    index: index,
    bgOpacity: 0.8,
    showHideOpacity: true,
    loop: false,
    getThumbBoundsFn: function(index) {
      var thumbnail = $dom[0],
      pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
      rect = thumbnail.getBoundingClientRect(); 
      return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
    }
  };
  var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
  gallery.init();
}).on('click', '.btn-album-delete', function() {
  var $dom = $(this);
  if ($dom.parent().hasClass('album-mask')) {
    $dom.parent().removeClass('album-mask');
  } else {
    $dom.parent().addClass('album-mask');
  }
}).on('click', '.btn-album-delete-process', function() {
  // 삭제
  var $btn = $(this);
  var albumIdx = [];
  var sourceFile = [];

  $('.album-mask').each(function() {
    albumIdx.push($(this).data('album-idx'));
    sourceFile.push($(this).data('src'));
  });

  if (albumIdx == '') {
    return false;
  }

  $.ajax({
    url: '/album/delete_process',
    data: 'albumIdx=' + albumIdx + '&sourceFile=' + sourceFile,
    dataType: 'json',
    type: 'post',
    beforeSend: function() {
      $btn.css('opacity', '0.5').prop('disabled', true).text('삭제중..');
    },
    success: function(result) {
      location.replace($('input[name=baseUrl]').val() + '/album');
    }
  });
});