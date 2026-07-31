<?php
namespace Reluxity_CGP\Domain;

defined( 'ABSPATH' ) || exit;

final class RelationshipType {
	public const PARENT = 'parent';
	public const CLUSTER = 'cluster';
	public const SUPPORTING = 'supporting';
	public const CROSS_PILLAR = 'cross_pillar';

	public static function all(): array {
		return array( self::PARENT, self::CLUSTER, self::SUPPORTING, self::CROSS_PILLAR );
	}
}
