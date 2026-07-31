<?php
namespace Reluxity_CGP\Schema;

defined( 'ABSPATH' ) || exit;

final class SchemaInstaller {
	public const SCHEMA_VERSION = '1.0.0';
	public const OPTION_NAME = 'reluxity_cgp_schema_version';

	public static function activate(): void {
		self::install();
	}

	public static function install(): void {
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$charset_collate = $wpdb->get_charset_collate();
		$nodes = $wpdb->prefix . 'reluxity_content_nodes';
		$relationships = $wpdb->prefix . 'reluxity_content_relationships';

		dbDelta( "CREATE TABLE {$nodes} (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			post_id BIGINT UNSIGNED NOT NULL,
			content_role VARCHAR(32) NOT NULL,
			purpose TEXT NULL,
			search_intent VARCHAR(64) NULL,
			priority VARCHAR(32) NULL,
			planning_status VARCHAR(32) NULL,
			created_at DATETIME NOT NULL,
			updated_at DATETIME NOT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY post_id (post_id),
			KEY content_role (content_role),
			KEY priority (priority),
			KEY planning_status (planning_status)
		) {$charset_collate};" );

		dbDelta( "CREATE TABLE {$relationships} (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			source_post_id BIGINT UNSIGNED NOT NULL,
			target_post_id BIGINT UNSIGNED NOT NULL,
			relationship_type VARCHAR(32) NOT NULL,
			description TEXT NULL,
			weight INT UNSIGNED NULL,
			created_at DATETIME NOT NULL,
			updated_at DATETIME NOT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY unique_relationship (source_post_id, target_post_id, relationship_type),
			KEY source_post_id (source_post_id),
			KEY target_post_id (target_post_id),
			KEY relationship_type (relationship_type)
		) {$charset_collate};" );

		update_option( self::OPTION_NAME, self::SCHEMA_VERSION );
	}
}
