<?php
// Include database and book classes
include_once 'database.inc.php';
include_once 'file.inc.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    // Handle file upload
    $file = $_FILES['file'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_error = $file['error'];

    // Define target directory for file upload
    $target_dir = '../src/pdf/';
    $target_file = $target_dir . basename($file_name);

    // Check if directory exists, if not create it
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);  // Create the directory if it doesn't exist
    }

    // Generate a unique name for the file
    $unique_file_name = uniqid('file_', true) . '.' . pathinfo($file_name, PATHINFO_EXTENSION);  // Use original extension
    $target_file = $target_dir . $unique_file_name;

    // Move uploaded file to target directory
    if ($file_error === 0) {
        if (move_uploaded_file($file_tmp, $target_file)) {
            // Initialize database connection
            $database = new Database();
            $db = $database->getConnection();

            // Initialize Book object
            $book = new Book($db);
            $book->title = $title;
            $book->author = $author;
            $book->year = $year;
            $book->category = $category;
            $book->description = $description;
            $book->file_name = $unique_file_name;  // Save the unique file name in the database

            // Insert book into the database with error checking
            if ($book->createBook()) {
                header("Location: ../index.php?status=success");
            } else {
                header("Location: ../index.php?status=error&type=db_error");
            }
        } else {
            header("Location: ../index.php?status=error&type=file_move_error");
        }
    } else {
        header("Location: ../index.php?status=error&type=file_upload_error");
    }
}
