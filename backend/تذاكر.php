<?php
// Import database connection file
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET requests
if ($method == 'GET') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'] ?? null, FILTER_SANITIZE_NUMBER_INT);

    // Check if id is provided
    if ($id) {
        // SQL query to select ticket by id
        $stmt = $pdo->prepare('SELECT * FROM تذاكر WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $ticket = $stmt->fetch();

        // Check if ticket exists
        if ($ticket) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($ticket);
        } else {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Ticket not found']);
        }
    } else {
        // SQL query to select all tickets
        $stmt = $pdo->prepare('SELECT * FROM تذاكر');
        $stmt->execute();
        $tickets = $stmt->fetchAll();

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($tickets);
    }
}

// Handle POST requests
if ($method == 'POST') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $title = filter_var($data['title'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'] ?? null, FILTER_SANITIZE_STRING);

    // Check if required fields are provided
    if (!$title || !$description) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // SQL query to insert new ticket
    $stmt = $pdo->prepare('INSERT INTO تذاكر (title, description) VALUES (:title, :description)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Ticket created successfully']);
}

// Handle PUT requests
if ($method == 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $id = filter_var($data['id'] ?? null, FILTER_SANITIZE_NUMBER_INT);
    $title = filter_var($data['title'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'] ?? null, FILTER_SANITIZE_STRING);

    // Check if required fields are provided
    if (!$id || !$title || !$description) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // SQL query to update ticket
    $stmt = $pdo->prepare('UPDATE تذاكر SET title = :title, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Ticket updated successfully']);
}

// Handle DELETE requests
if ($method == 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $id = filter_var($data['id'] ?? null, FILTER_SANITIZE_NUMBER_INT);

    // Check if id is provided
    if (!$id) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // SQL query to delete ticket
    $stmt = $pdo->prepare('DELETE FROM تذاكر WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Ticket deleted successfully']);
}