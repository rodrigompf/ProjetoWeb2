<?php
require_once './app/models/userModel.php';

class ProfileController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Displays the user's profile.
     */
    public function view()
    {
        // Check if the user is logged in (you can customize this check)
        if (isset($_SESSION['user']) && isset($_SESSION['user']['id'])) {
            $userId = $_SESSION['user']['id'];
            $user = $this->userModel->getUserById($userId);
            require_once './app/views/loginRegist/profileView.php';  // Display the profile
        } else {
            echo "User not logged in.";
        }
    }

    /**
     * Displays the edit profile page and handles the form submission.
     */
    public function edit()
{
    $error = null;

    try {
        // Check if the user is logged in and fetch their ID from the session
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
            throw new Exception("User not logged in.");
        }

        $userId = $_SESSION['user']['id'];  // Get the logged-in user's ID

        $conn = Connection::getInstance();

        // Fetch the user's profile details using the logged-in user ID
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new Exception("User not found.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get the new form data
            $username = $_POST['username'] ?? null;
            $email = $_POST['email'] ?? null;
            $age = $_POST['age'] ?? null;
            $phone = $_POST['phone'] ?? null;
            $address = $_POST['address'] ?? null;
            $newImage = $_FILES['profile_image'] ?? null;

            // Validate required fields
            if (!$username || !$email) {
                $error = "Username and email are required.";
            } else {
                $imagePath = $user['profile_image']; // Current image path (if any)

                // If a new file is uploaded, process it
                if ($newImage && $newImage['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = './app/assets/profile/'; // Directory to save the image
                    $imageExtension = pathinfo($newImage['name'], PATHINFO_EXTENSION);  // Get image extension
                    $imageName = $userId . '.' . $imageExtension;  // Use user ID as the filename
                    $uploadPath = $uploadDir . $imageName;  // Full path to the image

                    // Move the uploaded file to the target directory
                    if (move_uploaded_file($newImage['tmp_name'], $uploadPath)) {
                        $imagePath = $imageName;  // Update the image path
                    } else {
                        $error = "Error uploading the image.";
                    }
                }

                // Update user profile in the database
                $sql = "UPDATE users SET username = :username, email = :email, age = :age, phone = :phone, address = :address, profile_image = :profile_image WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':age', $age);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':address', $address);
                $stmt->bindParam(':profile_image', $imagePath);  // Save the image filename in the DB
                $stmt->bindParam(':id', $userId);

                if ($stmt->execute()) {
                    // Redirect to the profile view page upon success
                    header("Location: /profile?id=$userId");
                    exit;
                } else {
                    $error = "Error updating the user profile.";
                }
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }

    // Include the view to edit the user profile
    require_once './app/views/loginRegist/profileEditView.php';
}

}
?>
