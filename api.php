<?php
//include database
require_once('db_connect.php');
// Set the response headers to indicate JSON content
header('Content-Type: application/json');

//get  people person by name
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['person'])) {
        $name = $_GET['person'];
        $sql = "SELECT * FROM users WHERE name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $persons = [];
        while ($row = $result->fetch_assoc()) {
            $persons[] = $row;
        }
        echo json_encode($persons);
    } else {
        echo json_encode(['message' => 'insert a name to fetch']);
    }
    // Post method to Insert data into the database
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        global $conn;
        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data['name'];
        
        // Insert data into the database
        $sql = "INSERT INTO users (name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $name);
    
    if ($stmt->execute()) {
        echo json_encode(['message' => 'Person created successfully']);
    } else {
        echo json_encode(['message' => 'Failed to create person']);
    }
    //editing/updating person using ID
}elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'];
        $name = $data['name'];
        $sql = "UPDATE users SET name = ? WHERE id = ?";
        $stmt = $conn->prepare($sq);
        $stmt->bind_param('si', $name, $id);
    if ($stmt->execute()) {
        echo json_encode(['message' => 'Person updated successfully']);
    } else {
        echo json_encode(['message' => 'Failed to update person']);
    }
// deleteing person by ID
}elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        parse_str(file_get_contents("php://input"), $data);
        $id = $data['id'];
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
if ($stmt->execute()) {
    echo json_encode(['message' => 'Person deleted successfully']);
} else {
    echo json_encode(['message' => 'Failed to delete person']);
}

}
?>
