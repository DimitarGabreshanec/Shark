$(document).ready(function(){
    $("#sub_img").on('change', function(e){    
        var tgt = e.target || window.event.srcElement,
            files = tgt.files;
        var form_data = new FormData();
        var totalfiles = files.length;
        for (var index = 0; index < totalfiles; index++) {
            form_data.append("sub_img[]", files[index]);
        }
        form_data.append("_token", "{{csrf_token()}}");  
        form_data.append("product_id", "{{ $product->id }}");
        $.ajax({
            type: "post",
            url : '{{ route("admin.products.upload_img") }}',
            data:  form_data,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function(result) {
                if (result.result_code == 'success') {    
                    location.reload();
                    return true;
                } else {
                    alert('お気に入りを設定することが出来ません。');
                }
            },
            error: function(){
                alert('お気に入りを設定することが出来ません。');
            },
        }); 
    });

    $("#main_img").on('change', function(e){     
        var tgt = e.target || window.event.srcElement,
            files = tgt.files;
        var form_data = new FormData();
        var totalfiles = files.length;
        form_data.append("main_img", files[0]);
        form_data.append("_token", "{{csrf_token()}}");  
        form_data.append("product_id", "{{ $product->id }}");
        $.ajax({
            type: "post",
            url : '{{ route("admin.products.upload_img") }}',
            data:  form_data,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function(result) {
                if (result.result_code == 'success') {    
                    location.reload();
                    return true;
                } else {
                    alert('お気に入りを設定することが出来ません。');
                }
            },
            error: function(){
                alert('お気に入りを設定することが出来ません。');
            },
        }); 
    }); 

    $(document).on('click', '#clear3', function(e){
        if (confirm("画像を削除しますか？") == true) { 
            var image_name = $(this).data('image_name'); 
            $.ajax({
                type: "post",
                url : '{{ route("admin.products.delete_img") }}',
                data: {
                    image_name: image_name, 
                    product_id: "{{ $product->id }}",
                    _token: '{{ csrf_token() }}',
                },
                dataType: "json",
                success: function(result) {
                    $('.li_' + result.sequence).remove();              
                        return true;
                    },
                error: function(){
                    alert('お気に入りを設定することが出来ません。');
                },
                });
            }
    });

    $(".member-discount").click(function(){
        if($(this).val() == "{{ config('const.discount_type.percent') }}")
        {
            $("#discount_desc").text("%");
        }
        else
        {
            $("#discount_desc").text("円");
        }
    });

    $('input[name="type"]').click(function () {
        if ($(this).is(':checked')) {
            var type = $(this).val();
            $('.view-element').hide();
            $('.type-' + type).show(); 
        }
    });  
});