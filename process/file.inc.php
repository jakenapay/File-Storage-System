<?php
class Book {
    private $conn;
    private $table_name = "books";

    public $title;
    public $author;
    public $year;
    public $category;
    public $description;
    public $file_name;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Function to insert a book into the database
    public function createBook() {
        $query = "INSERT INTO " . $this->table_name . " (title, author, published_year, category, description, pdf_file, upload_date)
                  VALUES (:title, :author, :year, :category, :description, :file_name, NOW())";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':year', $this->year);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':file_name', $this->file_name);

        // Error checking for query execution
        if ($stmt->execute()) {
            return true;
        } else {
            // Capture the error information for debugging
            $errorInfo = $stmt->errorInfo();
            echo "SQL Error: " . $errorInfo[2]; // Print detailed SQL error
            return false;
        }
    }
}
?>
