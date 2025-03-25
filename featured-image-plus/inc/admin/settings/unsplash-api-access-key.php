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
function fip_display_unsplash_api_access_key() {
	$fip = new Featured_Image_Plus();

	$unsplash_api_access_key = get_option( 'fip_unsplash_api_access_key', $fip->unsplash_api_access_key );

	printf(
		'<input
			type="text"
			class="regular-text"
			id="fip-unsplash-api-access-key"
			name="fip_unsplash_api_access_key"
			value="%1$s"
			placeholder="Enter your Unsplash API access key..."
		/>',
		esc_attr( $unsplash_api_access_key )
	);
	?>
	<div class="description">
		<small>
			<?php echo esc_html__( 'Use the public Unsplash API with an access key.', 'featured-image-plus' ); ?>
		</small>
		<em>
			<?php echo esc_html__( 'You have a limit of 50 or 5,000 API requests per hour, depending on your app status.', 'featured-image-plus' ); ?>
		</em>
	</div>
	<?php
}

/**
 * Sanitize and update the setting.
 */
function fip_sanitize_unsplash_api_access_key( $unsplash_api_access_key ) {
	// Verify the nonce.
	$_wpnonce = ( isset( $_REQUEST['fip_wpnonce'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['fip_wpnonce'] ) ) : '';

	if ( empty( $_wpnonce ) || ! wp_verify_nonce( $_wpnonce, 'fip_settings_nonce' ) ) {
		return;
	}

	// Nothing selected.
	if ( empty( $unsplash_api_access_key ) ) {
		return;
	}

	// Option changed and updated.
	if ( ! get_transient( 'fip_settings_unsplash_api_access_key' )
		&& get_option( 'fip_unsplash_api_access_key', '' ) !== $unsplash_api_access_key ) {
		add_settings_error(
			'fip_settings_errors',
			'fip_settings_unsplash_api_access_key',
			esc_html__( 'Unspalsh access key option was updated successfully.', 'featured-image-plus' ),
			'updated'
		);

		// Add transient to avoid double notice on initial Save when using settings_errors().
		set_transient( 'fip_settings_unsplash_api_access_key', true, 5 );
	}

	return sanitize_text_field( wp_unslash( $unsplash_api_access_key ) );
}
