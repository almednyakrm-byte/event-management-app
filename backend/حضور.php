<?php
// Import database connection
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
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    
    // Check if id is provided
    if ($id) {
        // SQL query structure: Select by id
        $stmt = $pdo->prepare('SELECT * FROM حضور WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $attendance = $stmt->fetch();
        
        // Output processing
        if ($attendance) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($attendance);
        } else {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Attendance not found']);
        }
    } else {
        // SQL query structure: Select all
        $stmt = $pdo->prepare('SELECT * FROM حضور');
        $stmt->execute();
        $attendances = $stmt->fetchAll();
        
        // Output processing
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($attendances);
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
    
    // Read input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $date = filter_var($input['date'] ?? null, FILTER_VALIDATE_DATE);
    $student_id = filter_var($input['student_id'] ?? null, FILTER_VALIDATE_INT);
    $status = filter_var($input['status'] ?? null, FILTER_VALIDATE_INT);
    
    // Check if input is valid
    if (!$date || !$student_id || !$status) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }
    
    // SQL query structure: Insert
    $stmt = $pdo->prepare('INSERT INTO حضور (date, student_id, status) VALUES (:date, :student_id, :status)');
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':status', $status);
    $stmt->execute();
    
    // Output processing
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Attendance created successfully']);
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
    
    // Read input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    $date = filter_var($input['date'] ?? null, FILTER_VALIDATE_DATE);
    $student_id = filter_var($input['student_id'] ?? null, FILTER_VALIDATE_INT);
    $status = filter_var($input['status'] ?? null, FILTER_VALIDATE_INT);
    
    // Check if input is valid
    if (!$id || !$date || !$student_id || !$status) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }
    
    // SQL query structure: Update
    $stmt = $pdo->prepare('UPDATE حضور SET date = :date, student_id = :student_id, status = :status WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':status', $status);
    $stmt->execute();
    
    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Attendance updated successfully']);
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
    
    // Read input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    
    // Check if input is valid
    if (!$id) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }
    
    // SQL query structure: Delete
    $stmt = $pdo->prepare('DELETE FROM حضور WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Attendance deleted successfully']);
}