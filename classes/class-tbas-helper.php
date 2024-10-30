<?php
/**
 * Helper.
 *
 * @package tbas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Tbas_Helper.
 */
class Tbas_Helper {

	/**
	 * Instance object.
	 *
	 * @var instance
	 */
	private static $instance;

	/**
	 * Instance object.
	 *
	 * @var default_options
	 */
	private static $default_options = null;

	/**
	 * Instance object.
	 *
	 * @var common_options
	 */
	private static $common_options = null;

	/**
	 * Get things started
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
	}

	/**
	 * Default values
	 *
	 * @since 1.0.0
	 */
	public function default_options() {

		if ( null === self::$default_options ) {

			$defaults = array(
				'tbas_enable_animation'   => 'no',
				'tbas_animation_show'     => 'user-switch',
				'tbas_animation_type'     => 'typing',
				'tbas_animation_speed'    => 300,
				'tbas_animation_title'    => '',
				'tbas_countdown_duration' => 30,
				'tbas_countdown_title'    => '{{countdown}}',
			);

			self::$default_options = $defaults;
		}

		return apply_filters( 'tbas_default_options', self::$default_options );
	}

	/**
	 * Saved values
	 *
	 * @since 1.0.0
	 */
	public function common_options() {

		if ( null === self::$common_options ) {

			$defaults = array(
				'tbas_enable_animation'   => 'no',
				'tbas_animation_type'     => 'typing',
				'tbas_animation_speed'    => 100,
				'tbas_animation_title'    => '',
				'tbas_countdown_duration' => 30,
				'tbas_countdown_title'    => '{{countdown}}',
			);

			$stored_values = get_option( TBAS_SETTINGS_GROUP, array() );

			self::$common_options = wp_parse_args( $stored_values, $defaults );
		}

		return apply_filters( 'tbas_common_options', self::$common_options );
	}

	/**
	 * Get options
	 *
	 * @param string $option options.
	 * @param string $default default value.
	 * @since 1.0.0
	 */
	public function get_option( $option = '', $default = '' ) {

		$options = $this->common_options();

		if ( ! isset( $options[ $option ] ) ||
			( isset( $options[ $option ] ) && empty( $options[ $option ] ) )
		) {
			return $this->default_options()[ $option ];
		}

		return $options[ $option ];
	}

	/**
	 * Return the instance of class
	 *
	 * @return    object The instnace of of class
	 * @since     1.0.0
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) ) {

			self::$instance = new self;
		}

		return self::$instance;
	}
}

/**
 * Instance to functions everywhere.
 *
 * @return object The one true Tbas_Helper Instance
 * @since  1.0
 */
function tbas_helper() {
	return Tbas_Helper::instance();
}

tbas_helper();

