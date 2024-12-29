<?php
/**
 * [Short Description]
 *
 * @package    DEVRY\FIP
 * @copyright  Copyright (c) 2024, Developry Ltd.
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU Public License
 * @since      1.4
 */

namespace DEVRY\FIP;

! defined( ABSPATH ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'Featured_Image_Plus' ) ) {

	class Featured_Image_Plus {
		/**
		 * Add featured image types supported.
		 */
		public $types_supported;

		/**
		 * Add theme support.
		 */
		public $theme_support;

		/**
		 * Unsplash API access key.
		 */
		public $unsplash_api_access_key;

		/**
		 * Add compact mode.
		 */
		public $compact_mode;

		/**
		 * Consturtor.
		 */
		public function __construct() {
			// Use some defaults for the Options, for initial plugin usage.
			$this->types_supported         = array( 'post', 'page' );
			$this->theme_support           = ''; // No
			$this->unsplash_api_access_key = '';
			$this->compact_mode            = ''; // No

			// Retrieve from options, if available; otherwise, use the default values.
			$this->types_supported         = get_option( 'fip_types_supported', $this->types_supported );
			$this->theme_support           = get_option( 'fip_theme_support', $this->theme_support );
			$this->unsplash_api_access_key = get_option( 'fip_unsplash_api_access_key', $this->unsplash_api_access_key );
			$this->compact_mode            = get_option( 'fip_compact_mode', $this->compact_mode );
		}

		/**
		 * Initializor.
		 */
		public function init() {
			add_action( 'wp_loaded', array( $this, 'on_loaded' ) );
		}

		/**
		 * Plugin loaded.
		 */
		public function on_loaded() {
			add_action( 'admin_init', array( $this, 'add_theme_support' ) );
			add_action( 'admin_init', array( $this, 'manage_columns' ) );
			add_action( 'quick_edit_custom_box', array( $this, 'quick_edit' ), 10, 2 );
			add_action( 'bulk_edit_custom_box', array( $this, 'bulk_edit' ), 10, 2 );
			add_action( 'admin_enqueue_scripts', array( $this, 'no_conflict' ), 11 );
		}

		/**
		 * Add featured image support if Option is yes and theme doesn't have it enabled.
		 */
		public function add_theme_support() {
			if ( function_exists( 'add_theme_support' ) ) {
				if ( 'yes' === $this->theme_support && ! current_theme_supports( 'post-thumbnails' ) ) {
					add_theme_support( 'post-thumbnails' );
				}
			}
		}

		/**
		 * Call actions based on the selected supported types.
		 */
		public function manage_columns() {
			if ( empty( $this->types_supported ) ) {
				return;
			}

			foreach ( $this->types_supported as $type_supported ) {
				// Posts & Pages.
				$columns       = $type_supported . 's';
				$custom_column = $type_supported . 's';

				// CPTs.
				if ( ! in_array( $type_supported, array( 'post', 'page', 'product' ), true ) ) {
					$columns       = $type_supported . '_posts';
					$custom_column = $type_supported . '_posts';
				}

				/**
				 * Add featured images head and body columns with content.
				 */
				add_action(
					'manage_' . $columns . '_columns',
					function( $columns ) use ( $type_supported ) {
						return $this->manage_post_type_columns( $columns, $type_supported );
					}
				);

				add_action(
					'manage_' . $custom_column . '_custom_column',
					function( $column_name, $post_id ) use ( $type_supported ) {
						return $this->add_custom_column_content( $column_name, $post_id, $type_supported );
					},
					10,
					2
				);

				/**
				 * Add sort and filter for the featured images.
				 */
				add_action(
					'current_screen',
					function() {
						$screen = get_current_screen();

						// Sorting.
						add_action( 'manage_' . $screen->id . '_sortable_columns', array( $this, 'manage_sortable_columns' ) );
						add_action( 'pre_get_posts', array( $this, 'sortable_columns_query' ) );

						// Filtering.
						add_action( 'restrict_manage_posts', array( $this, 'add_featured_image_filter' ) );
						add_filter( 'parse_query', array( $this, 'filter_featured_image_query' ) );
					}
				);
			}
		}

		/**
		 * Display the table head for the featured images.
		 */
		public function manage_post_type_columns( $post_columns, $type_supported ) {
			$screen = get_current_screen();

			// Limit this only for default Posts and if not selected to add other CPTs.
			if ( $type_supported === $screen->post_type ) {
				unset( $post_columns['cb'] );
				unset( $post_columns['thumb'] );

				$new_columns['cb']  = '<input type="checkbox" />';
				$new_columns['fip'] = 'Featured Image';
				$post_columns       = array_merge( $new_columns, $post_columns );
			}

			return $post_columns;
		}

		/**
		 * Display the content for the featured images.
		 */
		public function add_custom_column_content( $column_name, $post_id, $type_supported ) {
			$post_type = get_post_type( $post_id );

			if ( $post_type === $type_supported ) {
				switch ( $column_name ) {
					case 'fip':
						$featured_image = '';

						if ( has_post_thumbnail( $post_id ) ) {
							$featured_image = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
						}

						if ( $featured_image ) {
							?>
							<div class="fip-thumb-cont">
								<img src="<?php echo esc_url( $featured_image ); ?>" alt="" class="fip-thumb" />
								<input type="hidden" name="fip-id" value="<?php echo esc_attr( get_post_thumbnail_id( $post_id ) ); ?>" />
							</div>
							<?php

						} else {
							?>
							<div class="fip-thumb-cont">
								<img src="<?php echo esc_url( FIP_PLUGIN_IMG_URL ); ?>no-image-placeholder.png" alt="" class="fip-thumb" />
							</div>
							<?php
						}
						break;
					default:
						break;
				}
			}
		}

		/**
		 * Add sortable columns.
		 */
		public function manage_sortable_columns( $columns ) {
			$columns['fip'] = 'fip';

			return $columns;
		}

		/**
		 * Run a custom Query to sort the added columns values.
		 */
		public function sortable_columns_query( $query ) {
			if ( ! is_admin() ) {
				return;
			}

			if ( ! empty( $_REQUEST['post_type'] )
				&& in_array( $_REQUEST['post_type'], $this->types_supported, true ) ) {
				$orderby = '';

				if ( ! empty( $query->get( 'orderby' ) ) ) {
					$orderby = $query->get( 'orderby' );
				}

				switch ( $orderby ) {
					case 'fip':
						$query->set( 'meta_key', '_thumbnail_id' ); // Sort by featured image ID.
						$query->set(
							'orderby',
							array(
								'meta_value' => 'DESC',
								'ID'         => 'DESC',
							)
						);

						add_filter( 'get_meta_sql', array( $this, 'filter_get_meta_sql' ) );
						break;
					default:
						break;
				}
			}
		}

		/**
		 * Modify the SQL to show posts without _thumbnail_id when we do orderby.
		 * By default the query will exclude all posts that don't have the specified meta key.
		 */
		public function filter_get_meta_sql( $clauses ) {
			remove_filter( 'get_meta_sql', 'filter_get_meta_sql' );

			// Change the inner join to a left join,
			// and change the where so it is applied to the join, not the results of the query.
			$clauses['join']  = str_replace( 'INNER JOIN', 'LEFT JOIN', $clauses['join'] ) . $clauses['where'];
			$clauses['where'] = '';

			return $clauses;
		}

		/**
		 * Add custom filter for the featured image for the `fip`.
		 */
		public function add_featured_image_filter( $post_type ) {
			$filter_selected = isset( $_REQUEST['fip_filter'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['fip_filter'] ) ) : '';

			if ( ! is_admin() ) {
				return;
			}

			if ( ! in_array( $post_type, $this->types_supported, true ) ) {
				return;
			}

			echo sprintf(
				'<select id="fip-filter" name="fip_filter" class="postform">
					<option value="">All Featured Images</option>
					<option value="with-featured-image" %1$s>With Featured Image</option>
					<option value="without-featured-image" %2$s>Without Featured Image</option>
				</select>',
				( 'with-featured-image' === $filter_selected ) ? 'selected' : '',
				( 'without-featured-image' === $filter_selected ) ? 'selected' : '',
			);
		}

		/**
		 * Run a custom Query to filter out the results based on the `fip`.
		 */
		public function filter_featured_image_query( $query ) {
			global $pagenow;

			if ( ! ( is_admin() && $query->is_main_query() ) ) {
				return $query;
			}

			if ( empty( $_REQUEST['fip_filter'] ) ) {
				return $query;
			}

			if ( 'edit.php' !== $pagenow ) {
				return $query;
			}

			// Check if the there is ID set for the featured image or NOT.
			if ( 'with-featured-image' === $_REQUEST['fip_filter'] ) {
				$query->query_vars['meta_query'][] = array(
					'key'     => '_thumbnail_id',
					'compare' => 'EXISTS',
				);
			} else {
				$query->query_vars['meta_query'][] = array(
					'key'     => '_thumbnail_id',
					'compare' => 'NOT EXISTS',
				);
			}
		}

		/**
		 * Use quick edit to manage featured images.
		 */
		public function quick_edit( $column_name, $post_type ) {
			if ( 'fip' === $column_name ) {
				$this->display_quick_edit_view( $post_type );
			}
		}

		/**
		 * Use bulk edit to manage featured images.
		 */
		public function bulk_edit( $column_name, $post_type ) {
			if ( 'fip' === $column_name ) {
				$this->display_quick_edit_view( $post_type, 'bulk' );
			}
		}

		/**
		 * Custom block used to manage featured images in the Quick/Bulk edit mode.
		 */
		public function display_quick_edit_view( $post_type, $type = 'quick' ) {
			require FIP_PLUGIN_DIR_PATH . 'inc/admin/views/featured-image-inline.php'; // Cannot use require_once.
		}

		/**
		 * There were some JS conflicts with 3rd party plugins that use jQuery UI Button.
		 */
		public function no_conflict( $hook_suffix ) {
			if ( 'edit.php' === $hook_suffix && ! empty( $_GET['post_type'] ) ) {
				wp_deregister_script( 'jquery-ui-button' );
			}
		}

		/**
		 * Keep track of the Unsplash/OpenAI API requests made within the hour.
		 *
		 * $num_requests is used for OpenAI only, since we can have 1-3 images
		 * generated per API request selected from the Options we need to multiply the count
		 */
		public function store_transient_api_requests( $option_name, $transient_key, $num_requests = 1 ) {
			if ( false === get_option( $option_name ) ) {
				add_option( $option_name, 1 );
				set_transient( $transient_key, 1, 60 * 60 );
			}

			if ( false === get_transient( $transient_key ) ) {
				delete_option( $option_name );
			} else {
				update_option( $option_name, get_option( $option_name ) + ( 1 * (int) $num_requests ) );
			}
		}

		/**
		 * Get post keywords based on the Title, Tags, Categories and Content text.
		 */
		public function get_post_keywords( $post_id, $query_source ) {
			$sources = array_filter( explode( ',', $query_source ) );
			$text    = '';

			foreach ( $sources as $source ) {
				switch ( $source ) {
					case 'title':
						$text .= wp_strip_all_tags( get_post_field( 'post_title', $post_id ) );
						break;
					case 'categories':
						$categories = wp_get_post_categories( $post_id, array( 'fields' => 'all' ) );

						if ( ! empty( $categories ) ) {
							foreach ( $categories as $category ) {
								$text .= ',' . $category->name;
							}
						}
						break;
					case 'tags':
						$tags = wp_get_post_tags( $post_id );

						if ( ! empty( $tags ) ) {
							foreach ( $tags as $tag ) {
								$text .= ',' . $tag->name;
							}
						}
						break;
					case 'content':
						$text .= ',' . wp_strip_all_tags( get_post_field( 'post_content', $post_id ) );
						break;
				}
			}

			$keywords = $this->remove_stop_words( $text, file( FIP_PLUGIN_DIR_PATH . '/stopwords.txt' ) );

			return $keywords;
		}

		/**
		 * Remove words from the text that are located in stopwords.txt
		 */
		public function remove_stop_words( $text, $stopwords ) {
			$keywords = array();

			// Remove line breaks and spaces from stopwords
			$stopwords = array_map(
				function( $x ) {
					return trim( strtolower( $x ) );
				},
				$stopwords
			);

			// Replace all non-word chars with comma
			$pattern = '/[0-9\W]/';
			$text    = preg_replace( $pattern, ',', $text );

			// Create an array from $text
			$text_array = explode( ',', $text );

			// remove whitespace and lowercase words in $text
			$text_array = array_map(
				function( $x ) {
					return trim( strtolower( $x ) );
				},
				$text_array
			);

			foreach ( $text_array as $term ) {
				if ( ! in_array( $term, $stopwords, true ) ) {
					$keywords[] = trim( $term );
				}
			};

			// Remove duplicates and empty and re-index.
			return array_values( array_unique( array_filter( $keywords ) ) );
		}
	}

	$fip = new Featured_Image_Plus();
	$fip->init();
}
