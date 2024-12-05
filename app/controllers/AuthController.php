<?php

require_once '../app/models/UserModel.php';

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            if ($this->userModel->createUser($username, $email, $password)) {
                header('Location: /login');
                exit;
            } else {
                echo "Error registering user.";
            }
        }

        require_once '../app/views/registerView.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve form data
            $email = $_POST['email'];
            $password = $_POST['password'];
    
            // Attempt to authenticate the user
            $user = $this->userModel->login($email, $password);
    
            // Check if the user exists and password matches
            if ($user) {
                // Start a session and store user data
                session_start();
                $_SESSION['user'] = $user;
    
                // Redirect to the home page or any other page after successful login
                header('Location: /');  // Assuming / is the homepage route
                exit;
            } else {
                // If login fails, set an error message (can be passed to the view)
                $error = "Invalid email or password.";
            }
        }
    
        // Render the login view
        require_once '../app/views/loginView.php';
    }
    


    public function logout()
{
    session_start();    // Start the session
    session_unset();    // Remove all session variables
    session_destroy();  // Destroy the session
    header('Location: /'); // Redirect to the home page after logout
    exit();
}

}
?>
