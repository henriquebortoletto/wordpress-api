<?php

/**
 * Disable all internal wp routes
 * remove_action( 'rest_api_init', 'create_initial_rest_routes', 99 ); 
 */

$path = get_template_directory();

require_once $path . '/endpoints/api_rest_change_prefix.php';
require_once $path . '/endpoints/api_rest_disabled_routes.php';
require_once $path . '/endpoints/api_rest_password-reset.php';
require_once $path . '/endpoints/api_rest_stats_get.php';
require_once $path . '/endpoints/api_rest_user_get.php';
require_once $path . '/endpoints/api_rest_user_post.php';
require_once $path . '/endpoints/api_rest_photo_get.php';
require_once $path . '/endpoints/api_rest_photo_post.php';
require_once $path . '/endpoints/api_rest_photo_delete.php';
require_once $path . '/endpoints/api_rest_comment_get.php';
require_once $path . '/endpoints/api_rest_comment_post.php';
require_once $path . '/endpoints/api_rest_token_expire.php';

add_action( 'rest_api_init'  , 'register_api_user_get'               );
add_action( 'rest_api_init'  , 'register_api_user_post'              );
add_action( 'rest_api_init'  , 'register_api_photo_get'              );
add_action( 'rest_api_init'  , 'register_api_photo_get_all'          );
add_action( 'rest_api_init'  , 'register_api_photo_post'             );
add_action( 'rest_api_init'  , 'register_api_photo_delete'           );
add_action( 'rest_api_init'  , 'register_api_comment_get'            );
add_action( 'rest_api_init'  , 'register_api_comment_post'           );
add_action( 'rest_api_init'  , 'register_api_password_generate_link' );
add_action( 'rest_api_init'  , 'register_api_password_reset'         );
add_action( 'rest_api_init'  , 'register_api_stats_get'              );
add_action( 'jwt_auth_expire', 'expire_token'                        );

add_filter( 'rest_url_prefix', 'change_api'      );
add_filter( 'rest_endpoints' , 'disabled_routes' );

update_option( 'large_size_w', 1000 );
update_option( 'large_size_h', 1000 );
update_option( 'large_crop'  ,    1 );
