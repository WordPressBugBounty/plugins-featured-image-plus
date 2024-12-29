<?php
/**
 * [Short description]
 *
 * @package    DEVRY\FIP
 * @copyright  Copyright (c) 2024, Developry Ltd.
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU Public License
 * @since      1.5
 */

namespace DEVRY\FIP;

! defined( ABSPATH ) || exit; // Exit if accessed directly.

function fip_add_classic_editor_controls( $content, $post_id, $thumbnail_id ) {
	$fip = new Featured_Image_Plus();

	if ( empty( $fip->unsplash_api_access_key ) ) {
		return $content;
	}

	ob_start();
	?>
		<div class="fip">
			<div class="fip-editor-container">
				<div class="fip-loading-bar"></div>
				<div class="fip-output"></div>

				<p>
					<?php echo esc_html__( 'Generate and attach a custom featured image using the Unsplash API.', 'featured-image-plus' ); ?>
				</p>

				<input type="hidden" name="fip-current-post-id" value="<?php echo $post_id; ?>" />

				<p>
					<select name="fip-editor-image-source" class="fip-custom-select">
						<option value=""><?php echo esc_html__( '-- Select Source --', 'featured-image-plus' ); ?></option>
						<option value="unsplash"><?php echo esc_html__( 'Unsplash', 'featured-image-plus' ); ?></option>
					</select>
				</p>

				<p>
					<select name="fip-editor-image-keyword" class="fip-custom-select" disabled>
						<option value=""><?php echo esc_html__( '-- Select Keyword --', 'featured-image-plus' ); ?></option>
					</select>
				</p>

				<p>
					<select name="fip-editor-image-option" class="fip-custom-select" disabled >
						<option value=""><?php echo esc_html__( '-- Select Image --', 'featured-image-plus' ); ?></option>
					</select>
				</p>

				<div class="fip-thumb-preview"></div>

				<button type="submit" name="fip-editor-image-attach" class="button button-primary button-large" disabled>
					<?php echo esc_html__( 'Attach featured image', 'featured-image-plus' ); ?>
				</button>
			</div>
		</div>
	<?php
	$content .= ob_get_clean();

	return $content;
}

add_filter( 'admin_post_thumbnail_html', __NAMESPACE__ . '\fip_add_classic_editor_controls', 10, 3 );
