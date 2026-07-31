<?php
namespace Reluxity_CGP\Admin;

use Reluxity_CGP\Capabilities\CapabilityService;

defined( 'ABSPATH' ) || exit;

final class AdminPage {
	public function register(): void {
		add_menu_page( 'Reluxity Content Graph', 'Content Graph', CapabilityService::CAPABILITY, 'reluxity-content-graph', array( $this, 'render' ), 'dashicons-networking', 30 );
	}

	public function render(): void {
		echo '<div class="wrap"><div id="reluxity-content-graph-app"></div></div>';
	}
}
