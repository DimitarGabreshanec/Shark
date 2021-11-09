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

$(function () {
  $(".btn-back-index, .btn-clear").click(function() {
      var url = $(this).data('url');
      location.href = url;
  });
});
