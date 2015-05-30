<?php

function thumbnail_setup() {
	// add_image_size( 'main-visual', 1260, 504, true );
}
add_action( 'after_setup_theme', 'thumbnail_setup' );


// ============================================
//
// Admin page settings
// 
// ============================================

add_action( 'admin_menu', 'addMenus' );
function addMenus() {
	// add_options_page( 'My Plugin Options', 'My Plugin', 'manage_options', 'my-unique-identifier', 'my_plugin_options' );
	// add_menu_page( 'ヘルプ', 'ヘルプ', 'level_0', 'my-help', 'pageActionHelp', 'dashicons-heart' );
}
// function pageActionHelp() {
// }

function my_getarchives_where( $where, $r ) {
	if ( isset( $r[ 'post_type' ] ) ) {
		$where = str_replace( '\'post\'', '\'' . $r['post_type'] . '\'', $where );
	}
	return $where;
}
add_filter( 'getarchives_where', 'my_getarchives_where', 10, 2 );


function remove_menus() {
	$user = wp_get_current_user();
	remove_menu_page( 'edit.php' );
	remove_menu_page( 'edit-comments.php' );
	remove_menu_page( 'tools.php' );
	if ( !current_user_can( 'level_10' ) ) {
		remove_menu_page( 'wpcf7' );
	}
}
add_action( 'admin_menu', 'remove_menus' );


// ============================================
//
// Use in page
// 
// ============================================

// bodyにつけるクラスを取得
function page_class() {
	global $post;
	$class = '';
	if ( is_page() ) : $class = $post->post_name.' page';
	elseif ( is_archive() ) : $class = $post->post_type.' archive';
	elseif ( is_single() ) : $class = $post->post_type.' single';
	elseif ( is_home() ) : $class = 'index';
	elseif ( is_404() ) : $class = 'not-found page';
	endif;

	if ( wp_is_mobile() ) : $class .= ' sp';
	else : $class .= ' pc';
	endif;
	if ( is_preview() ) : $class = str_replace('post_', '', $class);
	endif;

	return $class;
}

function page_keywords() {
	global $post;
	
	$defaults = [];
	if ( is_single() ) :
		$options = [ single_post_title( '', false ), $post->post_type ];
	elseif ( is_archive() ) :
		$options = [ $post->post_type ];
	elseif ( is_page() ) :
		$options = [ $post->post_name ];
	else :
		$options = [];	
	endif;

	$keys = array_merge( $options, $defaults );
	return join( ',', $keys );
}

function is_single_in( $post_type = '' ) {
	if ( !$post_type ) : return is_single(); endif;
	if ( !is_single() ) : return false; endif;

	global $post;
	return $post->post_type === $post_type;
}

function is_archive_in( $post_type = '' ) {
	if ( !$post_type ) : return is_archive(); endif;
	if ( !is_archive() ) : return false; endif;

	global $post;
	return $post->post_type === $post_type;
}

function cfs_fields( $args, $cfs ) {
	$datas = array();
	foreach ( $args as $arg ) {
		$datas[ $arg ] = $cfs->get( $arg );
	}
	return $datas;
}

// function files_tail() {
// 	$tail = '?ver=' . MY_VERSION;
// 	if ( MY_DEBUG )
// 		$tail .= '&t=' . time();
// 	echo $tail;
// }


// ============================================
//
// Custom post settings
// 
// ============================================

function create_custom_posts() {
	// https://developer.wordpress.org/resource/dashicons
	// $custom_objects = [
	// 	[
	// 		'post-type' => 'works',
	// 		'name' => 'WORKS',
	// 		'name-jp' => '作品',
	// 		'menu-icon' => 'dashicons-admin-home',
	// 		'show-ui' => true,
	// 		'supports' => [ 'title' ]
	// 	],
	// 	[
	// 		'post-type' => 'news',
	// 		'name' => 'NEWS',
	// 		'name-jp' => 'お知らせ',
	// 		'menu-icon' => 'dashicons-megaphone',
	// 		'show-ui' => true,
	// 		'supports' => [ 'title', 'editor' ],
	// 	]
	// ];
	// $tax_objects = [
	// 	[
	// 		'label' => 'Label',
	// 		'slug' => 'slug',
	// 		'hierarchical' => true,
	// 		'post-type' => [
	// 			'slug',
	// 		]
	// 	]
	// ];
	foreach ( $custom_objects as $params ) :
		$labels = [
			'name' => _x( $params[ 'name' ], 'post type general name' ),
			'singular_name' => _x($params[ 'name' ], 'post type singular name' ),
			'add_new' => _x( $params[ 'name-jp' ] . 'を新規追加', 'animal' ),
			'add_new_item' => __( '新規項目追加' ),
			'edit_item' => __( '項目を編集' ),
			'new_item' => __( '新規項目' ),
			'view_item' => __( '項目を表示' ),
			'search_items' => __( '項目検索' ),
			'not_found' =>  __( $params['name-jp'] . 'が見つかりません' ),
			'not_found_in_trash' => __( 'ゴミ箱に' . $params[ 'name-jp' ].'はありません' ),
			'parent_item_colon' => ''
		];
		$args = [
			'labels' => $labels,
			'public' => true,
			'menu_icon' => $params[ 'menu-icon' ],
			'publicly_queryable' => true,
			'show_ui' => $params[ 'show-ui' ],
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => 100,
			'supports' => $params[ 'supports' ],
			'has_archive' => true
		];
  		register_post_type( $params[ 'post-type' ], $args );
	endforeach;

	foreach ( $tax_objects as $tax_params ) :
		$tax_args = [
			'hierarchical' => $tax_params[ 'hierarchical' ],
			'update_count_callback' => '_update_post_term_count',
			'label' => $tax_params[ 'label' ],
			'singular_label' => $tax_params[ 'label' ],
			'public' => true,
			'show_ui' => true
		];
		register_taxonomy( $tax_params[ 'slug' ], $tax_params[ 'post-type' ], $tax_args );
	endforeach;
}
add_action( 'init', 'create_custom_posts' );
