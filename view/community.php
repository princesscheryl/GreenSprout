<?php
session_start();
require_once '../db/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: user_register.php');
    exit();
}

// Fetch posts with user info and like counts
$query = "
    SELECT 
        p.*, 
        u.username,
        COUNT(DISTINCT pc.comment_id) as comment_count,
        COUNT(DISTINCT pl.user_id) as like_count,
        EXISTS(SELECT 1 FROM post_likes WHERE post_id = p.post_id AND user_id = ?) as user_liked
    FROM posts p
    JOIN users u ON p.user_id = u.user_id
    LEFT JOIN post_comments pc ON p.post_id = pc.post_id
    LEFT JOIN post_likes pl ON p.post_id = pl.post_id
    GROUP BY p.post_id
    ORDER BY p.created_at DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$posts = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenSprout Community</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard_styles.css">
    <link rel="stylesheet" href="../assets/css/community_styles.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <span class="logo-text">GreenSprout</span>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li>
                    <a href="user_dashboard.php">
                        <i class="ri-dashboard-3-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="community.php" class="active">
                        <i class="ri-team-line"></i>
                        <span>Community</span>
                    </a>
                </li>
                <li>
                    <a href="plant_manager.php">
                        <i class="ri-leaf-line"></i>
                        <span>Plant Manager</span>
                    </a>
                </li>
                <li>
                    <a href="../actions/logout.php">
                        <i class="ri-logout-box-line"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <div class="content">
        <div class="community-header">
            <h1>Community</h1>
            <button class="create-post-btn">
                <i class="fas fa-plus"></i> Create Post
            </button>
        </div>

        <div class="posts-container">
            <?php foreach ($posts as $post): ?>
                <div class="post-card">
                    <div class="post-header">
                        <div class="post-info">
                            <span class="username"><?php echo htmlspecialchars($post['username']); ?></span>
                            <span class="post-date"><?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                        </div>
                    </div>
                    
                    <h2 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h2>
                    <p class="post-content"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                    
                    <?php if ($post['image_path']): ?>
                        <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="Post image" class="post-image">
                    <?php endif; ?>

                    <div class="post-actions">
                        <button class="like-btn <?php echo $post['user_liked'] ? 'liked' : ''; ?>" 
                                data-post-id="<?php echo $post['post_id']; ?>">
                            <i class="fas fa-heart"></i>
                            <span class="like-count"><?php echo $post['like_count']; ?></span>
                        </button>
                        <button class="comment-btn" data-post-id="<?php echo $post['post_id']; ?>">
                            <i class="fas fa-comment"></i>
                            <span class="comment-count"><?php echo $post['comment_count']; ?></span>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Create Post Modal -->
    <div id="createPostModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Create a Post</h2>
            <form id="createPostForm" action="../actions/create_post.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" required></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Image (optional)</label>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>
                <button type="submit" class="submit-btn">Post</button>
            </form>
        </div>
    </div>

    <!-- Comments Modal -->
    <div id="commentsModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Comments</h2>
            <div id="comments-container">
                <!-- Comments will be loaded here -->
            </div>
            <form id="comment-form">
                <input type="hidden" id="comment-post-id" name="post_id">
                <div class="form-group">
                    <textarea id="comment-content" name="content" required 
                             placeholder="Write a comment..."></textarea>
                </div>
                <button type="submit" class="submit-btn">Post Comment</button>
            </form>
        </div>
    </div>

    <script src="../assets/js/community.js"></script>
</body>
</html> 