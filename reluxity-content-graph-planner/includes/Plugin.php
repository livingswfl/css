<?php
namespace Reluxity_CGP;

use Reluxity_CGP\Admin\AdminPage;
use Reluxity_CGP\Admin\Assets;
use Reluxity_CGP\Repository\NodeRepository;
use Reluxity_CGP\Repository\PageRepository;
use Reluxity_CGP\Repository\RelationshipRepository;
use Reluxity_CGP\Rest\GraphController;
use Reluxity_CGP\Rest\NodeController;
use Reluxity_CGP\Rest\PageController;
use Reluxity_CGP\Rest\RelationshipController;
use Reluxity_CGP\Service\GraphService;
use Reluxity_CGP\Service\MetricsService;
use Reluxity_CGP\Service\NodeService;
use Reluxity_CGP\Service\RelationshipService;

defined( 'ABSPATH' ) || exit;

final class Plugin {
	private static ?Plugin $instance = null;

	public static function instance(): Plugin {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		$page_repository = new PageRepository();
		$node_repository = new NodeRepository();
		$relationship_repository = new RelationshipRepository();
		$metrics = new MetricsService( $relationship_repository );
		$node_service = new NodeService( $node_repository, $page_repository );
		$relationship_service = new RelationshipService( $relationship_repository, $page_repository );
		$graph_service = new GraphService( $node_repository, $relationship_repository, $page_repository, $metrics );

		add_action( 'admin_menu', array( new AdminPage(), 'register' ) );
		add_action( 'admin_enqueue_scripts', array( new Assets(), 'enqueue' ) );
		add_action( 'rest_api_init', array( new GraphController( $graph_service ), 'register_routes' ) );
		add_action( 'rest_api_init', array( new NodeController( $node_repository, $node_service ), 'register_routes' ) );
		add_action( 'rest_api_init', array( new RelationshipController( $relationship_repository, $relationship_service ), 'register_routes' ) );
		add_action( 'rest_api_init', array( new PageController( $page_repository, $node_service ), 'register_routes' ) );
		add_action( 'before_delete_post', array( $this, 'delete_post_graph_data' ) );
	}

	public function delete_post_graph_data( int $post_id ): void {
		$pages = new PageRepository();
		if ( ! $pages->get_page( $post_id ) ) {
			return;
		}
		( new NodeRepository() )->delete( $post_id );
		( new RelationshipRepository() )->delete_for_post( $post_id );
	}
}
