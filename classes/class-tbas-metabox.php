<?php
/**
 * Metabox
 *
 * @package tbas
 */

/**
 * Meta Boxes setup
 */
class Tbas_Metabox {


	/**
	 * Instance
	 *
	 * @var $instance
	 */
	private static $instance;

	/**
	 * Meta Option
	 *
	 * @var $meta_option
	 */
	private static $meta_option;

	/**
	 * Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {

		/* Init Metabox */
		add_action( 'load-post.php', array( $this, 'init_metabox' ) );
		add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
	}

	/**
	 * Initialize meta box
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init_metabox() {

		/**
		 * Fires after the title field.
		 *
		 * @param WP_Post $post Post object.
		 */
		add_action( 'add_meta_boxes', array( $this, 'register_settings_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_meta_box' ) );
	}

	/**
	 * Settings meta box.
	 *
	 * @return void
	 */
	function register_settings_meta_box() {

		add_meta_box(
			'tbas-title-animation',                     // Id.
			__( 'Title Bar Animation', 'browser-title-bar-animation' ),        // Title.
			array( $this, 'title_bar_animation_meta_box' ),      // Callback.
			null,           // Post_type.
			'normal',       // Context.
			'high'          // Priority.
		);
	}

	/**
	 * Metabox Markup
	 *
	 * @param object $post Post object.
	 * @return void
	 */
	function title_bar_animation_meta_box( $post ) {

		/**
		 * Get options
		 */
		$updated_data = self::get_current_saved_meta( $post->ID );

		$this->title_bar_animation_meta_box_markup( $updated_data );
	}

	/**
	 * Page Header Tabs
	 *
	 * @param  array $options Post meta.
	 * @return void
	 */
	function title_bar_animation_meta_box_markup( $options ) {

		wp_nonce_field( 'save-nonce-title-animation-meta', 'nonce-title-animation-meta' );

		echo tbas_meta_fields()->get_checkbox_field(
			array(
				'label' => __( 'Override Global Settings', 'browser-title-bar-animation' ),
				'name'  => 'tbas_override_global',
				'value' => $options['tbas_override_global'],
				'after' => __( 'Enable this to override global settings', 'browser-title-bar-animation' ),
			)
		);

		echo '<div class="tbas-override-meta-settings-fields">';
			echo tbas_meta_fields()->get_select_field(
				array(
					'label'   => __( 'Enable Title Bar Animation', 'browser-title-bar-animation' ),
					'name'    => 'tbas_enable_animation',
					'value'   => $options['tbas_enable_animation'],
					'options' => array(
						'yes' => __( 'Yes', 'browser-title-bar-animation' ),
						'no'  => __( 'No', 'browser-title-bar-animation' ),
					),
				)
			);

			echo tbas_meta_fields()->get_select_field(

				array(
					'label'       => __( 'Animation Show', 'browser-title-bar-animation' ),
					'name'        => 'tbas_animation_show',
					'value'       => $options['tbas_animation_show'],
					'options'     => array(
						'always'      => __( 'Always', 'browser-title-bar-animation' ),
						'user-switch' => __( 'When user switch to another tab', 'browser-title-bar-animation' ),
					),
					'description' => __( 'When to apply animation?', 'browser-title-bar-animation' ),
				)
			);

			echo tbas_meta_fields()->get_select_field(

				array(
					'label'   => __( 'Animation Type', 'browser-title-bar-animation' ),
					'name'    => 'tbas_animation_type',
					'value'   => $options['tbas_animation_type'],
					'options' => array(
						'typing'    => __( 'Typing', 'browser-title-bar-animation' ),
						'scrolling' => __( 'Scrolling', 'browser-title-bar-animation' ),
						'blinking'  => __( 'Blinking', 'browser-title-bar-animation' ),
						'countdown' => __( 'Countdown', 'browser-title-bar-animation' ),
					),
				)
			);

			echo tbas_meta_fields()->get_number_field(
				array(
					'label'       => __( 'Animation Speed', 'browser-title-bar-animation' ),
					'name'        => 'tbas_animation_speed',
					'value'       => $options['tbas_animation_speed'],
					'description' => __( 'Animation speed. eg. 1000 for 1second', 'browser-title-bar-animation' ),
				)
			);

			echo tbas_meta_fields()->get_text_field(
				array(
					'label' => __( 'Animation Title', 'browser-title-bar-animation' ),
					'name'  => 'tbas_animation_title',
					'value' => $options['tbas_animation_title'],
					'help'  => __( 'Animation will apply on this title. If empty, default title will consider as animation title.', 'browser-title-bar-animation' ),
				)
			);
			echo tbas_meta_fields()->get_number_field(
				array(
					'label'       => __( 'Countdown Duration', 'browser-title-bar-animation' ),
					'name'        => 'tbas_countdown_duration',
					'value'       => $options['tbas_countdown_duration'],
					'description' => __( 'Duration in minutes.', 'browser-title-bar-animation' ),
				)
			);

			echo tbas_meta_fields()->get_text_field(
				array(
					'label'       => __( 'Countdown Title', 'browser-title-bar-animation' ),
					'name'        => 'tbas_countdown_title',
					'value'       => $options['tbas_countdown_title'],
					'help'        => __( 'User {{countdown}} tag.', 'browser-title-bar-animation' ),
					'description' => __( 'You can use {{countdown}} tag in string.', 'browser-title-bar-animation' ),
				)
			);
		echo '</div>';
	}

	/**
	 * Get metabox options
	 *
	 * @param int $post_id post id.
	 * @return array
	 */
	public static function get_meta_option( $post_id ) {

		if ( null === self::$meta_option ) {
			/**
			 * Set metabox options
			 */
			self::$meta_option = tbas_meta()->get_animation_fields( $post_id );
		}

		return self::$meta_option;
	}

	/**
	 * Get metabox options
	 *
	 * @param int $post_id post ID.
	 * @return array
	 */
	public static function get_current_saved_meta( $post_id ) {

		$stored        = get_post_meta( $post_id );
		$default_meta  = self::get_meta_option( $post_id );
		$saved_options = array();

		foreach ( $default_meta as $key => $value ) {

			if ( isset( $stored[ $key ] ) ) {
				self::$meta_option[ $key ]['default'] = ( isset( $stored[ $key ][0] ) ) ? maybe_unserialize( $stored[ $key ][0] ) : '';
			}

			$saved_options[ $key ] = self::$meta_option[ $key ]['default'];
		}

		return $saved_options;
	}

	/**
	 * Metabox Save
	 *
	 * @param  number $post_id Post ID.
	 * @return void
	 */
	function save_meta_box( $post_id ) {

		// Checks save status.
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );

		$is_valid_nonce = ( isset( $_POST['nonce-title-animation-meta'] ) && wp_verify_nonce( $_POST['nonce-title-animation-meta'], 'save-nonce-title-animation-meta' ) ) ? true : false;

		// Exits script depending on save status.
		if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
			return;
		}

		tbas_meta()->save_animation_fields( $post_id );
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
Tbas_Metabox::get_instance();
