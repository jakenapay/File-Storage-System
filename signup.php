
<?php
// Include the database and user class
include_once 'database.inc.php';  // The file where your Database class is defined
include_once 'user.inc.php';      // The file where your User class is defined

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        h2 {
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #4cae4c;
        }

        .link {
            text-align: center;
        }

        .message {
            text-align: center;
            margin-top: 15px;
            color: green;
        }

        .error {
            text-align: center;
            margin-top: 15px;
            color: red;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Create Account</h2>
        <form action="create_user.php" method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="member">Member</option>
                    <option value="librarian">Librarian</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit">Create Account</button>
            </div>

            <div class="link">
                <a class="" href="login.php">Login</a>
            </div>

            <!-- Display the message dynamically -->
            <?php if (!empty($message)): ?>
                <div class="message">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
        </form>
    </div>

</body>

</html>