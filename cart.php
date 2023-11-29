<?php
require 'inc.php';
session_start();

// Check if the "Add to Cart" button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    // Get product details from the form
    $product_id = 1;  // Replace with your actual product ID
    $product_quantity = 1;  // Replace with your actual product quantity

    // Retrieve product details from the database
    $product_query = "SELECT name, price FROM PLANTS WHERE plant_id = ?";
    $stmt = $conn->prepare($product_query);
    $stmt->bind_param("i", $plant_id);
    $stmt->execute();
    $stmt->bind_result($Name, $price);
    $stmt->fetch();
    $stmt->close();

    // Create an item array with dynamic product details
    $itemArray = array(
        'id' => $plant_id,
        'name' => $Name,
        'price' => $price,
        'quantity' => $product_quantity
    );

    // Initialize the cart if it doesn't exist
    if (!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = array();
    }

    // Check if the product is already in the cart
    $productExists = false;
    foreach ($_SESSION["cart"] as &$item) {
        if ($item['id'] == $plant_id) {
            $item['quantity'] += $product_quantity;
            $productExists = true;
        }
    }

    // If the product is not in the cart, add it
    if (!$productExists) {
        $_SESSION["cart"][] = $itemArray;
    }
}

?>



<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="./css/cart.css">
</head>

<body>
    <div class="cart-summary">
        <h2>Shopping Cart</h2>
        <?php
        // Display the contents of the cart in a table
        if (!empty($_SESSION["cart"])) {
            echo "<table class=' table-striped  '>
           
            <tr class='table-success col-2'>
                       
      <th scope='col-3'>Product</th>
      <th scope='col-3'>Quantity</th>
      <th scope='col-6'>Price</th>
                    </tr>";
            foreach ($_SESSION["cart"] as $item) {
                echo "<tr>
                        <td scope='col-3'>{$item['name']}</td>
                        <td>{$item['quantity']}</td>
                        <td>$ {$item['price']}</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Your cart is empty.</p>";
        }
        ?>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>