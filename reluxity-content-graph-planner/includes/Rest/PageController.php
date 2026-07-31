<?php
namespace Reluxity_CGP\Rest;

use Reluxity_CGP\Capabilities\CapabilityService;
use Reluxity_CGP\Repository\PageRepository;
use Reluxity_CGP\Service\NodeService;

defined( 'ABSPATH' ) || exit;

final class PageController {
	public function __construct( private PageRepository $pages, private NodeService $nodes ) {}
	public function register_routes(): void {
		register_rest_route( 'reluxity-cgp/v1', '/pages/search', array( 'methods' => \WP_REST_Server::READABLE, 'callback' => array( $this, 'search' ), 'permission_callback' => array( $this, 'permissions' ) ) );
		register_rest_route( 'reluxity-cgp/v1', '/pages/draft', array( 'methods' => \WP_REST_Server::CREATABLE, 'callback' => array( $this, 'draft' ), 'permission_callback' => array( $this, 'permissions' ) ) );
	}
	public function permissions(): bool { return CapabilityService::current_user_can_manage(); }
	public function search( \WP_REST_Request $request ): \WP_REST_Response { return rest_ensure_response( $this->pages->search( sanitize_text_field( $request->get_param( 'search' ) ?: '' ), 20 ) ); }
	public function draft( \WP_REST_Request $request ) { return rest_ensure_response( $this->nodes->create_draft_node( $request->get_json_params() ?: array() ) ); }
}
