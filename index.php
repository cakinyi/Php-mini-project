<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Include database configuration
include "config.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve hashed password from the database
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();

    // Verify password
    if ($hashed_password && password_verify($password, $hashed_password)) {
        // Password is correct, store username in session variable
        $_SESSION['login_user'] = $username;
        // Redirect to dashboard
        header("location: dashboard.php");
        exit(); // Ensure script stops execution after redirect
    } else {
        // Password is incorrect, display error message
        $error = "Your Login Name or Password is invalid";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(45deg, #3498db, #2ecc71);
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        div {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            margin-bottom: 8px;
            font-weight: bold;
            display: block;
            color: #333;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #2ecc71;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #27ae60;
        }

        .error-message {
            color: #ff0000;
            margin-top: 10px;
        }

        a {
            text-decoration: none;
            color: #3498db;
            margin-top: 10px;
            display: block;
        }

        a:hover {
            color: #1e87d6;
        }
    </style>
</head>
<body>
    <div>
        <form action="" method="post">
            <label>UserName :</label>
            <input type="text" name="username" required>

            <label>Password :</label>
            <input type="password" name="password" required>

            <input type="submit" value="Submit">
        </form>

        <!-- Display error message -->
        <div class="error-message"><?php if(isset($error)) { echo $error; } ?></div>

        <a href="forgot_password.php">Forgot Password?</a>
    </div>
</body>
</html>
