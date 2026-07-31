<?php
namespace Reluxity_CGP\Admin;

defined( 'ABSPATH' ) || exit;

final class Assets {
	public function enqueue( string $hook ): void {
		if ( 'toplevel_page_reluxity-content-graph' !== $hook ) {
			return;
		}
		$asset_file = RELUXITY_CGP_DIR . 'assets/admin/build/index.asset.php';
		$asset = file_exists( $asset_file ) ? require $asset_file : array( 'dependencies' => array( 'wp-element', 'wp-components', 'wp-api-fetch' ), 'version' => RELUXITY_CGP_VERSION );
		wp_enqueue_script( 'reluxity-cgp-admin', RELUXITY_CGP_URL . 'assets/admin/build/index.js', $asset['dependencies'], $asset['version'], true );
		wp_enqueue_style( 'reluxity-cgp-admin', RELUXITY_CGP_URL . 'assets/admin/build/index.css', array(), $asset['version'] );
		wp_localize_script( 'reluxity-cgp-admin', 'reluxityCgpSettings', array( 'restUrl' => esc_url_raw( rest_url( 'reluxity-cgp/v1/' ) ), 'nonce' => wp_create_nonce( 'wp_rest' ) ) );
	}
}
