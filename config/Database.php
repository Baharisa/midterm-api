<?php
class Database {
    // Render.com PostgreSQL connection details
    private $host = 'dpg-cv6aourqf0us73f1958g-a.ohio-postgres.render.com';  // Full hostname from Render
    private $port = '5432';
    private $db_name = 'midterm1';
    private $username = 'midterm1_user';
    private $password = 'tooRPlrHx3vuiQhipRRIZZu52evdiMr3';
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
        }

        return $this->conn;
    }
}
?>
