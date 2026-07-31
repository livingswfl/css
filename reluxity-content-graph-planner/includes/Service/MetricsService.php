<?php
namespace Reluxity_CGP\Service;

use Reluxity_CGP\Domain\RelationshipType;
use Reluxity_CGP\Repository\RelationshipRepository;

defined( 'ABSPATH' ) || exit;

final class MetricsService {
	private RelationshipRepository $relationships;

	public function __construct( RelationshipRepository $relationships ) {
		$this->relationships = $relationships;
	}

	public function for_post( int $post_id ): array {
		$incoming = $this->relationships->incoming( $post_id );
		$outgoing = $this->relationships->outgoing( $post_id );
		$all = array_merge( $incoming, $outgoing );
		$breakdown = array_fill_keys( RelationshipType::all(), 0 );
		foreach ( $all as $relationship ) {
			$type = $relationship['relationship_type'];
			$breakdown[ $type ] = ( $breakdown[ $type ] ?? 0 ) + 1;
		}
		return array(
			'incoming' => count( $incoming ),
			'outgoing' => count( $outgoing ),
			'crossPillar' => $breakdown[ RelationshipType::CROSS_PILLAR ] ?? 0,
			'breakdown' => $breakdown,
		);
	}
}
