<?php
  /**
   * [post_to_query - convert and check post array to string for PDO query]
   * @param  array  $allowed [array with allowed columns]
   * @param  array  $post    [post array]
   * @return string          [string for PDO query]
   */
  function post_to_query( $allowed, $post, $not_empty = false){
    $values = [];
    $set = "";

    if ($not_empty){
      foreach ($allowed as $field) {
        if ( isset($post[$field]) && !empty($post[$field]) ) {
          $set.="`".str_replace("`", "``", $field)."`". "=:$field, ";
          $values[$field] = $post[$field];
        }
      }
    }

    else {
      foreach ($allowed as $field) {
        if (isset($post[$field])) {
          $set.="`".str_replace("`", "``", $field)."`". "=:$field, ";
          $values[$field] = $post[$field];
        }
      }
    }
    return [substr($set, 0, -2), $values];
  }


                    /**
                     *
                     *
                     *  Files upload functions
                     *
                     *
                     */
  /**
   * [file_check - validate file before upload]
   * @param  string  $post_name [post name of file]
   * @param  string  $file_type [type of upload file]
   * @param  string  $file_size [size of upload file]
   * @param  string  $file_name [name of upload file]
   * @param  string  $tmp_file  [temp file path]
   * @param  array   $supported [list of allowed filetypes]
   * @param  integer $max_size  [maximum filesize]
   * @return string             [validation info]
   */
  function file_check( $post_name, $file_type, $file_size, $file_name, $tmp_file, $supported, $max_size ){
    switch (true) {
      case $post_name == NULL:
        return 'filename == null';
        break;
      case !in_array($file_type, $supported):
        unlink($tmp_file);
        return 'not image';
        break;
      case $file_size > $max_size:
        unlink($tmp_file);
        return 'too big';
        break;
      case $file_name == NULL:
        unlink($tmp_file);
        return 'no temp';
        break;
      case preg_match('/[^A-Za-zА-Яа-яёЁй0-9 (\)\,\s\-\.\_]/iu', $file_name):
        unlink($tmp_file);
        return 'file name ' . $file_name;
        break;
      default:
        return 'done';
    }      
  }


  /**
   * [magick_resize - resize image or make thimb by gmagic]
   * @param  string  $source  [temp file path]
   * @param  integer $width   [max width of file in px]
   * @param  integer $height  [max height of file in px]
   * @param  boolean $bestfit [make image proportional]
   * @param  integer $quality [compress quality - bigger is better]
   * @param  boolean $thumb   [if false - just resize, else - make low quality thumb]
   */
  function gmagick_resize( $source, $width, $height, $bestfit, $quality, $thumb = false ){
      $image = new Gmagick($source);
      $image->setCompressionQuality($quality);
      $thumb ? $image->thumbnailImage($width, $height, $bestfit) : $image->scaleimage($width, $height, $bestfit);
    $image->write($source);
  }
  
  function magick_resize( $source, $width, $height, $bestfit, $quality, $thumb = false ){
    $image = new Imagick();
    $image->readImage(realpath($source));
      $image->setCompressionQuality($quality);
      $thumb ? $image->thumbnailImage($width, $height, $bestfit) : $image->scaleimage($width, $height, $bestfit);
    $image->writeImage($source);
  }


  /**
   * [upload_image - verify, compress and upload image to server]
   * @param  string  $img_type   [type of upload file]
   * @param  integer $img_size   [size of upload file]
   * @param  string  $tmp_file   [temp file path]
   * @param  string  $file_name  [name of upload file]
   * @param  string  $img_name   [post name of file]
   * @param  string  $save_to    [path where save file]
   * @param  integer $img_w      [max width of file in px]
   * @param  integer $img_h      [max height of file in px]
   * @param  integer $img_c      [compress quality - bigger is better]
   * @param  boolean $make_thumb [if false - just resize, else - make low quality thumb]
   * @param  integer $img_tw     [max width of file in px]
   * @param  integer $img_th     [max height of file in px]
   * @param  integer $img_tc     [compress quality - bigger is better]
   * @return string              [info about completed operation]
   */
  function upload_image( $img_type, $img_size, $tmp_file, $file_name, $img_name, $save_to, $img_w, $img_h, $img_c, $make_thumb, $img_tw = 90, $img_th = 90, $img_tc = 50) {
    $supported = ['image/jpeg', 'image/png'];
    $hint = file_check($file_name, $img_type, $img_size, $img_name, $tmp_file, $supported, 1572864);
    $new_file = "$save_to/$img_name";
    $thumb = "$save_to/thumb/$img_name";
    if ($hint != 'done')
      return $hint;
    if (!is_dir($save_to))
      mkdir ($save_to, 0755, true);

    copy($tmp_file, $new_file);

    if ( $make_thumb ){
      if (!is_dir("$save_to/thumb"))
        mkdir ("$save_to/thumb", 0755, true);
      copy($tmp_file, $thumb);
      magick_resize($thumb, $img_tw, $img_th, true, $img_tc, true);
    }

    unlink($tmp_file);
    list($width_orig, $height_orig) = getimagesize($new_file);
    $img_res = $width_orig . 'x' . $height_orig;
    magick_resize($new_file, $img_w, $img_h, true, $img_c);
    return $hint;
  }


                    /**
                     *
                     *
                     *  Work with database
                     *
                     *
                     */
  /**
   * [delete_by_id - delete from database by id]
   * @param  string $post        [post with id and alias]
   * @param  string $table       [table name]
   * @return string              [info about completed operation]
   */
  function delete_by_id ( $post, $table ){
    $explode = explode(' ', $post);
    $post_id = (int)$explode[0];
    preg_match('/[^A-Za-zА0-9\s\.\-]/iu', $explode[1]) ? exit('wrong alias') : $post_alias = $explode[1];

    switch ( $table ) {
      case 'pages':
        $query = 'DELETE FROM pages WHERE pages_id = ? AND pages_alias = ? LIMIT 1';
        $exec_vars = [$post_id, $post_alias];
        break;
      case 'templates':
        $query = 'DELETE FROM templates WHERE twig_id = ? AND template_name = ? LIMIT 1; ';
        $query.= 'DELETE FROM pages WHERE pages_tmpl IS NULL; ';
        $exec_vars = [$post_id, $post_alias];

        $templates_path = $_SERVER['DOCUMENT_ROOT'] . '/templates/ext/';
        unlink(  $templates_path . $post_alias );
        break;
      default:
        exit('table not exist');
    }
    
    $result = Model\PDO_start::getConnect()->dbh->prepare($query)->execute($exec_vars);
    return !$result ? null : 'done';
  }


  /**
   * [upd_status - changes status in database by id]
   * @param  string $post        [post with id, alias and status]
   * @param  string $table       [table name]
   * @return string              [info about completed operation]
   */
  function upd_status ( $post, $table ){
    $explode = explode(' ', $post);
    $post_id = (int)$explode[0];
    preg_match('/[^A-Za-zА0-9\s\-]/iu', $explode[1]) ? exit('wrong alias') : $post_alias = $explode[1];

    $post_status = (int)$explode[2];

    switch ( $table ) {
      case 'pages':
        $query = 'UPDATE pages SET pages_status = ? WHERE pages_id = ? AND pages_alias = ? LIMIT 1';
        break;
      default:
        exit('table not exist');
    }
    
    $result = Model\PDO_start::getConnect()->dbh->prepare($query)->execute([$post_status, $post_id, $post_alias]);
    return !$result ? null : 'done';
  }


  /**
   * [post_pages - update or add page]
   * @param  array   $post        [post from form]
   * @param  string  $parm        [add or edit]
   * @param  integer $id          [or null, only needed for change page]
   * @param  array   $allowed     [array with allowed post keys]
   * @return string               [info about completed operation]
   */
  function post_pages ( $post, $parm, $allowed ){
    if (  empty($post['pages_title']) || 
          preg_match('/[^A-Za-zА-Яа-яёЁ0-9\s\-]/iu', $post['pages_title']) || 
          preg_match('/[^A-Za-zА0-9\s\-]/iu', $post['pages_alias']) 
        )
      return 'no title';
    
    $post['pages_dt'] = date('Y-m-d H:i:s');
    list($set, $values) = post_to_query( $allowed, $post );
    $query = $parm == 'edit' ? "UPDATE pages SET $set WHERE pages_id = :pages_id" : "INSERT INTO pages SET $set";
    $result = Model\PDO_start::getConnect()->dbh->prepare($query)->execute( $values );

    if (!$result) {
      $error = $result->errorInfo();
      die ("Error: (".$error[0].':'.$error[1].') '.$error[2]);
    }
    
    return 'done';
  }  


  function post_template ( $post, $parm, $id, $allowed ){
    $templates_path = $_SERVER['DOCUMENT_ROOT'] . '/templates/ext/';
    $post['last_modified'] = date('Y-m-d H:i:s');
    $exist_templates = tepmlates_list();

    if (  empty($post['template_name']) 
          || preg_match('/[^A-Za-zА0-9\.\-]/iu', $post['template_name'])
          || ( in_array( $post['template_name'], $exist_templates ) && $post['template_name'] !== $post['orig_name'])
      ){
      return 'bad title';
    }
    else 
      $post_name = $post['template_name'];

    list( $set, $values ) = post_to_query( $allowed, $post );

    if ( $parm == 'edit' )
      $query = 'UPDATE templates SET template_name = :template_name, source = :source, last_modified = :last_modified 
        WHERE twig_id = :twig_id';
    else
      $query = "INSERT INTO templates SET $set";

    $result = Model\PDO_start::getConnect()->dbh->prepare($query)->execute( $values );

    if (!$result) {
      $error = $result->errorInfo();
      die ("Error: (".$error[0].':'.$error[1].') '.$error[2]);
    }

    if ( $parm == 'edit' ) {
      if ( $post['orig_name'] !== $post_name && file_exists($templates_path . $post['orig_name']) )
        unlink(  $templates_path . $post['orig_name'] );
    }

    file_put_contents( $templates_path . $post_name, $post['source'] );
    return 'done';
  }

