<?php
// Assuming the controller has already passed the user data into this file
if (isset($user)) {
    // Check if the user has a profile image
    $profileImagePath = '/app/assets/profile/' . $user['id'] . '.png';  // Assuming the image is named as user_id.png
    $imageExists = file_exists($_SERVER['DOCUMENT_ROOT'] . $profileImagePath);
    $profileImageUrl = $imageExists ? $profileImagePath : '/app/assets/profile/default.png';  // Default image if no image exists
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <!-- Tailwind CSS CDN (Add this to your head tag) -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg mt-10">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">User Profile</h1>

        <!-- Display Profile Image -->
        <div class="text-center mb-6">
            <img src="<?php echo $profileImageUrl; ?>" alt="Profile Image" class="w-32 h-32 rounded-full mx-auto object-cover">
        </div>
        
        <table class="min-w-full table-auto">
            <tr class="border-b">
                <th class="px-6 py-3 text-left text-gray-600">Username</th>
                <td class="px-6 py-3 text-gray-800"><?php echo htmlspecialchars($user['username']); ?></td>
            </tr>
            <tr class="border-b">
                <th class="px-6 py-3 text-left text-gray-600">Email</th>
                <td class="px-6 py-3 text-gray-800"><?php echo htmlspecialchars($user['email']); ?></td>
            </tr>
            <tr class="border-b">
                <th class="px-6 py-3 text-left text-gray-600">Age</th>
                <td class="px-6 py-3 text-gray-800"><?php echo htmlspecialchars($user['age']); ?></td>
            </tr>
            <tr class="border-b">
                <th class="px-6 py-3 text-left text-gray-600">Phone</th>
                <td class="px-6 py-3 text-gray-800"><?php echo htmlspecialchars($user['phone']); ?></td>
            </tr>
            <tr class="border-b">
                <th class="px-6 py-3 text-left text-gray-600">Address</th>
                <td class="px-6 py-3 text-gray-800"><?php echo htmlspecialchars($user['address']); ?></td>
            </tr>
        </table>

        <div class="mt-6 text-center">
            <a href="/edit-profile?id=<?php echo $user['id']; ?>" class="inline-block bg-blue-500 text-white py-2 px-6 rounded-md hover:bg-blue-600 transition">Edit Profile</a>
        </div>
    </div>

</body>
</html>

<?php
} else {
    echo "<p class='text-center text-red-500'>User not found!</p>";
}
?>
