<?php if (isset($error)): ?>
    <div class="text-red-500"><?= $error ?></div>  <!-- Display error message -->
<?php endif; ?>

<!-- Your login form here -->
<form method="POST" action="/login">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

<!-- Link to Register page -->
<div class="mt-4 text-center">
    <p>Don't have an account? <a href="/register" class="text-blue-500 hover:underline">Register here</a></p>
</div>