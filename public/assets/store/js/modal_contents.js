$(function(){
  $(window).bind('beforeunload', function(event) {
    var  display_state = $( '#modal-target').css('display');
    if(display_state === 'block') {
      return event.returnValue = "終了してよろしいですか？"
    }
  });
});





$(function(){
    // スクロールバーの横幅を取得
    $('html').append('<div class="scrollbar" style="overflow:scroll;"></div>');
    var scrollsize = window.innerWidth - $('.scrollbar').prop('clientWidth');
    $('.scrollbar').hide();

    // 「.modal-open」をクリック
    $('.modal-open').click(function(){
        $('#modal-open').css('display','block');

        // html、bodyを固定（overflow:hiddenにする）
        $('html, body').addClass('lock');

        // オーバーレイ用の要素を追加
        $('body').append('<div class="modal-overlay"></div>');

        // オーバーレイをフェードイン
        $('.modal-overlay').fadeIn('fast');

        // モーダルコンテンツのIDを取得
        var modal = '#' + $(this).attr('data-target');

         // モーダルコンテンツを囲む要素を追加
        $(modal).wrap("<div class='modal-wrap'></div>");

        // モーダルコンテンツを囲む要素を表示
        $('.modal-wrap').show();

        // モーダルコンテンツの表示位置を設定
        modalResize();

         // モーダルコンテンツフェードイン
        $(modal).fadeIn('fast');

        // モーダルコンテンツをクリックした時はフェードアウトしない
        $(modal).click(function(e){
            e.stopPropagation();
        });

        // 「.modal-overlay」あるいは「.modal-close」をクリック
        $('.modal-wrap, .modal-close').off().click(function(){
            // モーダルコンテンツとオーバーレイをフェードアウト
            $(modal).fadeOut('fast');
            $('.modal-overlay').fadeOut('fast',function(){
                // html、bodyの固定解除
                $('html, body').removeClass('lock');
                // オーバーレイを削除
                $('.modal-overlay').remove();
                // モーダルコンテンツを囲む要素を削除
                $(modal).unwrap("<div class='modal-wrap'></div>");
           });
        });

        // リサイズしたら表示位置を再取得
        $(window).on('resize', function(){
            modalResize();
        });

        // モーダルコンテンツの表示位置を設定する関数
        function modalResize(){
            // ウィンドウの横幅、高さを取得
            var w = $(window).width();
            var h = $(window).height();

            // モーダルコンテンツの横幅、高さを取得
            var mw = $(modal).outerWidth(true);
            var mh = $(modal).outerHeight(true);

            // モーダルコンテンツの表示位置を設定
            if ((mh > h) && (mw > w)) {
                $(modal).css({'left': 0 + 'px','top': 0 + 'px'});
            } else if ((mh > h) && (mw < w)) {
                var x = (w - scrollsize - mw) / 2;
                $(modal).css({'left': x + 'px','top': 0 + 'px'});
            } else if ((mh < h) && (mw > w)) {
                var y = (h - scrollsize - mh) / 2;
                $(modal).css({'left': 0 + 'px','top': y + 'px'});
            } else {
                var x = (w - mw) / 2;
                var y = (h - mh) / 2;
                $(modal).css({'left': x + 'px','top': y + 'px'});
            }
        }

    });
});