<script>
    $(document).ready(function(e) {
        $('.btn-set-order-complete').click(function(){
            var id = $(this).data('id');
            var product_name = $(this).data('name');
            var message = $(this).data('message');
            var f_confirm = confirm('「' + product_name + '」の注文を' + message + '\nよろしいでしょうか?');
            if(f_confirm) {
                $('#frm_complete_' + id).submit();
            }
        });
    });
</script>
