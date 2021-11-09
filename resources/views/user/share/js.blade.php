<script>
    $(document).ready(function(e) {
        $('input.product-quantity[type="number"]').change(function(){
            if( parseInt($(this).val()) <= 0 ) {
                $(this).val(1);
            }
            return true;
        });

        $('input.cart-product-quantity[type="number"]').change(function(){
            var order_type = $(this).data('order_type');
            var product_id = $(this).data('product_id');
            var quantity = $(this).val();
            $.ajax({
                type: "post",
                url : '{{ route("user.cart.set_product") }}',
                data: {
                    order_type: order_type,
                    product_id: product_id,
                    quantity: quantity,
                    _token: '{{ csrf_token() }}',
                },
                dataType: "json",
                success: function(result) {
                    if (result.result_code == 'success') {
                        getCartProductCount();
                    }
                },
            });
        });
    });

    function addCartProduct(event, product_id) {
        event.preventDefault();
        var quantity = $('#product_quantity_' + product_id).val();
        $.ajax({
            type: "post",
            url : '{{ route("user.cart.add_product") }}',
            data: {
                product_id: product_id,
                quantity: quantity,
                _token: '{{ csrf_token() }}',
            },
            dataType: "json",
            success: function(result) {
                if (result.result_code == 'success') {
                    getCartProductCount();
                    return true;
                } else {
                    return false;
                }
            },
        });
    }

    function getCartProductCount() {
        $.ajax({
            type: "post",
            url : '{{ route("user.cart.product_count") }}',
            data: {
                _token: '{{ csrf_token() }}',
            },
            dataType: "json",
            success: function(result) {
                var product_count = parseInt(result.product_count);
                if(product_count > 0) {
                    $('#cart_product_count').removeClass('hide').text(product_count);
                } else {
                    $('#cart_product_count').addClass('hide');
                }
            },
        });
    }

    function toggleFavorite(store_id, product_type) {

        //e.preventDefault();
        $.ajax({
            type: "post",
            url : '{{ route("user.stores.toggle_favorite") }}',
            data: {
                store_id: store_id,
                product_type: product_type,
                _token: '{{ csrf_token() }}',
            },
            dataType: "json",
            success: function(result) {
                if (result.result_code == 'success') {
                    return true;
                } else {
                    alert('お気に入りを設定することが出来ません。');
                }
            },
            error: function(){
                alert('お気に入りを設定することが出来ません。');
            },
        });
    }

    function goSearchCondition(lat, lon) {

         //e.preventDefault();
         $.ajax({
             type: "get",
             url : '{{ route("user.stores.search.condition") }}',
             data: {
                lat: lat,
                lon: lon,
                _token: '{{ csrf_token() }}',
             },
             dataType: "json",
             success: function(result) {
                 if (result.result_code == 'success') {
                     return true;
                 } else {
                     alert('お気に入りを設定することが出来ません。');
                 }
             },
             error: function(){
                 alert('お気に入りを設定することが出来ません。');
             },
         });
     }

</script>
