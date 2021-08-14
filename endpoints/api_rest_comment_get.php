<?php

function api_comment_get( $request ) {
  $post_id = $request['id'];

  $response = get_comments([
    'post_id' => $post_id
  ]);

  return rest_ensure_response( $response );
}

function register_api_comment_get() {
  register_rest_route( 'v1', '/comment/(?P<id>[0-9]+)', [
    'methods'  => WP_REST_Server::READABLE,
    'callback' => 'api_comment_get'
  ]);
}
