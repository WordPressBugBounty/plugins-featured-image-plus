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
 * [AJAX] Check if image source is valid and has API key added.
 */
function fip_check_image_source() {
	$fip       = new Featured_Image_Plus();
	$fip_admin = new FIP_Admin();

	$image_source = isset( $_REQUEST['image_source'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['image_source'] ) ) : '';

	if ( empty( $image_source ) ) {
		$fip_admin->print_json_message(
			0,
			__( 'You must select a source from the list.', 'featured-image-plus' )
		);
	}

	if ( 'unsplash' === $image_source && '' === $fip->unsplash_api_access_key ) {
		$fip_admin->print_json_message(
			0,
			__( 'Empty or invalid Unsplash API access key! Go to the plugin <a href="/wp-admin/admin.php?page=fip_settings" target="_blank">Options</a> page.', 'featured-image-plus' )
		);
	}

	$fip_admin->print_json_message( 1, '' );
}

add_action( 'wp_ajax_fip_check_image_source', __NAMESPACE__ . '\fip_check_image_source' );

/**
 * [AJAX] Generate the current post keywords.
 */
function fip_generate_post_keywords() {
	$fip       = new Featured_Image_Plus();
	$fip_admin = new FIP_Admin();

	$output_html = '';
	$post_id     = isset( $_REQUEST['post_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['post_id'] ) ) : '';

	if ( empty( $post_id ) ) {
		$fip_admin->print_json_message(
			1,
			__( 'Invalid post ID or post not found.', 'featured-image-plus' )
		);
	}

	$post_keywords = $fip->get_post_keywords( $post_id, 'title, categorie, tags' );

	foreach ( $post_keywords as $keyword ) {
		$output_html .= "<option value='{$keyword}'>{$keyword}</option>";
	}

	echo wp_json_encode(
		array(
			array(
				'status'  => 1,
				'message' => $output_html,
			),
		)
	);

	exit;
}

add_action( 'wp_ajax_fip_generate_post_keywords', __NAMESPACE__ . '\fip_generate_post_keywords' );

/**
 * [AJAX] Generate the current post keywords.
 */
function fip_get_image_options() {
	$fip       = new Featured_Image_Plus();
	$fip_admin = new FIP_Admin();

	$output_html = '';
	$option_cnt  = 0;

	$post_id      = isset( $_REQUEST['post_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['post_id'] ) ) : '';
	$image_source = isset( $_REQUEST['image_source'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['image_source'] ) ) : '';
	$keyword      = isset( $_REQUEST['keyword'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['keyword'] ) ) : '';

	if ( empty( $post_id ) ) {
		$fip_admin->print_json_message(
			0,
			__( 'Invalid post ID or post not found.', 'featured-image-plus' )
		);
	}

	if ( empty( $image_source ) ) {
		$fip_admin->print_json_message(
			0,
			__( 'You must select a source from the list.', 'featured-image-plus' )
		);
	}

	if ( empty( $keyword ) ) {
		$fip_admin->print_json_message(
			0,
			__( 'You must select a keyword from the list.', 'featured-image-plus' )
		);
	}

	if ( 'unsplash' === $image_source ) {
		$response = '';

		$option_name   = 'fip_unsplash_api_requests';
		$transient_key = 'fip_unsplash_' . str_replace( ' ', '-', $keyword );

		$request_url = 'https://api.unsplash.com/search/photos';

		if ( $keyword ) {
			// Use transient request data or make an API request.
			if ( false === get_transient( $transient_key ) ) {
				// Get results only from the 1st page.
				$request_args = array(
					'body' => array(
						'client_id' => $fip->unsplash_api_access_key,
						'query'     => $keyword,
						'page'      => 1,
						'per_page'  => get_option( 'fip_unsplash_results_returned', 10 ),
					),
				);

				$response = wp_remote_get( $request_url, $request_args );

				$body     = wp_remote_retrieve_body( $response );
				$response = json_decode( $body );

				// Save the API request data made to Unsplash into a transient for 1hr.
				set_transient( $transient_key, $response, 60 * 60 );

				// Update the number of request made tothe Unsplash API the past 1hr.
				$fip->store_transient_api_requests( $option_name, $transient_key );
			} else {
				$response = get_transient( $transient_key );
			}

			if ( $response ) {
				if ( property_exists( $response, 'results' ) ) {
					foreach ( $response->results as $result ) {
						/**
						 * Get the `raw` sized image from Unsplash image options.
						 * Use base64_encode() becauase some chars are causing erros with wp_kses().
						 */
						if ( property_exists( $result, 'urls' ) ) {
							$base_width   = 1024; // Used for 1:1.
							$aspect_ratio = '1:1';

							list( $ratio_width, $ratio_height ) = explode( ':', $aspect_ratio );

							// Calculate the height based on the aspect ratio.
							$calculated_height = round( $base_width * ( $ratio_height / $ratio_width ) );

							// Construct the URL with the desired dimensions and fit mode.
							$image_urls[] = $result->urls->raw . "?w={$base_width}&h={$calculated_height}&fit=crop";
						}
					}
				}
			}
		}
	}

	foreach ( $image_urls as $image_url ) {
		$option_cnt++;
		$output_html .= "<option value='option-{$option_cnt}' data-image='{$image_url}'>Option {$option_cnt}</option>";
	}

	if ( empty( $output_html ) ) {
		$fip_admin->print_json_message(
			0,
			__( 'No images found with the selected keyword.', 'featured-image-plus' )
		);
	}

	echo wp_json_encode(
		array(
			array(
				'status'  => 1,
				'message' => $output_html,
			),
		)
	);

	exit;
}

add_action( 'wp_ajax_fip_get_image_options', __NAMESPACE__ . '\fip_get_image_options' );

/**
 * [AJAX] Save and attach post featured image.
 */
function fip_save_attach_featured() {
	$fip_admin = new FIP_Admin();

	$post_id      = isset( $_REQUEST['post_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['post_id'] ) ) : '';
	$image_source = isset( $_REQUEST['image_source'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['image_source'] ) ) : '';
	$image_option = isset( $_REQUEST['image_option'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['image_option'] ) ) : '';
	$keyword      = isset( $_REQUEST['keyword'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['keyword'] ) ) : '';

	if ( empty( $post_id ) ) {
		$fip_admin->print_json_message(
			0,
			__( 'Invalid post ID or post not found.', 'featured-image-plus' )
		);
	}

	if ( empty( $image_source ) ) {
		$fip_admin->print_json_message(
			0,
			__( 'You must select a source from the list.', 'featured-image-plus' )
		);
	}

	if ( empty( $image_option ) ) {
		$fip_admin->print_json_message(
			0,
			__( 'You must select an image option from the list.', 'featured-image-plus' )
		);
	}

	if ( empty( $keyword ) ) {
		$fip_admin->print_json_message(
			0,
			__( 'You must select a keyword from the list.', 'featured-image-plus' )
		);
	}

	// Attach featured image.
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	$image_url = esc_url_raw( $image_option . '.jpg' ); // Ensure the URL is sanitized assuming we get JPG file.

	// Download and create attachment in one step using media_sideload_image()
	$attachment_id = media_sideload_image(
		$image_url,
		$post_id,
		sanitize_text_field( ucwords( str_replace( '-', ' ', pathinfo( $image_url, PATHINFO_FILENAME ) ) ) ),
		'id'
	);

	if ( is_wp_error( $attachment_id ) ) {
		@unlink( $abs_filepath ); // Clean up downloaded file.
		$fip_admin->print_json_message(
			0,
			__( 'Featured image attachment failed.', 'featured-image-plus' )
		);
	}

	// Set as post thumbnail
	set_post_thumbnail( $post_id, $attachment_id );

	echo wp_json_encode(
		array(
			array(
				'status'        => 1,
				'message'       => __( 'Featured image attachment was successful.', 'featured-image-plus' ),
				'attachment_id' => $attachment_id,
			),
		)
	);

	exit;
}

add_action( 'wp_ajax_fip_save_attach_featured', __NAMESPACE__ . '\fip_save_attach_featured' );
