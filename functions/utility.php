<?php

function is_single_of( $post_type = '' ) {
	if ( !$post_type ) : return is_single(); endif;
	if ( !is_single() ) : return false; endif;

	global $post;
	return $post->post_type === $post_type;
}




function is_archive_of( $post_type = '' ) {
	if ( !$post_type ) : return is_archive(); endif;
	if ( !is_archive() ) : return false; endif;

	global $post;
	return $post->post_type === $post_type;
}




function is_page_of( $post_name = '' ) {
	if ( !$post_name ) : return is_page(); endif;
	if ( !is_page() ) : return false; endif;

	global $post;
	return $post->post_name === $post_name;
}




function cfs_fields( $args ) {
	global $cfs;
	$datas = [];
	foreach ( $args as $arg ) {
		$datas[ $arg ] = $cfs->get( $arg );
	}
	return $datas;
}




function files_tail() {
	$tail = '?ver=' . APP_VERSION;
	if ( WP_DEBUG )
		$tail .= '&t=' . time();
	echo $tail;
}
