<?php

namespace Core;

use PDO;
use App\Config;

/**
 * Base Db
 */
class Db
{
  private static $instance = false;
  
  protected $conn;

  public function __construct()
  {
      $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';
      $this->conn = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);
  }

  public static function getInstance()
  {
      if (self::$instance === false) {
          self::$instance = new self();
      }

      return self::$instance;
  }

  public function getConnexion()
  {
      return $this->conn;
  }
}
