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
 * Display a notice encouraging users to rate the plugin
 * on WordPress.org and provide options to dismiss the notice.
 */
function fip_display_rating_notice() {
	$fip_admin = new FIP_Admin();

	if ( ! get_option( 'fip_rating_notice', '' ) ) {
		?>
			<div class="notice notice-info is-dismissible fip-admin">
				<h3><?php echo esc_html( FIP_PLUGIN_NAME ); ?></h3>
				<p>
					<?php
					printf(
						wp_kses(
							/* translators: %1$s is replaced with "by giving it 5 stars rating" */
							__( '✨💪🔌 Could you kindly support the plugin %1$s? Thank you in advance!', 'featured-image-plus' ),
							json_decode( FIP_PLUGIN_ALLOWED_HTML_ARR, true )
						),
						'<strong>' . esc_html__( 'by giving it 5 stars rating', 'featured-image-plus' ) . '</strong>'
					);
					?>
				</p>
				<div class="button-group">
					<a href="<?php echo esc_url( FIP_PLUGIN_WPORG_RATE ); ?>" target="_blank" class="button button-primary">
						<?php echo esc_html__( 'Rate us @ WordPress.org', 'featured-image-plus' ); ?>
						<i class="dashicons dashicons-external"></i>
					</a>
					<a href="<?php echo esc_url( admin_url( $fip_admin->admin_page . FIP_SETTINGS_SLUG . '&_wpnonce=' . wp_create_nonce( 'fip_rating_notice_nonce' ) . '&action=fip_dismiss_rating_notice' ) ); ?>" class="button">
						<?php echo esc_html__( 'I already did', 'featured-image-plus' ); ?>
					</a>
					<a href="<?php echo esc_url( admin_url( $fip_admin->admin_page . FIP_SETTINGS_SLUG . '&_wpnonce=' . wp_create_nonce( 'fip_rating_notice_nonce' ) . '&action=fip_dismiss_rating_notice' ) ); ?>" class="button">
						<?php echo esc_html__( "Don't show this notice again!", 'featured-image-plus' ); ?>
					</a>
				</div>
			</div>
		<?php
	}
}
