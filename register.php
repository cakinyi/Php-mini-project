<?php
include 'config.php'; // Database connection

$error = ''; // Variable to hold error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $mobile_phone = $_POST['mobile_phone'];
    $address = $_POST['address'];
    $registration_number = $_POST['registration_number'];

    // Validate input
    if (empty($username) || empty($email) || empty($password) || empty($mobile_phone) || empty($address) || empty($registration_number)) {
        $error = 'Please fill in all fields.';
    } else {
        // Check if username or registration number already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR registration_number = ?");
        $stmt->bind_param("ss", $username, $registration_number);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username or Registration Number already taken.";
        } else {
            // Insert new user into the database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Securely hash the password
            $insertQuery = $conn->prepare("INSERT INTO users (username, email, password, mobile_phone, address, registration_number) VALUES (?, ?, ?, ?, ?, ?)");
            $insertQuery->bind_param("ssssss", $username, $email, $hashed_password, $mobile_phone, $address, $registration_number);

            if ($insertQuery->execute()) {
                header("Location: index.php"); // Redirect to login page upon successful registration
                exit;
            } else {
                $error = "Error occurred during registration. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(45deg, #3498db, #2ecc71);
            overflow: hidden;
        }

        h2 {
            color: #fff;
        }

        form {
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            box-sizing: border-box;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333; /* Text color for labels */
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #2ecc71; /* Green button background color */
            color: #fff;
            cursor: pointer;
            padding: 8px;
            border: none;
            border-radius: 4px;
        }

        button:hover {
            background-color: #27ae60; /* Darker green on hover */
        }

        p {
            color: red;
        }
    </style>
</head>
<body>
    <h2>Register</h2>
    <form action="register.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        
        <label for="mobile_phone">Mobile Phone:</label>
        <input type="text" id="mobile_phone" name="mobile_phone" required><br>
        
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br>
        
        <label for="registration_number">Registration Number:</label>
        <input type="text" id="registration_number" name="registration_number" required><br>
        
        <button type="submit">Register</button>
    </form>
    
    <?php
    if (!empty($error)) {
        echo '<p style="color:red;">' . $error . '</p>';
    }
    ?>
</body>
</html>