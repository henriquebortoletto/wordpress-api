<?php

function api_photo_post( $request ) {
  $user = wp_get_current_user();

  if ( $user->ID === 0 ) {
    $response = new WP_Error( 'error', 'Usuário não possui permissão', ['status' => 401] );
    return rest_ensure_response( $response );
  }

  $name   = sanitize_text_field( $request['name'] );
  $weight = sanitize_text_field( $request['weight'] );
  $age    = sanitize_text_field( $request['age'] );
  $files  = $request->get_file_params(); 

  if ( empty( $name ) || empty( $weight ) || empty( $age ) || empty( $files ) ) {
    $response = new WP_Error( 'error', 'Dados incompletos', ['status' => 422] );
    return rest_ensure_response( $response );
  }

  $response = wp_insert_post([
    'post_author'  => $user->ID,
    'post_type'    => 'post',
    'post_status'  => 'publish',
    'post_title'   => $name,
    'post_content' => $name,
    'files'        => $files,
    'meta_input' => [
      'weight' => $weight,
      'age'    => $age,
      'access' => 0
    ]
  ]);

  $post_id = $response;

  require_once ABSPATH . 'wp-admin/includes/image.php';
  require_once ABSPATH . 'wp-admin/includes/file.php';
  require_once ABSPATH . 'wp-admin/includes/media.php';

  $photo_id = media_handle_upload( 'img', $post_id );
  update_post_meta( $post_id, 'img', $photo_id );
  
  return rest_ensure_response( $response );
}

function register_api_photo_post() {
  register_rest_route( 'v1', '/photo', [
    'methods'  => WP_REST_Server::CREATABLE,
    'callback' => 'api_photo_post'
  ]);
}
