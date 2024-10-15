<?php
// Include the database connection
include_once 'database.inc.php';

session_start();

// Initialize the Database object
$database = new Database();
$pdo = $database->getConnection();

// Check if the ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare update statement to mark the book as deleted
    $sql = "UPDATE books SET isDelete = 1 WHERE id = :id";

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        // Redirect back with a success message
        header("Location: ../index.php?status=success&action=delete");
    } else {
        // Redirect back with an error message
        header("Location: ../index.php?status=error&type=db_error&action=delete");
    }
} else {
    // Redirect back with an error message if no ID is provided
    header("Location: ../index.php?status=error&type=missing_id&action=delete");
}
?>