function move_pages ( $post, $i = 0 ) {
  foreach($post['pages_alias'] as $value){
    $pages_position = $i++;
    $explode = explode(' ', $value);
    $pages_id = $explode[0];
    $pages_alias = $explode[1];

    $query = "UPDATE pages SET pages_position = ? WHERE pages_id = ? AND pages_alias = ? LIMIT 1";
    $result = Model\PDO_start::getConnect()->dbh->prepare($query)->execute([$pages_position, $pages_id, $pages_alias]);
    if (!$result) {
      $error = $result->errorInfo();
      die ("Error: (".$error[0].':'.$error[1].') '.$error[2]);
    }
  }

  return 'done';
}


                    /**
                     *
                     *
                     *  Point-of-entry
                     *
                     *
                     */
  $url_exist  = [ 'seo','pages','settings','logout','templates', '' ]; // Allowed urls !!! checking if empty field needed
  $parm_exist = [ 'edit','add','unpublish', '' ]; // Allowed parms

  isset( $_POST['current'] ) ? $current = preg_replace( '/[^A-Za-z]/', '', $_POST['current'] ) : $current = null;
  isset( $_POST['parm'] ) ? $parm = preg_replace( '/[^A-Za-z]/', '', $_POST['parm'] ) : $parm = null;
  isset( $_POST['product_id'] ) ? $id = (int)$_POST['product_id'] : $id = null;
  isset( $_POST['twig_id'] ) ? $id = (int)$_POST['twig_id'] : $id = null;

  unset( $_POST['current'], $_POST['parm'], $_POST['product_id'] );
  if ( preg_match('/[^0-9]/', $id) || !in_array($parm, $parm_exist) || !in_array($current, $url_exist) )
    die( 'wrong id or parm' );  

  if ( !empty($_POST) ) {
      switch ( true ) {
      case isset( $_POST['move_pages'] ):
        $hint = move_pages( $_POST );
        break;
      case isset( $_POST['upd_status'] ):
        $hint = upd_status( $_POST['upd_status'], $current );
        break;
      case isset( $_POST['delete'] ):
        $hint = delete_by_id( $_POST['delete'], $current );
        break;
      case isset( $_POST['pages_title'] ):
        $allowed = ['pages_title', 'pages_alias', 'pages_dt', 'pages_id', 'pages_status', 'pages_tmpl'];
        $hint = post_pages( $_POST, $parm, $allowed);
        break;
      case isset( $_POST['template_name'] ):
        $allowed = ['template_name', 'source', 'last_modified', 'twig_id'];
        $hint = post_template( $_POST, $parm, $id, $allowed);
        break;
      default:
        die('post is empty');
      }

    $_SESSION['hint'] = $hint;
    exit($hint);
  }

  else 
    die();
  