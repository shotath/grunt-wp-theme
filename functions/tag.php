<?php

function logo() {
	$tag = is_home() ? 'h1' : 'div';
?>

<<?= $tag ?> class="logo">
	<a href="<?= home_url() ?>" class="logo__link"><?php bloginfo( 'name' ); ?></a>
</<?= $tag ?>>
<?php
}




function global_nav( $state ) {

	$home_url = home_url();
	$nav_items = [
		'ABOUT US' => $home_url . '/about-us/',
		'SERVICE' => $home_url . '/service/',
		'RECRUIT' => $home_url . '/recruit/',
		'BLOG' => $home_url . '/blog/',
		'ACCESS' => $home_url . '/access/',
		'CONTACT' => $home_url . '/contact/'
	];
?>

<nav class="global-nav global-nav--<?= $state ?>">
	<ul class="global-nav__list">
		<?php foreach ( $nav_items as $name => $url ): ?>
		<?php $slug = strtolower( str_replace( ' ', '-', $name ) ); ?>
		<li class="global-nav__item global-nav__item--<?= $slug ?>">
			<a class="global-nav__link global-nav__link--<?= $slug ?>" href="<?= $url ?>"><?= $name ?></a>
		</li>
		<?php endforeach; ?>
	</ul>
</nav>
<?php
}




function pagination( $pages = '', $range = 2 ) {
	global $paged;
	$showitems = ( $range * 2 ) + 1;

	if ( empty( $paged ) ) {
		$paged = 1;
	}

	if ( $pages == '' ) {
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		if ( !$pages ) {
			$pages = 1;
		}
	}

	// $pages = 100;

	if ( 1 != $pages ) {
		echo '<ol class="pagination">';
		if ( $paged > 2 && $paged > $range + 1 && $showitems < $pages ) {
			echo '<li class="pagination__item pagination__item--first"><a href="' . get_pagenum_link( 1 ) . '">&laquo;</a></li>';
		}
		if ( $paged > 1 && $showitems < $pages ) {
			echo '<li class="pagination__item pagination__item--prev"><a href="' . get_pagenum_link( $paged - 1 ) . '">&lsaquo;</a></li>';
		}

		for ( $i = 1; $i <= $pages; $i++ ) {
			if ( 1 != $pages &&( !( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
				echo ( $paged == $i ) ? '<li class="pagination__item pagination__item--current">' . $i . '</li>' : '<li class="pagination__item"><a href="' . get_pagenum_link( $i ) . '">' . $i . '</a></li>';
			}
		}

		if ( $paged < $pages && $showitems < $pages ) {
			echo '<li class="pagination__item pagination__item--next"><a href="' . get_pagenum_link( $paged + 1 ) . '">&rsaquo;</a></li>';
		}
		if ( $paged < $pages - 1 &&  $paged + $range - 1 < $pages && $showitems < $pages ) {
			echo '<li class="pagination__item pagination__item--last"><a href="' . get_pagenum_link( $pages ) . '">&raquo;</a></li>';
		}
		echo '</ol>';
	}
}



function timeline( $data ) {
	// $data = [
	// 	[
	// 		'number' => '1234',
	// 		'title' => '',
	// 		'description' => '',
	// 		'large' => bool
	// 		'image' => ''
	// 	],
	// 	// :
	// 	// :
	// ];
?>

<ul class="timeline">
    <?php foreach ( $data as $key => $item ) :
		$numbers = str_split( $item[ 'number' ] );
		?>

        <li class="timeline__item timeline__item--<?= $key % 2 == 0 ? 'right' : 'left' ?>">
			<div class="timeline__inner">
				<p class="timeline__numbers">
					<?php foreach ( $numbers as $number ) : ?><span class="timeline__number timeline__number--<?= $number === ':' ? 'colon' : $number ?>"><?= $number ?></span><?php endforeach; ?>

				</p>
							<div class="timeline__texts">
								<?php if ( isset( $item[ 'title' ] ) ) : ?>

									<h4 class="timeline__title"><?= $item[ 'title' ] ?></h4>
								<?php endif; ?>

								<?php if ( isset( $item[ 'description' ] ) ) : ?>

									<div class="timeline__description <?= ( isset( $item[ 'large' ] ) && $item[ 'large' ] ) ? 'timeline__description--large' : ''  ?>"><?= $item[ 'description' ] ?></div>
								<?php endif; ?>
							</div>
			</div>
			<?php if ( isset( $item[ 'image' ] ) ) : ?>

			<div class="timeline__image">
				<div class="timeline__image-shadow"></div>
				<img src="<?= $item[ 'image' ] ?>" alt="" />
			</div>
			<?php endif; ?>

        </li> <!-- /.timeline__item -->
    <?php endforeach; ?>

</ul>

<?php
}
