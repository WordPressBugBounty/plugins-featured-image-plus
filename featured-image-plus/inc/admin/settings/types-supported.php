<?php
/**
 * [Short description]
 *
 * @package    DEVRY\FIP
 * @copyright  Copyright (c) 2024, Developry Ltd.
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU Public License
 * @since      1.4
 */

namespace DEVRY\FIP;

! defined( ABSPATH ) || exit; // Exit if accessed directly.

/**
 * Display the setting.
 */
function fip_display_types_supported() {
	$fip = new Featured_Image_Plus();

	$types_supported = get_option( 'fip_types_supported', $fip->types_supported );

	$options_html = '';

	$types_available = array_merge(
		array(
			'post' => 'post',
			'page' => 'page',
		),
		get_post_types(
			array(
				'public'   => true,
				'_builtin' => false,
			),
			'names',
			'and'
		)
	);

	foreach ( $types_available as $type ) {
		$type_text = ucwords( $type );
		$selected  = '';

		if ( in_array( $type, $types_supported, true ) ) {
			$selected = 'selected';
		}

		$options_html .= "<option value=\"{$type}\" {$selected}>{$type_text}</option>";
	}
	?>
		<select id="fip-types-supported" name="fip_types_supported[]" multiple>
			<?php echo wp_kses( $options_html, json_decode( FIP_PLUGIN_ALLOWED_HTML_ARR, true ) ); ?>
		</select>
		<p class="description">
			<small>
				<?php echo esc_html__( 'Choose the post types supported by the plugin.', 'featured-image-plus' ); ?>
			</small>
		</p>
	<?php
}

/**
 * Sanitize and update option.
 */
function fip_sanitize_types_supported( $types_supported ) {
	// Verify the nonce.
	$_wpnonce = ( isset( $_REQUEST['fip_wpnonce'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['fip_wpnonce'] ) ) : '';

	if ( empty( $_wpnonce ) || ! wp_verify_nonce( $_wpnonce, 'fip_settings_nonce' ) ) {
		return;
	}

	// Nothing selected.
	if ( empty( $types_supported ) ) {
		return;
	}

	// Option changed and updated.
	if ( ! get_transient( 'fip_settings_types_supported' )
		&& get_option( 'fip_types_supported' ) != $types_supported ) { // Don't use strict comparsions to check that arrays are equal.
		add_settings_error(
			'fip_settings_errors',
			'fip_settings_types_supported',
			esc_html__( 'Supported types option was updated successfully.', 'featured-image-plus' ),
			'updated'
		);

		// Add transient to avoid double notice on initial Save when using settings_errors().
		set_transient( 'fip_settings_types_supported', true, 5 );
	}

	return array_map( 'sanitize_text_field', $types_supported );
}
