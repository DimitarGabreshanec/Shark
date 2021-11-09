// JavaScript Document

$(document).ready(function(){
  var def_css   = {color : '#999999'};
  var focus_css = {color : '#000000'};
  var selector  = $('#keyword');

  //初期の色セット
  selector.css(def_css);

  //フォーカス時の色と値の変更
  selector.focus(function(){
    if($(this).val() == this.defaultValue){
      $(this).val('');
      $(this).css(focus_css);
    }
  })
  //フォーカス解除時の色と値の変更
  selector.blur(function(){
    if($(this).val() == this.defaultValue || $(this).val() == ''){
      $(this).val(this.defaultValue);
      $(this).css(def_css);
    };
  });
});

