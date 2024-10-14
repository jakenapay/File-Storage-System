<?php
// Include the database and user class
include_once 'database.inc.php';  // The file where your Database class is defined
include_once 'user.php';      // The file where your User class is defined

$message = "";  // Initialize an empty message

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Initialize the database connection
    $database = new Database();
    $db = $database->getConnection();

    // Initialize user object
    $user = new User($db);
    $user->name = $name;
    $user->email = $email;
    $user->password = $password;  // The raw password, will be hashed in the User class
    $user->role = $role;

    // Try to create the user
    if ($user->create()) {
        $message = "User created successfully.";  // Success message
    } else {
        $message = "Error creating user.";  // Error message
    }
}
?>

