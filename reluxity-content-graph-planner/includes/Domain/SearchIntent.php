<?php
namespace Reluxity_CGP\Domain;

defined( 'ABSPATH' ) || exit;

final class SearchIntent {
	public const INFORMATIONAL = 'informational';
	public const COMMERCIAL = 'commercial';
	public const TRANSACTIONAL = 'transactional';
	public const NAVIGATIONAL = 'navigational';
	public const LOCAL = 'local';
	public const MIXED = 'mixed';

	public static function all(): array {
		return array( self::INFORMATIONAL, self::COMMERCIAL, self::TRANSACTIONAL, self::NAVIGATIONAL, self::LOCAL, self::MIXED );
	}
}
