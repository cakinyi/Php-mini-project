<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    if (isset($email)) {
        $code = "";
        for ($i = 0; $i < 5; $i++) {
            $number = rand(0, 10);
            $code .= $number;
        }

        $query1 = "SELECT id, email, password, code, phonenumber, address, registration FROM student WHERE email = '{$email}'";
        $result = mysqli_query($conn, $query1);

        $sender = filter_var("omondiakinyi5@gmail.com", FILTER_SANITIZE_EMAIL);
        $pass = 'zbayjrbbncywsdbx';
        $to = $email;
        echo "<p> password: $pass</p>";

        $mail = new PHPMailer(true);
        $mail->addAddress($to);

        if (mysqli_num_rows($result) > 0) {
            $resultarr = mysqli_fetch_array($result);
            $id = $resultarr["id"];

            $query2 = "UPDATE student SET code='$code' WHERE id = '$id'";
            mysqli_query($conn, $query2);

            $subject = "Password retrieval code";
            $message = "Your retrieval code is " . $code;
            $headers = "From: $subject\r\n";


            try {
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Password = $pass;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom($sender);

                $query3 = "INSERT INTO student (email, code) VALUES ('$email', '$code')";
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $message;

                $mail->send();
                echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            mysqli_query($conn, $query3);

            // Send email using Gmail's SMTP server
            $subject = "Password retrieval code";
            $message = "Your retrieval code is " . $code;

            $headers = "From: $sender"; // Replace with your Gmail address

            try {
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Password = $pass;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom($sender);

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $message;

                $mail->send();
                echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
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
