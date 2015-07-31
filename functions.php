<?php

$theme = wp_get_theme();
define( 'APP_VERSION', $theme->Version );

$functions_dir =  __DIR__ . '/functions';
if ( $handle = opendir( $functions_dir ) ) {

	while ( false !== ( $file = readdir( $handle ) ) ) {
		if ( in_array( $file, [ '.', '..' ] ) )
			continue;
		if ( !is_dir( $functions_dir . '/' . $file ) ) {
			require_once $functions_dir . '/' . $file;
		}
	}

	closedir( $handle );
}
