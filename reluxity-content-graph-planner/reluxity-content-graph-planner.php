<?php
/**
 * Plugin Name: Reluxity Content Graph Planner
 * Description: Visual content architecture and relationship planner for Reluxity WordPress pages.
 * Version: 0.1.0
 * Author: Reluxity
 * Text Domain: reluxity-content-graph-planner
 */

defined( 'ABSPATH' ) || exit;

define( 'RELUXITY_CGP_VERSION', '0.1.0' );
define( 'RELUXITY_CGP_FILE', __FILE__ );
define( 'RELUXITY_CGP_DIR', plugin_dir_path( __FILE__ ) );
define( 'RELUXITY_CGP_URL', plugin_dir_url( __FILE__ ) );

require_once RELUXITY_CGP_DIR . 'includes/Domain/ContentRole.php';
require_once RELUXITY_CGP_DIR . 'includes/Domain/RelationshipType.php';
require_once RELUXITY_CGP_DIR . 'includes/Domain/Priority.php';
require_once RELUXITY_CGP_DIR . 'includes/Domain/PlanningStatus.php';
require_once RELUXITY_CGP_DIR . 'includes/Domain/SearchIntent.php';
require_once RELUXITY_CGP_DIR . 'includes/Schema/SchemaInstaller.php';
require_once RELUXITY_CGP_DIR . 'includes/Capabilities/CapabilityService.php';
require_once RELUXITY_CGP_DIR . 'includes/Repository/PageRepository.php';
require_once RELUXITY_CGP_DIR . 'includes/Repository/NodeRepository.php';
require_once RELUXITY_CGP_DIR . 'includes/Repository/RelationshipRepository.php';
require_once RELUXITY_CGP_DIR . 'includes/Service/MetricsService.php';
require_once RELUXITY_CGP_DIR . 'includes/Service/GraphService.php';
require_once RELUXITY_CGP_DIR . 'includes/Service/NodeService.php';
require_once RELUXITY_CGP_DIR . 'includes/Service/RelationshipService.php';
require_once RELUXITY_CGP_DIR . 'includes/Admin/AdminPage.php';
require_once RELUXITY_CGP_DIR . 'includes/Admin/Assets.php';
require_once RELUXITY_CGP_DIR . 'includes/Rest/GraphController.php';
require_once RELUXITY_CGP_DIR . 'includes/Rest/NodeController.php';
require_once RELUXITY_CGP_DIR . 'includes/Rest/RelationshipController.php';
require_once RELUXITY_CGP_DIR . 'includes/Rest/PageController.php';
require_once RELUXITY_CGP_DIR . 'includes/Plugin.php';

register_activation_hook( __FILE__, array( 'Reluxity_CGP\\Schema\\SchemaInstaller', 'activate' ) );
register_activation_hook( __FILE__, array( 'Reluxity_CGP\\Capabilities\\CapabilityService', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Reluxity_CGP\\Capabilities\\CapabilityService', 'deactivate' ) );

add_action(
	'plugins_loaded',
	static function () {
		Reluxity_CGP\Plugin::instance()->init();
	}
);
