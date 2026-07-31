<?php
namespace Reluxity_CGP\Domain;

defined( 'ABSPATH' ) || exit;

final class Priority {
	public const HIGH = 'high';
	public const MEDIUM = 'medium';
	public const LOW = 'low';

	public static function all(): array {
		return array( self::HIGH, self::MEDIUM, self::LOW );
	}
}
