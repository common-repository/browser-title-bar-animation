<?php
/**
 * Front.
 *
 * @package wp-tbas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Tbas_Frontend.
 */
class Tbas_Frontend {

	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	private static $instance = null;

	/**
	 *  Initiator
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {

			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {

		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
	}

	/**
	 * Defines all constants
	 *
	 * @since 1.0.0
	 */
	function load_scripts() {

		global $post;

		$post_id = 0;

		if ( $post ) {
			$post_id = $post->ID;
		}

		$enable_animation = tbas_helper()->get_option( 'tbas_enable_animation' );

		$override_global = tbas_meta()->get_animation_meta_value( $post_id, 'tbas_override_global' );

		if ( is_singular() && 'yes' === $override_global ) {

			$enable_meta_animation = tbas_meta()->get_animation_meta_value( $post_id, 'tbas_enable_animation' );

			if ( 'yes' === $enable_meta_animation ) {
				$enable_animation = $enable_meta_animation;
			}
		}

		if ( 'yes' === $enable_animation ) {

			wp_enqueue_script( 'tbas-frontend', TBAS_URL . 'assets/js/frontend.min.js', array( 'jquery' ), TBAS_VER, true );

			$animation_show     = tbas_helper()->get_option( 'tbas_animation_show' );
			$animation_type     = tbas_helper()->get_option( 'tbas_animation_type' );
			$animation_title    = tbas_helper()->get_option( 'tbas_animation_title' );
			$animation_speed    = tbas_helper()->get_option( 'tbas_animation_speed' );
			$countdown_duration = tbas_helper()->get_option( 'tbas_countdown_duration' );
			$countdown_title    = tbas_helper()->get_option( 'tbas_countdown_title' );

			if ( is_singular() && 'yes' === $override_global ) {

				$animation_title_meta    = tbas_meta()->get_animation_meta_value( $post_id, 'tbas_animation_title' );
				$animation_show_meta     = tbas_meta()->get_animation_meta_value( $post_id, 'tbas_animation_show' );
				$animation_type_meta     = tbas_meta()->get_animation_meta_value( $post_id, 'tbas_animation_type' );
				$animation_speed_meta    = tbas_meta()->get_animation_meta_value( $post_id, 'tbas_animation_speed' );
				$countdown_duration_meta = tbas_meta()->get_animation_meta_value( $post_id, 'tbas_countdown_duration' );
				$countdown_title_meta    = tbas_meta()->get_animation_meta_value( $post_id, 'tbas_countdown_title' );

				if ( '' !== $animation_title_meta ) {
					$animation_title = $animation_title_meta;
				}

				if ( '' !== $animation_show_meta ) {
					$animation_show = $animation_show_meta;
				}

				if ( '' !== $animation_type_meta ) {
					$animation_type = $animation_type_meta;
				}

				if ( '' !== $animation_speed_meta ) {
					$animation_speed = $animation_speed_meta;
				}

				if ( '' !== $countdown_duration_meta ) {
					$countdown_duration = $countdown_duration_meta;
				}

				if ( '' !== $countdown_title_meta ) {
					$countdown_title = $countdown_title_meta;
				}
			}

			wp_localize_script(
				'tbas-frontend',
				'tbas_options',
				array(
					'animation_title'    => $animation_title,
					'animation_show'     => $animation_show,
					'animation_type'     => $animation_type,
					'animation_speed'    => $animation_speed,
					'countdown_duration' => $countdown_duration,
					'countdown_title'    => $countdown_title,
					'enable_on_focus'    => 'no',
					'delay_start'        => 500,
					'delay_stop'         => 500,
					'delay_cycle'        => 500,
				)
			);
		}
	}
}

/**
 *  Prepare if class 'Tbas_Frontend' exist.
 *  Kicking this off by calling 'get_instance()' method
 */
Tbas_Frontend::get_instance();
