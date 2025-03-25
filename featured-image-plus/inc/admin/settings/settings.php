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

// The slug for plugin main page.
define( __NAMESPACE__ . '\FIP_SETTINGS_SLUG', 'fip_settings' );

require_once FIP_PLUGIN_DIR_PATH . 'inc/admin/settings/settings-menu.php';
require_once FIP_PLUGIN_DIR_PATH . 'inc/admin/settings/settings-page.php';
require_once FIP_PLUGIN_DIR_PATH . 'inc/admin/settings/settings-actions.php';
require_once FIP_PLUGIN_DIR_PATH . 'inc/admin/settings/settings-register.php';

require_once FIP_PLUGIN_DIR_PATH . 'inc/admin/settings/types-supported.php';
require_once FIP_PLUGIN_DIR_PATH . 'inc/admin/settings/theme-support.php';
require_once FIP_PLUGIN_DIR_PATH . 'inc/admin/settings/unsplash-api-access-key.php';
require_once FIP_PLUGIN_DIR_PATH . 'inc/admin/settings/compact-mode.php';
