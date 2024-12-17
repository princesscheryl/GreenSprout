<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debugging session data
error_log("Session data on dashboard: " . print_r($_SESSION, true));

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    error_log("No user_id in session, redirecting to login");
    header('Location: ../view/user_register.php');
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Include database configuration
require_once '../db/config.php';


$userId = $_SESSION['user_id'];     
try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ? AND is_active = 1");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    // Get user's plants
    $plantsStmt = $conn->prepare("
        SELECT * FROM user_plants 
        WHERE user_id = ? 
        AND status = 'Active'
        ORDER BY nickname ASC
    ");
    $plantsStmt->bind_param("i", $userId);
    $plantsStmt->execute();
    $plants = $plantsStmt->get_result()->fetch_all(MYSQLI_ASSOC);

    if (!$user) {
        error_log("User not found or inactive, destroying session");
        session_destroy();
        session_write_close();
        header('Location: ../view/user_register.php');
        exit();
    }
} catch (Exception $e) {
    error_log("Dashboard error: " . $e->getMessage());
    header('Location: ../view/user_register.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - GreenSprout</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../assets/css/dashboard_styles.css">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="index2.php" style="text-decoration: none; color: inherit;">
                <span class="logo-text">GreenSprout</span>
            </a>
            <i class="ri-arrow-left-s-line toggle-btn"></i>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li>
                    <a href="user_dashboard.php" class="nav-button active">
                        <i class="ri-dashboard-3-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="community.php" class="nav-link">
                        <i class="ri-group-line"></i>
                        <span>Community</span>
                    </a>
                </li>
                <li>
                    <a href="plant_manager.php" class="nav-link">
                        <i class="ri-leaf-line"></i>
                        <span>Plant Manager</span>
                    </a>
                </li>
                <li>
                    <a href="../actions/logout.php" class="nav-link">
                        <i class="ri-logout-circle-line"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main>
        
        <div class="dashboard-grid">
            <!-- Weather Widget -->
            <div class="weather-widget">
                <h2>Local Weather</h2>
                <div id="weather-info">
                    <p>Unable to load weather data.</p>
                    <a href="#" class="retry-btn" onclick="initializeWeather()"><i class="ri-refresh-line"></i> retry</a>
                </div>
            </div>

            <!-- Plant Care Reminders -->
            <div class="care-reminders">
                <h2>Plant Care Reminders</h2>
                <div id="reminders-list">
                    <p>All plants are taken care of!</p>
                </div>
                <button class="log-care-btn" onclick="openCareLogModal()"><i class="ri-add-line"></i> Log Plant Care</button>
            </div>

            <!-- Growing Streak -->
            <div class="growing-streak">
                <h2>Your Growing Streak</h2>
                <div id="streak-info">
                    <div class="streak-count"><i class="ri-fire-fill"></i><span>0 days</span></div>
                </div>
            </div>

            <!-- Plants -->
            <div class="your-plants">
                <h2>Your Plants</h2>
                <?php if (empty($plants)): ?>
                    <p>You haven't added any plants yet. Start tracking your garden!</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($plants as $plant): ?>
                            <li><?php echo htmlspecialchars($plant['nickname'] ?? $plant['name']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <button class="add-plant-btn" onclick="openAddPlantModal()"><i class="ri-add-line"></i> Add New Plant</button>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 GreenSprout. All rights reserved.</p>
    </footer>

    <script src="../assets/js/dashboard.js"></script>

    <!-- Care Log Modal -->
    <div id="care-log-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Log Plant Care</h2>
            <form id="care-log-form">
                <div class="form-group">
                    <label for="plant-select">Select Plant</label>
                    <select id="plant-select" name="plant_id" required>
                        <?php if (!empty($plants)): ?>
                            <?php foreach ($plants as $plant): ?>
                                <option value="<?php echo $plant['user_plant_id']; ?>">
                                    <?php echo htmlspecialchars($plant['nickname'] ?? $plant['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">No plants available</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="care-options">
                    <label><input type="checkbox" name="care_type[]" value="watering"> Watering</label>
                    <label><input type="checkbox" name="care_type[]" value="fertilizing"> Fertilizing</label>
                    <label><input type="checkbox" name="care_type[]" value="pruning"> Pruning</label>
                </div>
                <button type="submit" class="submit-button">
                    <span class="button-text">Log Care</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Add Plant Modal -->
    <div id="add-plant-modal" class="modal">
        <div class="modal-content">
            <button class="close" aria-label="Close modal">&times;</button>
            <h2>Add New Plant</h2>
            <form id="add-plant-form" method="POST">
                <div class="form-group">
                    <label for="plant-name">Plant Name</label>
                    <input type="text" id="plant-name" name="name" required 
                           placeholder="Enter plant name">
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
                        <input type="number" id="watering-frequency" name="watering_frequency" 
                               min="1" value="7" required>
                        <span class="input-suffix">days</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="plant-notes">Notes (optional)</label>
                    <textarea id="plant-notes" name="notes" rows="3"
                              placeholder="Add any special care instructions or notes about your plant"></textarea>
                </div>
                <button type="submit" class="submit-button">
                    <span class="button-text">Add Plant</span>
                </button>
            </form>
        </div>
    </div>
</body>
</html>
