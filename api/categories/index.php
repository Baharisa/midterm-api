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
include_once '../../models/Category.php';

$database = new Database();
$db = $database->connect();
$category = new Category($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $category->id = $_GET['id'];
        $result = $category->read_single();
    } else {
        $result = $category->read();
    }

    if ($result->rowCount() > 0) {
        $categories_arr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $categories_arr[] = $row;
        }
        echo json_encode($categories_arr);
    } else {
        echo json_encode(["message" => "category_id Not Found"]);
    }
}
?>
