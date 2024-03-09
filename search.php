<?php
include "config.php"; // Ensure this points to your database connection file

$searchResult = ""; // To store the search results

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['registration_number'])) {
    $registrationNumber = $_POST['registration_number'];
    
    // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM users WHERE registration_number = ?");
    $stmt->bind_param("s", $registrationNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Construct the display string with user details. Adjust as needed.
            $searchResult .= "Name: " . $row["username"] . "<br>Email: " . $row["email"] . "<br>Mobile Phone: " . $row["mobile_phone"] . "<br>Address: " . $row["address"] . "<br><br>";
        }
    } else {
        $searchResult = "No user found with that registration number.";
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Contact Details</title>
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

        h2, h3 {
            color: #fff; /* White text color for headers */
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
            background-color: #3498db; /* Blue button background color */
            color: #fff;
            cursor: pointer;
            padding: 8px;
            border: none;
            border-radius: 4px;
        }

        button:hover {
            background-color: #2ecc71; /* Darker green on hover */
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background: linear-gradient(45deg, #3498db, #2ecc71);
            color: #fff; /* White text color for pop-up */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .popup button {
            background-color: #fff; /* White button background color */
            color: #3498db; /* Blue text color for button */
            cursor: pointer;
            border: none;
            padding: 8px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h2>Search for Contact Details</h2>
    <form method="post" action="search.php">
        <label for="registration_number">Registration Number:</label>
        <input type="text" id="registration_number" name="registration_number" required>
        <button type="submit" onclick="showPopup()">Search</button>
    </form>
    
    <div id="popup" class="popup">
        <h3>Search Results:</h3>
        <p><?php echo $searchResult; ?></p>
        <button onclick="hidePopup()">Close</button>
    </div>

    <script>
        function showPopup() {
            document.getElementById("popup").style.display = "block";
        }

        function hidePopup() {
            document.getElementById("popup").style.display = "none";
        }
    </script>
</body>
</html>
