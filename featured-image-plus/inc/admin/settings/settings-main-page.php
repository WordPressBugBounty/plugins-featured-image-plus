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

$fip_admin = new FIP_Admin();

$has_user_cap = $fip_admin->check_user_cap();

?>
<div class="fip-admin">
	<div class="fip-pro">
		<h4>
			<?php echo esc_html__( 'Get the PRO version today!', 'featured-image-plus' ); ?>
		</h4>
		<p>
			<?php echo esc_html__( 'The PRO version will unlock more features, better performance, priority support, and integration with OpenAI and Unsplash APIs.', 'featured-image-plus' ); ?>
		</p>
		<table>
			<tr>
				<th><?php echo esc_html__( 'Feature', 'featured-image-plus' ); ?></th>
				<th><?php echo esc_html__( 'Free', 'featured-image-plus' ); ?></th>
				<th><?php echo esc_html__( 'PRO', 'featured-image-plus' ); ?></th>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'AI Image Creator', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'no', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'Auto-generate images from Unsplash or OpenAI', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'no', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'Select support for registered CPTs', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'Posts, Pages & CPTs', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'all', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'Manage featured image visibility, position, size, etc.', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'no', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'Add theme support for featured images', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'no', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'Add support to all public taxonomies', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'no', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'Auto-remove and attach featured images from post content; works with Classic, Block, and Elementor editors	', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'no', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'WooCommerce and other 3rd-partry plugin support', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'Unsplash API itegration', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'limited', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'extended', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'OpenAI/DALL-E 2 API integration', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'no', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'Sort and filter by featured images	', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'Priority email support', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'no', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'Regular plugin updates', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'delayed', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'first release', 'featured-image-plus' ); ?></td>
			</tr>
		</table>
		<p class="button-group">
			<a
				class="button button-primary button-pro"
				href="https://bit.ly/43el4Il"
				target="_blank"
			>
				<?php echo esc_html__( 'GET PRO VERSION', 'featured-image-plus' ); ?>
			</a>
			<a
				class="button button-primary button-watch-video"
				href="https://www.youtube.com/watch?v=r3czQg7Xqec "
				target="_blank"
			>
				<?php echo esc_html__( 'Watch Video', 'featured-image-plus' ); ?>
			</a>
		</p>
	</div>
	<h2>
		<?php echo esc_html__( 'Featured Image Plus', 'featured-image-plus' ); ?>
	</h2>
	<p>
		<?php
		printf(
			wp_kses(
				__( 'Optimize your WordPress workflow with Featured Image Plus, managing featured images easily with bulk edit and Unsplash integration.', 'featured-image-plus' ),
				json_decode( FIP_PLUGIN_ALLOWED_HTML_ARR, true )
			),
		);
		?>
	</p>

	<hr />
	<form method="post" action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>">
		<div class="fip-loading-bar"></div>
		<div id="fip-output" class="notice is-dismissible fip-output"></div>
		<?php settings_errors( 'fip_settings_errors' ); ?>
		<?php wp_nonce_field( 'fip_settings_nonce', 'fip_wpnonce' ); ?>
		<?php
			settings_fields( FIP_SETTINGS_SLUG );
			do_settings_sections( FIP_SETTINGS_SLUG );
		?>
		<div class="submit button-group">
			<p class="submit button-group">
				<button
					type="submit"
					class="button button-primary"
					id="submit-button"
					name="submit-button"
				>
					<?php echo esc_html__( 'Save', 'featured-image-plus' ); ?>
				</button>
				<button
					type="button"
					class="button"
					id="fip-reset-button"
					name="fip-reset-button"
				>
					<?php echo esc_html__( 'Reset', 'featured-image-plus' ); ?>
				</button>
			</p>
		</div>
	</form>
	<br clear="all" />
	<hr />
	<div class="fip-support-credits">
		<p>
			<?php
			printf(
				wp_kses(
					/* translators: %1$s is replaced with "Support Forum" */
					__( 'If something isn\'t clear, please open a ticket on the official plugin %1$s. We aim to address all tickets within a few working days.', 'featured-image-plus' ),
					json_decode( FIP_PLUGIN_ALLOWED_HTML_ARR, true )
				),
				'<a href="' . esc_url( FIP_PLUGIN_WPORG_SUPPORT ) . '" target="_blank">' . esc_html__( 'Support Forum', 'featured-image-plus' ) . '</a>'
			);
			?>
		</p>
		<p>
			<strong><?php echo esc_html__( 'Please rate us', 'featured-image-plus' ); ?></strong>
			<a href="<?php echo esc_url( FIP_PLUGIN_WPORG_RATE ); ?>" target="_blank">
				<img src="<?php echo esc_url( FIP_PLUGIN_DIR_URL ); ?>assets/dist/img/rate.png" alt="Rate us @ WordPress.org" />
			</a>
		</p>
		<p>
			<strong><?php echo esc_html__( 'Having issues?', 'featured-image-plus' ); ?></strong> 
			<a href="<?php echo esc_url( FIP_PLUGIN_WPORG_SUPPORT ); ?>" target="_blank">
				<?php echo esc_html__( 'Create a Support Ticket', 'featured-image-plus' ); ?>
			</a>
		</p>
		<p>
			<strong><?php echo esc_html__( 'Developed by', 'featured-image-plus' ); ?></strong>
			<a href="https://krasenslavov.com/" target="_blank">
				<?php echo esc_html__( 'Krasen Slavov @ Developry', 'featured-image-plus' ); ?>
			</a>
		</p>
	</div>
	<hr />
	<p>
		<small>
			<?php
			printf(
				wp_kses(
					/* translators: %1$s is replaced with "help and support me on Patreon" */
					__( '* For the price of a cup of coffee per month, you can %1$s for the development and maintenance of all my free WordPress plugins. Every contribution helps and is deeply appreciated!', 'featured-image-plus' ),
					json_decode( FIP_PLUGIN_ALLOWED_HTML_ARR, true )
				),
				'<a href="https://patreon.com/krasenslavov" target="_blank">' . esc_html__( 'help and support me on Patreon', 'featured-image-plus' ) . '</a>'
			);
			?>
		</small>
	</p>
</div>
