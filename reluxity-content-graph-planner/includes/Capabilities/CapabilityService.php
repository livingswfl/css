<?php
namespace Reluxity_CGP\Capabilities;

defined( 'ABSPATH' ) || exit;

final class CapabilityService {
	public const CAPABILITY = 'reluxity_manage_content_graph';

	public static function activate(): void {
		$role = get_role( 'administrator' );
		if ( $role ) {
			$role->add_cap( self::CAPABILITY );
		}
	}

	public static function deactivate(): void {
		$role = get_role( 'administrator' );
		if ( $role ) {
			$role->remove_cap( self::CAPABILITY );
		}
	}

	public static function current_user_can_manage(): bool {
		return current_user_can( self::CAPABILITY ) || current_user_can( 'manage_options' );
	}
}
