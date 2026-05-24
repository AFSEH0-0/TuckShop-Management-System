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
    $supplierName = $_POST['SupplierName'];
    $contactEmail = $_POST['ContactEmail'];
    $supplierAddress = $_POST['SupplierAddress'];
    $totalSales = $_POST['TotalSales'];
    $paymentTerms = $_POST['PaymentTerms'];

    $sql = "INSERT INTO supplier (SupplierName, ContactEmail, SupplierAddress, TotalSales, PaymentTerms)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssis", $supplierName, $contactEmail, $supplierAddress, $totalSales, $paymentTerms);

    if ($stmt->execute()) {
        echo "<p>New supplier added successfully.</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Fetch list of suppliers
$sql = "SELECT SupplierID, SupplierName, ContactEmail, SupplierAddress, TotalSales, PaymentTerms FROM supplier";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Management</title>
    <style>
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
        input[type="text"], input[type="email"], input[type="number"] {
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
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #1E90FF;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Supplier Management</h1>

    <!-- Form to add a new supplier -->
    <form action="supplier.php" method="post">
        <h2>Add New Supplier</h2>
        <label for="SupplierName">Supplier Name:</label>
        <input type="text" id="SupplierName" name="SupplierName" required>
        <label for="ContactEmail">Contact Email:</label>
        <input type="email" id="ContactEmail" name="ContactEmail" required>
        <label for="SupplierAddress">Supplier Address:</label>
        <input type="text" id="SupplierAddress" name="SupplierAddress" required>
        <label for="TotalSales">Total Sales:</label>
        <input type="number" id="TotalSales" name="TotalSales" step="0.01" required><br>
        <label for="PaymentTerms">Payment Terms:</label>
        <input type="text" id="PaymentTerms" name="PaymentTerms" required><br>
        <input type="submit" value="Add Supplier">
    </form>

    <!-- Table to show list of suppliers -->
    <h2>List of Suppliers</h2>
    <table>
        <thead>
            <tr>
                <th>SupplierID</th>
                <th>Supplier Name</th>
                <th>Contact Email</th>
                <th>Supplier Address</th>
                <th>Total Sales</th>
                <th>Payment Terms</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['SupplierID']}</td>
                            <td>{$row['SupplierName']}</td>
                            <td>{$row['ContactEmail']}</td>
                            <td>{$row['SupplierAddress']}</td>
                            <td>{$row['TotalSales']}</td>
                            <td>{$row['PaymentTerms']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No suppliers found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Link to go back to admin login page -->
    <a href="admin_login.php">Back to Admin Login</a>
</div>

</body>
</html>

<?php
$conn->close();
?>
