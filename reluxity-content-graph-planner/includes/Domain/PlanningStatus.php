<?php
namespace Reluxity_CGP\Domain;

defined( 'ABSPATH' ) || exit;

final class PlanningStatus {
	public const PLANNED = 'planned';
	public const DRAFTING = 'drafting';
	public const NEEDS_REVIEW = 'needs_review';
	public const PUBLISHED = 'published';
	public const REFRESH_NEEDED = 'refresh_needed';
	public const ARCHIVED = 'archived';

	public static function all(): array {
		return array( self::PLANNED, self::DRAFTING, self::NEEDS_REVIEW, self::PUBLISHED, self::REFRESH_NEEDED, self::ARCHIVED );
	}
}
