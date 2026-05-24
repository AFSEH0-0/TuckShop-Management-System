<?php
// Database connection
$servername = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$dbname = "tuck-shop"; // Change if needed

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adminUsername = $_POST['username'];
    $adminPassword = $_POST['password'];
    $adminFirstName = $_POST['AdminFirstName'];
    $adminLastName = $_POST['AdminLastName'];
    $adminEmail = $_POST['AdminEmail'];

    $sql = "INSERT INTO admin (username, password, AdminFirstName, AdminLastName, AdminEmail)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $adminUsername, $adminPassword, $adminFirstName, $adminLastName, $adminEmail);

    if ($stmt->execute()) {
        echo "<p>New admin added successfully.</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Fetch list of admins
$sql = "SELECT AdminID, username, AdminFirstName, AdminLastName, AdminEmail FROM admin";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management</title>
    <style>
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #1E90FF;
            text-decoration: none;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #333;
            color: #fff;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 30px auto;
            padding: 20px;
            background: #444;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
        }
        h1 {
            text-align: center;
            color: #1E90FF;
        }
        form {
            margin-bottom: 20px;
            text-align: center;
        }
        input[type="text"], input[type="password"], input[type="email"] {
            width: 80%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #555;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
        }
        input[type="submit"] {
            background-color: #1E90FF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0a74da;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #555;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Admin Management</h1>

    <!-- Form to add a new admin -->
    <form action="admin.php" method="post">
        <h2>Add New Admin</h2>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <label for="AdminFirstName">First Name:</label>
        <input type="text" id="AdminFirstName" name="AdminFirstName" required><br>
        <label for="AdminLastName">Last Name:</label>
        <input type="text" id="AdminLastName" name="AdminLastName" required><br>
        <label for="AdminEmail">Email:</label>
        <input type="email" id="AdminEmail" name="AdminEmail" required><br>
        <input type="submit" value="Add Admin">
    </form>

    <!-- Table to show list of admins -->
    <h2>List of Admins</h2>
    <table>
        <thead>
            <tr>
                <th>AdminID</th>
                <th>Username</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['AdminID']}</td>
                            <td>{$row['username']}</td>
                            <td>{$row['AdminFirstName']}</td>
                            <td>{$row['AdminLastName']}</td>
                            <td>{$row['AdminEmail']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No admins found</td></tr>";
            }
            ?>
        </tbody>
       
    </table>
        <br>
            <a href="admin_login.php">Back to admin login</a>

</div>
</body>
</html>

<?php
$conn->close();
?>
