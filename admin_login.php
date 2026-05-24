<?php
// Start a session to store user info if needed
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection settings
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "tuck-shop"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize error variable
$error = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    // Prepare SQL statement to prevent SQL injection
    $sql = "SELECT username, password FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $inputUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists and password is correct
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($inputPassword, $row['password'])) {
            // Password is correct, redirect to admin dashboard
            header("Location: admindashboard.php");
            exit();
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Invalid username or password";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            background-color: #333;
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        header {
            width: 100%;
            background-color: #444;
            padding: 10px 0;
            text-align: center;
            margin-bottom: 20px;
        }
        header h1 {
            margin: 0;
        }
        .content {
            width: 80%;
            max-width: 1200px;
        }
        .nav-links {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .nav-links a {
            background-color: #1E90FF;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 10px;
            text-align: center;
            width: 150px;
        }
        .nav-links a:hover {
            background-color: #0d74d1;
        }
        .footer {
            text-align: center;
            padding: 20px;
            background-color: #222;
            color: #bbb;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
    </header>
    <div class="content">
        <div class="nav-links">
            <a href="index.html">Home</a>
            <a href="admin.php">Edit Admins</a>
            <a href="sales.php">Sales</a>
            <a href="product.php">Product</a>
            <a href="category.php">Product Categories</a>
            <a href="supplier.php">Supplier</a>
        </div>
    </div>
    <div class="footer">
        &copy; 2024 Tuck Shop. All rights reserved.
    </div>
</body>
</html>