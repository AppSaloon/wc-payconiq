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
            var url = document.location.protocol + "//" + document.location.hostname + wc_add_to_cart_params.ajax_url;

            $.ajax( {
                url: url,
                type: 'POST',
                data: {
                    action: 'payconiq_check_order_status',
                    order_id: order_id
                },
                success: function ( data ) {
                    /**
                     * if order status is completed or processing then redirect page to thank you page
                     */
                    if ( data.status == 'completed' || data.status == 'processing' ) {
                        window.location.href = data.message;
                    }
                }
            } );
        }, 5000 );

    } );

} );