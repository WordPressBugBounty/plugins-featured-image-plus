<?php
/**
 * [Short description]
 *
 * @package    DEVRY\FIP
 * @copyright  Copyright (c) 2025, Developry Ltd.
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU Public License
 * @since      1.5
 */

namespace DEVRY\FIP;

! defined( ABSPATH ) || exit; // Exit if accessed directly.

/**
 * Display the setting.
 */
function fip_display_compact_mode() {
	$fip = new Featured_Image_Plus();

	$compact_mode = get_option( 'fip_compact_mode', $fip->compact_mode );

	// Compact mode option if empty or non-existent then No, otherwise Yes.
	if ( 'yes' === $compact_mode ) {
		$compact_mode = 'selected';
	}

	printf(
		'<select id="fip-compact-mode" name="fip_compact_mode">
			<option value="">No</option>
			<option value="yes" %1$s>Yes</option>
		</select>',
		esc_attr( $compact_mode )
	);
	?>
		<p class="description">
			<small>
				<?php echo esc_html__( 'Compact mode moves the plugin access link under Settings.', 'featured-image-plus' ); ?>
			</small>
		</p>
	<?php
}

/**
 * Sanitize and update option.
 */
function fip_sanitize_compact_mode( $compact_mode ) {
	// Verify the nonce.
	$_wpnonce = ( isset( $_REQUEST['fip_wpnonce'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['fip_wpnonce'] ) ) : '';

	if ( empty( $_wpnonce ) || ! wp_verify_nonce( $_wpnonce, 'fip_settings_nonce' ) ) {
		return;
	}

	// Nothing selected.
	if ( empty( $compact_mode ) ) {
		return;
	}

	// Option changed and updated.
	if ( ! get_transient( 'fip_settings_compact_mode' )
		&& get_option( 'fip_compact_mode', '' ) !== $compact_mode ) {
		add_settings_error(
			'fip_settings_errors',
			'fip_settings_compact_mode',
			esc_html__( 'Compact mode option was updated successfully.', 'featured-image-plus' ),
			'updated'
		);

		// Add transient to avoid double notice on initial Save when using settings_errors().
		set_transient( 'fip_settings_compact_mode', true, 5 );
	}

	return sanitize_text_field( wp_unslash( $compact_mode ) );
}
