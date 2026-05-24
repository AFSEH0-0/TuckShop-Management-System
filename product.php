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
    $productName = $_POST['ProductName'];
    $price = $_POST['Price'];
    $stockQuantity = $_POST['StockQuantity'];
    $categoryId = $_POST['CategoryID'];
    $supplierId = $_POST['SupplierID'];

    $sql = "INSERT INTO product (ProductName, Price, StockQuantity, CategoryID, SupplierID)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdiii", $productName, $price, $stockQuantity, $categoryId, $supplierId);

    if ($stmt->execute()) {
        echo "<p>New product added successfully.</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Fetch list of categories and suppliers for form selection
$categorySql = "SELECT CategoryID, CategoryName FROM category";
$categoryResult = $conn->query($categorySql);

$supplierSql = "SELECT SupplierID, SupplierName FROM supplier";
$supplierResult = $conn->query($supplierSql);

// Fetch list of products
$productSql = "SELECT ProductID, ProductName, Price, StockQuantity, CategoryID, SupplierID FROM product";
$productResult = $conn->query($productSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
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
        input[type="text"], input[type="number"], select {
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
            color: #1E90FF;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 20px;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Product Management</h1>

    <!-- Form to add a new product -->
    <form action="product.php" method="post">
        <h2>Add New Product</h2>
        <label for="ProductName">Product Name:</label>
        <input type="text" id="ProductName" name="ProductName" required><br>
        <label for="Price">Price:</label>
        <input type="number" step="0.01" id="Price" name="Price" required><br>
        <label for="StockQuantity">Stock Quantity:</label>
        <input type="number" id="StockQuantity" name="StockQuantity" required><br>
        <label for="CategoryID">Category:</label>
        <select id="CategoryID" name="CategoryID" required>
            <option value="">Select Category</option>
            <?php
            while ($categoryRow = $categoryResult->fetch_assoc()) {
                echo "<option value='{$categoryRow['CategoryID']}'>{$categoryRow['CategoryName']}</option>";
            }
            ?>
        </select><br>
        <label for="SupplierID">Supplier:</label>
        <select id="SupplierID" name="SupplierID" required>
            <option value="">Select Supplier</option>
            <?php
            while ($supplierRow = $supplierResult->fetch_assoc()) {
                echo "<option value='{$supplierRow['SupplierID']}'>{$supplierRow['SupplierName']}</option>";
            }
            ?>
        </select><br>
        <input type="submit" value="Add Product">
    </form>

    <!-- Table to show list of products -->
    <h2>List of Products</h2>
    <table>
        <thead>
            <tr>
                <th>ProductID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Stock Quantity</th>
                <th>CategoryID</th>
                <th>SupplierID</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($productResult->num_rows > 0) {
                // Output data of each row
                while ($row = $productResult->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['ProductID']}</td>
                            <td>{$row['ProductName']}</td>
                            <td>{$row['Price']}</td>
                            <td>{$row['StockQuantity']}</td>
                            <td>{$row['CategoryID']}</td>
                            <td>{$row['SupplierID']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No products found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Link to go back to admin login -->
    <a href="admin_login.php">Back to Admin Login</a>
</div>

</body>
</html>

<?php
$conn->close();
?>
