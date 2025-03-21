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
include_once '../../models/Quote.php';

$database = new Database();
$db = $database->connect();
$quote = new Quote($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $quote->id = $_GET['id'];
        $result = $quote->read_single();
    } else {
        $result = $quote->read();
    }

    if ($result->rowCount() > 0) {
        $quotes_arr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $quotes_arr[] = $row;
        }
        echo json_encode($quotes_arr);
    } else {
        echo json_encode(["message" => "No Quotes Found"]);
    }
}

elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->quote) && !empty($data->author_id) && !empty($data->category_id)) {
        $quote->quote = $data->quote;
        $quote->author_id = $data->author_id;
        $quote->category_id = $data->category_id;

        $newId = $quote->create();
        if ($newId) {
            echo json_encode(["message" => "Quote Created", "id" => $newId]);
        } else {
            echo json_encode(["message" => "Failed to Create Quote"]);
        }
    } else {
        echo json_encode(["message" => "Missing Required Parameters"]);
    }
}

elseif ($method === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id) && !empty($data->quote) && !empty($data->author_id) && !empty($data->category_id)) {
        $quote->id = $data->id;
        $quote->quote = $data->quote;
        $quote->author_id = $data->author_id;
        $quote->category_id = $data->category_id;

        if ($quote->update()) {
            echo json_encode(["message" => "Quote Updated"]);
        } else {
            echo json_encode(["message" => "Failed to Update Quote"]);
        }
    } else {
        echo json_encode(["message" => "Missing Required Parameters"]);
    }
}

elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id)) {
        $quote->id = $data->id;

        if ($quote->delete()) {
            echo json_encode(["message" => "Quote Deleted"]);
        } else {
            echo json_encode(["message" => "Failed to Delete Quote"]);
        }
    } else {
        echo json_encode(["message" => "Missing Required Parameters"]);
    }
}
?>
