<?php
  namespace Model;

  class load_twig {
    private $twig_array = [];
    private $template;
    private $twig;
    
    public function __construct($twig_array, $template, $twig){
      $this->twig_array = $twig_array;
      $this->template = $template;
      $this->twig = $twig;
    }

    function view(){
      echo $this->twig->render( $this->template, $this->twig_array );
    }
  }

  class PDO_start {
    public $dbh;
    public $isConnected;
    public static $instance = null;

    private function __construct() {
      $this->isConnected = true;
      $settings = parse_ini_file('/var/www/test/test.ini');
      $opt = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES => true,
        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
      ];

      try { 
        $this->dbh = new \PDO('mysql:host=localhost;charset=utf8;dbname='.$settings['dbname'], $settings['username'], $settings['password'],$opt);
      }catch(\PDOException $e) { 
        $this->isConnected = false;
        throw new Exception($e->getMessage());
      }
    }

    public static function getConnect() {
      if (self::$instance == null) self::$instance = new static();
      
      return self::$instance;
    }
  }

  class DatabaseTwigLoader implements \Twig_LoaderInterface {
    protected $dbh;
    
    public function __construct( \PDO $dbh ) {
      $this->dbh = $dbh;
    }
    
    public function getSourceContext ( $name ) {
      if (false === $source = $this->getValue( 'source', $name ))
        throw new \Twig_Error_Loader(sprintf('Template "%s" does not exist.', $name));
      return new \Twig_Source( $source, $name );
    }
    
    public function exists( $name ) {
      return $name === $this->getValue( 'template_name', $name );
    }
    
    public function getCacheKey( $name ) {
      return $name;
    }
    
    public function isFresh( $name, $time ) {
      if (false === $lastModified = $this->getValue( 'last_modified', $name ))
        return false;
      return $lastModified <= $time;
    }
    
    protected function getValue( $column, $name ) {
      $sth = $this->dbh->prepare('SELECT '.$column.' FROM templates WHERE template_name = :name');
      $sth->execute( [':name' => (string)$name] );
      return $sth->fetchColumn();
    }
  }
