<?php
require 'inc.php';
$sql = "SELECT * FROM plants";
$result = $conn->query($sql);
$sql2 = "SELECT * FROM plants_category";
$result2 = $conn->query($sql2);

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
session_start();

// Check if the "add" action is triggered
if (isset($_GET['action']) && $_GET['action'] == "add") {
    // Make sure the product id is provided
    if (!empty($_GET['id'])) {
        $productId = $_GET['id'];

        // Fetch product details from the database based on the provided id
        $stmt = $conn->prepare("SELECT * FROM PLANTS WHERE plant_id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        // Check if the product exists
        if ($product) {
            // Prepare item array with product details
            $itemArray = array(
                'name' => $product['Name'],
                'id' => $product['plant_id'],
                'quantity' => 1, // You may adjust this based on your needs
                'price' => $product['price'],
                'image' => $product['picture']
            );

            // Check if the cart session variable is already set
            if (!empty($_SESSION["cart_item"])) {
                // Check if the product is already in the cart
                if (in_array($product['plant_id'], array_keys($_SESSION["cart_item"]))) {
                    // Update the quantity if the product is already in the cart
                    $_SESSION["cart_item"][$product['plant_id']]['quantity'] += 1;
                } else {
                    // Add the product to the cart if it's not already in the cart
                    $_SESSION["cart_item"][$product['plant_id']] = $itemArray;
                }
            } else {
                // Set the cart session variable and add the product
                $_SESSION["cart_item"][$product['plant_id']] = $itemArray;
            }
        }
    }
}

// Your HTML and product listing code can go here


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
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>

                    <button class="btn btn-secondary"><a href="http://localhost/opep/clientpage.php/">ALL</a></button>
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
                <button type="button" class="btn btn-dark">
                    <img width="50" height="50" src="https://img.icons8.com/cute-clipart/64/000000/shopping-cart.png" alt="shopping-cart" /></button>

            </div>

        </div>

    </nav>
    <header class="head">

        <div class="overlay">
            <div class="cartbar col-3"></div>
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

                                            <form method="post" action="clientpage.php?action=add&id=<?php echo $row['plant_id']; ?>">
                                                <input type="submit" value="Add to Cart">
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                <?php }
                }

                ?>





            </div>
            <h2>Product Listing</h2>

            <div class="product-list">
                <?php
                // Display products
                while ($row = $result->fetch_assoc()) {
                ?>
                    <div class="product-item">
                        <img src="<?php echo $row['picture']; ?>" alt="<?php echo $row['Name']; ?>">
                        <h3><?php echo $row['Name']; ?></h3>
                        <p>Price: $<?php echo $row['price']; ?></p>
                        <!-- Add-to-cart form -->
                        <form method="post" action="your_cart_page.php?action=add&id=<?php echo $row['plant_id']; ?>">
                            <input type="submit" value="Add to Cart">
                        </form>
                    </div>
                <?php
                }
                ?>
            </div>

            <!-- Your other HTML content -->

        </section>
    </main>
    <script src="./script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>