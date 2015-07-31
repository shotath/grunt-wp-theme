<?php

// add_action( 'admin_menu', 'addMenus' );
function addMenus() {
	// add_options_page( 'My Plugin Options', 'My Plugin', 'manage_options', 'my-unique-identifier', 'my_plugin_options' );
	// add_menu_page( 'ヘルプ', 'ヘルプ', 'level_0', 'my-help', 'pageActionHelp', 'dashicons-heart' );
}
// function pageActionHelp() {
// }




add_action( 'admin_menu', 'remove_menus' );
function remove_menus() {
	$user = wp_get_current_user();
	remove_menu_page( 'edit.php' );
	remove_menu_page( 'edit-comments.php' );
	remove_menu_page( 'tools.php' );
	if ( $user->ID !== 1 ) {
		remove_menu_page( 'wpcf7' );
		remove_menu_page( 'edit.php?post_type=day-of-employee' );
	}
}




// add_action( 'admin_init', 'manage_capability' );
function manage_capability() {
	$role = get_role( 'editor' );

	$role->add_cap( 'edit_themes' );

	$role->remove_cap( 'plugins' );
	$role->remove_cap( 'pages' );
	$role->remove_cap( 'moderate_comments' );
	$role->remove_cap( 'edit_dashboard' );
}




// add_action( 'admin_print_footer_scripts', 'limit_category_select' );
function limit_category_select() {
	?>
	<script type="text/javascript">
		jQuery(function($) {
			// 投稿画面のカテゴリー選択を制限
			var categoryCheckboxes = $('.categorychecklist input[type=checkbox]');
			categoryCheckboxes.click(function() {
				var $self = $(this);
				$self.parents('.categorychecklist').find('input[type=checkbox]').attr('checked', false);
				$self.attr('checked', true);
			});

			// クイック編集のカテゴリー選択を制限
			var quickCategoryCheckboxes = $('.cat-checklist input[type=checkbox]');
			quickCategoryCheckboxes.click(function() {
				var $self = $(this);
				$self.parents('.cat-checklist').find('input[type=checkbox]').attr('checked', false);
				$self.attr('checked', true);
			});
		});
	</script>
	<?php
}
