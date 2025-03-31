<?php
/**
 * [Short description]
 *
 * @package    DEVRY\FIP
 * @copyright  Copyright (c) 2025, Developry Ltd.
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU Public License
 * @since      1.4
 */

namespace DEVRY\FIP;

! defined( ABSPATH ) || exit; // Exit if accessed directly

/**
 * Don't allow to have both Free and Pro active at the same time.
 */
function fip_check_pro_plugin() {
	// Deactitve the Pro version if active.
	if ( is_plugin_active( 'featured-image-plus-pro/featured-image-plus.php' ) ) {
		deactivate_plugins( 'featured-image-plus-pro/featured-image-plus.php', true );
	}
}

register_activation_hook( FIP_PLUGIN_BASENAME, __NAMESPACE__ . '\fip_check_pro_plugin' );

/**
 * Display a promotion for the pro plugin.
 */
function fip_display_upgrade_notice() {
	$fip_admin = new FIP_Admin();

	if ( get_option( 'fip_upgrade_notice' ) && get_transient( 'fip_upgrade_plugin' ) ) {
		return;
	}
	?>
		<div class="notice notice-success is-dismissible fip-admin">
			<!-- <p class="fip-upgrade-notice-discount"> -->
				<?php
				// printf(
				// 	wp_kses(
				// 		/* translators: %1$s is replaced with "FIP10" */
				// 		/* translators: %2$s is replaced with "10% off" */
				// 		__( 'Use the %1$s promo code and get %2$s your purchase!', 'featured-image-plus' ),
				// 		json_decode( FIP_PLUGIN_ALLOWED_HTML_ARR, true )
				// 	),
				// 	'<code>' . esc_html__( 'FIP10', 'featured-image-plus' ) . '</code>',
				// 	'<strong>' . esc_html__( '10% off', 'featured-image-plus' ) . '</strong>'
				// );
				?>
			<!-- </p> -->
			<h3><?php echo esc_html__( 'Featured Image Plus PRO 🚀', 'featured-image-plus' ); ?></h3>
			<p>
				<?php
				printf(
					wp_kses(
						/* translators: %1$s is replaced with "Found the free version helpful" */
						/* translators: %2$s is replaced with "Featured Image Plus Pro" */
						__( '✨🎉📢 %1$s? Discover the added benefits of upgrading to %2$s?', 'featured-image-plus' ),
						json_decode( FIP_PLUGIN_ALLOWED_HTML_ARR, true )
					),
					'<strong>' . esc_html__( 'Found the free version helpful', 'featured-image-plus' ) . '</strong>',
					'<strong>' . esc_html__( 'Featured Image Plus Pro', 'featured-image-plus' ) . '</strong>'
				);
				?>
			</p>
			<div class="button-group">
				<a href="https://bit.ly/43jkDMW" target="_blank" class="button button-primary button-success">
					<?php echo esc_html__( 'Go Pro', 'featured-image-plus' ); ?>
					<i class="dashicons dashicons-external"></i>
				</a>
				<a href="<?php echo esc_url( admin_url( $fip_admin->admin_page . FIP_SETTINGS_SLUG . '&_wpnonce=' . wp_create_nonce( 'fip_upgrade_notice_nonce' ) . '&action=fip_dismiss_upgrade_notice' ) ); ?>" class="button">
					<?php echo esc_html__( 'I already did', 'featured-image-plus' ); ?>
				</a>
				<a href="<?php echo esc_url( admin_url( $fip_admin->admin_page . FIP_SETTINGS_SLUG . '&_wpnonce=' . wp_create_nonce( 'fip_upgrade_notice_nonce' ) . '&action=fip_dismiss_upgrade_notice' ) ); ?>" class="button">
					<?php echo esc_html__( "Don't show this notice again!", 'featured-image-plus' ); ?>
				</a>
			</div>
		</div>
	<?php
	delete_option( 'fip_upgrade_notice' );

	// Set the transient to last for 30 days.
	set_transient( 'fip_upgrade_plugin', true, 30 * DAY_IN_SECONDS );
}

add_action( 'admin_notices', __NAMESPACE__ . '\fip_display_upgrade_notice' );
