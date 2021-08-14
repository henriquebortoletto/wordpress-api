<?php

function disabled_routes( $endpoints ) {
  unset( $endpoints['/wp/v2/users'] );
  unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
  
  return $endpoints;
} 
