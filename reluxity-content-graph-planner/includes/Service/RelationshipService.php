<?php
namespace Reluxity_CGP\Service;

use Reluxity_CGP\Domain\RelationshipType;
use Reluxity_CGP\Repository\PageRepository;
use Reluxity_CGP\Repository\RelationshipRepository;

defined( 'ABSPATH' ) || exit;

final class RelationshipService {
	public function __construct( private RelationshipRepository $relationships, private PageRepository $pages ) {}

	public function create( array $data ) {
		$source = absint( $data['source_post_id'] ?? 0 );
		$target = absint( $data['target_post_id'] ?? 0 );
		$type = sanitize_key( $data['relationship_type'] ?? '' );
		if ( $source === $target ) {
			return new \WP_Error( 'reluxity_cgp_self_relationship', 'A page cannot relate to itself.', array( 'status' => 400 ) );
		}
		if ( ! $this->pages->get_page( $source ) || ! $this->pages->get_page( $target ) ) {
			return new \WP_Error( 'reluxity_cgp_invalid_relationship_pages', 'Source and target must be valid WordPress Pages.', array( 'status' => 400 ) );
		}
		if ( ! in_array( $type, RelationshipType::all(), true ) ) {
			return new \WP_Error( 'reluxity_cgp_invalid_relationship_type', 'Invalid relationship type.', array( 'status' => 400 ) );
		}
		$data['source_post_id'] = $source;
		$data['target_post_id'] = $target;
		$data['relationship_type'] = $type;
		return $this->relationships->create( $data );
	}
}
