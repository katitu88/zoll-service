<?php
  /**
   * index.php
   *
   * Kubik-Studio CMS
   *
   * @author     Sergey saintfr3ak Voronezhev
   * @copyright  2018-2019 Kubik-Studio.de
   * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
   * 
   */

  require 'file:///C|/WinNMP/WWW/www/vendor/autoload.php';
  require 'php/functions.php'; // DB connectors, etc.
  require 'php/config.php'; // Settings
  require 'php/cache.php'; // Cache functions
  require 'php/routing.php';
  require 'php/controller.php'; // Also uses './php/adm/auth.php'

?>
