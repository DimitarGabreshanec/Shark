jQuery(document).ready(function () {
    var userAgent = window.navigator.userAgent.toLowerCase();
    var appVersion = window.navigator.appVersion.toLowerCase();

    if ((navigator.userAgent.indexOf('Android') > 0 && navigator.userAgent.indexOf('Mobile') == -1) || navigator.userAgent.indexOf('A1_07') > 0 || navigator.userAgent.indexOf('SC-01C') > 0 || navigator.userAgent.indexOf('iPad') > 0) {
        jQuery('head').append('<meta name="viewport" content="width=1024, initial-scale=0.70,minimum-scale=0.70, maximum-scale=2, user-scalable=yes" />');
    } else if ((navigator.userAgent.indexOf('iPhone') > 0 && navigator.userAgent.indexOf('iPad') == -1) || navigator.userAgent.indexOf('iPod') > 0 || (navigator.userAgent.indexOf('Android') > 0 && navigator.userAgent.indexOf('Mobile') > 0)) {
        jQuery("head").append(jQuery('<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1, maximum-scale=2, user-scalable=yes" />'));
    }
});
//
//
// $(function() {
//
// $(".drawer").drawer();
// $('#nav li').on('click', function() {
// $('.drawer').drawer('close');
// });
// });
//
// $(function() {
// $(".drawer-toggle").addClass("open");
// $('.drawer-toggle').click(function(e) {
// 	$(this).toggleClass("close");
// })
// });
//


$(function () {
    $('.clothes-list ul').each(function () {
        var rep = 0;
        $(this).children().each(function () {
            var itemHeight = parseInt($(this).css('height'));
            if (itemHeight > rep) {
                rep = itemHeight;
            }
            ;
        });
        $(this).children().css({
            height: (rep)
        });
    });
});

$(function () {
    $('select[name="selectitem2"]').change(function () {
        var radioval = $(this).val();
        if (radioval == 'その他') {
            $('#inputother2').removeAttr('disabled');
        } else {
            $('#inputother2').attr('disabled', 'disabled');
        }
    });

    $('select[name="selectitem2-2"]').change(function () {
        var radioval = $(this).val();
        if (radioval == 'その他') {
            $('#inputother2-2').removeAttr('disabled');
        } else {
            $('#inputother2-2').attr('disabled', 'disabled');
        }
    });

});

$(function () {
    $('input[name="radioitem"]:radio').change(function () {
        var radioval = $(this).val();
        if (radioval == 'その他') {
            $('#inputother').removeAttr('disabled');
        } else {
            $('#inputother').attr('disabled', 'disabled');
        }
    });

    $('select').change(function () {
        var radioval = $(this).val();
        if (radioval == 'その他') {
            $('#inputother2').removeAttr('disabled');
        } else {
            $('#inputother2').attr('disabled', 'disabled');
        }
    });

});

$(function () {

    function demo01() {
        $(this).next().slideToggle(300);
    }

    $(".simple .toggle").click(demo01);


    function demo02() {
        $(this).toggleClass("active").next().slideToggle(300);
    }

    // $("#leftside .switch .toggle").hover(demo02);

    $("#leftside .switch .toggle").click(demo02);

    function demo03() {
        $(this).toggleClass("f_active").next().slideToggle(300);
    }

    $("#leftside #nav li").click(demo03);
});

$(function () {
    $("p.menu").click(function () {
        $(this).toggleClass("bg02").next().slideToggle();
    });
});


// テキストエリア入力欄自動調整
$(function () {
    autosize(document.querySelectorAll('textarea'));
});


$(function () {
    if ($('.tab li').size()) {
        $('.tab > li').click(function () {
            // 一度タブについているクラスselectを消し、
            $('.tab > li').removeClass('select');
            // クリックされたタブのみにクラスselectをつけます。
            $(this).addClass('select')
            // 絞り込み
            switch ($(this).attr("id")) {
                case 'all':
                    $('.content > li').fadeIn('slow');
                    //$('ul.tab_content li').removeAttr('style');
                    break;
                default:
                    $('.content > li').hide();
                    //$('ul.tab_content li.' + $(this).attr("id")).show();
                    $('.content > li.' + $(this).attr("id")).fadeIn('slow');
                    break;
            }
        });
    }
});


