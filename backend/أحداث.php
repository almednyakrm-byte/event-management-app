<?php

require_once 'db.php';

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if the user is an admin
if ($method === 'PUT' || $method === 'DELETE') {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
}

// Get the request body
$body = json_decode(file_get_contents('php://input'), true);

// Handle GET requests
if ($method === 'GET') {
    try {
        // Prepare the SQL query
        $stmt = $pdo->prepare('SELECT * FROM events');
        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return the events as JSON
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($events);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle POST requests
if ($method === 'POST') {
    try {
        // Validate the request body
        if (!isset($body['title']) || !isset($body['description'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }
        
        // Sanitize the request body
        $title = htmlspecialchars($body['title']);
        $description = htmlspecialchars($body['description']);
        
        // Prepare the SQL query
        $stmt = $pdo->prepare('INSERT INTO events (title, description) VALUES (:title, :description)');
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        
        // Return the newly created event as JSON
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('id' => $pdo->lastInsertId()));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle PUT requests
if ($method === 'PUT') {
    try {
        // Validate the request body
        if (!isset($body['id']) || !isset($body['title']) || !isset($body['description'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }
        
        // Sanitize the request body
        $id = intval($body['id']);
        $title = htmlspecialchars($body['title']);
        $description = htmlspecialchars($body['description']);
        
        // Prepare the SQL query
        $stmt = $pdo->prepare('UPDATE events SET title = :title, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        
        // Return a success message as JSON
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Event updated successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle DELETE requests
if ($method === 'DELETE') {
    try {
        // Validate the request body
        if (!isset($body['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }
        
        // Sanitize the request body
        $id = intval($body['id']);
        
        // Prepare the SQL query
        $stmt = $pdo->prepare('DELETE FROM events WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Return a success message as JSON
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Event deleted successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}