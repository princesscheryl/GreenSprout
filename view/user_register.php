<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../db/config.php'; 

// Initialize variables
$showSignup = false;
$loginError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['firstName'])) {
    $response = ['status' => 'error', 'message' => ''];

    try {
        // Get registration form data
        $firstName = trim($_POST['firstName']);
        $lastName = trim($_POST['lastName']);
        $username = trim($_POST['username']);
        $email = trim($_POST['signupEmail']);
        $password = $_POST['signupPassword'];
        $confirmPassword = $_POST['confirmPassword'];
        
        // Convert numeric skill level to enum value
        // switch($_POST['skill_level']) {
        //     case '1':
        //         $skillLevel = 'Beginner';
        //         break;
        //     case '2':
        //         $skillLevel = 'Intermediate';
        //         break;
        //     case '3':
        //         $skillLevel = 'Advanced';
        //         break;
        //     default:
        //         $skillLevel = 'Beginner';
        // }
        $skillLevel = $_POST['skill_level'];

        // Basic validation
        if (empty($firstName) || empty($lastName) || empty($username) || empty($email) || empty($password)) {
            throw new Exception('All fields are required');
        }

        if ($password !== $confirmPassword) {
            throw new Exception('Passwords do not match');
        }

        // Check for existing email
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            throw new Exception('Email already registered.');
        }

        // Check for existing username
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            throw new Exception('Username already taken.');
        }

        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Set default values
        $currentStreak = 0;
        $longestStreak = 0;
        $isActive = 1;
        $role = 'user';
        $weatherUnit = 'celsius';
        
        // Insert new user
        $stmt = $conn->prepare("
            INSERT INTO users (
                first_name, 
                last_name, 
                username, 
                email, 
                password_hash,
                current_streak,
                longest_streak,
                skill_level,
                is_active,
                role,
                weather_unit
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        if (!$stmt) {
            throw new Exception('Database prepare failed: ' . $conn->error);
        }

        $stmt->bind_param(
            "sssssiisiss", 
            $firstName,
            $lastName,
            $username,
            $email,
            $passwordHash,
            $currentStreak,
            $longestStreak,
            $skillLevel,
            $isActive,
            $role,
            $weatherUnit
        );
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'user';
            
            $response['status'] = 'success';
            $response['message'] = 'Registration successful';
            $response['redirect'] = 'user_dashboard.php';
        } else {
            throw new Exception('Registration failed: ' . $stmt->error);
        }

    } catch (Exception $e) {
        error_log('Registration Error: ' . $e->getMessage());
        $response['message'] = $e->getMessage();
    }

    // Send JSON response
    // header('Content-Type: application/json');
    // echo json_encode($response);
    // exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenSprout - Login / Sign Up</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container" id="container">
        <!-- Sign Up Form -->
        <div class="sign-up">
            <form id="signupForm" method="POST">
                <h1>Create Account</h1>
                <span>or use your email for registration</span>
                <input type="text" name="firstName" placeholder="First Name" required />
                <input type="text" name="lastName" placeholder="Last Name" required />
                <input type="text" name="username" placeholder="Username" required />
                <input type="email" name="signupEmail" placeholder="Email" required />
                <input type="password" name="signupPassword" placeholder="Password" required />
                <input type="password" name="confirmPassword" placeholder="Confirm Password" required />
                <select name="skill_level" required>
                    <option value="" disabled selected>Gardening Experience</option>
                    <option value="Beginner">Beginner</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Advanced">Advanced</option>
                </select>
                <button type="submit">Sign Up</button>
            </form>
        </div>

        <!-- Sign In Form -->
        <div class="sign-in">
            <form id="signinForm" method="POST">
                <h1>Sign In</h1>
                <span>or use your account</span>
                <input type="text" name="identifier" placeholder="Email or Username" required />
                <input type="password" name="password" placeholder="Password" required />
                <a href="#">Forgot your password?</a>
                <button type="submit">Sign In</button>
            </form>
        </div>

        <!-- Overlay -->
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected, please login with your personal info</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-right">
                    <h1>Hello, Sprout!</h1>
                    <p>Enter your personal details and start your journey with us</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Switch between Sign In and Sign Up
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');

        signUpButton.addEventListener('click', () => container.classList.add('right-panel-active'));
        signInButton.addEventListener('click', () => container.classList.remove('right-panel-active'));
    </script>
</body>
</html>