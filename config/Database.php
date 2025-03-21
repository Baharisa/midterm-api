<?php
class Database {
  private $host;
  private $port;
  private $db_name;
  private $username;
  private $password;
  private $conn;

  public function __construct() {
    $this->host = getenv('DB_HOST');
    $this->port = getenv('DB_PORT') ?: '5432';
    $this->db_name = getenv('DB_NAME');
    $this->username = getenv('DB_USER');
    $this->password = getenv('DB_PASS');
  }

  public function connect() {
    $this->conn = null;
    try {
      $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name};";
      $this->conn = new PDO($dsn
