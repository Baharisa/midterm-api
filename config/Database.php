<?php
// Load Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

class Database {
  private $host;
  private $port;
  private $db_name;
  private $username;
  private $password;
  private $conn;

  public function __construct() {
    // Load from .env file if available (local dev)
    if (file_exists(__DIR__ . '/../.env')) {
      $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
      $dotenv->load();
    }

    // Use $_ENV first, then getenv() (for Render)
    $this->host     = $_ENV['DB_HOST']     ?? getenv('DB_HOST');
    $this->port     = $_ENV['DB_PORT']     ?? getenv('DB_PORT') ?: '5432';
    $this->db_name  = $_ENV['DB_NAME']     ?? getenv('DB_NAME');
    $this->username = $_ENV['DB_USER']     ?? getenv('DB_USER');
    $this->password = $_ENV['DB_PASS']     ?? getenv('DB_PASS');
  }

  public function connect() {
    $this->conn = null;

    try {
      // SSL mode is required by Render
      $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name};sslmode=require";
      $this->conn = new PDO($dsn, $this->username, $this->password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo 'Connection Error: ' . $e->getMessage();
    }

    return $this->conn;
  }
}
?>
