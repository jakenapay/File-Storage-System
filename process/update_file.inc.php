<?php
// Include the database connection
include_once 'database.inc.php';

// Initialize the database connection
$database = new Database();
$db = $database->getConnection();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $book_id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    // Fetch the current file name from the database (to delete it later if a new file is uploaded)
    $query = "SELECT file FROM books WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $book_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $old_file_name = $result['file'];

    // Handle the file upload
    $file = $_FILES['file'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_error = $file['error'];
    
    $new_file_name = '';  // Initialize variable for the new file name
    $file_updated = false;  // Track if the file is updated

    // If a file was uploaded without errors
    if ($file_error === 0 && !empty($file_name)) {
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $new_file_name = uniqid('book_', true) . '.' . $file_ext;  // Generate a unique file name
        $target_dir = '../src/pdf/';  // Directory to store files
        $target_file = $target_dir . $new_file_name;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($file_tmp, $target_file)) {
            $file_updated = true;  // Mark that the file has been updated

            // Delete the old file from the server if a new file is uploaded
            if (!empty($old_file_name) && file_exists($target_dir . $old_file_name)) {
                unlink($target_dir . $old_file_name);  // Delete the old file
            }
        } else {
            echo '<script>
                alert("Error: File upload failed.");
                window.location.href = "../editRecord.php?id=' . $book_id . '";
            </script>';
            exit();
        }
    }

    // Prepare the SQL statement to update the record
    if ($file_updated) {
        // Update both the details and the file name if a new file is uploaded
        $query = "UPDATE books SET title = :title, author = :author, published_year = :year, category = :category, description = :description, file = :file WHERE id = :id";
    } else {
        // Update only the details, keeping the old file
        $query = "UPDATE books SET title = :title, author = :author, published_year = :year, category = :category, description = :description WHERE id = :id";
    }

    $stmt = $db->prepare($query);

    // Bind parameters
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':year', $year);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':id', $book_id);

    // Bind file parameter if file was updated
    if ($file_updated) {
        $stmt->bindParam(':file', $new_file_name);
    }

    // Attempt to execute the query
    if ($stmt->execute()) {
        echo '<script>
            alert("Book updated successfully!");
            window.location.href = "../editRecord.php?id=' . $book_id . '";
        </script>';
    } else {
        echo '<script>
            alert("Error: Book update failed.");
            window.location.href = "../editRecord.php?id=' . $book_id . '";
        </script>';
    }
} else {
    header("Location: ../index.php");
    exit();
}
