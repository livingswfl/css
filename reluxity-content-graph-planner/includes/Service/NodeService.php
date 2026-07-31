<?php
namespace Reluxity_CGP\Service;

use Reluxity_CGP\Domain\ContentRole;
use Reluxity_CGP\Domain\PlanningStatus;
use Reluxity_CGP\Domain\Priority;
use Reluxity_CGP\Domain\SearchIntent;
use Reluxity_CGP\Repository\NodeRepository;
use Reluxity_CGP\Repository\PageRepository;

defined( 'ABSPATH' ) || exit;

final class NodeService {
	public function __construct( private NodeRepository $nodes, private PageRepository $pages ) {}

	public function upsert( array $data ) {
		$post_id = absint( $data['post_id'] ?? 0 );
		if ( ! $this->pages->get_page( $post_id ) ) {
			return new \WP_Error( 'reluxity_cgp_invalid_page', 'A valid WordPress Page is required.', array( 'status' => 400 ) );
		}
		if ( ! in_array( $data['content_role'] ?? '', ContentRole::all(), true ) ) {
			return new \WP_Error( 'reluxity_cgp_invalid_role', 'A valid content role is required.', array( 'status' => 400 ) );
		}
		foreach ( array( 'priority' => Priority::all(), 'planning_status' => PlanningStatus::all(), 'search_intent' => SearchIntent::all() ) as $field => $allowed ) {
			if ( ! empty( $data[ $field ] ) && ! in_array( $data[ $field ], $allowed, true ) ) {
				return new \WP_Error( 'reluxity_cgp_invalid_' . $field, 'Invalid ' . $field . ' value.', array( 'status' => 400 ) );
			}
		}
		return $this->nodes->upsert( $data );
	}

	public function create_draft_node( array $data ) {
		$title = sanitize_text_field( $data['title'] ?? '' );
		if ( '' === $title ) {
			return new \WP_Error( 'reluxity_cgp_missing_title', 'A page title is required.', array( 'status' => 400 ) );
		}
		$post_id = $this->pages->create_draft( $title );
		if ( ! $post_id ) {
			return new \WP_Error( 'reluxity_cgp_create_failed', 'Unable to create the draft WordPress Page.', array( 'status' => 500 ) );
		}
		$data['post_id'] = $post_id;
		return $this->upsert( $data );
	}
}
