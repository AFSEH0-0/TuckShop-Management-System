<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales</title>
    <style>
        a{
            color: red;
            text-align: center;
            text-decoration: none;
        }
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
        .form-container {
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            width: 300px;
        }
        input, select {
            margin-bottom: 10px;
            padding: 10px;
            font-size: 16px;
        }
        button {
            padding: 10px;
            font-size: 16px;
            background-color: #1E90FF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0d74d1;
        }
        table {
            border-collapse: collapse;
            width: 100%;
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
        .footer {
            text-align: center;
            padding: 20px;
            background-color: #222;
            color: white;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Sales</h1>
    </header>
    <div class="content">
        <div class="form-container">
            <form action="sales.php" method="POST">
                <label for="TotalSalesAmount">Total Sales Amount:</label>
                <input type="number" step="0.01" id="TotalSalesAmount" name="TotalSalesAmount" required>
                
                <label for="PaymentMethod">Payment Method:</label>
                <input type="text" id="PaymentMethod" name="PaymentMethod" required>
                
                <label for="SupplierID">Supplier ID:</label>
                <select id="SupplierID" name="SupplierID" required>
                    <?php
                    // Fetch SupplierID from supplier table
                    $conn = new mysqli("localhost", "root", "", "tuck-shop");
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    $supplierResult = $conn->query("SELECT SupplierID FROM supplier");
                    while ($row = $supplierResult->fetch_assoc()) {
                        echo "<option value='" . $row['SupplierID'] . "'>" . $row['SupplierID'] . "</option>";
                    }
                    ?>
                </select>

                <label for="CustomerID">Customer ID:</label>
                <select id="CustomerID" name="CustomerID" required>
                    <?php
                    // Fetch CustomerID from customer table
                    $customerResult = $conn->query("SELECT CustomerID FROM customer");
                    while ($row = $customerResult->fetch_assoc()) {
                        echo "<option value='" . $row['CustomerID'] . "'>" . $row['CustomerID'] . "</option>";
                    }
                    ?>
                </select>

                <label for="OrderID">Order ID:</label>
                <select id="OrderID" name="OrderID" required>
                    <?php
                    // Fetch OrderID from orders table
                    $orderResult = $conn->query("SELECT OrderID FROM orders");
                    while ($row = $orderResult->fetch_assoc()) {
                        echo "<option value='" . $row['OrderID'] . "'>" . $row['OrderID'] . "</option>";
                    }
                    ?>
                </select>
                
                <button type="submit">Add Sale</button>
            </form>
        </div>
        
        <h2>Sales List</h2>
        <table>
            <thead>
                <tr>
                    <th>Sale ID</th>
                    <th>Sale Date</th>
                    <th>Total Sales Amount</th>
                    <th>Payment Method</th>
                    <th>Supplier ID</th>
                    <th>Customer ID</th>
                    <th>Order ID</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Insert new sale if form is submitted
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $totalSalesAmount = $_POST['TotalSalesAmount'];
                    $paymentMethod = $_POST['PaymentMethod'];
                    $supplierID = $_POST['SupplierID'];
                    $customerID = $_POST['CustomerID'];
                    $orderID = $_POST['OrderID'];
                    $saleDate = date('Y-m-d H:i:s');

                    $insertSql = "INSERT INTO sales (SaleDate, TotalSalesAmount, PaymentMethod, SupplierID, CustomerID, OrderID) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($insertSql);
                    $stmt->bind_param("sdssii", $saleDate, $totalSalesAmount, $paymentMethod, $supplierID, $customerID, $orderID);

                    if ($stmt->execute()) {
                        echo "<p>New sale added successfully</p>";
                    } else {
                        echo "Error: " . $insertSql . "<br>" . $conn->error;
                    }

                    $stmt->close();
                }

                // Fetch all sales
                $salesResult = $conn->query("SELECT * FROM sales");
                if ($salesResult->num_rows > 0) {
                    while ($row = $salesResult->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row["SaleID"]) . "</td>
                                <td>" . htmlspecialchars($row["SaleDate"]) . "</td>
                                <td>" . htmlspecialchars($row["TotalSalesAmount"]) . "</td>
                                <td>" . htmlspecialchars($row["PaymentMethod"]) . "</td>
                                <td>" . htmlspecialchars($row["SupplierID"]) . "</td>
                                <td>" . htmlspecialchars($row["CustomerID"]) . "</td>
                                <td>" . htmlspecialchars($row["OrderID"]) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No sales found</td></tr>";
                }

                // Close connection
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    <a href="admin_login.php">Go back to admin Dashboard</a>
    </body>
</html>
