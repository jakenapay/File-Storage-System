<?php
// Include the database connection
include_once 'database.inc.php';

session_start();

// Initialize the Database object
$database = new Database();
$pdo = $database->getConnection();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepare the SQL statement to fetch the user
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Check if the user exists
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start a session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];  // Store the user's role
            // Redirect to a dashboard or home page
            header("Location: ../index.php");
            exit();
        } else {
            // Invalid password
            echo "Incorrect password.";
        }
    } else {
        // No user found with that email
        echo "No user found with this email.";
    }
}
