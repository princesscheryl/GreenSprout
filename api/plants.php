<?php
session_start();
require_once '../db/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get_plant':
        $plantId = $_GET['plant_id'] ?? 0;
        $stmt = $conn->prepare("SELECT * FROM user_plants WHERE user_plant_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $plantId, $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $plant = $result->fetch_assoc();
        
        if ($plant) {
            echo json_encode(['status' => 'success', 'plant' => $plant]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Plant not found']);
        }
        break;

    case 'update_plant':
        $plantId = $_POST['plant_id'] ?? 0;
        $name = $_POST['name'] ?? '';
        $type = $_POST['type'] ?? '';
        $wateringFrequency = $_POST['watering_frequency'] ?? 0;

        $stmt = $conn->prepare("UPDATE user_plants SET nickname = ?, plant_type = ?, watering_frequency = ? WHERE user_plant_id = ? AND user_id = ?");
        $stmt->bind_param("ssiii", $name, $type, $wateringFrequency, $plantId, $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Plant updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update plant']);
        }
        break;

    case 'log_growth':
        $plantId = $_POST['plant_id'] ?? 0;
        $height = $_POST['height'] ?? null;
        $hasNewLeaves = isset($_POST['has_new_leaves']) ? 1 : 0;
        $hasFlowers = isset($_POST['has_flowers']) ? 1 : 0;
        $hasFruits = isset($_POST['has_fruits']) ? 1 : 0;
        $wetnessLevel = $_POST['wetness_level'] ?? null;
        $isWilting = isset($_POST['is_wilting']) ? 1 : 0;
        $isFlaccid = isset($_POST['is_flaccid']) ? 1 : 0;
        $notes = $_POST['notes'] ?? '';
        $photoUrl = null;

        // Handle photo upload if present
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/growth/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileExtension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $newFileName = uniqid('growth_') . '.' . $fileExtension;
            $uploadPath = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                $photoUrl = 'uploads/growth/' . $newFileName;
            }
        }

        try {
            $stmt = $conn->prepare("
                INSERT INTO growth_tracking (
                    user_plant_id, height, has_new_leaves, has_flowers, 
                    has_fruits, wetness_level, is_wilting, is_flaccid, 
                    photo_url, notes
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->bind_param(
                "idiiisiibs", 
                $plantId, $height, $hasNewLeaves, $hasFlowers, 
                $hasFruits, $wetnessLevel, $isWilting, $isFlaccid, 
                $photoUrl, $notes
            );
            
            if ($stmt->execute()) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Growth logged successfully'
                ]);
            } else {
                throw new Exception("Database error: " . $stmt->error);
            }
        } catch (Exception $e) {
            error_log("Growth Log Error: " . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to log growth: ' . $e->getMessage()
            ]);
        }
        break;

    case 'log_care':
        $plantId = $_POST['plant_id'] ?? 0;
        $careTypes = $_POST['care_type'] ?? [];
        $currentDate = date('Y-m-d H:i:s');
        
        try {
            $conn->begin_transaction();
            
            // Update the plant's care history
            foreach ($careTypes as $careType) {
                $stmt = $conn->prepare("INSERT INTO care_logs (user_plant_id, care_type, care_date) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $plantId, $careType, $currentDate);
                $stmt->execute();
            }
            
            // Update last_watered date if watering was performed
            if (in_array('watering', $careTypes)) {
                $stmt = $conn->prepare("UPDATE user_plants SET last_watered = ? WHERE user_plant_id = ? AND user_id = ?");
                $stmt->bind_param("sii", $currentDate, $plantId, $_SESSION['user_id']);
                $stmt->execute();
            }
            
            // Update last_fertilized date if fertilizing was performed
            if (in_array('fertilizing', $careTypes)) {
                $stmt = $conn->prepare("UPDATE user_plants SET last_fertilized = ? WHERE user_plant_id = ? AND user_id = ?");
                $stmt->bind_param("sii", $currentDate, $plantId, $_SESSION['user_id']);
                $stmt->execute();
            }
            
            $conn->commit();
            echo json_encode([
                'status' => 'success',
                'message' => 'Care logged successfully'
            ]);
            
        } catch (Exception $e) {
            $conn->rollback();
            error_log("Care Log Error: " . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to log care: ' . $e->getMessage()
            ]);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}