<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
</head>
<body>
    <h2>Register</h2>
    <form action="process/create_user.inc.php" method="POST">
        <div>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div>
            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
        <button type="submit">Register</button>
    </form>
</body>
</html>
