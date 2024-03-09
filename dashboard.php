<?php
include "config.php";
session_start();

if(!isset($_SESSION['login_user'])){
    header("location:index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form data and save it to the database
    $mobile_phone = $_POST['mobile_phone'];
    $email = $_POST['email']; // Consider using session email or another method for identification
    $address = $_POST['address'];
    $registration_number = $_POST['registration_number'];

    $sql = "UPDATE users SET mobile_phone='$mobile_phone', email='$email', address='$address', registration_number='$registration_number' WHERE username='{$_SESSION['login_user']}'";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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

        input[type="submit"] {
            background-color: #2ecc71; /* Green button background color */
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #27ae60; /* Darker green on hover */
        }
    </style>
</head>
<body>
    <form action="" method="post">
        <label for="mobile_phone">Mobile Phone :</label>
        <input type="text" name="mobile_phone" id="mobile_phone" required>

        <label for="email">Email :</label>
        <input type="email" name="email" id="email" required>

        <label for="address">Address :</label>
        <input type="text" name="address" id="address" required>

        <label for="registration_number">Registration Number :</label>
        <input type="text" name="registration_number" id="registration_number" required>

        <input type="submit" value="Update">
    </form>
</body>
</html>


