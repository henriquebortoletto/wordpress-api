<?php

function api_comment_post( $request ) {
  $user = wp_get_current_user();
  
  if ( $user->ID === 0 ) {
    $response = new WP_Error( 'error', 'Sem permissão', ['status' => 401] );
    return rest_ensure_response( $response );
  }

  $comment = sanitize_text_field( $request['comment'] );
  $post_id = $request['id'];

  if ( empty($comment) ) {
    $response = new WP_Error( 'error', 'Dados incompletos', ['status' => 422] );
    return rest_ensure_response( $response );
  }

  $comment = wp_insert_comment([
    'comment_author'  => $user->user_login,
    'comment_content' => $comment,
    'comment_post_ID' => $post_id,
    'user_id'         => $user->ID
  ]);

  $response = get_comment( $comment );

  return rest_ensure_response( $response );
}

function register_api_comment_post() {
  register_rest_route( 'v1', '/comment/(?P<id>[0-9]+)', [
    'methods'  => WP_REST_Server::CREATABLE,
    'callback' => 'api_comment_post'
  ]);
}
