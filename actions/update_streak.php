<?php
session_start();
require_once '../db/config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');

try {
    // Insert today's streak
    $sql = "INSERT IGNORE INTO user_streaks (user_id, streak_date) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $today);
    $stmt->execute();

    // Count consecutive days
    $sql = "SELECT COUNT(*) as streak 
            FROM (
                SELECT streak_date
                FROM user_streaks
                WHERE user_id = ?
                AND streak_date >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
                ORDER BY streak_date DESC
            ) as dates
            WHERE DATEDIFF(CURRENT_DATE, streak_date) < 2";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $current_streak = $result->fetch_assoc()['streak'];

    // Update user's streak
    $sql = "UPDATE users SET current_streak = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $current_streak, $user_id);
    $stmt->execute();

    echo json_encode([
        'status' => 'success',
        'streak' => $current_streak
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} 