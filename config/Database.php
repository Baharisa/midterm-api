<?php
class Database {
  private $conn;

  public function connect() {
    $this->conn = null;

    $host = getenv('DB_HOST');
    $port = getenv('DB_PORT') ?: '5432';
    $dbname = getenv('DB_NAME');
    $user = getenv('DB_USER');
    $pass = getenv('DB_PASS');

    try {
      // Add sslmode=require to fix Render PostgreSQL SSL requirement
      $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
      $this->conn = new PDO($dsn, $user, $pass);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      die('Connection Error: ' . $e->getMessage());
    }

    return $this->conn;
  }
}
?>
