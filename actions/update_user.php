<?php
session_start();
require_once '../db/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $is_active = $_POST['is_active'];
    
    try {
        $stmt = $conn->prepare("
            UPDATE users 
            SET username = ?, email = ?, role = ?, is_active = ?
            WHERE user_id = ?
        ");
        $stmt->bind_param("sssii", $username, $email, $role, $is_active, $user_id);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            throw new Exception('Failed to update user');
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} 