<?php

function api_stats_get( $request ) {
  $user_id = (wp_get_current_user())->ID;

  if ( $user_id === 0 ) {
    $response = new WP_Error( 'error', 'Usuário não possui permissão', ['status' => 401] );
    return rest_ensure_response( $response );
  }

  $posts = (new WP_Query([
    'post_type'      => 'post',
    'author'         => $user_id,
    'posts_per_page' => -1
  ]))->posts;

  $stats = [];

  if ( $posts ) {
    foreach( $posts as $post ) {
      $stats[] = [
        'id'     => $post->ID,
        'title'  => $post->post_title,
        'access' => get_post_meta( $post->ID, 'access', true )
      ];
    }
  }

  return rest_ensure_response( $stats );
}

function register_api_stats_get() {
  register_rest_route( 'v1', '/stats', [
    'methods'  => WP_REST_Server::READABLE,
    'callback' => 'api_stats_get'
  ]);
}
