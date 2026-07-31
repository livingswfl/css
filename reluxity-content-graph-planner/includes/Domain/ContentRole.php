<?php
namespace Reluxity_CGP\Domain;

defined( 'ABSPATH' ) || exit;

final class ContentRole {
	public const PILLAR = 'pillar';
	public const CLUSTER = 'cluster';
	public const SUPPORTING = 'supporting';

	public static function all(): array {
		return array( self::PILLAR, self::CLUSTER, self::SUPPORTING );
	}
}
