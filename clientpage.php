<?php
require 'inc.php';
$sql = "SELECT * FROM plants";
$result = $conn->query($sql);
$sql2 = "SELECT * FROM plants_category";
$result2 = $conn->query($sql2);
if (isset($_POST['cartbutton'])) {

    header("Location:/OPEP/cart.php/");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["search"])) {
    $searchTerm = $_GET["search"];
    $sql = "SELECT * FROM plants WHERE Name LIKE '%$searchTerm%'";
    $result = $conn->query($sql);
} else {
    // If no search query is provided, show all plants
    $sql = "SELECT * FROM plants";
    $result = $conn->query($sql);
}

// Assuming you have established a database connection ($conn)

// Start or resume the session
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
    <title>CLIENT PAGE</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="../css/client.css?v=<?php echo time(); ?>">

</head>

<body>
    <nav class="navbar navbar-expand-lg ">
        <div class="container-fluid">
            <img src="../images/opep-high-resolution-logo-transparent.png" alt="Logo" width="130" height="60" class="d-inline-block align-text-top mx-3">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse mx-3" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>

                    <button class="btn"><a href="http://localhost/opep/clientpage.php/">ALL</a></button>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown
                        </a>

                        <ul class="dropdown-menu">
                            <form action="" method="post">

                                <li><button class="dropdown-item" type="submit" name="show" value="1">Indoor</button></li>
                                <li><button class="dropdown-item" type="submit" name="show" value="2">Roses</button></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </form>
                        </ul>

                    </li>

                </ul>

                <form class="d-flex mx-5" role="search" method="get">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </form>
                <form action="" method="post">
                    <button type="submit" class="btn btn-dark" name="cartbutton">
                        <img width="50" height="50" src="https://img.icons8.com/cute-clipart/64/000000/shopping-cart.png" alt="shopping-cart" /></button>
                </form>
            </div>

        </div>

    </nav>
    <header class="head">

        <div class="overlay">

            <h1 class="col-12 mx-5">"Embark on a leafy adventure! Explore our online plant
                nursery and bring nature's beauty into your home."</h1>
        </div>

    </header>
    <main>
        <section class="section2">
            <div class="contains-items col-10 mx-auto my-5 ">
                <?php
                function fetchData($group)
                {
                    global $conn;
                    $data = "SELECT * FROM plants WHERE cate_gory = $group";
                    $result2 = $conn->query($data);

                    if ($result2->num_rows > 0) {
                        while ($row = $result2->fetch_assoc()) {
                            echo "<div class='container text-center my-3 mx-4'>
                    <div class='align-items-center col-4'>
                        <div class='col-3'>
                            <div class='card' style='width: 18rem;'>
                                <img src='" . $row["picture"] . "' class='card-img-top' alt='...'>
                                <div class='card-body'>
                                    <h5 class='card-title'>" . $row["Name"] . "</h5>
                                    <p class='card-text'>" . $row["price"] . "$</p>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>";
                        }
                    } else {
                        echo "No data found.";
                    }
                }
                ?>
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['show'])) {
                    $selectedGroup = $_POST['show'];
                    fetchData($selectedGroup);
                }

                ?>
                <?php if (!isset($_POST['show'])) { ?>
                    <?php



                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <div class="container text-center my-3 mx-3">
                            <div class=" align-items-center col-4">


                                <div class="col-3">
                                    <div class="card" style="width: 18rem;">
                                        <img src="<?= $row["picture"] ?>" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $row["Name"] ?></h5>
                                            <p class="card-text"><?php echo $row["cate_gory"] ?></p>
                                            <p class="card-text"><?php echo $row["price"] ?>$</p>

                                            <div class="product-details">
                                                <form method="post" action="">


                                                    <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                <?php }
                }

                ?>





        </section>
    </main>
    <script src="./script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>