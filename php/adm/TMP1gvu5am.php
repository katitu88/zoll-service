<?php
  function auth_page($loader_opt){
    $loader = new Twig_Loader_Filesystem( './templates/adm/' );
    $twig = new Twig_Environment( $loader, $loader_opt );
    $view = new \Model\load_twig([], 'auth.tmpl', $twig );
    $view->view();
    exit();
  }

  /**
   * [generateCode - solt generator]
   * @param  integer $length   [code of solt]
   * @param  boolean $no_space [use space or not]
   * @return string            [return rando, generated code]
   */
  function generateCode( $length=6, $no_space=false ){
    $no_space == false ? $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789' : $chars = 'abcdefghijkmnopqrstuvwxyzABCDEFGHJKMNPRQSTUVWXYZ23456789';
    $code = '';
    
    $clen = strlen($chars) - 1;
    while ( strlen($code) < $length ) {
      $code .= $chars[mt_rand(0,$clen)];  
    }
    
    return $code;
  }


  /**
   * [logoff - logoff user]
   */
  function logoff( $domain ) {
    setcookie('id', null, time() -1, '/', ".$domain");
    setcookie('hash', null, time() -1, '/', ".$domain");
    header('Location: /admin');
    exit('fuckoff');
  }


  /**
   * [connect - check form and set cookies]
   * @param  string  $login        [login from form]
   * @param  string  $password     [password from form]
   * @param  string  $my_ip        [user's ipv4 adress]
   * @param  string  $domain       [domain adress for cookies]
   * @param  integer $cookie_time  [cookoes expire time]
   * @return string                [status]
   */
  function connect( $login, $password, $my_ip, $domain, $cookie_time ){
    $stmt = \Model\PDO_start::getConnect()->dbh->prepare('SELECT user_id, user_password FROM users WHERE user_login = ? LIMIT 1');
    $status = $stmt->execute([$login]);
    $data = $stmt->fetch(\PDO::FETCH_ASSOC);
    \Model\PDO_start::getConnect()->dbh->prepare('UPDATE users SET user_hash = ? WHERE user_login = ?')->execute(['', $login]);

    if( $status && password_verify($password, $data['user_password']) ){
      $hash = hash( 'sha256', generateCode(10) );
      $query = 'UPDATE users SET user_hash = ?, user_ip = ?, user_last_logon = ? WHERE user_id = ?';
      $result = \Model\PDO_start::getConnect()->dbh->prepare($query)->execute([ $hash, $my_ip, date('Y-m-d H:i:s'), (int)$data['user_id'] ]);

      if (!$result)
        die('fuckoff');
      
      setcookie( 'id', (int)$data['user_id'], time() + $cookie_time, '/', ".$domain" );
      setcookie( 'hash', $hash, time() + $cookie_time, '/', ".$domain" );
      return 'done';
    }

    else 
      return 'fuckoff';
  }


  /**
   * [check_login - check auth by cookies]
   * @param  string  $my_ip        [user's ipv4 adress]
   * @param  string  $domain       [domain adress for cookies]
   * @param  integer $cookie_time  [cookoes expire time]
   * @return string                [status]
   */
  function check_login( $my_ip, $domain, $cookie_time ){
    if ( preg_match('/[^A-Za-zА0-9\-]/iu', $_COOKIE['hash']) ){
      logoff($domain);
    }

    $stmt = \Model\PDO_start::getConnect()->dbh->prepare('SELECT user_hash, user_id, user_ip, user_last_logon FROM users WHERE user_hash = ? AND user_ip = ? LIMIT 1');
    $stmt->execute([ $_COOKIE['hash'], $my_ip ]);
    $data = $stmt->fetch(\PDO::FETCH_ASSOC);
    $now = new DateTime();
    $expired_time = strtotime( '+' . $cookie_time . ' seconds', strtotime( $data['user_last_logon']) );
    if ( $data['user_hash'] == $_COOKIE['hash'] && $data['user_id'] == (int)$_COOKIE['id'] && $data['user_ip'] == (int)$my_ip ){
        if ( $now->getTimestamp() >= $expired_time ){
          logoff($domain);
        }
        else {
          $query = 'UPDATE users SET user_last_logon = ? WHERE user_id = ?';
          \Model\PDO_start::getConnect()->dbh->prepare($query)->execute([ date('Y-m-d H:i:s'), $data['user_id'] ]);
          setcookie('id', (int)$_COOKIE['id'], time() + $cookie_time, '/', ".$domain");
          setcookie('hash', $_COOKIE['hash'], time() + $cookie_time, '/', ".$domain");
          return 'all_ok';
        }
      }
      else {
        logoff($domain);
      }
  }


                    /**
                     *
                     *
                     *  Point-of-entry
                     *
                     *
                     */
  $my_ip = str_replace( '.', '', $_SERVER['REMOTE_ADDR'] ); // User's ipv4-adress

  if( !isset($_COOKIE['id']) || !isset($_COOKIE['hash']) ){ // Auth check if no Cookies
    if ( !isset($_POST['login']) && !isset($_POST['password']) )// No Post
      auth_page($loader_opt);

    else {
        switch (true) {
          case isset($_POST['im_not_a_robot']):
          case empty($_POST['login']):
          case empty($_POST['password']):
          case preg_match('/[^A-Za-zА0-9\-]/iu', $_POST['login']):
          case preg_match('/[^A-Za-zА0-9\s\-\!\)\(]/iu', $_POST['password']):
            exit('fuckoff');
            break;
          default:
            $login = $_POST['login'];
            $password = $_POST['password'];
            $auth_check = connect( $login, $password, (int)$my_ip, $domain, $cookie_time );
            break;
        }
     } 
  } 

  elseif ( isset($_COOKIE['id']) && isset($_COOKIE['hash']) ) // See cookies, omnomnom
    $auth_check = check_login( (int)$my_ip, $domain, $cookie_time );

  switch (true) {
    case $auth_check == 'all_ok' && !empty($_POST):
      require 'php/adm/func.php';
      break;
    case $auth_check == 'all_ok':
      break;
    default:
      exit($auth_check);
      break;
  }
  