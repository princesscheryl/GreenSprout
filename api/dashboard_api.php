<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // Ensure session starts at the top
header('Content-Type: application/json');

// Debugging session data
error_log("Session Data: " . print_r($_SESSION, true));

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
    exit();
}

// Database connection
require_once '../db/config.php';

$response = ['status' => 'error', 'message' => 'Invalid action'];
$action = $_GET['action'] ?? null;

try {
    switch ($action) {
        // ------------------- Reminders -------------------
        case 'reminders':
            $userId = $_SESSION['user_id'];
            $stmt = $conn->prepare("
                SELECT nickname, care_type, due_date 
                FROM plant_reminders 
                WHERE user_id = ? AND status = 'pending'
                ORDER BY due_date ASC
            ");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $reminders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $response = ['status' => 'success', 'reminders' => $reminders];
            break;

        // ------------------- Log Plant Care -------------------
        case 'log_care':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate session
                $userId = $_SESSION['user_id'] ?? null;
                if (!$userId) {
                    http_response_code(401);
                    error_log("Log Care Error: User not authenticated.");
                    echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
                    exit();
                }

                // Validate input
                $plantId = $_POST['plant_id'] ?? null;
                $careTypes = $_POST['care_type'] ?? [];
                error_log("Log Care Inputs - Plant ID: $plantId, Care Types: " . print_r($careTypes, true));

                if (empty($plantId) || empty($careTypes)) {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => 'Plant ID and care type are required']);
                    exit();
                }

                try {
                    $conn->begin_transaction();

                    // Update care columns
                    foreach ($careTypes as $careType) {
                        if ($careType === 'watering') {
                            $stmt = $conn->prepare("UPDATE user_plants SET last_watered = NOW() WHERE user_plant_id = ? AND user_id = ?");
                        } elseif ($careType === 'fertilizing') {
                            $stmt = $conn->prepare("UPDATE user_plants SET last_fertilized = NOW() WHERE user_plant_id = ? AND user_id = ?");
                        }

                        if (isset($stmt)) {
                            $stmt->bind_param("ii", $plantId, $userId);
                            if (!$stmt->execute()) {
                                throw new Exception("SQL Execution Error: " . $stmt->error);
                            }
                        }
                    }

                    // Insert streak for today
                    $stmt = $conn->prepare("
                        INSERT IGNORE INTO user_streaks (user_id, streak_date) 
                        VALUES (?, CURDATE())
                    ");
                    $stmt->bind_param("i", $userId);
                    if (!$stmt->execute()) {
                        throw new Exception("Streak Insertion Error: " . $stmt->error);
                    }

                    // Calculate streak
                    $streak = calculateStreak($conn, $userId);

                    $conn->commit();
                    $response = ['status' => 'success', 'message' => 'Plant care logged successfully', 'streak' => $streak];
                } catch (Exception $e) {
                    $conn->rollback();
                    error_log("Log Care Error: " . $e->getMessage());
                    http_response_code(500);
                    $response = ['status' => 'error', 'message' => 'Failed to log care: ' . $e->getMessage()];
                }
            }
            break;

        // ------------------- Add Plant -------------------
        case 'add_plant':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userId = $_SESSION['user_id'];
                $name = trim($_POST['name'] ?? '');
                $type = trim($_POST['type'] ?? '');
                $wateringFrequency = intval($_POST['watering_frequency'] ?? 7);
                $notes = trim($_POST['notes'] ?? '');
                $plantedDate = date('Y-m-d H:i:s');

                if (empty($name) || empty($type)) {
                    throw new Exception('Plant name and type are required');
                }

                $stmt = $conn->prepare("
                    INSERT INTO user_plants (user_id, nickname, plant_type, watering_frequency, planted_date, status, notes)
                    VALUES (?, ?, ?, ?, ?, 'Active', ?)
                ");
                $stmt->bind_param("ississ", $userId, $name, $type, $wateringFrequency, $plantedDate, $notes);

                if ($stmt->execute()) {
                    $response = ['status' => 'success', 'message' => 'Plant added successfully'];
                } else {
                    throw new Exception('Failed to add plant');
                }
            }
            break;

        default:
            $response = ['status' => 'error', 'message' => 'Invalid API action'];
            break;
    }
} catch (Exception $e) {
    error_log("API Error: " . $e->getMessage());
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

// ------------------- Function to Calculate Streak -------------------
function calculateStreak($conn, $userId) {
    try {
        $sql = "SELECT streak_date FROM user_streaks WHERE user_id = ? ORDER BY streak_date DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $streak = 0;
        $today = new DateTime('today');
        $lastDate = null;

        foreach ($result as $row) {
            $currentDate = new DateTime($row['streak_date']);

            if ($lastDate === null) {
                if ($currentDate == $today) {
                    $streak = 1;
                } else {
                    break;
                }
            } else {
                $diff = $lastDate->diff($currentDate)->days;
                if ($diff === 1) {
                    $streak++;
                } else {
                    break;
                }
            }
            $lastDate = $currentDate;
        }
        return $streak;
    } catch (Exception $e) {
        error_log("Streak Calculation Error: " . $e->getMessage());
        return 0;
    }
}

echo json_encode($response);
exit();
?>