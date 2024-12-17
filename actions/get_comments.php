<?php
session_start();
require_once '../db/config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$post_id = $_GET['post_id'] ?? 0;

try {
    $stmt = $conn->prepare("
        SELECT 
            pc.*,
            u.username
        FROM post_comments pc
        JOIN users u ON pc.user_id = u.user_id
        WHERE pc.post_id = ?
        ORDER BY pc.created_at DESC
    ");
    
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $comments = $result->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode([
        'status' => 'success',
        'comments' => $comments
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} 