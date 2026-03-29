<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// CONFIGURATION
$servername = "sql111.infinityfree.com";
$username = "if0_41276255";
$password = "Mm7739851898727";
$dbname = "if0_41276255_demo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error"]));
}

$method = $_SERVER['REQUEST_METHOD'];

// 1. GET ALL (FOR UNLOCKING)
if ($method === 'GET') {
    $result = $conn->query("SELECT * FROM users");
    $data = [];
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
} 

// 2. POST (SAVE NEW MESSAGE)
elseif ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $name = $conn->real_escape_string($input['name']);
    $key  = $conn->real_escape_string($input['secret_key']);
    $msg  = $conn->real_escape_string($input['message']);
    
    $sql = "INSERT INTO users (name, secret_key, message) VALUES ('$name', '$key', '$msg')";
    if($conn->query($sql)) echo json_encode(["status" => "success"]);
}

// 3. DELETE (SELF-DESTRUCT)
elseif ($method === 'DELETE') {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $conn->query("DELETE FROM users WHERE id = $id");
        echo json_encode(["status" => "deleted"]);
    }
}

$conn->close();
?>