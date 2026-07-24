<?php
// Start the session to store user data
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, return a JSON response with their user data
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $response = array('status' => 'logged_in', 'user_id' => $user_id, 'username' => $username);
    echo json_encode($response);
    exit;
}

// Check if the user is trying to register or login
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Check if the user is trying to register
    if ($action == 'register') {
        // Check if all required fields are present
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Check if the username and email are not empty
            if (!empty($username) && !empty($email)) {
                // Check if the username and email are valid
                if (preg_match('/^[a-zA-Z0-9]+$/', $username) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    // Check if the username is not already taken
                    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $response = array('status' => 'error', 'message' => 'Username already taken');
                        echo json_encode($response);
                        exit;
                    }

                    // Check if the email is not already taken
                    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $response = array('status' => 'error', 'message' => 'Email already taken');
                        echo json_encode($response);
                        exit;
                    }

                    // Hash the password
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);

                    // Insert the user into the database
                    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $username, $email, $password_hash);
                    $stmt->execute();

                    // Return a JSON response with a success message
                    $response = array('status' => 'success', 'message' => 'User registered successfully');
                    echo json_encode($response);
                    exit;
                } else {
                    // Return a JSON response with an error message
                    $response = array('status' => 'error', 'message' => 'Invalid username or email');
                    echo json_encode($response);
                    exit;
                }
            } else {
                // Return a JSON response with an error message
                $response = array('status' => 'error', 'message' => 'Username and email are required');
                echo json_encode($response);
                exit;
            }
        } else {
            // Return a JSON response with an error message
            $response = array('status' => 'error', 'message' => 'All fields are required');
            echo json_encode($response);
            exit;
        }
    }

    // Check if the user is trying to login
    elseif ($action == 'login') {
        // Check if all required fields are present
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Check if the username and password are not empty
            if (!empty($username) && !empty($password)) {
                // Check if the username and password are valid
                $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    if (password_verify($password, $row['password'])) {
                        // Log the user in
                        $_SESSION['user_id'] = $row['id'];
                        $_SESSION['username'] = $username;

                        // Return a JSON response with a success message
                        $response = array('status' => 'success', 'message' => 'User logged in successfully');
                        echo json_encode($response);
                        exit;
                    } else {
                        // Return a JSON response with an error message
                        $response = array('status' => 'error', 'message' => 'Invalid password');
                        echo json_encode($response);
                        exit;
                    }
                } else {
                    // Return a JSON response with an error message
                    $response = array('status' => 'error', 'message' => 'Invalid username');
                    echo json_encode($response);
                    exit;
                }
            } else {
                // Return a JSON response with an error message
                $response = array('status' => 'error', 'message' => 'Username and password are required');
                echo json_encode($response);
                exit;
            }
        } else {
            // Return a JSON response with an error message
            $response = array('status' => 'error', 'message' => 'All fields are required');
            echo json_encode($response);
            exit;
        }
    }

    // Check if the user is trying to logout
    elseif ($action == 'logout') {
        // Log the user out
        session_destroy();

        // Return a JSON response with a success message
        $response = array('status' => 'success', 'message' => 'User logged out successfully');
        echo json_encode($response);
        exit;
    }
}

// If the user is not logged in, return a JSON response with a logged out status
$response = array('status' => 'logged_out');
echo json_encode($response);
exit;
?>