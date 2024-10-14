<?php
// header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
// header("Pragma: no-cache");
// header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
// Check for status in the URL
$status = isset($_GET['status']) ? $_GET['status'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- css datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- Data tables -->
    <script>
        let table = new DataTable('#showTable');

        $(document).ready(function() {
            $('#showTable').DataTable();
        });
    </script>
</head>

<body>

    <div class="container mt-5">
        <div class="container">
            <h1 class="mb-5 text-uppercase text-center" style="letter-spacing: 2rem;font-family:'Times New Roman', Times, serif">Files</h1>
        </div>
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-12 col-md-12 col-lg-12">
                <table id="showTable" class="table thead-dark table-responsive">
                    <thead class="table-dark">
                        <tr>
                            <!-- <th>Book ID</th> -->
                            <th>Title</th>
                            <th>Author</th>
                            <th>Year</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>File Name</th>
                            <th>Upload Date</th>
                            <th>File</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <?php
                        // Include the database class
                        include_once 'process/database.inc.php';  // Assuming your Database class is in 'database.php'

                        // Initialize the database connection
                        $database = new Database();
                        $db = $database->getConnection();

                        // Fetch books from the database
                        $query = "SELECT * FROM books";  // Adjust the query if your table name is different
                        $stmt = $db->prepare($query);
                        $stmt->execute();

                        // Check if there are books to display
                        if ($stmt->rowCount() > 0) {
                            // Loop through the records and display each book
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo '<tr>';
                                // echo '<td>' . htmlspecialchars($row['book_id']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['title']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['author']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['published_year']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['category']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['pdf_file']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['upload_date']) . '</td>';

                                // New Download column with a button and icon
                                echo '<td class="text-center">';
                                $file_path = 'src/pdf/' . htmlspecialchars($row['pdf_file']);

                                // Check if the file exists in the folder
                                if (file_exists($file_path)) {
                                    // Show the enabled download button if the file exists
                                    // echo '<a class="btn btn-dark btn-sm" href="' . $file_path . '" download data-bs-toggle="tooltip" data-bs-placement="top" title="Download" onclick="refreshAfterDownload()">';
                                    echo '<a class="btn btn-dark btn-sm" href="src/pdf/' . htmlspecialchars($row['pdf_file']) . '?v=' . time() . '" download data-bs-toggle="tooltip" data-bs-placement="top" title="Download" onclick="refreshAfterDownload()">';

                                    echo '<i class="fa-solid fa-download"></i>';  // Font Awesome download icon
                                    echo '</a>';
                                } else {
                                    // Show a disabled button if the file doesn't exist
                                    echo '<button class="btn btn-dark btn-sm" disabled data-bs-toggle="tooltip" data-bs-placement="top" title="File not available">';
                                    echo '<i class="fa-solid fa-download"></i>';  // Font Awesome download icon
                                    echo '</button>';
                                }

                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            // If no books are found, display a message
                            echo '<tr><td colspan="8" class="text-center">No books found.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Button trigger for add file modal -->
        <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#addfilemodal">
            Upload
        </button>
    </div>


    <!-- Add modal -->
    <div class="modal fade" id="addfilemodal" tabindex="-1" aria-labelledby="addfilemodal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 text-capitalize" id="addmodallabel">Upload new file</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="process/insert_file.inc.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="author">Author</label>
                                <input type="text" name="author" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="year">Year</label>
                                <input type="number" name="year" min="0" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="category">Category</label>
                                <select name="category" class="form-control" required>
                                    <option value="" disabled>Choose Category</option>
                                    <option value="Book">Book</option>
                                    <option value="Letter">Letter</option>
                                    <option value="Form">Form</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="file">Upload PDF</label>
                                <input type="file" name="file" class="form-control" accept="application/pdf" required>
                            </div>
                            <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-dark">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        function refreshAfterDownload() {
            // Set a timeout to refresh the page after 2 seconds (adjust the delay as needed)
            setTimeout(function() {
                location.reload(); // Refresh the page
            }, 1000); // 2-second delay before refreshing
        }

        // JavaScript to display alerts based on the URL parameters
        var status = "<?php echo $status; ?>";
        var type = "<?php echo $type; ?>";

        if (status === 'success') {
            alert('Book inserted successfully.');
        } else if (status === 'error') {
            switch (type) {
                case 'invalid_file':
                    alert('Only PDF files are allowed.');
                    break;
                case 'db_error':
                    alert('Error inserting book into the database.');
                    break;
                case 'file_move_error':
                    alert('Error moving the uploaded file.');
                    break;
                case 'file_upload_error':
                    alert('Error uploading the file.');
                    break;
                default:
                    alert('An unknown error occurred.');
                    break;
            }
        }
    </script>

    <!-- bootstrap tooltip -->
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- script data tables -->
    <script src="https://cdn.datatables.net/2.1.7/css/dataTables.bootstrap5.min.css"></script>
</body>

</html>