<?php
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKen | Register</title>
</head>
<body>
    <h1>Register</h1>
    <form method="POST" action="register_process.php">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <button type="submit">Register</button>
    </form>
</body>
</html>