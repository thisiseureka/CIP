<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Function: rating icons
 */
if ( ! function_exists( 'uacf7_rating_icon' ) ) {

	function uacf7_rating_icon( $tag ) {

		$icon_class = $tag->get_class_option();

		if ( $icon_class != '' ) {

			$icon = '<i class="' . $icon_class . '"></i>';

		} else {

			$get_icon = $tag->get_option( 'icon', '', true );

			switch ( $get_icon ) {
				case 'star1':
					$icon = '<i class="far fa-star"></i>';
					break;
				case 'star2':
					$icon = '✪';
					break;
				case 'heart':
					$icon = '<i class="fas fa-heart"></i>';
					break;
				case 'thumbs':
					$icon = '<i class="fas fa-thumbs-up"></i>';
					break;
				case 'smile':
					$icon = '<i class="far fa-smile"></i>';
					break;
				case 'ok':
					$icon = '<i class="far fa-check-circle"></i>';
					break;

				default:
					$icon = '<i class="far fa-star"></i>';
			}
		}

		return $icon;
	}
}
