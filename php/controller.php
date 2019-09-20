<?php
  $loader = new Twig_Loader_Filesystem('/');

/*  
    $filter = new Twig_SimpleFilter('date_russian_month', function ($date) {
      $months = [1 => 'Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня',
        'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября  ', 'Декабря'];
      $date = DateTime::createFromFormat('Y-m-d H:i:s', $date);
      $key = $date->format('n');
      return $date->format('d ' . $months[$key] . ' Y H:i');
    });
*/

  if ( !empty($url_request[1]) && $url_request[1] == 'admin' ) {
    session_start();
    if ( isset($_SESSION['hint']) ){
      $twig_array['hint'] = $_SESSION['hint'];
      unset( $_SESSION['hint'] );
    }

    require './php/adm/auth.php';

    if ( $current == 'logout' ){
      setcookie('id', null, time() -1, '/', ".$domain");
      setcookie('hash', null, time() -1, '/', ".$domain");
      setcookie('sms', null, time() -1, '/', ".$domain");
      unset($_SESSION[$session_name]);
      header('Location: /admin');
    }

    elseif ($current !== '404_adm'){
      $twig_array['db'] = get_db( $current, $parm, $id );
      $twig_array['db2'] = $current == 'pages' || $current == 'templates' ? tepmlates_list() : '';
    }

    if ( ($parm == 'edit' || $parm == 'add') && $current != 'pages')
      $tmpl_path = './templates/adm/ext/edit/'; 
    else
      $tmpl_path = './templates/adm/ext/';

    $current = $current . '.tmpl';
  }
  

  else {
    if ( !empty($_FILES) || !empty($_POST) ) // Files and post blocking
      die('motherfucker');

    $tmpl_path = '';
    $loader_db = new \Model\DatabaseTwigLoader(\Model\PDO_start::getConnect()->dbh); // Database loader
    $loader = new Twig_Loader_Chain( [$loader, $loader_db] );
    $current = get_tmpl_name( $current );
  }

  $twig = new Twig_Environment( $loader, $loader_opt );
  //$twig->addFilter($filter);
  if ( DEBUG )
    $twig->addExtension(new \Twig\Extension\DebugExtension());
 
  $view = new Model\load_twig( $twig_array, $tmpl_path . $current, $twig );
  $view->view();
