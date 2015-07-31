<script>
    window.HOME_URL = '<?= home_url() ?>/';

    ( function ( window, $ ) {
        var $win = $( window );
        var $body = $( 'body' );
        var timeout_ms = 10 * 1000;
        var delays = [
            500
        ];

        function addClassWithDelay( delays ) {
            $body.addClass( 'on-load' );
            delays.forEach( function ( delay ) {
                setTimeout( function () {
                    $body.addClass( 'on-load-' + delay + 'ms' );
                }, delay );
            } );
        }
        $win.on( 'load', function () {
            addClassWithDelay( delays );
        } );
        setTimeout( function () {
            addClassWithDelay( delays );
        }, timeout_ms );

    } )( window, jQuery );
</script>

<?php if ( is_page_of( 'contact' ) ) : ?>

<script src="<?php bloginfo( 'template_directory' ); ?>/files/js/jquery.jpostal.js<?php files_tail(); ?>"></script>
<script>
    jQuery( function () {
        jQuery( '#postcode' ).jpostal( {
            postcode: [ '#postcode' ],
            address: {
                '#address': '%3%4%5'
            },
            url : {
                'http'  : 'http://jpostal-1006.appspot.com/json/',
                'https' : 'https://jpostal-1006.appspot.com/json/'
        	}
        } );
    } );
</script>
<?php endif; ?>

<?php wp_footer(); ?>
