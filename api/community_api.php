<?php
require_once '../db/config.php';
session_start();

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Get current user data
function getCurrentUser($conn) {
    if (!isLoggedIn()) {
        return null;
    }
    
    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT user_id, username, first_name, last_name, skill_level, current_streak, profile_picture 
                           FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Handle API requests
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'current_user':
        if ($user = getCurrentUser($conn)) {
            echo json_encode($user);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Not logged in']);
        }
        break;

    case 'posts':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Get posts with filters
            $category = $_GET['category'] ?? '';
            $sort = $_GET['sort'] ?? 'recent';
            $skill = $_GET['skill'] ?? '';
            $search = $_GET['search'] ?? '';

            $query = "SELECT p.*, u.username, u.profile_picture, u.skill_level,
                     (SELECT COUNT(*) FROM comments WHERE post_id = p.post_id) as comment_count
                     FROM community_posts p
                     JOIN users u ON p.user_id = u.user_id
                     WHERE p.status = 'Active'";

            if ($category) {
                $query .= " AND p.post_type = '$category'";
            }
            if ($skill) {
                $query .= " AND u.skill_level = '$skill'";
            }
            if ($search) {
                $query .= " AND (p.title LIKE '%$search%' OR p.content LIKE '%$search%')";
            }

            switch ($sort) {
                case 'commented':
                    $query .= " ORDER BY comment_count DESC";
                    break;
                case 'liked':
                    $query .= " ORDER BY likes DESC";
                    break;
                default:
                    $query .= " ORDER BY p.created_at DESC";
            }

            $result = $conn->query($query);
            $posts = [];
            while ($row = $result->fetch_assoc()) {
                // Get post images
                $imageQuery = "SELECT * FROM post_images WHERE post_id = " . $row['post_id'];
                $imageResult = $conn->query($imageQuery);
                $row['images'] = $imageResult->fetch_all(MYSQLI_ASSOC);

                // Get comments
                $commentQuery = "SELECT c.*, u.username, u.profile_picture 
                               FROM comments c 
                               JOIN users u ON c.user_id = u.user_id 
                               WHERE c.post_id = " . $row['post_id'] . " 
                               AND c.status = 'Active' 
                               ORDER BY c.created_at DESC";
                $commentResult = $conn->query($commentQuery);
                $row['comments'] = $commentResult->fetch_all(MYSQLI_ASSOC);

                $posts[] = $row;
            }
            echo json_encode($posts);
        }
        elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isLoggedIn()) {
                http_response_code(401);
                echo json_encode(['error' => 'Not logged in']);
                break;
            }

            // Create new post
            $userId = $_SESSION['user_id'];
            $postType = $_POST['post-type'];
            $title = $_POST['title'];
            $content = $_POST['content'];

            $stmt = $conn->prepare("INSERT INTO community_posts (user_id, post_type, title, content) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $userId, $postType, $title, $content);
            
            if ($stmt->execute()) {
                $postId = $conn->insert_id;

                // Handle image uploads
                if (isset($_FILES['images'])) {
                    $uploadDir = '../uploads/posts/';
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                        $fileName = time() . '_' . $_FILES['images']['name'][$key];
                        $filePath = $uploadDir . $fileName;
                        
                        if (move_uploaded_file($tmp_name, $filePath)) {
                            $imageUrl = 'uploads/posts/' . $fileName;
                            $stmt = $conn->prepare("INSERT INTO post_images (post_id, image_url) VALUES (?, ?)");
                            $stmt->bind_param("is", $postId, $imageUrl);
                            $stmt->execute();
                        }
                    }
                }
                echo json_encode(['success' => true, 'post_id' => $postId]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to create post']);
            }
        }
        break;

    case 'comments':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isLoggedIn()) {
                http_response_code(401);
                echo json_encode(['error' => 'Not logged in']);
                break;
            }

            $postId = $_GET['post_id'] ?? null;
            $content = json_decode(file_get_contents('php://input'), true)['content'] ?? '';

            if (!$postId || !$content) {
                http_response_code(400);
                echo json_encode(['error' => 'Missing required fields']);
                break;
            }

            $userId = $_SESSION['user_id'];
            $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $postId, $userId, $content);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to create comment']);
            }
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Invalid action']);
}
?> 