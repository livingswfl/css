<?php
namespace Reluxity_CGP\Rest;

use Reluxity_CGP\Capabilities\CapabilityService;
use Reluxity_CGP\Repository\RelationshipRepository;
use Reluxity_CGP\Service\RelationshipService;

defined( 'ABSPATH' ) || exit;

final class RelationshipController {
	public function __construct( private RelationshipRepository $relationships, private RelationshipService $service ) {}
	public function register_routes(): void {
		register_rest_route( 'reluxity-cgp/v1', '/relationships', array(
			array( 'methods' => \WP_REST_Server::READABLE, 'callback' => array( $this, 'list' ), 'permission_callback' => array( $this, 'permissions' ) ),
			array( 'methods' => \WP_REST_Server::CREATABLE, 'callback' => array( $this, 'create' ), 'permission_callback' => array( $this, 'permissions' ) ),
		) );
		register_rest_route( 'reluxity-cgp/v1', '/relationships/(?P<id>\d+)', array( 'methods' => \WP_REST_Server::DELETABLE, 'callback' => array( $this, 'delete' ), 'permission_callback' => array( $this, 'permissions' ) ) );
	}
	public function permissions(): bool { return CapabilityService::current_user_can_manage(); }
	public function list(): \WP_REST_Response { return rest_ensure_response( $this->relationships->all() ); }
	public function create( \WP_REST_Request $request ) { return rest_ensure_response( $this->service->create( $request->get_json_params() ?: array() ) ); }
	public function delete( \WP_REST_Request $request ): \WP_REST_Response { $this->relationships->delete( absint( $request['id'] ) ); return rest_ensure_response( array( 'deleted' => true ) ); }
}
