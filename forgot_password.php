<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    if (isset($email)) {
        $code = "";
        for ($i = 0; $i < 5; $i++) {
            $number = rand(0, 10);
            $code .= $number;
        }

        $query1 = "SELECT `id`, `email`, `password`, `code`, `phonenumber`, `address`, `registration` FROM `student` WHERE email = '{$email}'";
        $result = mysqli_query($conn, $query1);

        if (mysqli_num_rows($result) > 0) {
            $resultarr = mysqli_fetch_array($result);
            $id = $resultarr["id"];

            $query2 = "UPDATE `student` SET `code`='$code' WHERE id = '$id'";
            mysqli_query($conn, $query2);

            // Send email using Gmail's SMTP server
            $to = $email;
            $subject = "Password retrieval code";
            $message = "Your retrieval code is " . $code;
            $headers = "From: opiyogilphine@gmail.com\r\n";
            if (mail($to, $subject, $message, $headers)) {
                echo "done";
            } else {
                exit("Error sending email");
            }
        } else {
            $query3 = "INSERT INTO `student` (`email`, `code`) VALUES ('$email', '$code')";
            mysqli_query($conn, $query3);

            // Send email using Gmail's SMTP server
            $to = $email;
            $subject = "Password retrieval code";
            $message = "Your retrieval code is " . $code;

            $headers = "From: opiyogilphine@gmail.com"; // Replace with your Gmail address

            if (mail($to, $subject, $message, $headers)) {
                echo "done";
            } else {
                exit("Error sending email");
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #4CAF50, #45a049); /* Adjust the colors as needed */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #4CAF50;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <form method="post" action="forgot_password.php">
        <h2>Forgot Password</h2>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>