$(function () {
    if ($('.tabInTab > li').size()) {
        $('.tabInTab > li').click(function () {
            // 一度タブについているクラスselectを消し、
            $('.tabInTab > li').removeClass('select');
            // クリックされたタブのみにクラスselectをつけます。
            $(this).addClass('select')
            // 絞り込み
            switch ($(this).attr("id")) {
                case 'all':
                    $('.tabInContent > li').fadeIn('slow');
                    //$('ul.tab_content li').removeAttr('style');
                    break;
                default:
                    $('.tabInContent > li').hide();
                    //$('ul.tab_content li.' + $(this).attr("id")).show();
                    $('.tabInContent > li.' + $(this).attr("id")).fadeIn('slow');
                    break;
            }
        });
    }
});


// $(function() {
//   //クリックしたときのファンクションをまとめて指定
//   $('.tab > li').click(function() {
//
//     //.index()を使いクリックされたタブが何番目かを調べ、
//     //indexという変数に代入します。
//     var index = $('.tab > li').index(this);
//
//     //コンテンツを一度すべて非表示にし、
//     $('.content > li').css('display', 'none');
//
//     //クリックされたタブと同じ順番のコンテンツを表示します。
//     $('.content > li').eq(index).css('display', 'block');
//
//     //一度タブについているクラスselectを消し、
//     $('.tab > li').removeClass('select');
//
//     //クリックされたタブのみにクラスselectをつけます。
//     $(this).addClass('select')
//   });

//   //クリックしたときのファンクションをまとめて指定
//   $('.tabInTab > li').click(function() {
//
//     //.index()を使いクリックされたタブが何番目かを調べ、
//     //indexという変数に代入します。
//     var index = $('.tabInTab > li').index(this);
//
//     //コンテンツを一度すべて非表示にし、
//     $('.tabInContent > li').css('display', 'none');
//
//     //クリックされたタブと同じ順番のコンテンツを表示します。
//     $('.tabInContent > li').eq(index).css('display', 'block');
//
//     //一度タブについているクラスselectを消し、
//     $('.tabInTab > li').removeClass('select');
//
//     //クリックされたタブのみにクラスselectをつけます。
//     $(this).addClass('select')
//   });
// });

$(function () {
    $(".sortbtn").on("click", function () {
        $(this).toggleClass("open close");
    });
});

$(function () {
    $("[name='s1']").click(function () {
        var num = $(":checked").val();
        if (num == 1) {
            $(".hide_box").css('display', 'block');
        } else {
            $(".hide_box").css('display', 'none');
        }
    });
    $("[name='s1']").click(function () {
        var num = $(":checked").val();
        if (num == 1) {
            $(".hide_box2").css('display', 'table_row');
        } else {
            $(".hide_box2").css('display', 'none');
        }
    });
});


$(function () {
    $(window).load(function () {
        $('.Tipper-demo').tipper();
        //各要素のdata属性にオプションを設定しています
    });
});

// $(function() {
// 	new aTable('.js-table',{lang:'ja'});
// });

//
$(function () {
    if ($('.floattable').size()) {
        var $table = $('.floattable');
        $table.floatThead();
    }
});
//
// $(function(){
//   var fix    = $(".floattable");             //メニューオブジェクトを格納
//   var fixTop = fix.offset().top;             //メニューの縦座標を格納
//   $(window).scroll(function () {             //スクロールが発生したら開始
//     if($(window).scrollTop() >= fixTop) {    //スクロール時の縦座標がメニューの縦座標以上なら…
//       fix.addClass("search-condition-fixed")
//       //fix.css("position","fixed");           //メニューに position:fixed 付与
//       //fix.css("top","62px");                 //メニューに top:0 付与
//       //fix.css("left","0");
//       //fix.css("background","#fff");
//     } else {
//       fix.removeClass("search-condition-fixed")
//       //fix.css("position","");                //メニューの position を空に
//       //fix.css("top","");                     //メニューの top を空に
//     }
//   });
// });


