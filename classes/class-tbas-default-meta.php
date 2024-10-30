<?php
/**
 * Default meta options.
 *
 * @package tbas
 */

/**
 * Initialization
 *
 * @since 1.0.0
 */
class Tbas_Default_Meta {


	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	private static $instance;

	/**
	 * Member Variable
	 *
	 * @var animation_fields
	 */
	private static $animation_fields = null;

	/**
	 *  Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 *  Constructor
	 */
	public function __construct() {

	}


	/**
	 *  Flow Default fields.
	 *
	 * @param int $post_id post id.
	 * @return array
	 */
	function get_animation_fields( $post_id ) {

		if ( null === self::$animation_fields ) {

			self::$animation_fields = array(
				'tbas_override_global'    => array(
					'default'  => 'no',
					'sanitize' => 'FILTER_DEFAULT',
				),
				'tbas_enable_animation'   => array(
					'default'  => 'yes',
					'sanitize' => 'FILTER_DEFAULT',
				),
				'tbas_animation_show'     => array(
					'default'  => 'user-switch',
					'sanitize' => 'FILTER_DEFAULT',
				),
				'tbas_animation_type'     => array(
					'default'  => 'typing',
					'sanitize' => 'FILTER_DEFAULT',
				),
				'tbas_animation_speed'    => array(
					'default'  => 300,
					'sanitize' => 'FILTER_SANITIZE_NUMBER_INT',
				),
				'tbas_animation_title'    => array(
					'default'  => '',
					'sanitize' => 'FILTER_DEFAULT',
				),
				'tbas_countdown_duration' => array(
					'default'  => 30,
					'sanitize' => 'FILTER_SANITIZE_NUMBER_INT',
				),
				'tbas_countdown_title'    => array(
					'default'  => '{{countdown}}',
					'sanitize' => 'FILTER_DEFAULT',
				),
			);
		}

		return self::$animation_fields;
	}

	/**
	 *  Save Animation Meta fields.
	 *
	 * @param int $post_id post id.
	 * @return void
	 */
	function save_animation_fields( $post_id ) {

		$post_meta = $this->get_animation_fields( $post_id );

		$this->save_meta_fields( $post_id, $post_meta );
	}

	/**
	 *  Save Meta fields - Common Function.
	 *
	 * @param int   $post_id post id.
	 * @param array $post_meta options to store.
	 * @return void
	 */
	function save_meta_fields( $post_id, $post_meta ) {

		if ( ! ( $post_id && is_array( $post_meta ) ) ) {

			return;
		}

		foreach ( $post_meta as $key => $data ) {

			$meta_value = false;

			// Sanitize values.
			$sanitize_filter = ( isset( $data['sanitize'] ) ) ? $data['sanitize'] : 'FILTER_DEFAULT';

			switch ( $sanitize_filter ) {

				case 'FILTER_SANITIZE_STRING':
					$meta_value = filter_input( INPUT_POST, $key, FILTER_SANITIZE_STRING );
					break;

				case 'FILTER_SANITIZE_URL':
					$meta_value = filter_input( INPUT_POST, $key, FILTER_SANITIZE_URL );
					break;

				case 'FILTER_SANITIZE_NUMBER_INT':
					$meta_value = filter_input( INPUT_POST, $key, FILTER_SANITIZE_NUMBER_INT );
					break;

				case 'FILTER_SANITIZE_ARRAY_STRING':
					if ( isset( $_POST[ $key ] ) && is_array( $_POST[ $key ] ) ) {
						$meta_value = array_map( 'sanitize_text_field', $_POST[ $key ] );
					}
					break;

				default:
					$meta_value = filter_input( INPUT_POST, $key, FILTER_DEFAULT );
					break;
			}

			if ( false !== $meta_value ) {
				update_post_meta( $post_id, $key, $meta_value );
			} else {
				delete_post_meta( $post_id, $key );
			}
		}
	}

	/**
	 *  Get checkout meta.
	 *
	 * @param int    $post_id post id.
	 * @param string $key options key.
	 * @param mix    $default options default value.
	 * @return string
	 */
	function get_animation_meta_value( $post_id, $key, $default = false ) {

		$value = $this->get_save_meta( $post_id, $key );

		if ( ! $value ) {

			if ( $default ) {

				$value = $default;
			} else {

				$fields = $this->get_animation_fields( $post_id );

				if ( isset( $fields[ $key ]['default'] ) ) {

					$value = $fields[ $key ]['default'];
				}
			}
		}

		return $value;
	}


	/**
	 *  Get post meta.
	 *
	 * @param int    $post_id post id.
	 * @param string $key options key.
	 * @return string
	 */
	function get_save_meta( $post_id, $key ) {

		$value = get_post_meta( $post_id, $key, true );

		return $value;
	}
}

/**
 *  Kicking this off by calling 'get_instance()' method
 */
Tbas_Default_Meta::get_instance();
