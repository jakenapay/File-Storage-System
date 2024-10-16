<?php
// Include the database connection
include_once 'database.inc.php';

// Initialize the database connection
$database = new Database();
$pdo = $database->getConnection();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['givenname']) . " " . trim($_POST['familyname']);
    $email = trim($_POST['email']);  // Collecting email
    $password = trim($_POST['password']);
    $role = 'user';
    // Setting role as user so the next one to register is set as user

    // Input validation (optional but recommended)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
        exit;
    }

    // Hash the password before saving it
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Prepare the SQL insert query
    $sql = "INSERT INTO users (name, email, password, role) VALUES (:username, :email, :password, :role)";

    // Execute the statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);  // Bind email
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':role', $role);

    // Attempt to execute the query
    if ($stmt->execute()) {
        echo '<script>
            alert("User registered successfully!");
            window.location.href = "signup.php";
          </script>';
    } else {
        echo '<script>
            alert("Error: User registration failed.");
            window.location.href = "signup.php";
          </script>';
    }
}