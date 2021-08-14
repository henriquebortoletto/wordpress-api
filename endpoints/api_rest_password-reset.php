<?php

function api_password_generate_link( $request ) {
  $login = $request['login'];
  $url   = $request['url'];

  if ( empty( $login ) ) {
    $response = new WP_Error( 'error', 'Informe o email ou login', ['status' => 406] );
    return rest_ensure_response( $response );
  }

  $user = get_user_by( 'email', $login );

  if ( empty( $user ) ) {
    $user = get_user_by( 'login', $login );
  }

  if ( empty( $login ) ) {
    $response = new WP_Error( 'error', 'Usuário não existe', ['status' => 401] );
    return rest_ensure_response( $response );
  }

  $user_login = $user->user_login;
  $user_email = $user->user_email;

  $key = get_password_reset_key( $user );

  $message = "Utilize o link abaixo para resetar a sua senha: \r\n";
  $url     = esc_url_raw($url . "/?key=$key&login=" . rawurlencode( $user_login ) . "\r\n");
  $body    = $message . $url;

  wp_mail( $user_email, 'Password Reset', $body );

  /** 
   * change the answer when it stops 
   * the production environment.
   */
  return rest_ensure_response( ['send' => $user_email, 'Password Reset', $body] );
}

function register_api_password_generate_link() {
  register_rest_route( 'v1', '/password/generate', [
    'methods'  => WP_REST_Server::CREATABLE,
    'callback' => 'api_password_generate_link'
  ]);
}

function api_password_reset( $request ) {
  $login     = $request['login'];
  $password  = $request['password'];
  $key       = $request['key'];
  $user      = get_user_by( 'login', $login );

  if ( empty( $user ) ) {
    $response = new WP_Error( 'error', 'Usuário não existe', ['status' => 401] );
    return rest_ensure_response( $response );
  } 

  $check_key = check_password_reset_key( $key, $login );

  if ( is_wp_error( $check_key ) ) {
    $response = new WP_Error( 'error', 'Token expirado', ['status' => 401] );
    return rest_ensure_response( $response );
  }

  reset_password( $user, $password );

  return rest_ensure_response([
    'message' => 'Senha alterada com sucesso'
  ]);
}

function register_api_password_reset() {
  register_rest_route( 'v1', '/password/reset', [
    'methods'  => WP_REST_Server::CREATABLE,
    'callback' => 'api_password_reset'
  ]);
}
