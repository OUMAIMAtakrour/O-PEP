<?php
require 'inc.php';
$sql = "SELECT * FROM plants";
$result = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["search"])) {
    $searchTerm = $_GET["search"];
    $sql = "SELECT * FROM plants WHERE Name LIKE '%$searchTerm%'";
    $result = $conn->query($sql);
} else {
    // If no search query is provided, show all plants
    $sql = "SELECT * FROM plants";
    $result = $conn->query($sql);
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
                                            <form action="" method="POST">

                                                <input type="hidden" name="id_pr" value="<?= $row["plant_id"] . '_' . time() ?>">

                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add To Cart</button>
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
        </section>
    </main>
    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>