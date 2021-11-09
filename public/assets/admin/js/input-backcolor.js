// JavaScript Document

	$(function(){
    $('input[type=text],textarea,select').css({
        backgroundColor: '#ffebeb',
    });
    $('input.input-text,textarea,select,option').each(function(){
        if(this.value == ""){
            $(this).css("background-color","#ffebeb");
        } else {
            $(this).css("background-color","#ffffff");
        }
    });
    $('input.input-text,textarea,select,option').blur(function(){
        if(this.value == ""){
            $(this).css("background-color","#ffebeb");
        } else {
            $(this).css("background-color","#ffffff");
        }
    });
    $('input.input-text,select,option').focus(function(){
        if(this.value == ""){
            $(this).css("background-color","#ffebeb");
        } else {
            $(this).css("background-color","#ffffff");
        }
    });
});
