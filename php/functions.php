<?php

  /**
   * [menu_list - return active pages list]
   * @return array [pages list]
   */
  function menu_list() {
    $query = 'SELECT pages_alias, pages_title FROM pages WHERE pages_status = TRUE 
      ORDER BY pages_position ASC';
    $stmt = \Model\PDO_start::getConnect()->dbh->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    return !$result ? die('die') : $result;
  }


  /**
   * [tepmlates_list - return pages list]
   * @return array [tepmlates list]
   */
  function tepmlates_list() {
    $query = 'SELECT twig_id, template_name FROM templates';
    $stmt = \Model\PDO_start::getConnect()->dbh->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    return !$result ? die('die') : $result;
  }


    /**
   * [get_db - fetch all or row, from available tables]
   * @param  string  $table [table name]
   * @param  string  $parm  [edit/add/unpublish]
   * @param  integer $id    [id of product's, for edit it]
   * @return array          [result]
   */
  function get_db( $table, $parm, $id ){
    if ( $parm == 'add' )
      return null;

    elseif ( $parm == 'unpublish' || $parm == null ) {
      $parm == 'unpublish' ? $status = FALSE : $status = TRUE;
      switch ($table) {
        case 'pages':
          $query = 'SELECT * FROM pages, templates WHERE pages_tmpl = twig_id 
            AND pages_status = ? ORDER BY pages_position ASC';
          break;
        case 'templates':
          $query = 'SELECT * FROM templates ORDER BY twig_id ASC';
          break;
        default:
          die('table not exist');
      }

      $stmt = \Model\PDO_start::getConnect()->dbh->prepare($query);
      $stmt->execute([$status]);
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    elseif ( $parm == 'edit' ){
      switch ($table) {
        case 'pages':
          return null;
          break;
        case 'templates':
          $query = 'SELECT * FROM templates WHERE twig_id = ? LIMIT 1';
          break;
        default:
          exit('table not exist');
      }

      $stmt = \Model\PDO_start::getConnect()->dbh->prepare($query);
      $stmt->execute( [(int)$id] );
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    else 
      die('unknown parm');
    
    return !$result ? null : $result;
  }
  

  function get_tmpl_name($current){
    $query = 'SELECT template_name FROM pages, templates WHERE pages_alias = ? AND pages_tmpl = twig_id LIMIT 1';
    $stmt = \Model\PDO_start::getConnect()->dbh->prepare($query);
    $stmt->execute( [$current] );
    $result = $stmt->fetchColumn();
    return !$result ? '404.tmpl' : $result;
  }
