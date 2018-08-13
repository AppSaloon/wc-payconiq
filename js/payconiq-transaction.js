jQuery( function ( $ ) {

    $( document ).ready( function () {

        /**
         * Every 5 seconds check for order status
         */
        setInterval( function () {
            var order_id = $( '#order_id' ).val();

            /**
             * Ajax url
             * @type {string}
             */
            var url = document.location.protocol + "//" + document.location.hostname;
            url += wc_add_to_cart_params.ajax_url + '?action=check_order_status&order_id=' + order_id;

            $.ajax( {
                url: url,
                success: function ( data ) {
                    console.log( data );
                }
            } );
        }, 5000 );

    } );

} );