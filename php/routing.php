<?php
  $to_explode = !empty($_POST) ? str_replace( "http://$domain",'',$_SERVER['HTTP_REFERER'] ) :  $_SERVER['REQUEST_URI'];
  $url_request = explode( '/', $to_explode );

  if ( !empty($url_request[1]) && $url_request[1] == 'admin' ) { //Admin-panel
    $url_exist = [ 'seo', 'pages', 'templates', 'settings', 'logout']; // Allowed pages for admin
    $parm_exist = [ 'edit', 'add', 'unpublish', '' ]; // Allowed parameters for admin
    $first = 'pages'; // Default page for admin
    $siteurl = "http://$domain/admin"; // For admin

    if (!empty($url_request[2]) ) 
      $twig_array['current'] = $current = preg_replace( '/[^A-Za-z]/', '', $url_request[2] );

    else {
      $request = 'https://' . $domain . $_SERVER['REQUEST_URI'];
      $current = $request == $siteurl ? $first : '404_adm' ;
    }

    $parm = ( !empty($url_request[3]) ? preg_replace( '/[^A-Za-z]/', '', $url_request[3] ) : null ); // Parameters
    $id = ( !empty($url_request[4]) ? (int)str_replace( 'id-', '', $url_request[4] ) : null );

    switch (true) {
      case !in_array( $current, $url_exist ):
      case !in_array( $parm, $parm_exist ):
      case count($url_request) > 5:
      case $parm == 'edit' && empty($url_request[4]):
      case preg_match('/[^0-9]/', $id):
        $current = '404_adm';
        break;
      default:
        $twig_array['current'] = $current;
        $twig_array['parm'] = $parm;
        $twig_array['id'] = $id;
        break;
    }
  }

  else { // Front pages
    $twig_array['menu'] = $menu = $reset_menu = menu_list(); // Array of active pages
    reset($reset_menu); // First page
    $twig_array['first_page'] = $first_page = key($reset_menu); // URL adress of first page
    $twig_array['no_url'] = !empty($url_request[1]);
    $current = ( !empty($url_request[1]) ? preg_replace('/[^A-Za-z]-/', '', $url_request[1]) : $first_page ); // Make Get of page clear

    switch (true) { // Validate url
      case !array_key_exists($current, $menu): // Url not exist
      case count($url_request) > 2: // Too long url
        $current = '404';
        break;
      default:
        $twig_array['current'] = $current; // Get value of url
        break;
    }
  }
