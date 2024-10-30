<?php
/**
 * Admin.
 *
 * @package tbas
 */

/**
 * Class Tbas_Admin.
 */
class Tbas_Admin {

	/**
	 * Instance object.
	 *
	 * @var instance
	 */
	private static $instance;

	/**
	 * Get things started
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		add_filter( 'plugin_action_links_' . TBAS_BASE, array( $this, 'add_action_links' ) );
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_init', array( $this, 'settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_style_scripts' ) );
	}

	/**
	 *  Show actions on the plugin page.
	 *
	 * @param array $links links.
	 * @return array
	 */
	public function add_action_links( $links ) {

		$mylinks = array(
			'<a href="' . admin_url( 'options-general.php?page=' . TBAS_SETTINGS_PAGE ) . '">' . __( 'Settings', 'browser-title-bar-animation' ) . '</a>',
		);

		return array_merge( $links, $mylinks );
	}

	/**
	 * Register menu
	 *
	 * @since 1.0.0
	 */
	public function register_menu() {
		add_options_page(
			__( 'Title Bar Animation', 'browser-title-bar-animation' ),
			__( 'Title Bar Animation', 'browser-title-bar-animation' ),
			'manage_options',
			TBAS_SETTINGS_PAGE,
			array( $this, 'admin_page_view_callback' )
		);
	}

	/**
	 * Admin page
	 *
	 * @since 1.0.0
	 */
	public function admin_page_view_callback() {

		?>
		<div class="wrap">
			<h2> <?php esc_attr_e( 'Title Bar Animation', 'browser-title-bar-animation' ); ?> </h2>
			<div class="tbas-settings-wrapper">
				<div class="tbas-settings-form">
					<form action="options.php" method="POST">
						<?php
						settings_fields( TBAS_SETTINGS_GROUP );
						do_settings_sections( TBAS_SETTINGS_PAGE );
						submit_button();
						?>
					</form>
				</div>
				<div class="tbas-contact-support">
				<?php
				echo '<h2>' . __( 'Support', 'browser-title-bar-animation' ) . '</h2>';
				echo '<p>' . __( 'Got a question? I\'m happy to help!', 'browser-title-bar-animation' ) . '</p>';
				echo '<p><a href="https://www.techiesandesh.com/contact/" target="_blank">' . __( 'Submit a Ticket »', 'browser-title-bar-animation' ) . '</a></p>';
				?>
				</div>
		</div>
		<?php
	}

