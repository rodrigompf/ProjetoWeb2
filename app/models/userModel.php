<?php

require_once '../app/database/connection.php';

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();  // Initialize database connection
    }

    // Get a user by their username (used for login or checking existing users)
    public function getUserByUsername($username)
    {
        $query = "SELECT * FROM users WHERE username = :username"; // Query to fetch user by username
        $stmt = $this->db->prepare($query);  // Prepare the SQL statement
        $stmt->execute([':username' => $username]);  // Execute with the given username

        return $stmt->fetch(PDO::FETCH_ASSOC);  // Return the result as an associative array
    }

    // Create a new user (used for registration)
        public function createUser($username, $email, $password)
    {
        $query = "INSERT INTO users (username, email, password, admin) VALUES (:username, :email, :password, :admin)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':admin' => false,  // Admin is always set to false by default
        ]);
    }

    // Authenticate user (used for login)
    public function login($email, $password)
    {
        // Fetch user by email
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':email' => $email]);

        // Get user data from the database
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists and verify the password
        if ($user && password_verify($password, $user['password'])) {
            return $user;  // Return user data if credentials are correct
        }

        return false;  // Return false if login fails
    }
}
?>
