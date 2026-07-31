<?php
namespace Reluxity_CGP\Repository;

defined( 'ABSPATH' ) || exit;

final class NodeRepository {
	private string $table;

	public function __construct() {
		global $wpdb;
		$this->table = $wpdb->prefix . 'reluxity_content_nodes';
	}

	public function find_by_post_id( int $post_id ): ?array {
		global $wpdb;
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$this->table} WHERE post_id = %d", $post_id ), ARRAY_A );
		return $row ?: null;
	}

	public function all(): array {
		global $wpdb;
		return $wpdb->get_results( "SELECT * FROM {$this->table} ORDER BY updated_at DESC", ARRAY_A );
	}

	public function by_role( string $role ): array {
		global $wpdb;
		return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$this->table} WHERE content_role = %s ORDER BY updated_at DESC", $role ), ARRAY_A );
	}

	public function upsert( array $data ): array {
		global $wpdb;
		$now = current_time( 'mysql' );
		$existing = $this->find_by_post_id( (int) $data['post_id'] );
		$record = array(
			'post_id' => (int) $data['post_id'],
			'content_role' => sanitize_key( $data['content_role'] ),
			'purpose' => isset( $data['purpose'] ) ? sanitize_textarea_field( $data['purpose'] ) : '',
			'search_intent' => isset( $data['search_intent'] ) ? sanitize_key( $data['search_intent'] ) : '',
			'priority' => isset( $data['priority'] ) ? sanitize_key( $data['priority'] ) : '',
			'planning_status' => isset( $data['planning_status'] ) ? sanitize_key( $data['planning_status'] ) : '',
			'updated_at' => $now,
		);
		if ( $existing ) {
			$wpdb->update( $this->table, $record, array( 'post_id' => (int) $data['post_id'] ) );
		} else {
			$record['created_at'] = $now;
			$wpdb->insert( $this->table, $record );
		}
		return $this->find_by_post_id( (int) $data['post_id'] );
	}

	public function delete( int $post_id ): void {
		global $wpdb;
		$wpdb->delete( $this->table, array( 'post_id' => $post_id ), array( '%d' ) );
	}
}
