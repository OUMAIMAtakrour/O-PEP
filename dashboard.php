<?php
require 'inc.php';

$sql = "SELECT plants.plant_id, plants.Name, plants.picture, plants.price, plants_category.category_name
        FROM plants
        LEFT JOIN plants_category ON plants.cate_gory = plants_category.category_id";
$result = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $plantName = isset($_POST["plant-name"]) ? $_POST["plant-name"] : '';
    $price = isset($_POST["price"]) ? $_POST["price"] : '';

    // Check if the form is submitted and an image is uploaded
    if (isset($_POST['submit']) && isset($_FILES['image'])) {
        $img_name = $_FILES['image']['name'];
        $img_size = $_FILES['image']['size'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $error = $_FILES['image']['error'];

        if ($error === 0) {
            if ($img_size > 125000) {
                $em = "Sorry, your file is too large.";
                echo $em;
                exit();
            } else {
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);
                $allowed_exs = array("jpg", "jpeg", "png");

                if (in_array($img_ex_lc, $allowed_exs)) {
                    $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
                    $img_upload_path = './images/' . $new_img_name;
                    move_uploaded_file($tmp_name, $img_upload_path);

                    // Insert into the 'images' table
                    $insertRequest = "INSERT INTO images(image_url) VALUES (?)";
                    $stmt = $conn->prepare($insertRequest);
                    $stmt->bind_param("s", $new_img_name);

                    if ($stmt->execute()) {
                        echo "Image added successfully!";
                    } else {
                        echo "Error: " . $stmt->error;
                    }

                    $stmt->close();
                    exit();
                } else {
                    $em = "You can't upload files of this type";
                    echo $em;
                    exit();
                }
            }
        } else {
            $em = "Unknown error occurred!";
            echo $em;
            exit();
        }
    }

    // If no image is uploaded, continue with plant information
    if (!empty($_FILES["image"]["tmp_name"]) && is_uploaded_file($_FILES["image"]["tmp_name"])) {
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $uploadedMimeType = mime_content_type($_FILES["image"]["tmp_name"]);

        if (!in_array($uploadedMimeType, $allowedMimeTypes)) {
            echo "Sorry, only JPEG, PNG, and GIF files are allowed.";
            exit;
        }

        $targetDirectory = "./images/";
        $new_img_name = basename($_FILES["image"]["name"]); // Use the original name
        $targetFile = $targetDirectory . $new_img_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $insertRequest = "INSERT INTO plants (Name, picture, price) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertRequest);
            $stmt->bind_param("ssi", $plantName, $new_img_name, $price); // Use $new_img_name

            if ($stmt->execute()) {
                echo "Plant added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "Please choose a file to upload.";
    }
}





if (isset($_POST['delete'])) {
    $stmt = $conn->prepare("DELETE FROM plants WHERE plant_id=?");
    $stmt->bind_param('i', $_POST['id_pr']);
    $stmt->execute();
}

if (isset($_POST['edit_submit'])) {
    // Handle the form submission for editing
    $editPlantId = $_POST['edit_id_pr'];
    $editedName = $_POST['edit_name'];
    $editedPrice = $_POST['edit_price'];

    // SQL query to update the plant information
    $updateQuery = "UPDATE plants SET Name=?, price=? WHERE plant_id=?";

    // Prepare the statement
    $stmt = $conn->prepare($updateQuery);

    // Bind parameters
    $stmt->bind_param("ssi", $editedName, $editedPrice, $editPlantId);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Plant information updated successfully!";
    } else {
        echo "Error updating plant information: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/dash.css?v=<?php echo time(); ?>">
    <title>DASHBOARD</title>
</head>

<body>

    <header class="first">
        <div class="sidebar d-flex justify-content-between align-items-center ">
            <img src="../images/logo-admin.png" alt="" class="img-fluid my-2 col-1 mx-4">
            <ul class="text-light dash-list d-flex  justify-content-evenly col-4">
                <li>USERS</li>
                <li>CATEGORIES</li>
                <li><a href="/OPEP/index.php">LOG OUT</a></li>
            </ul>

        </div>
        <div class="overlay d-flex">

            <h1 class="title text-center  mx-auto">Rooted in Nature's Symphony: A Botanical Ballet of Beauty and Life</h1>
        </div>
    </header>
    <main>

        <section class="section2">
            <form action="" method="POST">
                <input type="hidden" name="id_pr" value="<?= $row["plant_id"] ?>">
                <button type="button" class="btn btn-primary my-5 mx-5" data-bs-toggle="modal" data-bs-target="#exampleModal">Add to catalogue</button>
            </form>
            <div class="contains-items col-11 mx-auto my-3">
                <?php
                while ($row = mysqli_fetch_assoc($result)) :
                ?>
                    <div class="container text-center my-3 mx-4">
                        <div class=" align-items-center col-4">


                            <div class="col-3">
                                <div class="card" style="width: 18rem;">
                                    <img src="<?= $row["picture"] ?>" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $row["Name"] ?></h5>
                                        <p class="card-text"><?php echo $row["price"] ?>$</p>
                                        <p class="card-text">Category: <?php echo $row["category_name"] ?></p>
                                        <form action="" method="POST">
                                            <input type="hidden" name="id_pr" value="<?= $row["plant_id"] ?>">
                                            <input type="submit" name="delete" value="Delete" class="btn btn-primary">
                                            <input type="button" name="edit" value="Edit item" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-name="<?php echo $row['Name']; ?>" data-price="<?php echo $row['price']; ?>">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php
                endwhile
                ?>





            </div>
        </section>
        <?php

        ?>
        <!-- Button trigger modal -->
        <!-- Button trigger modal -->


        <!-- Modal -->
        <!-- Modal for Editing Plant -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Plant Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form for editing plant information -->
                        <form action="" method="POST">
                            <input type="hidden" name="edit_id_pr" id="edit_id_pr">
                            <label for="edit_name">Plant Name</label>
                            <input type="text" class="form-control" id="edit_name" name="edit_name" required>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" aria-label="Text input with dropdown button">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Dropdown</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="#">Separated link</a></li>
                                </ul>
                            </div>
                            <label for="edit_price">Plant Price ($)</label>
                            <input type="number" class="form-control" id="edit_price" name="edit_price" required>

                            <?php
                            // Additional fields for editing other information can be added here
                            ?>

                            <button type="submit" name="edit_submit" class="btn btn-primary mt-3">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>





        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data">

                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Name</span>
                                <input type="text" class="form-control" placeholder="plant" aria-label="Username" aria-describedby="basic-addon1" name="plant-name">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Category</span>
                                <input type="text" class="form-control" placeholder="plant" aria-label="Username" aria-describedby="basic-addon1" name="category">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Price</span>
                                <input type="number" class="form-control" placeholder="00.00$" aria-label="Username" aria-describedby="basic-addon1" name="price">
                            </div>
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="inputGroupFile01">Upload</label>
                                <input type="file" class="form-control" id="inputGroupFile01" name="image" accept="image/*">
                            </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</html>
<?php
?>