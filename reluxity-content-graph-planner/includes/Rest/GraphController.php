<?php
namespace Reluxity_CGP\Rest;

use Reluxity_CGP\Capabilities\CapabilityService;
use Reluxity_CGP\Service\GraphService;

defined( 'ABSPATH' ) || exit;

final class GraphController {
	public function __construct( private GraphService $graph ) {}
	public function register_routes(): void {
		register_rest_route( 'reluxity-cgp/v1', '/graph', array( 'methods' => \WP_REST_Server::READABLE, 'callback' => array( $this, 'get' ), 'permission_callback' => array( $this, 'permissions' ) ) );
	}
	public function permissions(): bool { return CapabilityService::current_user_can_manage(); }
	public function get( \WP_REST_Request $request ): \WP_REST_Response { return rest_ensure_response( $this->graph->get_graph( sanitize_key( $request->get_param( 'view' ) ?: 'overview' ), absint( $request->get_param( 'post_id' ) ) ) ); }
}
