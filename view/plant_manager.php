<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../view/user_register.php');
    exit();
}

// Include database configuration
require_once '../db/config.php';

// Fetch user's plants
$userId = $_SESSION['user_id'];
try {
    $stmt = $conn->prepare("
        SELECT * FROM user_plants 
        WHERE user_id = ? 
        AND status = 'Active'
        ORDER BY nickname ASC
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $plants = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    error_log("Error fetching plants: " . $e->getMessage());
    $plants = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Manager - GreenSprout</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../assets/css/dashboard_styles.css">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <span class="logo-text">GreenSprout</span>
            <i class="ri-arrow-left-s-line toggle-btn"></i>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li>
                    <a href="user_dashboard.php" class="nav-link">
                        <i class="ri-dashboard-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="community.php" class="nav-link">
                        <i class="ri-team-line"></i>
                        <span>Community</span>
                    </a>
                </li>
                <li>
                    <a href="plant_manager.php" class="nav-link active">
                        <i class="ri-leaf-line"></i>
                        <span>Plant Manager</span>
                    </a>
                </li>
                <li>
                    <a href="../actions/logout.php" class="nav-link">
                        <i class="ri-logout-box-line"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main>
        <div class="content-wrapper">
            <h1>Plant Manager</h1>
            
            <div class="plants-grid">
                <?php if (empty($plants)): ?>
                    <p class="no-plants">You haven't added any plants yet. Start your garden by adding a plant!</p>
                <?php else: ?>
                    <?php foreach ($plants as $plant): ?>
                        <div class="plant-card" data-plant-id="<?php echo $plant['user_plant_id']; ?>">
                            <div class="plant-header">
                                <h3><?php echo htmlspecialchars($plant['nickname']); ?></h3>
                                <span class="plant-type"><?php echo htmlspecialchars($plant['plant_type']); ?></span>
                            </div>
                            <div class="plant-details">
                                <?php
                                // Get latest growth tracking info
                                $trackingStmt = $conn->prepare("
                                    SELECT * FROM growth_tracking 
                                    WHERE user_plant_id = ? 
                                    ORDER BY recorded_date DESC 
                                    LIMIT 1
                                ");
                                $trackingStmt->bind_param("i", $plant['user_plant_id']);
                                $trackingStmt->execute();
                                $growth = $trackingStmt->get_result()->fetch_assoc();

                                // Get planting schedule info
                                $scheduleStmt = $conn->prepare("
                                    SELECT current_stage FROM planting_schedules 
                                    WHERE user_id = ? AND plant_name = ?
                                    ORDER BY created_at DESC LIMIT 1
                                ");
                                $scheduleStmt->bind_param("is", $_SESSION['user_id'], $plant['nickname']);
                                $scheduleStmt->execute();
                                $schedule = $scheduleStmt->get_result()->fetch_assoc();
                                ?>

                                <div class="growth-info">
                                    <p><i class="ri-seedling-line"></i> Stage: 
                                        <?php echo htmlspecialchars($schedule['current_stage'] ?? 'Not set'); ?>
                                    </p>
                                    <?php if ($growth && $growth['height']): ?>
                                        <p><i class="ri-ruler-line"></i> Height: <?php echo htmlspecialchars($growth['height']); ?> cm</p>
                                    <?php endif; ?>
                                    <?php if ($growth): ?>
                                        <div class="plant-status">
                                            <?php if ($growth['has_new_leaves']): ?>
                                                <span class="status-tag"><i class="ri-leaf-line"></i> New Leaves</span>
                                            <?php endif; ?>
                                            <?php if ($growth['has_flowers']): ?>
                                                <span class="status-tag"><i class="ri-flower-line"></i> Flowering</span>
                                            <?php endif; ?>
                                            <?php if ($growth['has_fruits']): ?>
                                                <span class="status-tag"><i class="ri-plant-line"></i> Fruiting</span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="care-history">
                                    <?php if ($plant['last_watered']): ?>
                                        <p class="care-date">
                                            <i class="ri-drop-line"></i> Last watered: 
                                            <span><?php echo date('M d, Y', strtotime($plant['last_watered'])); ?></span>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <?php if ($plant['last_fertilized']): ?>
                                        <p class="care-date">
                                            <i class="ri-seedling-line"></i> Last fertilized: 
                                            <span><?php echo date('M d, Y', strtotime($plant['last_fertilized'])); ?></span>
                                        </p>
                                    <?php endif; ?>

                                    <?php
                                    // Get latest care log
                                    $careStmt = $conn->prepare("
                                        SELECT care_type, care_date 
                                        FROM care_logs 
                                        WHERE user_plant_id = ? 
                                        ORDER BY care_date DESC 
                                        LIMIT 1
                                    ");
                                    $careStmt->bind_param("i", $plant['user_plant_id']);
                                    $careStmt->execute();
                                    $latestCare = $careStmt->get_result()->fetch_assoc();
                                    
                                    if ($latestCare): ?>
                                        <p class="care-date">
                                            <i class="ri-heart-line"></i> Last care: 
                                            <span><?php echo date('M d, Y', strtotime($latestCare['care_date'])); ?></span>
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <p><i class="ri-drop-line"></i> Water every <?php echo htmlspecialchars($plant['watering_frequency']); ?> days</p>
                                <p><i class="ri-calendar-line"></i> Planted: <?php echo date('M d, Y', strtotime($plant['planted_date'])); ?></p>
                            </div>
                            <div class="plant-actions">
                                <button class="edit-plant" data-plant-id="<?php echo $plant['user_plant_id']; ?>">
                                    <i class="ri-edit-line"></i> Edit
                                </button>
                                <button class="log-care" data-plant-id="<?php echo $plant['user_plant_id']; ?>">
                                    <i class="ri-heart-line"></i> Log Care
                                </button>
                                <button class="log-growth" data-plant-id="<?php echo $plant['user_plant_id']; ?>">
                                    <i class="ri-leaf-line"></i> Log Growth
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <button class="add-plant-btn" onclick="openAddPlantModal()">
                <i class="ri-add-line"></i> Add New Plant
            </button>
        </div>
    </main>

    <!-- Add Plant Modal -->
    <div id="add-plant-modal" class="modal">
        <div class="modal-content">
            <button class="close" aria-label="Close modal">&times;</button>
            <h2>Add New Plant</h2>
            <form id="add-plant-form" method="POST">
                <div class="form-group">
                    <label for="plant-name">Plant Name</label>
                    <input type="text" id="plant-name" name="name" required placeholder="Enter plant name">
                </div>
                <div class="form-group">
                    <label for="plant-type">Plant Type</label>
                    <select id="plant-type" name="plant_type" required>
                        <option value="">Select Type</option>
                        <option value="Indoor">Indoor Plant</option>
                        <option value="Outdoor">Outdoor Plant</option>
                        <option value="Herb">Herb/Vegetable</option>
                        <option value="Succulent">Succulent/Cactus</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="watering-frequency">Watering Frequency</label>
                    <div class="input-group">
                        <input type="number" id="watering-frequency" name="watering_frequency" min="1" value="7" required>
                        <span class="input-suffix">days</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="plant-notes">Notes (optional)</label>
                    <textarea id="plant-notes" name="notes" rows="3" placeholder="Add any special care instructions"></textarea>
                </div>
                <button type="submit">Add Plant</button>
            </form>
        </div>
    </div>

    <!-- Care Log Modal -->
    <div id="care-log-modal" class="modal">
        <div class="modal-content">
            <button class="close" aria-label="Close modal">&times;</button>
            <h2>Log Plant Care</h2>
            <form id="care-log-form">
                <input type="hidden" id="care-plant-id" name="plant_id">
                <div class="care-options">
                    <label><input type="checkbox" name="care_type[]" value="watering"> Watering</label>
                    <label><input type="checkbox" name="care_type[]" value="fertilizing"> Fertilizing</label>
                    <label><input type="checkbox" name="care_type[]" value="pruning"> Pruning</label>
                </div>
                <button type="submit">Log Care</button>
            </form>
        </div>
    </div>

    <!-- Edit Plant Modal -->
    <div id="edit-plant-modal" class="modal">
        <div class="modal-content">
            <button class="close" aria-label="Close modal">&times;</button>
            <h2>Edit Plant</h2>
            <form id="edit-plant-form">
                <input type="hidden" id="edit-plant-id" name="plant_id">
                
                <div class="form-group">
                    <label for="edit-plant-name">Plant Name</label>
                    <input type="text" id="edit-plant-name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="edit-plant-type">Plant Type</label>
                    <input type="text" id="edit-plant-type" name="type" required>
                </div>

                <div class="form-group">
                    <label for="edit-watering-frequency">Watering Frequency (days)</label>
                    <input type="number" id="edit-watering-frequency" name="watering_frequency" 
                           min="1" required>
                </div>

                <button type="submit">Update Plant</button>
            </form>
        </div>
    </div>

    <!-- Growth Log Modal -->
    <div id="growth-log-modal" class="modal">
        <div class="modal-content">
            <button class="close" aria-label="Close modal">&times;</button>
            <h2>Log Growth Progress</h2>
            <form id="growth-log-form" enctype="multipart/form-data">
                <input type="hidden" id="growth-plant-id" name="plant_id">
                
                <div class="form-group">
                    <label for="growth-stage">Current Stage</label>
                    <select id="growth-stage" name="growth_stage" required>
                        <option value="Seed">Seed</option>
                        <option value="Sprout">Sprout</option>
                        <option value="Seedling">Seedling</option>
                        <option value="Growing">Growing</option>
                        <option value="Mature">Mature</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="plant-height">Height (cm)</label>
                    <input type="number" id="plant-height" name="height" step="0.1" min="0">
                </div>

                <div class="growth-checkboxes">
                    <label>
                        <input type="checkbox" name="has_new_leaves" value="1">
                        New Leaves
                    </label>
                    <label>
                        <input type="checkbox" name="has_flowers" value="1">
                        Flowers
                    </label>
                    <label>
                        <input type="checkbox" name="has_fruits" value="1">
                        Fruits
                    </label>
                </div>

                <div class="form-group">
                    <label for="wetness-level">Soil Moisture</label>
                    <select id="wetness-level" name="wetness_level">
                        <option value="Dry">Dry</option>
                        <option value="Moist">Moist</option>
                        <option value="Wet">Wet</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="growth-notes">Notes</label>
                    <textarea id="growth-notes" name="notes" rows="3" 
                             placeholder="Add observations about your plant's growth"></textarea>
                </div>

                <button type="submit">Log Growth</button>
            </form>
        </div>
    </div>

    <!-- All your modals here -->
    </div>

    <!-- Move the script tag here, just before closing body tag -->
    <script src="../assets/js/plant_manager.js"></script>
</body>
</html> 