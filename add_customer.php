<?php
$servername = "localhost";
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "tuck-shop"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerID = $_POST['customerID'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $phoneNumber = $_POST['phoneNumber'];
    $customerAddress = $_POST['customerAddress'];

    $sql = "INSERT INTO customer (CustomerID, FirstName, LastName, PhoneNumber, CustomerAddress) VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("issss", $customerID, $firstName, $lastName, $phoneNumber, $customerAddress);
        if ($stmt->execute()) {
            echo "New record created successfully";
        } else {
            echo "Error executing statement: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "Form not submitted correctly.";
}

$conn->close();

// Redirect back to the form page
header("Location: customer.html");
exit();
?>
