<?php
if ( $post !== NULL ) :
    $slug = $post->post_type;
    get_template_part( 'archives/archive', $slug );
else :
    get_template_part( '404' );
endif;