	/**
	 * Settings
	 *
	 * @since 1.0.0
	 */
	public function settings() {

		add_settings_section(
			TBAS_SETTINGS_SECTION,
			__( 'General Settings', 'browser-title-bar-animation' ),
			array( $this, 'section_callback' ),
			TBAS_SETTINGS_PAGE
		);

		// Enable Animation.
		add_settings_field(
			'tbas_enable_animation',
			__( 'Enable Title Bar Animation', 'browser-title-bar-animation' ),
			array( $this, 'callback_input_checkbox' ),
			TBAS_SETTINGS_PAGE,
			TBAS_SETTINGS_SECTION,
			array(
				'name'        => 'tbas_enable_animation',
				'id'          => 'tbas_enable_animation',
				'description' => __( 'Enable Title Bar Animation Globally', 'browser-title-bar-animation' ),
			)
		);

		// Animation Type.
		add_settings_field(
			'tbas_animation_show',
			__( 'Apply Animation', 'browser-title-bar-animation' ),
			array( $this, 'callback_input_dropdown' ),
			TBAS_SETTINGS_PAGE,
			TBAS_SETTINGS_SECTION,
			array(
				'name'        => 'tbas_animation_show',
				'id'          => 'tbas_animation_show',
				'description' => __( 'When to apply animation?', 'browser-title-bar-animation' ),
				'options'     => array(
					'always'      => __( 'Always', 'browser-title-bar-animation' ),
					'user-switch' => __( 'When user switch to another tab', 'browser-title-bar-animation' ),
				),
			)
		);

		// Animation Type.
		add_settings_field(
			'tbas_animation_type',
			__( 'Animation Type', 'browser-title-bar-animation' ),
			array( $this, 'callback_input_dropdown' ),
			TBAS_SETTINGS_PAGE,
			TBAS_SETTINGS_SECTION,
			array(
				'name'        => 'tbas_animation_type',
				'id'          => 'tbas_animation_type',
				'description' => __( 'Animation Type', 'browser-title-bar-animation' ),
				'options'     => array(
					'typing'    => __( 'Typing', 'browser-title-bar-animation' ),
					'scrolling' => __( 'Scrolling', 'browser-title-bar-animation' ),
					'blinking'  => __( 'Blinking', 'browser-title-bar-animation' ),
					'countdown' => __( 'Countdown', 'browser-title-bar-animation' ),
				),
			)
		);

		add_settings_field(
			'tbas_animation_type_screenshots',
			'',
			array( $this, 'callback_screenshots' ),
			TBAS_SETTINGS_PAGE,
			TBAS_SETTINGS_SECTION,
			array(
				'name'        => 'tbas_animation_type_screenshots',
				'id'          => 'tbas_animation_type_screenshots',
				'description' => __( 'Animation Examples', 'browser-title-bar-animation' ),
			)
		);

		// Animation Speed.
		add_settings_field(
			'tbas_animation_speed',
			__( 'Animation Speed', 'browser-title-bar-animation' ),
			array( $this, 'callback_input_number' ),
			TBAS_SETTINGS_PAGE,
			TBAS_SETTINGS_SECTION,
			array(
				'name'        => 'tbas_animation_speed',
				'id'          => 'tbas_animation_speed',
				'description' => __( 'Animation speed', 'browser-title-bar-animation' ),
			)
		);

		// Animation Title.
		add_settings_field(
			'tbas_animation_title',
			__( 'Animation Title', 'browser-title-bar-animation' ),
			array( $this, 'callback_input_text' ),
			TBAS_SETTINGS_PAGE,
			TBAS_SETTINGS_SECTION,
			array(
				'name'        => 'tbas_animation_title',
				'id'          => 'tbas_animation_title',
				'description' => __( 'Animation will apply on this title. If empty, default title will consider as animation title.', 'browser-title-bar-animation' ),
			)
		);

		// Countdown Time.
		add_settings_field(
			'tbas_countdown_duration',
			__( 'Countdown Duration', 'browser-title-bar-animation' ),
			array( $this, 'callback_input_number' ),
			TBAS_SETTINGS_PAGE,
			TBAS_SETTINGS_SECTION,
			array(
				'name'        => 'tbas_countdown_duration',
				'id'          => 'tbas_countdown_duration',
				'description' => __( 'Time in minutes', 'browser-title-bar-animation' ),
			)
		);
		// Countdown Animation Title.
		add_settings_field(
			'tbas_countdown_title',
			__( 'Countdown Title', 'browser-title-bar-animation' ),
			array( $this, 'callback_input_text' ),
			TBAS_SETTINGS_PAGE,
			TBAS_SETTINGS_SECTION,
			array(
				'name'        => 'tbas_countdown_title',
				'id'          => 'tbas_countdown_title',
				'placeholder' => __( '{{countdown}}', 'browser-title-bar-animation' ),
				'description' => __( 'Animation will apply on this title. If empty, default title will consider as animation title.', 'browser-title-bar-animation' ),
			)
		);

		register_setting(
			TBAS_SETTINGS_GROUP,
			TBAS_SETTINGS_GROUP
		);

	}


	/**
	 * Section Intro Callback.
	 *
	 * @since 1.0.0
	 */
	public function section_callback() {
	}

	/**
	 * Input field callback
	 *
	 * @param array $args arguments.
	 * @since 1.0.0
	 */
	public function callback_input_text( $args ) {

		$value       = tbas_helper()->get_option( $args['name'] );
		$placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : '';
		?>
		<input style="width: 30%" type="text" id="<?php echo $args['id']; ?>" placeholder="<?php echo $placeholder; ?>" name="<?php echo TBAS_SETTINGS_GROUP; ?>[<?php echo $args['name']; ?>]" value="<?php echo $value; ?>"/>

		<?php if ( isset( $args['description'] ) ) : ?>
		<p class="description"><?php echo $args['description']; ?></p>
		<?php endif; ?>
		<?php
	}

	/**
	 * Input field callback
	 *
	 * @param array $args arguments.
	 * @since 1.0.0
	 */
	public function callback_input_number( $args ) {

		$value = tbas_helper()->get_option( $args['name'] );
		?>
		<input style="width: 10%" type="number" id="<?php echo $args['id']; ?>" name="<?php echo TBAS_SETTINGS_GROUP; ?>[<?php echo $args['name']; ?>]" value="<?php echo $value; ?>"/>

		<?php if ( isset( $args['description'] ) ) : ?>
		<p class="description"><?php echo $args['description']; ?></p>
		<?php endif; ?>
		<?php
	}

