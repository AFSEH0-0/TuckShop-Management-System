<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order</title>
    <style>
        
        body {
            background-color: #121212;
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        header {
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        form {
            display: flex;
            flex-direction: column;
            width: 300px;
            margin-top: 20px;
        }
        input, select {
            margin-bottom: 10px;
            padding: 10px;
            font-size: 16px;
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
            color: #00bcd4;
            padding: 15px 25px;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s, color 0.3s;
        }
        a:hover {
            text-decoration: underline;
        }
        h1 {
            margin-top: 20px;
        }
        ul {
            list-style-type: none;
            padding: 0;
            width: 80%;
        }
        li {
            background-color: #444;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .product-name {
            font-size: 1.2em;
        }
        .product-price {
            font-size: 1.2em;
            color: #1E90FF;
        }
        button {
            background-color: #1E90FF;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #0d74d1;
        }
       
    </style>
</head>
<body>
    <header>
    <h1>Products Available</h1>
    </header>
    <ul>
        <li>
            <span class="product-name">Snacks</span>
            <span class="product-price">PKR 60.00</span>
        </li>
        <li>
            <span class="product-name">Cold Drink</span>
            <span class="product-price">PKR 150.00</span>
        </li>
        <li>
            <span class="product-name">Chocolate</span>
            <span class="product-price">PKR 100.00</span>
        </li>
        <li>
            <span class="product-name">Nimko</span>
            <span class="product-price">PKR 20.00</span>
        </li>
    </ul>
    <h1>Place Order</h1>
    <form action="place_order.php" method="POST">
        <label for="CustomerID">Customer ID:</label>
        <input type="number" id="CustomerID" name="CustomerID" required>
        
        <label for="Product">Product:</label>
        <input type="text" id="Product" name="Product" required>
     
        <label for="TotalAmount">Total Amount:</label>
        <input type="number" step="0.01" id="TotalAmount" name="TotalAmount" required>
        
        <button type="submit">Place Order</button>
    </form>
    
    <h1>Order List</h1>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer ID</th>
                <th>Product</th>
                <th>Order Date</th>
                <th>Total Amount</th>
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

            // Insert new order if form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $customerID = $_POST['CustomerID'];
                $product = $_POST['Product'];
                $totalAmount = $_POST['TotalAmount'];
                $orderDate = date('Y-m-d H:i:s');

                $sql = "INSERT INTO orders (CustomerID, Product, OrderDate, TotalAmount) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("issd", $customerID, $product, $orderDate, $totalAmount);

                if ($stmt->execute()) {
                    echo "<p>New order placed successfully</p>";
                } else {
                    echo "Error: " . $stmt->error;
                }

                $stmt->close();
            }

            // Fetch all orders
            $sql = "SELECT OrderID, CustomerID, Product, OrderDate, TotalAmount FROM orders";
            $result = $conn->query($sql);

            // Display data in a table
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row["OrderID"]) . "</td>
                            <td>" . htmlspecialchars($row["CustomerID"]) . "</td>
                            <td>" . htmlspecialchars($row["Product"]) . "</td>
                            <td>" . htmlspecialchars($row["OrderDate"]) . "</td>
                            <td>" . htmlspecialchars($row["TotalAmount"]) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No orders found</td></tr>";
            }

            // Close connection
            $conn->close();
            ?>
        </tbody>
    </table>
        <a href="index.html">Back to Homepage</a>
</body>
</html>
