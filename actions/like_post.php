<?php
session_start();
require_once '../db/config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$post_id = $data['post_id'] ?? 0;
$user_id = $_SESSION['user_id'];

try {
    // Check if user already liked the post
    $check_stmt = $conn->prepare("SELECT * FROM post_likes WHERE post_id = ? AND user_id = ?");
    $check_stmt->bind_param("ii", $post_id, $user_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Unlike the post
        $delete_stmt = $conn->prepare("DELETE FROM post_likes WHERE post_id = ? AND user_id = ?");
        $delete_stmt->bind_param("ii", $post_id, $user_id);
        $delete_stmt->execute();

        // Update likes count in posts table
        $update_stmt = $conn->prepare("UPDATE posts SET likes = likes - 1 WHERE post_id = ?");
        $update_stmt->bind_param("i", $post_id);
        $update_stmt->execute();
    } else {
        // Like the post
        $insert_stmt = $conn->prepare("INSERT INTO post_likes (post_id, user_id) VALUES (?, ?)");
        $insert_stmt->bind_param("ii", $post_id, $user_id);
        $insert_stmt->execute();

        // Update likes count in posts table
        $update_stmt = $conn->prepare("UPDATE posts SET likes = likes + 1 WHERE post_id = ?");
        $update_stmt->bind_param("i", $post_id);
        $update_stmt->execute();
    }

    // Get updated likes count
    $count_stmt = $conn->prepare("SELECT likes FROM posts WHERE post_id = ?");
    $count_stmt->bind_param("i", $post_id);
    $count_stmt->execute();
    $likes_result = $count_stmt->get_result()->fetch_assoc();

    echo json_encode([
        'status' => 'success',
        'likes' => $likes_result['likes']
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} 