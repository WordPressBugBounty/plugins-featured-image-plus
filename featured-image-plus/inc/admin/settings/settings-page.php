<?php
/**
 * [Short description]
 *
 * @package    DEVRY\FIP
 * @copyright  Copyright (c) 2025, Developry Ltd.
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU Public License
 * @since      1.3
 */

namespace DEVRY\FIP;

! defined( ABSPATH ) || exit; // Exit if accessed directly.

/**
 * Display the featured image plus page layout.
 */
function fip_display_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have sufficient permissions to access this page.' );
	}

	add_settings_section(
		FIP_SETTINGS_SLUG,
		'Settings',
		'',
		FIP_SETTINGS_SLUG
	);

	// Add setting field for types supported.
	add_settings_field(
		'fip_types_supported',
		'<label for="fip-types-supported">'
			. esc_html__( 'Types Supported', 'featured-image-plus' )
			. '</label>',
		__NAMESPACE__ . '\fip_display_types_supported',
		FIP_SETTINGS_SLUG,
		FIP_SETTINGS_SLUG,
	);

	// Add setting field for theme support.
	add_settings_field(
		'fip_theme_support',
		'<label for="fip-theme-support">'
			. esc_html__( 'Theme Support', 'featured-image-plus' )
			. '</label>',
		__NAMESPACE__ . '\fip_display_theme_support',
		FIP_SETTINGS_SLUG,
		FIP_SETTINGS_SLUG,
	);

	// Add setting field for Unsplash API access key.
	add_settings_field(
		'fip_unsplash_api_access_key',
		'<label for="fip-unsplash-api-access-key">'
			. esc_html__( 'Unsplash access key', 'featured-image-plus' )
			. '</label>',
		__NAMESPACE__ . '\fip_display_unsplash_api_access_key',
		FIP_SETTINGS_SLUG,
		FIP_SETTINGS_SLUG,
	);

	// Add setting field for compact mode.
	add_settings_field(
		'fip_compact_mode',
		'<label for="fip-compact-mode">'
			. esc_html__( 'Compact Mode', 'featured-image-plus' )
			. '</label>',
		__NAMESPACE__ . '\fip_display_compact_mode',
		FIP_SETTINGS_SLUG,
		FIP_SETTINGS_SLUG,
	);

	require_once FIP_PLUGIN_DIR_PATH . 'inc/admin/settings/settings-main-page.php';
}
