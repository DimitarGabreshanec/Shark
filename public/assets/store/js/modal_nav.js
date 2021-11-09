$(function () {
  $("a#btn").click(function () {
    $("#overlay").fadeToggle('slow'); /*ふわっと表示*/
    $("#btn span").toggleClass("change");
  });
});
