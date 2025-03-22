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
    // Load .env file
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    // Get environment variables
    $this->host = $_ENV['DB_HOST'];
    $this->port = $_ENV['DB_PORT'] ?: '5432';
    $this->db_name = $_ENV['DB_NAME'];
    $this->username = $_ENV['DB_USER'];
    $this->password = $_ENV['DB_PASS'];
  }

  public function connect() {
    $this->conn = null;

    try {
      $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name};sslmode=require";
      $this->conn = new PDO($dsn, $this->username, $this->password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
      echo 'Connection Error: ' . $e->getMessage();
    }

    return $this->conn;
  }
}
?>
