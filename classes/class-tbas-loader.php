<?php
/**
 * Loader.
 *
 * @package wp-tbas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Tbas_Loader' ) ) {

	/**
	 * Class Tbas_Loader.
	 */
	final class Tbas_Loader {

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

			$this->define_constants();

			$this->load_helper_files_components();

			/* Activation hook. */
			register_activation_hook( TBAS_FILE, array( $this, 'activation_reset' ) );

			/* deActivation hook. */
			register_deactivation_hook( TBAS_FILE, array( $this, 'deactivation_reset' ) );

			/* Load Plugin */
			add_action( 'plugins_loaded', array( $this, 'load_plugin' ), 99 );

			/* Text Domain */
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		}

		/**
		 * Defines all constants
		 *
		 * @since 1.0.0
		 */
		function define_constants() {

			define( 'TBAS_BASE', plugin_basename( TBAS_FILE ) );
			define( 'TBAS_DIR', plugin_dir_path( TBAS_FILE ) );
			define( 'TBAS_URL', plugins_url( '/', TBAS_FILE ) );
			define( 'TBAS_VER', '1.3.0' );
			define( 'TBAS_SLUG', 'tbas' );
			define( 'TBAS_SETTINGS_PAGE', 'tbas-settings' );
			define( 'TBAS_SETTINGS_GROUP', 'tbas_settings' );
			define( 'TBAS_SETTINGS_SECTION', 'tbas_settings_section' );
		}

		/**
		 * Load Helper Files and Components.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		function load_helper_files_components() {

			/* Helper */
			include_once TBAS_DIR . 'classes/class-tbas-helper.php';

			/* Update */
			include_once TBAS_DIR . 'classes/class-tbas-update.php';

			/* Default Meta */
			include_once TBAS_DIR . 'classes/class-tbas-default-meta.php';
			include_once TBAS_DIR . 'classes/class-tbas-meta-fields.php';
			include_once TBAS_DIR . 'classes/class-tbas-metabox.php';

			/* Admin */
			include_once TBAS_DIR . 'classes/class-tbas-admin.php';

			/* Frontend */
			include_once TBAS_DIR . 'classes/class-tbas-frontend.php';

		}

		/**
		 * Loads plugin files.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		function load_plugin() {
		}

		/**
		 * Load browser-title-bar-animation Text Domain.
		 * This will load the translation textdomain depending on the file priorities.
		 *      1. Global Languages /wp-content/languages/browser-title-bar-animation/ folder
		 *      2. Local dorectory /wp-content/plugins/browser-title-bar-animation/languages/ folder
		 *
		 * @since 1.0.3
		 * @return void
		 */
		public function load_textdomain() {

			// Default languages directory.
			$lang_dir = TBAS_DIR . 'languages/';

			/**
			 * Filters the languages directory path to use.
			 *
			 * @param string $lang_dir The languages directory path.
			 */
			$lang_dir = apply_filters( 'tbas_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter.
			global $wp_version;

			$get_locale = get_locale();

			if ( $wp_version >= 4.7 ) {
				$get_locale = get_user_locale();
			}

			/**
			 * Language Locale
			 *
			 * @var $get_locale The locale to use.
			 * Uses get_user_locale()` in WordPress 4.7 or greater,
			 * otherwise uses `get_locale()`.
			 */
			$locale = apply_filters( 'plugin_locale', $get_locale, 'browser-title-bar-animation' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'browser-title-bar-animation', $locale );

			// Setup paths to current locale file.
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/plugins/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/browser-title-bar-animation/ folder.
				load_textdomain( 'browser-title-bar-animation', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/browser-title-bar-animation/languages/ folder.
				load_textdomain( 'browser-title-bar-animation', $mofile_local );
			} else {
				// Load the default language files.
				load_plugin_textdomain( 'browser-title-bar-animation', false, $lang_dir );
			}
		}

		/**
		 * Activation Reset
		 */
		function activation_reset() {
		}

		/**
		 * Deactivation Reset
		 */
		function deactivation_reset() {
		}
	}

	/**
	 *  Prepare if class 'Tbas_Loader' exist.
	 *  Kicking this off by calling 'get_instance()' method
	 */
	Tbas_Loader::get_instance();

	if ( ! function_exists( 'tbas' ) ) {
		/**
		 * Get global class.
		 *
		 * @return object
		 */
		function tbas() {
			return Tbas_Loader::get_instance();
		}
	}

	if ( ! function_exists( 'tbas_meta_fields' ) ) {
		/**
		 * Get tbas meta.
		 *
		 * @return object
		 */
		function tbas_meta_fields() {
			return Tbas_Meta_Fields::get_instance();
		}
	}

	if ( ! function_exists( 'tbas_meta' ) ) {
		/**
		 * Get tbas meta.
		 *
		 * @return object
		 */
		function tbas_meta() {
			return Tbas_Default_Meta::get_instance();
		}
	}
}
