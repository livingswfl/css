<?php
namespace Reluxity_CGP\Rest;

use Reluxity_CGP\Capabilities\CapabilityService;
use Reluxity_CGP\Repository\NodeRepository;
use Reluxity_CGP\Service\NodeService;

defined( 'ABSPATH' ) || exit;

final class NodeController {
	public function __construct( private NodeRepository $nodes, private NodeService $service ) {}
	public function register_routes(): void {
		register_rest_route( 'reluxity-cgp/v1', '/nodes', array(
			array( 'methods' => \WP_REST_Server::READABLE, 'callback' => array( $this, 'list' ), 'permission_callback' => array( $this, 'permissions' ) ),
			array( 'methods' => \WP_REST_Server::CREATABLE, 'callback' => array( $this, 'save' ), 'permission_callback' => array( $this, 'permissions' ) ),
		) );
		register_rest_route( 'reluxity-cgp/v1', '/nodes/(?P<post_id>\d+)', array( 'methods' => \WP_REST_Server::EDITABLE, 'callback' => array( $this, 'save' ), 'permission_callback' => array( $this, 'permissions' ) ) );
	}
	public function permissions(): bool { return CapabilityService::current_user_can_manage(); }
	public function list(): \WP_REST_Response { return rest_ensure_response( $this->nodes->all() ); }
	public function save( \WP_REST_Request $request ) { $data = $request->get_json_params() ?: array(); $data['post_id'] = absint( $request['post_id'] ?: ( $data['post_id'] ?? 0 ) ); return rest_ensure_response( $this->service->upsert( $data ) ); }
}
