<?php
header('Content-Type: application/json');
include_once 'config/Database.php';

$database = new Database();
$conn = $database->connect();

if ($conn) {
    echo json_encode(['message' => '✅ Connected to PostgreSQL successfully via Render!']);
} else {
    echo json_encode(['message' => '❌ Connection failed.']);
}
?>
