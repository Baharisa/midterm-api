<?php
class Quote {
    private $conn;
    private $table = 'quotes';

    public $id;
    public $quote;
    public $author_id;
    public $category_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT q.id, q.quote, a.author, c.category 
                  FROM " . $this->table . " q
                  JOIN authors a ON q.author_id = a.id
                  JOIN categories c ON q.category_id = c.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function read_single() {
        $query = "SELECT q.id, q.quote, a.author, c.category 
                  FROM " . $this->table . " q
                  JOIN authors a ON q.author_id = a.id
                  JOIN categories c ON q.category_id = c.id
                  WHERE q.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " (quote, author_id, category_id) 
                  VALUES (:quote, :author_id, :category_id) RETURNING id";
        $stmt = $this->conn->prepare($query);

        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);

        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['id'];  // Return the created quote ID
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET quote = :quote, author_id = :author_id, category_id = :category_id 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
}
?>
