<?php
  //define( 'DEBUG', true );
  const DEBUG = true;
  date_default_timezone_set( 'Europe/Moscow' );
  header( 'Content-type: text/html; charset=utf-8' );
  setlocale( LC_TIME, 'ru_RU.utf8' );

  $cache = '/var/www/test_cache';
  $twig_array['domain'] = $domain = $_SERVER['HTTP_HOST'];
  $twig_array['cookie_time'] = $cookie_time = 1800;
  $css_path_orig = 'css/debug/';
  $css_path_min = 'css/min/';

  if ( DEBUG ) { 
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    error_reporting(-1);
    $twig_array['title'] = 'DEBUG';
    $twig_array['css_path'] = $css_path = $css_path_orig;
    $loader_opt = [ 'auto_reload' => true, 'debug' => true ];
  }

  else {
    error_reporting(0);
    $twig_array['title'] = 'Test';
    $twig_array['css_path'] = $css_path = $css_path_min;
    $loader_opt = [ 'cache' => $cache, 'auto_reload' => false ];
  }
  