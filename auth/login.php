<?php
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoKen | Login</title>
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .login-container {
            background: #d1d5db;
            border-radius: 4;
            padding: 4;
            text-align: center;
        }

        input {
            padding: 2;
            border-radius: 3;
        }

        button {
            padding: 2;
            border-radius: 3;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h1>Login</h1>
        <form method="POST" action="login_process.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <button type="submit">Login</button>
            <a href="index.php">Back to Home</a>
        </form>
    </div>
</body>

</html>