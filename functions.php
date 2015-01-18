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
// 	global $ymk;
// 	$ymk->get_and_store_tweets();
// 	$ymk->get_and_store_playlist();

// 	echo '<div class="wrap">';
// 	echo '<p>Updated tweets & playlist on DB.</p>';
// 	echo '</div>';
// }

function removeMenus () {
	$user = wp_get_current_user();
	remove_menu_page('edit.php');
	remove_menu_page('edit-comments.php');
	remove_menu_page('tools.php');
	remove_menu_page('link-manager.php');
	if (!current_user_can('level_10')) {
	}
}
add_action('admin_menu', 'removeMenus');


// ============================================
//
// Custom post settings
// 
// ============================================

function createCustomPosts() {
	$customObject = [
		// [
		// 	'post-type' => 'main-visual',
		// 	'name' => 'MAIN VISUAL',
		// 	'name-jp' => 'メインビジュアル',
		// 	'menu-icon' => 'dashicons-format-image',
		// 	'show-ui' => true,
		// 	'supports' => ['title'],
		// 	'taxonomies' => []
		// ],
		// [
		// 	'post-type' => 'news',
		// 	'name' => 'NEWS',
		// 	'name-jp' => 'ニュース',
		// 	'menu-icon' => 'dashicons-megaphone',
		// 	'show-ui' => true,
		// 	'supports' => ['title', 'editor'],
		// 	'taxonomies' => []
		// ],
	];
	foreach ($customObject as $params) {
		$labels = [
			'name' => _x($params['name'], 'post type general name'),
			'singular_name' => _x($params['name'], 'post type singular name'),
			'add_new' => _x($params['name-jp'].'を新規追加', 'animal'),
			'add_new_item' => __('新規項目追加'),
			'edit_item' => __('項目を編集'),
			'new_item' => __('新規項目'),
			'view_item' => __('項目を表示'),
			'search_items' => __('項目検索'),
			'not_found' =>  __($params['name-jp'].'が見つかりません'),
			'not_found_in_trash' => __('ゴミ箱に'.$params['name-jp'].'はありません'),
			'parent_item_colon' => ''
		];
		$args = [
			'labels' => $labels,
			'public' => true,
			'menu_icon' => $params['menu-icon'], // https://developer.wordpress.org/resource/dashicons
			'publicly_queryable' => true,
			'show_ui' => $params['show-ui'],
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => 100,
			'supports' => $params['supports'],
			'has_archive' => true
		];
  		register_post_type($params['post-type'], $args);

  		foreach ($params['taxonomies'] as $taxParams) {
  			$taxArgs = [
				'hierarchical' => true,
				'update_count_callback' => '_update_post_term_count',
				'label' => $taxParams['label'],
				'singular_label' => $taxParams['label'],
				'public' => true,
				'show_ui' => true
  			];
			register_taxonomy($taxParams['slug'], $params['post-type'], $taxArgs);
  		}
	}
}
add_action('init', 'createCustomPosts');



// ============================================
//
// User agent
// 
// ============================================


$agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";

function echo_agent() {
	global $agent;
	echo $agent;
}
function is_docomo() {
	global $agent;
	return( preg_match ('/DoCoMo/', $agent) );
}

function is_au() {
	global $agent;
	return( preg_match('/KDDI\-/', $agent) || preg_match('/UP\.Browser/',$agent) );
}

function is_softbank() {
	global $agent;
	return( preg_match('/J\-PHONE|Vodafone|MOT\-[CV]980|SoftBank/', $agent) );
}

function is_ie6() {
	global $agent;
	return( preg_match('/MSIE 6/', $agent) );
}

function is_ie7() {
	global $agent;
	return( preg_match('/MSIE 7/', $agent) );
}

function is_ie8() {
	global $agent;
	return( preg_match('/MSIE 8/', $agent) );
}

function is_ie9() {
	global $agent;
	return( preg_match('/MSIE 9/', $agent) );
}

function is_MOB() {
	global $agent;
	return(is_docomo() || is_au() || is_softbank());
}

function is_iOS() {
	global $agent;
	return( preg_match('/iPhone/',$agent) || preg_match('/iPod/',$agent) || preg_match('/iPad/',$agent) );
}
function is_android() {
	global $agent;
	return( preg_match('/Android/',$agent) );
}
function is_winPhone() {
	global $agent;
	return( preg_match('/Windows Phone/',$agent) || preg_match('/Symbian/',$agent) );
}
function is_berry() {
	global $agent;
	return( preg_match('/BlackBerry/',$agent) );
}
function is_SP() {
	global $agent;
	return( is_iOS() || is_android() || is_winPhone() || is_berry() );
}