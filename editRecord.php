<?php
// Include the database connection
include_once 'process/database.inc.php'; // Adjust the path as necessary

// Start the session
session_start();

// Initialize the database connection
$database = new Database();
$db = $database->getConnection();

// Check if the ID is provided
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];

    // Prepare the SQL statement to fetch the record
    $query = "SELECT * FROM books WHERE id = :book_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':book_id', $book_id);
    $stmt->execute();
    $book = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Redirect if no ID is provided
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

    <div class="container mt-5">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-12 col-md-10 col-lg-6">
                <h3 class="mb-0 text-uppercase text-center" style="letter-spacing: .1rem;font-family:Arial, Helvetica, sans-serif">Edit</h3>
                <form action="process/update_file.inc.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($book['id']); ?>">

                    <div class="mb-3">
                        <label for="title" class="form-label">Title:</label>
                        <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($book['title']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="author" class="form-label">Author:</label>
                        <input type="text" name="author" id="author" class="form-control" value="<?php echo htmlspecialchars($book['author']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="year" class="form-label">Year:</label>
                        <input type="number" name="year" id="year" class="form-control" value="<?php echo htmlspecialchars($book['published_year']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Category:</label>
                        <input type="text" name="category" id="category" class="form-control" value="<?php echo htmlspecialchars($book['category']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description:</label>
                        <textarea name="description" id="description" class="form-control" required><?php echo htmlspecialchars($book['description']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="file">Upload New File</label>
                        <input type="file" name="file" class="form-control">
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a class="btn btn-light border" href="index.php" role="button">Back</a>
                        <button class="btn btn-dark me-md-2" type="submit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>