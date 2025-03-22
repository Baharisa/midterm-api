<?php
// CORS headers for API accessibility
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');

// Preflight request handler
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include database and model
require_once '../../config/Database.php';
require_once '../../models/Quote.php';

// Initialize DB and model
$database = new Database();
$db = $database->connect();
$quote = new Quote($db);

// Get HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// Route: GET
if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $quote->id = (int)$_GET['id'];
        $result = $quote->read_single();
    } else {
        $result = $quote->read();
    }

    if ($result && $result->rowCount() > 0) {
        $quotes_arr = [];

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $quotes_arr[] = $row;
        }

        echo json_encode($quotes_arr);
    } else {
        echo json_encode(["message" => "No Quotes Found"]);
    }
}

// Route: POST
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->quote) && !empty($data->author_id) && !empty($data->category_id)) {
        $quote->quote = htmlspecialchars(strip_tags($data->quote));
        $quote->author_id = (int)$data->author_id;
        $quote->category_id = (int)$data->category_id;

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

// Route: PUT
elseif ($method === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id) && !empty($data->quote) && !empty($data->author_id) && !empty($data->category_id)) {
        $quote->id = (int)$data->id;
        $quote->quote = htmlspecialchars(strip_tags($data->quote));
        $quote->author_id = (int)$data->author_id;
        $quote->category_id = (int)$data->category_id;

        if ($quote->update()) {
            echo json_encode(["message" => "Quote Updated"]);
        } else {
            echo json_encode(["message" => "Failed to Update Quote"]);
        }
    } else {
        echo json_encode(["message" => "Missing Required Parameters"]);
    }
}

// Route: DELETE
elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id)) {
        $quote->id = (int)$data->id;

        if ($quote->delete()) {
            echo json_encode(["message" => "Quote Deleted"]);
        } else {
            echo json_encode(["message" => "Failed to Delete Quote"]);
        }
    } else {
        echo json_encode(["message" => "Missing Required Parameters"]);
    }
}

// Route: unsupported
else {
    http_response_code(405);
    echo json_encode(["message" => "Method Not Allowed"]);
}
