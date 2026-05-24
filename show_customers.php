<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer List</title>
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
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px 0;
            color: white;
        }
        th, td {
            border: 1px solid #555;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #555;
        }
        a {
            color: #1E90FF;
            text-decoration: none;
            margin: 20px 0;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Customer List</h1>
    <table>
        <thead>
            <tr>
                <th>CustomerID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone Number</th>
                <th>Customer Address</th>
            </tr>
        </thead>
        <tbody>
            <?php
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);

            // Database connection
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

            // Fetch all customers
            $sql = "SELECT CustomerID, FirstName, LastName, PhoneNumber, CustomerAddress FROM customer";
            $result = $conn->query($sql);

            // Display data in a table
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row["CustomerID"]) . "</td>
                            <td>" . htmlspecialchars($row["FirstName"]) . "</td>
                            <td>" . htmlspecialchars($row["LastName"]) . "</td>
                            <td>" . htmlspecialchars($row["PhoneNumber"]) . "</td>
                            <td>" . htmlspecialchars($row["CustomerAddress"]) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No customers found</td></tr>";
            }

            // Close connection
            $conn->close();
            ?>
        </tbody>
    </table>
    <a href="customer.html">Back to Form</a>
</body>
</html>
