<?php
namespace Reluxity_CGP\Service;

use Reluxity_CGP\Domain\ContentRole;
use Reluxity_CGP\Repository\NodeRepository;
use Reluxity_CGP\Repository\PageRepository;
use Reluxity_CGP\Repository\RelationshipRepository;

defined( 'ABSPATH' ) || exit;

final class GraphService {
	public function __construct( private NodeRepository $nodes, private RelationshipRepository $relationships, private PageRepository $pages, private MetricsService $metrics ) {}

	public function get_graph( string $view = 'overview', int $focus_post_id = 0 ): array {
		$node_rows = 'overview' === $view ? $this->nodes->by_role( ContentRole::PILLAR ) : $this->related_node_rows( $focus_post_id );
		$nodes = array();
		foreach ( $node_rows as $row ) {
			$nodes[] = $this->format_node( $row );
		}
		$edges = array_map( array( $this, 'format_edge' ), $this->relationships->all() );
		return array(
			'view' => $view,
			'root' => array( 'id' => 'root', 'label' => 'Reluxity', 'type' => 'website' ),
			'focusPostId' => $focus_post_id,
			'nodes' => array_values( array_filter( $nodes ) ),
			'edges' => array_values( array_filter( $edges ) ),
			'actions' => array( 'canCreatePillar' => true, 'canCreateCluster' => true, 'canCreateSupportingPage' => true ),
		);
	}

	private function related_node_rows( int $focus_post_id ): array {
		$rows = array();
		$focus = $this->nodes->find_by_post_id( $focus_post_id );
		if ( $focus ) {
			$rows[ $focus_post_id ] = $focus;
		}
		foreach ( array_merge( $this->relationships->incoming( $focus_post_id ), $this->relationships->outgoing( $focus_post_id ) ) as $relationship ) {
			foreach ( array( 'source_post_id', 'target_post_id' ) as $field ) {
				$row = $this->nodes->find_by_post_id( (int) $relationship[ $field ] );
				if ( $row ) {
					$rows[ (int) $row['post_id'] ] = $row;
				}
			}
		}
		return array_values( $rows );
	}

	private function format_node( array $row ): ?array {
		$post = $this->pages->get_page( (int) $row['post_id'] );
		if ( ! $post ) {
			return null;
		}
		return array(
			'id' => 'post:' . $post->ID,
			'postId' => (int) $post->ID,
			'label' => get_the_title( $post ),
			'role' => $row['content_role'],
			'purpose' => $row['purpose'],
			'searchIntent' => $row['search_intent'],
			'priority' => $row['priority'],
			'planningStatus' => $row['planning_status'],
			'url' => get_permalink( $post ),
			'metrics' => $this->metrics->for_post( (int) $post->ID ),
		);
	}

	private function format_edge( array $row ): array {
		return array( 'id' => 'rel:' . $row['id'], 'source' => 'post:' . $row['source_post_id'], 'target' => 'post:' . $row['target_post_id'], 'type' => $row['relationship_type'], 'label' => ucwords( str_replace( '_', ' ', $row['relationship_type'] ) ) );
	}
}
