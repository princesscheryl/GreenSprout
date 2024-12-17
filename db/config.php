<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session with settings
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_lifetime', 3600);
    ini_set('session.cookie_secure', 0);
    ini_set('session.cookie_httponly', 1);

    session_set_cookie_params([
        'lifetime' => 3600,
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true
    ]);

    session_start();
}

// Database connection configuration
$servername = "localhost";
$username = "princess.donkor";
$password = "asiedu@2003";
$dbname = "webtech_fall2024_princess_donkor";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        error_log("Database connection failed: " . $conn->connect_error);
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    error_log("Database connected successfully");

    // Set the character set to utf8mb4
    if (!$conn->set_charset("utf8mb4")) {
        error_log("Charset setting failed: " . $conn->error);
        throw new Exception("Error setting charset: " . $conn->error);
    }

} catch (Exception $e) {
    error_log("Database Error: " . $e->getMessage());
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    } else {
        echo "Database connection failed. Please try again later.";
    }
    exit();
}
?>