<?php
class User {
    private $conn;
    private $table_name = "users";

    // Object properties
    public $name;
    public $email;
    public $password;
    public $role;

    // Constructor with database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new user
    public function create() {
        // Hash the password before saving it
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        // Insert query
        $query = "INSERT INTO " . $this->table_name . " (name, email, password, role) VALUES (:name, :email, :password, :role)";

        // Prepare query statement
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":role", $this->role);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
