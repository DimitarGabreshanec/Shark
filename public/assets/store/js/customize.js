if (navigator.userAgent.match(/(iPhone|iPad|iPod|Android)/)) {
  $(function () {
    $('.tel i').each(function () {
      var str = $(this).html();
      if ($(this).children().is('img')) {
        $(this).html($('<a>').attr('href', 'tel:' + $(this).children().attr('alt').replace(/-/g, '')).append(str + '</a>'));
      } else {
        $(this).html($('<a>').attr('href', 'tel:' + $(this).text().replace(/-/g, '')).append(str + '</a>'));
      }
    });
  });
}
$(function(){
var submit = $('input[type="submit"].submit');
submit.prop('disabled', true);
	submit.css('opacity',.7);
$('input[id="ok"]').on("click",function(){
    if ($(this).prop('checked')) {
        submit.prop('disabled', false);
		submit.css('opacity',1);
    }else{
		submit.css('opacity',.7);
        submit.prop('disabled', true);
    }
})
})
$(function() {
  $('[name="category"]:radio').change( function() {
    if($('[id=company]').prop('checked')){
      $('.appear').fadeIn();
    } else if ($('[id=indivisual]').prop('checked')) {
$('.appear').fadeOut();
    }
  });
});
$(function() {
  $('input[type=file].one').after('<div class"=imagebox"></div>');

  // アップロードするファイルを選択
  $('input[type=file].one').change(function() {
	$('#clear').show();
    var file = $(this).prop('files')[0];

    // 画像以外は処理を停止
    if (! file.type.match('image.*')) {
      // クリア
      $(this).val('');
      $('.imagebox').html('');
      return;
    }

    // 画像表示
    var reader = new FileReader();
    reader.onload = function() {
      var img_src = $('<img>').attr('src', reader.result);
      $('.imagebox').html(img_src);
    }
    reader.readAsDataURL(file);
  });


  // ファイル参照をクリア
  $('#clear').click(function() {
    $('input[type=file].one').val('');
    // IE バージョン判定 10以下

      $('.imagebox').html('');

    $(this).hide();
  });
});

$(function() {
    //imgタグでファイルが選択された以下が発動
        $("#choose-multiple-images-input").on('change', function(e){
			$('#clear2').show();
                var tgt = e.target || window.event.srcElement,
                    files = tgt.files;

                // FileReaderがサポートされているか確認.
                if (FileReader && files && files.length) {
                    for (var i = 0; i < files.length; i++)
                        {
                          //それぞれの画像ファイルに対してFileReaderを読んで読んだらimg-placeにimgタグを追加していく.
                            (function(){
                                var fr = new FileReader();
                                fr.onload = function () {
                                    $(".imagebox2 ul").append("<li><img src='"+fr.result+"'></img></li>");
                                }
                                fr.readAsDataURL(files[i]);
                            })();

                        }
                }
            });
  $('#clear2').click(function() {
    $('input[type=file].many').val('');
    // IE バージョン判定 10以下

      $('.imagebox2').html('');

    $(this).hide();
  });
});
$(function(){
$("ul.acc li dl dt i").on("click", function() {
 if ($(this).text() === '+') {
                        $(this).text('-');
                    } else {
                        $(this).text('+');
                    }
$(this).parent().next('dd').slideToggle();
});
});

$(function(){
$("dl.matching dt").on("click", function() {
	$(this).toggleClass("active");
$(this).next('dd').slideToggle();
});
});

$(function(){
$("input.cart").on("click", function() {
 $(this).prev(".add-wrap").fadeIn("fast");
	setTimeout(function(){
       $(".add-wrap").fadeOut("fast");
    },1500);
});

});

$(function(){
    $('.textarea .pic i,.shop-list-search li .pic i, .shop-list-search2 li .pic i').on('click', function(event){
        event.preventDefault();
        $(this).toggleClass('active');
    });
});

$(function(){
   /*var fixedStaticElem = $("#fix-btn");
   var fixedStaticContent = $("#wrap-fixed-static");
   var contentTopFixedStatic = 0;
   var win = $(window);

   function fixedStaticPos() {
     contentTopFixedStatic = fixedStaticContent.offset().top + fixedStaticElem.outerHeight();
   }
   function fixedStaticEffect() {
     if( win.scrollTop() + win.height() > contentTopFixedStatic ){
       fixedStaticElem.addClass("none");
     } else if( fixedStaticElem.hasClass("none") ) {
       fixedStaticElem.removeClass("none");
     }
   }
   $(window).on('load resize scroll', function(){
     fixedStaticPos();
     fixedStaticEffect();
   }); */
});


$(function() {
	$('#selectcart').css('opacity','0.3');
  $('#selectcart').attr('disabled', 'disabled');

  $('input[name=sec]').click(function() {
    if ( $(this).prop('checked') == false ) {
      $('#selectcart').attr('disabled', 'disabled');
		$('#selectcart').css('opacity','0.3');
    } else {
      $('#selectcart').removeAttr('disabled');
		$('#selectcart').css('opacity','1');
    }
  });
});


