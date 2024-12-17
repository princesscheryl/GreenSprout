<?php
session_start();
require_once '../db/config.php';

// Check authentication
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    header('Location: user_register.php');
    exit();
}

// Fetch analytics data
$currentMonth = date('m');
$currentYear = date('Y');

// Get monthly user registrations
$monthlyUsers = $conn->query("
    SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
    FROM users 
    GROUP BY month 
    ORDER BY month DESC 
    LIMIT 12
")->fetch_all(MYSQLI_ASSOC);

// Get user activity data
$userActivity = $conn->query("
    SELECT DATE_FORMAT(last_tracking_date, '%Y-%m-%d') as date, COUNT(*) as count 
    FROM users 
    WHERE last_tracking_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY date
")->fetch_all(MYSQLI_ASSOC);

// Get skill level distribution
$skillDistribution = $conn->query("
    SELECT skill_level, COUNT(*) as count 
    FROM users 
    GROUP BY skill_level
")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenSprout Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin_styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <span class="logo-text">GreenSprout</span>
            <button class="sidebar-collapse" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li class="<?php echo !isset($_GET['view']) ? 'active' : ''; ?>">
                    <a href="admin_dashboard.php">
                        <i class="fas fa-chart-line"></i>
                        <span>Analytics</span>
                    </a>
                </li>
                <li class="<?php echo isset($_GET['view']) && $_GET['view'] === 'users' ? 'active' : ''; ?>">
                    <a href="manage_users.php">
                        <i class="fas fa-users"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li>
                    <a href="../actions/logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <div class="content">
        <header>
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        </header>

        <?php if (!isset($_GET['view'])): ?>
            <!-- Analytics Dashboard -->
            <div class="stats">
                <div class="stat-card">
                    <h3>Total Users</h3>
                    <div class="number"><?php echo $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count']; ?></div>
                    <p>Registered users</p>
                </div>
                <div class="stat-card">
                    <h3>Active Users</h3>
                    <div class="number"><?php echo $conn->query("SELECT COUNT(*) as count FROM users WHERE is_active = 1")->fetch_assoc()['count']; ?></div>
                    <p>Currently active</p>
                </div>
                <div class="stat-card">
                    <h3>New Users (This Month)</h3>
                    <div class="number"><?php echo $conn->query("SELECT COUNT(*) as count FROM users WHERE MONTH(created_at) = $currentMonth AND YEAR(created_at) = $currentYear")->fetch_assoc()['count']; ?></div>
                    <p>Recent registrations</p>
                </div>
            </div>

            <div class="charts-grid">
                <div class="chart-container">
                    <h3>User Growth</h3>
                    <canvas id="userGrowthChart"></canvas>
                </div>
                <div class="chart-container">
                    <h3>Daily Active Users</h3>
                    <canvas id="activeUsersChart"></canvas>
                </div>
                <div class="chart-container">
                    <h3>Skill Level Distribution</h3>
                    <canvas id="skillDistributionChart"></canvas>
                </div>
            </div>

        <?php else: ?>
            <!-- Users Management View -->
            <!-- Your existing users management code -->
        <?php endif; ?>
    </div>

    <script>
        // User Growth Chart
        new Chart(document.getElementById('userGrowthChart'), {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column(array_reverse($monthlyUsers), 'month')); ?>,
                datasets: [{
                    label: 'New Users',
                    data: <?php echo json_encode(array_column(array_reverse($monthlyUsers), 'count')); ?>,
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                plugins: {
                    legend: {
                        labels: { color: '#fff' }
                    },
                    tooltip: {
                        backgroundColor: '#2c2c2c',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#3498db',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { 
                            color: 'rgba(255, 255, 255, 0.1)',
                            drawBorder: false
                        },
                        ticks: { 
                            color: '#fff',
                            font: { size: 12 }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { 
                            color: '#fff',
                            font: { size: 12 },
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });

        // Active Users Chart
        new Chart(document.getElementById('activeUsersChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($userActivity, 'date')); ?>,
                datasets: [{
                    label: 'Daily Active Users',
                    data: <?php echo json_encode(array_column($userActivity, 'count')); ?>,
                    backgroundColor: '#2ecc71',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                plugins: {
                    legend: {
                        labels: { color: '#fff' }
                    },
                    tooltip: {
                        backgroundColor: '#2c2c2c',
                        titleColor: '#fff',
                        bodyColor: '#fff'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { 
                            color: 'rgba(255, 255, 255, 0.1)',
                            drawBorder: false
                        },
                        ticks: { 
                            color: '#fff',
                            font: { size: 12 }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { 
                            color: '#fff',
                            font: { size: 12 },
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });

        // Skill Distribution Chart
        new Chart(document.getElementById('skillDistributionChart'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_column($skillDistribution, 'skill_level')); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_column($skillDistribution, 'count')); ?>,
                    backgroundColor: ['#3498db', '#2ecc71', '#e74c3c', '#f1c40f', '#9b59b6'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { 
                            color: '#fff',
                            padding: 20,
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#2c2c2c',
                        titleColor: '#fff',
                        bodyColor: '#fff'
                    }
                }
            }
        });

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.querySelector('.content').classList.toggle('expanded');
        }
    </script>
</body>
</html>