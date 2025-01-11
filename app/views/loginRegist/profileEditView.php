<?php if (isset($user)) { ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg mt-10">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Edit Profile</h1>

        <!-- Profile Edit Form -->
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="username" class="block text-gray-700">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" class="w-full p-2 border border-gray-300 rounded-md" required><br><br>

            <label for="email" class="block text-gray-700">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full p-2 border border-gray-300 rounded-md" required><br><br>

            <label for="age" class="block text-gray-700">Age:</label>
            <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($user['age']); ?>" class="w-full p-2 border border-gray-300 rounded-md"><br><br>

            <label for="phone" class="block text-gray-700">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" class="w-full p-2 border border-gray-300 rounded-md"><br><br>

            <label for="address" class="block text-gray-700">Address:</label>
            <textarea id="address" name="address" class="w-full p-2 border border-gray-300 rounded-md"><?php echo htmlspecialchars($user['address']); ?></textarea><br><br>

            <!-- File Upload for Profile Image -->
            <label for="profile_image" class="block text-gray-700">Profile Image:</label>
            <input type="file" id="profile_image" name="profile_image" accept="image/*" class="w-full p-2 border border-gray-300 rounded-md"><br><br>

            <input type="submit" value="Update Profile" class="bg-blue-500 text-white py-2 px-6 rounded-md hover:bg-blue-600 transition">
        </form>
    </div>

</body>
</html>
<?php } else { echo "User not found."; } ?>