	/**
	 * Input field callback
	 *
	 * @param array $args arguments.
	 * @since 1.0.0
	 */
	public function callback_input_dropdown( $args ) {

		$value       = tbas_helper()->get_option( $args['name'] );
		$options     = $args['options'];
		$pro_options = isset( $args['pro-options'] ) ? $args['pro-options'] : array();
		?>

		<select id="<?php echo $args['id']; ?>" name='<?php echo TBAS_SETTINGS_GROUP; ?>[<?php echo $args['name']; ?>]'>
			<?php foreach ( $options as $slug => $title ) { ?>
				<?php
					$disabled = '';

				if ( array_key_exists( $slug, $pro_options ) ) {
					$disabled = 'disabled ';
					$title    = $pro_options[ $slug ];
				}
				?>
				<option value='<?php echo $slug; ?>' <?php echo $disabled; ?> <?php selected( $value, $slug ); ?>><?php echo $title; ?></option>
			<?php } ?>
		</select>
		<?php if ( isset( $args['description'] ) ) : ?>
		<p class="description"><?php echo $args['description']; ?></p>
		<?php endif; ?>
		<?php
	}

	/**
	 * Input field callback
	 *
	 * @param array $args arguments.
	 * @since 1.0.0
	 */
	public function callback_input_checkbox( $args ) {

		$value = tbas_helper()->get_option( $args['name'] );

		?>
		<input type="hidden" id="<?php echo $args['id']; ?>" name="<?php echo TBAS_SETTINGS_GROUP; ?>[<?php echo $args['name']; ?>]" value="no"/>
		<input <?php echo 'yes' === $value ? 'checked' : ''; ?> type="checkbox" id="<?php echo $args['id']; ?>" name="<?php echo TBAS_SETTINGS_GROUP; ?>[<?php echo $args['name']; ?>]" value="yes"/>
		<?php echo $args['description']; ?>
		<?php if ( isset( $args['description'] ) ) : ?>
		<p class="description"></p>
		<?php endif; ?>
		<?php
	}

	/**
	 * Input field callback
	 *
	 * @param array $args arguments.
	 * @since 1.0.0
	 */
	public function callback_screenshots( $args ) {

		$animation_type = tbas_helper()->get_option( 'tbas_animation_type' );

		?>
		<div class="tbas-animation-screenshots">
			<div class="tbas-screenshot tbas-screenshot-typing">
				<img src="<?php echo TBAS_URL; ?>/admin/assets/images/title-bar-typing-effect.gif"  id="<?php echo $args['id']; ?>"/>
			</div>
			<div class="tbas-screenshot tbas-screenshot-scrolling">
				<img src="<?php echo TBAS_URL; ?>/admin/assets/images/title-bar-scrolling-effect.gif"  id="<?php echo $args['id']; ?>"/>
			</div>
			<div class="tbas-screenshot tbas-screenshot-blinking">
				<img src="<?php echo TBAS_URL; ?>/admin/assets/images/title-bar-blinking-effect.gif"  id="<?php echo $args['id']; ?>"/>
			</div>
			<div class="tbas-screenshot tbas-screenshot-countdown">
				<img src="<?php echo TBAS_URL; ?>/admin/assets/images/title-bar-countdown-effect.gif"  id="<?php echo $args['id']; ?>"/>
			</div>
		</div>
		<?php
	}


	/**
	 *  Add scripts and styles
	 *
	 * @since 1.0.0
	 */
	public function add_style_scripts() {

		if ( ! ( isset( $_GET['page'] ) && TBAS_SETTINGS_PAGE === $_GET['page'] ) ) { // phpcs:ignore
			return;
		}

		wp_enqueue_style( 'tbas-admin', TBAS_URL . 'admin/assets/css/admin-settings.css', array(), TBAS_VER );
		wp_enqueue_script( 'tbas-admin', TBAS_URL . 'admin/assets/js/admin-settings.js', array( 'jquery' ), TBAS_VER, true );

		$localize = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		);

		wp_localize_script( 'tbas-admin', 'tbas_vars', $localize );
	}

	/**
	 * Return the instance of class
	 *
	 * @return    object The instnace of of class
	 * @since     1.0.0
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) ) {

			self::$instance = new Tbas_Admin();
		}

		return self::$instance;
	}
}

/**
 * Instance to functions everywhere.
 *
 * @return object The one true Tbas_Admin Instance
 * @since  1.0
 */
Tbas_Admin::instance();
