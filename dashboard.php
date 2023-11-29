<?php
require 'inc.php';

$sql = "SELECT * FROM plants";
$sql2 = "SELECT * FROM plants_category";
$result2 = $conn->query($sql2);
$result = $conn->query($sql);

?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $plantName = isset($_POST["plant-name"]) ? $_POST["plant-name"] : '';

    $price = isset($_POST["price"]) ? $_POST["price"] : '';
    $targetDirectory = "./images/";
    $targetFile = $targetDirectory .  $_FILES["image"]["name"];


    // Check if the file is uploaded successfully
    if (!empty($_FILES["image"]["tmp_name"]) && is_uploaded_file($_FILES["image"]["tmp_name"])) {
        // Check the file's MIME type (adjust as needed)
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $uploadedMimeType = mime_content_type($_FILES["image"]["tmp_name"]);

        if (!in_array($uploadedMimeType, $allowedMimeTypes)) {
            echo "Sorry, only JPEG, PNG, and GIF files are allowed.";
            exit;
        }

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // SQL query to insert data into the 'plants' table using prepared statements
            $insertRequest = "INSERT INTO plants (Name, picture, price) VALUES (?, ?, ?)";

            // Prepare the statement
            $stmt = $conn->prepare($insertRequest);

            // Bind parameters
            $stmt->bind_param("ssi", $plantName, $targetFile, $price);

            // Execute the statement
            if ($stmt->execute()) {
                echo "Plant added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement
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
                <li>LOG OUT</li>
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