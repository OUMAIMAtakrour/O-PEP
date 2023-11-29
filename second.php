<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
include 'inc.php';

session_start();
if ($_SESSION['email']) {
    $email = $_SESSION['email'];
    echo $email;
}

if (isset($_POST['01'])) {
    $hight = 01;
} else if (isset($_POST['02'])) {
    $hight = 02;
}
if (isset($hight)) {
    $updateRequet = $conn->prepare("UPDATE persons SET user_role = ? where Email = ? ");
    $updateRequet->bind_param("is", $hight, $email);
    $updateRequet->execute();
    if ($hight == 01) {

        header("location: /OPEP/login.php/");
    } else if ($hight == 02) {
        header("location: /OPEP/login.php/");
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

    <link rel="stylesheet" href="./css/second.css?v=<?php echo time(); ?>">

</head>

<body>
    <section class="d-flex ">
        <div class="division col-6">

            <form action="" method="post" class="text-center">
                <img src="./images/icons8-admin-100.png" alt="" class="col-6 m-auto my-5 d-block">
                <button class="btn btn-outline-dark" type="submit" name="01">ADMIN</button>

            </form>
        </div>
        <div class="division2 col-6 ">

            <form action="" method="post" class="text-center">

                <img src="./images/icons8-users-80.png" alt="" class="col-6 mx-5 my-5 d-block">
                <button class="btn btn-outline-dark" type="submit" name="02">CLIENT</button>
            </form>
        </div>
    </section>

    <!-- <form action="" method="post">
        <input type="hidden" name="hidden_value" value="client">
        <button type="submit" value="client" name="what">CLIENT</button>

    </form>
    <form action="" method="post">
       <input type="hidden" name="hidden_value" value=" -->
    <!-- <button type="submit" value="admin" name="what">ADMIN</button>
    </form> -->

    <?php





    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>