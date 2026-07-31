<?php
namespace Reluxity_CGP\Repository;

defined( 'ABSPATH' ) || exit;

final class PageRepository {
	public function get_page( int $post_id ): ?\WP_Post {
		$post = get_post( $post_id );
		return $post && 'page' === $post->post_type ? $post : null;
	}

	public function search( string $term = '', int $limit = 20 ): array {
		$query = new \WP_Query( array(
			'post_type' => 'page',
			'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future' ),
			's' => $term,
			'posts_per_page' => $limit,
			'orderby' => 'title',
			'order' => 'ASC',
		) );
		return array_map( array( $this, 'summarize' ), $query->posts );
	}

	public function create_draft( string $title ): int {
		$post_id = wp_insert_post( array( 'post_title' => $title, 'post_type' => 'page', 'post_status' => 'draft' ), true );
		return is_wp_error( $post_id ) ? 0 : (int) $post_id;
	}

	public function summarize( \WP_Post $post ): array {
		return array( 'postId' => (int) $post->ID, 'title' => get_the_title( $post ), 'status' => $post->post_status, 'url' => get_permalink( $post ) );
	}
}
