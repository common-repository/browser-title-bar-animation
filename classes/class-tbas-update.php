<?php
/**
 * Update Compatibility
 *
 * @package tbas
 */

/**
 * Update initial setup
 *
 * @since 1.0.0
 */
class Tbas_Update {

	/**
	 * Class instance.
	 *
	 * @access private
	 * @var $instance Class instance.
	 */
	private static $instance;

	/**
	 * Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 *  Constructor
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'init' ) );
	}

	/**
	 * Init
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function init() {

		do_action( 'tbas_update_before' );

		// Get auto saved version number.
		$saved_version = get_option( 'tbas-version', false );

		// Update auto saved version number.
		if ( ! $saved_version ) {
			update_option( 'tbas-version', TBAS_VER );
			return;
		}

		// If equals then return.
		if ( version_compare( $saved_version, TBAS_VER, '=' ) ) {
			return;
		}

		// Update auto saved version number.
		update_option( 'tbas-version', TBAS_VER );

		do_action( 'tbas_update_after' );
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
Tbas_Update::get_instance();
