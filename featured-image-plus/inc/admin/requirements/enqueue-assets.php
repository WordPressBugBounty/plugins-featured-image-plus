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
 * Enqueue admin assets below.
 */
function fip_enqueue_admin_assets() {
	if ( ! is_admin() ) {
		return;
	}

	// Load assets only for page page staring with prefix fip-.
	$screen = get_current_screen();
	// if ( strpos( $screen->id, 'fip_' ) ) {}

	$fip = new Featured_Image_Plus();

	wp_enqueue_style(
		'fip-classic-editor',
		FIP_PLUGIN_DIR_URL . 'assets/dist/css/fip-classic-editor.min.css',
		array(),
		FIP_PLUGIN_VERSION,
		'all'
	);

	wp_enqueue_script(
		'fip-classic-editor',
		FIP_PLUGIN_DIR_URL . 'assets/dist/js/fip-classic-editor.min.js',
		array(),
		FIP_PLUGIN_VERSION,
		true
	);

	wp_localize_script(
		'fip-classic-editor',
		'fip',
		array(
			'plugin_url'    => FIP_PLUGIN_DIR_URL,
			'plugin_domain' => FIP_PLUGIN_DOMAIN,
			'ajax_url'      => esc_url( admin_url( 'admin-ajax.php' ) ),
			'ajax_nonce'    => wp_create_nonce( 'fip_ajax_nonce' ),
		)
	);

	// Load the following assets for all the supported types.
	if ( ! empty( $fip->types_supported ) && in_array( $screen->post_type, $fip->types_supported, true ) ) {
		wp_enqueue_media();
		wp_enqueue_script( 'media-grid' );
		wp_enqueue_script( 'media' );
	}

	wp_enqueue_style(
		'fip-admin',
		FIP_PLUGIN_DIR_URL . 'assets/dist/css/fip-admin.min.css',
		array(),
		FIP_PLUGIN_VERSION,
		'all'
	);

	wp_enqueue_script(
		'fip-admin',
		FIP_PLUGIN_DIR_URL . 'assets/dist/js/fip-admin.min.js',
		array( 'jquery' ),
		FIP_PLUGIN_VERSION,
		true
	);

	wp_localize_script(
		'fip-admin',
		'fip',
		array(
			'plugin_url' => FIP_PLUGIN_DIR_URL,
			'ajax_url'   => esc_url( admin_url( 'admin-ajax.php' ) ),
			'ajax_nonce' => wp_create_nonce( 'fip_ajax_nonce' ),
		)
	);
}

/**
 * Enqueue block editor (styles and scripts) for the plugin.
 */
function fip_enqueue_block_editor_assets() {
	$fip = new Featured_Image_Plus();

	if ( empty( $fip->unsplash_api_access_key ) && empty( $fip->openai_api_access_key ) ) {
		return;
	}

	wp_enqueue_style(
		'fip-block-editor',
		FIP_PLUGIN_DIR_URL . 'assets/dist/css/fip-block-editor.min.css',
		array(),
		FIP_PLUGIN_VERSION,
		'all'
	);

	wp_enqueue_script(
		'fip-block-editor',
		FIP_PLUGIN_DIR_URL . 'assets/dist/js/fip-block-editor.min.js',
		array( 'wp-element', 'wp-hooks', 'wp-data' ),
		FIP_PLUGIN_VERSION,
		true
	);

	wp_localize_script(
		'fip-block-editor',
		'fip',
		array(
			'plugin_url'    => FIP_PLUGIN_DIR_URL,
			'plugin_domain' => FIP_PLUGIN_DOMAIN,
			'ajax_url'      => esc_url( admin_url( 'admin-ajax.php' ) ),
			'ajax_nonce'    => wp_create_nonce( 'fip_ajax_nonce' ),
		)
	);
}

add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\fip_enqueue_block_editor_assets' );