$(function () {
    if (!navigator.userAgent.match(/(iPhone|iPod|Android)/)) {
        var fix = $("#leftside, #rightside"); //メニューオブジェクトを格納
        var fixTop = fix.offset().top; //メニューの縦座標を格納
        $(window).scroll(function () { //スクロールが発生したら開始
            if ($(window).scrollTop() >= fixTop) { //スクロール時の縦座標がメニューの縦座標以上なら…
                fix.addClass("nav-fixed")
                //fix.css("position","fixed");           //メニューに position:fixed 付与
                //fix.css("top","62px");                 //メニューに top:0 付与
                //fix.css("left","0");
                //fix.css("background","#fff");
            } else {
                fix.removeClass("nav-fixed")
                //fix.css("position","");                //メニューの position を空に
                //fix.css("top","");                     //メニューの top を空に
            }
        });
    }
});

// $(function() {
//   $(".today_schedule_table").addClass("open");
//   $('.today_schedule_table').hide();
//   $('.today_schedule h3').click(function(e) {
//     $(this).toggleClass("close");
//     // $('.today_schedule table').addClass("after_open");
//     //選択したパネルを開く
//     $('+div', this).slideToggle(300);
//   })
// });

$(function () {
    if (!navigator.userAgent.match(/(iPhone|iPod|Android)/)) {
        function setMyCookie() {
            myCookieVal = $('#leftside, #rightside, .menu-trigger, .MDL_window_wrap_calender').hasClass('active') ? 'isActive' : 'notActive';
            $.cookie('myCookieName', myCookieVal, {
                path: '/'
            });
        }

        if ($.cookie('myCookieName') == 'isActive') {
            $('#leftside, #rightside, .menu-trigger, .MDL_window_wrap_calender').addClass('active');
        } else {
            $('#leftside, #rightside, .menu-trigger, .MDL_window_wrap_calender').removeClass('active');
        }

        $(".menu-trigger").click(function () {
            $('#leftside, #rightside, .menu-trigger, .MDL_window_wrap_calender').toggleClass('active');
            setMyCookie();
        });
    }
});


// $(function() {
// $("#leftside, #rightside, .menu-trigger").addClass("open");
// // $('.today_schedule_table').hide();
// $('.menu-trigger').click(function(e) {
// 	$("#leftside, #rightside, .menu-trigger").toggleClass("close");
// 	// $('.today_schedule table').addClass("after_open");
// 	//選択したパネルを開く
// })
// });

$(function () {
    $('#content-show_btn').click(function () {
        var content = '#' + $(this).attr('data-target');
        $('.parts_checklist').fadeIn('slow');
    })
});


$(function () {
    //ハッシュタグ読み込み
    var hash = location.hash;
    if (hash.length) {
        //ハッシュがあったら
        if (hash.match(/#rental/)) {
            //ハッシュタグが「#tab◯」ってなってたら
            //表示領域を全部非表示にする
            // $('.content li').css('display', 'none');
            $('.content > li').css('display', 'none');
            //メニューに付いてる「class="active"」を削除
            $('ul.tab li').removeClass('select');
            //「#tab◯」を「◯」だけにする
            //n番目の内容を表示する
            // $('.content li').eq(index).css('display', 'block');
            $('.content > li').eq(2).css('display', 'block');
            //n番目のメニューに「class="active"」を付与する
            $('ul.tab li:nth-child(3)').addClass('select');
            // 			$('ul.tab li:nth-child(1)').removeClass('select');
            // $('ul.tab li:nth-child(2)').removeClass('current');
        } else {

        }
    } else {
        //ハッシュがなかったら（普通にページ開いたときとか）
        //1番目のメニューに「class="active"」を付与する
        $('ul.tab li').eq(0).addClass('select');
        //内容部分を全部非表示にする
        $('.content > li').hide();
        //1番目の表示内容を表示する
        $('.content > li').eq(0).show();
    }
});


$(function () {
    $(".submit_confirm").click(function () {
        swal({
            title: '登録しますか',
            // text: "好きなテキストを入力",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '登録する'
        });
    });
});


$(function () {
    $(".delete_record").click(function(e){  
        e.preventDefault();
        var delete_id = $(this).attr('cidx'); 
        helper_confirm("dialog-confirm", "削除", "レコードを削除します。<br />よろしいですか？", 300, "確認", "閉じる", function(){
            var frm_id = "#frm_delete_" + delete_id; 
            $(frm_id).submit();
        }); 
    });
});



$(function () {
    $(".btn-back-index, .btn-clear").click(function() {
        var url = $(this).data('url');
        location.href = url;
    });
});