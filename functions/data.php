<?php

function page_class() {
	global $post;
	$class = '';
	if ( is_page() ) : $class = $post->post_name.' page';
	elseif ( is_archive() ) : $class = $post->post_type.' archive';
	elseif ( is_single() ) : $class = $post->post_type.' single';
	elseif ( is_home() ) : $class = 'index';
	elseif ( is_404() ) : $class = 'not-found page';
	endif;

	// if ( wp_is_mobile() ) : $class .= ' sp';
	// else : $class .= ' pc';
	// endif;
	if ( is_preview() ) : $class = str_replace( 'post_', '', $class );
	endif;

	return $class;
}
