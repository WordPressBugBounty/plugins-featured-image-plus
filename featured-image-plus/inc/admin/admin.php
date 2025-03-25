<?php
/**
 * Load admin files.
 *
 * @package    DEVRY\FIP
 * @copyright  Copyright (c) 2025, Developry Ltd.
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU Public License
 * @since      1.4
 */

namespace DEVRY\FIP;

! defined( ABSPATH ) || exit; // Exit if accessed directly.

require_once FIP_PLUGIN_DIR_PATH . 'inc/admin/requirements/requirements.php';
require_once FIP_PLUGIN_DIR_PATH . 'inc/admin/settings/settings.php';

require_once FIP_PLUGIN_DIR_PATH . 'inc/admin/classic-editor/classic-editor.php';
require_once FIP_PLUGIN_DIR_PATH . 'inc/admin/block-editor/block-editor.php';
