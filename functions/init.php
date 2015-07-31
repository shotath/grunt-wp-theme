<?php

// add_action( 'init', 'create_custom_posts' );
function create_custom_posts() {
	// https://developer.wordpress.org/resource/dashicons
	$custom_objects = [
		[
			'post-type' => 'blog',
			'name' => 'BLOG',
			'name-jp' => 'ブログ',
			'menu-icon' => 'dashicons-welcome-write-blog',
			'show-ui' => true,
			'supports' => [ 'title', 'editor' ],
		],
		[
			'post-type' => 'service',
			'name' => 'SERVICE',
			'name-jp' => '事業案内',
			'menu-icon' => 'dashicons-cart',
			'show-ui' => true,
			'supports' => [ 'title' ],
		]
	];
	$tax_objects = [
		[
			'label' => 'ブログカテゴリー',
			'slug' => 'blog-category',
			'hierarchical' => true,
			'post-type' => [
				'blog',
			]
		]
	];
	foreach ( $custom_objects as $params ) :
		$labels = [
			'name' => _x( $params[ 'name-jp' ], 'post type general name' ),
			'singular_name' => _x($params[ 'name-jp' ], 'post type singular name' ),
			'add_new' => _x( $params[ 'name-jp' ] . 'を新規追加', 'animal' ),
			'add_new_item' => __( '新規項目追加' ),
			'edit_item' => __( '項目を編集' ),
			'new_item' => __( '新規項目' ),
			'view_item' => __( '項目を表示' ),
			'search_items' => __( '項目検索' ),
			'not_found' =>  __( $params[ 'name-jp' ] . 'が見つかりません' ),
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




// add_action( 'after_setup_theme', 'thumbnail_setup' );
function thumbnail_setup() {
	add_image_size( 'blog-thumb-80', 80, 80, true );
	add_image_size( 'blog-thumb-120', 120, 120, true );
	add_image_size( 'blog-thumb-160', 160, 160, true );
	add_image_size( 'blog-content-thumb-300', 300, 200, true );
	add_image_size( 'blog-content-thumb-600', 600, 400, true );

	add_image_size( 'timeline-280', 280, 186, true );
	add_image_size( 'timeline-560', 280 * 2, 186 * 2, true );

	add_image_size( 'employee-main-300', 300, 999, false );
	add_image_size( 'employee-main-600', 600, 999, false );
}
