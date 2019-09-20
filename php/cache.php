<?php
  /**
   * [is_dir_empty - detect emptyness]
   * @param  string  $dir [path to directory]
   * @return boolean      [is empty?]
   */
  function is_dir_empty($dir) {
      return count(glob($dir . '/*', GLOB_NOSORT)) === 0 ? true : false;
  }
  
  /**
   * [deleteAll - rm -R]
   * @param  string  $str [path to directory]
   * @return boolean      [status]
   */
  function deleteAll($str) {
      if (is_file($str)) {
          return unlink($str);
      }
      elseif (is_dir($str)) {
          $scan = glob(rtrim($str,'/').'/*');
          foreach($scan as $index=>$path) {
              deleteAll($path);
          }
          return @rmdir($str);
      }
  }

  if (DEBUG) {
    if ( !is_dir_empty($cache) ){
      deleteAll($cache);
      mkdir($cache, 0755);
    }

    if ( !is_dir_empty('./style/' . $css_path_min) ){
      deleteAll('./style/' . $css_path_min);
      mkdir('./style/' . $css_path_min, 0755);
    }
  }

  else {  
    if ( is_dir_empty('./style/' . $css_path_min) ){
      $scanned_directory = array_diff(scandir('./style/' . $css_path_orig), ['..', '.']);
      foreach ($scanned_directory as $min_css) {
        $sourcePath = "./style/$css_path_orig/$min_css";
        $minifiedPath = "./style/$css_path_min/$min_css";

        $minifier = new MatthiasMullie\Minify\CSS($sourcePath);
        $minifier->minify($minifiedPath);
      }
    }
  }
