<?php
namespace Reluxity_CGP\Repository;

defined( 'ABSPATH' ) || exit;

final class RelationshipRepository {
	private string $table;

	public function __construct() {
		global $wpdb;
		$this->table = $wpdb->prefix . 'reluxity_content_relationships';
	}

	public function create( array $data ): array {
		global $wpdb;
		$now = current_time( 'mysql' );
		$record = array(
			'source_post_id' => (int) $data['source_post_id'],
			'target_post_id' => (int) $data['target_post_id'],
			'relationship_type' => sanitize_key( $data['relationship_type'] ),
			'description' => isset( $data['description'] ) ? sanitize_textarea_field( $data['description'] ) : '',
			'weight' => isset( $data['weight'] ) ? absint( $data['weight'] ) : null,
			'created_at' => $now,
			'updated_at' => $now,
		);
		$wpdb->replace( $this->table, $record );
		return $this->find( (int) $wpdb->insert_id );
	}

	public function find( int $id ): ?array {
		global $wpdb;
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$this->table} WHERE id = %d", $id ), ARRAY_A );
		return $row ?: null;
	}

	public function all(): array {
		global $wpdb;
		return $wpdb->get_results( "SELECT * FROM {$this->table} ORDER BY updated_at DESC", ARRAY_A );
	}

	public function outgoing( int $post_id ): array {
		global $wpdb;
		return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$this->table} WHERE source_post_id = %d", $post_id ), ARRAY_A );
	}

	public function incoming( int $post_id ): array {
		global $wpdb;
		return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$this->table} WHERE target_post_id = %d", $post_id ), ARRAY_A );
	}

	public function delete( int $id ): void {
		global $wpdb;
		$wpdb->delete( $this->table, array( 'id' => $id ), array( '%d' ) );
	}

	public function delete_for_post( int $post_id ): void {
		global $wpdb;
		$wpdb->query( $wpdb->prepare( "DELETE FROM {$this->table} WHERE source_post_id = %d OR target_post_id = %d", $post_id, $post_id ) );
	}
}
