// 使い方
// input要素にinput-commaクラスを付与する
// →入力内容がカンマ区切りになる。
// ※submitする時にカンマを全て外すのでサーバー側での考慮は不要

$(function() {
	// 初期表示時にカンマ編集
	AddCommaAll();

	// 入力されるたびにカンマ編集
	$(document).on('input', '.input-comma', function(){
		AddComma(this);
	});

	// 全角の場合半角に変換しコンマ編集
	$(document).on('change', '.input-comma', function(){
		let input_value = $(this).val();

		if(input_value.match(/^[０-９]+$/)){
			input_value = input_value.replace(/[０-９]/g,function(s){
				return String.fromCharCode(s.charCodeAt(0)-0xFEE0);
			});

			const comma_value = Number(input_value).toLocaleString();
			$(this).val(comma_value);
			$(this).trigger('change');
		}
	});

	// post直前にカンマ除去
	$(document).on('submit', 'form', function(){
		removeComma();
	});


});
	
//全項目にカンマ編集
function AddCommaAll(){
	$(".input-comma").each(function(index, object){
		AddComma(object);
	});
}

// 引数のオブジェクトのみカンマ編集
function AddComma(object){
	const input_value = $(object).val();

	if(input_value!=undefined && input_value!='' && input_value!=0){
		const remove_comma_value = input_value.split(',').join('');
		if(isFinite(remove_comma_value)){
			const comma_value = Number(remove_comma_value).toLocaleString();
			if(comma_value != 0) {
                $(object).val(comma_value);
            } else {
                $(object).val('');
            }
		}
	}
}

// 全角→半角
function zenkakuToHankaku(str) {
    return str.replace(/[０-９]/g, function(s) {
        return String.fromCharCode(s.charCodeAt(0) + 0xFEE0);
    });
}

// カンマ除去
function removeComma(){
	$(".input-comma").each(function(index, object){
		const input_value = $(object).val();
		const remove_comma_value = input_value.split(',').join('');
		$(object).val(remove_comma_value);
	});
}

// カンマ除去部品
function removeCommaValue(value) {
	return Number(value.split(',').join(''));
}
// カンマ追加部品
function addCommaValue(value) {
	return Number(value).toLocaleString();
}
