<?php
session_start();
require_once '../db/config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$post_id = $data['post_id'] ?? 0;
$content = $data['content'] ?? '';
$user_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("
        INSERT INTO post_comments (post_id, user_id, content)
        VALUES (?, ?, ?)
    ");
    
    $stmt->bind_param("iis", $post_id, $user_id, $content);
    
    if ($stmt->execute()) {
        // Get the new comment with username
        $comment_id = $stmt->insert_id;
        $select_stmt = $conn->prepare("
            SELECT 
                pc.*,
                u.username
            FROM post_comments pc
            JOIN users u ON pc.user_id = u.user_id
            WHERE pc.comment_id = ?
        ");
        
        $select_stmt->bind_param("i", $comment_id);
        $select_stmt->execute();
        $result = $select_stmt->get_result();
        $comment = $result->fetch_assoc();
        
        echo json_encode([
            'status' => 'success',
            'comment' => $comment
        ]);
    } else {
        throw new Exception("Failed to add comment");
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} 