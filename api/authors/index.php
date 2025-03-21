<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../../config/Database.php';
include_once '../../models/Author.php';

$database = new Database();
$db = $database->connect();
$author = new Author($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $author->id = $_GET['id'];
        $result = $author->read_single();
    } else {
        $result = $author->read();
    }

    if ($result->rowCount() > 0) {
        $authors_arr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $authors_arr[] = $row;
        }
        echo json_encode($authors_arr);
    } else {
        echo json_encode(["message" => "author_id Not Found"]);
    }
}
?>
