
$(function() {
     $(".modal_btn").click(function() {
           $("#overlay").fadeIn();　/*ふわっと表示*/
           $('.MDL_window_wrap').animate({
           },300);
 });
     $("#close").click(function() {
           $("#overlay").fadeOut();　/*ふわっと消える*/
           $('.MDL_window_wrap').animate({
           },300);
 });
});
