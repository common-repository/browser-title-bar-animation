<?php
/**
 * Meta Fields.
 *
 * @package tbas
 */

/**
 * Class Tbas_Meta_Fields.
 */
class Tbas_Meta_Fields {

	/**
	 * Instance
	 *
	 * @var $instance
	 */
	private static $instance;

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

		/* Add Scripts */
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_meta_scripts' ), 20 );
	}

	/**
	 * Admin meta scripts
	 *
	 * @since 1.0.0
	 */
	public function admin_meta_scripts() {

		global $pagenow;
		global $post;

		$screen = get_current_screen();

		if ( ( 'post-new.php' === $pagenow || 'post.php' === $pagenow ) ) {

			wp_enqueue_script(
				'tbas-admin-meta',
				TBAS_URL . 'admin/assets/meta/js/meta-fields.js',
				array( 'jquery' ),
				TBAS_VER,
				true
			);

			wp_enqueue_style( 'tbas-admin-meta', TBAS_URL . 'admin/assets/meta/css/meta-fields.css', array(), TBAS_VER );
		}
	}

	/**
	 * Field common function
	 *
	 * @param array  $field_data Field data.
	 * @param string $field_content Field html.
	 * @since 1.0.0
	 */
	function get_field( $field_data, $field_content ) {

		$label       = isset( $field_data['label'] ) ? $field_data['label'] : '';
		$help        = isset( $field_data['help'] ) ? $field_data['help'] : '';
		$after_html  = isset( $field_data['after_html'] ) ? $field_data['after_html'] : '';
		$description = isset( $field_data['description'] ) ? $field_data['description'] : '';

		$name_class = 'field-' . $field_data['name'];

		$field_html = '<div class="tbas-field-row ' . $name_class . '">';

		if ( ! empty( $label ) || ! empty( $help ) ) {
			$field_html .= '<div class="tbas-field-row-heading">';
			if ( ! empty( $label ) ) {
				$field_html .= '<label>' . esc_html( $label ) . '</label>';
			}
			if ( ! empty( $help ) ) {
				$field_html .= '<i class="tbas-field-heading-help dashicons dashicons-editor-help">';
					// $field_html .= '<span class="tbas-tooltip" data-tooltip= "'. esc_attr( $help ) .'"></span>';
				$field_html     .= '</i>';
				$field_html     .= '<span class="tbas-tooltip-text">';
					$field_html .= $help;
				$field_html     .= '</span>';
			}
			$field_html .= '</div>';
		}

		$field_html .= '<div class="tbas-field-row-content">';
		$field_html .= $field_content;

		if ( ! empty( $description ) ) {
			$field_html .= '<div class="tbas-field-desc"><p class="description">' . $description . '</p></div>';
		}
		if ( ! empty( $after_html ) ) {
			$field_html .= $after_html;
		}

		$field_html .= '</div>';
		$field_html .= '</div>';

		return $field_html;
	}

	/**
	 * Text field
	 *
	 * @param array $field_data Field data.
	 * @since 1.0.0
	 */
	function get_text_field( $field_data ) {

		$value = $field_data['value'];

		$attr = '';

		if ( isset( $field_data['attr'] ) && is_array( $field_data['attr'] ) ) {

			foreach ( $field_data['attr'] as $attr_key => $attr_value ) {
				$attr .= ' ' . $attr_key . '="' . $attr_value . '"';
			}
		}

		$field_content = '<input type="text" name="' . $field_data['name'] . '" value="' . $value . '" ' . $attr . '>';

		return $this->get_field( $field_data, $field_content );
	}

	/**
	 * Shortcode field
	 *
	 * @param array $field_data Field data.
	 * @since 1.0.0
	 */
	function get_shortcode_field( $field_data ) {

		$attr = '';

		$attr_fields = array(
			'readonly'  => 'readonly',
			'onfocus'   => 'this.select()',
			'onmouseup' => 'return false',
		);

		if ( $attr_fields && is_array( $attr_fields ) ) {

			foreach ( $attr_fields as $attr_key => $attr_value ) {
				$attr .= ' ' . $attr_key . '="' . $attr_value . '"';
			}
		}

		$field_content = '<input type="text" name="' . $field_data['name'] . '" value="' . $field_data['content'] . '" ' . $attr . '>';

		return $this->get_field( $field_data, $field_content );
	}

	/**
	 * Display field
	 *
	 * @param array $field_data Field data.
	 * @since 1.0.0
	 */
	function get_display_field( $field_data ) {

		$field_content = $field_data['content'];

		return $this->get_field( $field_data, $field_content );
	}

	/**
	 * HR line field
	 *
	 * @param array $field_data Field data.
	 * @since 1.0.0
	 */
	function get_hr_line_field( $field_data ) {

		$field_data = array(
			'name'    => 'tbas-hr-line',
			'content' => '<hr>',
		);

		$field_content = $field_data['content'];

		return $this->get_field( $field_data, $field_content );
	}

	/**
	 * Number field
	 *
	 * @param array $field_data Field data.
	 * @since 1.0.0
	 */
	function get_number_field( $field_data ) {

		$value = $field_data['value'];

		$attr = '';

		if ( isset( $field_data['attr'] ) && is_array( $field_data['attr'] ) ) {

			foreach ( $field_data['attr'] as $attr_key => $attr_value ) {
				$attr .= ' ' . $attr_key . '="' . $attr_value . '"';
			}
		}

		$field_content = '<input type="number" name="' . $field_data['name'] . '" value="' . $value . '" ' . $attr . '>';

		return $this->get_field( $field_data, $field_content );
	}

	/**
	 * Hidden field
	 *
	 * @param array $field_data Field data.
	 * @since 1.0.0
	 */
	function get_hidden_field( $field_data ) {

		$value = $field_data['value'];

		$attr = '';

		if ( isset( $field_data['attr'] ) && is_array( $field_data['attr'] ) ) {

			foreach ( $field_data['attr'] as $attr_key => $attr_value ) {
				$attr .= ' ' . $attr_key . '="' . $attr_value . '"';
			}
		}

		$field_content = '<input type="hidden" id="' . $field_data['name'] . '" name="' . $field_data['name'] . '" value="' . $value . '" ' . $attr . '>';

		return $this->get_field( $field_data, $field_content );
	}

	/**
	 * Textare field
	 *
	 * @param array $field_data Field data.
	 * @since 1.0.0
	 */
	function get_area_field( $field_data ) {

		$value = $field_data['value'];

		$field_content  = '<textarea name="' . $field_data['name'] . '" rows="10" cols="50">';
		$field_content .= $value;
		$field_content .= '</textarea>';

		return $this->get_field( $field_data, $field_content );
	}

	/**
	 * Checkobox field
	 *
	 * @param array $field_data Field data.
	 * @since 1.0.0
	 */
	function get_checkbox_field( $field_data ) {

		$value = $field_data['value'];

		$field_content = '';
		if ( isset( $field_data['before'] ) ) {
			$field_content .= '<span>' . $field_data['before'] . '</span>';
		}
		$field_content .= '<input type="hidden" name="' . $field_data['name'] . '" value="no">';
		$field_content .= '<input type="checkbox" id="' . $field_data['name'] . '" name="' . $field_data['name'] . '" value="yes" ' . checked( 'yes', $value, false ) . '>';

		if ( isset( $field_data['after'] ) ) {
			$field_content .= '<span>' . $field_data['after'] . '</span>';
		}

		return $this->get_field( $field_data, $field_content );
	}

	/**
	 * Radio field
	 *
	 * @param array $field_data Field data.
	 * @since 1.0.0
	 */
	function get_radio_field( $field_data ) {

		$value         = $field_data['value'];
		$field_content = '';

		if ( is_array( $field_data['options'] ) && ! empty( $field_data['options'] ) ) {

			foreach ( $field_data['options'] as $data_key => $data_value ) {

				$field_content .= '<div class="tbas-radio-option">';
				$field_content .= '<input type="radio" name="' . $field_data['name'] . '" value="' . $data_key . '" ' . checked( $data_key, $value, false ) . '>';
				$field_content .= $data_value;
				$field_content .= '</div>';
			}
		}

		return $this->get_field( $field_data, $field_content );
	}

	/**
	 * Select field
	 *
	 * @param array $field_data Field data.
	 * @since 1.0.0
	 */
	function get_select_field( $field_data ) {

		$value       = $field_data['value'];
		$pro_options = isset( $field_data['pro-options'] ) ? $field_data['pro-options'] : array();

		$field_content = '<select name="' . $field_data['name'] . '">';

		if ( is_array( $field_data['options'] ) && ! empty( $field_data['options'] ) ) {

			foreach ( $field_data['options'] as $data_key => $data_value ) {

				$disabled = '';

				if ( array_key_exists( $data_key, $pro_options ) ) {
					$disabled   = 'disabled ';
					$data_value = $pro_options[ $data_key ];
				}

				$field_content .= '<option value="' . $data_key . '" ' . selected( $value, $data_key, false ) . ' ' . $disabled . '>' . $data_value . '</option>';
			}
		}

		$field_content .= '</select>';

		if ( isset( $field_data['after'] ) ) {
			$field_content .= '<span>' . $field_data['after'] . '</span>';
		}

		return $this->get_field( $field_data, $field_content );
	}

	/**
	 * Section field
	 *
	 * @param array $field_data Field data.
	 * @since 1.0.0
	 */
	function get_section( $field_data ) {
		$field_html          = '<div class="tbas-field-row tbas-field-section">';
			$field_html     .= '<div class="tbas-field-section-heading" colspan="2">';
				$field_html .= '<label>' . esc_html( $field_data['label'] ) . '</label>';

		if ( isset( $field_data['help'] ) ) {
			$field_html .= '<i class="tbas-field-heading-help dashicons dashicons-editor-help" title="' . esc_attr( $field_data['help'] ) . '"></i>';
		}
			$field_html .= '</div>';
		$field_html     .= '</div>';
		return $field_html;
	}

	/**
	 * Descriptions field
	 *
	 * @param array $field_data Field data.
	 * @since 1.0.0
	 */
	function get_description_field( $field_data ) {

		$field_html          = '<div class="tbas-field-row tbas-field-desc ' . $field_data['name'] . '">';
			$field_html     .= '<div class="tbas-field-desc-content">';
				$field_html .= $field_data['content'];
			$field_html     .= '</div>';
		$field_html         .= '</div>';

		return $field_html;
	}
}

Tbas_Meta_Fields::get_instance();
